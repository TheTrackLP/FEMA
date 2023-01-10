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
						<col width="10.2%">
						<col width="8.2%">
						<col width="22.2%">
						<col width="16.2%">
						<col width="14.2%">
						<col width="14.2%">
						<col width="14.2%">
					</colgroup>
					<thead class="thead-dark">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">CV#</th>
							<th class="text-center">Name</th>
							<th class="text-center">Department</th>
							<th class="text-center">Address/Contact#</th>
							<th class="text-center">Date</th>
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
						 		<p><small><b><?php echo "CV-",$row['id'] ?></small></b></p>
						 	</td>
						 	<td>
						 		<p><b><?php echo ucwords($row['lastname'].", ".$row['firstname'].' '.$row['middlename']) ?></b></p>
						 	</td>
						 	<td>
						 		<p><?php echo $row['department']?></p>							
							</td>
						 	<td>
						 		<?php echo $row['contact_no']?><br>
						 		<?php echo $row['address'] ?>
						 	</td>
						 	<td class="text-center">
						 		<p>Date Created :<b><br><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>
						 	</td>
						 	<td class="text-center">
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