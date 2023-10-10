           

         <!--   'account_number' => $account_number,
                                    'account_name' => $account_details['NOMREST'],
                                    'current_balance' => $account_details['SIN'] ,
                                    'account_type' => $account_details['TCLI'],
                                    'product_name' => $account_details['INTI'],
                                    'total_week_amount' => $total_amount_this_week,
                                    'total_week_number' => '',
                                    'withdrawal_limit' => $withdrawal_limit,
                                    'eligible_limit' => $eligible_amount,
                                    'last_cash_withdrawn'=> '',
                                    'withdrawal_amount'=> $amount,
                                    'charge_amount' => $charge_amount,
                                    'eligibile_amount_after_withdraw' => $eligible_limit_after_withdrawal,
                                    'balance_after_withdraw' => $balance_after_withdrawal  );  -->    <div class="row">
   <div class="col-md-6 p-4  mar-right">
                        <table>
                            <tbody>
                                <tr>
                                    <th>Account Name : </th>
                                    <td><?php echo $account_detail['account_name'];?></td>
                                </tr>
                                <tr>
                                    <th>Account Number :</th>
                                    <td><?php echo $account_detail['account_number'];?></td>
                                </tr>
                                 <tr>
                                    <th>Current Balance :</th>
                                    <td><?php echo $account_detail['current_balance'];?></td>
                                </tr>
                                 <tr>
                                    <th>Account Type :</th>
                                    <td><?php echo $account_detail['account_type'];?></td>
                                </tr>
                                 <tr>
                                    <th>Product Name :</th>
                                    <td><?php echo $account_detail['product_name'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                   
                    <div class="col-md-6 col-md-6 p-4">
                        <table>
                            <tbody>
                                <tr>
                                    <th>Total Withdrawal : </th>
                                    <td><?php echo $account_detail['total_week_amount'];?></td>
                                </tr>
                                <tr>
                                    <th>Total number :</th>
                                    <td><?php echo $account_detail['total_week_number'];?></td>
                                </tr>
                                 <tr>
                                    <th>Withdrawal Limit :</th>
                                    <td><?php echo $account_detail['withdrawal_limit'];?></td>
                                </tr>
                                 <tr>
                                    <th>Eligible Limit :</th>
                                    <td><?php echo $account_detail['eligible_limit'];?></td>
                                </tr>
                                 <tr>
                                    <th>Last Withdrawal:</th>
                                    <td><?php echo $account_detail['last_cash_withdrawn'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>