<?php

function   export_to_raw($transaction_history, $transaction_event, $opening_balance, $account_balance, $total_transaction,$customer_detail, $account_number, $transaction_start_date, $transaction_end_date){
    $ci = & get_instance();


        $transaction_detail = array('AcctBalDet'=>array(
        'Accountnumber' =>  $customer_detail['NCP'],
        'OpeningBalance' => number_format($opening_balance['OPENING_BAL'],  2 ),
        'Currency' =>   $account_balance['CURRENCY'],
        'LienAmount' => number_format($opening_balance['lien_amount'],  2 ),
        'ForthePeriodOf' => $ci->input->post('start_date') .' To '. $ci->input->post('end_date'),
        'AvailableBalance' => $account_balance['AVAIL_BAL'],
        'UnClearedBalance' => $account_balance['TRANS_BAL'] ,
        'AccountName' => $customer_detail['NOMREST'],
        'TotalCredit' => $total_transaction['TOTAL_CREDITS'],
        'TotalDebit' => $total_transaction['TOTAL_DEBIT'],
        'CustAddress' => $customer_detail['CUSTOMER_ADDRESS'],
        'CustomerId' => str_replace(' ', '', $customer_detail['CLI']),
        'ProductName' => $customer_detail['CPRO'].' - '. $customer_detail['INTI'],
        'ProductCode' => $customer_detail['CPRO'],
        'AcctOpenDate' => $customer_detail['DOU'],
        'BranchName' => $customer_detail['BRANCH_NAME'],
        'AcctStmtHist' => array(),
        'today_transact' => array(),
                
    ));
$count = 1;
$count = $count +1;
$deposit ='';
$withdrawal ='';
foreach($transaction_history as $transact_histories){
    if($transact_histories['TYPE']=='withdrawal'){
        $withdrawal = 'D'; }else{$withdrawal=''; }

      if($transact_histories['TYPE']=='deposit'){
        $deposit = 'C'; } else{$deposit='';}

      if ($transact_histories['MON1'] == 0  and $transact_histories['NCP1']==$account_balance['ACCTNO']){
                    // show nothing
            }
                else{
                //remove breakedown of bulk multiple transaction from debitted account
            if ($transact_histories['TYP']== 150 and $transact_histories['NAT'] <> 'VIRMUL' and $transact_histories['NCP1'] ==  $account_balance['ACCTNO']) {
                    // show nothing
                }
                else{
        $transaction_detail['AcctStmtHist'][]=  
            array(  
            'TranSerial'=> $count,
            'TransDate' => $transact_histories['DSAI'],
            'Reference' => '',
            'TransDetails' => $transact_histories['NARRATION'],
            'ValueDate' => $transact_histories['DATEH'],
            'Debit' => $withdrawal ,
            'Credit' => $deposit,
            'TransAmt' => str_replace(' ', '', $transact_histories['AMOUNT']),
            'TranBranch' => $customer_detail['AGE'],
            'Currency' => $account_balance['CURRENCY'],
            'TranId' => "976107",
            'TranPostedDateTime' => $transact_histories['DSAI'],
            'RunningBalance' => str_replace(' ', '',$transact_histories['AVAILABLE_BALANCE']));
           
    }
}
}
    foreach($transaction_event as $transact_events){
        if($transact_events['TYPE']=='withdrawal'){$withdrawal = 'D'; }else{$withdrawal=''; }

      if($transact_events['TYPE']=='deposit'){ $deposit = 'C';} else{$deposit='';}

        $transaction_detail['today_transact'][] = 
        array(   
            'TranSerial'=> $count,
            'TransDate' => $transact_events['DSAI'],
            'Reference' => '',
            'TransDetails' => $transact_events['NARRATION'],
            'ValueDate' => $transact_events['DATEH'],
            'Debit' => $withdrawal,
            'Credit' => $deposit,
            'TransAmt' => $transact_events['AMOUNT'],
            'TranBranch' => $customer_detail['AGE'],
            'Currency' => $account_balance['CURRENCY'],
            'TranId' => "976107",
            'TranPostedDateTime' => $transact_events['DSAI'],
            'RunningBalance' => str_replace(' ', '',$transact_events['AVAILABLE_BALANCE']));
            }

            return  $transaction_detail;

}

?>