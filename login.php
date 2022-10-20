	<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login | Loan Management System</title>
 	

<?php include('./header.php'); ?>
<?php include('./db_connect.php'); ?>
<?php 
session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>
<style>
	body {
	background-color: ghostwhite;
	display: flex;
  	flex-direction: column;
  	background-image: url("assets/img/bg_fcu.jpg");
  	background-repeat: no-repeat;
  	background-size: cover;
 } 
	img{
		width: 70px;
		height: 70px;
		border-radius: 50px;
	}
	.header{
		color: white;
		background-color: #00008B;
		font-size: 30px;
		font-weight: 50px;
		padding: 10px 10px;
		font-weight: normal;
}
.card {
		margin: auto;
		float: none;
		margin-bottom: 10px;
		padding: 0px;
		width: auto;
		background-color: rgba(0,0,0,.5);
    color: #fff;
}

.card-header{
	background-color: #00008B;
	color: white;
	width: 100%;
	font-size: 20px;
	font-weight: bold;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
}
.container-fluid{
	padding: 100px 100px;
}

</style>
</head>
<body>
	<div class="header">
		<img src="assets/img/fema_logo.png">
		<span>FCU EMPLOYEE MUTUAL ASSOCIATION</span>
	</div>
	<div class="container-fluid">
		<div class="col-lg-12">
			<div class="card col-md-5 col-md-offset-7">
				<div class="card-header">
					LOG IN
				</div>
				<div class="card-body">
					<form id="login-form">
						<div class="form-group">
							<label for="username" class="control-label">Username:</label>
							<input type="text" id="username" name="username" class="form-control">
						</div>
						<div class="form-group">
							<label for="password" class="control-label">Password:</label>
							<input type="password" id="password" name="password" class="form-control">
						</div>
						<center><button class="btn btn-primary col-md-4">Login</button></center>
					</form>
				</div>
			</div>
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
					location.href ='index.php?page=home';
				}else if(resp == 2){
					location.href ='voting.php';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>