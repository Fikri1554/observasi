<?php 
	if(!$this->session->userdata('userId'))
	{
		redirect(base_url());
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
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />-->
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">

    <title>PT. ADNYANA</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lineicons/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <section id="container">
        <header class="header black-bg" style="background-color:#7192AF;border-bottom:1px solid #e7e7e7">
            <div class="sidebar-toggle-box" style="color:#fefefe;">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Menu"></div>
            </div>
            <!--logo start-->
            <a href="" class="logo"><b>PT. ADNYANA</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu"></div>
        </header>
        <aside>
            <div id="sidebar" class="nav-collapse" style="background-color:#545454;">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    <p class="centered"><a><img src="<?php echo base_url(); ?>assets/img/andhika.gif" class="img-circle"
                                width="60"></a></p>
                    <h5 class="centered"><?php echo $this->session->userdata('fullName'); ?></h5>
                    <li class="mt">
                        <a id="idObservasi" href="<?php echo base_url('front/observasi'); ?>">
                            <i class="fa fa-eye"></i>
                            <span>Observasi</span>
                        </a>
                    </li>
                    <li>
                        <a id="idProsedure" href="<?php echo base_url('prosedure/'); ?>">
                            <i class="glyphicon glyphicon-file"></i>
                            <span>Prosedur</span>
                        </a>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;" id="idSetting">
                            <i class="fa fa-cogs"></i>
                            <span>Setting</span>
                        </a>
                        <?php 
              $usrType = $this->session->userdata('userType');
              if($usrType == "admin")
              {
            ?>
                        <ul class="sub">
                            <li><a href="<?php echo base_url('setting/'); ?>">User</a></li>
                        </ul>
                        <?php 
              }
            ?>
                        <ul class="sub">
                            <li><a href="<?php echo base_url('setting/getChangePass'); ?>">Change Password</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="logout" href="<?php echo base_url('front/logout'); ?>">
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
<!--<script src="<?php echo base_url();?>assets/js/jquery.js"></script>-->
<script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.dcjqaccordion.2.7.js">
</script>
<script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--common script for all pages-->
<script src="<?php echo base_url();?>assets/js/common-scripts.js"></script>