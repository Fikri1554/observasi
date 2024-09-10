<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class DisplayVessel extends CI_Controller {

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
	function getData()
	{
		$dataOut["vessel"] = $this->getVessel();
		$dataOut["jabatan"] = $this->getJabatan();
		$dataTR = "";

		$data = $this->observasi->getDataLogin();
		$no = 1;
		foreach ($data as $key => $value) 
		{
			//echo $value->nama_pengamat."<br>";
			$dataTR .= "<tr>
							<td align=\"center\">".$no."</td>
							<td align=\"center\">".$value->id_name."</td>
							<td align=\"center\">".$value->full_name."</td>
							<td>".$value->username."</td>
							<td>".base64_decode($value->password)."</td>
							<td>".$value->email."</td>
							<td>".$value->namaJabatan."</td>
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

		$this->load->view('front/user',$dataOut);
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
		$dataUpd['password'] = base64_encode($data['pass']);
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