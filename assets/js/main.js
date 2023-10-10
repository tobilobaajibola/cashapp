
(function ($) {
    "use strict";

    
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    

})(jQuery);



 

 function submit_account_details(formid, formurl){
         dataString = $(formid).serialize();
         // triger loading screen 
         $(document).ajaxSend(function() {
            $("#loading").fadeIn(1000);　
          });
              $.ajax({
           type: "POST",
           url: formurl,
           // dataType: "json",
           data: dataString,          
  
           success:function(data){
                var data = JSON.parse(data);
                console.log(data);
                    if($.isEmptyObject(data.error)){
                    
                    $("#charge_details_section").css('display', 'none');
                    $(".account_section").css('display', 'block');
                    $("#alert_fail").css('display', 'none');

                   
                    // $("#alert_success").append('<strong>'+ data.success+'</strong>');
                    $("#account_name").html(data.account_name); 
                    $("#account_number").html(data.account_number);
                    $("#current_balance").html(data.current_balance);
                    $("#account_type").html(data.account_type);
                    $("#product_name").html(data.product_name);
                    $("#total_week_amount").html(data.total_week_amount);
                    $("#total_week_number").html(data.total_week_number);
                    $("#withdrawal_limit").html(data.withdrawal_limit);
                    $("#eligible_limit").html(data.eligible_limit);
                    $("#eligible_limit_status").html(data.eligible_limit_status);
                    $("#last_cash_withdrawn").html(data.last_cash_withdrawn);
                    $("#withdrawal_amount").html(data.withdrawal_amount);
                    $("#charge_amount").html(data.charge_amount);
                    // var eligibile_limit_after_withdraw = data.eligibile_limit_after_withdraw;
                    // if(eligibile_limit_after_withdraw < 0){

                    // }
                    $("#eligibile_limit_after_withdraw").html(data.eligibile_limit_after_withdraw);
                    $("#balance_after_withdraw").html(data.balance_after_withdraw);

                    if(data.pending_charge_amount > 0){
                        $(".pending_charge_nodata").css('display', 'none');
                        $(".pending_charge_data").css('display', 'block');
                        $("#pending_charge_amount_withdrawn").html(data.pending_charge_amount_withdrawn);
                        $("#pending_charge_eventid").html(data.pending_charge_eventid);
                        $("#pending_charge_amount").html(data.pending_charge_amount);
                       document.getElementById('account_number_input').value = data.account_number ;
                       document.getElementById('pending_charge_amount_input').value = data.pending_charge_amount ;
                       document.getElementById('pending_charge_eventid_input').value = data.pending_charge_eventid;
                    }
                    else{
                        $(".pending_charge_data").css('display', 'none');
                        $(".pending_charge_nodata").css('display', 'block');

                    }
                    $(".show_after_submit").css('display', 'block');
                    // clear everything inside the form
              
             }   
             else{
                    $("#alert_fail").css('display', 'block');
                    $('#alert_fail').append('<div>'+ data.error +'</div>');       

             }
            }
           }).done(function() {
      setTimeout(function(){
        $("#loadingoverlay").fadeOut(1000);
      },5000);
      });
     }
         window.submit_account_details=submit_account_details;


            $("#addkpi").submit(function(){
        submit_account_details(formid, formurl);
        return false;
     });





function post_pending_charge(formid, formurl){
         dataString = $(formid).serialize();
         // triger loading screen 
  // console.log(dataString);
         $(document).ajaxSend(function() {
            $("#loading").fadeIn(1000);　
          });

          

              $.ajax({
           type: "POST",
           url: formurl,
           // dataType: "json",
           data: dataString,          
           success:function(data){
                var data = JSON.parse(data);
                console.log(data);
                    if($.isEmptyObject(data.error)){

                    
                    $("#charge_details_section").css('display', 'block');
                    $("#pending_charge_data").css('display', 'none');
                    $("#charge_details").css('display', 'block');

                    $("#charge_details").append('<table><tbody>'+
                                '<tr>'+
                                    '<th>Amount Charged : </th>'+
                                    '<td>'+data.amount_charged+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<th>Principal Eventid :</th>'+
                                    '<td>'+data.eventid+'</td>'+
                                '</tr>'+
                                 '<tr>'+
                                    '<th>Teller ID :</th>'+
                                    '<td>'+data.teller_id+'</td>'+
                                '</tr>'+
                                 '<tr>'+
                                    '<th>Reference :</th>'+
                                    '<td>'+data.charge_reference+'</td>'+
                                '</tr>'+
                            '</tbody>'+
                        '</table>');
                    $("#charge_response_success").css('display', 'block');
                      // reload the page
                   

                }
                else{
                   // clear previous errors 
                    $("#alert_fail").empty();
                    $("#alert_fail").empty();
                   // display new triggered error
                    $("#alert_fail").css('display', 'block');
                    $('#charge_details').append('<div>'+ data.error +'</div>');       

                }
                
            }
           }).done(function() {
      setTimeout(function(){
        $("#loadingoverlay").fadeOut(1000);
      },5000);
      });
     }

      $("#post_pending_charge").submit(function(){
        post_pending_charge(formid, formurl);
        return false;  //stop the actual form post !important!
      });

// set refresh timer
(function(seconds) {
    var refresh,       
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href = location.href;
            }, seconds * 1000);
        };

    $(document).on('keypress click', function() { intvrefresh() });
    intvrefresh();

}(720)); // define here seconds