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
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">

    <title>My Apps</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lineicons/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <!-- CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


    <script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    ul.sidebar-menu li a.active,
    ul.sidebar-menu li a:hover,
    ul.sidebar-menu li a:focus {
        background: #D46D16;
    }

    ul.sidebar-menu li ul.sub li {
        background: #633819;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        var userId = '<?php echo $this->session->userdata('userIdMyApps'); ?>';
        $.post('<?php echo base_url("myapps/cekShowMenuMyApps"); ?>', {
                userId: userId
            },
            function(data) {
                $.each(data, function(key, value) {
                    if (value.name_apps == "Surat") {
                        $("#idSurat").show();
                    }
                    if (value.name_apps == "Mail & Invoice") {
                        $("#idMailInvoice").show();
                    }
                    if (value.name_apps == "Status Cuti") {
                        $("#idStsCuti").show();
                        $("#idSubCuti").show();
                    }
                    if (value.name_apps == "Persetujuan Cuti") {
                        $("#idCuti").show();
                        $("#idSubCuti").show();
                    }
                    if (value.name_apps == "Pengajuan Ijin") {
                        $("#idStsIjin").show();
                        $("#idSubIjin").show();
                    }
                    if (value.name_apps == "Persetujuan Ijin") {
                        $("#idPrstjnIjin").show();
                        $("#idSubIjin").show();
                    }
                    if (value.name_apps == "Vessel Tracking") {
                        $("#idVesselTrack").show();
                        $("#idSubSO").show();
                    }
                    if (value.name_apps == "Voyage Estimate") {
                        $("#idVoyEst").show();
                        $("#idSubCommercial").show();
                    }
                    if (value.name_apps == "Statistik") {
                        $("#idByTrip").show();
                        $("#idSubStatistik").show();
                    }
                    if (value.name_apps == "Survey Customer") {
                        $("#idReportSurvey").show();
                        $("#idSubSurveyCust").show();
                    }
                    if (value.name_apps == "Form IT Request") {
                        $("#idFormITReq").show();
                    }
                });
            },
            "json"
        );
    });
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });
    </script>
</head>

