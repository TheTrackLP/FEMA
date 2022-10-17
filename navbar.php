
<style>
	img {
  display: block;
  max-width:200px;
  max-height:100px;
  width: auto;
  height: auto;
  margin-left: auto;
  margin-right: auto;
  border-radius: 50px;
}

a.btn-out{
 background-color: white;	
 color: black;
 display: block;
 text-align: center;
}
nav#sidebar{
	background-color: white;
}
a.nav-item{
	background-color: white;
	border-radius: 50px;
	margin: 5px;
	    transform: translateY(-6px);
}
a.nav-item:hover, .nav-item.active {
    background-color: yellow;
    color: black;
}

</style>
<nav id="sidebar" class='mx-lt-5' >
		
		<div class="sidebar-list">
				<img src="assets/img/filamer.png">
				<a href="ajax.php?action=logout" class="btn-out"><?php echo $_SESSION['login_name'] ?> <i class="fa fa-power-off"></i></a>
				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
				<a href="index.php?page=borrowers" class="nav-item nav-borrowers"><span class='icon-field'><i class="fa fa-bars"></i></span> Borrowers</a>
				<a href="index.php?page=loans" class="nav-item nav-loans"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loans</a>	
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				<a href="index.php?page=plan" class="nav-item nav-plan"><span class='icon-field'><i class="fa fa-list-alt"></i></span> Loan Plans</a>	
				<a href="index.php?page=loan_type" class="nav-item nav-loan_type"><span class='icon-field'><i class="fa fa-th-list"></i></span> Loan Types</a>		
				<a href="#" class="nav-item nav-reports"><span class="icon-field"><i class="fa fa-list-ul"></i></span> Reports </a>
				<?php if($_SESSION['login_type'] == 1): ?>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
				
			<?php endif; ?>
		</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
