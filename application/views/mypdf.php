<<!DOCTYPE html>
<html>
<head>
    <!-- developed by Tobiloba Ajibola and Hakeem Samaila
    08064012829 -->
    <title>Taj Account Statement <?php echo $account_balance['ACCTNO'];?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body id="wrapper" class="statement_body">
<div id="container">
<img   src="<?php echo $_SERVER["DOCUMENT_ROOT"]; ?>/statements/assets/images/tajbg.jpg">

<div class="top-section">
<div class="account_summary">
    <div class="customer_details">
       <p><h3 style="line-height: 20px;"><?php echo $customer_detail['NOMREST'];?></h3></p> 
       <p><b><?php echo $customer_detail['CPRO'].' - '. $customer_detail['INTI'];?></b></p> 
       <p style="line-height: 12px;"><b><?php echo $customer_detail['CUSTOMER_ADDRESS'];?></b></p> 
    </div>
    <div class="account_details">
        <table class="main-summary">
            <tr>
                <td>
                    <table   class="left-menu" style="margin-right: 30px;">
                        <tr><td class="left-menu-content">Account Number</td></tr>
                        <tr><td class="left-menu-content">Currency</td></tr>
                        <tr><td class="left-menu-content">Opening Balance</td></tr>
                        <tr><td class="left-menu-content">Total Credit</td></tr>
                        <tr><td class="left-menu-content">Total Debit</td></tr>
                        <tr><td class="left-menu-content">Tran Balance</td></tr>
                        <tr><td >Available Balance</td></tr>
                    </table>
                </td>
                <td>
                <table class="right-menu" style="float:right;">
                    <tr><td><?php echo $account_balance['ACCTNO'];?></td></tr>
                    <tr><td><?php echo $account_balance['CURRENCY'];?></td></tr>
                    <tr><td><?php echo number_format($opening_balance['OPENING_BAL'],  2 );?></td></tr>
                    <tr><td><?php echo number_format($total_transaction['TOTAL_CREDITS'],  2 );?></td></tr>
                    <tr><td><?php echo number_format($total_transaction['TOTAL_DEBIT'], 2 );?></td></tr>
                    <tr><td><?php echo number_format($account_balance['TRANS_BAL'],  2 );?></td></tr>
                    <tr><td><?php echo number_format($account_balance['AVAIL_BAL'], 2 );?> </td></tr>
                </table>
                </td>
            </tr>


        </table>
    </div>
</div>

<div  class="image_center advert">
    <br>
<img  style="width:49%" src="<?php echo $_SERVER["DOCUMENT_ROOT"]; ?>/statements/assets/images/ussd.png">
<img style="width:49%" src="<?php echo $_SERVER["DOCUMENT_ROOT"]; ?>/statements/assets/images/finance.png">
<!-- <img style="width:100%" src="<?php echo base_url();?>assets/images/sbanner.jpg"> -->
</div>
</div>
<div>
<table class="table " > 
    <thead style="border: 0.5px solid #d2121d; border-bottom: 0px!important;">
    <tr style="color:#d2121d; ">
            <td width="200"><b>Account History - for <?php echo $account_balance['ACCTNO'].' '.$customer_detail['INTI'];?></b></td>
            <td width="200" style="float:right;"><b>Statement Cycle <?php echo $transaction_start_date .' to '. $transaction_end_date;?></b></td>
        </tr>
    </thead>
    </table>
    <div  id="statement_wrapper" >
