<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <meta name="keyword" content="andhika line, andhika group, andhika, adnyana">
	
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">
    <title>SURVEY CUSTOMER</title>

    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style-responsive.css" rel="stylesheet">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	 <!-- js placed at the end of the document so the pages load faster-->
    <script src="<?php echo base_url();?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
		$("#btnNext").click(function(){
          var cmpny = "";
          var nmCust = "";
          var positionCust = "";
          var surveyType = "";
			
          cmpny = $("#txtCompany").val();
          nmCust = $("#txtNameCust").val();
          positionCust = $("#txtPositionCust").val();
          surveyType = $("#slcTypeSurvey").val();

          if(cmpny == ""){
            alert("Company Empty..!!");
            return false;
          }
          if(nmCust == ""){
            alert("Name Empty..!!");
            return false;
          }
          if(positionCust == ""){
            alert("Position Empty..!!");
            return false;
          }
		  
          $("#idLoading").show();
          $.post('<?php echo base_url("surveyCustomer/addGetQuestion")."/"; ?>',
          {   
            cmpny : cmpny,nmCust : nmCust,positionCust : positionCust,surveyType : surveyType
          },
            function(data) 
            {
              if(data.dataCek == "kosong")
              {
                $("#idBodySurvey").empty();
                $("#idHomeSurvey").hide(100);
                $("#idBodySurvey").append(data.trNya);
                $("#idCust").val(data.idCust);
                $("#idTxtV2").text(data.lblV2);
                $("#idChoiceSurvey").show(250);
              }else{
                alert("data Survey Sudah diisi..!!");
                return false;
              }
            },
            "json"
          );
        });

        $("#btnSave").click(function(){
          var idNya = "";
          var valNya = "";
          var v1 = "";
          var idCust = "";
          var otherPerform = "";
          var commentSuggestion = "";

          $("#idLoadingQuestion").show();
          idCust = $("#idCust").val();
          idNya = $("#idCustSurvey").val();
          otherPerform = $("#idOtherPerform").val();
          commentSuggestion = $("#idCommentSuggestion").val();
          var arr = idNya.split("^");
          var hslPelayanan = $("#slcPelayanan").val();

          $.each( arr, function( index, val ) {
            var grpQuestion = $("#idGrpLbl_"+val).val();
            var question = $("#idLbl_"+val).text();
            var remark = $("#idRemark_"+val).val();
            var impt = "";
            var asses = "";
              $.each($("input[id='v1_"+val+"']:checked"), function(){
                impt = $(this).val();
              });
              $.each($("input[id='v2_"+val+"']:checked"), function(){
                asses = $(this).val();
              });
              if(impt == "")
              {
                alert("Importance Rating Empty..!!");
				        abort();
                // return false;
              }
              if(asses == "")
              {
                alert($("#idTxtV2").text()+" Empty..!!");
				        abort();
                // return false;
              }
              if(valNya == "")
              {
                valNya = grpQuestion+"^"+question+"^"+impt+"^"+asses+"^"+remark+"^"+idCust;
              }else{
                valNya += "||"+grpQuestion+"^"+question+"^"+impt+"^"+asses+"^"+remark+"^"+idCust;
              }
          });
          $.post('<?php echo base_url("surveyCustomer/addSurveyQuestion"); ?>',
          {   
            valNya : valNya,otherPerform : otherPerform,commentSuggestion : commentSuggestion,hslPelayanan : hslPelayanan
          },
            function(data) 
            {
              alert(data);
              $("#idChoiceSurvey").hide();
              $("#idThanks").show(200);
            },
            "json"
          );
        });
      });
      
      function checkOnlyOne(id,name)
      {
        if($("input:checkbox[name='"+name+"']").is(":checked"))
        {
          $("input:checkbox[id='"+id+"']").attr("disabled", true);
          $("input:checkbox[name='"+name+"']").removeAttr("disabled");
        }else{
          $("input:checkbox[id='"+id+"']").removeAttr("disabled");
        }        
      }
    </script>
	
  </head>
  <body style="background-color:#9c9c9c;">
    <div class="row">
    <div style="background-color:#89000A;border-bottom:1px solid #89000A;" align="center">
        <label style="color: #FFF; font-size: 24px;padding: 10px;"><b>ANDHIKA GROUP</b></label>
    </div>
  </div>
    <div class="container" id="idHomeSurvey" style="background-color:#FFF;">
      <fieldset>
        <legend style="padding: 10px;"> 
            <i class="fa fa-angle-right"></i> SURVEY CUSTOMER SATISFACTION 
            <span style="padding-left:10px;display:none;" id="idLoading">
              <img src="<?php echo base_url('assets/img/loading.gif'); ?>">
            </span>
        </legend>
          <div class="row" style="padding: 0px 10px 10px 10px;">
            <div class="col-md-2 col-xs-12"><label>Survey Date:</label></div>
            <div class="col-md-10 col-xs-12"><?php echo $dateNow; ?></div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Company Name <label style="color:red;">*</label> :</div>
            <div class="col-md-5 col-xs-12">
              <input type="text" id="txtCompany" name="txtCompany" class="form-control" placeholder="Company" autofocus = "autofocus">
            </div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Respondent's Name <label style="color:red;">*</label> :</div>
            <div class="col-md-5 col-xs-12">
              <input type="text" id="txtNameCust" name="txtNameCust" class="form-control" placeholder="Name">
            </div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Respondent's Position <label style="color:red;">*</label> :</div>
            <div class="col-md-5 col-xs-12">
              <input type="text" id="txtPositionCust" name="txtPositionCust" class="form-control" placeholder="Position">
            </div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-2 col-xs-12">Survey Type :</div>
            <div class="col-md-5 col-xs-12">
              <select class="form-control" id="slcTypeSurvey" disabled="disabled">
                <?php
                  if($typeSurvey == "charter")
                  {
                    echo "<option value=\"charter\">Charter</option>";
                  }else if($typeSurvey == "owner")
                  {
                    echo "<option value=\"owner\">Owner</option>";
                  }else{
                    echo "<option value=\"others\">Other's</option>";
                  }
                ?>                
              </select>
            </div>
          </div>
          <div class="row" style="padding: 10px;">
            <div class="col-md-12 col-xs-12" align="center">
              <button type="button" id="btnNext" class="btn btn-danger btn-sm" title="Next">
                <i class="fa fa-check"> </i> Next
              </button>
            </div>
          </div>
          <center>
            <label style="padding-top: 50px;font-size: 11px;">Copyright @ <?php echo date("Y"); ?> Andhika Group</label>
          </center>
       </fieldset>
    </div>
    <div class="container-fluid" id="idChoiceSurvey" style="background-color: #FFF;display:none;">
      <div class="form-panel" id="idDataTable">
        <legend style="padding: 10px;"> <i class="fa fa-angle-right"></i> SURVEY CUSTOMER SATISFACTION
            <span style="padding-left:10px;display:none;" id="idLoadingQuestion"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
        </legend>
        <div class="table-responsive">
          <table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
            <thead>
              <tr style="background-color: #89000A;color: #FFF;">
                <th rowspan="2" colspan="2" style="width:25%;text-align:center;vertical-align:middle;">Criteria Of Customer Satisfaction</th>
                <th colspan="5" style="width:30%;text-align: center;">Importance Rating</th>
                <th colspan="5" style="width:30%;text-align: center;" id="idTxtV2"></th>
                <th rowspan="2" style="width:15%;text-align: center;vertical-align:middle;">Remark</th>
              </tr>
              <tr style="background-color: #89000A;color: #FFF;">
                <th style="text-align: center;">Unimportant</th>
                <th style="text-align: center;">Slightly</th>
                <th style="text-align: center;">Moderately</th>
                <th style="text-align: center;">Important</th>
                <th style="text-align: center;">Very Important</th>
                <th style="text-align: center;">Very Poor</th>
                <th style="text-align: center;">Poor</th>
                <th style="text-align: center;">Fair</th>
                <th style="text-align: center;">Good</th>
                <th style="text-align: center;">Excellent</th>
              </tr>
            </thead>
            <tbody id="idBodySurvey">
            </tbody>
          </table>
        </div>
        <div class="form-group" align="center">
          <input type="hidden" id="idCust" name="idCust" value="">
          <button id="btnSave" class="btn btn-info btn-sm" name="btnSave" title="Save">
              <i class="fa fa-check-square-o"></i> Save</button>
        </div>
        <center>
          <label style="font-size: 11px;">Copyright @ <?php echo date("Y"); ?> Andhika Group</label>
        </center>
      </div>
    </div>
    <div class="container" id="idThanks" style="background-color: #FFF;display:none;">
      <div class="row" style="padding: 0px 10px 10px 10px;">
        <div class="col-md-2 col-xs-12">
        </div>
        <div class="col-md-12 col-xs-12" align="right">
          <img src="<?php echo base_url(); ?>assets/img/thanks.jpg" style="width: 50%;">
        </div>
      </div>
    </div>
  </body>
</html>
