
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
 font-size: 20px;
}
.dropdown-menu{
	color: white;
	border: none;
	width: 230px;
}
a.dropdown-item:hover{
    background-color: #0081A7;
    color: black;
    border-radius: 50px;
    font-weight: bold;
}
.button-hover:hover, .button-hover.active{
	background-color: #0081A7 !important;
    color: white !important;
    border-radius: 50px;
    font-weight: bold;
}


</style>
<nav id="sidebar" class='mx-lt-5' >	
		<div class="sidebar-list" style="background-image: linear-gradient(to bottom right, #00AFB9, #FED9B7)">
				<img class="rounded-circle py-1 mb-3" src="assets/img/filamer.png">
				<a href="ajax.php?action=logout" class="btn-out py-1 bg-transparent button-hover"><?php echo $_SESSION['login_name'] ?> <div class="badge badge-danger"><i class="fa fa-power-off"></i></div></a>
				<hr>
				<?php if($_SESSION['login_position'] == "Admin"){ ?>
				<div class="dropdown show">
					<a href="index.php?page=home" class="nav-item nav-home button-hover"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
					<a class="nav-item dropdown-toggle button-hover" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class='icon-field'><i class="fa fa-bars"></i></span> Borrowers</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a href="index.php?page=borrowers" class="nav-item nav-borrowers"><span class='icon-field'><i class="fa fa-bars"></i></span>New/Exist</a>
						<a class="dropdown-item nav-item nav-for" href="index.php?page=borrowers_new"><span><span class='icon-field'><i class="fa fa-plus"></i></span>New</a>
						<a class="dropdown-item nav-item nav-for" href="index.php?page=borrowers_exist"><span><span class='icon-field'><i class="fa fa-globe"></i></span>Existing</a>
					</div>
				</div>
				<a href="index.php?page=loans" class="nav-item nav-loans button-hover"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loan</a>
				<a href="index.php?page=payments" class="nav-item nav-payments button-hover"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				<a href="index.php?page=plan" class="nav-item nav-plan button-hover"><span class='icon-field'><i class="fa fa-list-alt"></i></span> Loan Plans</a>
				<a href="index.php?page=reports" class="nav-item nav-reports button-hover"><span class="icon-field"><i class="fa fa-list-ul"></i></span> Reports </a>
				<a href="index.php?page=staffs_members" class="nav-item nav-users button-hover"><span class='icon-field'><i class="fa fa-users"></i></span> Staffs/Members</a>
				<a href="index.php?page=department" class="nav-item nav-department button-hover"><span class='icon-field'><i class="fa fa-building"></i></span> Departments</a>



			<?php } elseif($_SESSION['login_position'] == "Cashier"){ ?>
				<a href="index.php?page=loans" class="nav-item nav-loans"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loan</a>
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>

			<?php } elseif($_SESSION['login_position'] == "Posting_clerk"){ ?>
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				
			<?php } elseif($_SESSION['login_position'] == "Bookkeeper"){ ?>
				<a href="index.php?page=loans" class="nav-item nav-loans"><span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span> Loan</a>
				<a href="index.php?page=payments" class="nav-item nav-payments"><span class='icon-field'><i class="fa fa-money-bill"></i></span> Payments</a>
				<a href="index.php?page=plan" class="nav-item nav-plan"><span class='icon-field'><i class="fa fa-list-alt"></i></span> Loan Plans</a>
				<a href="index.php?page=reports" class="nav-item nav-reports"><span class="icon-field"><i class="fa fa-list-ul"></i></span> Reports </a>
				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
			<?php } else{ ?>
			<?php }?>
		</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>

