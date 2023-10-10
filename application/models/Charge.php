<?php

class  Charge extends CI_Model{


// $query_visitor = $this->db->query("CALL list_visitor($company_id)");
        // mysqli_next_result( $this->db->conn_id );
        // return $query_visitor->result();

function get_statement_fn($start_date, $end_date, $account_number){
	// return $this->db->get_where(''ALL_TABLES', array(table_name => 'MANTIS_BUG_TABLE')')->result_array();
 	// $statement_query = $this->db->query("CALL TAJ_STATMENT_FN",($start_date, $end_date, $account_number));
 	$statement_query = $this->db->query("CALL TAJ_STATMENT_FN(?,?,?)",array('start_date'=>$start_date,'end_date'=> $end_date, 'account_number'=>$account_number));
 	// oci_parse($this->db->conn_id)
 	// $this->db->conn_id;
	return $statement_query->result();
}



function get_account_detail($account_number){
$query_customer_detail = $this->db->query("select a.ncp,a.cli, b.nomrest, a.cpro, a.inti, a.age, a.dou, a.sin, b.tcli
FROM tajprod.bkCOM a join tajprod.bkcli b on a.cli = b.cli left join tajprod.bkemacli m on a.cli = m.cli  WHERE a.dev= 566 and a.ncp='$account_number'  ");
return $query_customer_detail->row_array();
}


function get_opening_balance($account_number, $start_date){
$query_balance = $this->db->query("select sde opening_bal FROM tajprod.bksld WHERE ncp='$account_number' and dco= to_date('$start_date')-1 and rownum=1 ");
return $query_balance->row_array();
}

function get_account_balance($account_number){
$query_account_balance = $this->db->query("select ncp acctno, (select trim(lib2) from tajprod.bknom where ctab='005' and cacc=dev) currency,sde trans_bal,sin avail_bal, mtfdr lien_amount FROM tajprod.bkcom WHERE ncp='$account_number'");

return $query_account_balance->row_array();
}

function get_total_withdrawal_bkheve($customer_number){
$query_total_transaction = $this->db->query("select  sum(mht1)total_amount, count(ncp1) total_number  from tajprod.bkheve h where dev = 566 and cli1 = '$customer_number' and dsai >= trunc(sysdate,'IW') and  nat = 'RETESP' and ope in ('231','233','227','229') and eta not  in ('AB','IG')  ");
return $query_total_transaction->row_array();
}

function get_total_withdrawal_today($customer_number){
$query_withdrawal_today = $this->db->query("select  sum(mht1) total_amount, count(ncp1) total_number   from tajprod.bkeve where dev = 566 and cli1 = '$customer_number' and dsai >= trunc(sysdate,'IW') and  nat = 'RETESP' and ope in ('231','233','227','229') and eta not  in ('AB','IG')  ");
return $query_withdrawal_today->row_array();
}



function get_last_withdrawal_by_teller_today($account_number, $teller_id){
$last_withdrawal = $this->db->query("select * from (select eve, ncp1, mon1, mht1, uti, uta, utf from tajprod.bkheve  where ncp1 = '$account_number' and uti = '$teller_id' and uta != ' ' and utf='$teller_id' and nat = 'RETESP'    and  eta in ('VA','VF','FO','AT') order by hsai desc) where  rownum <= 1");
return $last_withdrawal->row_array();

// select * from (select eve, ncp1, mon1, uti, uta, utf from tajprod.bkeve  where ncp1 = '$account_number' and nat like '%RETESP%'  and uti = '$teller_id' and uta <> ' ' and utf = '$teller_id' and  eta in ('VA','VF','FO','AT') order by hsai desc) where  rownum <= 1
}

function get_charge_on_last_transation(){
	$last_trasnaction_charge= $this->db->query("SELECT  *  FROM tajprod.bkeve where uti='$teller_id'  (NVL(SUBSTR(eve, 0 , INSTR(eve, '|')-1), eve)) = 193921");
	return $last_trasnaction_charge->row_array();
}



}
?>