<table class="table   statement_table">
       
    <thead>
        <tr>
            <th width="50">Trans Date</th>
            <th width="50">Value Date</th>
            <th>Branch</th>
            <th>Transaction Details</th>
            <th>Reference</th>
            <th>Deposit</th>
            <th>Withdrawal</th>
            <th>Balance</th>
        </tr>
    </thead>

    <tbody>
     <tr class="bal_fwd" style="background: #fdd1d1;">
        <td></td>
        <td></td>
        <td></td>
        <td >Balance Brought Forward</td>
        <td></td>
        <td></td>
        <td></td>
        <td ><?php echo number_format($opening_balance['OPENING_BAL'],  2 );?></td>
    </tr>
        <?php
        foreach ($transact_history as $key => $transaction_histories) {
           // remove transaction with zero naira or other currency
            if ($transaction_histories['MON1'] == 0  and $transaction_histories['NCP1']==$account_balance['ACCTNO']){
                    // show nothing
            }
            else{
                //remove breakedown of bulk multiple transaction from debitted account
            if ($transaction_histories['TYP']== 150 and $transaction_histories['NAT'] <> 'VIRMUL' and $transaction_histories['NCP1'] ==  $account_balance['ACCTNO']) {
                    // show nothing
            }
            else{
            ?>
        <tr style="font-size: 5px;">
            <td><?php  echo $transaction_histories['DSAI']; ?></td>
            <td><?php  echo $transaction_histories['DATEH']; ?></td>
            <td><?php  echo $customer_detail['AGE']; ?></td>
            <td><?php  echo  $transaction_histories['NARRATION']; ?></td>
            <td></td>
            <td class="deposit"><?php if($transaction_histories['TYPE']== 'deposit') {echo  $transaction_histories['AMOUNT'];} else{ echo '0.00';} ?></td>
            <td class="withdrawal"><?php if($transaction_histories['TYPE']== 'withdrawal') {echo  $transaction_histories['AMOUNT'];} else{ echo '0.00';} ?></td>
            <td><?php  echo  $transaction_histories['AVAILABLE_BALANCE']; ?></td>
        </tr>
        <?php
    }
        }
    }
            ?>
                <?php
        foreach ($transaction_event as $key => $transaction_events) {
                //remove breakedown of bul transaction from debitted account
            if ($transaction_events['TYP']== 150 and $transaction_events['NAT'] <> 'VIRMUL' and $transaction_events['NCP1'] ==  $account_balance['ACCTNO']) {
                    // show nothing
                }
                else{
            ?>
        <tr>
            <td><?php  echo $transaction_events['DSAI']; ?></td>
            <td><?php  echo $transaction_events['DATEH']; ?></td>
            <td><?php  echo $customer_detail['AGE']; ?></td>
            <td><?php  echo  $transaction_events['NARRATION']; ?></td>
            <td></td>
            <td class="deposit"><?php if($transaction_events['TYPE']== 'deposit') {echo  $transaction_events['AMOUNT'];} else{ echo '0.00';} ?></td>
            <td class="withdrawal"><?php if($transaction_events['TYPE']== 'withdrawal') {echo  $transaction_events['AMOUNT'];} else{ echo '0.00';} ?></td>
            <td><?php  echo  $transaction_events['AVAILABLE_BALANCE']; ?></td>
        </tr>
        <?php
    }
        }
            ?>
              <tr class="bal_fwd" style="background: #fdd1d1;">
        <td></td>
        <td></td>
        <td></td>
        <td><b>END OF STATEMENT</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td ></td>
    </tr>
    </tbody>
</table>
</div>
</div>
</div>
<footer>
    <em>You must advise TAJBank of any discrepancies observed in this statement within 2 weeks of receipt, otherwise it will be considered accurate. All products and services are subject to the Bank(s) terms and conditions. For any enquiries contact: Phone Number: +2349087937417,+2347008252265, Email: tajconnect@tajbank.com</em>
</footer>
</body>
</html>

<style type="text/css">

.statement_body{
                background-image:url('<?php echo $_SERVER["DOCUMENT_ROOT"]; ?>/statements/assets/images/wmark.JPG');
                /*background-size: ;*/
                background-repeat: no-repeat;
                background-position: 0% 65%;
                background-size: 400px 400px;
                
            }

.statement_body::before {
    opacity: 0.40;
    z-index: -1;
}

.top-section{
    height: 330px;
}
.withdrawal{
color: #d2121d;
}

.deposit{
color : green;
}
.advert{
    display: block;
    margin-top: 15%;
    float: none;
}

/*#container {
   background-image: url('<?php echo base_url();?>assets/images/tajbg.png');
   background-repeat: repeat;
   padding: 0px !important;
   marigin: 0px !important;
}
*/
.account_summary{
    float: left;
    display: inline-flex;
    width: 700px;
}
.account_summary table.main-summary{
/*border: 1px solid  #d2121d ;*/
}
.account_summary tr{
/*border: 1px solid  #d2121d ;*/
margin: 0px;
}

.customer_details{
    width: 49%;
    font-size: 10px;
    line-height: 6px;
    font-weight: 4px; 
    margin-top: 35px; 
}

.account_details{
display: inline;
float: right;
width: 49%;
}
.account_details table.main-summary{
float: right; 
font-size: 40px;
}
  .right-menu td{
       height: 13px;
    }
.left-menu{
    background-color: #e6e6e6;
    border: 1px solid #d2121d;
}

.left-menu-content{
    border-bottom: 1px solid #d2121d;
}



.statement_table{
    border: 1px solid #d2121d;
    /*margin-top: -40px;*/
}

.statement_table tbody{
     border: 1.5px solid #525659;
}
.statement_table thead tr{
    background-color: #767679;
    color:  #ffffff;
}

.statement_table td{
    border-right: 0px;
    border-left: 0px;
    padding-right: 0px !important;
    padding-right: 0px !important;
    font-size: 8px;
}

