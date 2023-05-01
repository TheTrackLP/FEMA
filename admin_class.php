<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function user_login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM borrowers where email = '".$username."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['user_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login_admin.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login_member.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '".md5($password, 'key')."' ";
		$data .= ", position = '$position' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}

	function save_member(){
		extract($_POST);
		$data = " email = '$email' ";
		$data .= ", password = '$password' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO borrowers set ".$data);
		}else{
			$save = $this->db->query("UPDATE borrowers set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function save_plan(){
		extract($_POST);
		$data = " plan_loan = '$plan_loan' ";
		$data .= ", interest_percentage = '$interest_percentage' ";
		$data .= ", penalty_rate = '$penalty_rate' ";
		$data .= ", description = '$description' ";
		
		if(empty($id)){
			$save = $this->db->query("INSERT INTO loan_plan set ".$data);
		}else{
			$save = $this->db->query("UPDATE loan_plan set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_plan(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_plan where id = ".$id);
		if($delete)
			return 1;
	}
	function save_department(){
		extract($_POST);
		$data = " department = '$department' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO departments set ".$data);
		}else{
			$save = $this->db->query("UPDATE departments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_department(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM departments where id = ".$id);
		if($delete)
			return 1;
	}
	function save_borrower(){
		extract($_POST);
		$data = " lastname = '$lastname' ";
		$data .= ", firstname = '$firstname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", contact_no = '$contact_no' ";
		$data .= ", email = '$email' ";
		$data .= ", shared_capital = '$shared_capital' ";
		$data .= ", stat = '$stat' ";
		
		if(empty($id)){
			$save = $this->db->query("INSERT INTO borrowers set ".$data);
		}else{
			$save = $this->db->query("UPDATE borrowers set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_borrower(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM borrowers where id = ".$id);
		if($delete)
			return 1;
	}
	function save_loan(){
		extract($_POST);
			$data = " borrower_id = $borrower_id ";
			$data .= " , plan_id = '$plan_id' ";
			$data .= " , shared_cap = '$shared_cap' ";
			$data .= " , amount = '$amount' ";
			$data .= " , amount_borrowed = '$amount_borrowed' ";
			$data .= " , purpose = '$purpose' ";
			$minimum = 500;
			$days = 15;
			if(isset($status)){
				$data .= " , status = '$status' ";
				if($status == 2){
					$amount = $this->db->query("SELECT * FROM loan_list where amount_borrowed = $amount_borrowed")->fetch_array();
					$roundoff = $amount['amount_borrowed'] / $minimum;
					for($i= 1; $i <= $roundoff;$i++){
						$date = date("Y-m-d",strtotime(date("Y-m-d")." +".$i*$days." days"));
						$chk = $this->db->query("SELECT * FROM loan_schedules where loan_id = $id and date(date_due) ='$date'  ");
						if($chk->num_rows > 0){
							$ls_id = $chk->fetch_array()['id'];
							$this->db->query("UPDATE loan_schedules set loan_id = $id, date_due ='$date' where id = $ls_id ");
						}else{
							$this->db->query("INSERT INTO loan_schedules set loan_id = $id, date_due ='$date' ");
							$ls_id = $this->db->insert_id;
						}
						$sid[] = $ls_id;
					}
					$sid = implode(",",$sid);
					$this->db->query("DELETE FROM loan_schedules where loan_id = $id and id not in ($sid) ");
					$data .= " , date_released = '".date("Y-m-d H:i")."' ";
				}else{
					$chk = $this->db->query("SELECT * FROM loan_schedules where loan_id = $id")->num_rows;
					if($chk > 0){
						$this->db->query("DELETE FROM loan_schedules where loan_id = $id ");
					}

				}
			}
			if(empty($id)){
				$ref_no = mt_rand(1,99999999);
				$i= 1;

				while($i== 1){
					$check = $this->db->query("SELECT * FROM loan_list where ref_no ='$ref_no' ")->num_rows;
					if($check > 0){
					$ref_no = mt_rand(1,99999999);
					}else{
						$i = 0;
					}
				}
				$data .= " , ref_no = '$ref_no' ";
			}
			if(empty($id))
			$save = $this->db->query("INSERT INTO loan_list set ".$data);
			else
			$save = $this->db->query("UPDATE loan_list set ".$data." where id=".$id);
		if($save)
			return 1;
	}

	function delete_loan(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_payment(){
		extract($_POST);
			$data = " loan_id = $loan_id ";
			$data .= " , borrower_id = '$borrower_id' ";
			$data .= " , loan_plan = '$loan_plan' ";
			$data .= " , paid = '$paid' ";
			$data .= " , interest = '$interest' ";
			$data .= " , capital = '$capital' ";
			$data .= " , penalty_amount = '$penalty_amount' ";
			$data .= " , overdue = '$overdue' ";

			$addshare = $this->db->query("SELECT * FROM borrowers where id = $borrower_id")->fetch_array();
			if(isset($addshare['shared_capital'])){
				$add = $addshare['shared_capital'] + $capital;
				$save = $this->db->query("UPDATE borrowers set shared_capital =".$add." where id = ".$borrower_id);
			}

			$balance = $this->db->query("SELECT * FROM loan_list where id = $loan_id")->fetch_array();
			if(isset($balance['amount'])){
				$remain = $balance['amount'] - $paid;
				$add_cap = $balance['shared_cap'] + $capital;
				$save = $this->db->query("UPDATE loan_list set shared_cap = ".$add_cap." where id = ".$loan_id);
			 	$save = $this->db->query("UPDATE loan_list set amount = ".$remain." where id = ".$loan_id);
			}
			$completed = $this->db->query("SELECT * FROM loan_list where id = $loan_id")->fetch_array();
			if(empty($completed['amount'])){
				$fully = 3;
				$save = $this->db->query("UPDATE loan_list set status = ".$fully." where id = ".$loan_id);
			}
			$thisWeek = $this->db->query("SELECT id, paid_month FROM loan_schedules where id = $loan_id")->fetch_array();
			if(empty($thisWeek['paid_month'])){
				$done = 1;
				$save = $this->db->query("UPDATE loan_schedules set paid_month = ".$done." where id = ".$loan_id);
			}

		if(empty($id)){
			$save = $this->db->query("INSERT INTO payments set ".$data);
		}else{
			$save = $this->db->query("UPDATE payments set ".$data." where id = ".$id);
		}
		if($save)
			return 1;
	}

	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete)
			return 1;
	}

}
