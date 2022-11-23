jQuery(document).ready(function(){

jQuery('button[name=register]').prop('disabled',true);
 
var ajax_url=my_ajax_object.ajax_url;

//alert(ajax_url);	
 
 var is_phone_varified=localStorage.getItem('is_phone_varified');
 
 if(is_phone_varified!=1){
  //jQuery('button[name=register]').prop('disabled',true);
 } 
 	
jQuery(document).on('keyup blur', 'input[name=phone]', function(event) { 


 	var phone=jQuery(this).val();


var count_digit=phone.length;
// alert(count_digit);

if(count_digit<10){
jQuery('.send_otp').fadeOut();
return false;
}


if(phone==''){
return false;
}
jQuery('.send_otp').fadeIn();

});


/*jQuery('input[name=phone]').on('blur',function(){
var phone=jQuery(this).val();

if(phone==''){
return false;
}
jQuery('.send_otp').fadeIn();

});*/


jQuery(document).on('keyup', 'input[name=otp]', function(event) { 

 	
var otp=jQuery(this).val();

var phone=jQuery( "input[name=phone]" ).val();

var count_digit=otp.length;

if(count_digit<4){

return false;
}

check_is_valid_otp(phone,otp);

});


 	
 	jQuery(document).on('click', '.send_otp', function(event) { 



jQuery('.otp_message').fadeOut();

 	var phone=jQuery( "input[name=phone]" ).val();
 	var str='action=send_otp&phone='+phone;

//alert(str);

//jQuery('.content').html(str);

 	
jQuery.ajax({
url:ajax_url,
type:'GET',
data:str,
success:function(output){
var status=output.status;
var message=output.message;

jQuery('.phone-invalid-feedback').fadeOut();

//check is already varified 
if(status==2){
jQuery('.otp_sent').html('<div class="alert alert-success">'+message+'</div>').fadeIn('slow');
 jQuery('#register').prop('disabled',false);
 
 localStorage.setItem('is_phone_varified',1);
 
}  	


if(status==1){
jQuery('.send_otp').text('Resend OTP ');


jQuery('.otp_sent').html('<div class="alert alert-success">'+message+'</div>').fadeIn('slow');
jQuery('.enter_otp').fadeIn('slow');

setTimeout(function(){
jQuery('.otp_sent').fadeOut();
},2000);
 
}else if(status==0){
jQuery('.otp_sent').html('<div class="alert alert-danger">'+message+'</div>').fadeIn('slow');
} 

}

});


return false;

});




function check_is_valid_otp(phone,otp){

var str='action=check_is_valid_otp&phone='+phone+'&otp='+otp;

 

jQuery.ajax({
url:ajax_url,
type:'GET',
data:str,
success:function(output){
var status=output.status;
var message=output.message;
 	
if(status==1){
jQuery('.otp_sent').html('<div class="alert alert-success">'+message+'</div>').fadeIn('slow');
 	  jQuery('button[name=register]').prop('disabled',false);
  
  localStorage.setItem('is_phone_varified',1);

jQuery( ".enter_otp" ).fadeOut('slow');


 
}else{
jQuery('.otp_sent').html('<div class="alert alert-danger">'+message+'</div>').fadeIn('slow');
 jQuery('button[name=register]').prop('disabled',true);
 localStorage.setItem('is_phone_varified',0);

} 

}

});
}



 jQuery(document).on('click', 'button[name=register]', function(event) { 

     

 	var loader='<img style="width:20px" src="/public/images/ajax-loader.gif" />';

//jQuery(this).prop('disabled',true);

//jQuery('.reg_btn_loader').html(loader);

//return false;
 	 
 
});





});