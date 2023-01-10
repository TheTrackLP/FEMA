
<style>
	img {
  display: block;
  max-width:200px;
  max-height:150px;
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
.dropdown-menu{
	color: white;
	border: none;
	width: 230px;
}
a.dropdown-item:hover{
    background-color: yellow;
    color: black;
    border-radius: 50px;
    font-weight: bold;
}
a.nav-for:hover{
	background-color: yellow;
}

a.nav-approved:hover{
	background-color: lightblue;
}

a.nav-release:hover{
	background-color: lightskyblue;
}

a.nav-complete:hover{
	background-color: lightgreen;
}

a.nav-denied:hover{
	background-color: red;
}

</style>
<nav id="sidebar" class='mx-lt-5' >
		
		<div class="sidebar-list">
				<img src="assets/img/filamer.png">
				<a href="ajax.php?action=logout" class="btn-out"><?php echo $_SESSION['login_name'] ?> <i class="fa fa-power-off"></i></a>
				<?php if($_SESSION['login_position'] == "Admin"){ ?>
				<a href="index.php?page=borrowers" class="nav-item nav-borrowers"><span class='icon-field'><i class="fa fa-bars"></i></span> Borrowers</a>
				<div class="dropdown show">
					<a class="nav-item dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loans</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a href="index.php?page=loans" class="nav-item nav-loans">Show All Status</a>
						<a class="dropdown-item nav-item nav-for" href="index.php?page=status_approval"><span><span class='icon-field'><i class="fa fa-globe"></i></span> For Approval</a>
						<a class="dropdown-item nav-item nav-approved" href="index.php?page=status_approved"><span><span class='icon-field'><i class="fa fa-pause"></i></span> Approved</a>
						<a class="dropdown-item nav-item nav-release" href="index.php?page=status_released"><span><span class='icon-field'><i class="fa fa-thumbs-up"></i></span> Released</a>
						<a class="dropdown-item nav-item nav-complete" href="index.php?page=status_complete"><span><span class='icon-field'><i class="fa fa-list"></i></span> Complete</a>
						<a class="dropdown-item nav-item nav-denied" href="index.php?page=status_denied"><span><span class='icon-field'><i class="fa fa-times"></i></span> Denied</a>
					</div>
				</div>
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				<a href="index.php?page=plan" class="nav-item nav-plan"><span class='icon-field'><i class="fa fa-list-alt"></i></span> Loan Plans</a>
				<a href="index.php?page=reports" class="nav-item nav-reports"><span class="icon-field"><i class="fa fa-list-ul"></i></span> Reports </a>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>

			<?php } elseif($_SESSION['login_position'] == "Cashier"){ ?>
				<div class="dropdown show">
					<a class="nav-item dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loans</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item nav-item nav-release" href="index.php?page=status_released"><span><span class='icon-field'><i class="fa fa-thumbs-up"></i></span> Released</a>	
					</div>
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>

			<?php } elseif($_SESSION['login_position'] == "Posting_clerk"){ ?>
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				
			<?php } elseif($_SESSION['login_position'] == "Bookkeeper"){ ?>
				<a href="index.php?page=borrowers" class="nav-item nav-borrowers"><span class='icon-field'><i class="fa fa-bars"></i></span> Borrowers</a>
				<div class="dropdown show">
					<a class="nav-item dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loans</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a href="index.php?page=loans" class="nav-item nav-loans">Show All Status</a>
						<a class="dropdown-item nav-item nav-for" href="index.php?page=status_approval"><span><span class='icon-field'><i class="fa fa-globe"></i></span> For Approval</a>
						<a class="dropdown-item nav-item nav-approved" href="index.php?page=status_approved"><span><span class='icon-field'><i class="fa fa-pause"></i></span> Approved</a>
						<a class="dropdown-item nav-item nav-complete" href="index.php?page=status_complete"><span><span class='icon-field'><i class="fa fa-list"></i></span> Complete</a>
						<a class="dropdown-item nav-item nav-denied" href="index.php?page=status_denied"><span><span class='icon-field'><i class="fa fa-times"></i></span> Denied</a>
					</div>
				</div>
				<a href="index.php?page=plan" class="nav-item nav-plan"><span class='icon-field'><i class="fa fa-list-alt"></i></span> Loan Plans</a>
				<a href="index.php?page=reports" class="nav-item nav-reports"><span class="icon-field"><i class="fa fa-list-ul"></i></span> Reports </a>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
			<?php } else{ ?>
			<?php }?>
		</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
