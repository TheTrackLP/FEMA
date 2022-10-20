<?php include 'db_connect.php' ?>
<?php 

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM borrowers where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}

function getSequence($num= 1) {
  return sprintf("%'.01d", $num);
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-borrower">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Last Name</label>
						<input name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">First Name</label>
						<input name="firstname" class="form-control" required="" value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Middle Name</label>
						<input name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename : '' ?>">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
						<label for="">Address</label>
						<textarea name="address" id="" cols="30" rows="2" class="form-control" required><?php echo isset($address) ? $address : '' ?></textarea>
				</div>
				<div class="col-md-4">
					<div class="">
						<label for="">Contact #</label>
						<input type="text" class="form-control" name="contact_no" value="<?php echo isset($contact_no) ? $contact_no : '' ?>" required>
					</div>
				</div>
				<div class="col-md-4">
					<label for="">Years in Service</label>
					<select id="" class="form-control" name="year_service" required>
						<option><?php echo isset($POST_['year_service']) ? $POST_['year_service'] : '' ?>1-4 years</option>
						<option><?php echo isset($POST_['year_service']) ? $POST_['year_service'] : '' ?>5-9 years</option>
						<option><?php echo isset($POST_['year_service']) ? $POST_['year_service'] : '' ?>10 and above years</option>
					</select>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
						<label for="">Email</label>
						<input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>" required>
				</div>
				<div class="col-md-4">
					<div class="">
						<label for="">Employee ID</label><br>
						<input type="text" class="form-control" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '' ?>" required>
					</div>
				</div>
				<div class="col-md-4">
						<label for="">Date</label>
						<input type="text" class="form-control" name="date_created" value="<?php echo date('Y-m-d'), isset($_POST['date_created']) ? $_POST['date_created'] : '' ?>" required>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<label for="">Office/Department</label><br>
					<select class="form-control" name="department">
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Computer Studies</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Nursing</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Business Administration</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Teacher's Education</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Criminal Justice</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Hotel and Tourism Management</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Computer Studies</option>
						<option><?php echo isset($_POST['department']) ? $_POST['department'] : ''?>College of Engineering</option>
					</select>
				</div>
				<div class="col-md-6">
					<label for="">CV #</label><br>
					<input type="text" class="form-control" name="cv_number" value="<?php echo getSequence() ?>">
					
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	 $('#manage-borrower').submit(function(e){
	 	e.preventDefault()
	 	start_load()
	 	$.ajax({
	 		url:'ajax.php?action=save_borrower',
	 		method:'POST',
	 		data:$(this).serialize(),
	 		success:function(resp){
	 			if(resp == 1){
	 				alert_toast("Borrower data successfully saved.","success");
	 				setTimeout(function(e){
	 					location.reload()
	 				},1500)
	 			}
	 		}
	 	})
	 })
</script>