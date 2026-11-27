<?php

declare(strict_types=1);
use App\Helpers\ResponseHelper;
use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\EnsureClientOwnership;
use App\Http\Middleware\UserAccessibility;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', api: __DIR__.'/../routes/api.php', commands: __DIR__.'/../routes/console.php', health: '/up', then: function (): void {
        Route::middleware('api')->prefix('api/v1')->name('v1.')->group(base_path('routes/api.php'));
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['admin.access' => UserAccessibility::class, 'active.user' => EnsureActiveUser::class, 'client.owner' => EnsureClientOwnership::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn (Request $request): bool => $request->is('api/*') || $request->expectsJson());
        $exceptions->render(function (Throwable $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }
            $status = match (true) {
                $exception instanceof AuthenticationException => 401,
                $exception instanceof ModelNotFoundException => 404,
                $exception instanceof ValidationException => 422,
                $exception instanceof QueryException && in_array((string) $exception->getCode(), ['23505', '23503', '23000'], true) => 409,
                $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
                default => 500,
            };
            $messages = [
                401 => ['ar' => 'بيانات الدخول غير صحيحة أو الجلسة منتهية', 'en' => 'Invalid credentials or authentication expired'],
                403 => ['ar' => 'غير مسموح بتنفيذ العملية', 'en' => 'This operation is not allowed'],
                404 => ['ar' => 'البيانات غير موجودة', 'en' => 'Resource not found'],
                409 => ['ar' => 'تعارض في البيانات أو حالة العملية', 'en' => 'Data or operation state conflict'],
                422 => ['ar' => 'يرجى مراجعة البيانات المدخلة', 'en' => 'Please review the submitted data'],
                429 => ['ar' => 'محاولات كثيرة، حاول لاحقاً', 'en' => 'Too many attempts, please try again later'],
            ];
            $response = ResponseHelper::error($messages[$status] ?? null, null, $status, match ($status) {
                401 => 'UNAUTHENTICATED',403 => 'FORBIDDEN',404 => 'NOT_FOUND',409 => 'CONFLICT',422 => 'VALIDATION_ERROR',429 => 'TOO_MANY_REQUESTS',default => 'INTERNAL_ERROR',
            });
            if ($exception instanceof HttpExceptionInterface) {
                $response->headers->add($exception->getHeaders());
            }

            return $response;
        });
    })->create();
