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
            $user = $this->input->post('user');

            // echo $_SERVER['REMOTE_ADDR'];
            $api_key = $this->input->post('api_key');
            $data['check_api_access'] = $this->Account->check_api_access($api_key,$_SERVER['SERVER_ADDR']);
            if($data['check_api_access'] == TRUE){
                $log_request_data = array('app_id' => $data['check_api_access']['id'],
                                    'user_id' => $user,
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
        $data['pdf_data'] = $this->pdf->createPDF($html, $filename, true);


        $statement_file_data = array('no_of_pages' => $data['pdf_data'],
                                      'amount_to_charge' => $data['pdf_data'] * 5,
                                      'account_to_charge' => $customer_detail['NCP'],
                                      'format' => 'pdf',
                                      'filename'=>$filename.'.pdf',
                                     'download_link' =>'<a target="_blank" class="alert text-success" href='.base_url().'viewstatement/'.$filename.'.pdf'.'>Download</a>' );
        echo json_encode(['success'=>$statement_file_data]);
        // <a target="_blank" class="alert text-success" href='.base_url().'statement/'.$filename.'.pdf'.' download>Download</a>
        // echo json_encode(['success'=>'<a target="_blank" href='.base_url().'statement/'.$filename.'.pdf'.' download>Download</a>']);
        // echo json_encode(['success'=>'<a target="_blank" href='.base_url().'viewstatement/'.$filename.'>Download</a>']);
        }

         elseif ($this->input->post('download_type')=="raw") {
          
        $transaction_history =  $data['transact_history'];
          $transaction_detail =  export_to_raw($transaction_history, $transaction_event, $opening_balance, $account_balance, $total_transaction,$customer_detail, $account_number, $transaction_start_date, $transaction_end_date);
            echo json_encode(['success'=> $transaction_detail]); 
        }

       

        elseif($this->input->post('download_type')=="emailpdf"){

             $this->load->library('pdf');
                // $this->load->view('mypdf', $data);
                $html = $this->load->view('mypdf', $data, true);
                $filename = $account_number.''.date('ymdhis');
                $data['pdf_data'] = $this->pdf->createPDF($html, $filename, true);
              
// 'email' =>  $customer_detail['Email'],
// 'account_number' => $account_number,
// 'account_name' => $customer_detail['NOMREST'],
// 'attachment_link'  => base_url().'statement/'.$filename,
// 'statement_period' => $transaction_start_date.'-'.$transaction_end_date



                    // PHPMailer object
        $mail = $this->phpmailer_lib->load();
        
        // SMTP configuration
        
        
        $mail->setFrom('tajenterprisesuit@tajbank.com', 'Tajbank Statement');
        $mail->addReplyTo('noreply@tajbank.com', 'Tajbank Statement');
        
        // Add a recipient
        $mail->addAddress($customer_detail['EMAIL']);
       
        $mail->Subject = 'Your statement has arrived-This is a test';
        
        // Set email format to HTML
        $mail->isHTML(true);
        // Email body content
        $mailContent = '<h1>Dear '.$customer_detail['NOMREST'].'</h1>
            <p>Kindly find attached your statement for '.$transaction_start_date.' to '.$transaction_end_date.' .</p>';
        $mail->Body = $mailContent;
        // $mail->Body = $this->load->view('email/statement', $data, true );
        $mail->AddAttachment('statement/'.$filename.'.pdf');
        // Send email
        if(!$mail->send()){
            $response = 'Message could not be sent.';
            $response = $response. 'Mailer Error: ' . $mail->ErrorInfo;
        echo json_encode(['error'=>$response]);
        }else{
           

            $statement_file_data = array('no_of_pages' => $data['pdf_data'],
                                      'amount_to_charge' => $data['pdf_data'] * 5,
                                      'account_to_charge' => $customer_detail['NCP'],
                                      'format' => 'pdf',
                                      'filename'=>$filename.'.pdf',
                                     'download_link' =>'Message has been sent',
                                     'response' => 'Message has been sent' );


            $response = 'Message has been sent';
        echo json_encode(['success'=>$statement_file_data]);
        }
                // echo $response;
        
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
        $filename = base_url().'statement/'.$filename;
        $filename    =   file_get_contents($filename, false,
                             stream_context_create([
                                'ssl'  => [
                                    'verify_peer'      => false,
                                    'verify_peer_name' => false,
                                ]
                            ]));
        force_download($filename, $filename);
    //
    }




    function charge_customer_statement(){

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

