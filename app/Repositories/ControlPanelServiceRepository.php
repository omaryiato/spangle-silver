<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class ControlPanelServiceRepository
{

    // getControlPanelServicesList Funtion To Get Control Panel Services List
    public function getControlPanelServicesList($role_id, $employee_number, $feature_type)
    {

        try {
            if ($role_id !== null || $employee_number !== null) {

                // $query = "SELECT DISTINCT ajs.*
                //     FROM AJMI_SERVICES ajs
                //     JOIN AJMI_FEATURES ajf
                //         ON ajs.ID = ajf.SERVICE_ID
                //     LEFT JOIN AJMI_ROLE_FEATURES ajrf
                //         ON ajf.ID = ajrf.FEATURE_ID
                //     LEFT JOIN AJMI_EMPLOYEE_FEATURES ajef
                //         ON ajf.ID = ajef.FEATURE_ID
                //     WHERE ajf.FEATURE_TYPE = :feature_type
                //     AND ajs.DELETED_AT IS NULL
                // ";

                // $bindings = [
                //     'feature_type' => $feature_type,
                // ];

                // $whereConditions = ["ajf.FEATURE_TYPE = :feature_type", "ajs.DELETED_AT IS NULL"];

                // $roleCondition = '';
                // $employeeCondition = '';

                // if ($role_id !== null) {
                //     $roleCondition = "(ajrf.ROLE_ID = :role_id AND ajrf.ALLOW = 1)";
                //     $bindings['role_id'] = $role_id;
                // }


                // if ($employee_number !== null) {
                //     $employeeCondition = "(ajef.EMPLOYEE_NUMBER = :employee_number AND ajef.ALLOW = 1)";
                //     $bindings['employee_number'] = $employee_number;
                // }


                // if ($roleCondition && $employeeCondition) {
                //     $whereConditions[] = "($roleCondition OR $employeeCondition)";
                // } elseif ($roleCondition) {
                //     $whereConditions[] = $roleCondition;
                // } elseif ($employeeCondition) {
                //     $whereConditions[] = $employeeCondition;
                // }


                // $query = "SELECT DISTINCT ajs.*
                //         FROM AJMI_SERVICES ajs
                //         JOIN AJMI_FEATURES ajf ON ajs.ID = ajf.SERVICE_ID
                //         LEFT JOIN AJMI_ROLE_FEATURES ajrf ON ajf.ID = ajrf.FEATURE_ID
                //         LEFT JOIN AJMI_EMPLOYEE_FEATURES ajef ON ajf.ID = ajef.FEATURE_ID
                //         WHERE " . implode(' AND ', $whereConditions);

                // return collect(DB::select($query, $bindings));

                $query = DB::table('AJMI_SERVICES as ajs')
                            ->distinct()
                            ->select('ajs.*')
                            ->join('AJMI_FEATURES as ajf', 'ajs.ID', '=', 'ajf.SERVICE_ID')
                            ->leftJoin('AJMI_ROLE_FEATURES as ajrf', 'ajf.ID', '=', 'ajrf.FEATURE_ID')
                            ->leftJoin('AJMI_EMPLOYEE_FEATURES as ajef', 'ajf.ID', '=', 'ajef.FEATURE_ID')
                            ->where('ajf.FEATURE_TYPE', $feature_type)
                            ->whereNull('ajs.DELETED_AT')
                            ->where(function ($q) use ($role_id, $employee_number) {
                                if ($role_id !== null && $employee_number !== null) {
                                    $q->where(function ($sub) use ($role_id) {
                                        $sub->where('ajrf.ROLE_ID', $role_id)
                                            ->where('ajrf.ALLOW', 1);
                                    })->orWhere(function ($sub) use ($employee_number) {
                                        $sub->where('ajef.EMPLOYEE_NUMBER', $employee_number)
                                            ->where('ajef.ALLOW', 1);
                                    });
                                } elseif ($role_id !== null) {
                                    $q->where('ajrf.ROLE_ID', $role_id)
                                    ->where('ajrf.ALLOW', 1);
                                } elseif ($employee_number !== null) {
                                    $q->where('ajef.EMPLOYEE_NUMBER', $employee_number)
                                    ->where('ajef.ALLOW', 1);
                                }
                            });

                return $query->get();

            } else {
                return DB::table('AJMI_SERVICES')
                        ->whereNull('DELETED_AT')
                        ->get();
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getControlPanelServiceDetails Funtion To Get Control Panel Service Details
    public function getControlPanelServiceDetails($service_id)
    {

        try {
            return DB::table('AJMI_SERVICES')
                        ->whereId($service_id)
                        ->whereNull('DELETED_AT')
                        ->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewControlPanelService Funtion To Add new Control Panel Service
    public function addNewControlPanelService($service_details)
    {

        DB::beginTransaction();

        try {

            $service_id = DB::table('AJMI_SERVICES')
                            ->insertGetId([
                                'SERVICE_EN_NAME' => $service_details['service_en_name'],
                                'SERVICE_AR_NAME' => $service_details['service_ar_name'],
                                'SERVICE_CODE' => $service_details['service_code'],
                                'SERVICE_LOGO' => isset($service_details['service_logo']) ?
                                    str_replace(' ', '_', $service_details['service_en_name']) : null,
                                'SERVICE_DOMAIN' => $service_details['service_domain'],
                                'SERVICE_STATUS' => $service_details['service_status'],
                                'SERVICE_SESSION_TIMEOUT' => $service_details['service_session_timeout'],
                                'SERVICE_ICON' => $service_details['service_icon'],
                                'created_by' => $service_details['login_user'],
                                'created_at' => now(),
                                'updated_by' => $service_details['login_user'],
                                'updated_at' => now(),
                            ], 'ID');

            if ($service_details['service_logo']) {

                $service_en_name = str_replace(' ', '_', $service_details['service_en_name']);
                $service_logo_file_extension = $service_details['service_logo']?->getClientOriginalExtension();
                $service_logo_file_name = "{$service_en_name}.{$service_logo_file_extension}";

                $service_logo_folder_path = public_path("documents/e_control_panel_service_logo");

                if (!File::exists($service_logo_folder_path)) {
                    File::makeDirectory($service_logo_folder_path, 0755, true);
                }

                $service_details['service_logo']->move($service_logo_folder_path, $service_logo_file_name);

                // $output_attachment_path = $service_logo_folder_path . DIRECTORY_SEPARATOR . $service_logo_file_name;
                // $this->uploadDocumnetAcrchive->upload($qiwa_eos_request_info['employee_number'], $output_attachment_path, $attachment_file_name);
            }

            return $service_id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // updateControlPanelService Funtion To Update Control Panel Service info
    public function updateControlPanelService($service_details)
    {

        DB::beginTransaction();

        try {
            $service_logo = null;
            if ($service_details['service_logo']) {
                $service_logo = str_replace(' ', '_', $service_details['service_en_name']);
            } else {
                $service_logo = collect(DB::select("SELECT SERVICE_LOGO AS service_logo
                                                    FROM AJMI_SERVICES WHERE id = :service_id ",
                                            ["service_id" => $service_details['service_id']]))->first()->service_logo ?? null;
            }

            DB::table('AJMI_SERVICES')
                ->whereId($service_details['service_id'])
                ->update([
                            "SERVICE_EN_NAME" => $service_details['service_en_name'],
                            "SERVICE_AR_NAME" => $service_details['service_ar_name'],
                            "SERVICE_CODE" => $service_details['service_code'],
                            "SERVICE_LOGO" => $service_logo,
                            "SERVICE_DOMAIN" => $service_details['service_domain'],
                            "SERVICE_SESSION_TIMEOUT" => $service_details['service_session_timeout'],
                            "SERVICE_STATUS" => $service_details['service_status'],
                            "SERVICE_ICON" => $service_details['service_icon'],
                            "updated_by" => $service_details['login_user'],
                            "updated_at" => now()
                ]);

            if ($service_details['service_logo']) {

                $service_en_name = str_replace(' ', '_', $service_details['service_en_name']);
                $service_logo_file_extension = $service_details['service_logo']?->getClientOriginalExtension();
                $service_logo_file_name = "{$service_en_name}.{$service_logo_file_extension}";

                $service_logo_folder_path = public_path("documents/e_control_panel_service_logo");

                if (!File::exists($service_logo_folder_path)) {
                    File::makeDirectory($service_logo_folder_path, 0755, true);
                }

                $service_details['service_logo']->move($service_logo_folder_path, $service_logo_file_name);

                // $output_attachment_path = $service_logo_folder_path . DIRECTORY_SEPARATOR . $service_logo_file_name;
                // $this->uploadDocumnetAcrchive->upload($qiwa_eos_request_info['employee_number'], $output_attachment_path, $attachment_file_name);
            }

            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    // deleteControlPanelService Funtion To Delete Control Panel Service
    public function deleteControlPanelService($service_id, $login_user)
    {

        DB::beginTransaction();

        try {

            // return DB::table('AJMI_SERVICES')
            //             ->whereId($service_id)
            //             ->update([
            //                 'service_status' => 0,
            //                 'updated_by' => $login_user,
            //                 'updated_at' => now(),
            //                 'deleted_at' => now()
            //             ]);
            return DB::table('AJMI_SERVICES')
                        ->whereId($service_id)
                        ->delete();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}

