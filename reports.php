<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
        	<div class="card-header">
        		<div class="card-title">
        			<b>Reports</b><br>
        			<label for="" class="mt-2">Month</label>
        			<div class="col-sm-3">
        				<input type="month" name="month" id="month" value="<?php echo $month ?>" class="form-control">
        			</div>
        		</div>
			</div>
						<div class="plan-filter">
				<form>
					<div class="col-md-4">
						<div class="form-group">
				<?php
				$plan = $conn->query("SELECT * FROM loan_plan");
				?>
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
				<table class="table table-bordered" id="report-list">
					<colgroup>
						<col width="4%">
						<col width="7.5%">
						<col width="22%">
						<col width="23.5%">
						<col width="11%">
						<col width="10%">
						<col width="10%">
						<col width="12%">
					</colgroup>
					<thead class="thead-dark">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">CV #</th>
							<th class="text-center">Name</th>
							<th class="text-center">Type of Loan</th>
							<th class="text-center">Principal</th>
							<th class="text-center">Interest</th>
							<th class="text-center">Balance</th>
							<th class="text-center">Release Date</th>
						</tr>
					</thead>
					<tbody>
			          <?php
                      $i = 1;
                      $plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
                      while($row=$plan->fetch_assoc()){
                      	$plan_arr[$row['id']] = $row;
                      }
                      $qry = $conn->query("SELECT l.*,l.ref_no,l.amount,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, p.paid, p.interest from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = l.borrower_id where date_format(l.date_created,'%Y-%m') = '$month' order by unix_timestamp(l.date_created) asc ");
                      if($qry->num_rows > 0):
                      	while($row = $qry->fetch_array()):
                      		$monthly = ($row['amount'] + ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100)));
                      		$interest = $row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100);
                      		$penalty = $monthly * ($plan_arr[$row['plan_id']]['penalty_rate']/100);
			         ?>
			        <tr>
			        	<td class="text-center"><?php echo $i++ ?></td>
                        <td class="text-center">
                        	<p><small><b><?php echo $row['borrower_id'] ?></small></b></p>
                        </td>
                        <td>
						 	<p><small><b><?php echo $row['name'] ?></small></b></p>
                        </td>
                        <td class="text-center">
                        	<span><small><?php echo $plan_arr[$row['plan_id']]['plan'] ?></small></span>
                        </td>
                        <td class="text-center">
                        	<?php echo number_format($row['paid'],2)?>
                        </td>
                        <td class="text-center">
                        	<?php echo number_format($row['interest'],2) ?>
                        </td>
                        <td class="text-center">
                        	<?php echo number_format($row['amount'],2) ?>
                        </td>
                        <td class="text-center">
                            <p><b><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>

                        </td>
                    </tr>
                    <?php 
                        endwhile;
                   		else:
                    ?>
                    <tr>
                    	<th class="text-center" colspan="7">No Data.</th>
                    </tr>
                    <?php 
                    	endif;
                    ?>
			        </tbody>
                </table>
                <hr>
                <div class="col-md-12 mb-4">
                    <center>
                        <button class="btn btn-success btn-sm col-sm-3" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<noscript>
	<style>
		table#report-list{
			width:100%;
			border-collapse:collapse
		}
		table#report-list td,table#report-list th{
			border:1px solid
		}
        p{
            margin:unset;
            padding: unset;
        }
		.text-center{
			text-align:center
		}
        .text-right{
            text-align:right
        }
	</style>
</noscript>
<script>
    $("document").ready(function () {

      $("#report-list").dataTable({
        "searching": true
      });

      //Get a reference to the new datatable
      var table = $('#report-list').DataTable();

      //Take the category filter drop down and append it to the datatables_filter div. 
      //You can use this same idea to move the filter anywhere withing the datatable that you want.
      
      //Get the column index for the Category column to be used in the method below ($.fn.dataTable.ext.search.push)
      //This tells datatables what column to filter on when a user selects a value from the dropdown.
      //It's important that the text used here (Category) is the same for used in the header of the column to filter
      var planIndex = 0;
      $("#report-list th").each(function (i) {
        if ($($(this)).html() == "Loan Plan") {
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
	$('#month').change(function(){
		location.replace('index.php?page=reports&month='+$(this).val())
	})
	$('#print').click(function(){
		var _c = $('#report-list').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=1000,height=600')
		nw.document.write(`<p class="text-center"><b>Payment Report as of <?php echo date("F, Y",strtotime($month)) ?></b></p>
			<p class="text-center"><b>Loan <?php  ?></b></p>
			`)
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	})
</script>