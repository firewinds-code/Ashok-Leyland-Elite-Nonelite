function formValidate(){  
	
	var name=document.forms["myForm"]["name"].value;	 
	var password=document.forms["myForm"]["password"].value;	  
	var email=document.forms["myForm"]["email"].value;	
	var mobile=document.forms["myForm"]["mobile"].value; 
	var status=false;  
	if (name==""){  
		document.getElementById("name_error").innerHTML= "<img src='images/wrong.png'/> Please enter your name";  
		status=false;
	}
	if (password==""){  
		document.getElementById("password_error").innerHTML= "<img src='images/wrong.png'/> Please enter your password";  
		status=false;
	}
	if (email==""){
		
		document.getElementById("email_error").innerHTML= " <img src='images/wrong.png'/> Please enter email";  
		status=false; 
	}else{
		
		if (!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/)){
			document.getElementById("email_error").innerHTML= " <img src='images/wrong.png'/> Please enter valid email format";
			status=false;
		}else{
			
			status=true;
		}			
		  
	} 
	if (mobile==""){  
	document.getElementById("mobile_error").innerHTML= "<img src='images/wrong.png'/>  Please enter mobile number";  
	status=false; 
	}else{ 	
		if (isNaN(mobile)||mobile.indexOf(" ")!=-1){
			document.getElementById("mobile_error").innerHTML= " <img src='images/wrong.png'/> Please enter numeric number"; 
			status=false; 
        }
        if ((mobile.length > 10) || (mobile.length < 10)){
			document.getElementById("mobile_error").innerHTML= " <img src='images/wrong.png'/> Please enter 10 numbers"; 
			status=false; 
		}
		
		status=true;
	}  
	  
