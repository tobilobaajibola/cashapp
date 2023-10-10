<?php
/**
 * 
 */
class Charges extends CI_Controller
{
	
	function cashapp_view()
	{ 
		// $login_data =  array('username' =>'TAJ281', 'teller_id' =>'TAP0281', 'firstname' =>'tobiloba.ajibola@tajbank.com');

		// 	$_SESSION['cashapp_account'] = $login_data;
		// var_dump($_SESSION['cashapp_account']);
		$data= array();
		if(isset($_SESSION['cashapp_account'])){
           
		$data['page_layout']='cashapp_home';    
	    $this->load->view('page_layout', $data);
	}
	else{
		// echo $_SESSION['cashapp_account'];
            redirect(base_url().'login', 'refresh'); 
	}
	}

	function account_details(){
		if(isset($_SESSION['cashapp_account'])){
		$teller_id = 'TAJOS141';
		// $teller_id = $_SESSION['cashapp_account']['teller_id'];
		$data =array();
		// get_account_detail
        $account_number = $this->input->post('account_number'); 
        $amount = $this->input->post('amount'); 

		$account_details = $this->Charge->get_account_detail($account_number);
		$customer_number = $account_details['CLI'];
		
		// get customer transaction detail last one week
		$data['total_from_prev_day'] = $this->Charge->get_total_withdrawal_bkheve($customer_number);
		$data['total_from_today'] = $this->Charge->get_total_withdrawal_today($customer_number);

		$total_number_this_week = $data['total_from_prev_day']['TOTAL_NUMBER'] + $data['total_from_today']['TOTAL_NUMBER'];
		$total_amount_this_week = $data['total_from_prev_day']['TOTAL_AMOUNT'] + $data['total_from_today']['TOTAL_AMOUNT'];

		if (empty($account_details)) {
			echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>Account not found</strong></div>']);
			exit();
		}

		// get withdawal limit
		// if individual account
		if($account_details['TCLI'] == 1){
		$withdrawal_limit = 200000;
		$charge_percentage = 0.03;
		$account_type = 'Individual';
		}
	else{
		// if corporate account
		$withdrawal_limit = 5000000;
		$charge_percentage = 0.05;
		$account_type = 'Corporate';
		}
		// get customer local withdrawal limit for the week
		$eligible_amount = $withdrawal_limit - $total_amount_this_week;

		$eligible_limit_status = '';

		// if the local eligible amount is exceeded no need to call nibss, just go ahead and take full charge
		 if($eligible_amount < 1){
		$eligible_limit_status = '<span style="color:red">(Exceeded)</span>';
		}

		// if the local eligble amount has NOT been exceeded also check all channels to confirm the same
	

		$eligible_limit_after_withdrawal = $withdrawal_limit - ($total_amount_this_week + $amount);



		// calculate the charge to be taken from transaction whether full or part 
		// function charge_to_be_taken($eligible_amount, $amount, $charge_percentage);

		if($eligible_amount >= 0){
			$charge_amount = ($amount - $eligible_amount) * ($charge_percentage);
			// if charge amount is less than or equal to zero do not charge
			if($charge_amount <=0){
				$charge_amount = 0;
			}
			else{
				$charge_amount = $charge_amount;
			}


		}
		else{
			$charge_amount = $amount  * $charge_percentage;
		}

		$balance_after_withdrawal  = $account_details['SIN'] - $amount - $charge_amount;
		 if($balance_after_withdrawal < 1){
		$balance_after_withdrawal_status = '<span style="color:red">Insuffcient</span>';
		}

		// get last approved last withdrawal
		$data['last_withdrawal'] = $this->Charge->get_last_withdrawal_by_teller_today($account_number,  $teller_id);
		$last_amount_withdrawn = $data['last_withdrawal']['MHT1'] ;
		// var_dump($data['last_withdrawal']);
		// check if the transaction has not been charged using the eventid by checking charges taken and match with it eventid on bkeve
		$last_eventid_withdrawn = $data['last_withdrawal']['EVE'];



		// Get pending cash withdrawal charge on this account number processed by the teller starts
		// check if the last transaction can be charged 
		if($withdrawal_limit <= $total_amount_this_week){
		//check if charges on the last transaction will be on the full amount or part
		$total_amount_before_withdrawal = $total_amount_this_week - $last_amount_withdrawn;
		$eligible_amount_before_withdrawal = $withdrawal_limit - $total_amount_before_withdrawal;
		// if eligible amount amount before withdrawal is less than or equal to zero put charge on full amount
		if($eligible_amount_before_withdrawal <= 0){
			$pending_charge_amount = $last_amount_withdrawn * $charge_percentage;
		}
		else{
			$pending_charge_amount = ($last_amount_withdrawn - $eligible_amount) * ($charge_percentage);
		}

		}
		else{
			$pending_charge_amount = 0;
		}

		
		// Pass all required value to an array

		$account_detail = array('account_number' => $account_number,
									'account_name' => $account_details['NOMREST'],
									'current_balance' => $account_details['SIN'] ,
									'account_type' => $account_type,
									'product_name' => $account_details['INTI'],
									'total_week_amount' => number_format($total_amount_this_week, 2),
									'total_week_number' => $total_number_this_week,
									'withdrawal_limit' => number_format($withdrawal_limit, 2),
									'eligible_limit' => number_format($eligible_amount, 2),
									'eligible_limit_status'=> $eligible_limit_status,
									'last_cash_withdrawn'=> '',
									'withdrawal_amount'=> number_format($amount, 2),
									'charge_amount' => number_format($charge_amount, 2),
									'eligibile_limit_after_withdraw' => number_format($eligible_limit_after_withdrawal, 2),
									'balance_after_withdraw' => number_format($balance_after_withdrawal, 2),
									'pending_charge_amount_withdrawn' => number_format($last_amount_withdrawn, 2),
									'pending_charge_eventid' => $last_eventid_withdrawn,
									'pending_charge_amount' => $pending_charge_amount);
		
		// echo json_encode($transaction_detail);
		// check if last transaction is chargeable
        echo json_encode($account_detail);

		// $data['last_withdrawal'] = get_last_withdrawal_by_teller_today($account_number, $teller_id, $amount);
	    // $this->load->view('box/account_detail', $data);
        }
        else{
			echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>Session Timeout! Referesh page</strong></div>']);

        }

	}

