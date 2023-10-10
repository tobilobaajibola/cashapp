<?php
/**
 * 
 */
class Accounts extends CI_Controller
{

	function reset_password(){
		$password = password_hash('google123', PASSWORD_BCRYPT);
		echo $password;
        // $password_details  = array('password' => $password);
        $username = 'taj00';
	// 	$this->Account->update_user_password($username, $password);
	// unset($_SESSION['cashapp_account']);
	// redirect(base_url().'login', 'refresh'); 

	}
	
	function Login()
	{
		$data =array();
		if(isset($_SESSION['cashapp_account'])){
		$username= $_SESSION['cashapp_account']['username'];
		// $data['user_data'] = $this->Account->login_user($username);
			redirect(base_url(), 'refresh'); 
		}
		else{
	 // $data['page_layout']='login';    
    $this->load->view('login', $data);
    }     
	}

	function Login_account(){
			
		if(isset($_SESSION['cashapp_account'])){
		$username= $_SESSION['cashapp_account']['username'];
		// $data['user_data'] = $this->Account->login_user($username);
			redirect(base_url().'home', 'refresh'); 
		}
		
		elseif(isset($_REQUEST['statement_login'])){

	 	$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		if ($this->form_validation->run() == FALSE) {
         $data['response_msg']=validation_errors();
			echo json_encode(['error' => '<div class=" alert-text text-danger   mb-30"><strong>'.$data['response_msg'].' </strong> </div>']);

		}

		else{
			if($_POST['login_type']=='ad'){
			// decrypt password
	  	$this->load->library('Encryption');
	   	$nonceValue = 'nonce_value';
	    $password = $_REQUEST['password'];
	    $Encryption = new Encryption();
		$passwordDecrypted = $Encryption->decrypted($password, $nonceValue);

		// echo $passwordDecrypted;

			// validate account with AD
			// $hh=header('Content-type: application/json');
			// $data['verify_username']=$this->Account->check_username($_POST['username']);
			
			// if ($data['verify_username']==TRUE) {
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://172.19.1.71:7170/api/account/signinext',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			   CURLOPT_POSTFIELDS =>'{"username":"'.$this->input->post('username').'",
							"password":"'.$passwordDecrypted.'","applicationId":"233864dc-ee66-41ea-9227-75d31e9eb8b9"}',
			  CURLOPT_HTTPHEADER => array(
			    'Content-Type: application/json'
			  ),	));
			// curl_setopt($curl, CURLOPT_HEADER, $hh);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

			$response = curl_exec($curl);
			curl_close($curl);
			
			$response = json_decode($response, true);

			if ($response['status']==200) {

			// start validation on core banking
			// get tller id
			$data['teller_detail'] = $this->Account->get_teller($response['data']['email']);
			if(!empty($data['teller_detail']['CUTI'])){

			$data['cash_position'] = $this->Account->get_cashposition($data['teller_detail']['CUTI']);
			// verify if user is a teller
			// if(empty($data['cash_position']['CUTI'])){
			
			if($data['teller_detail']['SUS']=='N' and $data['teller_detail']['NBACC'] >=3){
			echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>User account locked on CBA</strong></div>']);
				exit();
			}
			// check status of cash postion
			// if($data['cash_position']['ETA'] != 'FE'){
 			// hook the session to login data
			$login_data =  array('username' =>$response['data']['username'], 'teller_id' =>$data['teller_detail']['CUTI'], 'firstname' =>$response['data']['firstName']);

			$_SESSION['cashapp_account'] = $login_data;

			// var_dump($_SESSION['cashapp_account']);
			// isset($_SESSION['cashapp_account']);

			echo json_encode(['success' => '<div class=" alert-text text-success   mb-30" "><strong>Successfully Logged in </strong> </div>']);
					// }
					// else{
					// 	echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>Cash Position not opened</strong></div>']);
					// }
				// }
				// else{
				// 	echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>User is not a teller</strong></div>']);
				// }
					}
				else{
					echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>CBA user not found</strong></div>']);
				}

				}
				else{
				echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>'.$response['message'].'</strong></div>']);

				}
		// }

		// else{
		// 	echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>Access Denied</strong></div>']);

		// }
	}
			elseif ($_POST['login_type']=='local') {
			// check if username submitted exists
			$submitted_username=$this->input->post('username');
			$data['verify_username']=$this->Account->check_username($submitted_username);
			
			if ($data['verify_username']==TRUE) {
			
			$hashed_username=$data['verify_username']['password'];
			$password=password_verify($this->input->post('password'), $hashed_username);	
			    
 			if ($password >= 1){
 				$login_data =  array('username' =>$data['verify_username']['username'], 'name'=>$data['verify_username']['name'] );
 				// hook the session to login data
				$_SESSION['cashapp_account'] = $login_data;
				
				// return true;
				// get user login info
				$username=$data['verify_username']['username'];
				$data['user_data'] = $this->Account->login_user($username);
				// send response message
				// $data['response_msg'] = '<div class="alert alert-mini alert-success mb-30">												
				// 								<strong>Successfully logged in!</strong>
				// 						 </div>';
				// redirect(base_url().'statement', 'refresh');
				echo json_encode(['success' => '<div class=" alert-text text-success   mb-30" "><strong>Successfully Logged in </strong> </div>']);

				
			}
			else{
				// unset($_SESSION['access_cashapp_account']);
				// return false;
				echo json_encode(['error' => '<div class=" alert-text text-danger   mb-30" "><strong>Wrong Credentialss </strong> </div>']);
  
			}
			
		}
		       else{
						echo json_encode(['error' =>'<div class="alert-text text-danger   mb-30""><strong>Wrong Credentials </strong></div>']);
		       }	
			}
			
   }


  }
  
  
	}

	function logout(){
		if(isset($_SESSION['cashapp_account'])){
			unset($_SESSION['cashapp_account']);
			// header("admin");
			echo "logged out";
			redirect(base_url().'login', 'refresh'); 
		}
		else{
			redirect(base_url().'login', 'refresh'); 
			
		}
	}
}
?>