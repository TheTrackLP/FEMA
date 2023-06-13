<?php
ob_start();
include('db_connect.php');
$rand = rand(1,99999999);

?>
<div class="container-fluid">
	<div class="col-lg-12">
	<form action="user_loan.php" method="post">
		<div class="row">
			<div class="col-md-10">
				<label class="control-label">Borrower</label>
				<input type="text" name="name" class="form-control" value="<?php echo isset($name) ? $name : ''?>" readonly>
			</div>		
		</div>
		<div class="row">
			<div class="col-md-6">
				<label>Shared Capital</label>
				<input type="number" name="shared_capital" placeholder="Enter Borrowers Capital" class="form-control" value="<?php echo isset($shared_capital) ? $shared_capital : ''?>">
			</div>
			<div class="col-md-6">
				<label>Years of Service</label>
				<?php if(isset($yservice)):?> 
				<select name="yservice" class="custom-select browser-default select2" disabled>
					<option value="" disabled selected></option>
					<option value="1"<?php echo $yservice == 1 ? "selected" : '' ?>>1-4 Years</option>
					<option value="2"<?php echo $yservice == 2 ? "selected" : '' ?>>5-9 Years</option>
					<option value="3"<?php echo $yservice == 3 ? "selected" : '' ?>>10 Years & Above</option>
				</select>
				<?php else:?>
					<select name="yservice" class="custom-select browser-default select2" disabled>
					<option value="" disabled selected></option>
					<option value="1">1-4 Years</option>
					<option value="2">5-9 Years</option>
					<option value="3">10 Years & Above</option>
				</select>
				<?php endif;?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<label class="control-label">Loan Plan</label>
				<?php
				$plan = $conn->query("SELECT * FROM loan_plan order by `plan_loan` asc ");
				?>
				<select name="plan_id" id="plan_id" class="custom-select browser-default select2" disabled>
					<option value=""></option>
						<?php while($row = $plan->fetch_assoc()): ?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($plan_id) && $plan_id == $row['id'] ? "selected" : '' ?> data-plan_loan="<?php echo $row['plan_loan'] ?>" data-interest_percentage="<?php echo $row['interest_percentage'] ?>" data-penalty_rate="<?php echo $row['penalty_rate'] ?>"><?php echo $row['plan_loan'] . ' [ '.$row['interest_percentage'].'%, '.$row['penalty_rate'].'% ]' ?></option>
						<?php endwhile; ?>
				</select>
				<small>Plan [ interest%,penalty% ]</small>
			</div>
		<div class="form-group col-md-6">
			<label class="control-label">Loan Amount</label>
			<input type="number" name="amount" class="form-control text-right" step="any" id="" value="<?php echo isset($amount) ? $amount : '' ?>" disabled>
		</div>
	</div>
		<div class="row">
			<div class="form-group col-md-6">
			<label class="control-label">Purpose</label>
			<textarea name="purpose" id="purpose" cols="30" rows="2" class="form-control" disabled><?php echo isset($purpose) ? $purpose : '' ?></textarea>
			<input type="hidden" id="id" name="id" value="<?php echo $id ?>">
			<input type="hidden" name="random" value="<?php echo $rand ?>">
			</div>
		</div>
		<div id="row-field">
			<div class="row ">
				<div class="col-md-12 text-center">
					<button class="btn btn-primary btn-sm" id="submit" name="submit">Save</button>
					<button class="btn btn-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
					<a class="btn btn-primary btn-sm" href="javascript:toggleFormElements(false);">Edit</a>
				</div>
			</div>
		</div>
		
	</form>
	</div>
</div>
<script>
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})

	function toggleFormElements(bDisabled) {
		var textarea = document.getElementsByTagName("textarea");
		for (var i = 0; i < textarea.length; i++) {
			textarea[i].disabled = bDisabled; 
		}
		var select = document.getElementsByTagName("select");
		for (var i = 0; i < select.length; i ++){
			select[i].disabled = bDisabled;
		}
		var input = document.getElementsByTagName("input");
		for (var i = 0; i < input.length; i ++){
			input[i].disabled = bDisabled;
		}
		var button = document.getElementsByTagName("button");
		for (var i = 0; i < button.length; i ++){
			button[i].disabled = bDisabled;
		}
		var select = document.getElementsByTagName("select");
		for (var i = 0; i = select.length; i ++){
			select[i].disabled = bDisabled;
		}
	}
</script>
<style>
	#uni_modal .modal-footer{
		display: none
	}
</style>
<?php 
	
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $shared_capital = $_POST['shared_capital'];
		$yservice = $_POST['yservice'];
        $plan = $_POST['plan_id'];
        $amount = $_POST['amount'];
        $purpose = $_POST['purpose'];
        $random = $_POST['random'];
        $id = $_POST['id'];


        $qry = mysqli_query($conn, "SELECT * FROM borrowers WHERE stat = 'New'");
        $result = mysqli_fetch_array($qry);
        //if($result>0){
        	//echo "<script>alert('The Admin didn't approve yor membership yet. Please try again later or contact the Admin.');</script>";
        //}
        //else{

        $query=mysqli_query($conn, "INSERT INTO loan_list (`ref_no`, `borrower_id`, `purpose`, `shared_cap`, `yservice`, `amount`, `amount_borrowed`, `plan_id`, `status`, `date_released`, `date_created`) VALUES ('$random', '$id', '$purpose', '$shared_capital', '$yservice', '$amount', '$amount', '$plan', '0', '0000-00-00 00:00:00', current_timestamp())");
		$query2=mysqli_query($conn, "UPDATE borrowers SET `shared_capital` = $shared_capital WHERE id = $id");
        if ($query && $query2) {
        	header('location:user-profile.php');
        }else{
        	echo "<script>alert('Something Went Wrong. Please try again.');</script>";
        }
    }
    //}
	ob_end_flush();
?>