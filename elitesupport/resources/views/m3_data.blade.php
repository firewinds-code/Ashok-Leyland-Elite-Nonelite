@extends("layouts.masterlayout")
@section('title','M3 Data')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Upload M3 Data</h4>
                <div class="row">
                    <div class="col-md-12">
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-import-excel')}}">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="DataID" id="DataID">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="file">File</label> <span style="color: red;">*</span> 
                                    <input type="file" name="import_file" id="import_file" class="form-control" required/>
                                    
                                    <a href="{{ asset('images/m3_data.csv') }}">
                                        <i class="fa fa-download"></i> Download format
                                    </a>
                                   
                                   
                                </div>
                            </div>
                            @if(Session::get('role') == '29' || Session::get('role') == '30')
                            <div class="box-footer">
                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
                                    <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                                </span>
                            </div>
                            @endif
                        </form>
                        <div class="clear"></div>
                        <hr>
                        <a href="#" class="btn-primary" style="padding: 5px;" id="import_user" onclick="importUser()">Import User Master</a><br>
                        <hr>
                        <div class="clear"></div>
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th>SUPPORT_CENTER_CODE</th>
                                        <th>SUPPORT_CENTER_TYPE</th>
                                        <th>SUPPORT_CENTER_NAME</th>
                                        <th>SC_PHONE_NUM</th>
                                        <th>SC_OWNER_NAME_1</th>
                                        <th>SC_OWNER_PHONE_NUM_1</th>
                                        <th>SC_OWNER_MOBILE_NUM_1</th>
                                        <th>SC_ADDRESS_1</th>
                                        <th>SC_ADDRESS_2</th>
                                        <th>SC_AREA_NAME</th>
                                        <th>SC_DISTRICT_NAME</th>
                                        <th>SC_CITY_NAME</th>
                                        <th>SC_STATE_NAME</th>
                                        <th>SC_PINCODE</th>
                                        <th>SC_LAND_MARK</th>
                                        <th>SC_REF_HIGHWAY</th>
                                        <th>GQ</th>
                                        <th>SERVICE_BOOKING</th>
                                        <th>AO</th>
                                        <th>RO</th>
                                        <th>Zone</th>
                                        <th>WORKS_MGR_NAME_1</th>
                                        <th>WORKS_MGR_PHONE_1</th>
                                        <th>WORKS_MGR_MOBILE_1</th>
                                        <th>WORKS_MGR_NAME_2</th>
                                        <th>WORKS_MGR_PHONE_2</th>
                                        <th>WORKS_MGR_MOBILE_2</th>
                                        <th>WM_E_Mail</th>
                                        <th>SAC_Service_Head_GM_Name</th>
                                        <th>SAC_Service_Head_GM_Mobile_Number</th>
                                        <th>SAC_Service_Head_GM_Mail_id</th>
                                        <th>SAC_Owner_Name</th>
                                        <th>SAC_Owner_Mobile_Number</th>
                                        <th>SAC_Owner_Mail_id</th>
                                        <th>AL_SE_NAME</th>
                                        <th>AL_SE_MOBILE</th>
                                        <th>ALSE_EMAIL_ID</th>
                                        <th>ASM</th>
                                        <th>ASM_Contact_number</th>
                                        <th>ASM_E_Mail</th>
                                        <th>Regional_Service_Manager</th>
                                        <th>RSM_Contact_number</th>
                                        <th>RSM_Mail_Id</th>
                                        <th>Zonal_Service_Manager</th>
                                        <th>ZSM_Contact_number</th>
                                        <th>ZSM_Mail_Id</th>
                                        <th>Workshop_Parts_Executive</th>
                                        <th>WPE_Contact_Number</th>
                                        <th>WPE_E_Mail</th>
                                        <th>Area_Parts_Manager</th>
                                        <th>APM_Contact_Number</th>
                                        <th>APM_E_Mail</th>
                                        <th>Regional_Parts_Manager</th>
                                        <th>RPM_Contact_Number</th>
                                        <th>RPM_E_Mail</th>
                                        <th>Zonal_Parts_Manager</th>
                                        <th>ZPM_Contact_Number</th>
                                        <th>ZPM_E_Mail</th>
                                        <th>Zonal_Manager</th>
                                        <th>ZM_E_Mail</th>
                                        <th>WA</th>
                                        <th>Working_Hrs</th>
                                        <th>Mobile_Van</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>GST_Number</th>
                                        <th>No_of_Bays</th>
                                        <th>Dealer_ID</th>
                                        <th>zoneID</th>
                                        <th>StateID</th>
                                        <th>CityID</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($rowData)
                                   {{--  {{ dd($rowData) }} --}}
                                        @foreach($rowData as $row)
                                        <tr>
                                            <td>{{"$row->SUPPORT_CENTER_CODE"}}</td>
                                            <td>{{"$row->SUPPORT_CENTER_TYPE"}}</td>
                                            <td>{{"$row->SUPPORT_CENTER_NAME"}}</td>
                                            <td>{{"$row->SC_PHONE_NUM"}}</td>
                                            <td>{{"$row->SC_OWNER_NAME_1"}}</td>
                                            <td>{{"$row->SC_OWNER_PHONE_NUM_1"}}</td>
                                            <td>{{"$row->SC_OWNER_MOBILE_NUM_1"}}</td>
                                            <td>{{"$row->SC_ADDRESS_1"}}</td>
                                            <td>{{"$row->SC_ADDRESS_2"}}</td>
                                            <td>{{"$row->SC_AREA_NAME"}}</td>
                                            <td>{{"$row->SC_DISTRICT_NAME"}}</td>
                                            <td>{{"$row->SC_CITY_NAME"}}</td>
                                            <td>{{"$row->SC_STATE_NAME"}}</td>
                                            <td>{{"$row->SC_PINCODE"}}</td>
                                            <td>{{"$row->SC_LAND_MARK"}}</td>
                                            <td>{{"$row->SC_REF_HIGHWAY"}}</td>
                                            <td>{{"$row->GQ"}}</td>
                                            <td>{{"$row->SERVICE_BOOKING"}}</td>
                                            <td>{{"$row->AO"}}</td>
                                            <td>{{"$row->RO"}}</td>
                                            <td>{{"$row->Zone"}}</td>
                                            <td>{{"$row->WORKS_MGR_NAME_1"}}</td>
                                            <td>{{"$row->WORKS_MGR_PHONE_1"}}</td>
                                            <td>{{"$row->WORKS_MGR_MOBILE_1"}}</td>
                                            <td>{{"$row->WORKS_MGR_NAME_2"}}</td>
                                            <td>{{"$row->WORKS_MGR_PHONE_2"}}</td>
                                            <td>{{"$row->WORKS_MGR_MOBILE_2"}}</td>
                                            <td>{{"$row->WM_E_Mail"}}</td>
                                            <td>{{"$row->SAC_Service_Head_GM_Name"}}</td>
                                            <td>{{"$row->SAC_Service_Head_GM_Mobile_Number"}}</td>
                                            <td>{{"$row->SAC_Service_Head_GM_Mail_id"}}</td>
                                            <td>{{"$row->SAC_Owner_Name"}}</td>
                                            <td>{{"$row->SAC_Owner_Mobile_Number"}}</td>
                                            <td>{{"$row->SAC_Owner_Mail_id"}}</td>
                                            <td>{{"$row->AL_SE_NAME"}}</td>
                                            <td>{{"$row->AL_SE_MOBILE"}}</td>
                                            <td>{{"$row->ALSE_EMAIL_ID"}}</td>
                                            <td>{{"$row->ASM"}}</td>
                                            <td>{{"$row->ASM_Contact_number"}}</td>
                                            <td>{{"$row->ASM_E_Mail"}}</td>
                                            <td>{{"$row->Regional_Service_Manager"}}</td>
                                            <td>{{"$row->RSM_Contact_number"}}</td>
                                            <td>{{"$row->RSM_Mail_Id"}}</td>
                                            <td>{{"$row->Zonal_Service_Manager"}}</td>
                                            <td>{{"$row->ZSM_Contact_number"}}</td>
                                            <td>{{"$row->ZSM_Mail_Id"}}</td>
                                            <td>{{"$row->Workshop_Parts_Executive"}}</td>
                                            <td>{{"$row->WPE_Contact_Number"}}</td>
                                            <td>{{"$row->WPE_E_Mail"}}</td>
                                            <td>{{"$row->Area_Parts_Manager"}}</td>
                                            <td>{{"$row->APM_Contact_Number"}}</td>
                                            <td>{{"$row->APM_E_Mail"}}</td>
                                            <td>{{"$row->Regional_Parts_Manager"}}</td>
                                            <td>{{"$row->RPM_Contact_Number"}}</td>
                                            <td>{{"$row->RPM_E_Mail"}}</td>
                                            <td>{{"$row->Zonal_Parts_Manager"}}</td>
                                            <td>{{"$row->ZPM_Contact_Number"}}</td>
                                            <td>{{"$row->ZPM_E_Mail"}}</td>
                                            <td>{{"$row->Zonal_Manager"}}</td>
                                            <td>{{"$row->ZM_E_Mail"}}</td>
                                            <td>{{"$row->WA"}}</td>
                                            <td>{{"$row->Working_Hrs"}}</td>
                                            <td>{{"$row->Mobile_Van"}}</td>
                                            <td>{{"$row->Latitude"}}</td>
                                            <td>{{"$row->Longitude"}}</td>
                                            <td>{{"$row->GST_Number"}}</td>
                                            <td>{{"$row->No_of_Bays"}}</td>
                                            <td>{{"$row->Dealer_ID"}}</td>
                                            <td>{{"$row->zoneID"}}</td>
                                            <td>{{"$row->StateID"}}</td>
                                            <td>{{"$row->CityID"}}</td>
                                            <td>{{"$row->created_at"}}</td>
                                        </tr>
                                        @endforeach
                                    @endisset                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    function importUser(){
        $.ajax({ url: '{{url("import-user-data")}}',
            success: function(response){
                alert(response);
            }
		});
    }
</script>

@endsection
