<?php 
session_start();
include 'db_connect.php';
include 'header.php';

//$qry = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name, b.email, c.plan_loan, l.status from loan_list l inner join borrowers b on b.id = l.borrower_id inner join loan_plan c on c.id = l.plan_id  where b.id =".$_SESSION['user_id']);
$qry = $conn->query("SELECT *,concat(lastname, ', ', firstname,' ',middlename) as name FROM borrowers WHERE id =".$_SESSION['user_id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k = $val;
}



?>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style-user.css">
</head>
<body>

    <style>
        p{
            margin: 1;
        }

        body {
            background-image: linear-gradient(to bottom right, #00AFB9, #FED9B7);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
    

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-2 border-right d-flex align-items-center">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <span class="font-weight-bold"><?php echo $name ?></span>
                <span class="text-black-50"></span><span><?php echo $email ?></span>
                <span class="text-black-50"></span><span>Shared Capital: <b><?php echo $shared_capital ?></b></span>
                <a href="ajax.php?action=logout2" class="btn btn-danger mt-3">LOGOUT <i class="fa fa-power-off"></i></a>
            </div>
        </div>
        <div class="col-md-10 border-right">
            <button class="btn btn-primary col-md-2 float-right mt-5" data-bs-toggle="modal" data-bs-target="#uni_modal" type="button" id="new_application"><i class="fa fa-plus"></i> Apply New Loan</button>

            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Account Details</h4>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered" id="filterTable">
                                <colgroup>
                                    <col width="5%">
                                    <col width="25%">
                                    <col width="15%">
                                    <col width="8%">
                                </colgroup>
                                <thead class="table-info">
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col" class="text-center">Type of Loan</th>
                                        <th scope="col" class="text-center">Next Payment Details</th>
                                        <th scope="col" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $five = 500;
                                    $plan = $conn->query("SELECT *,concat(plan_loan) as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
                                    while($row=$plan->fetch_assoc()){
                                $plan_arr[$row['id']] = $row;
                                }
                                $qry = $conn->query("SELECT l.*,concat(b.lastname,', ',b.firstname,' ',b.middlename)as name from loan_list l inner join borrowers b on b.id = l.borrower_id where b.id =".$_SESSION['user_id']);
                                    while($row = $qry->fetch_assoc()):
                                        $monthly = ($row['amount'] + ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100)));
                                        $this_month = ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100));
                                        $penalty = $monthly * ($plan_arr[$row['plan_id']]['penalty_rate']/100);
                                        $payments = $conn->query("SELECT * from payments where loan_id =".$row['id']);
                                        $paid = $payments->num_rows;
                                        $offset = $paid > 0 ? " offset $paid ": "";
                                        if($row['status'] == 2):
                                            $next = $conn->query("SELECT * FROM loan_schedules where loan_id = '".$row['id']."'  order by date(date_due) asc limit 1 $offset ")->fetch_assoc()['date_due'];
                                        endif;
                                        $sum_paid = 0;
                                        while($p = $payments->fetch_assoc()){
                                            $sum_paid += ($p['paid'] - $p['penalty_amount']);
                                        }
                                        if($row['status'] == 2){
                                        $add = (date('Ymd',strtotime($next)) < date("Ymd") ) ?  $penalty : 0;
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $i++ ?>
                                        </td>
                                        <td>
                                            <p><b><?php echo $plan_arr[$row['plan_id']]['plan'] ?></b></p>
                                        <p>Remaining Amount: <b><?php echo number_format($row['amount'],2) ?></b></p>
                                        <p>Reference No. <b><?php echo $row['ref_no'] ?></b></p>
                                        </td>
                                        <td>
                                            <?php if($row['status'] == 2 ): ?>
                                            <p>Date: <b>
                                            <?php echo date('M d, Y',strtotime($next)); ?>
                                            </b></p>
                                            <?php
                                            $date1 = new DateTime(date("F d, Y" ,strtotime($next)));
                                            $fifth = $date1->format('d');
                                            if($fifth > 16){
                                                echo "<p>Amount: <b>", number_format($row['amount'] * $plan_arr[$row['plan_id']]['interest_percentage']/100 + $five,2), "</b></p>";
                                                if($add){
                                                    echo "<p>Penalty: <span style='color: red;'><b>",$add = (date('Ymd',strtotime($next)) < date("Ymd") ) ?  $penalty : 0; "</b></span></p>";
                                                }
                                                else{
                                                    $penalty = 0;
                                                }
                                            }else{
                                                if($add){
                                                    echo "<p>Penalty: <span style='color: red;'><b>",$add = (date('Ymd',strtotime($next)) < date("Ymd") ) ?  $penalty : 0; "</b></span></p>";
                                                }else{
                                                    $penalty = 0;
                                                }
                                                echo "<p>Amount: <b>",number_format($five + $add,2),"</b></p>";
                                            }
                                            ?>
                                            <?php else: ?>
                                                N/a
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($row['status'] == 0): ?>
                                                <span class="badge bg-warning text-dark">For Approval</span>
                                            <?php elseif($row['status'] == 1): ?>
                                                <span class="badge bg-info text-dark">Approved</span>
                                            <?php elseif($row['status'] == 2): ?>
                                                <span class="badge bg-primary">For Payments</span>
                                                <p>Date: <b><?php echo date("F j, Y, g:i a", strtotime($row['date_created']))?></b></p>
                                            <?php elseif($row['status'] == 3): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php elseif($row['status'] == 4): ?>
                                                <span class="badge bg-danger">Denied</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>    
                </div>    
            </div>
                <!--
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" style="float: left;" type="button">Save Profile</button>
                </div>
                --->
            </div>
        </div>
    </div>
</div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">New Loan</h5>
      </div>
      <div class="modal-body">
          <?php include 'user_loan.php' ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm " >Save</button>
        <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary btn-sm" href="javascript:toggleFormElements(false);">Edit</a>
      </div>
      </div>
    </div>
  </div>