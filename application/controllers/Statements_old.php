<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Style\Border;
// use PhpOffice\PhpSpreadsheet\Style\Color;
class Statements extends CI_Controller {


    function index(){
        if(isset($_SESSION['statement_account'])){
            $data= array();
            $username= $_SESSION['statement_account']['username'];
            $data['user_data'] = $this->Account->login_user($username);
            $this->load->view('home', $data); 
        }
        else{
            redirect(base_url().'login', 'refresh'); 
        }
    }

   public function get_statements()
    {

        // header('Content-Type: application/json,  text/html, image/png; charset=utf-8');
        if ($this->input->post('download_type')=="raw") {
        header('Content-Type: application/json; charset=utf-8');
        }
        Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
        Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
        Header('Access-Control-Allow-Methods: GET,  POST'); //method allowed
        Header('Access-Control-Allow-Credentials: true');


        $start_date = $this->input->post('start_date'); 
     
            $end_date = $this->input->post('end_date'); 
   
    // validate date format yyyy-mm-dd
    function validate_date($date){
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",  $date)) {
       return TRUE;
        } else {
                echo json_encode(['error'=> 'Invalid Date format']);
                exit;
        }
    }

        validate_date($start_date);
        validate_date($end_date);
   
        if($_POST)
        {
         $this->form_validation->set_rules('start_date', 'Start Date', 'required|exact_length[10]');
        $this->form_validation->set_rules('end_date', 'End Date', 'required|exact_length[10]');
        $this->form_validation->set_rules('account_number', 'Nuban', 'required|exact_length[10]|is_natural');
        if ($this->form_validation->run() == FALSE) {
         $data['response_msg']=validation_errors();
                  echo json_encode(['error'=> $data['response_msg']]);


        }
        else{


            $start_date = $this->input->post('start_date'); 
            $start_date = str_replace('-', '/', $start_date);  
            $start_date = date("d/M/Y", strtotime($start_date));  
            // echo "New date format is: ".$newDate. " (YYYY/MM/DD)";
            $end_date = $this->input->post('end_date'); 
            $end_date = str_replace('-', '/', $end_date);  
            $end_date = date("d/M/Y", strtotime($end_date));  
            $account_number = $this->input->post('account_number');
            $download_type = $this->input->post('download_type');

            // echo $_SERVER['REMOTE_ADDR'];
            $api_key = $this->input->post('api_key');
            $data['check_api_access'] = $this->Account->check_api_access($api_key,$_SERVER['SERVER_ADDR']);
            if($data['check_api_access'] == TRUE){
                $log_request_data = array('app_id' => $data['check_api_access']['id'],
                                  'sys_ipadr'=> $_SERVER['REMOTE_ADDR'],
                                  'app_ipadr'=> $_SERVER['SERVER_ADDR'],
                                  'nuban' => $account_number,
                                  'start_date'=> $start_date,
                                  'end_date'=> $end_date,
                                  'status'=>'requesting',
                                  'format'=> $download_type);
                $this->Account->request_log($log_request_data);
                 }
              else{
                echo json_encode(['error'=>'Access Denied']);
                    exit;
              }
        // $data = array();
        // // $account_number, $start_date, $end_date
        // $data['statement'] = $this->Statement->get_statement($start_date, $end_date, $account_number);
       
        // $this->load->library('pdf');
        // // $this->load->view('mypdf', $data);
        // $html = $this->load->view('mypdf', $data, true);
        // $this->pdf->createPDF($html, 'mypdf', false);

        // verify the application credential

   

if($this->input->post('start_date') > $this->input->post('end_date')){
        echo json_encode(['error'=>'<div class="alert text-danger">Start date cannot be greater than End date</div>']);
}
else{
        $data = array();
        // $account_number, $start_date, $end_date
        $start_date = $start_date;
        $end_date = $end_date;
        $data['transaction_start_date'] = $start_date;
        $data['transaction_end_date'] = $end_date;
        $data['customer_detail'] = $this->Statement->get_customer_detail($account_number);
        $data['account_balance'] = $this->Statement->get_account_balance($account_number);

        // if account_number exist proceed
        if(!empty($data['account_balance'])){
        $data['opening_balance'] = $this->Statement->get_opening_balance($account_number, $start_date);
        $data['transaction_event'] = $this->Statement->get_transaction_event($start_date, $end_date, $account_number);
        $data['transact_history'] = $this->Statement->get_transaction_history($start_date, $end_date, $account_number);
        $data['total_transaction'] = $this->Statement->get_total_transaction($start_date, $end_date, $account_number);
        
        $data['statement'] = $this->Statement->get_statement($start_date, $end_date, $account_number);
        $transaction_histories =  $data['transact_history'];
        $transaction_event = $data['transaction_event'];
        $opening_balance = $data['opening_balance'] ;
        $account_balance = $data['account_balance']; 
        $total_transaction = $data['total_transaction'];
        $customer_detail = $data['customer_detail'];
        $transaction_start_date = $data['transaction_start_date'];
        $transaction_end_date = $data['transaction_end_date'];

        if($this->input->post('download_type')=="excel"){
           export_to_excel($transaction_histories, $transaction_event, $opening_balance, $account_balance, $total_transaction,$customer_detail, $account_number, $transaction_start_date, $transaction_end_date);

             
        }
        elseif ($this->input->post('download_type')=="pdf") {
            $this->load->library('pdf');
        // $this->load->view('mypdf', $data);
        $html = $this->load->view('mypdf', $data, true);
        $filename = $account_number.''.date('ymdhis');
        $this->pdf->createPDF($html, $filename, true);

    echo json_encode(['success'=>'<a target="_blank" href='.base_url().'statement/'.$filename.'.pdf'.' download>Download</a>']);
        // echo json_encode(['success'=>'<a target="_blank" href='.base_url().'viewstatement/'.$filename.'>Download</a>']);
        }

         elseif ($this->input->post('download_type')=="raw") {
          

        $transaction_detail = array('AcctBalDet'=>array(
        'Accountnumber' =>  $customer_detail['NCP'],
        'OpeningBalance' => isset($opening_balance['OPENING_BAL']),
        'Currency' =>   $account_balance['CURRENCY'],
        'LienAmount' => "0",
        'ForthePeriodOf' => $this->input->post('start_date') .' To '. $this->input->post('end_date'),
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
foreach($data['transact_history'] as $transact_histories){
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
    foreach($data['transaction_event'] as $transact_events){
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

  echo json_encode(['success'=> $transaction_detail]); 


        }

       

        elseif($this->input->post('download_type')=="emailpdf"){

             $this->load->library('pdf');
                // $this->load->view('mypdf', $data);
                $html = $this->load->view('mypdf', $data, true);
                $filename = $account_number.''.date('ymdhis');
                $this->pdf->createPDF($html, $filename, true);


                $hh=header('Content-type: application/json');
                $curl = curl_init();
                curl_setopt_array($curl, array(
                // CURLOPT_URL => 'https://skorebot.com/alertsender/send_alert',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('email' =>  $customer_detail['Email'],
                                        'account_number' => $account_number,
                                        'account_name' => $customer_detail['NOMREST'],
                                        'attachment_link'  => base_url().'statement/'.$filename,
                                        'statement_period' => $transaction_start_date.'-'.$transaction_end_date
                                         ))
                );
                curl_setopt($curl, CURLOPT_HEADER, $hh);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($curl);

                curl_close($curl);
                // echo $response;
        echo json_encode(['success'=>$response]);
     }
 }
     // if account number does not exist
     else{
        echo json_encode(['error'=>'<div class="alert text-danger">Account number does not exist</div>']);

     }
 }

    }
}

    }


    function download_statement($filename){
    $this->load->helper('file');
    $filename = base_url().'statement/'.$filename;
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="tajstatement.pdf"'); 
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
      
    // Read the file
    @readfile($filename);
    //
    }


    function api_statement(){
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost:8020/statements/statements/get_statements',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('start_date' => '02-OCT-2021','end_date' => '31-OCT-2021','account_number' => '0000023794'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
    }




function export_statement_excel(){
    // require 'vendor/autoload.php';

    // use PhpOffice\PhpSpreadsheet\Spreadsheet;
    // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // $spreadsheet = new Spreadsheet();
    // $sheet = $spreadsheet->getActiveSheet();
    // $sheet->setCellValue('A1', 'Hello World !');

    // $writer = new Xlsx($spreadsheet);
    // $writer->save('hello world.xlsx');
}
}
?>

