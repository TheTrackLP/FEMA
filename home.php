<?php include 'db_connect.php' ?>
<style>
    .card-body{
        padding: 5px 5px;
        margin: 5px;
        font-size: 30px;
    }
   
</style>

<div class="container-fluid">

	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>

	<div class="row mt-3 ml-3 mr-3">
			<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
						<h1>Admin Dashboard</h1>			
				</div>
				<hr>
				<div class="row ml-2 mr-2">
                <div class="col-md-6">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">New Pending Borrowers</div>
                                        <div class="text-lg font-weight-bold">
                                        	<?php 
                                        	$borrowers = $conn->query("SELECT * FROM borrowers WHERE stat = 'New'");
                                        	echo $borrowers->num_rows > 0 ? $borrowers->num_rows : "0";
                                        	 ?>
                                        		
                                    	</div>
                                    </div>
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Existing Borrowers</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $borrowers = $conn->query("SELECT * FROM borrowers WHERE stat = 'Existing'");
                                            echo $borrowers->num_rows > 0 ? $borrowers->num_rows : "0";
                                             ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                            </div>
                        </div>
                    </div>

                  <div class="col-md-6">
                        <div class="card bg-warning text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Active Loans</div>
                                        <div class="text-lg font-weight-bold">
                                        	<?php 
                                        	$loans = $conn->query("SELECT * FROM loan_list where status = 2");
                                        	echo $loans->num_rows > 0 ? $loans->num_rows : "0";
                                        	 ?>
                                        		
                                    	</div>
                                    </div>
                                    <i class="fa fa-user-friends"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-danger text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Payments Today</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $payments = $conn->query("SELECT sum(paid) as total FROM payments where date(date_created) = '".date("Y-m-d")."'");
                                            echo $payments->num_rows > 0 ? number_format($payments->fetch_array()['total'],2) : "0.00";
                                             ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
<script>
	
</script>