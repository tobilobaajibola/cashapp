    <section>
	 <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">

                <!-- <form class="login100-form validate-form"   action="mystatement" method="post"> -->
    <form  class="login100-form validate-form"   id="login_form" onsubmit="login_user('#login_form', '<?php echo base_url();?>accounts/login_account' ); return false" >
                    <span class="login100-form-title">
                    Account Statement Login 
                    </span>
                    <input type="hidden" name="statement_login"/>
                    <input type="hidden" name="login_type" value="ad" />
                    
                    <div class="row">
                    <div class="col-md-6 validate-input" data-validate = "Deposit Amount is required">
                        <input class="input100" type="text" name="username" placeholder="TajID" required="">
                        <span class="focus-input100"></span>
                     
                    </div>

                    <div class="col-md-6 validate-input" data-validate = "Customer expected percentage is required">
                        <input class="input100" type="password" name="password" placeholder="Password" required="">
                        <span class="focus-input100"></span>
                      
                    </div>
                    
                </div>
                <div class="row mt-4">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 ">
                        <button class="login100-form-btn">
                            Login
                        </button>
                        <div class="row response"  >
                        <span id="alert_success" style="text-align: center;"></span>
                        <span id="alert_fail" style="text-align: center;"></span>
                    </div>
                       
                </div>
                <div class="col-md-4">
                    
                </div>
                </div>
                    <p></p>
                    <span class="hide_after_submit" id="loading" style="display: none;">Loading...</span>
                    
                

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
<!-- main content part end -->

