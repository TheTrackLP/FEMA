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
			<div class="col-md-6">
				<label class="text-center">Borrowed Amount</label>
				<input type="number" name="amount_borrowed" value="<?php echo $total, isset($_POST['amount_borrowed']) ? $_POST['amount_borrowed'] : ''?>" readonly>
			</div>
			<div class="col-md-6">
				<label class="text-center">Penalty Amount</label>
				<input type="number" name="penaly" value="<?php echo number_format($penalty,2)?>" readonly>
			</div>
		</div>
	</div>
</div>
<hr>