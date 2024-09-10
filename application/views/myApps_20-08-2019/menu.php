<?php
	if(!$this->session->userdata('userIdMyApps'))
	{
		redirect(base_url("myapps"));
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">

  <title>My Apps</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css"/>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lineicons/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-responsive.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
  <script src="<?php echo base_url();?>assets/js/jquery.js"></script>
  <script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
   <style type="text/css">
     ul.sidebar-menu li a.active, ul.sidebar-menu li a:hover, ul.sidebar-menu li a:focus {
        background: #D46D16;
    }
    ul.sidebar-menu li ul.sub li{
        background: #633819;
    }
  </style>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#idMailInvoice").hide();
      $("#idCuti").hide();

      var userId = '<?php echo $this->session->userdata('userIdMyApps'); ?>';
      $.post('<?php echo base_url("myapps/cekShowMenuMyApps"); ?>',
      { userId : userId },
        function(data) 
        { 
          $.each( data, function( key, value ) {
            if(value.name_apps == "Mail & Invoice"){ $("#idMailInvoice").show(); }
            if(value.name_apps == "Info. Cuti"){ $("#idCuti").show(); }
          });
        },
      "json"
      );

    });
  </script>
</head>
<body>
<section id="container">
  <header class="header black-bg" style="background-color:#D46D16;border-bottom:1px solid #e7e7e7" >
    <div class="sidebar-toggle-box" style="color:#fefefe;" >
      <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Menu"></div>
    </div>
    <!--logo start-->
      <a href="" class="logo"><b>PT. ANDHIKA GROUP</b></a>
    <!--logo end-->
    <div class="nav notify-row" id="top_menu"></div>
  </header>
	<aside>
    <div id="sidebar"  class="nav-collapse" style="background-color:#545454;" >
      <!-- sidebar menu start-->
      <ul class="sidebar-menu" id="nav-accordion">
        <p class="centered"><a ><img src="<?php echo base_url(); ?>assets/img/fr-05.jpg" class="img-circle" width="60"></a></p>
        <h5 class="centered"><?php echo $this->session->userdata('fullNameMyApps'); ?></h5>
        <li class="sub-menu">
            <a href="javascript:;" id="idMyApps">
              <i class="fa fa-eye"></i>
              <span>My Apps</span>
            </a>
            <ul class="sub">
              <li id="idMailInvoice"><a href="<?php echo base_url('myapps/getMailRegInv'); ?>">Mail & Invoice</a></li>
            </ul>
            <ul class="sub">
              <li id="idCuti"><a href="<?php echo base_url('myapps/getCuti'); ?>">Info. Cuti</a></li>
            </ul>
        </li>
        <?php if($this->session->userdata('userTypeMyApps') == "admin" ){ ?>
        <li class="sub-menu">
            <a href="javascript:;" id="idSetting">
              <i class="fa fa-cogs"></i>
              <span>Setting</span>
            </a>
            <ul class="sub">
              <li><a href="<?php echo base_url('myapps/userSetting'); ?>">User Apps</a></li>
            </ul>
        </li>
        <?php } ?>
				<li>
				  <a class="logout" href="<?php echo base_url('myapps/logout'); ?>" >
					 <i class="fa fa-lock"></i>
					   <span>Logout</span>
				  </a>
			 </li>
      </ul>
      <!-- sidebar menu end-->
		</div>
  </aside>
</section>
</body>
</html>

<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.dcjqaccordion.2.7.js" class="include" type="text/javascript" ></script>
<script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--common script for all pages-->
<script src="<?php echo base_url();?>assets/js/common-scripts.js"></script>
    
	
	
