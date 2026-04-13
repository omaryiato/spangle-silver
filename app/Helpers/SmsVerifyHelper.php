<?php

namespace App\Helpers;

class SmsVerifyHelper
{

    // that used to dealing with arabic message
    function isArabicMessage($message)
    {

        try {
            // Regular expression pattern to match Arabic characters
            $pattern = '/\p{Arabic}/u';

            // Check if the message contains Arabic characters
            return preg_match($pattern, $message) === 1;
        } catch (\Exception $exception) {
            return ResponsHelper::error($message,'Error -> ' . $exception->getMessage(),400);
        }
    }

    function modemIntegration($phone_number, $message,$otp=null)
    {

        try {
            if ($this->isArabicMessage($message)){ $message = "The verification code for logging into the self-service platform is: $otp. Do not share it.";
            }else{
                if (isset($otp)){
                    $message = "$message : $otp";
                }

            }

            $base_url = "http://192.168.211.150/cgi/WebCGI";
            $api_key = "1500101=account=apiuser&password=apipass&port=3";
            $destination = "&destination=" . rawurlencode($phone_number);
            $content = "&content=" . rawurlencode($message);
            $url = $base_url . "?" . $api_key . $destination . $content;
            $headers = [
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            ];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $exception) {

            return ResponsHelper::error($message,'Error -> ' . $exception->getMessage(),400);
        }
    }

    public function moraIntegration($phone_number, $message, $otp = null)
    {

        try {
            $currentDomain = request()->getHost();
            if($currentDomain==env('app_url_local') or $currentDomain==env('app_url_test') ){

            }else{
                try {


                    if ($this->isArabicMessage($message)) {
                        $message = "The verification code: $otp for self-service.";
                    } else {
                        if (isset($otp)) {
                            $message = "OTP:$otp $message";
                        }
                    }

                    $base_url = "https://mora-sa.com/api/v1/sendsms";
                    $api_key = "7ff998d245c5adf59d6dd8113e2c1bc3ac65c212";
                    $username = "ALajmiCo";
                    $sender = "AlajmiCo";

                    $numbers = "&numbers=" . rawurlencode($phone_number);
                    $api_key_param = "api_key=" . $api_key;
                    $username_param = "username=" . $username;
                    $message_param = "message=" . rawurlencode($message);
                    $sender_param = "sender=" . $sender;

                    $url = $base_url . "?" . $api_key_param . "&" . $username_param . "&" . $message_param . "&" . $sender_param . $numbers;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-type: application/json',
                    ]);
                    $response = curl_exec($ch);
                    $handel_un_authorized = json_decode($response)->status;

                    if ($response === false or $handel_un_authorized->error==true) {
                        return false;
                        //$this->OurSMSIntegration($phone_number,$message,$otp);
                    } else {
                        $responseData = json_decode($response, true);
                        if (isset($responseData['status']['error'])){
                            if ($responseData['status']['error'] == true) {
                                // $this->OurSMSIntegration($phone_number,$message,$otp);
                                return false;
                            }
                        }
                    }
                    curl_close($ch);
                } catch (\Exception $exceptionxception){
                    return false;
                    // $this->OurSMSIntegration($phone_number,$message,$otp);
                }
            }
        } catch (\Exception $exception) {

            return ResponsHelper::error($currentDomain,'Error -> ' . $exception->getMessage(),400);
        }
    }

    public function OurSMSIntegration($phone_number, $message, $otp = null) {
        try {
            $base_url = 'https://api.oursms.com/api-a/msgs';
            $api_key = '8qpcnvjgZMqG3XY7FoPh';
            $username = 'AlajmiCompany';
            $sender = 'AlajmiCo';

            $params = [
                'username' => $username,
                'token' => $api_key,
                'src' => $sender,
                'dests' => $phone_number,
                'body' => $message,
                'priority' => 0,
                'delay' => 0,
                'validity' => 0,
                'maxParts' => 0,
                'dlr' => 0,
                'prevDups' => 1,
            ];

            $url = $base_url . '?' . http_build_query($params);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set to true to capture the response
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-type: application/json',
            ]);
            $response = curl_exec($ch);

            if ($response === false) {
                // SMS sending failed, return false
                return false;
            } else {
                // SMS sent successfully, return true
                return true;
            }
            curl_close($ch);


        } catch (\Exception $exceptionxception) {
            // Exception occurred, return false
            return false;
        }
    }

    // that used to send otp and message to specific number integration (original)
    public  function sendSMS($phone_number, $message, $otp = null)
    {

        try {
            $phone_number = '966573447923';
            $currentDomain = request()->getHost();
            if($currentDomain==env('app_url_local') or $currentDomain==env('app_url_test') ){
            }else {

                $moraStatus =  false; // Assuming this is a separate logic
                if ($this->isArabicMessage($message)) {
                    $message = $message;
                } else {
                    if (isset($otp)) {
                        $message = $message;
                    }
                }
                if ($moraStatus == false) {
                    $OurSmsStatus = $this->OurSMSIntegration($phone_number, $message, $otp);
                    $type = 'our_sms';
                    if ($OurSmsStatus == false) {
                        $this->modemIntegration($phone_number, $message, $otp);
                        $type = 'modem';
                    }
                }
                return $OurSmsStatus;
            }
        } catch (\Exception $exception) {

            return ResponsHelper::error($currentDomain,'Error -> ' . $exception->getMessage(),400);
        }
    }

    // that used to filter the phone number and added code of country
    public function filterPhoneNumber($phone_number)
    {

        try {
            $cur_zero_number = null;
            if (strlen($phone_number) > 0) {
                if (substr($phone_number, 0, 1) === '0')
                    $cur_zero_number = '966' . substr($phone_number, 1);
                if (strlen($cur_zero_number) == 12) {
                    return $cur_zero_number;
                }
            }
        } catch (\Exception $exception) {

            return ResponsHelper::error($phone_number,'Error -> ' . $exception->getMessage(),400);
        }
    }

}
