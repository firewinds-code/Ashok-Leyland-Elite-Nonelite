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
<!-----------------------End contactModule----------------------------------------->

<!-----------------------Vehicle----------------------------------------->
function vehicleValidation(){  		 
	var vehicle=document.forms["myForm"]["vehicle"].value;	
	var vehicle_subtype=document.forms["myForm"]["vehicle_subtype"].value;		
	var segment=document.forms["myForm"]["segment"].value;		
			
	var status=true;	
	if (vehicle=="NA"){  
		document.getElementById("vehicle_error").innerHTML= "<img src='images/wrong.png'/> Please enter vehicle";  
		status=false;
	}else{		  
		document.getElementById("vehicle_error").innerHTML= "<img src='images/right.png'/>";  
	}
	if (vehicle_subtype==""){  
		document.getElementById("vehicle_subtype_error").innerHTML= "<img src='images/wrong.png'/> Please enter model";  
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
function editvehicle(el){
	$('#updatevehicle').show();
	$('#insertvehicle').hide();
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#vehicle').val($(el).parents('td').parents('tr').find('.cls_vehicle').text());	
	$('#segment').val($(el).parents('td').parents('tr').find('.cls_segment').text());	
	$('#model').val($(el).parents('td').parents('tr').find('.cls_model').text());	
	$('#flag').val(flg);	
	$('#dataid').val(el.id);
	
}
<!-----------------------End Vehicle----------------------------------------->
<!-----------------------Role----------------------------------------->
function roleValidation(){  		 
	var usertype_id=document.forms["myForm"]["usertype_id"].value;
	var role=document.forms["myForm"]["role"].value;	
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
	$('#usertype_id').val($(el).parents('td').parents('tr').find('.cls_usertype_id').text());
	$('#role').val($(el).parents('td').parents('tr').find('.cls_role').text());
	$('#flag').val(flg);
	$('#dataid').val(el.id);
}
<!-----------------------End Role----------------------------------------->
<!-----------------------Users----------------------------------------->
function usersFormValidation(){ 

	var employee_id=$('#employee_id').val();
	var name=$('#name').val();
	var email=$('#email').val();
	var password=$('#password').val();
	var usertype_id=$('#usertype_id').val();
	var role=$('#role').val();
	var dealer_code=$('#dealer_code').val();
	var mobile=$('#phonenumbers').val();
	var State=$('#State').val();
	var City=$('#City').val();
	var Zone=$('#Zone').val();
	var product=$('#product').val();
	var select =  document.forms["myForm"];
	var Zoneoptions = ["Zone[]"].options;
	var Cityoptions = ["City[]"].options;
	var Stateoptions = ["Zone[]"].options;
	
	var status=true;	
	if (employee_id=="" && usertype_id == '2'){  
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
	if (product=="NA" && usertype_id == '3' ){  
		document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please enter product";  
		status=false;
	}else{  	
		document.getElementById("product_error").innerHTML= "<img src='images/right.png'/>";
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
	}else{ 
		if (!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/)){
			document.getElementById("email_error").innerHTML= " <img src='images/wrong.png'/> Please enter valid email format";
			status=false;
		}else{		
			document.getElementById("email_error").innerHTML= "<img src='images/right.png'/>";
		}	
	}
	if (password==""){  
		document.getElementById("password_error").innerHTML= "<img src='images/wrong.png'/> Please enter password";  
		status=false;
	}else{ 
		document.getElementById("password_error").innerHTML= "<img src='images/right.png'/>";
	}
	
	
	if (usertype_id=="NA"){  
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/wrong.png'/> Please enter user type";  
		status=false;
	}else{
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (role=="NA"){  
		document.getElementById("role_error").innerHTML= "<img src='images/wrong.png'/> Please enter role";  
		status=false;
	}else{ 
		document.getElementById("role_error").innerHTML= "<img src='images/right.png'/>";
	}
	if (dealer_code=="" && usertype_id == '3'){  
		document.getElementById("dealer_code_error").innerHTML= "<img src='images/wrong.png'/> Please enter dealer code";  
		status=false;
	}else{
		document.getElementById("dealer_code_error").innerHTML= "<img src='images/right.png'/>";
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
	
	$('#dealer_code').val($(el).parents('td').parents('tr').find('.cls_dealer_code').text());
	
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
<!-----------------------End Users----------------------------------------->

<!-----------------------Area----------------------------------------->
function areaValidation(){  		 
	var site_code=$('#site_code').val();	
	var site_name=$('#site_name').val();	
	var state=$('#state').val();	
	var city=$('#city').val();	
	var zone=$('#zone').val();	
			
	var status=true;	
	if (site_code==""){  
		document.getElementById("site_code_error").innerHTML= "<img src='images/wrong.png'/> Please enter site code";   
		status=false;
	}else{
		document.getElementById("site_code_error").innerHTML= "<img src='images/right.png'/> "; 
	} 
	if (site_name==""){  
		document.getElementById("site_name_error").innerHTML= "<img src='images/wrong.png'/> Please enter site name";   
		status=false;
	}else{
		document.getElementById("site_name_error").innerHTML= "<img src='images/right.png'/> "; 
	} 
	if (zone==""){  
		document.getElementById("zone_error").innerHTML= "<img src='images/wrong.png'/> Please enter zone";   
		status=false;
	}else{
		document.getElementById("zone_error").innerHTML= "<img src='images/right.png'/> "; 
	} 
	if (state==""){ 
		document.getElementById("state_error").innerHTML= "<img src='images/wrong.png'/> Please enter state";  		
		status=false;
	}else{
		document.getElementById("state_error").innerHTML= "<img src='images/right.png'/> "; 
	}
	if (city==""){  		 
		document.getElementById("city_error").innerHTML= "<img src='images/wrong.png'/> Please enter city";  
		status=false;
	}else{  	 
		document.getElementById("city_error").innerHTML= "<img src='images/right.png'/>";
	}	
return status;  
}
function editarea(el){
	$('#td_Status').show();
	$('#site_code').val($(el).parents('td').parents('tr').find('.cls_site_code').text());	
	$('#site_name').val($(el).parents('td').parents('tr').find('.cls_site_name').text());	
	$('#state').val($(el).parents('td').parents('tr').find('.cls_state').text());	
	$('#city').val($(el).parents('td').parents('tr').find('.cls_city').text());	
	$('#zone').val($(el).parents('td').parents('tr').find('.cls_zone').text());	
	$('#dataid').val(el.id);
	var flg=$(el).parents('td').parents('tr').find('.cls_flag').text();
	if(flg.trim()=='Active'){
		flg='1';
	}else{
		flg='0';
	}
	$('#flag').val(flg);
}
<!-----------------------End Area----------------------------------------->

<!-----------------------Dealer----------------------------------------->
function dealerValidation(){  
document.getElementById("State_error").innerHTML= "";
document.getElementById("City_error").innerHTML= "";
document.getElementById("Zone_error").innerHTML= "";
document.getElementById("DealerName_error").innerHTML= "";
document.getElementById("DealerCode_error").innerHTML= ""; 
	var State=document.forms["myForm"]["State"].value;
	var City=document.forms["myForm"]["City"].value;
	var Zone=document.forms["myForm"]["Zone"].value;
	var DealerName=document.forms["myForm"]["DealerName"].value;
	var DealerCode=document.forms["myForm"]["DealerCode"].value;
	var DealerType=document.forms["myForm"]["DealerType"].value;
	var product=document.forms["myForm"]["product"].value;
	var brand=document.forms["myForm"]["brand"].value;
	var address=document.forms["myForm"]["address"].value;
	
	
	var status=true;
 	if (DealerName=="") {
		document.getElementById("DealerName_error").innerHTML= "<img src='images/wrong.png'  /> Please Enter Dealer Name";
		status=false;
	} else {
		document.getElementById("DealerName_error").innerHTML= "<img src='images/right.png' /> ";
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
	/*if (Zone=="NA") {
		document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'  /> Please Enter Zone";
		status=false;
	}else {
		document.getElementById("Zone_error").innerHTML= "<img src='images/right.png' /> ";
	}
	if (State=="NA")
	{  
		document.getElementById("State_error").innerHTML= "<img src='images/wrong.png' /> Please Enter State";  
		status=false;
	}else {
		document.getElementById("State_error").innerHTML= "<img src='images/right.png' ' /> ";
	}
	if (City=="NA")
	{  
		document.getElementById("City_error").innerHTML= "<img src='images/wrong.png' '/> Please Enter City";  
		status=false;
	} else {
		document.getElementById("City_error").innerHTML= "<img src='images/right.png' '/>";  
	} 
	if (brand=="NA") {
	 document.getElementById("brand_error").innerHTML= "<img src='images/wrong.png'/> Please Enter brand";
		status=false;
	}else {
		document.getElementById("brand_error").innerHTML= "<img src='images/right.png' '/>";  
	}	
	if (product=="NA") {
	 document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please Enter product";
		status=false;
	} 
	else
	{  
		document.getElementById("product_error").innerHTML= "<img src='images/right.png' /> ";
	}*/
return status;  
}
<!-----------------------End Dealer----------------------------------------->

<!-----------------------New Case----------------------------------------->
function NewCaseValidate()
{
$('#case_type_error,#center_module_error,#Zone_error,#State_error,#City_error,#product_error,#brands_error,#Dealer_error,#phonenumbers_error').html("<img src='images/right.png'/>");
	var case_type=document.forms["myForm"]["case_type"].value;
	var center_module=document.forms["myForm"]["center_module"].value;
	var Region=document.forms["myForm"]["Zone"].value;
	var State=document.forms["myForm"]["State"].value;
	var City=document.forms["myForm"]["City"].value;
	var product=document.forms["myForm"]["product"].value;
	var brands=document.forms["myForm"]["brands"].value;
	var Dealer=document.forms["myForm"]["Dealer"].value;
	var phonenumbers=document.forms["myForm"]["phonenumbers"].value;
	var status=true;
	if (case_type=="NA")
	{
		document.getElementById("case_type_error").innerHTML= "<img src='images/wrong.png'/> Please select Case type";
		status=false;
	}
	if (center_module=="NA")
	{
		document.getElementById("center_module_error").innerHTML= "<img src='images/wrong.png'/> Please select Center Module";
		status=false;
	}
	if (Region=="NA")
	{
		document.getElementById("Zone_error").innerHTML= "<img src='images/wrong.png'/> Please select Region";
		status=false;
	}
	if (State=="NA")
	{
		document.getElementById("State_error").innerHTML= "<img src='images/wrong.png'/> Please select State";
		status=false;
	}
	if (City=="NA"){document.getElementById("City_error").innerHTML= "<img src='images/wrong.png'/> Please select City";status=false;}
	if (product=="NA"){document.getElementById("product_error").innerHTML= "<img src='images/wrong.png'/> Please select Product";status=false;}
	if (brands=="NA"){document.getElementById("brands_error").innerHTML= "<img src='images/wrong.png'/> Please select City";status=false;}
	if (phonenumbers==""){document.getElementById("phonenumbers_error").innerHTML= "<img src='images/wrong.png'/> Please Phone Number";status=false;}
    return status;  
}

<!-----------------------End New Case----------------------------------------->


<!-----------------------Complaint----------------------------------------->
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
<!-----------------------End Complaint----------------------------------------->

<!-----------------------Access----------------------------------------->
function accessValidation()
{
	document.getElementById("usertype_id_error").innerHTML='';
	document.getElementById("role_error").innerHTML='';
	document.getElementById("location_error").innerHTML='';
	document.getElementById("escalate_to_error").innerHTML='';
	document.getElementById("escalate_cc_error").innerHTML='';
	document.getElementById("create_complaint_error").innerHTML='';
	document.getElementById("update_complaint_error").innerHTML='';
	document.getElementById("close_complaint_error").innerHTML='';
	document.getElementById("post_complaint_survey_error").innerHTML='';
	document.getElementById("approval_error").innerHTML='';
	document.getElementById("menu_new_case_error").innerHTML='';
	document.getElementById("menu_update_case_error").innerHTML='';
	document.getElementById("menu_report_error").innerHTML='';
	document.getElementById("menu_dashboard_error").innerHTML='';
	var usertype_id=document.forms["myForm"]["usertype_id"].value;
	var role=document.forms["myForm"]["role"].value;	
	var location=document.forms["myForm"]["location"].value;
	var escalate_to=document.forms["myForm"]["escalate_to"].value;
	var escalate_cc=document.forms["myForm"]["escalate_cc"].value;
	var create_complaint=document.forms["myForm"]["create_complaint"].value;
	var update_complaint=document.forms["myForm"]["update_complaint"].value;
	var close_complaint=document.forms["myForm"]["close_complaint"].value;
	var post_complaint_survey=document.forms["myForm"]["post_complaint_survey"].value;
	
	var approval=document.forms["myForm"]["approval"].value;
	var menu_new_case=document.forms["myForm"]["menu_new_case"].value;
	var menu_update_case=document.forms["myForm"]["menu_update_case"].value;
	var menu_report=document.forms["myForm"]["menu_report"].value;
	var menu_dashboard=document.forms["myForm"]["menu_dashboard"].value;
	

	var status=false;
	if (usertype_id=="NA")
	{
		document.getElementById("usertype_id_error").innerHTML= "<img src='images/wrong.png'/> Please enter user type";
		status=false;
	}
	  if (role=="NA") {
		document.getElementById("role_error").innerHTML= "<img src='images/wrong.png'/> Please enter role";
		status=false;
	}  if (location=="NA") {
		document.getElementById("location_error").innerHTML= "<img src='images/wrong.png'/> Please enter location";
		status=false;
	}  if (escalate_to=="NA") {
		document.getElementById("escalate_to_error").innerHTML= "<img src='images/wrong.png'/> Please enter escalate to";
		status=false;
	}  if (escalate_cc=="NA") {
		document.getElementById("escalate_cc_error").innerHTML= "<img src='images/wrong.png'/> Please enter escalate cc";
		status=false;
	}  if (create_complaint=="NA") {
		document.getElementById("create_complaint_error").innerHTML= "<img src='images/wrong.png'/> Please enter create complaint";
		status=false;
	}  if (update_complaint=="NA") {
		document.getElementById("update_complaint_error").innerHTML= "<img src='images/wrong.png'/> Please enter update complaint";
		status=false;
	}  if (close_complaint=="NA") {
		document.getElementById("close_complaint_error").innerHTML= "<img src='images/wrong.png'/> Please enter close complaint";
		status=false;
	}  if (post_complaint_survey=="NA") {
		document.getElementById("post_complaint_survey_error").innerHTML= "<img src='images/wrong.png'/> Please enter post complaint survey";
		status=false;
	}  if (approval=="NA") {
		document.getElementById("approval_error").innerHTML= "<img src='images/wrong.png'/> Please enter approval";
		status=false;
	}  if (menu_new_case=="NA") {
		document.getElementById("menu_new_case_error").innerHTML= "<img src='images/wrong.png'/> Please enter new case";
		status=false;
	}  if (menu_update_case=="NA") {
		document.getElementById("menu_update_case_error").innerHTML= "<img src='images/wrong.png'/> Please enter update case";
		status=false;
	}  if (menu_report=="NA") {
		document.getElementById("menu_report_error").innerHTML= "<img src='images/wrong.png'/> Please enter report";
		status=false;
	}  if (menu_dashboard=="NA") {
		document.getElementById("menu_dashboard_error").innerHTML= "<img src='images/wrong.png'/> Please enter dashboard";
		status=false;
	}
	else
	{
		status = true;
	}
	return status;
}
<!-----------------------End Access----------------------------------------->

<!-----------------------File Settings----------------------------------------->
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
<!-----------------------End File Settings----------------------------------------->

<!-----------------------Js----------------------------------------->
$(document).ready(function () 
 {
	$('#dob,#datefrom,#dateto,#dop,#dos').datetimepicker({format:'Y-m-d',timepicker:false});
	$("#phonenumbers").keypress(function (e) 
	{
		$(this).attr('maxlength','10');
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		$("#errmsg").html("Digits Only").show().fadeOut("slow");
		return false;
	}
    });
    $('#email').blur(function()
	    {
	    	$('#email_error').hide();
	    	var email= this.value;
		if (!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/))
		{
			$('#email_error').show();
	document.getElementById("email_error").innerHTML= " <img src='images/wrong.png' style='height: 15px;width:15px;'/> Please enter valid email format";
	return false;
		}
	   });
});
<!-----------------------Update Case----------------------------------------->
function updateCaseValidate()
{
	$('#Status_error,#Assigneto_error,#observations_error,#Comment_error').html("<img src='images/right.png'/>");
	var CaseStataus=document.forms["myForm"]["Status"].value;
	var Assigneto=document.forms["myForm"]["Assigneto"].value;
	var observations=document.forms["myForm"]["observations"].value;
	var Comment=document.forms["myForm"]["Comment"].value;
	var status=true;
	if (CaseStataus=="NA")
	{
		document.getElementById("Status_error").innerHTML= "<img src='images/wrong.png'/> Please select Case Status";
		status=false;
	}
	if (CaseStataus=="ReAssigned")
	{
		if (Assigneto=="NA")
		{
		document.getElementById("Assigneto_error").innerHTML= "<img src='images/wrong.png'/> Please select Assigne To";
		status=false;
		}
	}
	if (observations=="")
	{
		document.getElementById("observations_error").innerHTML= "<img src='images/wrong.png'/> Please Observations";
		status=false;
	}
	if (Comment=="")
	{
		document.getElementById("Comment_error").innerHTML= "<img src='images/wrong.png'/> Please Comment";
		status=false;
	}
    return status;  
}

<!-----------------------End Update Case----------------------------------------->

<!-----------------------End Js----------------------------------------->