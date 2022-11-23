(function( $ ) {
  'use strict';
  jQuery(document).ready(function () {
  // $(document).ajaxSend(function() {
  //   $("#overlay").fadeIn(300);　
  // });
   // validation for input
   function vali_phone(){
      var userAddressparam = jQuery('#phone_code_txt').val();
      var reg = /^[0-9]+$/;
      var errorMessage = '';
      if (userAddressparam == ''){
        errorMessage = "*Phone Number required!";
        jQuery('#form_validation').removeClass('wpcf7-submit');
        jQuery('#phone_code_txt').addClass('err_col');
        jQuery('.errorMessage4').addClass('error_Message');
        $('.errorMessage4').html(errorMessage);
        return false;
      }
      else if ((userAddressparam.length)< 10 || (userAddressparam.length)>10 ){
        errorMessage = "*Phone Number is not valid";
        jQuery('#form_validation').removeClass('wpcf7-submit');
        jQuery('#phone_code_txt').addClass('err_col');
        jQuery('.errorMessage4').addClass('error_Message');
        $('.errorMessage4').html(errorMessage);
        return false;
      }
      else if (!reg.test(userAddressparam)){
        errorMessage = "*Phone Number is not valid";
        jQuery('#form_validation').removeClass('wpcf7-submit');
        jQuery('#phone_code_txt').addClass('err_col');
        jQuery('.errorMessage4').addClass('error_Message');
        $('.errorMessage4').html(errorMessage);
        return false;
      }
      else
      {
        errorMessage = '';
        jQuery('#phone_code_txt').removeClass('err_col');
        jQuery('.errorMessage4').removeClass('error_Message');
        $('.errorMessage4').html(errorMessage);
         return true;
      }
   } 

   jQuery('#phone_code_txt').on('keyup', vali_phone);
   jQuery('#form_validation').on('click', vali_phone);

   function first_name(){
      var userAddressparam = jQuery('#firstname_txt').val();
      var errorMessage = '';
      if (userAddressparam == ''){
        errorMessage = "*First Name required!";
        jQuery('#form_validation').removeClass('wpcf7-submit');
        jQuery('#firstname_txt').addClass('err_col');
        jQuery('.errorMessage1').addClass('error_Message');
        $('.errorMessage1').html(errorMessage);
        return false;
      }
      else
      {
        errorMessage = '';
        jQuery('#firstname_txt').removeClass('err_col');
        jQuery('.errorMessage1').removeClass('error_Message');
      $('.errorMessage1').html(errorMessage);
      return true;
      }
   } 

   jQuery('#firstname_txt').on('keyup', first_name);
   jQuery('#form_validation').on('click', first_name);

   function last_name(){
      var userAddressparam = jQuery('#lsatname_txt').val();
      var errorMessage = '';
      if (userAddressparam == ''){
        errorMessage = "*Last Name required!";
        jQuery('#form_validation').removeClass('wpcf7-submit');
        jQuery('#lsatname_txt').addClass('err_col');
         jQuery('.errorMessage2').addClass('error_Message');
        $('.errorMessage2').html(errorMessage);
        return false;
      }
      else
      {
        errorMessage = '';
        jQuery('#lsatname_txt').removeClass('err_col');
        jQuery('.errorMessage2').removeClass('error_Message');
        
      $('.errorMessage2').html(errorMessage);
      return true;
      }
   } 

   jQuery('#lsatname_txt').on('keyup', last_name);
   jQuery('#form_validation').on('click', last_name);

   function vali_email(){
      var userAddressparam = jQuery('#email_txt').val();
      var reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      var errorMessage = '';
      if (userAddressparam == ''){
        errorMessage = "*Email required!";
        jQuery('#form_validation').removeClass('wpcf7-submit');
      jQuery('#email_txt').addClass('err_col');
        jQuery('.errorMessage3').addClass('error_Message');
        $('.errorMessage3').html(errorMessage);
        return false;
      }
      else if (!reg.test(userAddressparam)){
        errorMessage = "Please Enter Valid Email";
        jQuery('#email_txt').addClass('err_col');
        jQuery('.errorMessage3').addClass('error_Message');
        $('.errorMessage3').html(errorMessage);

        return false;
      }
      else
      {
        errorMessage = '';
        jQuery('#email_txt').removeClass('err_col');
        jQuery('.errorMessage3').removeClass('error_Message');
      $('.errorMessage3').html(errorMessage);
      return true;
      }
   } 

   jQuery('#email_txt').on('keyup', vali_email);
   jQuery('#form_validation').on('click', vali_email);
        
    $('#form_validation').click(function(){
         if (document.getElementById('agree_checkbox').checked) {
          $('#form_validation').addClass('wpcf7-submit');
             document.getElementById('errorMessage5').innerHTML="";
        } else {
          document.getElementById('errorMessage5').innerHTML="Please include your preffered contact method(s)";
        }
    });

   // $("#form_validation").on("submit",function(form)
   // {
   //      $('#form_validation').addClass('wpcf7-submit');
   // });
   
 
   /*<!---------------Start CheckBox Valiation--------------->*/
    // $('#form_validation').click(function(){
    //   if (document.getElementById('agree_checkbox').checked) {
    //          document.getElementById('errorMessage5').innerHTML="";
    //     } else {
    //       document.getElementById('errorMessage5').innerHTML="Please include your preffered contact method(s)";
    //     }
    // });
/*<!---------------Start CheckBox Valiation--------------->*/

   setInterval(function() {
    jQuery('.form-wizard-next-btn').click(function () {
       // alert("sdddddddd");
    
    if(jQuery(this).hasClass("how_fix_it"))
    {
      var nm = jQuery(this).find('h2').text();
      jQuery('#how_fixed').val(nm);
    }
    var next = jQuery(this);
    next.parents('.form-wizard-content').removeClass('show');
    next.parents('.form-wizard-content').next('.form-wizard-content').addClass('show');
    jQuery(document).find('.form-wizard-content').each(function () {
      if (jQuery(this).hasClass('show')) {
        var formAtrr = jQuery(this).attr('data-tab-content');
        jQuery(document).find('.form-wizard-wrapper li a').each(function () {
          if (jQuery(this).attr('data-attr') == formAtrr) {
            jQuery(this).addClass('active');
            var innerWidth = jQuery(this).innerWidth();
            var position = jQuery(this).position();
            jQuery(document).find('.form-wizardmove-button').css({ "width": innerWidth });
          }
        });
      }
    });
    });

    jQuery('.form-wizard-previous-btn, .ZIP-code-hides').click(function () {
    var prev = jQuery(this);
    prev.parents('.form-wizard-content').removeClass('show');
    prev.parents('.form-wizard-content').prev('.form-wizard-content').addClass('show');
    jQuery(document).find('.form-wizard-content').each(function () {
      if (jQuery(this).hasClass('show')) {
        var formAtrr = jQuery(this).attr('data-tab-content');
        jQuery(document).find('.form-wizard-wrapper li a').each(function () {
          if (jQuery(this).attr('data-attr') == formAtrr) {
            jQuery(this).addClass('active');
            var innerWidth = jQuery(this).innerWidth();
            var position = jQuery(this).position();
            jQuery(document).find('.form-wizardmove-button').css({ "width": innerWidth });
          } 
        });
      }
    });
    });
   }, 500);

    jQuery(".end_step").click(function () {
       if(!jQuery('div').hasClass('csl-list-item'))
      {
        $("#lsat_steps_new").css("display", "none");
        $("#locationhide").css("display", "none");
        $("#Choosedayshow").css("display", "none");
        $("#What’swrong").css("display", "block");
      }
      else
      {
        $("#lsat_steps_new").css("display", "none");
        // $("#locationhide").css("display", "none");
        $("#Choosedayshow").css("display", "block");
        $("#What’swrong").css("display", "none");
      }
    });

  $('#asurion-ui-dropdown-1').on('change', function () {
    $('#What’swrong').show();
    $('#selectphone').hide();
  });

  $(".backlock").click(function(){
    $("#step_remove-4").removeClass("active");
  });

  $(".backlock-1").click(function(){
    $("#step_remove-3").removeClass("active");
    jQuery('#zip_code_txt').val('');
    jQuery('#how_fixed').val('');
  });

  $(".backlock-2").click(function(){
    $("#step_remove-2").removeClass("active");
    jQuery('#device_nm').val('');
  });

  $('#asurion-ui-dropdown-1').on('change', function () {
    $('#What’swrong').show();
    $('#selectphone').hide();
  });

  $("#selectphone .form-wizard-previous-btn").click(function () {
    $("#selectphone").css("display", "none");
  });

  $(".backlockslidnew").click(function () {
    $("#locationhide").css("display", "block");
    $("#Choosedayshow").css("display", "none");
  });

  $(".backlockslidlsatx").click(function () {
    $("#Choosedayshow").css("display", "block");
    $("#lsat_steps_new").css("display", "none");
  });

  $(".change_store").click(function () {
    $("#locationhide").css("display", "block");
    $("#lsat_steps_new").css("display", "none");
  });

  $(".backlockslidlsat_end").click(function () {
    $("#lsat_steps_new").css("display", "block");
    $("#lsat_steps_new_end").css("display", "none");
  });

  $(".backlock").click(function () {
    $("#What’swrong").css("display", "block");
  });

  jQuery(".backlockslid").click(function () {
    $(".grand_sub_device").val($(".grand_sub_device option:first").val()); 
    jQuery("#selectphone").css("display", "block");
    jQuery("#What’swrong").css("display", "none");
  });

  jQuery("#service-options a").click(function () {
    jQuery("#selectphone").css("display", "block");
  });  

  setInterval(function() {
    jQuery('.booking_map').click(function () {
      jQuery("#What’swrong").css("display", "none");
      var next = jQuery(this);
      var value_nm = jQuery(this).find('.dxPFUv').text();
      jQuery('#wrong_val').val(value_nm);

        var nm = jQuery('#how_fixed').val();
        if(nm == 'Mail-in Repair')
        {
            $('.booking_wrap').addClass('show');
            $("#locationhide").css("display", "none");
            $("#Choosedayshow").css("display", "none");
            $("#lsat_steps_new").css("display", "block");
        }
        else if(nm == 'We Come to You')
        {
            $('.booking_wrap').addClass('show');
            $("#locationhide").css("display", "none");
            $("#Choosedayshow").css("display", "none");
            $("#lsat_steps_new").css("display", "block");
        }
        else if(!jQuery('div').hasClass('csl-list-item'))
        {
            $('.booking_wrap').addClass('show');
            $("#locationhide").css("display", "none");
            $("#Choosedayshow").css("display", "none");
            $("#lsat_steps_new").css("display", "block");
        }
       else if(jQuery('div').hasClass('csl-list-item'))
        {
            $('.booking_wrap').addClass('show');
            $("#locationhide").css("display", "block");
        }
        else
        {
        next.parents('.form-wizard-content').removeClass('show');
        next.parents('.form-wizard-content').next('.form-wizard-content').addClass('show');
          jQuery(document).find('.form-wizard-content').each(function () {
            if (jQuery(this).hasClass('show')) {
              var formAtrr = jQuery(this).attr('data-tab-content');
              jQuery(document).find('.form-wizard-wrapper li a').each(function () {
                if (jQuery(this).attr('data-attr') == formAtrr) {
                  jQuery(this).addClass('active');
                  var innerWidth = jQuery(this).innerWidth();
                  var position = jQuery(this).position();
                  jQuery(document).find('.form-wizardmove-button').css({ "width": innerWidth });
                }
              });
            }
          });
        }
    });
    
    // jQuery('.backlockslidlsat').click(function () {
    //   jQuery("#lsat_steps_new").css("display", "none");
    //   var next = jQuery(this);
    //   var value_nm = jQuery(this).find('.dxPFUv').text();
    //   jQuery('#wrong_val').val(value_nm);

    //     var nm = jQuery('#how_fixed').val();
    //     if(nm == 'Mail-in Repair')
    //     {
    //         $('.booking_wrap').addClass('show');
    //         $("#locationhide").css("display", "none");
    //         $("#Choosedayshow").css("display", "none");
    //         $("#What’swrong").css("display", "block");
    //     }
    //     else if(nm == 'We Come to You')
    //     {
    //         $('.booking_wrap').addClass('show');
    //         $("#locationhide").css("display", "none");
    //         $("#Choosedayshow").css("display", "none");
    //         $("#What’swrong").css("display", "block");
    //     }
    // });
  
  }, 1000);

  jQuery(document).on('click', ".add_device", function( event ){
        $("#overlay").fadeIn(300);　
    var device_name = jQuery(this).find('#get_device_name').text();
    jQuery('#device_nm').val(device_name);
    jQuery('.device_name_ap').text(device_name);
    var image = jQuery(this).find('.image').attr('src');
    jQuery('.device_img_ap').attr("src",image);;
    var data = {
    'action': 'rrd_get_sub_device_details',
    'device_name': device_name
    };

    $.ajax({
      type: 'POST',
      data: data,
      url: my_ajax_object.ajax_url,
      success: function(response){
         jQuery('.sub_dev').html(response);
      }
    })
    .done(function() {
      setTimeout(function(){
        $("#overlay").fadeOut(300);
      },500);
    });

    // jQuery.post(my_ajax_object.ajax_url, data, function(response) {
    //   jQuery('.sub_dev').html(response);
    // });

    var data = {
      'action': 'rrd_get_serice_details',
      'device_name': device_name
    };

   
    jQuery.post(my_ajax_object.ajax_url, data, function(response) {
      jQuery('.cYosoy').html(response);
    });

  }); 

  jQuery(document).on('change', ".sub_devices_change", function( event ){
    $("#overlay").fadeIn(300);　
    var sub_device_name = jQuery(this).val();
    jQuery('.subdevice_name_ap').text(sub_device_name);
    var device_name = jQuery(this).attr("data-id");
    var data = {
    'action': 'rrd_get_grand_sub_device_details',
    'device_name': device_name,
    'sub_device_name': sub_device_name
    };

    $.ajax({
      type: 'POST',
      data: data,
      url: my_ajax_object.ajax_url,
      success: function(response){
        jQuery('.grand_sub_dev').html(response); 
        if(jQuery(".grand_sub_device option").length > 1)
        {
            jQuery('#granddev').css("display", "block");
        }
        else
        {
            jQuery("#selectphone").css("display", "none");
            jQuery("#What’swrong").css("display", "block");  
        }
      }
    })
    .done(function() {
      setTimeout(function(){
        $("#overlay").fadeOut(300);
      },500);
    });

    // $.ajax({
    //   type: 'POST',
    //   data: data,
    //   url: my_ajax_object.ajax_url,
    //   success: function(response){
    // // jQuery.post(my_ajax_object.ajax_url, data, function(response) {
    //   jQuery('.grand_sub_dev').html(response); 
    //   if(jQuery(".grand_sub_device option").length > 1)
    //   {
    //       jQuery('#granddev').css("display", "block");
    //   }
    //   else
    //   {
    //       jQuery("#selectphone").css("display", "none");
    //       jQuery("#What’swrong").css("display", "block");  
    //   }
    //  }
    // })
    // .done(function() {
    //   setTimeout(function(){
    //     $("#overlay").fadeOut(300);
    //   },500);
    // });


  });  

  jQuery(document).on('change', ".grand_sub_device", function( event ){
     jQuery("#selectphone").hide();
     jQuery("#What’swrong").css("display", "block"); 
  });

  setInterval(function() {
    $(".nextlocation .csl-list-item").click(function () {
         $('.appointment-details').show();
        var la = jQuery(this).find('.location_add').text();
        $('.loca_app_book').css("display", "block"); 
        $('.loca_default_add').css("display", "none"); 
        $('#booking_address').val(la);
        $('.loc_address').text(la);
        $("#locationhide").css("display", "none");
        $("#Choosedayshow").css("display", "block");
        $("input:radio[name=book_date]:first").attr('checked', true);

    });

    $(".lsat_step").click(function () {
       var bd = jQuery('.b_date').val();
       var bt = jQuery('.show_date_ti').val();
       console.log(bt);
        $('.booking_date_ap').text(bd);
        $('.booking_time_ap').text(bt);
        $("#Choosedayshow").css("display", "none");
        $("#lsat_steps_new").css("display", "block");
    });

    $(".wpcf7-submit").click(function () {
       var bd = jQuery('.b_date').val();
       var bt = jQuery('.show_date_ti').val();
        $('.booking_date_ap').text(bd);
        $('.booking_time_ap').text(bt);
        $("#lsat_steps_new").css("display", "none");
        $("#lsat_steps_new_end").css("display", "block");
    });
  }, 500);

// calendar js 
  jQuery(".click_btn_book").click(function() {
    jQuery(".popup").fadeIn(500);
  });

  jQuery(".close").click(function() {
    jQuery(".popup").fadeOut(500);
  });

  function GetDates(startDate) {
    const startDates = new Date(startDate)
    var daysToAdd = 6;
    var aryDates = [];
    var today = startDates.toLocaleDateString('en-us', { weekday:"short"});
    jQuery('.gaIwVl').html((startDates.getMonth() + 1) + "/" + startDates.getDate()+ ' ' +today);

    for(var i = 0; i <= daysToAdd; i++) {
          var months = startDates.getMonth() + 1;
          startDates.setDate(startDates.getDate());
          var dayNameSt = startDates.toLocaleDateString('en-us', { weekday:"short"});
          var datenamest = months + "/" + startDates.getDate();
          var showdatefo = startDates.toLocaleString("en-us", { weekday: 'short',day: 'numeric', month: 'long', year: 'numeric' });
          startDates.setDate(startDates.getDate() + 1);
          aryDates.push({date: datenamest,day:dayNameSt,show_date:showdatefo });
    }
    return aryDates;
  }

  jQuery('.flatpickr-next-month').remove();

  var flatpickr = $('#calendar .placeholder').flatpickr({
    inline: true,
    minDate: 'today',
    showMonths: 2,
    "disable": [
          function(date) {
              return (date.getDay() === 0);
          }
    ],
    "locale": {
          "firstDayOfWeek": 1 // start week on Monday
    },
    onChange: function(date, str, inst) {
      var contents = '';
      if(date.length) {
        var aryDates = GetDates(date.toString());
        var htm = '';
        jQuery('.day_date_sec').html(htm);
        var todaydate = new Date();
        var todate = (todaydate.getMonth() + 1) + "/" + todaydate.getDate();
        var tomdate = (todaydate.getMonth() + 1) + "/" + (todaydate.getDate() +1);
        // 

        jQuery.each(aryDates, function (index, value) {
          if(value.date == todate)
          {
            var srt_today = "<div class='sc-Galmp bmNBPa'>Today</div>";
          }
          else if(value.date == tomdate){
             var srt_today = "<div class='sc-Galmp eRiQJu'>Tomorrow</div>";
          }
          else
          {
            var srt_today = '';
          }
          if(value.day == 'Sun')
          {
            var dis = 'disabled';
            var addcls = 'radio_diabled';
          }
          else{
            var addcls = '';
            var dis = '';
          }

            var htm = "<span>"+srt_today+"<input type='radio' id='"+value.date+"' name='book_date' value='"+value.date + ' '+value.day+"' "+dis+" class='b_time' show-data='"+value.show_date+"'><label for='"+value.date+"' class='radio_repair "+addcls+"'><span>"+value.day+"</span><br>"+value.date+"</label></span>";
             jQuery('.day_date_sec').append(htm);
        }); // Here I just exchange data the location of the simple objects.
        $("input:radio[name=book_date]:first").prop('checked', true);
         timeDisabledEnabled(date.toString());
        jQuery(".popup").fadeOut(100);
      }
     
    },
    locale: {
      weekdays: {
        shorthand: ["S", "M", "T", "W", "T", "F", "S"],
        longhand: [
          "Sunday",
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday",
        ]
      }
    }
  })


  function vali_zip(){
      var userAddressparam = jQuery('#zip_code_txt').val();
      var reg = /^[0-9]+$/;
      var errorMessage = '';
      if (userAddressparam == ''){
        errorMessage = "*Zipcode required!";
        jQuery('.zip_code_btn').removeClass('form-wizard-next-btn');
        jQuery('.fKvJtF').addClass('err_col');
        jQuery('.errorMessage').addClass('error_Message');
        $('.errorMessage').html(errorMessage);
        return false;
      }
      else if ((userAddressparam.length)< 5 || (userAddressparam.length)>5 ){
        errorMessage = "*Postal Code is not valid";
        jQuery('.zip_code_btn').removeClass('form-wizard-next-btn');
        jQuery('.fKvJtF').addClass('err_col');
        jQuery('.errorMessage').addClass('error_Message');
        $('.errorMessage').html(errorMessage);
        return false;
      }
      else if (!reg.test(userAddressparam)){
        errorMessage = "*Postal Code is not valid";
        jQuery('.zip_code_btn').removeClass('form-wizard-next-btn');
        jQuery('.fKvJtF').addClass('err_col');
        jQuery('.errorMessage').addClass('error_Message');
        $('.errorMessage').html(errorMessage);
        return false;
      }
      else
      {
        jQuery('.fKvJtF').removeClass('err_col');
        jQuery('.errorMessage').removeClass('error_Message');
        errorMessage = '';
        var maxRadius = '20';
        var userLatLng;
        var geocoder = new google.maps.Geocoder();
        //console.log(geocoder);
        if(userAddressparam)
        {
          var myuserAddress = userAddressparam.replace(/[^a-z0-9\s]/gi, '');
        }
        else
        {
          var myuserAddress = '';
        }
        var maxRadius = parseInt(maxRadius, 10);
        
        if (myuserAddress && maxRadius) {
           // setInterval(function() {
              userLatLng = getLatLngViaHttpRequest(myuserAddress);
            // }, 100);
        } 
      }
      $('.errorMessage').html(errorMessage);
      $('.zip_code_btn').addClass('form-wizard-next-btn');
      return true;
    } 

    jQuery('#zip_code_txt').on('keyup', vali_zip);
    jQuery('.zip_code_btn').on('click', vali_zip);

   function getLatLngViaHttpRequest(address) {
  
    var addressStripped = address.split(' ').join('+');
    var key = cslAPI;
    var request = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + addressStripped + '&key=' + key;
       
    $.get( request, function( data ) {
      var searchResultsAlert = document.getElementById('location-search-alert');
      var searchResultsAlertMap = document.getElementById('locations-near-you-map');

      console.log('data.status',data.status);
      if (data.status === "ZERO_RESULTS") {
          
     var zip_code_click = document.getElementById('zip_code_click');
        zip_code_click.disabled = true;
        jQuery('.fKvJtF').addClass('err_col');
        jQuery('.errorMessage').addClass('error_Message');
        $('.errorMessage').html("No service options were available for that postal code, please try another one.");
 
        
         $('#zip_code_click').removeClass('form-wizard-next-btn');   
          
        document.getElementById('csl-wrapper').setAttribute("class", 'csl-wrapper no-locations');
        document.getElementById('csl-wrapper').innerHTML = '';
        searchResultsAlert.innerHTML = '<div class="nothing_found">No locations found in ' + address + '. Please search again.</div>';
        return;
      } 
      
      else {
                jQuery('.fKvJtF').removeClass('err_col');
        jQuery('.errorMessage').removeClass('error_Message');
          jQuery('.backlock-1').click(function () {
       document.getElementById("zip_code_txt").value = "";
        
        });
          
          var zip_code_click = document.getElementById('zip_code_click');
        zip_code_click.disabled = false;
      }
  
      var userLatLng = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
      var filteredLocations = allLocations.filter(isWithinRadius);

      console.log(filteredLocations);
      if (filteredLocations.length > 0) {
      //  initMap(filteredLocations);
      filteredLocations.forEach( function(location) {
        var distance = distanceBetween(location);
        location.distance = parseFloat(distance).toFixed(2);
      });
      filteredLocations.sort((x, y) => x.distance - y.distance);
      createListOfLocations(filteredLocations);

      // console.log(e);
      searchResultsAlert.innerHTML = 'Locations near ' + address + ':';
       jQuery('.rbook').removeClass('no_location_found');
      } 
      else {
        console.log("nothing found!");
         var htm = '<div class="csl-left"><div id="locations-near-you"><a href="#" class="location-near-you-box nextlocation"> </a></div></div><div class="csl-right"><div id="locations-near-you-map"></div></div>';
        document.getElementById('csl-wrapper').innerHTML = htm;
        jQuery('.rbook').addClass('no_location_found');
        searchResultsAlert.innerHTML = 'Sorry, no locations were found near ' + address + '.';
      }
      
    function distanceBetween(location) {
      var locationLatLng = new google.maps.LatLng(location.lat, location.lng);
      var distanceBetween = google.maps.geometry.spherical.computeDistanceBetween(locationLatLng, userLatLng);
      return convertMetersToMiles(distanceBetween);
    }
    
    function isWithinRadius(location) {
       var maxRadius = '20';
       var maxRadius = parseInt(maxRadius, 10);
      var locationLatLng = new google.maps.LatLng(location.lat, location.lng);
      var distanceBetween = google.maps.geometry.spherical.computeDistanceBetween(locationLatLng, userLatLng);
      return convertMetersToMiles(distanceBetween) <= maxRadius;
    }
      
    });  
  }

$("input:radio[name=book_date]:first").attr('checked', true);

function timeDisabledEnabled(chkDates = '') {
   // alert(chkDates);
    var date = new Date();
    var day = date.toLocaleDateString('en-us', { day: "numeric" });
    var dayNameSt = date.toLocaleDateString('en-us', { weekday: "short" });
    var month = date.getMonth() + 1;
    var currentDate = month + '/' + day + ' ' + dayNameSt;
    if(chkDates != '')
    {
      var startDates = new Date(chkDates);
      startDates.setDate(startDates.getDate());
      var daya = startDates.toLocaleDateString('en-us', { day: "numeric" });
      var dayNameSta = startDates.toLocaleDateString('en-us', { weekday: "short" });
      var montha = startDates.getMonth() + 1;
      var chkDate = montha + '/' + daya + ' ' + dayNameSta;
    }
    else{
      var chkDate = jQuery('input[name=book_date]:checked').val();
    }
     // console.log(chkDate);
    if (chkDate == currentDate) {
        var hours = date.getHours()+1;
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var plustime = hours + ':' + minutes + ' ' + ampm;
         jQuery(".b_date option").each(function() {
            let id = jQuery(this).attr('id');
            let element = jQuery(this).val();
             // console.log(id)
            if (plustime > element) {
               // console.log(plustime+'=='+element)
                jQuery('#' + id).prop("disabled", true);
            }
            else{
              jQuery('#' + id).prop("disabled", false);
            }
         });
    } else {
      jQuery.each(jQuery('.ena_time'), function(i, val) { 
        jQuery(this).prop("disabled", false);
      });
        
    }
     // $("input:radio[name=book_date]:first").attr('checked', false);
    // $(this).prop("checked", true);

}

  // jQuery(".b_time").click(function() {
    $("body").delegate(".b_time", "click", function(){
   var b_time = jQuery(this).val();
   var dd = jQuery(this).attr('show-data');
   jQuery('.show_date_ti').val(dd);
   jQuery(this).prop('checked', true);
   jQuery('.gaIwVl').html(b_time);
   timeDisabledEnabled();
  });

timeDisabledEnabled();

// function createListOfLocations(locations) {

//   var boundsa = new google.maps.LatLngBounds();
//   var mapOptions = {
//     mapTypeId: cslMaptype,
//     mapTypeControl: false,
//   };

//   var newmarkers = new Array();
//   var infoWindowContentsearch = new Array();
//   var map = new google.maps.Map(document.getElementById('locations-near-you-map'), mapOptions);
//   map.setTilt(45);
//   const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//   var locationsListb = document.getElementsByClassName('location-near-you-box');
//   // Clear any existing locations from the previous search first
//   locationsListb[0].innerHTML = '';
//   var i = 0;
//   var infoWindowsearch = new google.maps.InfoWindow();

//   locations.forEach(function(location) {
//     var positiona = new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']);
//     boundsa.extend(positiona);
//     var specificLocation = document.createElement('div');
//     let locphone = (location.phone !== "") ? location.phone + '<br /> ' : "";
//     let locfax = (location.fax !== "") ? location.fax + '<br /> ' : "";
//     let locwebsite = (location.website !== "") ? location.website : "";
//     let locaddress = (location.address !== "") ? location.address + '<br /> ' : "";
//     let loczip = (location.zip !== "") ? location.zip + '<br /> ' : "";
//     let locdistance = "<p class='distance'>" + location.distance + " Miles </p>";
//     let directionlink = "https://www.google.com/maps/dir/?api=1&destination=" + locations[i]['lat'] + ", " + locations[i]['lng'];
//     let lochours = (location.hours !== "") ? location.hours + ' ' : "";
//     var locationInfo = '<div data-markerid="' + i + '" href="#1" class="marker-link viewmaplink"> <h4>' + location.name + '</h4><p class="location_add">' + location.address + '<br /></p></div></div>';
//     specificLocation.setAttribute("class", 'csl-list-item');
//     specificLocation.innerHTML = locationInfo;
//     locationsListb[0].appendChild(specificLocation);
//     infoWindowContentsearch.push(['<div class="infoWindow"><h3>' + location.name + '</h3><p>' + locaddress + '<br />' + loczip + locphone + locfax + locwebsite + '</p><a class="directionlink" href="' + directionlink + '" target="_blank">Get Direction<a></div>'
//     ]);
  
//     if (clsIcon !== "")
//     {
//       var myicon = {
//         position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
//         url: clsIcon,
//         scaledSize: new google.maps.Size(50, 50),
//         origin: new google.maps.Point(0, 0),
//         anchor: new google.maps.Point(0, 0)
//       };

//       markersearch = new google.maps.Marker({
//         position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
//         icon: myicon,
//         map: map,
//         title: location.name,
//         myid: location.myid,
//         label: labels[i % labels.length]
//       });
//     } 
//     else
//     {
//       markersearch = new google.maps.Marker({
//         position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
//         map: map,
//         title: location.name,
//         myid: location.myid,
//         label: labels[i % labels.length]
//       });
//     }

//     google.maps.event.addListener(markersearch, 'click', (function(markersearch, i) {
//       return function() {
//         infoWindowsearch.setContent(infoWindowContentsearch[i][0]);
//         infoWindowsearch.open(map, markersearch);
//       }
//     })(markersearch, i));

//     if (locations.length > 1) {
//       map.fitBounds(boundsa);
//     } else {
//       var center = new google.maps.LatLng(locations[i].lat, locations[i].lng);
//       map.setCenter(center);
//       map.setZoom(15);
//     }
//     newmarkers.push(markersearch);
//     i++;
//   });

//   jQuery('.marker-link').on('click', function() {
//     google.maps.event.trigger(newmarkers[jQuery(this).attr('data-markerid')], 'click');
//   });

//   let imagePathb = "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m";
//   var clustermakera = new MarkerClusterer(map, newmarkers, {
//     imagePath: imagePathb
//   });
//  }

$(document).ready(function () {  
$("form").bind("keypress", function (e) {  
if (e.keyCode == 13) {  
return false;  
}  
});  
});  

});
})( jQuery );

