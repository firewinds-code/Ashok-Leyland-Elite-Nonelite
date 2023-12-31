<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ImportExcelModel extends Model{
    protected $table = 'user_raw_data';
    protected $fillable = [
        'SUPPORT_CENTER_CODE', 'SUPPORT_CENTER_TYPE', 'SUPPORT_CENTER_NAME', 'SC_PHONE_NUM', 'SC_OWNER_NAME_1', 'SC_OWNER_PHONE_NUM_1', 'SC_OWNER_MOBILE_NUM_1', 'SC_ADDRESS_1', 'SC_ADDRESS_2', 'SC_AREA_NAME', 'SC_DISTRICT_NAME', 'SC_CITY_NAME', 'SC_STATE_NAME', 'SC_PINCODE', 'SC_LAND_MARK', 'SC_REF_HIGHWAY', 'GQ', 'SERVICE_BOOKING', 'AO', 'RO', 'Zone', 'WORKS_MGR_NAME_1', 'WORKS_MGR_PHONE_1', 'WORKS_MGR_MOBILE_1', 'WORKS_MGR_NAME_2', 'WORKS_MGR_PHONE_2', 'WORKS_MGR_MOBILE_2', 'WM_E_Mail', 'SAC_Service_Head_GM_Name', 'SAC_Service_Head_GM_Mobile_Number', 'SAC_Service_Head_GM_Mail_id', 'SAC_Owner_Name', 'SAC_Owner_Mobile_Number', 'SAC_Owner_Mail_id', 'AL_SE_NAME', 'AL_SE_MOBILE', 'ALSE_EMAIL_ID', 'ASM', 'ASM_Contact_number', 'ASM_E_Mail', 'Regional_Service_Manager', 'RSM_Contact_number', 'RSM_Mail_Id', 'Zonal_Service_Manager', 'ZSM_Contact_number', 'ZSM_Mail_Id', 'Workshop_Parts_Executive', 'WPE_Contact_Number', 'WPE_E_Mail', 'Area_Parts_Manager', 'APM_Contact_Number', 'APM_E_Mail', 'Regional_Parts_Manager', 'RPM_Contact_Number', 'RPM_E_Mail', 'Zonal_Parts_Manager', 'ZPM_Contact_Number', 'ZPM_E_Mail', 'Zonal_Manager', 'ZM_E_Mail', 'WA', 'Working_Hrs', 'Mobile_Van', 'Latitude', 'Longitude', 'GST_Number', 'No_of_Bays', 'Dealer_ID', 'zoneID', 'StateID', 'CityID'
    ];
}
