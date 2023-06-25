<?php include 'db_connect.php' ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>New/Existing Borrower List</b>
				</large>
			</div>
			<div class="plan-filter">
				<form class="d-flex flex-row">
					<div class="col-md-4">
						<div class="form-group">
							<p><b>Office/Department:</b></p>
							<?php $department = $conn->query("SELECT * FROM `departments` ORDER BY `departments`.`department` ASC");?>
							<select id="deptFilter" class="custom-select browser-default select2">
								<option value="">Show All Office Department</option>
								<?php while($row = $department->fetch_assoc()): ?>
									<option value="<?php echo $row['department'] ?>"><?php echo $row['department'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<p><b>Status:</b></p>
							<select id="statusFilter" class="form-control">
								<option value="">New / Existing</option>
									<option>New</option>
									<option>Existing</option>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body">
				<table class="table table-bordered" id="borrower-list">
					<colgroup>
						<col width="5%">
						<col width="7%">
						<col width="27.5%">
						<col width="8.5%">
						<col width="16.5%">
						<col width="6.5%">
						<col width="10.5%">
						<col width="8%">
					</colgroup>
					<thead class="table-info">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">CV#</th>
							<th class="text-center">Borrower's Name</th>
							<th class="text-center">Shared Capital</th>
							<th class="text-center">Department</th>
							<th class="text-center">Status</th>
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
						 	<td class="text-center">
						 		<p>CV-<b><?php echo$row['id'] ?></b></p>
						 	</td>
						 	<td>
						 		<b><?php echo ucwords($row['lastname'].", ".$row['firstname'].' '.$row['middlename']) ?></b></p>
						 	</td>
						 	<td>
						 		<p class="text-center"><?php echo number_format($row['shared_capital'])?></p>							
							</td>
						 	<td>
						 		<p class="text-center"><?php echo $row['department']?></p>							
						 	</td>
						 	<td class="text-center">
						 		<?php if($row['stat'] == 'New'):?>
						 		<span class="badge badge-info">New</span>
						 		<?php elseif($row['stat'] == 'Existing'):?>
						 		<span class="badge badge-success">Existing</span>
						 	<?php endif; ?>
						 	</td>
						 	<td class="text-center">
						 		<b><br><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b>
						 	</td>
						 	<td class="text-center">
						 		<button class="btn btn-outline-primary btn-sm edit_borrower" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-eye"></i></button>
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
    $("document").ready(function () {

      $("#borrower-list").dataTable({
        "searching": true
      });

      var table = $('#borrower-list').DataTable();

	  var statusIndex = 0;
      $("#borrower-list th").each(function (i) {
        if ($($(this)).html() == "Status") {
			statusIndex = i; return false;
        }
      });

      $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
          var selectedItem = $('#statusFilter').val()
          var status = data[statusIndex];
          if (selectedItem === "" || status.includes(selectedItem)) {
            return true;
          }
          return false;
        }
      );

      $("#statusFilter").change(function (e) {
        table.draw();
      });

	  var deptIndex = 0;
      $("#borrower-list th").each(function (i) {
        if ($($(this)).html() == "Department") {
			deptIndex = i; return false;
        }
      });

      $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
          var selectedItem = $('#deptFilter').val()
          var dept = data[deptIndex];
          if (selectedItem === "" || dept.includes(selectedItem)) {
            return true;
          }
          return false;
        }
      );

      $("#deptFilter").change(function (e) {
        table.draw();
      });

      table.draw();
    })
	
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