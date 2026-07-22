<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Helpers\ResponseHelper;
use App\Repositories\Client\MainRepository;

class MainService
{
    public function __construct(
        protected MainRepository $mainRepository,
        protected ContactMessageHelper $contactMessageHelper
    ) {}

    public function getAllActiveData()
    {
        return $this->mainRepository->getAllActiveData();
    }

    public function getSiteTheme()
    {
        return $this->mainRepository->getSiteTheme();
    }

    public function getSiteMedia()
    {
        return $this->mainRepository->getSiteMedia();
    }

    public function stream(object $siteMedia)
    {
        // $media_details = $this->mainRepository->findById($id);

        // if(!$media_details){
        //     return false;
        // }

        if (!str_starts_with($siteMedia->mime_type, 'video/')) {
            return ResponseHelper::error(
                $siteMedia,
                [
                    'en' => "This file is not a video",
                    'ar' => "This file is not a video",
                ],
                400);
        }

        $relativePath = ltrim('api/'.$siteMedia?->file_path, '/');
        $path = public_path($relativePath);

        if (!file_exists($path)) {
            return ResponseHelper::error(
                $path,
                [
                    'en' => "File not found on disk: $path",
                    'ar' => "File not found on disk: $path",
                ],
                400);
        }

        $size     = filesize($path);
        $mimeType = $siteMedia->mime_type;

        if (request()->hasHeader('Range')) {
            return $this->handleRangeRequest($path, $size, $mimeType);
        }

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 65536);
                flush();
            }
            fclose($stream);
        }, 200, [
            'Content-Type'   => $mimeType,
            'Content-Length' => $size,
            'Accept-Ranges'  => 'bytes',
            'Cache-Control'  => 'no-cache',
        ]);
    }

    private function handleRangeRequest(string $path, int $size, string $mimeType)
    {
        preg_match('/bytes=(\d+)-(\d*)/', request()->header('Range'), $matches);

        $start = (int) $matches[1];
        $end   = isset($matches[2]) && $matches[2] !== ''
                    ? (int) $matches[2]
                    : $size - 1;

        $chunkSize = $end - $start + 1;

        return response()->stream(function () use ($path, $start, $chunkSize) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);
            $remaining = $chunkSize;

            while (!feof($stream) && $remaining > 0) {
                $toRead = min(65536, $remaining);
                echo fread($stream, $toRead);
                $remaining -= $toRead;
                flush();
            }

            fclose($stream);
        }, 206, [
            'Content-Type'   => $mimeType,
            'Content-Range'  => "bytes $start-$end/$size",
            'Content-Length' => $chunkSize,
            'Accept-Ranges'  => 'bytes',
        ]);
    }

    // sendNotification Funtion To Send Notification by email and sms
    // public function sendNotification(array $notification_details )
    // {

    //     try {

    //         $qiwa_eos_request_id= $notification_details['qiwa_eos_request_id'];
    //         $next_approver_number = $notification_details['next_approver_number'];
    //         $login_user = $notification_details['login_user'];
    //         $type = $notification_details['type'];
    //         $message = $notification_details['message'];

    //         // Check if theres a next approver or not

    //         if (isset($next_approver_number)) {


    //             // Get person ID

    //             $person_id = $this->contactInfoProvider->GetPersonID($next_approver_number);


    //             // Get employee phone number
    //             $phone_number = $this->contactInfoProvider->GetPhoneEmpFromPersonId($person_id);


    //             // Get employee phone number
    //             $email_address = $this->contactInfoProvider->GetEmailEmployee($next_approver_number);



    //             $notification_details['mail_to'] = $email_address?->email;
    //             $notification_details['next_approver_name'] = $email_address->full_name ?? null;


    //             // Initialize status tracking
    //             $smsSent = false;
    //             $emailSent = false;

    //             // Send SMS message for approval num

    //             // Try to send SMS
    //             // try {
    //             //     // Send SMS message for approval num

    //             //     if($notification_details['role'] != 6){
    //             //         // $smsSent = $this->smsVerifyHelper->sendSMS($phone_number, $message);
    //             //     }

    //             //     // Check if message sent or not

    //             //     if ($smsSent) {
    //             //     } else {
    //             //     }
    //             // } catch (\Exception $exception) {
    //             // }


    //             // Try to send email
    //             try {
    //                 if($type == 'Approver'){
    //                     $emailSent = $this->approverEmailHelper->sendApproverNotify($notification_details);
    //                 } else {
    //                     $emailSent = $this->approverEmailHelper->sendOwnerNotify($notification_details);
    //                 }

    //             } catch (\Exception $exception) {
    //             }


    //             // Check the overall status and handle accordingly
    //             if (!$emailSent) {
    //                 $status = [
    //                     // 'sms' => $smsSent ? 'success' : 'failure',
    //                     'email' => $emailSent ? 'success' : 'failure',
    //                 ];
    //                 return "failure";
    //             }


    //             return "Done";
    //         } else {
    //             return "warning";
    //         }
    //     } catch (\Exception $exception) {
    //         throw $exception;
    //     }
    // }


}
