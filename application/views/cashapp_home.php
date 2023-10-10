<section>
     <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 portrait900">
                <table>
                    <tr>

                        <td class="pull-right">Teller ID: <?php echo $_SESSION['cashapp_account']['teller_id'];?></td>
                    </tr>
                </table>
                    
                <!-- <form class="login100-form validate-form"   action="mystatement" method="post"> -->
                    <div class="form_section container-fluid">
    <form  class="login100-form validate-form"  id="account_details_form" onsubmit="submit_account_details('#account_details_form', '<?php echo base_url();?>account_details' ); return false" >
                    <span class="login100-form-title">
                        CASH WITHDRAWAL (INB)
                    </span>
                    <!-- <input type="hidden" name="api_key" value="676818F6FB0F4D11CCE290AF03E13223"> -->
                    <!-- <input type="hidden" name="user" value="<?php //echo $_SESSION['statement_account']['username'];?>"> -->
                    <div class="row">

                    <div class="col-md-5 validate-input p-4" data-validate = "Account number is required" >
                        <!-- <label>Account Number</label> -->
                        <input class="input100" type="number" name="account_number" placeholder="Account number" required="">
                        <span class="focus-input100"></span>
                       
                    </div>

                  <div class="col-md-5 validate-input p-4"  >
                        <!-- <label>Withdrawal Amount</label> -->
                        <input class="input100" type="number" name="amount" placeholder="Withdrawal Amount" value="0">
                        <span class="focus-input100"></span>
                       
                    </div>
                       <div class="col-md-2 p-4">
                        <button class="login100-form-btn btn-block">
                             Submit 
                        </button>
                   
                    </div>
                       
                </div>

                </form>
                <div id="alert_fail" class="p-l-20"></div>
            </div>



            <!-- <div class="account_data" style="display:none;"> -->
            <div class="account_section container-fluid ">
                <div id="display_account_details"></div>
                <div class="row">
                  <div class="col-md-7 p-4  mar-right">
                        <p class="sub-title p-b-10"><b>Account Information</b></p>
                        <table>
                            <tbody>
                                <tr>
                                    <th>Account Name : </th>
                                    <td><div id="account_name"></div></td>
                                </tr>
                                <tr>
                                    <th>Account Number :</th>
                                    <td><div id="account_number"></div> </td>
                                </tr>
                                 <tr>
                                    <th>Current Balance :</th>
                                    <td><div id="current_balance"></div> </td>
                                </tr>
                                 <tr>
                                    <th>Account Type :</th>
                                    <td><div id="account_type"></div> </td>
                                </tr>
                                 <tr>
                                    <th>Product Name :</th>
                                    <td><div id="product_name"></div> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                   
                    <div class="col-md-5 col-md-5 p-4">
                        <p class="sub-title p-b-10"><b>Branch withdrawal this week</b></p>
                        <table>
                            <tbody>
                                <tr>
                                    <th>Tota Withdrawal : </th>
                                    <td><div id="total_week_amount"></div> </td>
                                </tr>
                                <tr>
                                    <th>Total number :</th>
                                    <td><div id="total_week_number"></div></td>
                                </tr>
                                 <tr>
                                    <th>Withdrawal Limit :</th>
                                    <td><div id="withdrawal_limit"></div> </td>
                                </tr>
                                 <tr>
                                    <th>Eligible Limit :</th>
                                    <td><span id="eligible_limit"></span><span id="eligible_limit_status"></span> </td>
                                </tr>
                                 <tr>
                                   <!--  <th>Last Withdrawal:</th>
                                    <td><div id="last_cash_withdrawn"></div> </td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="withdrawal_section container-fluid"> 
                 <div class="row">
                    <div class="col-md-6 mar-right">
                        <p class="sub-title p-l-10 "><b>Proposed Withdrawal</b></p>
                        <div class="p-2">
                        <table>
                           <tbody>
                                <tr>
                                    <th>Amount to be Withdrawn: </th>
                                    <td><div id="withdrawal_amount"></div></td>
                                </tr>
                                <tr>
                                    <th>Charge after withdrawal :</th>
                                    <td><div id="charge_amount"></div></td>
                                </tr>
                                <tr>
                                    <th>Limit after withdrawal :</th>
                                    <td><div id="eligibile_limit_after_withdraw"></div></td>
                                </tr>
                                <tr>
                                    <th>Balance after withdrawal :</th>
                                    <td><div id="balance_after_withdraw"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="col-md-6 ">
                        <p class="sub-title p-l-10"><b>Pending Charge on last withdrawal by teller</b></p>
                        <div class="p-2 pending_charge_data"  style="display:none;">
                        <table>
                            <tbody>
                                <tr>
                                    <th>Amount Withdrawn: </th>
                                    <td><div id="pending_charge_amount_withdrawn"></div></td>
                                </tr>
                                <tr>
                                    <th>Withdrawal Event ID:</th>
                                    <td><div id="pending_charge_eventid"></div></td>
                                </tr>
                                <tr>
                                    <th>Amount to charge :</th>
                                    <td><div id="pending_charge_amount"></div></td>
                                </tr>
                            </tbody>
                         </table>

                        <form  class="validate-form"  id="post_pending_charge" onsubmit="post_pending_charge('#post_pending_charge', '<?php echo base_url();?>take_charge' ); return false" >
                           

                          
                                <input class="input100" type="hidden"  name="process_charge" value="process_charge" required="" readonly>
                                <input class="input100" type="hidden" id="pending_charge_amount_input" name="amount" required="" readonly>
                                <input class="input100" type="hidden" id="pending_charge_eventid_input" name="eventid" required="" readonly>
                                <input class="input100" type="hidden" id="account_number_input" name="account_number" required="" readonly>
                                
                             
                               <div class="col-md-7">
                                <button class="login100-form-btn btn-block">
                                     Take Charge
                                </button>
                           
                            </div>
                           </form>
                           </div>
                           <div class="pending_charge_nodata" style="display:none;">
                               <p>No Pending charge</p>
                           </div> 
                           <div class="charge_response_success" style="display:none;">
                            <div><img src="<?php echo base_url().'assets/images/success.png';?>" width="40" height="40"></div>
                            <p>Charge Successfuly Taken</p>
                        </div>
                        </div>

                
                    </div>
                </div>
          
            <div class="charge_details_section container-fluid">
                <div class="row">
                    <div class="col-md-12">
                  <p class="sub-title p-b-20"><b>Charge details</b></p>
                 <div id="charge_details"></div>
            </div>
            </div>
            </div>
        <!-- </div> -->
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
