<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\SmsVerifyHelper;
use App\Helpers\ApproverEmailHelper;



class MainControlPanelRepository
{

    protected $smsVerifyHelper;
    protected $approverEmailHelper;


    public function __construct(
        SmsVerifyHelper $smsVerifyHelper,
        ApproverEmailHelper $approverEmailHelper,)
    {
        $this->smsVerifyHelper = $smsVerifyHelper;
        $this->approverEmailHelper = $approverEmailHelper;

    }


    // controlPanelSystem Funtion To to Check User Validity
    public function controlPanelSystem($hashkey, $ip_address, $source, $p_action_type, $user_role)
    {
        try {

            $user_validation = collect(DB::select("SELECT xxajmi_sshr_emp_absence.xxajmi_user_micro_servc_validn_n('$hashkey') AS User_Validate FROM dual"))->first();

            $user_validation->function_name_query = '';
            $user_validation->session_expiry_date = '';
            $user_validation->server_sys_date = '';
            $user_validation->session_time_out = '';
            $user_validation->service_id = 1;
            $user_validation->approver_id = '';
            $user_validation->user_role = isset($user_role) ? $user_role : $user_validation->user_role;

            // $user_role = decrypt(Cookie::get('portal_shared_role_type'));
            // $user_role = Cookie::get('portal_shared_role_type');
            // $user_validation->service_id = Cookie::get('portal_service_id') ?? 112;

            // $login_user_otp = collect(DB::select(" SELECT attribute2 AS otp_code, ATTRIBUTE3 AS login_time FROM PER_PEOPLE_X
            //                                             WHERE employee_number = '$user_validation->emp_no'"))->first();
            // $otp = str_pad($login_user_otp->otp_code, 5, '0', STR_PAD_LEFT);
            // $emp_no = str_pad($user_validation->emp_no, 6, '0', STR_PAD_LEFT);
            // $datetime = date('YmdHis', strtotime($login_user_otp->login_time));
            // $user_validation->qr_code = $otp . $hashkey . $emp_no . $datetime;

            if (trim($user_validation->status) == 'valid') {

                // getSessionInfo Funtion To get session details
                $session_info = $this->getSessionInfo($user_validation->emp_no, $source, $ip_address, $p_action_type);
                $user_validation->session_expiry_date = $session_info['session_expiry_date'];
                $user_validation->server_sys_date = $session_info['server_sys_date'];
                $user_validation->session_time_out = $session_info['session_time_out'];

                // // Get Approver ID
                // $approver_info = collect(DB::select("SELECT ID, funcnamequery  FROM XXAJMI_PORTAL_LEV_APPV WHERE LEVEL_APPROVER_KEY = '$user_validation->user_role' AND SERVICE_ID =  $user_validation->service_id "))->first();

                // $user_validation->approver_id = $approver_info->id ?? '';
                // $user_validation->function_name_query  =  $approver_info->funcnamequery ?? '';
            }

            return $user_validation;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // checkIPAddress Funtion To to Check User Validity
    public function checkIPAddress($employee_number, $hashkey, $ip_address, $source, $p_action_type, $user_role)
    {
        try {

            $user_validation = collect(DB::select("SELECT apps.XXPP_EMP_STATUS_CHECK(:employee_number) FROM dual", [
                'employee_number' => $employee_number
            ]))->first();

            if ($user_validation == 'Active') {

                return true;
            }

            return false;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // getSessionInfo Funtion To get session details
    public function getSessionInfo($emp_no, $source, $ip_address, $p_action_type)
    {

        try {
            $service_session_timeout = collect(DB::select("SELECT  SERVICE_SESSION_TIMEOUT AS service_session_timeout  FROM AJMI_SERVICES WHERE NAME like '%e-Control-Panel%'"))->first()->service_session_timeout;

            DB::statement("BEGIN xxajmi_sshr_emp_absence.xxajmi_global_portal_authen('$emp_no', '$source', '$ip_address', $service_session_timeout, '$p_action_type'); END;");
            $session_expiry_date = collect(DB::select("SELECT TO_CHAR(SESSION_EXPIRY_DATE, 'HH24:MI:SS') AS session_expiry_date
                                                FROM ticket_login_portal_sessn
                                                WHERE employee_number = '$emp_no'"))->first()->session_expiry_date;

            $server_sys_date = collect(DB::select("SELECT TO_CHAR(SYSDATE, 'HH24:MI:SS')  AS current_date FROM DUAL"))->first()->current_date;

            $session_info = [
                'session_time_out' => $service_session_timeout,
                'session_expiry_date' => $session_expiry_date,
                'server_sys_date' => $server_sys_date,
            ] ;
            return $session_info ;
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public static function IncreaseSessionTimeout($emp_num)
    {

        try {
            $session_expiry_date = collect(DB::select("SELECT CASE
                                                                WHEN SESSION_EXPIRY_DATE <= SYSDATE + INTERVAL '3' MINUTE
                                                                    AND SESSION_EXPIRY_DATE > SYSDATE
                                                                THEN 'EXPIRING_SOON'
                                                                ELSE 'OK'
                                                            END AS session_status
                                                        FROM ticket_login_portal_sessn
                                                        WHERE employee_number = '$emp_num'"))->first()->session_status;

            if($session_expiry_date == "OK"){
                return;
            }

            $ip_address = null;
            // Check if HTTP_X_FORWARDED_FOR exists (may be set by proxy or load balancer)
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip_address =  trim($ipList[0]); // Return the first IP address in the list
            }

            // Check if HTTP_CLIENT_IP exists
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip_address =  $_SERVER['HTTP_CLIENT_IP'];
            }

            // Default to REMOTE_ADDR (direct client IP)
            if (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip_address =  $_SERVER['REMOTE_ADDR'];
            }

            $source = 'WEB';
            $p_action_type = 'add';

            $service_session_timeout = collect(DB::select("SELECT  SERVICE_SESSION_TIMEOUT AS service_session_timeout  FROM AJMI_SERVICES WHERE NAME like '%e-Control-Panel%'"))->first()->service_session_timeout;

            DB::statement("BEGIN xxajmi_sshr_emp_absence.xxajmi_global_portal_authen('$emp_num', '$source', '$ip_address', $service_session_timeout, '$p_action_type'); END;");

            return;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // sendNotification Funtion To Send Notification by email and sms
    public function sendNotification(array $notification_details )
    {

        try {

            $qiwa_eos_request_id= $notification_details['qiwa_eos_request_id'];
            $next_approver_number = $notification_details['next_approver_number'];
            $login_user = $notification_details['login_user'];
            $type = $notification_details['type'];
            $message = $notification_details['message'];

            Log::channel('sendnotification')->info("Step 1 -> Check if theres a next approver or not ");
            // Check if theres a next approver or not

            if (isset($next_approver_number)) {


                Log::channel('sendnotification')->debug("Step 2 -> Get person ID for " . $next_approver_number . "");
                // Get person ID

                // $person_id = $this->contactInfoProvider->GetPersonID($next_approver_number);


                // Log::channel('sendnotification')->debug("Step 2 -> Get employee phone number for #$person_id");
                // Get employee phone number
                // $phone_number = $this->contactInfoProvider->GetPhoneEmpFromPersonId($person_id);

                // Log::channel('sendnotification')->info("Step 3-> Get approver email address ($phone_number)");

                // Get employee phone number
                // $email_address = $this->contactInfoProvider->GetEmailEmployee($next_approver_number);


                Log::channel('sendnotification')->debug("Step 4 -> Send SMS message for approval num #" .$next_approver_number . "");

                // $notification_details['mail_to'] = $email_address?->email;
                $notification_details['next_approver_name'] = $email_address->full_name ?? null;


                // Initialize status tracking
                $smsSent = false;
                $emailSent = false;

                // Log::channel('sendnotification')->debug("Step 5 -> Send SMS message for approval num #$next_approver_number on phone number ($phone_number) ");
                // Send SMS message for approval num

                // Try to send SMS
                try {
                    Log::channel('sendnotification')->debug("Step 6 ->Call  sendSMS to send SMS");
                    // Send SMS message for approval num

                    if($notification_details['role'] != 6){
                        // $smsSent = $this->smsVerifyHelper->sendSMS($phone_number, $message);
                    }

                    Log::channel('sendnotification')->debug("Step 7 -> Check if message sent or not");
                    // Check if message sent or not

                    if ($smsSent) {
                        // Log::channel('sendnotification')->info("Step 8 -> SMS sent successfully to: ($phone_number)");
                    } else {
                        // Log::channel('sendnotification')->error("Step 8 -> Failed to send SMS to: ($phone_number)");
                    }
                } catch (\Exception $exception) {
                    // Log::channel('sendnotification')->error("Step 8 -> Error sending SMS to ($phone_number): " . $exception->getMessage());
                }

                // Log::channel('sendnotification')->debug("Step 9 -> Send email message for approval num #$next_approver_number on email address (" . $email_address?->email  . ") ");
                // Send email message for approval num

                // Try to send email
                try {
                    Log::channel('sendnotification')->debug("Step 10 ->Call  sendNotification to send email");
                    // Send email message for approval num

                    if($type == 'Approver'){
                        Log::channel('sendnotification')->debug("Step 6 ->" . $type );
                        $emailSent = $this->approverEmailHelper->sendApproverNotify($notification_details);
                    } else {
                        Log::channel('sendnotification')->debug("Step 6 ->" . $type );
                        $emailSent = $this->approverEmailHelper->sendOwnerNotify($notification_details);
                    }

                    // Log::channel('sendnotification')->debug("Step 11 -> Send email message for approval num #{$email_ditals['employee_number']} on email address ($email_address) ");

                    Log::channel('sendnotification')->debug("Step 11 -> Check if email sent or not");
                    // Check if message sent or not

                    if ($emailSent) {
                        // Log::channel('sendnotification')->info("Step 12 -> Email sent successfully to: (" . $email_address?->email . ")");
                    } else {
                        // Log::channel('sendnotification')->error("Step 12 -> Failed to send email to: (" . $email_address?->email . ")");
                    }
                } catch (\Exception $exception) {
                    // Log::channel('sendnotification')->error("Step 12 -> Error sending email to (" . $email_address?->email  . "): " . $exception->getMessage());
                }

                Log::channel('sendnotification')->debug("Step 13 -> Check the overall status and handle accordingly");

                // Check the overall status and handle accordingly
                if (!$smsSent || !$emailSent) {
                    $status = [
                        'sms' => $smsSent ? 'success' : 'failure',
                        'email' => $emailSent ? 'success' : 'failure',
                    ];
                    Log::channel('sendnotification')->warning("Step 14 -> Request No: ( $qiwa_eos_request_id ) submitted by: ($login_user)  with issues: " . json_encode($status));
                    return "warning";
                }

                Log::channel('sendnotification')->info("Step 14 -> Request No: ( $qiwa_eos_request_id ) submitted by: ($login_user) and ($next_approver_number) notified successfully.");

                // If everything is successful
                return "Done";
            } else {
                Log::channel('sendnotification')->warning("Step 2 -> Order No: ( $qiwa_eos_request_id ) submitted by: ($login_user), but there were issues with notifications. ");
                return "warning";
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // getAllActiveEmployeeList Funtion To Get All Active Employees
    public function getAllActiveEmployeeList()
    {

        try {
            return collect(DB::select("SELECT
                                            X.EMPLOYEE_NUMBER AS employee_number,
                                            X.FULL_NAME,
                                            D.EMP_ARABIC_NAME,
                                            D.JOB_NAME,
                                            D.JOB_NAME_ARB,
                                            D.COST_CENTER,
                                            (SELECT DESCRIPTION
                                                FROM apps.fnd_flex_values_vl ffvl
                                                WHERE ffvl.flex_value_set_id = '1014957'
                                                AND ffvl.FLEX_VALUE = D.COST_CENTER) AS COST_CENTER_NAME
                                        FROM PER_PEOPLE_X X
                                        LEFT JOIN xxpp_emp_cc_apr_detail D
                                            ON D.EMPLOYEE_NUMBER = X.EMPLOYEE_NUMBER
                                        WHERE NVL(X.CURRENT_EMPLOYEE_FLAG, 'N') = 'Y'"));

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getEmployeeInformation Funtion To Get Employee Information
    public function getEmployeeInformation($employee_number)
    {
        try {
            return collect(DB::select("SELECT xx_get_employee_by_iqama(:iqama_number, :employee_number) FROM DUAL",
                                        [
                                            "iqama_number" => null,
                                            "employee_number" => $employee_number,
                                        ]))->first();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getEmployeeAssignedFeature Funtion To Get Employee Assigned Feature
    public function getEmployeeAssignedFeature($service_id,$employee_number,$role_id)
    {
        try {

            $allowed_employee_feature = DB::table("AJMI_EMPLOYEE_FEATURES")
                                            ->where("EMPLOYEE_NUMBER", $employee_number)
                                            ->where("ALLOW", 1)
                                            ->pluck("FEATURE_ID")
                                            ->toArray();

            $allowed_role_feature = DB::table("AJMI_ROLE_FEATURES")
                                        ->where("ROLE_ID", $role_id)
                                        ->where("ALLOW", 1)
                                        ->pluck("FEATURE_ID")
                                        ->toArray();

            $feature_ids = array_unique(array_merge($allowed_employee_feature, $allowed_role_feature));

            if (empty($feature_ids)) {
                return collect();
            }

            $ids_string = implode(',', $feature_ids);

            $all_feature_ids = DB::select("SELECT DISTINCT ID FROM AJMI_FEATURES
                                            START WITH ID IN ($ids_string)
                                            CONNECT BY PRIOR PARENT_ID = ID
                                        ");

            $final_ids = array_map(fn($row) => $row->id, $all_feature_ids);

            return DB::table("AJMI_FEATURES")
                        ->whereIn("ID", $final_ids)
                        ->where("SERVICE_ID", $service_id)
                        ->whereNull('DELETED_AT')
                        ->select(
                            'ID as feature_id',
                            'FEATURE_EN_NAME',
                            'FEATURE_AR_NAME',
                            'FEATURE_CODE',
                            'FEATURE_TYPE',
                            'FEATURE_STATUS',
                            'IS_DEFAULT AS feature_is_default',
                            'PARENT_ID AS feature_parent_id',
                            'created_by',
                            'created_at',
                            'updated_by',
                            'updated_at'
                        )
                        ->get();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // truncateAllTables Funtion to Delete all tables data
    public function truncateAllTables()
    {
        try {
            DB::delete("DELETE FROM AJMI_EMPLOYEE_FEATURES");
            DB::delete("DELETE FROM AJMI_ROLE_FEATURES");
            DB::delete("DELETE FROM AJMI_FEATURES");
            DB::delete("DELETE FROM AJMI_PAGES");
            DB::delete("DELETE FROM AJMI_SERVICES");

            return;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // truncateAllTables Funtion to Delete all tables data
    public function systemAuditLogs($event_type, $action, $auditable_type, $old_values = null, $new_values, $login_user)
    {
        try {

            $ip_address = \request()->getClientIp();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            return DB::table("AJMI_AUDIT_LOGS")->insert([
                        'event_type' => $event_type,
                        'action' => $action,
                        'auditable_type' => $auditable_type,
                        // 'old_values' => $old_values,
                        'new_values' => json_encode($new_values, JSON_UNESCAPED_UNICODE),
                        'ip_address' => $ip_address,
                        'user_agent' => $user_agent,
                        'created_by' => $login_user,
                        'created_at' => now(),
                    ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }



}

