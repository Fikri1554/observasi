<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
		});
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<div class="form-panel" id="idDataTable">
					<div class="row mt" id="idData1">
						<center>
							<h3>WELCOME, <i><?php echo $this->session->userdata('fullNameMyApps'); ?>..!!</i></h3>
						</center>
					</div>
				</div>
			</section>
		</section>
	</section>
</body>
</html>

