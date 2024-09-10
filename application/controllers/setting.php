<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CI_Controller {

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
		$dataOut["vessel"] = $this->getVessel();
		$dataOut["jabatan"] = $this->getJabatan();
		$userId = $this->session->userdata('userId');
		$dataTR = "";
		$whereNya = "a.sts_delete = '0'";

		if($searchNya != "")
		{
			if($_POST['txtSearch'] != "")
			{			
				$whereNya .= " AND a.full_name LIKE '%".$_POST['txtSearch']."%' ";
			}
			if($_POST['slcVsl'] != "")
			{
				if($whereNya == "")
				{
					$whereNya .= "a.vessel = '".$_POST['slcVsl']."' ";
				}else{
					$whereNya .= " AND a.vessel = '".$_POST['slcVsl']."' ";
				}
				
			}
		}
		$data = $this->observasi->getDataLogin($whereNya);
		$no = 1;
		foreach ($data as $key => $value) 
		{
			//echo $value->nama_pengamat."<br>";
			$fullName = $value->full_name;
			if($userId == "1" OR $userId == "104" OR $userId == "4"){ $fullName .= "<br>".base64_decode($value->password); }
			$dataTR .= "<tr>
							<td align=\"center\">".$no."</td>
							<td align=\"center\">".$value->id_name."</td>
							<td align=\"center\">".$fullName."</td>
							<td>".$value->username."</td>
							<td>".$value->email."</td>
							<td>".$value->namaJabatan."</td>
							<td>".$value->namaKapal."</td>
							<td>".$value->user_type."</td>
							<td align=\"center\">
								<button type=\"button\" id=\"btnEdit\" class=\"btn btn-warning btn-xs\" onclick=\"getEdit('".$value->id."');\" >Edit</button>
								<a class=\"btn btn-danger btn-xs\" onclick=\"return confirm('Are you sure want to delete..??')\" href=\"setting/delLogin/".$value->id."\">Delete
								</a>
							</td>
						</tr>";
			$no ++;
		}
		$dataOut["dataLogin"] = $dataTR;
		if($searchNya == "")
		{
			$this->load->view('front/user',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}
	function saveData()
	{
		$data = $_POST;
		$stData = "";
		$cekUser = "";
		$cekIdCrew = "";
		$idEdit = $data['idEdit'];
		$idName = $data['idName'];
		$dataIns['full_name'] = $data['fullName'];
		$dataIns['username'] = $data['user'];
		if($idEdit == "")
		{
			// $dataIns['password'] = md5($data['pass']);
			$dataIns['password'] = base64_encode($data['pass']);
		}else{
			if($data['pass'] != "")
			{
				// $dataIns['password'] = md5($data['pass']);
				$dataIns['password'] = base64_encode($data['pass']);
			}
		}
		$dataIns['user_type'] = $data['userType'];
		$dataIns['id_jabatan'] = $data['position'];
		$dataIns['vessel'] = $data['vessel'];
		$dataIns['id_name'] = $idName;
		$dataIns['export'] = $data['stBtn'];
		try {
			if ($idEdit == "")
			{
				$cekIdCrew = $this->cekDataCreateUser("id_name = '".$idName."' AND  sts_delete = '0'");
				$cekUser = $this->cekDataCreateUser("username = '".$data['user']."' AND  sts_delete = '0'");
				if(($cekUser == "" && $cekIdCrew == "")||$data['userType'] == "admin")
				{
					$this->db->insert("login",$dataIns);
					$stData = "";
				}
				else if($cekIdCrew != "")
				{
					$stData = "Crew Id Already..!!";
				}
				else if($cekUser != "")
				{
					$stData = "Username Already..!!";
				}
				
			}else{
				$whereNya = "id = '".$idEdit."'";
				$this->observasi->updateData($whereNya,$dataIns,"login");
				$stData = "";
			}
			
		} catch (Exception $ex) {
			$stData = "Failed =>".$ex;
		}
		print json_encode($stData);
	}
	function getDataEdit()
	{
		$idEdit = $_POST['id'];

		$dataOut['dataLogin'] = $this->observasi->getDataEdit("login","id = '".$idEdit."'");
		//print_r($dataOut['dataLogin'] );exit;
		print json_encode($dataOut);
	}
	function getJabatan($id = "")
	{
		$dataOut = "";
		$dataJabatan = $this->observasi->getDataJabatan("mst_jabatan");
		foreach ($dataJabatan as $key => $value) 
		{
			$dataOut .= "<option value=\"".$value->id."\">".$value->name."</option>";
		}
		return $dataOut;
	}
	function getVessel($id = "")
	{
		$dataOutVessel = "";
		$dataVessel = $this->observasi->getDataAll("mst_vessel");
		foreach ($dataVessel as $key => $value) 
		{
			$dataOutVessel .= "<option value=\"".$value->id."\">".$value->name."</option>";
		}
		return $dataOutVessel;
	}
	function delLogin($id)
	{
		$data['sts_delete'] = "1";

		$whereNya = "id = '".$id."'";
		$this->observasi->updateData($whereNya,$data,"login");

		redirect(base_url('setting/'));
	}
	// function cekUser($idName = "")
	// {
	// 	$stCek = "";

	// 	if ($idName != "") 
	// 	{
	// 		$whereNya = "id_name = '".$idName."' AND sts_delete = '0'";
	// 		$cekUser = $this->observasi->cekData($whereNya,"login");
	// 		if ($cekUser > 0)
	// 		{
	// 			$stCek = "ada";
	// 		}
	// 	}
	// 	return $stCek;
	// }
	function cekDataCreateUser($whereNya)
	{
		$stCek = "";
		$cekDataNya = $this->observasi->cekData($whereNya,"login");
		if ($cekDataNya > 0)
		{
			$stCek = "ada";
		}
		return $stCek;
	}
	function getChangePass()
	{
		$userId = $this->session->userdata('userId');
		$dataOut['userId'] = $userId;
		$this->load->view('front/changePass',$dataOut);
	}
	function updNewPass()
	{
		$data = $_POST;
		// $dataUpd['password'] = md5($data['newPass']);
		$dataUpd['password'] = base64_encode($data['newPass']);
		$stData = "";
		$whereNya = "id = '".$data['userId']."'";

		try {
			$this->observasi->updateData($whereNya,$dataUpd,"login");
			$stData = "success";
		} catch (Exception $ex) {
			$stData = "Failed =>".$ex;
		}
		print json_encode($stData);
	}
































}