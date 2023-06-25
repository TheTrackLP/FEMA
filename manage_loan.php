<?php 
include('db_connect.php');
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM loan_list where id = ".$_GET['id']);
	foreach($qry->fetch_array() as $k => $v){
		$$k = $v;
	}
}
?>
<style>
	.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 15px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

.container:hover input ~ .checkmark {
  background-color: #ccc;
}

.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.container input:checked ~ .checkmark:after {
  display: block;
}

.container .checkmark:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
</style>
<div class="container-fluid">
	<div class="col-lg-12">
	<form action="" id="loan-application">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
		<div class="row">
			<div class="col-md-12">
				<label class="control-label">Borrower</label>
				<?php
				$borrower = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM borrowers WHERE stat = 'Existing' order by name asc ");
				?>
				<select name="borrower_id" id="borrower_id" class="custom-select browser-default select2" disabled>
					<option value=""></option>
						<?php while($row = $borrower->fetch_assoc()):
						$yos = $row['year_service'];
						if($yos == 1){
							$yos = "1-4 Years";
						}elseif($yos == 2){
							$yos = "5-9 Years";
						}elseif($yos == 3){
							$yos = "10 Years & Above";
							} ?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($borrower_id) && $borrower_id == $row['id'] ? "selected" : '' ?>><?php echo $row['name'] . " |Shared Capital ". $row['shared_capital'] . " | Years of Service " . $yos?></option>
						<?php endwhile; ?>
				</select>
			</div>		
		</div>
		<div class="row">
			<div class="col-md-6">
				<label>Shared Capital</label>
				<input type="number" name="shared_cap" placeholder="Enter Borrowers Capital" class="form-control" value="<?php echo isset($shared_cap) ? $shared_cap : ''?>" disabled>
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
		</div>
		
		<div class="form-group col-md-2 offset-md-2 .justify-content-center">
			<label class="control-label">&nbsp;</label>
			<button class="btn btn-primary btn-sm btn-block align-self-end" type="button" id="calculate" disabled>Calculate</button>
		</div>
		</div>
		<div id="calculation_table">
			
		</div>
		<?php if(isset($status)): ?>
			<?php if($status == 0 || $status == 1 || $status == 3 || $status == 4): ?>
		<div class="row">
			<div class="form-group col-md-9">
				<label class="container">
					<input type="radio" name="status" value="1">Approved
					<span class="checkmark"></span>
				</label>
				<label class="container">
					<input type="radio" name="status" value="2">For Payment	
					<span class="checkmark"></span>
				</label>
				<label class="container">
					<input type="radio" name="status" value="4">Denied		
					<span class="checkmark"></span>
				</label>
			</div>
		<?php endif ?>
		</div>
		<hr>
	<?php endif ?>
		<div id="row-field">
			<div class="row ">
				<div class="col-md-12 text-center">
					<button class="btn btn-primary btn-md float-left" >Save</button>
					<a class="btn btn-primary btn-md float-left" style="margin-left: 10px;" href="javascript:toggleFormElements(false);">Edit</a>
					<button class="btn btn-danger btn-md float-right" type="button" data-dismiss="modal">Cancel</button>
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
	$('#calculate').click(function(){
		calculate()
	})
	

	function calculate(){
		start_load()
		if($('#plan_loan_id').val() == '' && $('[name="amount"]').val() == ''){
			alert_toast("Select plan and enter amount first.","warning");
			return false;
		}
		var plan = $("#plan_id option[value='"+$("#plan_id").val()+"']")
		$.ajax({
			url:"calculation_table.php",
			method:"POST",
			data:{amount:$('[name="amount"]').val(),months:plan.attr('data-plan_loan'),interest:plan.attr('data-interest_percentage'),penalty:plan.attr('data-penalty_rate')},
			success:function(resp){
				if(resp){
					
					$('#calculation_table').html(resp)
					end_load()
				}
			}

		})
	}
	$('#loan-application').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_loan',
			method:"POST",
			data:$(this).serialize(),
			success:function(resp){
				if(resp ==1 ){
					$('.modal').modal('hide')
					alert_toast("Loan Data successfully saved.","success")
					setTimeout(function(){
						location.reload();
					},1500)
				}
			}
		})
	})
	$(document).ready(function(){
		if('<?php echo isset($_GET['id']) ?>' == 1)
			calculate()
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