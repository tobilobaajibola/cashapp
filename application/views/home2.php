<!DOCTYPE html>
<html>
<head>
    <!-- developed by Tobiloba Ajibola and Samaila Hakeem
    08064012829 -->
    <title>Statement Portal</title>
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
	 <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <!-- <form class="login100-form validate-form"   action="mystatement" method="post"> -->
    <form  class="login100-form validate-form"  id="statement_form" onsubmit="form_submitter('#statement_form', '<?php echo base_url();?>mystatement' ); return false" >
                    <span class="login100-form-title">
                        Account Statement 
                    </span>
                    
                    <div class="row">
                    <div class="col-md-4 validate-input" data-validate = "Start date is required">
                        <input class="input100" type="date" name="start_date" placeholder="Start Date" required="">
                        <span class="focus-input100"></span>
                     
                    </div>

                    <div class="col-md-4 validate-input" data-validate = "End date is required">
                        <input class="input100" type="date" name="end_date" placeholder="End Date" max="<?php echo date('m/d/Y');?>" required="">
                        <span class="focus-input100"></span>
                      
                    </div>
                    <div class="col-md-4 validate-input" data-validate = "Account number is required" >
                        <input class="input100" type="number" name="account_number" placeholder="Account number" required="">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 ">
                        <button class="login100-form-btn">
                             Generate 
                        </button>
                       
                       
                </div>
                <div class="col-md-4">
                    <p></p>
                    <span class="hide_after_submit" id="loading" style="display: none;">Loading...</span>
                    <span id="alert_success"></span>
                    <span id="alert_fail"></span>
                </div>
                </div>
                    
                

                </form>
                 
            
                <div id="display_statement"></div>
                <div class="login100-pic row p-t-50" >
                        <div class="js-tilt" data-tilt>
                    <img src="<?php echo base_url();?>assets/images/tajbank.png" alt="IMG">
                </div>
                      
                        <div class="text-center p-t-136">
                        
                        <a class="txt2" href="indexc.php">
                            <!-- Without concession -->
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                
            </div>

                

        </div>
    </div>



</section>
<!-- main content part end -->

<!-- footer  -->
<!-- jquery slim -->
    
<!--===============================================================================================-->  
    <script src="<?php echo base_url();?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
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
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/js/main.js"></script>


<!-- custom js -->
<script src="<?php echo base_url();?>assets/js/custom.js"></script>

<!-- active_chart js -->
<!-- <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') {?> -->
<script src="<?php echo base_url();?>assets/js/active_chart.js"></script>
<!-- <?php }?> -->
</body>
</html>