return status;  
}  
function loginValidate(){  		 
	var password=document.forms["myForm"]["password"].value;	  
	var email=document.forms["myForm"]["email"].value;	
	
	var status=false;	
	if (password==""){  
		document.getElementById("password_error").innerHTML= "<img src='images/wrong.png'/> Please enter your password";  
		status=false;
	}else{  
		 
		status=true;
	}  
	if (email=="")
	{		
		document.getElementById("email_error").innerHTML= " <img src='images/wrong.png'/> Please enter email";  
		status=false; 
	}else{	
		status=true;
	} 
	
return status;  
}
<!-----------------------User Type----------------------------------------->
function userType(){  		 
	var user_type=document.forms["myForm"]["user_type"].value;
	
	var status=false;	
	if (user_type==""){  
		document.getElementById("user_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter User Type";  
		status=false;
	}else{  
		
		status=true;
	}	
return status;  
}

function userTypeUpdate(){  		 
	var user_type=document.forms["myForm"]["user_type"].value;
	var flag=$('#flag').val();
	
	var status=false;	
	if (user_type==""){  
		document.getElementById("user_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter User Type";  
		status=false;
	}else{  
		  
		status=true;
	}
	if (flag=="NA"){  
		document.getElementById("flag_error").innerHTML= "<img src='images/wrong.png'/> Please enter User Type";  
		status=false;
	}else{  
		  
		status=true;
	}	
return status;  
}
function EditUserType(el){
	$('#updateUserType').show();
	$('#insertUserType').hide();
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#user_type').val($(el).parents('td').parents('tr').find('.cls_usertype').text());
	$('#flag').val(flg);
	$('#dataid').val(el.id);
}
<!-----------------------End User Type----------------------------------------->
<!-----------------------Brand----------------------------------------->
function brandValidation(){  		 
	var user_type=document.forms["myForm"]["brand"].value;
	
	var status=false;	
	if (user_type==""){  
		document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please enter brand";  
		status=false;
	}else{  
		  
		status=true;
	}	
return status;  
}

function brandUpdate(){  		 
	var user_type=document.forms["myForm"]["brand"].value;
	var flag=$('#flag').val();
	
	var status=false;	
	if (user_type==""){  
		document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please enter brand";  
		status=false;
	}else{  
		  
		status=true;
	}
	if (flag=="NA"){  
		document.getElementById("flag_error").innerHTML= "<img src='images/wrong.png'/> Please enter User Type";  
		status=false;
	}else{  
		  
		status=true;
	}	
return status;  
}
function EditBrand(el){
	$('#updateBrand').show();
	$('#insertBrand').hide();
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#brand').val($(el).parents('td').parents('tr').find('.cls_brand').text());
	$('#flag').val(flg);
	$('#dataid').val(el.id);
}
<!-----------------------End Brand----------------------------------------->

<!-----------------------contactModule----------------------------------------->
function contactModule(){  		 
	var mode_name1=$('#mode_name').val();
	var mode_name=document.forms["myForm"]["mode_name"].value;
	var modeName = mode_name1 !=''?mode_name1:mode_name;
	var status=true;	
	if (modeName==""){  
		document.getElementById("mode_name_error").innerHTML= "<img src='images/wrong.png'/> Please enter center name";  
		status=false;
	}else{  		  
		document.getElementById("mode_name_error").innerHTML= "<img src='images/right.png'/>";  
	}	
return status;  
}
function editContactModule(el){
	$('#updatecontactmodule').show();
	$('#insertcontactmodule').hide();
	
	$('#mode_name').val($(el).parents('td').parents('tr').find('.cls_mode_name').text());	
	$('#dataid').val(el.id);
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#flag').val(flg);
}
/* <!-----------------------End contactModule----------------------------------------->

<!-----------------------Vehicle-----------------------------------------> */
function vehicleValidation(){  	

	var vehicle=$('#vehicle').val();
	var model=$('#model').val();
	var segment=$('#segment').val();
		
			
	var status=true;	
	if (vehicle=="NA"){  
		document.getElementById("vehicle_error").innerHTML= "<img src='images/wrong.png'/> Please enter vehicle";  
		status=false;
	}else{		  
		document.getElementById("vehicle_error").innerHTML= "<img src='images/right.png'/>";  
	}
	if (model=="") {
		document.getElementById("model_error").innerHTML= "<img src='images/wrong.png'/> Please enter model";  
		status=false;
	}else{		  
		document.getElementById("vehicle_subtype_error").innerHTML= "<img src='images/right.png'/>";  
	}	
	if (segment=="NA"){  
		document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";  
		status=false;
	}else{  		  
		document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>"; 
	}	
	
return status;  
}
function editVehicleForm(){  	
 
	var vehicle=$('#vehicle').val();	
	var model=$('#model').val();	
	var segment=$('#segment').val();	
	var flag=$('#flag').val();	
	
	var status=true;	
	if (vehicle=="NA"){  
		document.getElementById("vehicle_error").innerHTML= "<img src='images/wrong.png'/> Please enter vehicle";  
		status=false;
	}else{		  
		document.getElementById("vehicle_error").innerHTML= "<img src='images/right.png'/>";  
	}
	if (model==""){  
		document.getElementById("model_error").innerHTML= "<img src='images/wrong.png'/> Please enter model";  
		status=false;
	}else{		  
		document.getElementById("model_error").innerHTML= "<img src='images/right.png'/>";  
	}	
	if (segment=="NA"){  
		document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";  
		status=false;
	}else{  		  
		document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>"; 
	}	
	if (flag=="NA"){  
		document.getElementById("flag_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";  
		status=false;
	}else{  		  
		document.getElementById("flag_error").innerHTML= "<img src='images/right.png'/>"; 
	}	
	
return status;  
}

/* <!-----------------------End Vehicle----------------------------------------->
<!-----------------------Role-----------------------------------------> */
function roleValidation(){
	var usertype_id=$('#ins_usertype_id').val();
	var role=$('#ins_role').val();
	
	
	
	var status=true;	
	if (usertype_id=="NA"){  
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/wrong.png'/> Please enter User Type";  
		status=false;
	}else{
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (role==""){
		document.getElementById("role_error").innerHTML= "<img src='images/wrong.png'/> Please enter role";  
		status=false;
	}else{
		document.getElementById("role_error").innerHTML= "<img src='images/right.png'/>";  
	}
	
return status;
}

function roleUpdate(){  		 
	var usertype_id=$('#usertype_id').val();
	var role=$('#role').val();
	var flag=$('#flag').val();
	
	var status=true;	
	if (usertype_id=="NA"){  
		document.getElementById("usertype_idd_error").innerHTML= "<img src='images/wrong.png'/> Please select user type"; 		
		status=false;
	}else{		  
		document.getElementById("usertype_idd_error").innerHTML= "<img src='images/right.png'/>"; 
	}
	if (role==""){  
		document.getElementById("rolee_error").innerHTML= "<img src='images/wrong.png'/> Please enter role";  
		status=false;
	}else{  		  
		document.getElementById("rolee_error").innerHTML= "<img src='images/right.png'/>"; 
	}	
	if (flag=="NA"){  
		document.getElementById("flag_error").innerHTML= "<img src='images/wrong.png'/> Please select status";  
		status=false;
	}else{	 
		document.getElementById("flag_error").innerHTML= "<img src='images/right.png'/>"; 
	}	
	
return status;  
}
function Editrole(el){
	
	$('#updaterole').show();
	$('#insertrole').hide();
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	var userTypeId = $(el).parents('td').parents('tr').find('.cls_usertype_id').text();
	/* var complaint_type = $(el).parents('td').parents('tr').find('.cls_complaint_type').text(); */
	$('#usertype_id').val(userTypeId);
	/* complaint_type = complaint_type!=''?complaint_type:'0';
	
		fn_get_complaint_cat(complaint_type); */
	
	
	$('#role').val($(el).parents('td').parents('tr').find('.cls_role').text());
	$('#flag').val(flg);
	$('#dataid').val(el.id);
}
/* <!-----------------------End Role----------------------------------------->
<!-----------------------Users-----------------------------------------> */
function usersFormValidation(){ 
	var flag=$('#flag').val();
	var employee_id=$('#employee_id').val();
	var name=$('#name').val();
	var email=$('#email').val();
	var password=$('#password').val();
	var usertype_id=$('#usertype_id').val();
	var reporting_manager=$('#reporting_manager').val();
	var reporting_manager_ajax=$('#reporting_manager_ajax').val();
	var role=$('#role').val();
	var brand=$('#brand').val();
	var dealer_id=$('#dealer_id').val();
	var mobile=$('#phonenumbers').val();
	var State=$('#state').val();
	var City=$('#City').val();
	var Zone=$('#zone').val();
	var product=$('#product').val();
	var segment=$('#segment').val();
	var complaint_type=$('#complaint_type').val();
	
	var status=true;
	if (usertype_id=="NA") {
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/wrong.png'/> Please enter user type";
		status=false;
	} else {
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/right.png'/>";
	
		if (usertype_id == '3') {			
			if (dealer_id=="NA") {
				document.getElementById("dealer_code_error").innerHTML= "<img src='images/wrong.png'/> Please enter dealer";
				status=false;
			} else {
				document.getElementById("dealer_code_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (product===null ) {
				document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter product";
				status=false;
			} else {
				document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (segment===null ) {
				document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";
				status=false;
			} else {
				document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (brand===null) {
				document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please enter brand";
				status=false;
			} else {
				document.getElementById("brand_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (complaint_type=="NA"){  
				document.getElementById("complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please select complaint type";  
				status=false;
			}else{ 
				document.getElementById("complaint_type_error").innerHTML= "<img src='images/right.png'/>";
			}
		}
		if (usertype_id == '1') {
			if (product===null ) {
				document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter product";
				status=false;
			} else {
				document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (segment===null ) {
				document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";
				status=false;
			} else {
				document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (brand===null) {
				document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please enter brand";
				status=false;
			} else {
				document.getElementById("brand_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (State===null ) {
				document.getElementById("State_error").innerHTML= "<img src='images/wrong.png'/> Please enter State";
				status=false;
			} else {
				document.getElementById("State_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (City===null ) {
				document.getElementById("City_error").innerHTML= "<img src='images/wrong.png'/> Please enter City";
				status=false;
			} else {
				document.getElementById("City_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (Zone===null ) {
				document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter Zone";
				status=false;
			} else {
				document.getElementById("Zone_error").innerHTML= "<img src='images/right.png'/>";
			}
			if (complaint_type=="NA"){  
				document.getElementById("complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please select complaint type";  
				status=false;
			}else{ 
				document.getElementById("complaint_type_error").innerHTML= "<img src='images/right.png'/>";
			}
		}
		
	}
	if (employee_id==""){  
		document.getElementById("employee_id_error").innerHTML= "<img src='images/wrong.png'/> Please enter employee id";  
		status=false;
	}else{ 	
		document.getElementById("employee_id_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (name==""){  
		document.getElementById("name_error").innerHTML= "<img src='images/wrong.png'/> Please enter name";  
		status=false;
	}else{  	
		document.getElementById("name_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (reporting_manager_ajax=="NA" || reporting_manager=='') {  
		document.getElementById("reporting_manager_error").innerHTML= "<img src='images/wrong.png'/> Please enter reporting manager";  
		status=false;
	}else{  	
		document.getElementById("reporting_manager_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	
	
	if (mobile=="")	{  
		document.getElementById("mobile_error").innerHTML= "<img src='images/wrong.png'/> Please enter mobile";  
		status=false;
	}
	else
	{  
		if (isNaN(mobile)||mobile.indexOf(" ")!=-1){
			document.getElementById("mobile_error").innerHTML= " <img src='images/wrong.png'/> Please enter numeric number"; 
			status=false; 
        }
        else if ((mobile.length > 10) || (mobile.length < 10)){
			document.getElementById("mobile_error").innerHTML= " <img src='images/wrong.png'/> Please enter 10 numbers"; 
			status=false; 
		}else{	
			document.getElementById("mobile_error").innerHTML= "<img src='images/right.png'/>";			
		}
	}
	if (email==""){  
		document.getElementById("email_error").innerHTML= "<img src='images/wrong.png'/> Please enter email";  
		status=false;
	}/* else{ 
		if (!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/)){
			document.getElementById("email_error").innerHTML= " <img src='images/wrong.png'/> Please enter valid email format";
			status=false;
		}else{		
			document.getElementById("email_error").innerHTML= "<img src='images/right.png'/>";
		}	
	} */
	if (password==""){  
		document.getElementById("password_error").innerHTML= "<img src='images/wrong.png'/> Please enter password";  
		status=false;
	}else{ 
		document.getElementById("password_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	if (role=="NA"){  
		document.getElementById("role_error").innerHTML= "<img src='images/wrong.png'/> Please enter role";  
		status=false;
	}else{ 
		document.getElementById("role_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	
	/*if (State=="null" && usertype_id != '2'){  
		document.getElementById("State_error").innerHTML= "<img src='images/wrong.png'/> Please enter State";  
		status=false;
	}else{ 
		document.getElementById("State_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (City=="null" && usertype_id != '2')
	{  
		document.getElementById("City_error").innerHTML= "<img src='images/wrong.png'/> Please enter City";  
		status=false;
	}else{  
		document.getElementById("City_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	if (Zoneoptions == '0' && usertype_id != '2'){  
		document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter Zone";  
		status=false;
	}else{
		document.getElementById("Zone_error").innerHTML= "<img src='images/right.png'/>";
	}	*/
	
return status;  
}


function editUser(el){
	
	$('#usertype_id').val($(el).parents('td').parents('tr').find('.cls_usertype_id').text());
	$('#role').val($(el).parents('td').parents('tr').find('.cls_role_id').text());
	$('#employee_id').val($(el).parents('td').parents('tr').find('.cls_employee_id').text());
	
	$('#name').val($(el).parents('td').parents('tr').find('.cls_name').text());
	$('#email').val($(el).parents('td').parents('tr').find('.cls_email').text());
	
	$('#dealer_id').val($(el).parents('td').parents('tr').find('.cls_dealer_code').text());
	
	$('#State').val($(el).parents('td').parents('tr').find('.cls_state').text());
	$('#City').val($(el).parents('td').parents('tr').find('.cls_city').text());	
	$('#zone').val($(el).parents('td').parents('tr').find('.cls_zone').text());
	$('#email').val($(el).parents('td').parents('tr').find('.cls_email').text());
	
	$('#mobile').val($(el).parents('td').parents('tr').find('.cls_mobile').text());
	$('#pincode').val($(el).parents('td').parents('tr').find('.cls_pincode').text());
	
	$('#dataid').val(el.id);
	$('#password').val('****');
	$('#password').attr("disabled", 'disabled');
}
/* <!-----------------------End Users----------------------------------------->

<!-----------------------Area-----------------------------------------> */
function areaValidation(){  		 
	var site_code=$('#site_code').val();		
	var state=$('#state').val();	
	var city=$('#City').val();	
	var zone=$('#zone').val();	
	var CityAjax=$('#CityAjax').val();	
			
	var status=true;	
	if (site_code==""){  
		document.getElementById("site_code_error").innerHTML= "<img src='images/wrong.png'/> Please enter site code";   
		status=false;
	}else{
		document.getElementById("site_code_error").innerHTML= "<img src='images/right.png'/> "; 
	} 
	
	if (zone=="NA"){  
		document.getElementById("zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter region";   
		status=false;
	}else{
		document.getElementById("zone_error").innerHTML= "<img src='images/right.png'/> "; 
	} 
	if (state=="NA"){ 
		document.getElementById("state_error").innerHTML= "<img src='images/wrong.png'/> Please enter state";  		
		status=false;
	}else{
		document.getElementById("state_error").innerHTML= "<img src='images/right.png'/> "; 
	}
	if (city=="" && CityAjax =='NA') {  		 
		document.getElementById("city_error").innerHTML= "<img src='images/wrong.png'/> Please enter site name";  
		status=false;
	}else{  	 
		document.getElementById("city_error").innerHTML= "<img src='images/right.png'/>";
	}	
return status;  
}
function editarea(el){
	var regionId = $(el).parents('td').parents('tr').find('.cls_regionId').text();
	var stateId = $(el).parents('td').parents('tr').find('.cls_stateId').text();
	var cityId = $(el).parents('td').parents('tr').find('.cls_cityId').text();
	var city = $(el).parents('td').parents('tr').find('.cls_city').text();
	fn_Zone_change(regionId,stateId);
	fn_State_change(regionId,stateId,cityId);
	citySearch(regionId,stateId,city);
	$('#td_Status').show();
	$('#CityAjax').show();
	$('#site_code').val($(el).parents('td').parents('tr').find('.cls_site_code').text());	
	$('#site_name').val($(el).parents('td').parents('tr').find('.cls_site_name').text());	
	$('#state').val($(el).parents('td').parents('tr').find('.cls_stateId').text());	
	$('#City').val($(el).parents('td').parents('tr').find('.cls_city').text());	
	$('#zone').val($(el).parents('td').parents('tr').find('.cls_regionId').text());	
	$('#dataid').val(el.id);
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#flag').val(flg);
	
}
/* <!-----------------------End Area----------------------------------------->

<!-----------------------Dealer-----------------------------------------> */
function dealerValidation(){  
	
	var State=$('#state').val();
	var City=$('#City').val();
	var Zone=$('#zone').val();
	var DealerName=$('#DealerName').val();
	//var Zone=($(this).find('input[name="zone[]"]').val()).length;
	var DealerCode=$('#DealerCode').val();
	var DealerType=$('#DealerType').val();
	var product=$('#product').val();
	var brand=$('#brand').val();
	var address=$('#address').val();
	var pincode=$('#pincode').val();
	
	
	var status=true;
 	if (DealerName=="") {
		document.getElementById("DealerName_error").innerHTML= "<img src='images/wrong.png'  /> Please Enter Dealer Name";
		status=false;
	} else {
		document.getElementById("DealerName_error").innerHTML= "<img src='images/right.png' /> ";
	}
	if (pincode=="") {
		document.getElementById("pincode_error").innerHTML= "<img src='images/wrong.png'  /> Please Enter pincode";
		status=false;
	} else {
		document.getElementById("pincode_error").innerHTML= "<img src='images/right.png' /> ";
	}
	if (DealerCode=="") {
		document.getElementById("DealerCode_error").innerHTML= "<img src='images/wrong.png' /> Please Enter Dealer Code";
		status=false;
	} else {
		document.getElementById("DealerCode_error").innerHTML= "<img src='images/right.png'/> ";
	}
	if (DealerType=="NA") {
		document.getElementById("DealerType_error").innerHTML= "<img src='images/wrong.png' /> Please Enter Dealer Type";
		status=false;
	} else {
		document.getElementById("DealerType_error").innerHTML= "<img src='images/right.png'/> ";
	}
	if (address=="") {
		document.getElementById("address_error").innerHTML= "<img src='images/wrong.png' /> Please Enter address";
		status=false;
	} else{
		document.getElementById("address_error").innerHTML= "<img src='images/right.png'  /> ";
	} 
	if (Zone=='NA') {
		document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'  /> Please Enter Zone";
		status=false;
	}else {
		document.getElementById("Zone_error").innerHTML= "<img src='images/right.png' /> ";
	}
	if (State===null)
	{  
		document.getElementById("State_error").innerHTML= "<img src='images/wrong.png' /> Please Enter State";  
		status=false;
	}else {
		document.getElementById("State_error").innerHTML= "<img src='images/right.png' ' /> ";
	}
	if (City===null)
	{  
		document.getElementById("City_error").innerHTML= "<img src='images/wrong.png' '/> Please Enter City";  
		status=false;
	} else {
		document.getElementById("City_error").innerHTML= "<img src='images/right.png' '/>";  
	} 
	if (brand===null) {
	 document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please Enter brand";
		status=false;
	}else {
		document.getElementById("brand_error").innerHTML= "<img src='images/right.png' '/>";  
	}	
	if (product===null) {
	 document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please Enter product";
		status=false;
	} 
	else
	{  
		document.getElementById("product_error").innerHTML= "<img src='images/right.png' /> ";
	}
return status;  
}
/* <!-----------------------End Dealer----------------------------------------->

<!-----------------------New Case-----------------------------------------> */
function newCaseValidate()
{
$('#case_type_error,#center_module_error,#Zone_error,#State_error,#City_error,#product_error,#brands_error,#Dealer_error,#phonenumbers_error').html("<img src='images/right.png'/>");
	var case_type=document.forms["myForm"]["case_type"].value;
	var center_module=document.forms["myForm"]["center_module"].value;
	var Region=document.forms["myForm"]["Zone"].value;
	var vehicle_model=document.forms["myForm"]["vehicle_model"].value;
	var vehicle_registration=document.forms["myForm"]["vehicle_registration"].value;
	var chassis_number=document.forms["myForm"]["chassis_number"].value;
	var complaintcategory=document.forms["myForm"]["complaintcategory"].value;
	
	var City=document.forms["myForm"]["City"].value;
	var product=document.forms["myForm"]["product"].value;
	var brands=document.forms["myForm"]["brands"].value;
	var Dealer=document.forms["myForm"]["Dealer"].value;
	var phonenumbers=document.forms["myForm"]["phonenumbers"].value;
	var email=document.forms["myForm"]["email"].value;
	var description=document.forms["myForm"]["description"].value;	
	var attachment=document.forms["myForm"]["attachment"].value;	
	var status=true;
	if(center_module == '2' || center_module == '4'){
	
		if(attachment=='' || attachment.files.length == 0){
		//if (attachment === null){
			document.getElementById("attachment_error").innerHTML= "<img src='images/wrong.png'/> Please attach file";
			status=false;
		}else{
			document.getElementById("attachment_error").innerHTML= "<img src='images/right.png'/>";
		}
	}
	
	if (case_type=="NA")
	{
		document.getElementById("case_type_error").innerHTML= "<img src='images/wrong.png'/> Please select Case type";
		status=false;
	} else {
		document.getElementById("case_type_error").innerHTML= "<img src='images/right.png'/>";
	}
	if(complaintcategory =='4'){
		if (vehicle_model=="NA")
		{
			document.getElementById("vehicle_model_error").innerHTML= "<img src='images/wrong.png'/> Please select vehicle model";
			status=false;
		} else {
			document.getElementById("vehicle_model_error").innerHTML= "<img src='images/right.png'/>";
		}
		if (vehicle_registration=="" && chassis_number=="" )
		{
			document.getElementById("vehicle_registration_error").innerHTML= "<img src='images/wrong.png'/> Please enter either Registration number or chassis number";
			document.getElementById("chassis_number_error").innerHTML= "<img src='images/wrong.png'/> Please enter either Registration number or chassis number";
			status=false;
		} else {
			document.getElementById("vehicle_registration_error").innerHTML= "<img src='images/right.png'/>";
			document.getElementById("chassis_number_error").innerHTML= "<img src='images/right.png'/>";
		}
		h1 = document.getElementById("chassis_number")
		var att = document.createAttribute("onblur");
		  att.value = "checkAlphaSpecial()";
		  h1.setAttributeNode(att);
	}else{
		h1 = document.getElementById("chassis_number");
		h1.removeAttribute("onblur"); 
		document.getElementById("vehicle_registration_error").innerHTML= "";
		document.getElementById("chassis_number_error").innerHTML= "";
		document.getElementById("vehicle_model_error").innerHTML= "";
	}
	
	if (description=="")
	{
		document.getElementById("description_error").innerHTML= "<img src='images/wrong.png'/> Please select description";
		status=false;
	} else {
		document.getElementById("description_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (center_module=="NA")
	{
		document.getElementById("center_module_error").innerHTML= "<img src='images/wrong.png'/> Please select mode of compalint";
		status=false;
	} else {
		document.getElementById("center_module_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (Dealer=="NA")
	{
		document.getElementById("Dealer_error").innerHTML= "<img src='images/wrong.png'/> Please select Dealer";
		status=false;
	} else {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (Region=="NA")
	{
		document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'/> Please select Region";
		status=false;
	} else {
		document.getElementById("Zone_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	if (City=="NA") {
		document.getElementById("City_error").innerHTML= "<img src='images/wrong.png'/> Please select City";status=false;
	} else {
		document.getElementById("City_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (product=="NA"){
		document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please select Product";status=false;
	} else {
	document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (brands=="NA"){document.getElementById("brands_error").innerHTML= "<img src='images/wrong.png'/> Please select City";status=false;
	} else {
	document.getElementById("brands_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (phonenumbers=="") {
		document.getElementById("phonenumbers_error").innerHTML= "<img src='images/wrong.png'/> Please Phone Number";status=false;
	} else {
		document.getElementById("phonenumbers_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (email=="") {
		document.getElementById("email_error").innerHTML= "<img src='images/wrong.png'/> Please enter email";
		status=false;
	}else {
		/* if (!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/)) {
			document.getElementById("email_error").innerHTML= " <img src='images/wrong.png'/> Please enter valid email format";
			status=false;
		} else {
			document.getElementById("email_error").innerHTML= "<img src='images/right.png'/>";
		} */
	}
	
	return status; 
}



/* <!-----------------------End New Case----------------------------------------->
<!-----------------------Update Case-----------------------------------------> */
function updateCaseValidate(){
	var caseStatus=$('#Status').val();
	var Assigneto=$('#Assigneto').val();
	var case_ownerId=$('#case_ownerId').val();
	
	
	//var attachment = document.getElementById('attachment');
	//var observations=$('#observations').val();
	
	var Comment=$('#Comment').val();
	var communication_customer=$('#communication_customer').val();
	var communication_attachment=$('#communication_attachment').val();
	var mode_communication=$('#mode_communication').val();
	var observations = $('#observations').val();
	var userId = $('#userId').val();
	var status=true;
	if(mode_communication == '2' || mode_communication == '4'){
	
		if(communication_attachment=='' || communication_attachment.files.length == 0){
		//if (attachment === null){
			document.getElementById("communication_attachment_error").innerHTML= "<img src='images/wrong.png'/> Please attach file";
			status=false;
		}else{
			document.getElementById("communication_attachment_error").innerHTML= "<img src='images/right.png'/>";
		}
	}
	if (caseStatus=="NA"){
		document.getElementById("Status_error").innerHTML= "<img src='images/wrong.png'/> Please select Case Status";
		status=false;
	}else{
		if (caseStatus=="ReAssigned"){
			if (Assigneto=="NA"){
				document.getElementById("Assigneto_error").innerHTML= "<img src='images/wrong.png'/> Please select Assigne To";
				status=false;
			}else{
				document.getElementById("Assigneto_error").innerHTML= "<img src='images/right.png'/>";
			}
		}else{
			document.getElementById("Status_error").innerHTML= "<img src='images/right.png'/>";
		}		
		
		if(caseStatus=='Acknowledged'){
			if(observations == ''){
				if(case_ownerId == userId){
					document.getElementById("observations_error").innerHTML= "<img src='images/wrong.png'/> Please enter observations";
					status=false;
				}else{
					document.getElementById("observations_error").innerHTML= "<img src='images/right.png'/>";
				}
			}
		}
		if (caseStatus=="Completed"){
			if(communication_customer ==''){
				document.getElementById("communication_customer_error").innerHTML= "<img src='images/wrong.png'/> Please enter communication customer";
				status=false;
			}else{
				document.getElementById("communication_customer_error").innerHTML= "<img src='images/right.png'/>";
			}
			
		
			
		}
	}
	/*if (observations=="")
	{
		document.getElementById("observations_error").innerHTML= "<img src='images/wrong.png'/> Please Observations";
		status=false;
	} else {
		document.getElementById("observations_error").innerHTML= "<img src='images/right.png'/>";
	}*/
	if (Comment=="")
	{
		document.getElementById("Comment_error").innerHTML= "<img src='images/wrong.png'/> Please Comment";
		status=false;
	} else {
		document.getElementById("Comment_error").innerHTML= "<img src='images/right.png'/>";
	}
    return status;  
}

/* <!-----------------------End Update Case----------------------------------------->

<!-----------------------Complaint-----------------------------------------> */
function complaintValidate()
{
	var complaint_type=document.forms["myForm"]["complaint_type"].value;
	var sub_complaint_type=document.forms["myForm"]["sub_complaint_type"].value;
	var dataid=document.forms["myForm"]["dataid"].value;
	var flag=document.forms["myForm"]["flag"].value;
	var status=true;
	
	if(dataid == ''){
		if (complaint_type=="NA") {
			document.getElementById("complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter complaint type";
			status=false;
		} else {
			document.getElementById("complaint_type_error").innerHTML= "<img src='images/right.png'/>";
		}
		if (sub_complaint_type=="") {
			document.getElementById("sub_complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter sub complaint type";
			status=false;
		} else {
			document.getElementById("sub_complaint_type_error").innerHTML= "<img src='images/right.png'/>";
		}
	}else{
		if (complaint_type=="NA") {
			document.getElementById("complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter complaint type";
			status=false;
		} else {
			document.getElementById("complaint_type_error").innerHTML= "<img src='images/right.png'/>";
		}
		if (sub_complaint_type=="") {
			document.getElementById("sub_complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter sub complaint type";
			status=false;
		} else {
			document.getElementById("sub_complaint_type_error").innerHTML= "<img src='images/right.png'/>";
		}
		if (flag=="NA") {
			document.getElementById("flag_error").innerHTML= "<img src='images/wrong.png'/> Please enter flag";
			status=false;
		} else {
			document.getElementById("flag_error").innerHTML= "<img src='images/right.png'/>";
		}
	}
	
	
	return status;
}


function Editcomplaint_type(el)
{
	$('#td_Status').show(); 
	$('#complaint_type').val($(el).parents('td').parents('tr').find('.cls_complaint_type').text());
	$('#sub_complaint_type').val($(el).parents('td').parents('tr').find('.cls_sub_complaint_type').text());
	$('#dataid').val(el.id);
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#flag').val(flg);
}
/* <!-----------------------End Complaint----------------------------------------->

<!-----------------------Access-----------------------------------------> */
function accessValidation()
{

	var usertype_id=$('#usertype_id').val();
	var role=$('#role').val();
	//var location=$('#location').val();
	var escalate_to=$('#escalate_to').val();
	var escalate_cc=$('#escalate_cc').val();
	//var create_user=$('#create_user').val();
	
	var post_complaint_survey=$('#post_complaint_survey').val();
	var menu_new_case=$('#menu_new_case').val();
	var menu_update_case=$('#menu_update_case').val();
	var menu_report=$('#menu_report').val();
	var menu_dashboard=$('#menu_dashboard').val();
	var approval=$('#approval').val();
	var re_opening=$('#re_opening').val();
	var close_complaint=$('#close_complaint').val();
	var status=true;
	if (close_complaint=="NA")
	{
		document.getElementById("close_complaint_error").innerHTML= "<img src='images/wrong.png'/> Please select close complaint";
		status=false;
	} else {
		document.getElementById("close_complaint_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (approval=="NA")
	{
		document.getElementById("approval_error").innerHTML= "<img src='images/wrong.png'/> Please select approval";
		status=false;
	} else {
		document.getElementById("approval_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (re_opening=="NA")
	{
		document.getElementById("re_opening_error").innerHTML= "<img src='images/wrong.png'/> Please select re opening";
		status=false;
	} else {
		document.getElementById("re_opening_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (menu_update_case=="NA")
	{
		document.getElementById("menu_update_case_error").innerHTML= "<img src='images/wrong.png'/> Please select update case";
		status=false;
	} else {
		document.getElementById("menu_update_case_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (post_complaint_survey=="NA")
	{
		document.getElementById("post_complaint_survey_error").innerHTML= "<img src='images/wrong.png'/> Please select post complaint survey";
		status=false;
	} else {
		document.getElementById("post_complaint_survey_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (usertype_id=="NA")
	{
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/wrong.png'/> Please select user type";
		status=false;
	} else {
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	if (escalate_to=="NA") {
		document.getElementById("escalate_to_error").innerHTML= "<img src='images/wrong.png'/> Please select escalate to";
		status=false;
	} else {
		document.getElementById("escalate_to_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (escalate_cc=="NA") {
		document.getElementById("escalate_cc_error").innerHTML= "<img src='images/wrong.png'/> Please select escalate cc";
		status=false;
	} else {
		document.getElementById("escalate_cc_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (menu_new_case=="NA") {
		document.getElementById("menu_new_case_error").innerHTML= "<img src='images/wrong.png'/> Please select new case";
		status=false;
	} else {
		document.getElementById("menu_new_case_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	if (menu_report=="NA") {
		document.getElementById("menu_report_error").innerHTML= "<img src='images/wrong.png'/> Please select report";
		status=false;
	} else {
		document.getElementById("menu_report_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (menu_dashboard=="NA") {
		document.getElementById("menu_dashboard_error").innerHTML= "<img src='images/wrong.png'/> Please select dashboard";
		status=false;
	}
 else {
		 document.getElementById("menu_dashboard_error").innerHTML= "<img src='images/right.png'/>";
	}
	return status;
}
/* <!-----------------------End Access----------------------------------------->

<!-----------------------File Settings-----------------------------------------> */
function fileSettingValidation()
{
	var function_type=$('#function_type').val();
	var size=$('#size').val();
	var file_format=$('#file_format').val();
	
	var status=false;
	if (function_type=="") {
		document.getElementById("function_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter function type";
		status=false;
	} else if (size=="") {
		document.getElementById("size_error").innerHTML= "<img src='images/wrong.png'/> Please enter size";
		status=false;
	} else if (file_format=="") {
		document.getElementById("file_format_error").innerHTML= "<img src='images/wrong.png'/> Please enter file format";
		status=false;
	}else{
		status=true;
	}
	return status;
}
/* <!-----------------------End File Settings----------------------------------------->

<!-----------------------Js-----------------------------------------> */
$(document).ready(function () 
 {
	$('#dob,#datefrom,#dateto,#dop,#dos').datetimepicker({format:'Y-m-d',timepicker:false});
	$('#datefrom1,#dateto1').datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
	$("#phonenumbers").keypress(function (e) 
	{
		$(this).attr('maxlength','10');
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		$("#errmsg").html("Digits Only").show().fadeOut("slow");
		return false;
	}
    });
   /*  $('#email').blur(function()
	    {
	    	$('#email_error').hide();
	    	var email= this.value;
		if (!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/))
		{
			$('#email_error').show();
	document.getElementById("email_error").innerHTML= " <img src='images/wrong.png' style='height: 15px;width:15px;'/> Please enter valid email format";
	return false;
		}
	   }); */
});

/* <!-----------------------End Customer Master-----------------------------------------> */
function customerValidate(){
	var customerID=$('#customerID').val();
	var customerOrg=$('#customerOrg').val();
	var vehicle=$('#vehicle').val();
	var segment=$('#segment').val();
	var address=$('#address').val();
	var zone=$('#zone').val();
	var state=$('#state').val();
	var City=$('#City').val();
	var pincode=$('#pincode').val();
	var sales_POC_1=$('#sales_POC_1').val();
	var sales_POC_2=$('#sales_POC_2').val();
	var segment1=$('#segment1').val();
	var segment2=$('#segment2').val();	
	var dataid=document.forms["myForm"]["dataid"].value;
	var status=true;
	if (customerID=="") {
		document.getElementById("customerID_error").innerHTML= "<img src='images/wrong.png'/> Please enter customer ID";
		status=false;
	} else {
		document.getElementById("customerID_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (customerOrg=="") {
		document.getElementById("customerOrg_error").innerHTML= "<img src='images/wrong.png'/> Please enter customer organisation";
		status=false;
	} else {
		document.getElementById("customerOrg_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (vehicle=="NA") {
		document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter Product";
		status=false;
	} else {
		document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (segment===null) {
		document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (address=="") {
		document.getElementById("address_error").innerHTML= "<img src='images/wrong.png'/> Please enter address";
		status=false;
	} else {
		document.getElementById("address_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (zone=="NA") {
		document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter region";
		status=false;
	} else {
		document.getElementById("Zone_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (state=="NA") {
		document.getElementById("State_error").innerHTML= "<img src='images/wrong.png'/> Please enter state";
		status=false;
	} else {
		document.getElementById("State_error").innerHTML= "<img src='images/right.png'/>";
	}if (City=="NA") {
		document.getElementById("City_error").innerHTML= "<img src='images/wrong.png'/> Please enter site";
		status=false;
	} else {
		document.getElementById("City_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	if (pincode=="") {
		document.getElementById("pincode_error").innerHTML= "<img src='images/wrong.png'/> Please enter pincode";
		status=false;
	} 
	if (sales_POC_1=="NA") {
		document.getElementById("sales_POC_1_error").innerHTML= "<img src='images/wrong.png'/> Please enter sales POC 1";
		status=false;
	} else {
		document.getElementById("sales_POC_1_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (segment1=="NA") {
		document.getElementById("segment1_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment 1";
		status=false;
	} else {
		document.getElementById("segment1_error").innerHTML= "<img src='images/right.png'/>";
	}
	if(sales_POC_2 !=''){
		if(segment2 =='NA'){
			document.getElementById("segment2_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment 2";
			status=false;
		} else {
			document.getElementById("segment2_error").innerHTML= "<img src='images/right.png'/>";
		}
	}
	if(segment2 !='NA'){
		if(sales_POC_2 ==''){
			document.getElementById("sales_POC_2_error").innerHTML= "<img src='images/wrong.png'/> Please enter sales POC 2";
			status=false;
		} else {
			document.getElementById("sales_POC_2_error").innerHTML= "<img src='images/right.png'/>";
		}
	}	
	return status;
}
/*function customerContactValidate(){
	var custname=$('#custname').val();
	var custrole=$('#custrole').val();
	var locate=$('#locate').val();
	var support_type=$('#support_type').val();
	var scope_of_services=$('#scope_of_services').val();
	var region=$('#region').val();
	var city=$('#city').val();
	var mobile1=$('#mobile1').val();
	var mobile2=$('#mobile2').val();
	var email=$('#email').val();
	var pri_sec=$('#pri_sec').val();
	var dealer_code_asoc=$('#dealer_code_asoc').val();
	var brand=$('#brand').val();	
	var vehicle=$('#vehicle').val();	
	var segment	=$('#segment').val();	
	var complaint_cat=$('#complaint_cat').val();	
	var dataid=document.forms["myForm"]["dataid"].value;
	var status=true;
	if (custname=="") {
		document.getElementById("custname_error").innerHTML= "Please enter customer name";
		status=false;
	} else {
		document.getElementById("custname_error").innerHTML= "";
	}
	if (custrole=="") {
		document.getElementById("custrole_error").innerHTML= "Please enter customer role";
		status=false;
	} else {
		document.getElementById("custrole_error").innerHTML= "";
	}
	if (locate=="") {
		document.getElementById("locate_error").innerHTML= "Please enter Located at";
		status=false;
	} else {
		document.getElementById("locate_error").innerHTML= "";
	}
	if (support_type=='NA') {
		document.getElementById("support_type_error").innerHTML= "Please enter support type";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "";
	}
	if (scope_of_services===null) {
		document.getElementById("scope_of_services_error").innerHTML= "Please enter scope of services";
		status=false;
	} else {
		document.getElementById("scope_of_services_error").innerHTML= "";
	}
	if (region=="NA") {
		document.getElementById("region_error").innerHTML= "Please enter region";
		status=false;
	} else {
		document.getElementById("region_error").innerHTML= "";
	}
	if (city=="NA") {
		document.getElementById("city_error").innerHTML= "Please enter city";
		status=false;
	} else {
		document.getElementById("city_error").innerHTML= "";
	}if (mobile1=="") {
		document.getElementById("mobile1_error").innerHTML= "Please enter mobile1";
		status=false;
	} else {
		fn_mob_change(mobile1);	
		document.getElementById("mobile1_error").innerHTML= "";
	}
	if (mobile2!="") {
		if (mobile1 == mobile2) {
			document.getElementById("mobile2_error").innerHTML= "Mobile 1 and Mobile 2 cannot be duplicates";
			status=false;
		} else {
			fn_mob_change1(mobile2);
			document.getElementById("mobile2_error").innerHTML= "";
		}
	}
	
	if (email=="") {
		document.getElementById("email_error").innerHTML= "Please enter email";
		status=false;
	} else {
		document.getElementById("email_error").innerHTML= "";
	}
	if (pri_sec=="NA") {
		document.getElementById("pri_sec_error").innerHTML= "Please enter primary/secondary";
		status=false;
	} else {
		document.getElementById("pri_sec_error").innerHTML= "";
	}
	if (dealer_code_asoc===null) {
		document.getElementById("dealer_code_asoc_error").innerHTML= "Please enter dealer code";
		status=false;
	} else {
		document.getElementById("dealer_code_asoc_error").innerHTML= "";
	}
	if (brand===null) {
		document.getElementById("brand_error").innerHTML= "Please enter brand";
		status=false;
	} else {
		document.getElementById("brand_error").innerHTML= "";
	}
	if (segment===null) {
		document.getElementById("segment_error").innerHTML= "Please enter segment";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "";
	}
	if (complaint_cat===null) {
		document.getElementById("complaint_cat_error").innerHTML= "Please enter complaint category";
		status=false;
	} else {
		document.getElementById("complaint_cat_error").innerHTML= "";
	}
	if (vehicle=='NA') {
		document.getElementById("product_error").innerHTML= "Please enter product";
		status=false;
	} else {
		document.getElementById("product_error").innerHTML= "";
	}
	
	return status;
}*/
function Editcustomer(el)
{

	var product = $(el).parents('td').parents('tr').find('.cls_productId').text();
	var zone  = $(el).parents('td').parents('tr').find('.cls_zoneId').text();
	var segment = $(el).parents('td').parents('tr').find('.cls_segmentId').text();
	var state = $(el).parents('td').parents('tr').find('.cls_stateId').text();
	var city = $(el).parents('td').parents('tr').find('.cls_CityId').text();
	var segment1 = $(el).parents('td').parents('tr').find('.cls_segment1').text();
	var segment2 = $(el).parents('td').parents('tr').find('.cls_segment2').text();
	
	
	$('#vehicle').val(product);
	
	fn_product_change(product,segment,segment1,segment2);
	//$('#segment').val();
	$('#zone').val(zone);
	Dealer_Zone_change(zone,state);
	Dealer_State_change(zone,state,city);
	//$('#state').val();
	//$('#City').val();
	$('#customerID').val($(el).parents('td').parents('tr').find('.cls_customerID').text());
	$('#customerOrg').val($(el).parents('td').parents('tr').find('.cls_customerOrg ').text());
	$('#address').val($(el).parents('td').parents('tr').find('.cls_address').text());
	$('#pincode').val($(el).parents('td').parents('tr').find('.cls_pincode').text());
	$('#sales_account_manager').val($(el).parents('td').parents('tr').find('.cls_sales_account_manager').text());
	$('#sales_POC_1').val($(el).parents('td').parents('tr').find('.cls_sales_POC_1').text());
	$('#sales_POC_2').val($(el).parents('td').parents('tr').find('.cls_sales_POC_2').text());
	//$('#segment1').val($(el).parents('td').parents('tr').find('.cls_segment1').text());
	//$('#segment2').val($(el).parents('td').parents('tr').find('.cls_segment2').text());
	
	$('#dataid').val(el.id);
	
}
function EditcustomerContact(el)
{

	
	var city = $(el).parents('td').parents('tr').find('.cls_city').text();
	var region = $(el).parents('td').parents('tr').find('.cls_region').text();
	var dealer = $(el).parents('td').parents('tr').find('.cls_dealer_code_asoc').text();
	var brand = $(el).parents('td').parents('tr').find('.cls_brand').text();
	var vehicle = $(el).parents('td').parents('tr').find('.cls_vehicle').text();
	var segment = $(el).parents('td').parents('tr').find('.cls_segment').text();
	var scope_of_services = $(el).parents('td').parents('tr').find('.cls_scope_of_services').text();
	var complaint_cat = $(el).parents('td').parents('tr').find('.cls_complaint_cat').text()
	//alert($(el).parents('td').parents('tr').find('.cls_region').text());
	//$('#city').val(city);
	$('#custname').val($(el).parents('td').parents('tr').find('.cls_custname').text());
	$('#custrole').val($(el).parents('td').parents('tr').find('.cls_custrole ').text());
	$('#locate').val($(el).parents('td').parents('tr').find('.cls_locate').text());
	$('#support_type').val($(el).parents('td').parents('tr').find('.cls_support_type').text());
	//$('#scope_of_services').val($(el).parents('td').parents('tr').find('.cls_scope_of_services').text());
	$('#mobile1').val($(el).parents('td').parents('tr').find('.cls_mobile1').text());
	$('#mobile2').val($(el).parents('td').parents('tr').find('.cls_mobile2').text());
	$('#email').val($(el).parents('td').parents('tr').find('.cls_email').text());
	$('#pri_sec').val($(el).parents('td').parents('tr').find('.cls_pri_sec').text());
	//$('#dealer_code_asoc').val($(el).parents('td').parents('tr').find('.cls_dealer_code_asoc').text());
	$('#segment').val($(el).parents('td').parents('tr').find('.cls_segment').text());
	//$('#complaint_cat').val($(el).parents('td').parents('tr').find('.cls_complaint_cat').text());
	$('#region').val($(el).parents('td').parents('tr').find('.cls_region').text());
	$('#vehicle').val(vehicle);
	fn_get_complaint_cat(complaint_cat);
	fn_get_city_zone_id(region,city);
	fn_get_scope_Service(scope_of_services);
	fn_get_brand(brand);
	fn_get_dealer(dealer);
	fn_product_change(vehicle,segment,'','')
	$('#dataid').val(el.id);
}
/* <!-----------------------End Customer Master----------------------------------------->
<!-----------------------Escalation Master-----------------------------------------> */
function escalationFormValidate(){
	
	var complaint_type=$('#complaint_type').val();
	var sub_complaint_type=$('#sub_complaint_type').val();
	var vehicle=$('#vehicle').val();
	var segment=$('#segment').val();
	
	var escalation_stage=$('#escalation_stage').val();
	var day=$('#day').val();
	var escalated_to=$('#escalated_to').val();
	var cc_to=$('#cc_to').val();
	var matrix_identifier=$('#matrix_identifier').val();
	
	var status=true;
	if(matrix_identifier ==''){
		if (matrix_identifier=="") {
			document.getElementById("matrix_identifier_error").innerHTML= " Please enter matrix identifier";
			status=false;
		} else {
			document.getElementById("matrix_identifier_error").innerHTML= "";
		}
		if (complaint_type=="NA") {
			document.getElementById("complaint_type_error").innerHTML= " Please enter complaint type";
			status=false;
		} else {
			document.getElementById("complaint_type_error").innerHTML= "";
		}
		
		if (sub_complaint_type===null) {
			document.getElementById("sub_complaint_type_error").innerHTML= " Please enter sub complaint type";
			status=false;
		} else {
			document.getElementById("sub_complaint_type_error").innerHTML= "";
		}
		if (vehicle=="NA") {
			document.getElementById("vehicle_error").innerHTML= "Please enter product";
			status=false;
		} else {
			document.getElementById("vehicle_error").innerHTML= "";
		}
		if (segment===null) {
			document.getElementById("segment_error").innerHTML= " Please enter segment";
			status=false;
		} else {
			document.getElementById("segment_error").innerHTML= "";
		}
	}
	
	if (escalation_stage=="NA") {
		document.getElementById("escalation_stage_error").innerHTML= " Please enter escalation stage";
		status=false;
	} else {
		document.getElementById("escalation_stage_error").innerHTML= "";
	}
	if (day=="NA") {
		document.getElementById("day_error").innerHTML= " Please enter day";
		status=false;
	} else {
		document.getElementById("day_error").innerHTML= "";
	}
	if (escalated_to=="NA") {
		document.getElementById("escalated_to_error").innerHTML= " Please enter escalated to";
		status=false;
	} else {
		document.getElementById("escalated_to_error").innerHTML= "";
	}
	if (cc_to ===null) {
		document.getElementById("cc_to_error").innerHTML= " Please enter cc to";
		status=false;
	} else {
		document.getElementById("cc_to_error").innerHTML= "";
	}
	
	
		
	return status;
}
function EditEscalation(el){
	
	
	var Complaint_id =$(el).parents('td').parents('tr').find('.cls_complaint_ID').text();
	var sub_Complaint_id =$(el).parents('td').parents('tr').find('.cls_sub_complaint_type').text();
	var region_id = $(el).parents('td').parents('tr').find('.cls_Region_ID').text();
	var city_id = $(el).parents('td').parents('tr').find('.cls_City_ID').text();
	var segment = $(el).parents('td').parents('tr').find('.cls_segment').text();
	var vehicle = $(el).parents('td').parents('tr').find('.cls_vehicle_ID').text();
	var cc_to_id = $(el).parents('td').parents('tr').find('.cls_Cc_To_ID').text();
	//alert(sub_Complaint_id);
	 getCcRole(cc_to_id);
	$('#complaint_type').val(Complaint_id);
	//User_get_product(vehicle);
	//User_product_change(vehicle,segment);
	//getSubComplaint(Complaint_id,sub_Complaint_id);
	$('#vehicle').val(vehicle);
	$('#region').val(region_id);
	//zone_change(region_id,city_id);
	//$('#City').val();
	
	$('#escalation_stage').val($(el).parents('td').parents('tr').find('.cls_escalation_stage').text());
	//$('#sub_complaint_type').val(sub_Complaint_id);
	$('#day').val($(el).parents('td').parents('tr').find('.cls_day').text());
	$('#escalated_to').val($(el).parents('td').parents('tr').find('.cls_Escalated_To_ID').text());
	
	$('#DataID').val(el.id);
	
	
}
/* <!-----------------------End Escalation Master----------------------------------------->
<!-----------------------Communication Master-----------------------------------------> */
function communicationFormValidate(){
	
	var complaint_type=document.forms["myForm"]["complaint_type"].value;
	var vehicle=document.forms["myForm"]["vehicle"].value;
	var region=document.forms["myForm"]["region"].value;
	var City=document.forms["myForm"]["City"].value;	
	var communication_stage=document.forms["myForm"]["communication_stage"].value;
	var day=document.forms["myForm"]["day"].value;
	var escalated_to=document.forms["myForm"]["escalated_to"].value;
	var cc_to=document.forms["myForm"]["cc_to"].value;
	var email_msg=document.forms["myForm"]["email_msg"].value;
	var sms_msg=document.forms["myForm"]["sms_msg"].value;
	
	
	var status=true;
	if (complaint_type=="NA") {
		document.getElementById("complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter complaint type";
		status=false;
	} else {
		document.getElementById("complaint_type_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (vehicle=="NA") {
		document.getElementById("vehicle_error").innerHTML= "<img src='images/wrong.png'/> Please enter vehicle";
		status=false;
	} else {
		document.getElementById("vehicle_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (region=="NA") {
		document.getElementById("region_error").innerHTML= "<img src='images/wrong.png'/> Please enter region";
		status=false;
	} else {
		document.getElementById("region_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (City=="NA") {
		document.getElementById("City_error").innerHTML= "<img src='images/wrong.png'/> Please enter city";
		status=false;
	} else {
		document.getElementById("City_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (communication_stage=="NA") {
		document.getElementById("communication_stage_error").innerHTML= "<img src='images/wrong.png'/> Please enter communication stage";
		status=false;
	} else {
		document.getElementById("communication_stage_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (day=="NA") {
		document.getElementById("day_error").innerHTML= "<img src='images/wrong.png'/> Please enter day";
		status=false;
	} else {
		document.getElementById("day_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (escalated_to=="NA") {
		document.getElementById("escalated_to_error").innerHTML= "<img src='images/wrong.png'/> Please enter escalated to";
		status=false;
	} else {
		document.getElementById("escalated_to_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (cc_to=="NA") {
		document.getElementById("cc_to_error").innerHTML= "<img src='images/wrong.png'/> Please enter cc to";
		status=false;
	} else {
		document.getElementById("cc_to_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (email_msg=="") {
		document.getElementById("email_msg_error").innerHTML= "<img src='images/wrong.png'/> Please enter email message";
		status=false;
	} else {
		document.getElementById("email_msg_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (sms_msg=="") {
		document.getElementById("sms_msg_error").innerHTML= "<img src='images/wrong.png'/> Please enter sms message";
		status=false;
	} else {
		document.getElementById("sms_msg_error").innerHTML= "<img src='images/right.png'/>";
	}
		
	return status;
}
function EditCommunication(el){
	var Complaint_id =$(el).parents('td').parents('tr').find('.cls_complaint_ID').text();
	var sub_Complaint_id =$(el).parents('td').parents('tr').find('.cls_sub_complaint_type').text();
	var region_id = $(el).parents('td').parents('tr').find('.cls_Region_ID').text();
	var city_id = $(el).parents('td').parents('tr').find('.cls_City_ID').text();
	var segment = $(el).parents('td').parents('tr').find('.cls_segment').text();
	var vehicle = $(el).parents('td').parents('tr').find('.cls_vehicle_ID').text();
	$('#complaint_type').val($(el).parents('td').parents('tr').find('.cls_complaint_ID').text());
	$('#vehicle').val(vehicle);
	$('#email_msg').val($(el).parents('td').parents('tr').find('.cls_email_msg').text());
	$('#sms_msg').val($(el).parents('td').parents('tr').find('.cls_sms_msg').text());
	$('#region').val(region_id);
	User_get_product(vehicle);
	User_product_change(vehicle,segment);
	getSubComplaint(Complaint_id,sub_Complaint_id);
	zone_change(region_id,city_id);
	//$('#City').val();
	
	$('#communication_stage').val($(el).parents('td').parents('tr').find('.cls_communication_stage').text());
	$('#sub_complaint_type').val(sub_Complaint_id);
	$('#day').val($(el).parents('td').parents('tr').find('.cls_day').text());
	$('#escalated_to').val($(el).parents('td').parents('tr').find('.cls_Escalated_To_ID').text());
	$('#cc_to').val($(el).parents('td').parents('tr').find('.cls_Cc_To_ID').text());
	$('#DataID').val(el.id);
	
	
}
/* <!-----------------------End Communication Master----------------------------------------->
<!-----------------------Consolidated Report-----------------------------------------> */
function consolidatedReportValidate()
{
	

	var datefrom=$('#datefrom1').val();
	var dateto=$('#dateto1').val();
	var brand=$('#brand').val();
	var product=$('#product').val();

	var segment=$('#segment').val();
	var zone=$('#zone').val();
	var Dealer=$('#Dealer').val();
	var Status=$('#Status').val();
	var status=true;
	if (datefrom=="") {
		document.getElementById("datefrom_error").innerHTML= "<img src='images/wrong.png'/> Please enter date from";
		status=false;
	} else {
		document.getElementById("datefrom_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (dateto=="") {
		document.getElementById("dateto_error").innerHTML= "<img src='images/wrong.png'/> Please enter date to";
		status=false;
	} else {
		document.getElementById("dateto_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (brand===null) {
		document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please enter brand";
		status=false;
	} else {
		document.getElementById("brand_error").innerHTML= "<img src='images/right.png'/>";
	}if (product===null) {
		document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter product";
		status=false;
	} else {
		document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (segment===null) {
		document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (zone===null) {
		document.getElementById("zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter zone";
		status=false;
	} else {
		document.getElementById("zone_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (Dealer===null) {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/wrong.png'/> Please enter Dealer";
		status=false;
	} else {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (Status===null) {
		document.getElementById("Status_error").innerHTML= "<img src='images/wrong.png'/> Please enter Status";
		status=false;
	} else {
		document.getElementById("Status_error").innerHTML= "<img src='images/right.png'/>";
	}
return status;	
}
/* <!-----------------------End Consolidated Report----------------------------------------->
<!-----------------------Top Focus Report-----------------------------------------> */
function topFocusValidate()
{
	

	
	var brand=$('#brand').val();
	var product=$('#product').val();

	var segment=$('#segment').val();
	var zone=$('#zone').val();
	var Dealer=$('#Dealer').val();
	
	var status=true;
	
	if (brand===null) {
		document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please enter brand";
		status=false;
	} else {
		document.getElementById("brand_error").innerHTML= "<img src='images/right.png'/>";
	}if (product===null) {
		document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter product";
		status=false;
	} else {
		document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (segment===null) {
		document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (zone===null) {
		document.getElementById("zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter zone";
		status=false;
	} else {
		document.getElementById("zone_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (Dealer===null) {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/wrong.png'/> Please enter Dealer";
		status=false;
	} else {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/right.png'/>";
	}
	
return status;	
}
/* <!-----------------------End Top Focus Report----------------------------------------->
<!-----------------------PCS Report-----------------------------------------> */
function pcsValidate()
{



	var year=$('#year').val();
	var zone1=$('#zone1').val();
	var Dealer=$('#Dealer').val();

	var status=true;
	if (year=='' || year=="NA") {
		document.getElementById("year_error").innerHTML= "<img src='images/wrong.png'/> Please enter year";
		status=false;
	} else {
		document.getElementById("year_error").innerHTML= "<img src='images/right.png'/>";
	}if (zone1===null) {
		document.getElementById("zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter zone";
		status=false;
	} else {
		document.getElementById("zone_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (Dealer===null) {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/wrong.png'/> Please enter Dealer";
		status=false;
	} else {
		document.getElementById("Dealer_error").innerHTML= "<img src='images/right.png'/>";
	}


return status;
}
function pcsValidate1(){
	var year=$('#year1').val();
	var zone1=$('#zone2').val();
	var Dealer=$('#Dealer1').val();

	var status=true;
	if (year=='' || year=="NA") {
		document.getElementById("year_error1").innerHTML= "<img src='images/wrong.png'/> Please enter year";
		status=false;
	} else {
		document.getElementById("year_error1").innerHTML= "<img src='images/right.png'/>";
	}if (zone1===null) {
		document.getElementById("zone_error1").innerHTML= "<img src='images/wrong.png'/> Please enter zone";
		status=false;
	} else {
		document.getElementById("zone_error1").innerHTML= "<img src='images/right.png'/>";
	}
	if (Dealer===null) {
		document.getElementById("Dealer_error1").innerHTML= "<img src='images/wrong.png'/> Please enter Dealer";
		status=false;
	} else {
		document.getElementById("Dealer_error1").innerHTML= "<img src='images/right.png'/>";
	}


return status;
}
/* <!-----------------------End PCS Report----------------------------------------->
<!-----------------------Deale Summary Report-----------------------------------------> */
function dealerSummaryReportValidate()
{
	

	var datefrom=$('#datefrom1').val();
	var dateto=$('#dateto1').val();
	
	var product=$('#product').val();

	var segment=$('#segment').val();
	var complaintType=$('#complaintType').val();
	var status=true;
	if (datefrom=="") {
		document.getElementById("datefrom_error").innerHTML= "<img src='images/wrong.png'/> Please enter date from";
		status=false;
	} else {
		document.getElementById("datefrom_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (dateto=="") {
		document.getElementById("dateto_error").innerHTML= "<img src='images/wrong.png'/> Please enter date to";
		status=false;
	} else {
		document.getElementById("dateto_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (product===null) {
		document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter product";
		status=false;
	} else {
		document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (segment===null) {
		document.getElementById("segment_error").innerHTML= "<img src='images/wrong.png'/> Please enter segment";
		status=false;
	} else {
		document.getElementById("segment_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (complaintType===null) {
		document.getElementById("complaint_type_error").innerHTML= "<img src='images/wrong.png'/> Please enter complaint type";
		status=false;
	} else {
		document.getElementById("complaint_type_error").innerHTML= "<img src='images/right.png'/>";
	}
	
return status;	
}
/* <!-----------------------End Deale Summary Report----------------------------------------->
	
<!-----------------------End Js-----------------------------------------> */