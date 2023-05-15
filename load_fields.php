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
$loan = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no from loan_list l inner join borrowers b on b.id = l.borrower_id where l.id = ".$_POST['loan_id']);
foreach($loan->fetch_array() as $k => $v){
	$meta[$k] = $v;
}
$plan = $conn->query("SELECT *,concat(plan_loan, '[',interest_percentage,'%, ',penalty_rate,'] ') as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
while($row=$plan->fetch_assoc()){
	$plan_arr[$row['id']] = $row;
}
$planloan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
while($row=$planloan->fetch_assoc()){
	$planloan_arr[$row['id']] = $row;
}

$plan_arr = $conn->query("SELECT *,concat(plan_loan,'[ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id  = '".$meta['plan_id']."' ")->fetch_array();
$monthly = ($meta['amount'] + ($meta['amount'] * ($plan_arr['interest_percentage']/100)));
$penalty = $monthly * ($plan_arr['penalty_rate']/100);
$payments = $conn->query("SELECT * from payments where loan_id =".$_POST['loan_id']);

$ppaid = $payments->num_rows;
$offset = $ppaid > 0 ? " offset $ppaid ": "";
?>

<div class="col-lg-12">
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label for="">Borrower</label>
			<input name="borrower" class="form-control" required="" value="<?php echo isset($borrower) ? $borrower : (isset($meta['name']) ? $meta['name'] : '') ?>" readonly>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="">Loan Plan</label>
			<input name="loan_plan" class="form-control" required="" value="<?php echo isset($loan_plan) ? $loan_plan : (isset($plan_arr['plan_loan']) ? $plan_arr['plan_loan'] : '') ?>" readonly>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="">CV #:</label>
			<input name="borrower_id" class="form-control" required="" value="<?php echo isset($borrower_id) ? $borrower_id : (isset($meta['borrower_id']) ? $meta['borrower_id'] : '') ?>" readonly>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<label>Remaining Balance:</label>
		<input name="balance" class="form-control" required="" value="<?php echo isset($balance) ? $balance : (isset($meta['amount']) ? $meta['amount'] : '') ?>" readonly>
	</div>
	<div class="col-md-4">
		<label>Capital Share:</label>
		<input name="capital" class="form-control" required="" value="<?php echo isset($capital) ? $capital : (isset($meta['shared_cap']) ? $meta['shared_cap'] : '') ?>" readonly>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-5">
		<div class="form-group">
			<label for="">Principal</label>
			<input type="number" name="paid" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($paid) ? $paid : 0 ?>">
			<label for="">Interest</label>
			<input type="number" name="interest" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($interest) ? $interest : 0 ?>">
			<label for="">Paid-in Capital</label>
			<input type="number" name="capital" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($interest) ? $interest : 0 ?>">
			<input type="hidden" name="penalty_amount" value="<?php echo $add ?>">
			<input type="hidden" name="loan_id" value="<?php echo $_POST['loan_id'] ?>">
			<input type="hidden" name="overdue" value="<?php echo $add > 0 ? 1 : 0 ?>">
		</div>
	</div>
	<div class="col-md-5">
		<div class="form-group">
		<label for="">Amount to be Paid</label>
		<label for=""></label>

		</div>
	</div>
</div>
