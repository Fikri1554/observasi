<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link href="<?php echo base_url();?>asset/flexgrid/css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>asset/flexgrid/css/flexigrid.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url();?>asset/flexgrid/js/flexigrid.pack.js"></script>
    
	<script type="text/javascript">
		function data(com,grid) 
		{
			if (com == 'Refresh')
			{
				window.location = "<?=site_url("/administrator/dataList/")  ;?>";
			}
			if(com == 'Add')
			{
				$('.col-lg-6').empty();
				$('.col-lg-6').load('<?=site_url("/administrator/addKaryawan/add");?>');
			}								
		}
		
		function editData(id)
		{
			$('.col-lg-6').load('<?=site_url('/administrator/addKaryawan/edit');?>', function(){
			
				$.post('<?php echo base_url('administrator/editKaryawan'); ?>',
				{ id : id },
					function(data) 
					{	
						$.each(data, function(i, item) 
						{
							var html = '<input type="hidden" value="'+item.id+'" name="id" class="idEdit"/>';
							$('.panel-body').append(html);
							$("#nama").val(item.nama);
							$("#alamat").val(item.alamat);
							$("#jenis_kelamin").val(item.jenis_kelamin);
							$("#tgl").val(item.tgl);
							$("#bln").val(item.bln);
							$("#thn").val(item.thn);
						})
					},
				"json"
				);
			});
		}
	</script>
   	<style type="text/css">
		.flexigrid div.fbutton .add{
			background: url(<?php echo base_url();?>asset/flexgrid/images/add.png) no-repeat center left;
		}	
		
		.flexigrid div.fbutton .refresh{
			background: url(<?php echo base_url();?>asset/flexgrid/images/refresh.png) no-repeat center left;
		}			
    </style>
</head>

<body>
<div id="page-wrapper">
	<fieldset>
    	<legend>Data Karyawan</legend>
        	<div class="col-lg-6" style="width:100%;padding:0px;">
            	<div class="panel-body" style="padding:0px;">
                	<div class="table-responsive">
                        <?php
							echo $js_grid;
						?>
                        <table id="data" style="display:none"></table>
                   	</div>
            	</div>
        	</div>
    </fieldset>
</div>
</body>
</html>
