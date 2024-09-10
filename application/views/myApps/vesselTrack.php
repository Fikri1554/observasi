<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
  	<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.label/leaflet.label.css"/>
  	<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
  	<script src="http://leaflet.github.io/Leaflet.label/leaflet.label.js"></script> -->

  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/leaflet/leaflet.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/leaflet/leafletLabel.css" />
  	<script src="<?php echo base_url(); ?>assets/leaflet/leaflet.js"></script>
  	<script src="<?php echo base_url(); ?>assets/leaflet/leafletLabel.js"></script>

	<script type="text/javascript">
		var markers;
		$(document).ready(function(){
			$("#idLoading").hide();
			$( "#txtStartDate" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $( "#txtEndDate" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $("#btnSearch").click(function(){
		    	var sVessel,sDate,eDate = "";
		    	var cekData = 0;
		    	$("#idLoading").show();
		    	removeLine();

		    	sVessel = $("#slcStatus").val();
		    	sDate = $("#txtStartDate").val();
		    	eDate = $("#txtEndDate").val();

		    	if(sVessel == "")
		    	{
		    		alert("Vessel Empty..!!");
					$("#idLoading").hide();
		    		return false;
		    	}
				if(sDate == "" || eDate == "")
		    	{
		    		alert("Start Date / End Date Empty..!!");
		    		$("#idLoading").hide();
		    		return false;
		    	}
		    	$.post('<?php echo base_url("shipOperation/getVesselTracking"); ?>',
				{   
					sVessel : sVessel,sDate : sDate,eDate : eDate
				},
					function(data) 
					{
						var jmlData = data.dataLoc.length;
				      	var arr = [];
				       	$.each(data.dataLoc, function (key, val) {
				       		cekData++;
				       		arr.push(new L.LatLng(val.latitude, val.longitude));
				       		if(cekData == jmlData)
				       		{				       			
				       			viewPoint(data.image,data.image_2,val.latitude,val.longitude);
				       			map.panTo(new L.LatLng(val.latitude, val.longitude));
				       		}
						});

				        var options ={color: data.color, weight: 3,opacity: 0.5, smoothFactor: 1};
				        var polyline = new L.Polyline(arr, options);
				        polyline.addTo(map);
				        $("#idLoading").hide();
					},
				"json"
				);
		    });
		});
		function viewPoint(icon1,icon2,lat,longs)
  		{
  			var icon_1,icon_2 = '';

      		icon_1 = '<?php echo base_url(); ?>assets/img/icon/'+icon1;
  			icon_2 = '<?php echo base_url(); ?>assets/img/icon/'+icon2;
      		
  			var iconNya = new L.Icon({
			    iconUrl: icon_1,
			    shadowUrl: icon_2,
			    className: 'blinking',
			    iconSize: [25, 41],
			    iconAnchor: [12, 41],
			    popupAnchor: [1, -34],
			    shadowSize: [41, 41]
			  });
  			markers = new L.marker([lat,longs], {icon: iconNya}).addTo(map);  			
  		}
  		function removeLine()
  		{
  			for(i in map._layers)
  			{
		        if(map._layers[i]._path != undefined)
		        {
		        	try {
		                map.removeLayer(map._layers[i]);
		            }
		            catch(e) {
		                console.log("problem with " + e + map._layers[i]);
		            }
		        }
	   		}
	   		if (markers)
	   		{
				map.removeLayer(markers);
			}
  		}
		function reloadPage()
		{
			window.location = "<?php base_url('shipOperation');?>";
		}
	</script>
	<style type="text/css">
		#mapid{
			width: 100%%;
		    height: 480px;
		}  
		.leaflet-control-attribution {
		    display: none;
		}
		.leaflet-control-layers-selector {
		    display: none;
		}
		@keyframes fade {
		    from { opacity: 0; } 
		  }
		.ui-widget{ font-size: 12px; }
	  	.ui-datepicker{ width: 232px; }
		.blinking { animation: fade 0.9s infinite alternate; }
	</style>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Vessel Tracking<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" style="padding: 15px;">
					<div class="row">
						<div class="col-md-12 col-xs-12" id="mapid"></div>
						<div id="idFormSearch">
							<dir class="col-md-2 col-xs-12">
								<select name="slcStatus" id="slcStatus" class="form-control input-sm">
									<option value="">- Select Vessel -</option>
									<?php echo $vessel; ?>
								</select>
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="Start Date" type="text" class="form-control input-sm" id="txtStartDate" name="txtStartDate" value="">
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="End Date" type="text" class="form-control input-sm" id="txtEndDate" name="txtEndDate" value="">
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="submit" style="width: 100%;" id="btnSearch" class="btn btn-primary btn-sm" title="Search"><i class="fa fa-search"></i> Search</button>
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" style="width: 100%;" id="btnCancelSearch" onclick="reloadPage();" class="btn btn-success btn-sm" title="Refresh"><i class="fa fa-refresh"></i> Refresh</button>
							</dir>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="col-md-2 col-xs-12">
								<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-yellow.png">&nbsp ATHALIA</img>
							</div>
							<div class="col-md-2 col-xs-12">
								<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-black.png">&nbsp BULK BATAVIA</img>
							</div>
							<div class="col-md-2 col-xs-12">
								<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-green.png">&nbsp KANISHKA</img>
							</div>
							<div class="col-md-2 col-xs-12">
								<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-blue.png">&nbsp NARESWARI</img>
							</div>
				     		<div class="col-md-2 col-xs-12">
				     			<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-orange.png">&nbsp PARAMESTI</img>
				     		</div>
				     		<!--<div class="col-md-2 col-xs-12">
				     			<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-violet.png">&nbsp VENTURA</img>
				     		</div>-->
				     		<div class="col-md-2 col-xs-12">
				     			<img style="width:15px;" src="<?php echo base_url(); ?>assets/img/icon/marker-icon-red.png">&nbsp VIDYANATA</img>
				     		</div>				     		
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
</body>
</html>
<script type="text/javascript">
	var map = L.map('mapid').setView([-1.0463888888889,117.2075], 5);
  			  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: ''}).addTo(map);
</script>

