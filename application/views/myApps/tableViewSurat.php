<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css"/>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lineicons/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-responsive.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
  <!--<script src="<?php echo base_url();?>assets/js/jquery.js"></script>-->
  <script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#btnClose").click(function(){
            window.close();
        });
    });
  </script>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12" style="background-color:#7192AF;">
        <h3 style="color:#FFF;">ANDHIKA GROUP</h3>
      </div>
    </div>
    <h4><i class="fa fa-angle-right"></i> Informasi / Information</h4>
    <div class="form-panel">
      <div class="row">
        <div class="col-md-2 col-xs-12">
          <label style="font-size:13px;">Perusahaan / Company :</label>
        </div>
        <div class="col-md-10 col-xs-12">
          <b style="font-size:14px;"><?php echo $data[0]->nmcmp; ?></b>
        </div>
      </div>
      <div class="row" style="margin-top:15px;">
        <div class="col-md-2 col-xs-12">
          <label style="font-size:13px;">Tgl. Surat / Date :</label>
        </div>
        <div class="col-md-10 col-xs-12">
          <b><?php echo $tglSurat; ?></b>
        </div>
      </div>
      <div class="row" style="margin-top:15px;">
        <div class="col-md-2 col-xs-12">
          <label style="font-size:13px;">No. Surat / Reference :</label>
        </div>
        <div class="col-md-10 col-xs-12">
          <b><?php echo $data[0]->nosurat; ?></b>
        </div>
      </div>
      <div class="row" style="margin-top:15px;">
        <div class="col-md-2 col-xs-12">
          <label style="font-size:13px;">Penerima / Addressed to :</label>
        </div>
        <div class="col-md-10 col-xs-12">
          <b><?php echo $data[0]->address; ?></b>
        </div>
      </div>
      <div class="row" style="margin-top:15px;">
        <div class="col-md-2 col-xs-12">
          <label style="font-size:13px;">Keterangan / Subject :</label>
        </div>
        <div class="col-md-10 col-xs-12">
          <b><?php echo $data[0]->ket; ?></b>
        </div>
      </div>
      <div class="row" style="margin-top:15px;">
        <div class="col-md-2 col-xs-12">
          <label style="font-size:13px;">Pembuat / PIC :</label>
        </div>
        <div class="col-md-10 col-xs-12">
          <b><?php echo $data[0]->createdby; ?></b>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" align="center" style="margin-top:10px;">
          <button id="btnClose" class="btn btn-danger btn-xs" name="btnClose" title="Close">
            <i class="fa fa-ban"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

