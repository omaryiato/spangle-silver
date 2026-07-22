<?php

namespace App\Repositories\Client;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderDetail;

class MainOrderRepository
{

    public function getUserOrders($user_id)
    {
        return Order::where('user_id', $user_id)->with([
            'user',
            'address',
            'shipping',
            'details',
            'details.variant',
            'details.variant.color',
            'details.variant.size',
            'user',
        ])->get();
    }

    public function addNewOrder($order_request)
    {
        return Order::create($order_request);
    }

    public function addNewCouponUsage($use_coupon)
    {
        return CouponUsage::create($use_coupon);
    }
    public function checkCouponUsageValidity($coupon_id, $user_id)
    {
        $coupon = Coupon::where('id', $coupon_id)->get();

        $totalUsed = CouponUsage::where('coupon_id', $coupon_id)->count();

        $userUsed = CouponUsage::where('coupon_id', $coupon_id)
            ->where('user_id', $user_id)
            ->count();

        if ($totalUsed >= $coupon->max_usage) {
            return true;
        }

        if ($userUsed >= 1) {
            return true;
        }

        return false;
    }

    // addRequestDetail Funtion To Add new Request
    public function addOrderDetails(Order $order_request, array $details)
    {
        return $order_request->details()->createMany($details);
    }

    // prepareNotificationDetails Funtion To Prepare Notification Details
    // public function prepareNotificationDetails($request_id, $approver_role, $action_by, $notification_type, $notes)
    // {

    //     if($notification_type == 'Owner'){

    //         $request_details = AjmiSubcontractorRequests::find($request_id);


    //         // --- Step 16 ->  Handle notification message   ---
    //         $notification_message = "Request #$request_id for project #{$request_details->project_number} has been rejected, by #$action_by.\n"
    //                     . "تم رفض الطلب رقم #$request_id للمشروع رقم #{$request_details->project_number} من خلال  #$action_by .";

    //         $owner = collect(DB::select("SELECT ACTION_BY as action_by
    //                                         FROM AJMI_SUBCONTRACTOR_ACTION_HISTORY
    //                                         WHERE SUBCONTRACTOR_REQUEST_ID = :request_id
    //                                         AND APPROVER_LEVEL = 0
    //                                     ", ["request_id" => $request_id]))->first()->action_by;

    //             // --- Step 17 ->  Handle notification details   ---
    //             $notification_details = [
    //                 'request_id' => $request_id,
    //                 'project_number' => $request_details->project_number,
    //                 'notes' => $notes,
    //                 'next_approver_number' => $owner,
    //                 'message' => $notification_message,
    //                 'login_user' => $action_by,
    //                 'type' => $notification_type ,
    //             ];

    //             // --- Step 18 -> Call notification function    ---
    //             return $this->mainSubcontractorRepository->sendNotification($notification_details);
    //     }

    //     $current_approver = AjmiSubcontractorActionHistory::select(
    //                                                         'approver_level as current_approver_level'
    //                                                     )
    //                                                     ->where([
    //                                                         'subcontractor_request_id'    => $request_id,
    //                                                         'approver_role' => $approver_role,
    //                                                     ])
    //                                                     ->where('approver_level', '!=', 0)
    //                                                     ->orderBy('approver_level')
    //                                                     ->first();

    //     $current_approver_level = $current_approver->current_approver_level ?? null;
    //     $current_approver_role = $approver_role;

    //     $next_approver = AjmiSubcontractorActionHistory::select(
    //                                                 'approver_level as next_approver_level',
    //                                                 'approver_role as next_approver_role',
    //                                             )
    //                                             ->where('subcontractor_request_id', $request_id)
    //                                             ->where('approver_level', '>', $current_approver_level)
    //                                             ->orderBy('approver_level')
    //                                             ->first();

    //     $next_approver_level = $next_approver?->next_approver_level ?? null;
    //     $next_approver_role  = $next_approver?->next_approver_role ?? null;


    //     $request_details = AjmiSubcontractorRequests::find($request_id);

    //     $approvers = [];
    //     $role_column = null;
    //     $role_name   = null;

    //     /*
    //     |--------------------------------------------------------------------------
    //     | 1) تحديد مصدر البيانات حسب الدور
    //     |--------------------------------------------------------------------------
    //     */
    //     if (in_array($next_approver_role, ['manger', 'regional_manager', 'Deputy GM (Tech)'])) {

    //         if ($next_approver_role == 'manger') {
    //             $role_column = 'MGR_NUMBER';
    //         } elseif ($next_approver_role == 'regional_manager') {
    //             $role_column = 'RGNL_NUMBER';
    //         } elseif ($next_approver_role == 'Deputy GM (Tech)') {
    //             $role_column = 'DUPTY_NUMBER';
    //         }

    //         $rows = DB::select(
    //             "SELECT DISTINCT {$role_column} AS employee_number
    //             FROM xxpp_emp_cc_apr_detail
    //             WHERE COST_CENTER = :cost_center",
    //             ['cost_center' => $request_details->project_number]
    //         );

    //         $approvers = collect($rows)
    //             ->pluck('employee_number')
    //             ->filter()
    //             ->unique()
    //             ->values()
    //             ->toArray();

    //     } elseif (in_array($next_approver_role, ['executive_manager', 'technical_office', 'cost_management_department', 'Financial'])) {

    //         if ($next_approver_role == 'executive_manager') {
    //             $role_name = 'executive_manager';
    //         } elseif ($next_approver_role == 'technical_office') {
    //             $role_name = 'technical_office';
    //         } elseif ($next_approver_role == 'cost_management_department') {
    //             $role_name = 'cost_management_department';
    //         } elseif ($next_approver_role == 'Financial') {
    //             $role_name = 'Financial';
    //         }

    //         $row = DB::selectOne("SELECT DBMS_LOB.SUBSTR(employees, 4000, 1) AS employees_csv
    //                                 FROM selfservice.self_service_user_roles
    //                                 WHERE process_role = :role_name",
    //                                 ['role_name' => $role_name]
    //                             );

    //         if (!empty($row?->employees_csv)) {
    //             $approvers = array_map(
    //                 'trim',
    //                 explode(',', $row->employees_csv)
    //             );
    //         }
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | 2) إرسال الإشعارات لكل Approver
    //     |--------------------------------------------------------------------------
    //     */
    //     if (!empty($approvers)) {

    //         foreach ($approvers as $approver_number) {

    //             $notification_message =
    //                 "You have new request #$request_id for project #{$request_details->project_number}.\n"
    //             . "لديك طلب رقم #$request_id للمشروع رقم #{$request_details->project_number} ";

    //             $notification_details = [
    //                 'request_id'   => $request_id,
    //                 'project_number'      => $request_details->project_number,
    //                 'notes'                => $notes,
    //                 'next_approver_number' => $approver_number,
    //                 'message'              => $notification_message,
    //                 'login_user'           => $action_by,
    //                 'type'                 => $notification_type,
    //             ];

    //             $this->mainSubcontractorRepository->sendNotification($notification_details);
    //         }
    //         return true;
    //     } else {
    //         return true;
    //     }
    // }

}
