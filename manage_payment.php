<?php include 'db_connect.php' ?>
<?php 

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM payments where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-payment">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">
				<div class="col-md-9">
					<div class="form-group">
						<label for="" class="control-label">Name. | Plan.</label>
						<?php
						$loan = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name from loan_list l inner join borrowers b on b.id = l.borrower_id  WHERE status=2 && 3");
						$plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
						while($row=$plan->fetch_assoc()){
							$plan_arr[$row['id']] = $row;
						}
						?>

						<select name="loan_id" id="loan_id" class="custom-select browser-default select2">
							<option value=""></option>
							<?php 
							while($row=$loan->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($loan_id) && $loan_id == $row['id'] ? "selected" : '' ?>><?php echo $row['name'].', '.$plan_arr[$row['plan_id']]['plan'] ?></option>
							<?php endwhile; ?>
						</select>
						
					</div>
				</div>
			</div>
			<div class="row" id="fields">
				
			</div>
		</form>
	</div>
</div>

<script>
	$('[name="loan_id"]').change(function(){
		load_fields()
	})
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})

	function load_fields(){
		start_load()
		$.ajax({
			url:'load_fields.php',
			method:"POST",
			data:{id:'<?php echo isset($id) ? $id : "" ?>',loan_id:$('[name="loan_id"]').val()},
			success:function(resp){
				if(resp){
					$('#fields').html(resp)
					end_load()
				}
			}
		})
	}
	$('#manage-payment').submit(function(e){
	  	e.preventDefault()
	  	start_load()
	  	$.ajax({
	  		url:'ajax.php?action=save_payment',
	  		method:'POST',
	  		data:$(this).serialize(),
	  		success:function(resp){
	  			if(resp == 1){
	  				alert_toast("Payment data successfully saved.","success");
	  				setTimeout(function(e){
	  					location.reload()
	  				},1500)
	  			}
	  		}
	  	})
	 })
	 $(document).ready(function(){
	 	if('<?php echo isset($_GET['id']) ?>' == 1)
		load_fields()
	 })
</script>