.statement_table tr{
    border-bottom: 0.5px solid #525659 !important;
    border-top: 0.5px solid #525659 !important;
    font-size: 8px;
}

footer {
                position: fixed; 
                bottom: -50px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                /** Extra personal styles **/
                /*background-color: #e6e6e6;*/
                color: #999;
                text-align: center;
                /*line-height: 15px;*/
                font-size: 10px;
            }

html {
    font-family: arial;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%
}

body {
    margin: 0
}

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
main,
menu,
nav,
section,
summary {
    display: block
}

audio,
canvas,
progress,
video {
    display: inline-block;
    vertical-align: baseline
}

audio:not([controls]) {
    display: none;
    height: 0
}

[hidden],
template {
    display: none
}

a {
    background-color: transparent
}

a:active,
a:hover {
    outline: 0
}

abbr[title] {
    border-bottom: 1px dotted
}

b,
strong {
    font-weight: 700
}

dfn {
    font-style: italic
}

h1 {
    margin: .67em 0;
    font-size: 2em
}

table {
    background-color: transparent;
    /*border-collapse: collapse;*/
}

caption {
    padding-top: 8px;
    padding-bottom: 8px;
    color: #777;
    text-align: left
}

th {
    text-align: center;
    font-size: 10px;
}
td{
    font-size: 10px;
}


.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 0px;
    border-collapse: collapse;
}

.table>tbody>tr>td,
.table>tbody>tr>th,
.table>tfoot>tr>td,
.table>tfoot>tr>th,
.table>thead>tr>td,
.table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    /*border-top: 1px solid #e6e6e6;*/
border-bottom: 0.5px solid  #525659 ;

}

.table>thead>tr>th {
    vertical-align: bottom;
    /*border-bottom: 2px solid #e6e6e6*/
    border-bottom: 1px solid  #d2121d ;

}

.table>caption+thead>tr:first-child>td,
.table>caption+thead>tr:first-child>th,
.table>colgroup+thead>tr:first-child>td,
.table>colgroup+thead>tr:first-child>th,
.table>thead:first-child>tr:first-child>td,
.table>thead:first-child>tr:first-child>th {
    border-top: 0
}

.table>tbody+tbody {
    border-top: 2px solid #e6e6e6
}

.table .table {
    background-color: transparent;
}

.table-condensed>tbody>tr>td,
.table-condensed>tbody>tr>th,
.table-condensed>tfoot>tr>td,
.table-condensed>tfoot>tr>th,
.table-condensed>thead>tr>td,
.table-condensed>thead>tr>th {
    padding: 5px
}

.table-bordered {
    /*border: 1px solid #e6e6e6*/
}

.table-bordered>tbody>tr>td,
.table-bordered>tbody>tr>th,
.table-bordered>tfoot>tr>td,
.table-bordered>tfoot>tr>th,
.table-bordered>thead>tr>td,
.table-bordered>thead>tr>th {
    /*border: 1px solid #e6e6e6*/
}

.table-bordered>thead>tr>td,
.table-bordered>thead>tr>th {
    border-bottom-width: 2px
}

.table-striped>tbody>tr:nth-of-type(odd) {
    background-color: #ffffff
}

.table-hover>tbody>tr:hover {
    background-color: #f5f5f5
}

table col[class*=col-] {
    position: static;
    display: table-column;
    float: none
}

table td[class*=col-],
table th[class*=col-] {
    position: static;
    display: table-cell;
    float: none
}

.table>tbody>tr.active>td,
.table>tbody>tr.active>th,
.table>tbody>tr>td.active,
.table>tbody>tr>th.active,
.table>tfoot>tr.active>td,
.table>tfoot>tr.active>th,
.table>tfoot>tr>td.active,
.table>tfoot>tr>th.active,
.table>thead>tr.active>td,
.table>thead>tr.active>th,
.table>thead>tr>td.active,
.table>thead>tr>th.active {
    background-color: #f5f5f5
}

.table-hover>tbody>tr.active:hover>td,
.table-hover>tbody>tr.active:hover>th,
.table-hover>tbody>tr:hover>.active,
.table-hover>tbody>tr>td.active:hover,
.table-hover>tbody>tr>th.active:hover {
    background-color: #e8e8e8
}

.table>tbody>tr.success>td,
.table>tbody>tr.success>th,
.table>tbody>tr>td.success,
.table>tbody>tr>th.success,
.table>tfoot>tr.success>td,
.table>tfoot>tr.success>th,
.table>tfoot>tr>td.success,
.table>tfoot>tr>th.success,
.table>thead>tr.success>td,
.table>thead>tr.success>th,
.table>thead>tr>td.success,
.table>thead>tr>th.success {
    background-color: #dff0d8
}

