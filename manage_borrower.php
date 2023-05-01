<?php include 'db_connect.php' ?>
<?php 

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM borrowers where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-borrower">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<input type="text" name="date_created" class="form-control" value="<?php echo date('Y-m-d'), isset($_POST['date_created']) ? $_POST['date_created'] : ''?>">

			<header><b>Personal Details</b></header>
			<hr>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Last Name</label>
						<input name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname : '' ?>" disabled>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">First Name</label>
						<input name="firstname" class="form-control" value="<?php echo isset($firstname) ? $firstname : '' ?>" required disabled>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Middle Name</label>
						<input name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename : '' ?>" disabled>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<div class="">
						<label for="">Email</label>
						<input type="text" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>" disabled>
					</div>
				</div>
				<div class="col-md-4">
					<div class="">
						<label>Mobile Number</label>
						<input type="number" name="contact_no" class="form-control" value="<?php echo isset($contact_no) ? $contact_no : ''?>" disabled>
					</div>
				</div>
				<div class="col-md-4">
					<div class="">
						<label>Shared Capital</label>
						<input type="number" name="shared_capital" class="form-control" value="<?php echo isset($shared_capital) ? $shared_capital : ''?>" disabled>
					</div>
				</div>
			</div>
				<div class="row">
					<div class="form-group col-md-6">
						<label>Year of Servic</label>
						<select id="" class="form-control" name="year_service" required disabled>
							<option selected><?php echo isset($year_service) ? $year_service : '' ?></option>
							<option>1-4 years</option>
                            <option>5-9 years</option>
                            <option>10 and above years</option>
                        </select>
                    </div>
                </div>
			<?php if(isset($stat)): ?>
				<div class="row">
					<div class="form-group col-md-6">
						<label class="control-label">Status</label>
						<select class="custom-select browser-default" name="stat">
							<option selected><?php echo isset($stat) ? $stat : ''?></option>
							<option><?php echo isset($_POST['stat']) ? $_POST['stat'] : ''?>New</option>
							<option><?php echo isset($_POST['stat']) ? $_POST['stat'] : ''?>Existing</option>
						<?php endif ?>
					</select>
				</div>
				<div class="offset-md-10 col-md-2">
					<a class="btn btn-primary btn-sm" href="javascript:toggleFormElements(false);">Edit</a>
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
	}
</script>