<?php include 'db_connect.php' ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<b>Loan List</b>
					<button class="btn btn-primary col-md-2 float-right" type="button" id="new_application"><i class="fa fa-plus"></i> Create New Application</button>
				</div>
				
			</div>
			<div class="plan-filter">
				<form>
					<div class="col-md-4">
						<div class="form-group">
				<?php
				$plan = $conn->query("SELECT * FROM loan_plan");
				?>
				<p><b>Type of Loan</b></p>
				<select id="planFilter" class="form-control">
					<option value="">Show All Plan</option>
						<?php while($row = $plan->fetch_assoc()): ?>
							<option value="<?php echo $row['plan_loan'] ?>"><?php echo $row['plan_loan'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<p><b>Status</b></p>
					<select id="statusFilter" class="form-control">
						<option value="">Show all Status</option>
						<option>For Approval</option>
						<option>Approved</option>
						<option>Released</option>
						<option>Complete</option>
						<option>Denied</option>
					</select>
				</div>
			</div>
		</form>
	</div>
			<div class="card-body">
				<table class="table table-bordered" id="filterTable">
					<colgroup>
						<col width="5%">
						<col width="7%">
						<col width="20.2%">
						<col width="25%">
						<col width="15%">
						<col width="8%">
						<col width="12%">
					</colgroup>
					<thead class="table-info">
						<tr>
							<th scope="col" class="text-center">#</th>
							<th scope="col" class="text-center">CV#</th>
							<th scope="col" class="text-center">Name</th>
							<th scope="col" class="text-center">Type of Loan</th>
							<th scope="col" class="text-center">Next Payment Details</th>
							<th scope="col" class="text-center">Status</th>
							<th scope="col" class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							
							$i=1;
							$five = 500;
							$plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
							while($row=$plan->fetch_assoc()){
								$plan_arr[$row['id']] = $row;
							}
							$qry = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.shared_capital from loan_list l inner join borrowers b on b.id = l.borrower_id  order by id desc");
							while($row = $qry->fetch_assoc()):
								$monthly = ($row['amount'] + ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100)));
								$this_month = ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100));
								$penalty = $monthly * ($plan_arr[$row['plan_id']]['penalty_rate']/100);
								$payments = $conn->query("SELECT * from payments where loan_id =".$row['id']);
								$paid = $payments->num_rows;
								$offset = $paid > 0 ? " offset $paid ": "";
								if($row['status'] == 2):
									$next = $conn->query("SELECT * FROM loan_schedules where loan_id = '".$row['id']."'  order by date(date_due) asc limit 1 $offset ")->fetch_assoc()['date_due'];
								endif;
								$sum_paid = 0;
								while($p = $payments->fetch_assoc()){
									$sum_paid += ($p['paid'] - $p['penalty_amount']);
								}
						 ?>
						 <tr>
						 	
						 	<td class="text-center"><?php echo $i++ ?></td>
						 	<td class="text-center">
						 		<p><?php echo "CV#-"."<b>".$row['borrower_id']."</b>" ?></p>
						 	<td>
						 		<p><b><?php echo $row['name'] ?></b></p>
						 	</td>
						 	<td>
						 		<p><b><?php echo $plan_arr[$row['plan_id']]['plan'] ?></b></p>
						 	</td>
						 	<td>
						 		<?php if($row['status'] == 2 ): ?>
						 		<p><small><b><?php echo date('M d, Y',strtotime($next)); ?></b></small></p>
						 		<p><small>Penalty :<b><?php echo $add = (date('Ymd',strtotime($next)) < date("Ymd") ) ?  $penalty : 0; ?></b></small></p>
								<button class="btn btn-sm btn-primary view_schedule" data-id="<?php echo $row['id']?>">View Schedule</button>
						 		<?php elseif($row['status'] == 3): ?>
						 			<b>Loan Fully Paid</b>
					 			<?php else: ?>
					 				N/A
						 		<?php endif; ?>
						 	</td>
						 	<td class="text-center">
						 		<?php if($row['status'] == 0): ?>
						 			<span class="badge badge-warning">For Approval</span>
						 		<?php elseif($row['status'] == 1): ?>
						 			<span class="badge badge-info">Approved</span>
					 			<?php elseif($row['status'] == 2): ?>
						 			<span class="badge badge-primary">Released</span>
					 			<?php elseif($row['status'] == 3): ?>
						 			<span class="badge badge-success">Completed</span>
					 			<?php elseif($row['status'] == 4): ?>
						 			<span class="badge badge-danger">Denied</span>
						 		<?php endif; ?>
						 	</td>
						 	<td class="text-center">
									<button class="btn btn-outline-success btn-sm view_summary" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-print"></i></button>
									<?php if($row['status'] == 0 || $row['status'] == 1 || $row['status'] == 2 || $row['status'] == 4): ?>
						 			<button class="btn btn-outline-primary btn-sm edit_loan" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-eye"></i></button>
						 		<?php endif; ?>						 			
						 		<button class="btn btn-outline-danger btn-sm delete_loan" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
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

      $("#filterTable").dataTable({
        "searching": true
      });

      //Get a reference to the new datatable
      var table = $('#filterTable').DataTable();

      //Take the category filter drop down and append it to the datatables_filter div. 
      //You can use this same idea to move the filter anywhere withing the datatable that you want.
      //$("#filterTable_filter.dataTables_filter").append($("#planFilter"));
      
      //Get the column index for the Category column to be used in the method below ($.fn.dataTable.ext.search.push)
      //This tells datatables what column to filter on when a user selects a value from the dropdown.
      //It's important that the text used here (Category) is the same for used in the header of the column to filter
      var planIndex = 0;
      $("#filterTable th").each(function (i) {
        if ($($(this)).html() == "Loan Details") {
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


      var statusIndex = 0;
      $("#filterTable th").each(function (i) {
        if ($($(this)).html() == "Status") {
          planIndex = i; return false;
        }
      });

      //Use the built in datatables API to filter the existing rows by the Category column
      $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
          var selectedItem = $('#statusFilter').val()
          var status = data[planIndex];
          if (selectedItem === "" || status.includes(selectedItem)) {
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
      $("#statusFilter").change(function (e) {
        table.draw();
      });

      table.draw();
    })

	$('#new_application').click(function(){
		uni_modal("New Loan Application","manage_loan.php",'mid-large')
	})
	$('.view_summary').click(function(){
		uni_modal("Payment Details","view_summary.php?loan_id="+$(this).attr('data-id')+"&id=0","mid-large")
	})
	$('.view_schedule').click(function(){
		view_modal("View Payment Schedule","view_schedule.php?loan_id="+$(this).attr('data-id')+"&id=0","mid-large")
	})
	$('.view_loan').click(function(){
		view_modal("View Loan","manage_loan.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.edit_loan').click(function(){
		uni_modal("Edit Loan","manage_loan.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_loan').click(function(){
		_conf("Are you sure to delete this data?","delete_loan",[$(this).attr('data-id')])
	})
function delete_loan($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_loan',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Loan successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>