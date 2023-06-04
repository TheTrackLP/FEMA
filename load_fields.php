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
$loan = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no, b.shared_capital from loan_list l inner join borrowers b on b.id = l.borrower_id where l.id = ".$_POST['loan_id']);
foreach($loan->fetch_array() as $k => $v){
	$meta[$k] = $v;
}
$planloan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
while($row=$planloan->fetch_assoc()){
	$planloan_arr[$row['id']] = $row;
}

$plan_arr = $conn->query("SELECT *,concat(plan_loan,'[ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id  = '".$meta['plan_id']."' ")->fetch_array();
$monthly = ($meta['amount'] + ($meta['amount'] * ($plan_arr['interest_percentage']/100)));
$with_interest = ($meta['amount'] * ($plan_arr['interest_percentage']/100));
$penalty = $monthly * ($plan_arr['penalty_rate']/100);
$payments = $conn->query("SELECT * from payments where loan_id =".$_POST['loan_id']);
$five = 500;
$ppaid = $payments->num_rows;
$offset = $ppaid > 0 ? " offset $ppaid ": "";
$next = $conn->query("SELECT * FROM loan_schedules where loan_id = '".$_POST['loan_id']."'  order by date(date_due) asc limit 1 $offset ")->fetch_assoc()['date_due'];

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
			<input name="loan_plan" class="form-control" required="" value="<?php echo isset($loan_plan) ? $plan_id : (isset($plan_arr['plan_loan']) ? $plan_arr['plan_loan'] : '') ?>" readonly>
			<input type="hidden" name="plan_id" value="<?php echo $meta['plan_id'] ?>">
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
		<input name="capital" class="form-control" required="" value="<?php echo isset($capital) ? $capital : (isset($meta['shared_capital']) ? $meta['shared_capital'] : '') ?>" readonly>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label for="">Principal</label>
			<input type="number" name="paid" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($paid) ? $paid : 0 ?>">
			<label for="">Interest</label>
			<input type="number" name="interest" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($interest) ? $interest : 0 ?>">
		</div>
	</div>
	<div class="cole-md-4">
		<label for="">Paid-in Capital</label>
		<input type="number" name="capital" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($capital) ? $capital : 0 ?>">
		<label for="">Penalty</label>
		<input type="number" name="sampleInterest" step="any" min="" class="form-control text-right" required="" value="<?php echo isset($sampleInterest) ? $sampleInterest : 0 ?>">
		<input type="hidden" name="loan_id" value="<?php echo $_POST['loan_id'] ?>">
		<input type="hidden" name="penalty_amount" value="<?php echo $add ?>">
		<input type="hidden" name="overdue" value="<?php echo $add > 0 ? 1 : 0 ?>">
	</div>
	<div class="col-md-4">
		<?php 
		 $date1 = new DateTime(date("F d, Y" ,strtotime($next)));
		 $fifth = $date1->format('d');
		 if($fifth <= 16){
			 echo "<p>Amount: <b>", number_format($with_interest + $five,2), "</b></p>";
		 }else{
			 echo "<p>Amount: <b>",number_format($five,2),"</b></p>";
		 }
		?>
		<p><small>Penalty :<b><?php echo $add = (date('Ymd',strtotime($next)) < date("Ymd") ) ?  $penalty : 0; ?></b></small></p>
	</div>
</div>
