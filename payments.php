	<?php include 'db_connect.php' ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>Payment History</b>
					<?php if($_SESSION['login_position'] == "Cashier" || $_SESSION['login_position'] == "Posting_clerk"):?>
						<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_payments"><i class="fa fa-plus"></i> New Payment</button>
					<?php  endif;?>
				</large>
			</div>
			<div class="plan-filter">
				<form>
					<div class="col-md-4">
						<div class="form-group">
				<?php
				$plan = $conn->query("SELECT * FROM loan_plan");
				?>
				<p><b>Plan:</b></p>
				<select id="planFilter" class="form-control">
					<option value="">Show All Plan</option>
						<?php while($row = $plan->fetch_assoc()): ?>
							<option value="<?php echo $row['plan_loan'] ?>"><?php echo $row['plan_loan'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
			</div>
		</form>
	</div>
			<div class="card-body">
				<table class="table table-bordered" id="loan-list">
					<colgroup>
						<col width="5%">
						<col width="18.5%">
						<col width="26.5%">
						<col width="19.5%">
						<col width="8.5%">
						<col width="12.5%">
						<col width="8.5%">
					</colgroup>
					<thead class="table-info">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Reference and CV #</th>
							<th class="text-center">Borrowers Details</th>
							<th class="text-center">Amount</th>
							<th class="text-center">Penalty</th>
							<th class="text-center">Date</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							
							$i=1;
							$plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
							while($row=$plan->fetch_assoc()){
								$plan_arr[$row['id']] = $row;
							}
							$qry = $conn->query("SELECT p.*,l.ref_no,l.amount,l.plan_id,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.contact_no from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = l.borrower_id  order by p.id desc");
							while($row = $qry->fetch_assoc()):
								
						 ?>
						 <tr>
						 	
						 	<td class="text-center"><?php echo $i++ ?></td>
						 	<td>
						 		<p>Loan Ref. #<b><?php echo $row['ref_no'] ?></b></p>
						 		<p>OR. #<b><?php echo $row['of_re'] ?></b></p>
						 	</td>
						 	<td>
						 		Name: <b><?php echo $row['name'] ?></b><br>
						 		Plan: <b><?php echo $plan_arr[$row['plan_id']]['plan']  ?></b>
						 		
						 	</td>
						 	<td>
						 		Balance: <b><?php echo number_format($row['amount'],2) ?></b><br>
						 		Principal: <b><?php echo number_format($row['paid'],2) ?></b><br>
						 		Interest: <b><?php echo number_format($row['interest'],2) ?></b><br>
								Capital: <b><?php echo number_format($row['capital'],2) ?></b>
						 	</td>
						 	<td class="text-center">
						 		<?php echo number_format($row['penalty_amount'],2) ?>
						 	</td>
						 	<td>
						 		<?php echo date("M d, Y", strtotime($row['date_created']))?>
						 	</td>
						 	<td class="text-center">
								 <?php if($_SESSION['login_position'] == "Cashier" || $_SESSION['login_position'] == "Posting_clerk"):?>
									<button class="btn btn-sm btn-outline-success view_payment" type="button" data-id="<?php echo $row['id'] ?>" data-loan_id="<?php echo $row['loan_id'] ?>"><i class="fa fa-print"></i></button>	
									<button class="btn btn-sm btn-outline-success view_summary" type="button" data-id="<?php echo $row['id'] ?>" data-loan_id="<?php echo $row['loan_id'] ?>"><i class="fa fa-file"></i></button>	
								<?php endif;?>
						 	</td>
						 </tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	td p {
		margin:unset;
	}
	td img {
	    width: 8vw;
	    height: 12vh;
	}
	td{
		vertical-align: middle !important;
	}
</style>	
<script>
    $("document").ready(function () {

      $("#loan-list").dataTable({
        "searching": true
      });

      //Get a reference to the new datatable
      var table = $('#loan-list').DataTable();

      //Take the category filter drop down and append it to the datatables_filter div. 
      //You can use this same idea to move the filter anywhere withing the datatable that you want.
      
      //Get the column index for the Category column to be used in the method below ($.fn.dataTable.ext.search.push)
      //This tells datatables what column to filter on when a user selects a value from the dropdown.
      //It's important that the text used here (Category) is the same for used in the header of the column to filter
      var planIndex = 0;
      $("#loan-list th").each(function (i) {
        if ($($(this)).html() == "Borrowers Details") {
          planIndex = i; return false;
        }
      });

      //Use the built in datatables API to filter the existing rows by the Category column
      $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
          var selectedItem = $('#planFilter').val()
          var plan = data[planIndex];
          if (selectedItem === "" || plan.includes(selectedItem)) {
            return true;
          }
          return false;
        }
      );

      //Set the change event for the Category Filter dropdown to redraw the datatable each time
      //a user selects a new filter.
      $("#planFilter").change(function (e) {
        table.draw();
      });

      table.draw();
    })
	$('#new_payments').click(function(){
		uni_modal("New Payment","manage_payment.php",'mid-large')
	})
	$('.view_payment').click(function(){
		uni_modal("Payment Details","view_payment.php?loan_id="+$(this).attr('data-loan_id')+"&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.view_summary').click(function(){
		uni_modal("Payment Details","view_summary.php?loan_id="+$(this).attr('data-loan_id')+"&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.edit_payment').click(function(){
		uni_modal("Edit Payment","manage_payment.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_payment').click(function(){
		_conf("Are you sure to delete this data?","delete_payment",[$(this).attr('data-id')])
	})
	function delete_payment($id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_payment',
				method:'POST',
				data:{id:$id},
				success:function(resp){
					if(resp==1){
						alert_toast("Payment successfully deleted",'success')
						setTimeout(function(){
							location.reload()
						},1500)

					}
				}
			})
		}
</script>