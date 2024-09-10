<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prosedure extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();		
		$this->load->model('observasi');
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{
		$this->getData();
	}

	function getData($searchNya = "")
	{
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$idVesselLogin = $this->session->userdata('idVesselLogin');
		$userType = $this->session->userdata('userType');
		$userId = $this->session->userdata('userId');
		$displayAdd = "";

		$whereNya = " WHERE A.st_delete = '0' ";

		if($userType != "admin")
		{
			if($userId == '192')//user si pika
			{
				$displayAdd = "";
			}else{
				$whereNya .= " AND A.id_vessel = '".$idVesselLogin."' ";
				$displayAdd = "display:none;";
			}		
		}

		if($searchNya != "")
		{
			$typeSearch = $_POST['slcType'];
			$txtSearch = $_POST['teks'];

			if($typeSearch == "subject")
			{
				$whereNya .= " AND A.subject_document LIKE '%".$txtSearch."%' ";
			}
			if($typeSearch == "vessel")
			{
				$whereNya .= " AND B.name LIKE '%".$txtSearch."%' ";
			}
		}

		$sql = "SELECT A.*,B.name as nameVessel 
				FROM prosedure A
				LEFT JOIN mst_vessel B ON B.id = A.id_vessel
				".$whereNya."
				ORDER BY date_document DESC";
		$rsl = $this->observasi->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" onclick=\"viewDoc('".$val->id."');\">View</button>";
			if($userType == "admin" || $userId == '192')
			{
				$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delDoc('".$val->id."');\">Delete</button>";
			}
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".$no."</td>";
				$trNya .= "<td align=\"center\">".$this->getMonth($val->date_document)."</td>";
				$trNya .= "<td>".$val->nameVessel."</td>";
				$trNya .= "<td>".$val->subject_document ."</td>";
				$trNya .= "<td>".$btnAct."</td>";
			$trNya .= "</tr>";

			$no ++;
		}

		$dataOut["displayAdd"] = $displayAdd;
		$dataOut["trNya"] = $trNya;

		if($searchNya == "")
		{			
			$dataOut["vessel"] = $this->getVessel();
			$this->load->view('front/prosedure',$dataOut);
		}else{
			print json_encode($dataOut["trNya"]);
		}
		
	}

	function saveDoc()
	{
		$data = $_POST;
		$status = "";
		$dataIns = array();
		$userId = $this->session->userdata('userId');
		$dateNow = date("Y-m-d");

		$dataIns["id_vessel"] = $data["vsl"];
		$dataIns["date_document"] = $data["dateDoc"];		
		$dataIns["subject_document"] = $data["sbjDoc"];
		$dataIns["detail_document"] = $data["detDoc"];
		$dataIns["add_userId"] = $userId;
		$dataIns["add_date"] = $dateNow;

		try {
			$this->db->insert("prosedure",$dataIns);
			$status = "Save Success..!!";
		} catch (Exception $ex) {
			$status = "Save Failed..!!";
		}

		print $status;
	}

	function viewDoc()
	{
		$dataOut =array();
		$data = $_POST;
		$idView = $data['id'];

		$sql = "SELECT A.*,B.name as nameVessel 
				FROM prosedure A
				LEFT JOIN mst_vessel B ON B.id = A.id_vessel
				WHERE A.st_delete = '0' AND A.id = '".$idView."'
				ORDER BY date_document DESC";

		$dataOut["detDoc"] = $this->observasi->getDataQuery($sql);
		$dataOut["dateDoc"] = $this->getMonth($dataOut["detDoc"][0]->date_document);
		$dataOut["btnClose"] = "<div class=\"col-md-12 col-xs-12\"><button class=\"btn btn-danger btn-xs btn-block\" onclick=\"reloadPage();\">Close</button></div>";

		print json_encode($dataOut);
	}

	function deldoc()
	{
		$status = "";
		$data = $_POST;
		$idDel = $data["id"];
		$dataDel = array();

		try {

			$dataDel['st_delete'] = '1';
			$whereNya = "id = '".$idDel."' ";

			$this->observasi->updateData($whereNya,$dataDel,"prosedure");

			$status = "Delete Succes..";
		} catch (Exception $ex) {
			$status = "Delete Failed..";
		}

		print json_encode($status);
	}

	function getVessel($id = "")
	{
		$dataOutVessel = "";
		$whereNya = "";
		if($id != "")
		{
			$whereNya = "id = '".$id."'";
		}
		$dataVessel = $this->observasi->getDataAll("mst_vessel",$whereNya);
		foreach ($dataVessel as $key => $value) 
		{
			$dataOutVessel .= "<option value=\"".$value->id."\">".$value->name."</option>";
		}
		return $dataOutVessel;
	}

	function getMonth($dateNya = "")
	{
		$dateConvert = "";
		$tgl = "";
		$bulan = "";
		$thn = "";

		$tgl = substr($dateNya, 8,2);
		$bulan = substr($dateNya, 5,2);
		$thn = substr($dateNya, 0,4);

		Switch ($bulan)
		{
		    case "01" : $bulan="Januari";
		        Break;
		    case "02" : $bulan="Februari";
		        Break;
		    case "03" : $bulan="Maret";
		        Break;
		    case "04" : $bulan="April";
		        Break;
		    case "05" : $bulan="Mei";
		        Break;
		    case "06" : $bulan="Juni";
		        Break;
		    case "07" : $bulan="Juli";
		        Break;
		    case "08" : $bulan="Agustus";
		        Break;
		    case "09" : $bulan="September";
		        Break;
		    case "10" : $bulan="Oktober";
		        Break;
		    case "11" : $bulan="November";
		        Break;
		    case "12" : $bulan="Desember";
		        Break;
		}
		$dateConvert = $tgl." ".$bulan." ".$thn;

		return $dateConvert;
	}

}