	function transaction_to_be_charged($account_number){
		

		// $withdrawal_limit 
		// $total_amount_this_week  
		// $teller_id = 'tew';

		// Get pending cash withdrawal charge on this account number processed by the teller
		// get last approved last withdrawal
		$data['last_withdrawal'] = $this->Charge->get_last_withdrawal_by_teller_today($account_number, $teller_id);
		// check if the transaction has not been charged using the eventid

		// check if the last transaction can be charged 
		if($withdrawal_limit <= $total_amount_this_week){
		//check if charges on the last transaction will be on the full amount or part
		$total_amount_before_withdrawal = $total_amount_this_week - $amount;
		$eligible_amount_before_withdrawal = $withdrawal_limit - $total_amount_before_withdrawal;
		// if eligible amount amount before withdrawal is less than or equal to zero put charge on full amount
		if($eligible_amount_before_withdrawal <= 0){
			$pending_charge_amount = $amount_withdrawn * $charge_percentage;
		}
		else{
			$pending_charge_amount = ($amount - $eligible_amount) * ($charge_percentage);
		}

		}
		else{
			$pending_charge_amount = 0;
		}



	}

	function process_charge(){

		if ($_POST['process_charge']) {

		$account_number = $this->input->post('account_number'); 
        $charge_amount = $this->input->post('amount'); 
        $eventid = $this->input->post('eventid'); 
        $teller_id = $_SESSION['cashapp_account']['teller_id'];

        // validate if this charge has not been taken

		$account_details = $this->Charge->get_account_detail($account_number);

		// using eventid confirm the charge hasn't been posted
		// validate teller id
$Narration = $eventid.'|'.$account_number.'|'.$teller_id.'|cash w charge';
$requestid =  date('YmdHis').$account_number;
	   $transaction_details = array(
		   'RequestID' => $requestid,		
		   'TranCurrency' => 'NGN' ,
		   'TranRemarks' => $requestid,
		   'SplitFee' => 'N' ,
		   'TranType' => 'ISWMOBILE',
		   'Account'=>array('DebitAccount' => $account_number ,
		   'CreditAccount' => '1000000322',
		   'DebitBranch' => $account_details['AGE'],
		   'CreditBranch' => 0001,
		   'TranAmount' => $charge_amount,
		   $Narration => $Narration));
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://172.19.11.32:8070/api/CBA/IntraBankAccountTransfer',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"RequestID":"'.$requestid.'",
"TranCurrency":"NGN",
"TranRemarks":"'.$requestid.'",
"SplitFee":"N",
"TranType":"ISWMOBILE",
"Accounts":{"DebitAccount":"'.$requestid.'",
"CreditAccount":"0000002456",
"DebitBranch":"'.$account_details['AGE'].'",
"CreditBranch":"00001",
"TranAmount":'.$charge_amount.',
"Narration":"'.$Narration.'"},
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Basic dG9iaToxMDdmNzMzMTM4OWI3NmZqMjc2ZTYxMjMxNDZkMjNkOGExMDkwMmg3'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response, true);
     // echo $response['ResponseMessage'];
if($response['ResponseMessage']=='Success')
    {

	   $transaction_response = array(
	   							   'teller_id' => $teller_id,
								   'charge_reference' => $requestid,
								   'eventid' =>  $eventid,
								   'amount_charged' =>  $charge_amount);
	   // echo json_encode($response);
	   echo json_encode($transaction_response);
	}
else{
			echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>'.$response['Response']['ReasonDescription'][0].'</strong></div>']);
}
		
	}
}


function transaction_autorization_request_nibss(){

}


}

?>