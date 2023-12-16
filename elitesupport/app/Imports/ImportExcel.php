<?php
namespace App\Imports;
use App\Models\ImportExcelModel;
/* use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection; */
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
//use Maatwebsite\Excel\Concerns\ToModel;

//class ImportExcel implements ToCollection
class ImportExcel implements ToModel,WithHeadingRow
{
   
    public function model(array   $row)
    {	
        return new ImportExcelModel([
            'SUPPORT_CENTER_CODE' => trim($row['support_center_code']),
            'SUPPORT_CENTER_CODE' => trim($row['support_center_code']),
            'SUPPORT_CENTER_TYPE' => trim($row['support_center_type']),
            'SUPPORT_CENTER_NAME' => trim($row['support_center_name']),
            'SC_PHONE_NUM' => $this->multiPhone($row['sc_phone_num']!=''?$row['sc_phone_num']:0000000000),
            'SC_OWNER_NAME_1' => trim($row['sc_owner_name_1']),
            'SC_OWNER_PHONE_NUM_1' => $this->multiPhone($row['sc_owner_phone_num_1']!=''?$row['sc_owner_phone_num_1']:0000000000),
            'SC_OWNER_MOBILE_NUM_1' => $this->multiPhone($row['sc_owner_mobile_num_1']!=''?$row['sc_owner_mobile_num_1']:0000000000),
            'SC_ADDRESS_1' => trim($row['sc_address_1']),
            'SC_ADDRESS_2' => trim($row['sc_address_2']),
            'SC_AREA_NAME' => trim($row['sc_area_name']),
            'SC_DISTRICT_NAME' => trim($row['sc_district_name']),
            'SC_CITY_NAME' => trim($row['sc_city_name']),
            'SC_STATE_NAME' => trim($row['sc_state_name']),
            'SC_PINCODE' => trim($row['sc_pincode']),
            'SC_LAND_MARK' => trim($row['sc_land_mark']),
            'SC_REF_HIGHWAY' => trim($row['sc_ref_highway']),
            'GQ' => trim($row['gq']),
            'SERVICE_BOOKING' => trim($row['service_booking']),
            'AO' => trim($row['ao']),
            'RO' => trim($row['ro']),
            'Zone' => trim($row['zone']),
            'WORKS_MGR_NAME_1' => trim($row['works_mgr_name_1']),
            'WORKS_MGR_PHONE_1' =>$this->multiPhone($row['works_mgr_phone_1']!=''?$row['works_mgr_phone_1']:0000000000),
            'WORKS_MGR_MOBILE_1' => $this->multiPhone($row['works_mgr_mobile_1']!=''?$row['works_mgr_mobile_1']:0000000000),
            'WORKS_MGR_NAME_2' => trim($row['works_mgr_name_2']),
            'WORKS_MGR_PHONE_2' => $this->multiPhone($row['works_mgr_phone_2']!=''?$row['works_mgr_phone_2']:0000000000),
            'WORKS_MGR_MOBILE_2' => $this->multiPhone($row['works_mgr_mobile_2']!=''?$row['works_mgr_mobile_2']:0000000000),
            'WM_E_Mail' => $this->multiEmail(trim($row['wm_e_mail']!=''?$row['wm_e_mail']:'test@dispostable.com')),
            'SAC_Service_Head_GM_Name' => trim($row['sac_service_head_gm_name']),
            'SAC_Service_Head_GM_Mobile_Number' => trim($row['sac_service_head_gm_mobile_number']),
            'SAC_Service_Head_GM_Mail_id' => trim($row['sac_service_head_gm_mail_id']),
            'SAC_Owner_Name' => trim($row['sac_owner_name']),
            'SAC_Owner_Mobile_Number' => $this->multiPhone($row['sac_owner_mobile_number']!=''?$row['sac_owner_mobile_number']:0000000000),  
            'SAC_Owner_Mail_id' => $this->multiEmail(trim($row['sac_owner_mail_id']!=''?$row['sac_owner_mail_id']:'test@dispostable.com')),
            'AL_SE_NAME' => trim($row['al_se_name']),
            'AL_SE_MOBILE' => trim($row['al_se_mobile']),
            'ALSE_EMAIL_ID' => $this->multiEmail(trim($row['alse_email_id']!=''?$row['alse_email_id']:'test@dispostable.com')),
            'ASM' => trim($row['asm']),
            'ASM_Contact_number' => $this->multiPhone($row['asm_contact_number']!=''?$row['asm_contact_number']:0000000000),
            'ASM_E_Mail' => $this->multiEmail(trim($row['asm_e_mail']!=''?$row['asm_e_mail']:'test@dispostable.com')),
            'Regional_Service_Manager' => trim($row['regional_service_manager']),
            'RSM_Contact_number' => $this->multiPhone($row['rsm_contact_number']!=''?$row['rsm_contact_number']:0000000000),
            'RSM_Mail_Id' => $this->multiEmail(trim($row['rsm_mail_id']!=''?$row['rsm_mail_id']:'test@dispostable.com')),
            'Zonal_Service_Manager' => trim($row['zonal_service_manager']),
            'ZSM_Contact_number' => $this->multiPhone($row['zsm_contact_number']!=''?$row['zsm_contact_number']:0000000000),
            'ZSM_Mail_Id' => $this->multiEmail(trim($row['zsm_mail_id']!=''?$row['zsm_mail_id']:'test@dispostable.com')),
            'Workshop_Parts_Executive' => trim($row['workshop_parts_executive']),
            'WPE_Contact_Number' => $this->multiPhone($row['wpe_contact_number']!=''?$row['wpe_contact_number']:0000000000),
            'WPE_E_Mail' => $this->multiEmail(trim($row['wpe_e_mail']!=''?$row['wpe_e_mail']:'test@dispostable.com')),
            'Area_Parts_Manager' => trim($row['area_parts_manager']),
            'APM_Contact_Number' => $this->multiPhone($row['apm_contact_number']!=''?$row['apm_contact_number']:0000000000),
            'APM_E_Mail' => $this->multiEmail(trim($row['apm_e_mail']!=''?$row['apm_e_mail']:'test@dispostable.com')),
            'Regional_Parts_Manager' => trim($row['regional_parts_manager']),
            'RPM_Contact_Number' => $this->multiPhone($row['rpm_contact_number']!=''?$row['rpm_contact_number']:0000000000),
            'RPM_E_Mail' => trim($row['rpm_e_mail']),
            'Zonal_Parts_Manager' => trim($row['zonal_parts_manager']),
            'ZPM_Contact_Number' => $this->multiPhone($row['zpm_contact_number']!=''?$row['zpm_contact_number']:0000000000),
            'ZPM_E_Mail' => $this->multiEmail(trim($row['zpm_e_mail']!=''?$row['zpm_e_mail']:'test@dispostable.com')),
            'Zonal_Manager' => trim($row['zonal_manager']),
            'ZM_E_Mail' => trim($row['zm_e_mail']),
            'WA' => trim($row['wa']),
            'Working_Hrs' => trim($row['working_hrs']),
            'Mobile_Van' => trim($row['mobile_van']),
            'Latitude' => trim($row['latitude']),
            'Longitude' => trim($row['longitude']),
            'GST_Number' => trim($row['gst_number']),
            'No_of_Bays' => trim($row['no_of_bays']),
            'Dealer_ID' => trim($row['dealer_id']),
            'zoneID' => trim($row['zoneid']),
            'StateID' => trim($row['stateid']),
            'CityID' => trim($row['cityid'])
        ]);
        
    }
    public function multiEmail($val){
        if(empty($val)){
            $val = 'test@dispostable.com';
        }else{
            $val = rtrim($val,';');
            $val = rtrim($val,',');
            $val = rtrim($val,' ');
            $val = rtrim($val,':');
            $val = str_replace(":",",",$val);
            $val = str_replace(";",",",$val);
            $val = str_replace(" ",",",$val);
        }        
        return $val;
    }
    public function multiPhone($val){
        $val = rtrim($val,';');
        $val = rtrim($val,',');
        $val = rtrim($val,' ');
        $val = rtrim($val,':');
        if($val == '-'){
            $val = '0000000000';
        }else{
            $val = str_replace(":",",",$val);
            $val = str_replace(";",",",$val);
            //$val = str_replace("-","0000000000",$val);
            $val = str_replace("_","",$val);
            $val = str_replace(" ","",$val);
        }
        return $val;
    }
}