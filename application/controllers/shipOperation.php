<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ShipOperation extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->model('myapp');
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{
		$dataOut = array();
		$dataOut['vessel'] = $this->getVessel("slcOpt");
		$this->load->view('myApps/vesselTrack',$dataOut);
	}	
	function getVesselTracking()
	{
		$dataOut = array();
		$data = $_POST;
		$whereNya = " WHERE vessel like '%".$data['sVessel']."%' AND date_position between '".$data['sDate']." 00:00:00' AND '".$data['eDate']." 23:59:59' AND delete_sts = '0' AND LENGTH(latitude) >= '4' ";

		$sql = " SELECT * FROM data_vessel ".$whereNya." ORDER BY date_position ASC ";
		$dataOut['dataLoc'] = $this->myapp->getDataQueryDb2($sql);

		$sqlCheck = "SELECT color,image,image_2 FROM mst_vesselview WHERE vessel_init LIKE '%".$data['sVessel']."%'";
		$dataVsl = $this->myapp->getDataQueryDb2($sqlCheck);

		foreach ($dataVsl as $key => $val)
		{
			$dataOut['color'] = $val->color;
			$dataOut['image'] = $val->image;
			$dataOut['image_2'] = $val->image_2;
		}

		print json_encode($dataOut);
	}
	function getVessel($typeData = "")
	{
		$dataOpt = "";
		$sql = "SELECT * FROM mst_vesselview ORDER BY vessel ASC";
		$data = $this->myapp->getDataQueryDb2($sql);
		if($typeData == "slcOpt")
		{
			foreach ($data as $key => $value)
			{
				$dataOpt .= "<option value=\"".$value->vessel_init."\">".$value->vessel."</option>";
			}
			return $dataOpt;
		}else{
			return $data;
		}		
	}








}