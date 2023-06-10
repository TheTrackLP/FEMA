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
           <div class="card-body">
				<table class="table table-bordered table-hover" id="report-list">
					<thead class="table-info">
						<tr>
							<th class="text-center align-middle" rowspan="2">#</th>
							<th class="text-center align-middle" rowspan="2"><?php echo date("m Y", strtotime($month)) ?></th>
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

                      $plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
                      while($row=$plan->fetch_assoc()){
                      	$plan_arr[$row['id']] = $row;
                      }
                      $b_qry = $conn->query("SELECT id, concat(lastname,' ',firstname,' ',middlename) as name from borrowers");
					  $borrowers = [];
					  while($b = $b_qry->fetch_array()){
						array_push($borrowers, $b);
					  }	
					$qry = $conn->query("SELECT p.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name from payments p inner join borrowers b on b.id = p.borrower_id where date_format(p.date_created,'%Y-%m') = '$month' order by unix_timestamp(p.date_created) asc ");
					$payments = [];
					while($payment = $qry->fetch_array()){
						array_push($payments, $payment);
					  }	
					$totals = [];
					$Totalcap = [0,0,0];
					for($i = 0; $i < 6; $i++){
						$arr = array('paid' => 0,'interest'=> 0);
						array_push($totals, $arr);
					}
					$i = 1;
					$hasdata = $qry->num_rows > 0;
					if($hasdata):
						foreach($borrowers as $borrower){
							$borrower['data'] = [];//[1=>array('paid' => 0,'interest'=> 0), 2=>array('paid' => 0,'interest'=> 0), 3=>array('paid' => 0,'interest'=> 0), 4=>array('paid' => 0,'interest'=> 0), 5=>array('paid' => 0,'interest'=> 0), 6=>array('paid' => 0,'interest'=> 0)];
							for($j = 0; $j < 6; $j++){
								$arr = array('paid' => 0,'interest'=> 0);
								array_push($borrower['data'], $arr);
							}
							$borrower['isIncluded'] = false;
							$borrower['total'] = 0;
							$borrower['Tcapital'] = 0;
							$borrower['Tpenalty'] = 0;
							foreach($payments as $pm){
								$another = [];
								if($borrower['id'] == $pm['borrower_id']){
									$borrower['isIncluded'] = true;
									for($t = 0; $t < 6; $t++){
										if($t+1 == $pm['plan_id']){
											$borrower['data'][$t]['paid'] += $pm['paid'];
											$borrower['data'][$t]['interest'] += $pm['interest'];
											$borrower['total'] += $pm['paid'] + $pm['interest'];
											$borrower['Tcapital'] += $pm['capital'];
											$borrower['Tpenalty'] += $pm['penalty_amount'];
											}
									}
								}
							}
							if($borrower['isIncluded']){
								for($t = 0; $t < 6; $t++){
									$totals[$t]['paid'] += $borrower['data'][$t]['paid'];
									$totals[$t]['interest'] += $borrower['data'][$t]['interest'];
									
								}
								$Totalcap[0] += $borrower['Tcapital'];
								$Totalcap[1] += $borrower['Tpenalty'];
								$Totalcap[2] += $borrower['total'];
							?>
			        <tr>
			        	<td class="text-center"><?php echo $i++ ?></td>
                        <td>
						 	<p><small><b><?php echo $borrower['name'] ?></small></b></p>
                        </td>
						<?php for($j = 0 ; $j < 6; $j++){
						?>
                        <td class="text-center">
							<?php echo $borrower['data'][$j]['paid'] ==0 ? "" : number_format($borrower['data'][$j]['paid'],2)?>
						</td>
                        <td class="text-center">
							<?php echo $borrower['data'][$j]['interest'] == 0 ? "" :number_format($borrower['data'][$j]['interest'],2)?>
						</td>
						<?php }?>
						<td class="text-center">
							<?php echo number_format($borrower['Tcapital'],2) ?>
                        </td>
						<td class="text-center">
						<?php echo number_format($borrower['Tpenalty'],2) ?>
                        </td>
						<td class="text-center">
							<?php echo number_format($borrower['total'],2) ?>
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
					<?php if($hasdata): ?>
					<tfoot>
						<tr>
			        	<td class="text-center"></td>
                        <td>
						 	<p><small><b>Total</small></b></p>
                        </td>
						<?php for($j = 0; $j < 6; $j++){
						?>
                        <td class="text-center">
							<?php echo $totals[$j]['paid'] == 0 ? '' : $totals[$j]['paid'] ?>
						</td>
                        <td class="text-center">
							<?php echo $totals[$j]['interest'] == 0 ? '' : $totals[$j]['interest']?>
						</td>
						<?php }?>
						<td class="text-center">
							<?php echo $Totalcap[0] ?>
                        </td>
						<td class="text-center">
						<?php echo $Totalcap[1] ?>
                        </td>
						<td class="text-center">
							<?php echo $Totalcap[2] ?>
						</td>
                    </tr>
					<?php endif; ?>
						
					</tfoot>

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
      $("#report-list").dataTable();
    })
	$('#month').change(function(){
		location.replace('index.php?page=reports&month='+$(this).val())
	})
	$('#print').click(function(){
		var _c = $('#report-list').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=1000,height=600')
		nw.document.write(`<p class="text-center"><b>Payment Report as of <?php echo date("F, Y",strtotime($month)) ?></b></p>`)
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	})
</script>