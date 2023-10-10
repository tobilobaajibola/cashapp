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
            <div class="wrap-login100 portrait650">

                <!-- <form class="login100-form validate-form"   action="mystatement" method="post"> -->
    <form  class="login100-form validate-form"  id="statement_form" onsubmit="form_submitter('#statement_form', '<?php echo base_url();?>mystatement' ); return false" >
                    <span class="login100-form-title">
                        Account Statement 
                    </span>
                    <input type="hidden" name="api_key" value="676818F6FB0F4D11CCE290AF03E13223">
                    <input type="hidden" name="user" value="<?php echo $_SESSION['statement_account']['username'];?>">
                    <div class="row">

                    <div class="col-md-6 validate-input" data-validate = "Start date is required">
                        <label>Start Date</label>
                        <input class="input100" type="date" name="start_date" placeholder="Start Date" required="">
                        <span class="focus-input100"></span>
                     
                    </div>

                    <div class="col-md-6 validate-input" data-validate = "End date is required">
                        <label>End Date</label>
                        <input class="input100" type="date" name="end_date" placeholder="End Date" max="<?php echo date('m/d/Y');?>" required="">
                        <span class="focus-input100"></span>
                      
                    </div>
                    <div class="col-md-6 validate-input mt-4" data-validate = "Account number is required" >
                        <label>Account Number</label>
                        <input class="input100" type="number" name="account_number" placeholder="Account number" required="">
                        <span class="focus-input100"></span>
                       
                    </div>

                    <div class="col-md-6 validate-input mt-4" data-validate = "Account number is required" placeholder="Generate As" >
                        <label>Generate As</label>
                        <select class="input100" name="download_type" required="">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="emailpdf">Email PDF</option>
                        </select>
                    </div>


                </div>
                <div class="row mt-4">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <button class="login100-form-btn btn-block">
                             Generate 
                        </button>
                        <div class="row text-center mt-4">
                    <p></p>
                   <span class="hide_after_submit alert text-danger" id="loading" style="display: none; width: 100%;">Loading...</span>
                    <span id="alert_success" class="alert text-success" style="display: none; width: 100%;"></span>
                    <span id="alert_fail" class="alert text-danger" style="display: none; width: 100%;"></span>
                </div>
                       
                </div>
               
                </div>
                    
                

                </form>
                 <!-- enable to charge customer  -->
                <!-- <div id="charge_customer"></div> -->

                <div id="display_statement"></div>
               
             
                    <div class="login100-pic row p-t-50" >
                    <div class="js-tilt" data-tilt>
                        <img src="<?php echo base_url();?>assets/images/tajbg.png" alt="IMG">
                    </div>
                </div>
                           <div class="row" style="float:right; width: 100%;">
                      
                        <div class="p-t-20 text-center" style="float:right; width: 100%;">
                        
                        <a class="txt2 alert text-danger" href="<?php echo base_url();?>logout">
                            <!-- Without concession -->
                            <i class="fa fa-off-right m-l-5" aria-hidden="true"></i>Logout
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
    <script src="<?php echo base_url();?>assets/vendor/jquery/jquery-3.6.0.min.js"></script>
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
<!-- <script src="<?php //echo base_url();?>assets/js/custom.js"></script> -->

<!-- active_chart js -->
<!-- <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') {?> -->
<!-- <script src="<?php //echo base_url();?>assets/js/active_chart.js"></script> -->
<!-- <?php }?> -->
</body>
</html>