.table-hover>tbody>tr.success:hover>td,
.table-hover>tbody>tr.success:hover>th,
.table-hover>tbody>tr:hover>.success,
.table-hover>tbody>tr>td.success:hover,
.table-hover>tbody>tr>th.success:hover {
    background-color: #d0e9c6
}

.table>tbody>tr.info>td,
.table>tbody>tr.info>th,
.table>tbody>tr>td.info,
.table>tbody>tr>th.info,
.table>tfoot>tr.info>td,
.table>tfoot>tr.info>th,
.table>tfoot>tr>td.info,
.table>tfoot>tr>th.info,
.table>thead>tr.info>td,
.table>thead>tr.info>th,
.table>thead>tr>td.info,
.table>thead>tr>th.info {
    background-color: #d9edf7
}

.table-hover>tbody>tr.info:hover>td,
.table-hover>tbody>tr.info:hover>th,
.table-hover>tbody>tr:hover>.info,
.table-hover>tbody>tr>td.info:hover,
.table-hover>tbody>tr>th.info:hover {
    background-color: #c4e3f3
}

.table>tbody>tr.warning>td,
.table>tbody>tr.warning>th,
.table>tbody>tr>td.warning,
.table>tbody>tr>th.warning,
.table>tfoot>tr.warning>td,
.table>tfoot>tr.warning>th,
.table>tfoot>tr>td.warning,
.table>tfoot>tr>th.warning,
.table>thead>tr.warning>td,
.table>thead>tr.warning>th,
.table>thead>tr>td.warning,
.table>thead>tr>th.warning {
    background-color: #fcf8e3
}

.table-hover>tbody>tr.warning:hover>td,
.table-hover>tbody>tr.warning:hover>th,
.table-hover>tbody>tr:hover>.warning,
.table-hover>tbody>tr>td.warning:hover,
.table-hover>tbody>tr>th.warning:hover {
    background-color: #faf2cc
}

.table>tbody>tr.danger>td,
.table>tbody>tr.danger>th,
.table>tbody>tr>td.danger,
.table>tbody>tr>th.danger,
.table>tfoot>tr.danger>td,
.table>tfoot>tr.danger>th,
.table>tfoot>tr>td.danger,
.table>tfoot>tr>th.danger,
.table>thead>tr.danger>td,
.table>thead>tr.danger>th,
.table>thead>tr>td.danger,
.table>thead>tr>th.danger {
    background-color: #f2dede
}

.table-hover>tbody>tr.danger:hover>td,
.table-hover>tbody>tr.danger:hover>th,
.table-hover>tbody>tr:hover>.danger,
.table-hover>tbody>tr>td.danger:hover,
.table-hover>tbody>tr>th.danger:hover {
    background-color: #ebcccc
}

.table-responsive {
    min-height: .01%;
    overflow-x: auto
}

@media screen and (max-width:767px) {
    .table-responsive {
        width: 100%;
        margin-bottom: 15px;
        overflow-y: hidden;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border: 1px solid #e6e6e6
    }
    .table-responsive>.table {
        margin-bottom: 0
    }
    .table-responsive>.table>tbody>tr>td,
    .table-responsive>.table>tbody>tr>th,
    .table-responsive>.table>tfoot>tr>td,
    .table-responsive>.table>tfoot>tr>th,
    .table-responsive>.table>thead>tr>td,
    .table-responsive>.table>thead>tr>th {
        white-space: nowrap
    }
    .table-responsive>.table-bordered {
        border: 0
    }
    .table-responsive>.table-bordered>tbody>tr>td:first-child,
    .table-responsive>.table-bordered>tbody>tr>th:first-child,
    .table-responsive>.table-bordered>tfoot>tr>td:first-child,
    .table-responsive>.table-bordered>tfoot>tr>th:first-child,
    .table-responsive>.table-bordered>thead>tr>td:first-child,
    .table-responsive>.table-bordered>thead>tr>th:first-child {
        border-left: 0
    }
    .table-responsive>.table-bordered>tbody>tr>td:last-child,
    .table-responsive>.table-bordered>tbody>tr>th:last-child,
    .table-responsive>.table-bordered>tfoot>tr>td:last-child,
    .table-responsive>.table-bordered>tfoot>tr>th:last-child,
    .table-responsive>.table-bordered>thead>tr>td:last-child,
    .table-responsive>.table-bordered>thead>tr>th:last-child {
        border-right: 0
    }
    .table-responsive>.table-bordered>tbody>tr:last-child>td,
    .table-responsive>.table-bordered>tbody>tr:last-child>th,
    .table-responsive>.table-bordered>tfoot>tr:last-child>td,
    .table-responsive>.table-bordered>tfoot>tr:last-child>th {
        border-bottom: 0
    }
}

</style>