<body>
    <section id="container">
        <header class="header black-bg" style="background-color:#D46D16;border-bottom:1px solid #e7e7e7"
            id="idHeaderNya">
            <div class="sidebar-toggle-box" style="color:#fefefe;">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Menu"></div>
            </div>
            <!--logo start-->
            <a href="" class="logo"><b>ANDHIKA GROUP</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu"></div>
        </header>
        <aside>
            <div id="sidebar" class="nav-collapse" style="background-color:#545454;">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    <p class="centered"><a>
                            <?php
            if($this->session->userdata('jnsKelamin') == '1'){ $jnsKlm = "iconMan.jpg"; } else { $jnsKlm = "iconWomen.jpg";}
          ?>
                            <img src="<?php echo base_url("assets/img/".$jnsKlm); ?>" class="img-circle" width="60">
                        </a></p>
                    <h5 class="centered"><?php echo $this->session->userdata('fullNameMyApps'); ?></h5>
                    <li class="sub-menu">
                        <a href="javascript:;" id="idMyApps">
                            <i class="fa fa-tasks"></i>
                            <span>My Apps</span>
                        </a>
                        <ul class="sub">
                            <li id="idSurat" style="padding-left:25px;display:none;"><a
                                    href="<?php echo base_url('myLetter/'); ?>"><i class="fa fa-inbox"></i> Surat</a>
                            </li>
                        </ul>
                        <ul class="sub">
                            <li id="idMailInvoice" style="padding-left:25px;display:none;"><a
                                    href="<?php echo base_url('myapps/getMailRegInv'); ?>"><i
                                        class="fa fa-envelope-o"></i> Mail & Invoice</a></li>
                        </ul>
                        <ul class="sub">
                            <li id="idFormITReq" style="padding-left:25px;display:none;"><a
                                    href="<?php echo base_url('form/getDataForm'); ?>"><i class='fa fa-file'></i>
                                    Form IT Request</a></li>
                        </ul>
                        <ul class="sub-menu" style="padding-left: 5px;width: 100%;">
                            <li class="sub-menu" style="margin-right: 0px;display:none;" id="idSubCuti">
                                <a href="javascript:;" id="idMyAppsCuti">
                                    <i class="fa fa-briefcase"></i><span>Cuti</span>
                                </a>
                                <ul class="sub">
                                    <li id="idCuti" style="display:none;"><a
                                            href="<?php echo base_url('cuti/getCuti'); ?>">Persetujuan Cuti</a></li>
                                </ul>
                                <ul class="sub">
                                    <li id="idStsCuti" style="display:none;"><a
                                            href="<?php echo base_url('cuti/getHistory'); ?>">Status Cuti</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="sub-menu" style="padding-left: 5px;width: 100%;">
                            <li class="sub-menu" style="margin-right: 0px;display:none;" id="idSubIjin">
                                <a href="javascript:;" id="idMyAppsCuti">
                                    <i class="fa fa-briefcase"></i><span>Ijin</span>
                                </a>
                                <ul class="sub">
                                    <li id="idStsIjin" style="display:none;"><a
                                            href="<?php echo base_url('ijin/requestIjin'); ?>">Pengajuan Ijin</a></li>
                                </ul>
                                <ul class="sub">
                                    <li id="idPrstjnIjin" style="display:none;"><a
                                            href="<?php echo base_url('ijin/getApproveIjin'); ?>">Persetujuan Ijin</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="sub-menu" style="padding-left: 5px;width: 100%;">
                            <li class="sub-menu" style="margin-right: 0px;display:none;" id="idSubSO">
                                <a href="javascript:;" id="idMyAppsVessel">
                                    <i class="fa fa-anchor"></i><span>Ship Operation</span>
                                </a>
                                <ul class="sub">
                                    <li id="idVesselTrack" style="display:none;"><a
                                            href="<?php echo base_url('shipOperation'); ?>">Vessel Tracking</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="sub-menu" style="padding-left: 5px;width: 100%;">
                            <li class="sub-menu" style="margin-right: 0px;display:none;" id="idSubCommercial">
                                <a href="javascript:;" id="idMyAppsCommercial">
                                    <i class="fa fa-building-o"></i><span>Ship Commercial</span>
                                </a>
                                <ul class="sub">
                                    <li id="idVoyEst" style="display:none;"><a
                                            href="<?php echo base_url('shipCommercial/getVoyageEst'); ?>">Voyage
                                            Estimator</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="sub-menu" style="padding-left: 5px;width: 100%;">
                            <li class="sub-menu" style="margin-right: 0px;display:none;" id="idSubSurveyCust">
                                <a href="javascript:;" id="idMyAppsSurveyCust">
                                    <i class="fa fa-history"></i><span>Survey Customer</span>
                                </a>
                                <ul class="sub">
                                    <li id="idReportSurvey" style="display:none;"><a
                                            href="<?php echo base_url('surveyCustomer/getDataSurvey'); ?>">Surveyed
                                            List</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="sub-menu" style="padding-left: 5px;width: 100%;">
                            <li class="sub-menu" style="margin-right: 0px;display:none;" id="idSubStatistik">
                                <a href="javascript:;" id="idMyAppsStatistik">
                                    <i class="fa fa-bar-chart-o"></i><span>Statistik</span>
                                </a>
                                <ul class="sub">
                                    <li id="idByTrip" style="display:none;"><a
                                            href="<?php echo base_url('statistik/getByTrip'); ?>">By Trip</a></li>
                                </ul>
                            </li>
                        </ul>
                        <!-- <ul class="sub">
              <li id="idCuti"><a href="<?php echo base_url('myapps/getCuti'); ?>">Info. Cuti</a></li>
            </ul> -->
                    </li>
                    <?php if($this->session->userdata('userTypeMyApps') == "admin" ){ ?>
                    <li class="sub-menu">
                        <a href="javascript:;" id="idSetting">
                            <i class="fa fa-cogs"></i>
                            <span>Setting</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url('myapps/userSetting'); ?>">User Apps</a></li>
                            <li><a href="<?php echo base_url('myapps/userDivSetting'); ?>">Custom Divisi</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li>
                        <a class="logout" href="<?php echo base_url('myapps/logout'); ?>">
                            <i class="fa fa-lock"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
</body>

</html>

<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.dcjqaccordion.2.7.js" class="include" type="text/javascript">
</script>
<script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--common script for all pages-->
<script src="<?php echo base_url();?>assets/js/common-scripts.js"></script>