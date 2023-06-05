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
           <div class="card-body">
				<table class="table table-bordered table-hover" id="report-list">
					<thead class="thead-dark">
						<tr>
							<th class="text-center align-middle" rowspan="2">#</th>
							<th class="text-center align-middle" rowspan="2"><?php echo date("F Y", strtotime($month))?></th>
							<th class="text-center align-middle" colspan="2">Appliance</th>
							<th class="text-center align-middle" colspan="2">Long Term</th>
							<th class="text-center align-middle" colspan="2">Short term</th>
							<th class="text-center align-middle" colspan="2">Rice Loan</th>
							<th class="text-center align-middle" colspan="2">Educational Loan</th>
							<th class="text-center align-middle" colspan="2">Special Emergency Loan</th>
							<th class="text-center align-middle" rowspan="2">Paid-In</th>
							<th class="text-center align-middle" rowspan="2">Total</th>
							<th class="text-center align-middle" rowspan="2">Date</th>
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
                      $qry = $conn->query("SELECT p.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name from payments p inner join borrowers b on b.id = p.borrower_id where date_format(p.date_created,'%Y-%m') = '$month' order by unix_timestamp(p.date_created) asc");
                      if($qry->num_rows > 0):
                      	while($row = $qry->fetch_array()):
							$total =  $row['paid'] + $row['interest'];
			         ?>
			        <tr>
			        	<td class="text-center"><?php echo $i++ ?></td>
                        <td>
						 	<p><small><b><?php echo $row['name'] ?></small></b></p>
                        </td>
						<?php for($j = 1 ; $j <= 6; $j++){
						?>
                        <td class="text-center">
							<?php echo $row['plan_id'] == $j? number_format($row['paid'], 2)  : ""?>
						</td>
                        <td class="text-center">
							<?php echo $row['plan_id'] == $j? number_format($row['interest'], 2)  : ""?>
						</td>
						<?php }?>
						<td class="text-center">
							<p>Paid in</p>
                        </td>
						<td class="text-center">
							<?php echo number_format($total, 2) ?>
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
			border:1px solid;
		}
        p{
            margin:unset;
            padding: unset;
        }
		.text-center{
			text-align:center;
		}
        .text-right{
            text-align:right;
        }
	</style>
</noscript>
<script>
    $("document").ready(function () {

      $("#report-list").dataTable({
      });
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