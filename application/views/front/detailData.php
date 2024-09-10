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
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">

  <title>PT.ADNYANA</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css"/>
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
    <div class="row" style="background-color:#7192AF;border-bottom:1px solid #e7e7e7;" align="center">
        <label style="color: #FFF; font-size: 24px;padding: 10px;"><b>PT.ADNYANA</b></label>
    </div>
    <div class="container" style="background-color: #FFF;">
      <fieldset>
        <legend style="padding: 10px;"> <i class="fa fa-angle-right"></i> DETAIL DATA OBSERVASI</legend>
          <div class="row" style="padding: 0px 10px 10px 10px;">
            <div class="col-md-2 col-xs-12"><label>Tgl Observasi :</label></div>
            <div class="col-md-10 col-xs-12"><?php echo $tgl_observasi; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Kategori Observasi :</div>
            <div class="col-md-10 col-xs-12"><?php echo ucwords($jns_observasi); ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Nama Kapal :</div>
            <div class="col-md-10 col-xs-12"><?php echo $namaKapal; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Detail Lokasi Observasi :</div>
            <div class="col-md-10 col-xs-12"><?php echo $detail_Lokasi_observasi; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Nama Pengamat :</div>
            <div class="col-md-10 col-xs-12"><?php echo $nama_pengamat; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Jabatan :</div>
            <div class="col-md-10 col-xs-12"><?php echo $namaJabatan; ?></div>
          </div>
		  <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Catatan Detail Observasi :</div>
            <div class="col-md-10 col-xs-12"><?php echo $catatan_detail; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Dampak yang Mungkin akan terjadi :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dampak; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Tindakan Perbaikan & Pencegahan :</div>
            <div class="col-md-10 col-xs-12"><?php echo $tindakan; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Alat Pelindung Diri :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dtPerDiri; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Alat Kerja & Peralatan :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dtAlatKer; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Lingkungan Kerja :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dtLingKer; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Posisi Kerja :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dtPosKer; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Ergonomik :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dtErgonomik; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Sistem Kerja :</div>
            <div class="col-md-10 col-xs-12"><?php echo $dtSisKer; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Lainnya :</div>
            <div class="col-md-10 col-xs-12"><?php echo $lainNya; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-12 col-xs-12" align="center">
              <button type="button" onclick="window.close();" id="btnSearch" class="btn btn-danger btn-sm" title="Add">
                <i class="fa fa-ban"> </i> CLOSE
              </button>
            </div>
          </div>
       </fieldset>
    </div>
</body>
</html>
<script src="<?php echo base_url();?>assets/js/jquery.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--common script for all pages-->
<script src="<?php echo base_url();?>assets/js/common-scripts.js"></script>
    
	
	
