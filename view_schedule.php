<?php 

include 'db_connect.php';
$fees = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, c.plan_loan, c.interest_percentage, c.penalty_rate, l.amount_borrowed from loan_list l inner join borrowers b on b.id = l.borrower_id inner join loan_plan c on c.id = l.plan_id  where l.id = {$_GET['loan_id']}");
foreach($fees->fetch_array() as $k => $v){
	$$k= $v;
}
$minus = $conn->query("SELECT count(loan_id) as total FROM loan_schedules WHERE loan_id ='".$_GET['loan_id']."'");
foreach($minus->fetch_array() as $k => $v){
	$$k= $v;
}
$plan = $conn->query("SELECT *,concat(plan_loan,' [ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
while($row=$plan->fetch_assoc()){
	$plan_arr[$row['id']] = $row;
}
$monthly_paid = $amount_borrowed / $total;
$wtih_interest = $interest_percentage/100;
$plus = $amount_borrowed + 1000;
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
	}

</style>
<div class="container-fluid">
	<div class="card">
		<div class="card-header text-center">
			<?php  
			$five = 500;
			$monthly = ($amount + ($amount * ($interest_percentage/100)));

			$this_month = ($amount * ($interest_percentage/100));	
			$penalty = $monthly * ($penalty_rate/100);

			?>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-5 col-xl-5">
					<p>Reference No.</p>
					<p><strong><?php echo $ref_no ?></strong></p>
				</div>
				<div class="col-md-7 col-xl-7">
					<p>Name:</p>
					<p><strong><?php echo $name?></strong></p><br>
				</div>
				<div class="col-md-6">
				<p>Interest this month: <b><?php echo number_format($this_month,2) ?></b></p>
				<p>Overdue Payable Amount: <b><?php echo number_format($penalty,2) ?></b></p>
			</div>
				<div class="col-md-6">
				<p>Amount Borrowed:<b><?php echo number_format($amount_borrowed, 2)?></b></p>
				<p>Amount Remaining:<b><?php echo number_format($amount,2) ?></b></p>
			</div>
		</div>
	</div>
	<hr>
	<div class="container">
		<div class="row">
			<div class="col-sm-6"><center>Months</center></div>
			<div class="col-sm-6"><center>Monthly Payment</center></div>
			<!--<div class="col-sm-4"><center>Paid this Month</center></div>-->
		</div>
		<hr>
		<?php 
		$tbl_schedule=$conn->query("SELECT * FROM loan_schedules WHERE loan_id ='".$_GET['loan_id']."'");
		while($row=$tbl_schedule->fetch_array()){
			?>
			<div class="row">
				<div class="col-sm-6 p-2 pl-5" style="border-right: 1px solid black; border-bottom: 1px solid black;">
					<strong><?php echo date("F d, Y" ,strtotime($row['date_due']));?></strong>
				</div>
				<div class="col-sm-6 p-2 pl-5" style="border-bottom: 1px solid black;">
					<strong><?php
					$date1 = new DateTime(date("F d, Y" ,strtotime($row['date_due'])));
					$fifth = $date1->format('d');
					if($fifth > 16){
						//echo "&#8369; ".number_format($amount * $interest_percentage/100 + $five,2);
						$plus -= 1000;
						$with_total = $wtih_interest * $plus;
						echo "&#8369; ".number_format($with_total + $five,2);
					}else{
						echo "&#8369; ".number_format($monthly_paid  , 2); 
					}
					?></strong>
				</div>
				<!--<div class="col-sm-4 text-center" style="border-left: 1px solid black; border-bottom: 1px solid black;">
				<?php #if($row['paid_month'] == 0): ?>
					<span class="badge badge-danger"><?php #echo "&#x2716;" ?></span>
				<?php #elseif($row['paid_month'] == 1): ?>
					<span class="badge badge-success"><?php #echo "&#x2714;" ?></span>
			<?php #endif; ?>
		</div>	--->
			</div>
			<?php 
		}
			?>
		</div>
	</div>
</div>