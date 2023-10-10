   <?php
    $nonceValue = 'nonce_value';
?>

<!DOCTYPE html>
<html>
<head>
    <!-- developed by Tobiloba Ajibola and Samaila Hakeem
    08064012829 -->
    <title>CASHAPP|LOGIN</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
    <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/icons/favicon.ico"/>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/animate/animate.css">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/main.css">
<!--===============================================================================================-->
</head>
    <body>
   <section>
     <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 portrait">
                   <form  class="login100-form validate-form" autocomplete="off" accept-charset="utf-8"  method="POST" id="regForm">
                        <span class="login100-form-title">
                   CASH WITHDRAWAL VERIFICATION PORTAL
                    </span>
                             
                             <input type="hidden" id="statement_login" name="statement_login"/>
                    <input type="hidden" id="login_type"  name="login_type" value="ad" />
                        <div class="row">
                    <div class="col-md-12 validate-input" data-validate = "Deposit Amount is required">
                        <input class="input100" type="text" id="username" name="username" placeholder="TajID Only" required="">
                        <span class="focus-input100"></span>
                     
                    </div>
                    </div>
                    <div class="row mt-4">
                    <div class="col-md-12 validate-input" data-validate = "Customer expected percentage is required">
                        <input class="input100" type="password" id="password" name="password" value="" placeholder="Password" required="">
                        <span class="focus-input100"></span>
                      
                    </div>
                    
                </div>
                          
                                 <div class="row mt-4">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4 ">
                                            <button type="submit" class="login100-form-btn">
                                                Login
                                            </button>
                                        </div>
                                        <div class=" text-center col-md-12 ">
                                            <div class="row response"  >
                                            <span id="alert_success" style="text-align: center;"></span>
                                            <span id="alert_fail" style="text-align: center;"></span>
                                            <span class="hide_after_submit" id="loading" style="display: none;">Loading...</span>
                                        </div>
                                           
                                    </div>
                                    <div class="col-md-4">
                                        
                                    </div>
                                    </div>
                                        <p></p>
                                        <!-- <span class="hide_after_submit" id="loading" style="display: none;">Loading...</span> -->
                                        
                 
                    </form>
                
        <div id="display_statement"></div>
                <div class="login100-pic row p-t-50" >
                        <div class="js-tilt" data-tilt>
                    <img src="<?php echo base_url();?>assets/images/tajbank.png" alt="IMG">
                </div>
                      
                       
                </div>

                
            </div>

                

        </div>
    </div>



</section>
        
    
<!--===============================================================================================-->  
    <script src="<?php echo base_url();?>assets/vendor/jquery/jquery-3.6.0.min.js"></script>
    <!-- <script src="<?php echo base_url();?>assets/vendor/jquery/jquery-3.6.0.min.js"></script> -->
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/vendor/tilt/tilt.jquery.min.js"></script>
    <script >
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
        <!-- Crypto js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
        
        <!-- Encryption js -->
        <script src="<?php echo base_url();?>assets/js/Encryption.js"></script>

        <script>
            $(document).ready(function() {
                 $(document).ajaxSend(function() {
            $("#loading").fadeIn(1000);　
          });
                // Submit form
                $("#regForm").submit(function (event) {
                    event.preventDefault();

                    var statement_login = $("#statement_login").val();
                    var login_type = $("#login_type").val();
                    var username = $("#username").val();
                    var password = $("#password").val();

                    var nonceValue = '<?php echo $nonceValue; ?>';
                    
                    // Encrypt form data
                    let encryption = new Encryption();
                    var passwordEncrypted = encryption.encrypt(password, nonceValue);

                    // Submit form using Ajax
                    $.ajax({
                        url: '<?php echo base_url();?>accounts/login_account',
                        method: 'POST',
                        data: {
                            statement_login: statement_login,
                            login_type: login_type,
                            username: username,
                            password: passwordEncrypted
                        },
                        success:function(data) {
                            var data = JSON.parse(data);
                            console.log(data);
                            if($.isEmptyObject(data.error)){

                    $("#alert_fail").empty();
                    $("#alert_success").empty();
                    $("#alert_success").css('display', 'block');
                    $(".hide_after_submit").css('display', 'none');
                    $("#alert_success").append('<strong>'+ data.success+'</strong>');
                    $(".show_after_submit").css('display', 'block');
                      // reload the page
                     window.setTimeout(function () {
                           location.reload('charge');
                        }, 1000);

                }
                else{
                   // clear previous errors 
                    $("#alert_fail").empty();
                    $("#alert_fail").empty();
                   // display new triggered error
                    $("#alert_fail").css('display', 'block');
                    $('#alert_fail').append('<div>'+ data.error +'</div>');       

                }
                        }
                    });

                });
            });
        </script>
    </body>
</html>

