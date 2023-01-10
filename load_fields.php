<?php include 'db_connect.php' ?>
<?php 
//extract($_POST);

$payable = 500;
if(isset($id)){
	$qry = $conn->query("SELECT * FROM payments where id=".$_POST['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}
$loan = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no, b.address from loan_list l inner join borrowers b on b.id = l.borrower_id where l.id = ".$_POST['loan_id']);
foreach($loan->fetch_array() as $k => $v){
	$meta[$k] = $v;
}
$plan = $conn->query("SELECT *,concat(plan_loan, '[',interest_percentage,'%, ',penalty_rate,'] ') as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
while($row=$plan->fetch_assoc()){
	$plan_arr[$row['id']] = $row;
}
$qry = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no, b.address from loan_list l inner join borrowers b on b.id = l.borrower_id  order by id asc");

$plan_arr = $conn->query("SELECT *,concat(plan_loan,'[ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id  = '".$meta['plan_id']."' ")->fetch_array();
$monthly = ($meta['amount'] + ($meta['amount'] * ($plan_arr['interest_percentage']/100)));
$penalty = $monthly * ($plan_arr['penalty_rate']/100);
$payments = $conn->query("SELECT * from payments where loan_id =".$_POST['loan_id']);
$paid = $payments->num_rows;
$offset = $paid > 0 ? " offset $paid ": "";
?>
<div class="col-lg-12">
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label for="">Borrower</label>
			<input name="borrower" class="form-control" required="" value="<?php echo isset($borrower) ? $borrower : (isset($meta['name']) ? $meta['name'] : '') ?>">
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label for="">Loan Plan</label>
			<input name="loan_plan" class="form-control" required="" value="<?php echo isset($loan_plan) ? $loan_plan : (isset($plan_arr['plan_loan']) ? $plan_arr['plan_loan'] : '') ?>">
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label for="">CV #:</label>
			<input name="borrower_id" class="form-control" required="" value="<?php echo isset($borrower_id) ? $borrower_id : (isset($meta['borrower_id']) ? $meta['borrower_id'] : '') ?>">
		</div>
	</div>
	
</div>
<hr>
<div class="row">
	<div class="col-md-5">
		<p><small>Total amount:<b><?php echo number_format($monthly,2) ?></b></small></p>
		<p><small>Penalty :<b><?php echo $add = (date('Ymd',strtotime(isset($row['date_released']) . '+15 days')) < date("Ymd") ) ?  $penalty : 0; ?></b></small></p>
		<p><small>Payable Minimum Amount :<b><?php echo number_format($payable,2) ?></b></small></p>
	</div>
	<div class="col-md-5">
		<div class="form-group">
			<label for="">Principal</label>
			<input type="number" name="amount" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($amount) ? $amount : '' ?>">
			<label for="">Interest</label>
			<input type="number" name="interest" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($interest) ? $interest : '' ?>">
			<input type="hidden" name="penalty_amount" value="<?php echo $add ?>">
			<input type="hidden" name="loan_id" value="<?php echo $_POST['loan_id'] ?>">
			<input type="hidden" name="overdue" value="<?php echo $add > 0 ? 1 : 0 ?>">
		</div>
	</div>
</div>
</div>