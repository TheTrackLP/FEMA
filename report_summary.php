<?php 
session_start();

include 'db_connect.php';
//SELECT p.*,l.ref_no,l.amount,l.plan_id,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = l.borrower_id  order by p.id desc
$fees = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no from loan_list l inner join borrowers b on b.id = l.borrower_id inner join loan_plan c on c.id = l.plan_id  where l.id = {$_GET['loan_id']}");
foreach($fees->fetch_array() as $k => $v){
	$$k= $v;
}
//SELECT p.*,l.ref_no,l.amount,l.plan_id,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = l.borrower_id
$payments = $conn->query("SELECT p.* from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = $borrower_id ");
$pay_arr = array();
while($row=$payments->fetch_array()){
	$pay_arr[$row['id']] = $row;
}
$plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
while($row=$plan->fetch_assoc()){
	$plan_arr[$row['id']] = $row;
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
			<p>CV #: <b><?php echo $borrower_id ?></b></p>
			<p>Borrower: <b><?php echo $name ?></b></p>
			<p>Ref No: <b><?php echo $ref_no ?></b></p>
		</div>
	</div>
	<hr>
	<p><b>Payment Summary</b></p>
	<hr>
	<br>
	<table width="100%" class="wborder">
		<p><b>Payment Details</b></p>
		<tr>
			<td width="25%" class="text-center">Principal</td>
			<td width="25%" class='text-center'>Interest</td>
			<td width="25%" class="text-center">Plan</td>
			<td width="25%" class="text-center">Date</td>
		</tr>
		<?php 
		$payment_total = 0;
		foreach ($pay_arr as $row) {
			if($row["id"] <= $_GET['id'] || $_GET['id'] == 0){
				$payment_total += $row['paid'];
				?>
				<tr>
					<td><b><?php echo $row['paid'] ?></b></td>
					<td><b><?php echo $row['interest'] ?></b></td>
					<td><b><small><?php echo $plan_arr[$row['plan_id']]['plan'] ?></b></small></td>
					<td><b><?php echo date('Y-M-d', strtotime($row['date_created'])) ?></b></td>
				</tr>
				<?php
				}
				}
				?>
				<table class="wborder">
					<tr>
						<td width="50%">
							<p><b>Loan Details</b></p>
							<hr>
							<table width="100%">
								<tr>
									<td width="75%">Loan Type</td>
									<td width="25%" class='text-right'>Amount</td>
								</tr>
									<?php 
									$loan = $conn->query("SELECT * FROM loan_list where borrower_id = $borrower_id");
									$total_loan = 0;
									while($row=$loan->fetch_array()){
									$total_loan += $row['amount'];						
									?>
								<td><small><b><?php echo $plan_arr[$row['plan_id']]['plan'] ?></b></small></td>
								<td class='text-right'><small><b><?php echo number_format($row['amount']) ?></b></small></td>
							</tr>
							<?php
							}
							?>
							<tr>
								<th>Total</th>
								<th class='text-right'><b><?php echo number_format($total_loan) ?></b></th>
							</tr>
						</table>
					</td>			
					<td width="50%">
						<table width="100%">
							<tr>
								<td>Total Loan Amount</td>
								<td class='text-right'><b><?php echo number_format($total_loan) ?></b></td>
							</tr>
							<tr>
								<td>Total Paid</td>
								<td class='text-right'><b><?php echo number_format($payment_total) ?></b></td>
							</tr>
							<tr>
								<td>Balance</td>
								<td class='text-right'><b><?php echo number_format($total_loan - $payment_total) ?></b></td>
							</tr>
						</table>
					</td>		
				</tr>
			</table>
			<br><br>
	<p><?php echo $_SESSION['login_name'] ?></p>
	<p><?php echo $_SESSION['login_position'] ?></p>

</div>