<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-department">
				<div class="card">
					<div class="card-header">
						   Add Department
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Departments</label>
								<input type="text" name="department" id="" class="form-control text-right">
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-danger col-sm-3" type="button" onclick="_reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead class="thead-dark">
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Departments</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$department = $conn->query("SELECT * FROM departments order by id asc");
								while($row=$department->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="text-center">
										 <p><large><b><?php echo $row['department']?></b></large></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_department" type="button" data-id="<?php echo $row['id'] ?>" data-department="<?php echo $row['department'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_department" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}

</style>
<script>
	function _reset(){
		$('[name="id"]').val('');
		$('#manage-department').get(0).reset();
	}
	
	$('#manage-department').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_department',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_department').click(function(){
		start_load()
		var department = $('#manage-department')
		department.get(0).reset()
		department.find("[name='id']").val($(this).attr('data-id'))
		department.find("[name='department']").val($(this).attr('data-department'))
		end_load()
	})
	$('.delete_department').click(function(){
		_conf("Are you sure to delete this department?","delete_department",[$(this).attr('data-id')])
	})
	function delete_department($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_department',
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