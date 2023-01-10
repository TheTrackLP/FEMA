<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>FEMA</title>
	
<?php include('./header.php'); ?>
<?php include('./db_connect.php'); ?>
<?php 
session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=borrowers");

?>

<style>
	body, html{
		height: 100%;
		margin: 0;
		padding: 0;
		float: left;
	}


</style>

</head>
<body>
	<div class="login_main">
		<div class="login_navbar">
			<div class="login_icon"> 
				<img class="li_logo" src="assets/img/filamer.png">
				<h2 class="li_fema">FEMA</h2>		
			</div>			
		</div>

		<div class="login_content">
			<h1><br>FCU EMPLOYEE <br><span>MUTUAL</span><br> ASSOCIATION</h1>

			<form id="login-form">
				<div class="login_form">
					<h2>LOG IN HERE</h2>
					<input type="text" id="username" name="username" placeholder="Enter Email Here" class="form-control">
					<input type="password" id="password" name="password" placeholder="Enter Password Here" class="form-control">
					<button class="btn_login"><a href="#">Login</button>	
				</div>
			</form>

		</div>

	</div>
	 <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=borrowers';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>
</html>