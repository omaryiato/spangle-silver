<?php

namespace App\Helpers;


use Illuminate\Support\Facades\DB;
use phpseclib3\Net\SFTP;
use App\Helpers\ResponsHelper;
use Illuminate\Support\Facades\Log;



class UploadDocumnetAcrchive
{

    public function upload($employeeNumber, $outputFileName_local_word_readyOnly, $file_output_name)
    {
        try {

            Log::channel('submittransfer')->info("Step 1: -> Handle upload document archive $employeeNumber, $outputFileName_local_word_readyOnly, $file_output_name");

            Log::channel('submittransfer')->info("Step 2: -> Hnadle current domian");

            // $currentDomain = request()->getHost();
            // if($currentDomain==env('app_url_local') or $currentDomain==env('app_url_test')){
            //     $FTP_HOST_ARCHIVE = "192.168.15.78";
            //     $FTP_USER_ARCHIVE = "appwls";
            //     $FTP_PASS_ARCHIVE = "appwls123";
            // }else{
            //     $FTP_HOST_ARCHIVE = "192.168.15.44";
            //     $FTP_USER_ARCHIVE = "selfservice";
            //     $FTP_PASS_ARCHIVE = "selfservice";
            // }

            $FTP_HOST_ARCHIVE = "192.168.15.245";
            $FTP_USER_ARCHIVE = "appwls";
            $FTP_PASS_ARCHIVE = "appwls123";

            Log::channel('submittransfer')->info("Step 2: -> Initialize an SFTP connection");

            // Initialize an SFTP connection
            $sftp = new SFTP($FTP_HOST_ARCHIVE);


            // Authenticate with the server using a username and password
            if (!$sftp->login($FTP_USER_ARCHIVE, $FTP_PASS_ARCHIVE)) {
                return  ResponsHelper::error("Login failed.");
            }

            Log::channel('submittransfer')->info("Step 4: -> Convert bytes to megabytes (1 megabyte = 1024 kilobytes)");

            $localFilePath = $outputFileName_local_word_readyOnly;
            $fileSizeInMB = round(filesize($localFilePath) / (1024 * 1024), 2); // Convert bytes to megabytes (1 megabyte = 1024 kilobytes)

            $compressedSizeInMB = $fileSizeInMB * 0.5; // Adjust the compression factor as needed

            $compressedSizeInKB = $compressedSizeInMB * 1024; // Convert megabytes to kilobytes

            $compressedSizeInKB = round($compressedSizeInKB, 2);
            $check_found_dire_archive =  DB::select("SELECT count(*) AS check_count
                                                    FROM apps.XXARCH_EBS_CONTEXT_RECORDS
                                                    WHERE context ='EMP' AND record = '$employeeNumber'
                                                    ")[0]->check_count;

            Log::channel('submittransfer')->info("Step 5: ->check found dire archive");

            if ($check_found_dire_archive == 0) {
                DB::statement("BEGIN xxajmi_sshr_emp_absence.xxajmi_archive_file_head_sshr ('$employeeNumber', '$file_output_name', $compressedSizeInKB); END;");
                DB::statement("BEGIN xxajmi_sshr_emp_absence.XXAJMI_ARCHIVE_FILE_SSHR ('$employeeNumber', '$file_output_name', $compressedSizeInKB); END;");
            } else if ($check_found_dire_archive == 1) {
                DB::statement("BEGIN xxajmi_sshr_emp_absence.XXAJMI_ARCHIVE_FILE_SSHR ('$employeeNumber', '$file_output_name', $compressedSizeInKB); END;");
            }

            $path_url =  DB::select("SELECT '/u02/temp/DocumentArchivingSystem/EMP/'||substr(arc_tran_code,instr(arc_tran_code,'-',1,2)+1,6)||'/'||arc_tran_code AS PATH
            FROM TRAN_ARCHIVING_HEADER@DOCARCH_ARCHSRV.ALAJMI.COM.SA
            WHERE ebs_record_no ='$employeeNumber' AND arc_tran_code LIKE 'EMP%' ");
            $tranc_code = DB::select("SELECT arc_tran_code FROM TRAN_ARCHIVING_HEADER@DOCARCH_ARCHSRV.ALAJMI.COM.SA WHERE ebs_record_no ='$employeeNumber' AND arc_tran_code LIKE 'EMP%'");

            $path_url =  DB::select("SELECT '/u02/temp/DocumentArchivingSystem/EMP/'||substr(arc_tran_code,instr(arc_tran_code,'-',1,2)+1,6)||'/'||arc_tran_code AS PATH
                                    FROM TRAN_ARCHIVING_HEADER@DOCARCH_ARCHSRV.ALAJMI.COM.SA
                                    WHERE ebs_record_no ='$employeeNumber' AND arc_tran_code LIKE 'EMP%' ");
            $tranc_code = DB::select("SELECT arc_tran_code
                                    FROM TRAN_ARCHIVING_HEADER@DOCARCH_ARCHSRV.ALAJMI.COM.SA
                                    WHERE ebs_record_no ='$employeeNumber' AND arc_tran_code LIKE 'EMP%'");

            if (isset($path_url[0])) {
                $remoteBaseDir = $path_url[0]->path;
                if (isset($tranc_code[0])) {
                    $remoteEmployeeDir = "{$remoteBaseDir}";
                    if (!$sftp->is_dir($remoteEmployeeDir)) {
                        if (!$sftp->mkdir($remoteEmployeeDir, 0777, true)) {
                            $sftp->disconnect();
                            Log::channel('submittransfer')->info("Step 6: -> Disconnect");
                            return;
                        }
                    }

                    $remoteFilePath = "{$remoteEmployeeDir}/" . basename($localFilePath);
                    if ($sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
                    }
                    $sftp->disconnect();
                }
            }

            Log::channel('submittransfer')->info("Step 6: -> Done");


        } catch (\Exception $exceptionxception) {
            Log::channel('submittransfer')->info("Step 8: -> Feild to insert ($file_output_name) into archive");

            return ResponsHelper::error($exceptionxception->getMessage());
        }
    }
}
