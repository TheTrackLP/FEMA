<?php 
include 'admin_class.php';
extract($_POST);
$payable = 500;
$total = $amount;
$monthly = $amount + ($amount * ($interest/100));
$penalty = $total * ($penalty/100);

?>
<hr>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-4">
				<label class="text-center">Total Borrowed Amount</label>
				<input type="number" name="total" value="<?php echo $total, isset($_POST['total']) ? $_POST['total'] : ''?>" disabled>
			</div>
			<div class="col-md-4">
				<label class="text-center">Total Payable amount</label>
				<input type="number" name="total" value="<?php echo $monthly, isset($_POST['total']) ? $_POST['total'] : ''?>" >
			</div>
			<div class="col-md-4">
				<label class="text-center">Penalty Expected Amount</label>
				<input type="number" name="penaly" value="<?php echo number_format($penalty,2)?>" disabled>
				
			</div>
		</div>
	</div>
</div>
<hr>