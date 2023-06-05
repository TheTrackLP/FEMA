<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
	// "2023-06"
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
				<table class="table table-bordered table-hover" id="report-list">
					<thead class="thead-dark">
						<tr>
							<th class="text-center align-middle" rowspan="2">#</th>
							<th class="text-center align-middle" rowspan="2">CV #</th>							
							<th class="text-center align-middle" rowspan="2">Name</th>
							<th class="text-center align-middle" colspan="2">Appliance</th>
							<th class="text-center align-middle" colspan="2">Long Term</th>
							<th class="text-center align-middle" colspan="2">Short term</th>
							<th class="text-center align-middle" colspan="2">Rice Loan</th>
							<th class="text-center align-middle" colspan="2">Educational Loan</th>
							<th class="text-center align-middle" colspan="2">Special Emergency Loan</th>
							<th class="text-center align-middle" rowspan="2">Paid-In</th>
							<th class="text-center align-middle" rowspan="2">Other Recievable</th>
							<th class="text-center align-middle" rowspan="2">Total</th>
						</tr>
						<tr>
							<th class="text-center align-middle">PRIN</th>
							<th class="text-center align-middle">INT</th>
							<th class="text-center align-middle">PRIN</th>
							<th class="text-center align-middle">INT</th>
							<th class="text-center align-middle">PRIN</th>
							<th class="text-center align-middle">INT</th>
							<th class="text-center align-middle">PRIN</th>
							<th class="text-center align-middle">INT</th>
							<th class="text-center align-middle">PRIN</th>
							<th class="text-center align-middle">INT</th>
							<th class="text-center align-middle">PRIN</th>
							<th class="text-center align-middle">INT</th>
						</tr>
					</thead>
					<tbody>
			          <?php
                      $i = 1;
                      $plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
                      while($row=$plan->fetch_assoc()){
                      	$plan_arr[$row['id']] = $row;
                      }
                      $b_qry = $conn->query("SELECT id, concat(lastname,' ',firstname,' ',middlename) as name from borrowers");
					  $borrowers = [];
					  while($b = $b_qry->fetch_array()){
						array_push($borrowers, $b);
					  }	
					$qry = $conn->query("SELECT l.*,l.ref_no,l.amount,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, p.paid, p.interest from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = l.borrower_id where date_format(l.date_created,'%Y-%m') = '$month' order by unix_timestamp(l.date_created) asc ");
					$payments = [];
					while($payment = $qry->fetch_array()){
						array_push($payments, $payment);
					  }	
					if($qry->num_rows > 0):
						  foreach($borrowers as $borrower){
							$borrower['data'] = [1=>array('paid' => 0,'interest'=> 0), 2=>array('paid' => 0,'interest'=> 0), 3=>array('paid' => 0,'interest'=> 0), 4=>array('paid' => 0,'interest'=> 0), 5=>array('paid' => 0,'interest'=> 0), 6=>array('paid' => 0,'interest'=> 0)];
                      		$borrower['isIncluded'] = false;
							foreach($payments as $pm){
								$another = [];
								$newDate = date("Y-m", strtotime($pm['date_created']));
								if($borrower['id'] == $pm['borrower_id'] && $newDate == $month){
									$borrower['isIncluded'] = true;
									for($t = 1 ; $t <= 6; $t++){
										if($t == $pm['plan_id']){
											$borrower['data'][$t]['paid'] += $pm['paid'];
											$borrower['data'][$t]['interest'] += $pm['interest'];
										}
									}
								}
							}
							if($borrower['isIncluded']){
					 ?>
			        <tr>
			        	<td class="text-center"><?php echo $i++ ?></td>
                        <td class="text-center">
                        	<p><small><b><?php echo $borrower['id'] ?></small></b></p>
                        </td>
                        <td>
						 	<p><small><b><?php echo $borrower['name'] ?></small></b></p>
                        </td>
						<?php for($j = 1 ; $j <= 6; $j++){
						?>
                        <td class="text-center">
							<?php echo $borrower['data'][$j]['paid'] ==0 ? "" : $borrower['data'][$j]['paid']?>
						</td>
                        <td class="text-center">
							<?php echo $borrower['data'][$j]['interest'] == 0 ? "" :$borrower['data'][$j]['interest']?>
						</td>
						<?php }?>
						<td class="text-center">
							<p>Paid in</p>
                        </td>
						<td class="text-center">
							<p>Other</p>
                        </td>
						<td class="text-center">
							<p>Total</p>
                        </td>
                    </tr>
                    <?php
							} 
						}
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