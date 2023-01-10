<?php include 'db_connect.php' ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>Borrower List</b>
					<button class="btn btn-primary btn-block col-md-2 float-right" type="button" id="new_borrower"><i class="fa fa-plus"></i> New Borrower</button>
				</large>
			</div>
			
			<div class="card-body">
				<table class="table table-bordered" id="borrower-list">
					<colgroup>
						<col width="10%">
						<col width="35%">
						<col width="30%">
						<col width="15%">
						<col width="10%">
					</colgroup>
					<thead class="thead-dark">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Borrower</th>
							<th class="text-center">Amount Borrowed</th>
							<th class="text-center">Borrower Date Created</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
							$i=1;

							$qry = $conn->query("SELECT * FROM borrowers order by id desc");
							while($row = $qry->fetch_assoc()):

						 ?>
						 <tr>
						 	
						 	<td class="text-center"><?php echo $i++ ?></td>
						 	<td>
						 		<p><small>CV # :<b><?php echo "CV-",$row['id'] ?></small></b></p>
						 		<p>Name :<b><?php echo ucwords($row['lastname'].", ".$row['firstname'].' '.$row['middlename']) ?></b></p>
						 		<p><small>Address :<b><?php echo $row['address'] ?></small></b></p>
						 		<p><small>Contact # :<b><?php echo $row['contact_no'] ?></small></b></p>
						 		<p><small>Email :<b><?php echo $row['email'] ?></small></b></p>
						 		<p><small>ID # :<b><?php echo $row['employee_id'] ?></small></b></p>
						 		<p><small>Years in Service :<b><?php echo $row['year_service'] ?></b></small></p>
						 		<p><small>Office/Department :<b><?php echo $row['department'] ?></b></small></p>
						 		
						 	</td>
						 	<td class="text-center">
									<?php 
									$loan = $conn->query("SELECT * FROM loan_list where borrower_id in (SELECT id from borrowers)");
									$total_loan = 0;
									while($row2=$loan->fetch_array()){
									$total_loan += $row2['amount'];						
									?>
							<p><small>Total Amount: <b><?php echo number_format($total_loan) ?></b></small></p>

							<?php } ?>
									
							</td>
						 	<td>
						 		<p>Date Created :<b><br><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>
						 	</td>
						 	<td class="text-center">
						 			<button class="btn btn-outline-success btn-sm view_borrower" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-eye"></i></button>
						 			<button class="btn btn-outline-primary btn-sm edit_borrower" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
						 			<button class="btn btn-outline-danger btn-sm delete_borrower" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
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
	$(document).ready( function () {
		$('#borrower-list').DataTable();
	} );	
	$('#new_borrower').click(function(){
		uni_modal("New borrower","manage_borrower.php",'mid-large')
	})
	$('.edit_borrower').click(function(){
		uni_modal("Edit Borrower's Details","manage_borrower.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.view_borrower').click(function(){
		view_modal("View Borrower's Detalis","manage_borrower.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_borrower').click(function(){
		_conf("Are you sure to delete this borrower?","delete_borrower",[$(this).attr('data-id')])
	})
function delete_borrower($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_borrower',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("borrower successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>