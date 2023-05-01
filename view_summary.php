<div class="container-fluid py-1">
	<?php include 'report_summary.php' ?>
</div>
<div class="modal-footer row display py-1">
		<div class="col-lg-12">
			<button class="btn float-right btn-danger" type="button" data-dismiss="modal">Close</button>
			<button class="btn float-right btn-success mr-2" type="button" id="print">Print</button>
		</div>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: block
	}
	.border-gradien-alert{
		border-image: linear-gradient(to right, red , yellow) !important;
	}
	.border-alert th, 
	.border-alert td {
	  animation: blink 200ms infinite alternate;
	}

	@keyframes blink {
	  from {
	    border-color: white;
	  }
	  to {
	    border-color: red;
		background: #ff00002b;
	  }
	}
</style>
<script>
	$('#print').click(function(){
		start_load()
		var nw = window.open('rsummary.php?loan_id=<?php echo $_GET['loan_id'] ?>&id=<?php echo $_GET['id'] ?>',"_blank","width=900,height=600")
		setTimeout(function(){
			nw.print()
			setTimeout(function(){
				nw.close()
				end_load()
			},500)
		},500)
	});
</script>