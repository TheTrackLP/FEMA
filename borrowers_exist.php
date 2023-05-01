<?php include 'db_connect.php' ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>New/Existing Borrower List</b>
					<button class="btn btn-primary btn-block col-md-2 float-right" type="button" id="new_borrower"><i class="fa fa-plus"></i> New Borrower</button>
				</large>
			</div>
			<div class="plan-filter">
				<form>
					<div class="col-md-4">
						<div class="form-group">
							<p><b>Office/Department:</b></p>
							<?php
              $department = $conn->query("SELECT * from departments");
              ?>
							<select id="planFilter" class="form-control">
								<option value="">Show All Office Department</option>
								<?php while($row = $department->fetch_assoc()): ?>
									<option value="<?php echo $row['department'] ?>"><?php echo $row['department'] ?></option>
								<?php endwhile; ?>
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
					<thead class="thead-dark">
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

							$qry = $conn->query("SELECT * FROM borrowers WHERE stat = 'Existing' order by id desc");
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
						 		<p class="text-center"><?php echo $row['shared_capital']?></p>							
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
						 		<p>Date Created :<b><br><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>
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

      //Get a reference to the new datatable
      var table = $('#borrower-list').DataTable();

      //Take the category filter drop down and append it to the datatables_filter div. 
      //You can use this same idea to move the filter anywhere withing the datatable that you want.
      //$("#filterTable_filter.dataTables_filter").append($("#planFilter"));
      
      //Get the column index for the Category column to be used in the method below ($.fn.dataTable.ext.search.push)
      //This tells datatables what column to filter on when a user selects a value from the dropdown.
      //It's important that the text used here (Category) is the same for used in the header of the column to filter
      var planIndex = 0;
      $("#borrower-list th").each(function (i) {
        if ($($(this)).html() == "Department") {
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