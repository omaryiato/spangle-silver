<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
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
