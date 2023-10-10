<?php
/**
 * 
 */
class Account extends CI_Model
{
	
	function login_user($username){

	$get_login_data =  $this->load->database('local', TRUE)->get_where('users',  array('username' =>$username));
			return $get_login_data->row_array();
	}

	function check_username($submitted_username){
			$query_check_username =  $this->load->database('local', TRUE)->get_where('users', array('username' =>$submitted_username ));
			return $query_check_username->row_array();
			
	}


		function update_user_password($username, $password){
	// $this->load->database('local', TRUE)->where('username', $username);
	$this->load->database('local', TRUE)->query("update users set password = '$password' where username = '$username' ");
	// return $this->load->database('local', TRUE)->update('users', $password_details);
	}

	function check_api_access($api_key, $ip_address){
		// $check_api_access =  $this->load->database('local', TRUE)->get_where('api_key', array('apikey' =>$api_key, 'ipaddr'=>$ip_address));
		$check_api_access =  $this->load->database('local', TRUE)->get_where('api_key', array('apikey' =>$api_key));
		return $check_api_access->row_array();
	}


	function get_teller($email){
		 $verify_teller = $this->db->query("select cuti, sus, nbacc from tajprod.evuti where  email = '$email'");
		 return $verify_teller->row_array();
	}

	function get_cashposition($teller_id){
		$get_cashposition = $this->db->query("select cuti, eta from tajprod.bkcai where cuti = '$teller_id'");
		return $get_cashposition->row_array();
	}

	function request_log($log_request_data){
		$this->load->database('local', TRUE)->insert('stmt_spool', $log_request_data);
	}

}
?>