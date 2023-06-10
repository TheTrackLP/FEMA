<?php 
include 'db_connect.php';

?>
<style>
	.label{
		font-weight: bold;
		font-size: 25px;
		color: black;
	}
</style>
<div class="container-fluid">
	<div class="row">
	<div class="col-lg-12">
	</div>
</div>
<br>
	<label class="label">Admins/Staffs Accounts</label>
	<div class="row">
		<div class="card col-lg-12">
			<div class="card-body">
				<table class="table-striped table-bordered col-md-12">
				<thead class="table-info">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">Username</th>
							<th class="text-center">Position</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php
	 					$users = $conn->query("SELECT * FROM users order by name asc");
	 					$i = 1;
	 					while($row= $users->fetch_assoc()):
					 ?>
					 <tr>
					 	<td class="text-center">
				 			<?php echo $i++ ?>
				 		</td>
					 	<td>
					 		<?php echo $row['name'] ?>
					 	</td>
					 	<td>
					 		<?php echo $row['username'] ?>
					 	</td>
					 	<td>
					 		<?php echo $row['position'] ?>
					 	</td>
					 	<td>
					 		<center>
					 			<div class="btn-group">
								  <button type="button" class="btn btn-info">Action</button>
								  <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    <span class="sr-only">Toggle Dropdown</span>
								  </button>
								  <div class="dropdown-menu">
								  	<a class="dropdown-item edit_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Edit</a>
								  	<div class="dropdown-divider"></div>
								  	<a class="dropdown-item delete_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Delete</a>
								  </div>
								</div>
							</center>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>
</div>
	<hr>
	<label class="label">Borrowers Accounts</label>
	<div class="row">
		<div class="card col-lg-12">
			<div class="card-body">
				<table class="table-striped table table-bordered col-md-12" id="filterMember">
					<colgroup>
						<col width="10%">
						<col width="40%">
						<col width="28%">
						<col width="22%">
					</colgroup>
					<thead class="table-info">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">Email</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$users = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename)as name FROM borrowers order by id asc");
					$i = 1;
					while($row= $users->fetch_assoc()):
					 ?>
					 <tr>
					 	<td class="text-center">
					 		<?php echo $i++ ?>
					 	</td>
					 	<td>
					 		<?php echo $row['name'] ?>
					 	</td>
					 	<td>
					 		<?php echo $row['email'] ?>
					 	</td>
					 	<td>
					 		<center>
					 			<div class="btn-group">
								<a class="edit_member" style="color: blue;" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Edit Account</a>
							</div>
						</center>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
	</div>
</div>
</div>
</div>
<script>
$('#filterMember').DataTable();
$('#new_user').click(function(){
	uni_modal('New User','manage_user.php')
})
$('.edit_user').click(function(){
	uni_modal('Edit User','manage_user.php?id='+$(this).attr('data-id'))
})
$('.edit_member').click(function(){
	uni_modal('Edit User','manage_member.php?id='+$(this).attr('data-id'))
})
$('.delete_user').click(function(){
		_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
	})
	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>