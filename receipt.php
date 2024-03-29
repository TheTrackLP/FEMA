<?php 
session_start();

include 'db_connect.php';
$fees = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no from loan_list l inner join borrowers b on b.id = l.borrower_id inner join loan_plan c on c.id = l.plan_id  where l.id = {$_GET['loan_id']}");
foreach($fees->fetch_array() as $k => $v){
	$$k= $v;
}
$payments = $conn->query("SELECT * FROM payments where loan_id = $id ");
$pay_arr = array();
while($row=$payments->fetch_array()){
	$pay_arr[$row['id']] = $row;
}
	?>
<style>
    .container-fluid {
        padding-right: 0;
        padding-left: 0;
        margin-right: auto;
        margin-left: auto
    }
 
	.flex{
		display: inline-flex;
		width: 100%;
	}
	.w-50{
		width: 50%;
	}
	.text-center{
		text-align:center;
	}
	.text-right{
		text-align:right;
	}
	table.wborder{
		width: 100%;
		border-collapse: collapse;
	}
	table.wborder>tbody>tr, table.wborder>tbody>tr>td{
		border:1px solid;
	}
	p{
		margin:unset;
		font-weight: bold;
	}

</style>
<div class="container-fluid">
	<p class="text-center">FILAMER EMPLOYEES MTUAL AASOCIATION (FEMA)</p>
	<p class="text-center">FILAMER CHRISTIAN UNIVERSITY</p>
	<p class="text-center">ROXAS CITY</p>
	<hr>
	<p class="text-center"><b><?php echo $_GET['id'] == 0 ? "Payments" : 'Payment Receipt' ?></b></p>
	<hr>
	<div class="flex">
		<div class="w-50">
			<p>OR #: <b><?php echo isset($pay_arr[$_GET['id']]) ? $pay_arr[$_GET['id']]['of_re']: '' ?></b></p>
			<p>Borrower: <b><?php echo $name ?></b></p>
			<p>Ref No: <b><?php echo $ref_no ?></b></p>
		</div>
		<?php if($_GET['id'] > 0): 
		$plans = $pay_arr[$_GET['id']]['plan_id'];
		if($plans == 1){
			$plans = "APPLIANCE LOAN";
		}elseif($plans == 2){
			$plans = "LONG TERM LOAN";
		}elseif($plans == 3){
			$plans = "SHORT TERM LOAN";
		}elseif($plans == 4){
			$plans = "RICE LOAN";
		}elseif($plans == 5){
			$plans = "EDUCATIONAL LOAN";
		}elseif($plans == 6){
			$plans = "SPECIAL EMERGENCY LOAN";
		}
		?>
		<div class="w-50">
			<p>Payment Date: <b><?php echo isset($pay_arr[$_GET['id']]) ? date("M d,Y",strtotime($pay_arr[$_GET['id']]['date_created'])) : '' ?></b></p>
			<p>Plan: <b><?php echo $plans ?></b></p>
		</div>
		<?php endif; ?>
	</div>
	<hr>
	<p><b>Payment Summary</b></p>
	<hr>
	<?php 
	$totals =$pay_arr[$_GET['id']]['paid'] + $pay_arr[$_GET['id']]['interest'] + $pay_arr[$_GET['id']]['capital'] + $pay_arr[$_GET['id']]['penalty_amount'];
	?>
	<br>
	<table width="100%" class="wborder">
		<tr>
			<td width="20%" class="text-center">Principal</td>
			<td width="20%" class='text-center'>Interest</td>
			<td width="20%" class="text-center">Shared Capital</td>
			<td width="20%" class="text-center">Penalty</td>
			<td width="20%" class="text-center">Total</td>
		</tr>
		<td class="text-center">
			<p><?php echo isset($pay_arr[$_GET['id']]) ? number_format($pay_arr[$_GET['id']]['paid'],2): ''?></p>
		</td>
		<td class="text-center">
			<p><?php echo isset($pay_arr[$_GET['id']]) ? number_format($pay_arr[$_GET['id']]['interest'],2): ''?></p>
		</td>
		<td class="text-center">
			<p><?php echo isset($pay_arr[$_GET['id']]) ? number_format($pay_arr[$_GET['id']]['capital'],2): ''?></p>
		</td>
		<td class="text-center">
			<p><?php echo isset($pay_arr[$_GET['id']]) ? number_format($pay_arr[$_GET['id']]['penalty_amount'],2): ''?></p>
		</td>
		<td class="text-center">
			<p><?php echo number_format($totals, 2) ?></p>
		</td>
					
			</table>
			<br><br>
	<p><?php echo $_SESSION['login_name'] ?></p>
	<p><?php echo $_SESSION['login_position'] ?></p>

</div>