<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
    	$this->load->model('myapp'); 
		$this->load->helper(array('form', 'url'));
	}
 
	function getDataForm() {
		$dataOut = array(); 
		$tr = '';  
		$no = 1;
		$userType = $this->session->userdata('userTypeMyApps');
		$userDiv = trim($this->session->userdata('nmDiv')); 
		$userDept = trim($this->session->userdata('nmDept')); 
		$userId = $this->session->userdata('userIdMyApps');
		$userFullName = $this->session->userdata('fullNameMyApps');

		$where = "WHERE sts_delete = '0' ";

		if ($userType == 'admin') {
			$sql = "SELECT * FROM form " . $where;
		} else {
			
			$financeAccess = array(
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'NON DEPARTMENT'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE & CONTROL'),
				array('div' => 'FINANCE', 'dept' => 'TAX'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'ACCOUNTING & REPORTING'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE'),
			);
			
			$isFinanceUser = false;
			foreach ($financeAccess as $access) {
				if (strcasecmp($userDiv, $access['div']) === 0 && strcasecmp($userDept, $access['dept']) === 0) {
					$isFinanceUser = true;
					break;
				}
			}

			if ($isFinanceUser) {
				$where .= " AND divisi LIKE '%FINANCE%'";
			} else {
				$where .= " AND divisi = '" . $userDiv . "'";
			}

			$sql = "SELECT * FROM form " . $where;
		}
		
		$data = $this->myapp->getDataQueryDB6($sql);

		foreach ($data as $key => $value) {
			$status = '';
			
			if ($value->st_submit === 'Y' && $value->st_acknowledge === 'N') {
				$status = "Waiting Acknowledge <i class='fa fa-clock-o'></i>";
			}
			if ($value->st_acknowledge === 'Y' && $value->st_approval === 'N') {
				$status = "Waiting Approval <i class='fa fa-clock-o'></i>";
			}
			if ($value->st_approval === 'Y') {
				$status = "Approve Success <i class='fa fa-check'></i>";
			}
			if ($value->st_detail === 'Y') {
				$btnDetail = "<button onclick=\"editData('".$value->id."');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button>";
			} else {
				$btnDetail = "<button onclick=\"addDetail('" . $value->id . "');\" title=\"Add Detail\" class=\"btn btn-primary btn-xs\" id=\"btnAdd\" type=\"button\"><i class=\"glyphicon glyphicon-plus\"></i></button>";
			}
			if ($value->st_submit === 'Y') {
				$btnExport = "<button onclick=\"ViewPrint('" .$value->id. "','request');\" class=\"btn btn-success btn-xs\" type=\"button\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>";
				$btnDetail = '';
				$btnDelete = '';
			} else {
				$btnExport = "<button onclick=\"ViewPrint('" . $value->id . "', 'request');\" class=\"btn btn-success btn-xs\" id=\"btnView_" . $value->id . "\" type=\"button\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>";
				$btnDelete = "<button onclick=\"delData('" . $value->id . "');\" class=\"btn btn-danger btn-xs\" id=\"btnDelete_" . $value->id . "\" type=\"button\" title=\"Delete\"><i class=\"fa fa-trash-o\"></i> Delete</button>";
			}
			$tr .= "<tr id='row_" . $value->id . "'>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $no . "</td>";
				$tr .= "<td align='center'>" . $btnDetail . "</td>"; 
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->project_reference . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->purpose . "</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;'>" . $value->company . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->location . "</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;'>" . $value->divisi . "</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;' id='status_" . $value->id . "'>" . $status . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$btnExport.$btnDelete."</td>";
			$tr .= "</tr>";

			$no++;
		}

		$buttonSettings = $this->userSetting();
		$dataOut['buttonApp'] = $buttonSettings['buttonApp'];
		$dataOut['buttonAck'] = $buttonSettings['buttonAck'];

		$dataOut['tr'] = $tr;
		$dataOut['getOptCompany'] = $this->getOptCompany(); 
		$dataOut['getOptMstDivisi'] = $this->getOptMstDivisi();
		$this->load->view('myApps/form', $dataOut);
	}

	function getFormRequestDetailById() {
		$id_form = $this->input->post('id_form');

		if (empty($id_form)) {
			echo json_encode(array('status' => 'error', 'message' => 'ID Form tidak valid.'));
			return;
		}

		$sqlDetail = "SELECT * FROM form_detail WHERE id_form = '".$id_form."' AND sts_delete = '0'";
		$formDetails = $this->myapp->getDataQueryDB6($sqlDetail);

		$detailCount = count($formDetails);

		if ($detailCount > 0) {
			echo json_encode(array('status' => 'success', 'details' => $formDetails, 'detailCount' => $detailCount));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Data detail tidak ditemukan.'));
		}
	}

	function saveEditDetail() {
		$data = $this->input->post();

		$txtIdForm = isset($data['txtIdEditForm']) ? $data['txtIdEditForm'] : null;

		if (empty($txtIdForm)) {
			echo json_encode(array("status" => "error", "message" => "ID Form is missing."));
			return;
		}

		$responseMessage = "";	
		$details = array();

		$arrDescriptions   = $data['txtdescription'];
		$arrTypes          = $data['txttype'];
		$arrReasons        = $data['txtreason'];
		$arrQuantities     = $data['txtquantity'];
		$arrNotes          = $data['txtnote'];
		$arrIdDetails      = $data['txtIdDetail'];

		$numEntries = count($arrDescriptions);

		for ($i = 0; $i < $numEntries; $i++) {
			if (empty($arrDescriptions[$i]) || empty($arrTypes[$i]) || empty($arrReasons[$i]) || $arrQuantities[$i] <= 0) {
				continue; 
			}
			$dataToUpdate = array(
				'description'   => $arrDescriptions[$i],
				'type'          => $arrTypes[$i],
				'reason'        => $arrReasons[$i],
				'quantity'      => $arrQuantities[$i],
				'note'          => $arrNotes[$i],
				'update_userid' => $this->session->userdata('userIdMyApps'),  
				'update_date'   => date('Y-m-d')
			);
			$idDetail = isset($arrIdDetails[$i]) ? $arrIdDetails[$i] : null;
			
			if ($idDetail) {
				$updateSuccess = $this->myapp->updateDataDb6('form_detail', $dataToUpdate, array('id' => $idDetail)); 

				if ($updateSuccess) {
					$responseMessage = "Update Success..!!";
					$details[] = $dataToUpdate;  
				} else {
					log_message('info', 'No rows updated for id_detail: ' . $idDetail);
				}
			}
		}
		$response = array(
			'status' => 'success',
			'idForm' => $txtIdForm,  
			'details' => $details,
			'message' => $responseMessage
		);

		echo json_encode($response);
	}

	function delData()
	{
		$stData = array('status' => 'Failed', 'message' => '');
		$idDel = $_POST['id'];
		$dataUpd = array();

		try {
	
			$dataUpd['sts_delete'] = '1';

			$whereNya = "id = '".$idDel."'";
			$this->myapp->updateDataDb6("form", $dataUpd, $whereNya);

			$stData['status'] = "Delete Success..!!";
		} catch (Exception $ex) {
			$stData['message'] = "Failed => ".$ex->getMessage();
		}

		echo json_encode($stData);
	}
	
 	function updateSubmitStatus() {
		$id_form = $this->input->post('id'); 
		$userid_submit = $this->session->userdata('userIdMyApps');
		$date_submit = date('Y-m-d');
		
		$data = array(
			'st_submit' => 'Y',
			'userid_submit' => $userid_submit,
			'date_submit' => $date_submit
		);

		$this->myapp->updateDataDb6('form', $data, array('id' => $id_form));

		$this->addDataMyAppLetter($id_form);

		echo json_encode(array('status' => 'success'));
	}

	function acknowledgeData()
	{
		$id_form = $this->input->post('id');
		$userid_submit = $this->session->userdata('userIdMyApps');
		$date_submit = date('Y-m-d');
		$userAcknowledge = $this->session->userdata('fullNameMyApps');
		$dateAcknowledge = date('Y-m-d');
		
		$data = array(
			'st_acknowledge' => 'Y',
			'userid_submit' => $userid_submit,
			'date_submit' => $date_submit,
			'user_acknowledge'=> $userAcknowledge,
			'date_acknowledge' => $dateAcknowledge
		);
			
		$this->myapp->updateDataDb6('form', $data, array('id' => $id_form));

		echo json_encode(array('status' => 'success'));
	}

	function approveData()
	{
		$id_form = $this->input->post('id');
		$userid_submit = $this->session->userdata('userIdMyApps');
		$date_submit = date('Y-m-d');
		$userApprove = $this->session->userdata('fullNameMyApps');
		$dateApprove = date('Y-m-d');

		$data = array(
			'st_approval' => 'Y',
			'userid_submit' => $userid_submit,
			'date_submit' => $date_submit,
			'user_approve' => $userApprove,
			'date_approve' => $dateApprove	
		);

		$this->myapp->updateDataDb6('form', $data, array('id' => $id_form));
		
		echo json_encode(array('status' => 'success'));
	}

	function getAcknowledgeData() {
		$userType = $this->session->userdata('userTypeMyApps');
		$userDiv = trim($this->session->userdata('nmDiv'));
		$userDept = trim($this->session->userdata('nmDept'));

		$where = "WHERE st_submit = 'Y' 
				AND st_acknowledge = 'N' 
				AND sts_delete = 0";

		if ($userType == 'admin') {
			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
					FROM form " . $where;
		} else {

			$financeAccess = array(
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'NON DEPARTMENT'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE & CONTROL'),
				array('div' => 'FINANCE', 'dept' => 'TAX'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'ACCOUNTING & REPORTING'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE'),
			);

			$isFinanceUser = false;
			foreach ($financeAccess as $access) {
				if (strcasecmp($userDiv, $access['div']) === 0 && strcasecmp($userDept, $access['dept']) === 0) {
					$isFinanceUser = true;
					break;
				}
			}

			if ($isFinanceUser) {
				$where .= " AND divisi LIKE '%FINANCE%'";
			} else {
				$where .= " AND divisi = '" . $userDiv . "'";
			}

			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
					FROM form " . $where;
		}

		$query = $this->myapp->getDataQueryDB6($sql);

		if ($query) {
			$resultArrayAcknowledge = array();
			foreach ($query as $row) {
				$resultArrayAcknowledge[] = array(
					'id' => $row->id,
					'project_reference' => $row->project_reference,
					'purpose' => $row->purpose,
					'company' => $row->company,
					'location' => $row->location,
					'divisi' => $row->divisi,
					'sts_delete' => $row->sts_delete
				);
			}
			echo json_encode(array('data' => $resultArrayAcknowledge));
		} else {
			echo json_encode(array('data' => array()));
		}
	}

	function getApprovalData() {
		$userType = $this->session->userdata('userTypeMyApps');
		$userDiv = trim($this->session->userdata('nmDiv'));
		$userDept = trim($this->session->userdata('nmDept'));
		$userId = $this->session->userdata('userIdMyApps');

		$where = "WHERE st_acknowledge = 'Y' 
				AND st_approval = 'N' 
				AND sts_delete = 0";

		if ($userId === '00054') {
			$where .= " AND (
				(divisi = 'BOD / BOC' AND department IN ('NON DEPARTMENT', 'PA')) OR
				(divisi = 'NON DIVISION' AND department IN ('SECRETARY', 'NON DEPARTMENT')) OR
				(divisi = 'OFFICE OPERATION' AND department IN ('IT', 'LEGAL', 'PROCUREMENT'))
			)";
			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
					FROM form " . $where;
		} elseif ($userType == 'admin') {
			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
					FROM form " . $where;
		} else {
			
			$financeAccess = array(
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'NON DEPARTMENT'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE & CONTROL'),
				array('div' => 'FINANCE', 'dept' => 'TAX'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'ACCOUNTING & REPORTING'),
				array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE'),
			);

			$isFinanceUser = false;
			foreach ($financeAccess as $access) {
				if (strcasecmp($userDiv, $access['div']) === 0 && strcasecmp($userDept, $access['dept']) === 0) {
					$isFinanceUser = true;
					break;
				}
			}

			if ($isFinanceUser) {
				$where .= " AND divisi LIKE '%FINANCE%'";
			} else {
				$where .= " AND divisi = '" . $userDiv . "'";
			}

			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
					FROM form " . $where;
		}

		$query = $this->myapp->getDataQueryDB6($sql);

		if ($query) {
			$resultArrayApproval = array();
			foreach ($query as $value) {
				$resultArrayApproval[] = array(
					'id' => $value->id,
					'project_reference' => $value->project_reference,
					'purpose' => $value->purpose,
					'company' => $value->company,
					'location' => $value->location,
					'divisi' => $value->divisi,
					'sts_delete' => $value->sts_delete
				);
			}
			echo json_encode(array('data' => $resultArrayApproval));
		} else {
			echo json_encode(array('data' => array()));
		}
	}

	function createQRCode($id = "")
	{
		$config = array();
		$this->load->library('ciqrcode');

		$config['cacheable']	= true;
		$config['cachedir']		= './assets/imgQRCodeForm/';
		$config['errorlog']		= './assets/imgQRCodeForm/';
		$config['imagedir']		= './assets/imgQRCodeForm/';
		$config['quality']		= true;
		$config['size']			= '1024';
		$config['black']		= array(224,255,255);
		$config['white']		= array(0,0,128);
		$this->ciqrcode->initialize($config);

		$imgName = base64_encode($id).'.jpg';

		$params['data'] = "http://apps.andhika.com/observasi/myLetter/viewLetter/".base64_encode($id); 
		$params['level'] = 'H'; 
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; 
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); 
	}

	function createNo($noNya = "")
	{
		$dt = strlen($noNya);
		$outNo = "";
		if($dt == 1)
		{
			$outNo = "000".$noNya;
		}
		else if($dt == 2)
		{
			$outNo = "00".$noNya;
		}
		else if($dt == 3)
		{
			$outNo = "0".$noNya;
		}
		else{
			$outNo = $noNya;
		}
		
		return $outNo;
	}

	function addDataMyAppLetter($IdForm = "")
	{
		$dateNow = date("Y-m-d");
		$yearNow = date("Y");
		$monthNow = date("m");
		$noSurat = "1";
		$initDivisi = "DOO";
		$initCmp = "";
		$idUsrLogin = $this->session->userdata('userIdMyApps');
		$fullNameLogin = $this->session->userdata('fullNameMyApps');
		$usrAddLogin = $idUsrLogin."#".date("H:i")."#".date("d/m/Y");
		$insSql = array();
		$imgName = "";

		try {
			$sql = "SELECT * FROM form WHERE id = '".$IdForm."' AND sts_delete = '0'";
			$rsl = $this->myapp->getDataQueryDB6($sql);

			if (count($rsl) > 0) {
				$initCmp = $rsl[0]->init_cmp;
			}

			if ($initCmp !== "") {
				$sqlSrv = "SELECT nosurat FROM tblEmpNoSurat
						WHERE cmpcode = '".$initCmp."' AND YEAR(tglsurat) = '".$yearNow."'
						ORDER BY nosurat DESC LIMIT 0,1";
				$rslSrv = $this->myapp->getDataQueryDB6($sqlSrv);

				if (count($rslSrv) > 0) {
					$ns = explode("/", $rslSrv[0]->nosurat);
					$noSurat = $ns[0] + 1;
				}

				$batchno = $this->getBatchNo();
				$formatNoSrt = $this->createNo($noSurat) . "/" . $initCmp . "/" . $initDivisi . "/" . $monthNow . substr($yearNow, 2, 2);

				$insSql["batchno"] = $batchno;
				$insSql["cmpcode"] = $initCmp;
				$insSql["nosurat"] = $formatNoSrt;
				$insSql["issueddiv"] = $initDivisi;
				$insSql["signedby"] = $initDivisi;
				$insSql["address"] = "IT Division";
				$insSql["tglsurat"] = $dateNow;
				$insSql["ket"] = "Form IT Request / IT / " . $fullNameLogin;
				$insSql["copydoc"] = "0";
				$insSql["canceldoc"] = "0";
				$insSql["createdby"] = $fullNameLogin;
				$insSql["addusrdt"] = $usrAddLogin;

				$this->myapp->insDataDb6($insSql, "tblEmpNoSurat");

				$updateSql = array('batchno' => $batchno);
				$this->myapp->updateDataDb6("form", $updateSql, array('id' => $IdForm));

				$imgName = $this->createQRCode($batchno);
			}
		} catch (Exception $e) {
			$imgName = "Failed => " . $e->getMessage();
		}
		return $imgName;
	}

	function previewPrint() 
	{	
		$id = $this->input->post('id');
		$typeView = $this->input->post('typeView');
		$userid = $this->session->userdata('userIdMyApps'); 
		$userType = $this->session->userdata('userTypeMyApps');
		
		if ($id === null) {
			show_error('ID is missing', 400);
			return;
		}

		$button = "";
		$logo_company = "/assets/img";
		$form_details = array();

		$queryForm = "SELECT * FROM `form` WHERE `id` = $id AND `sts_delete` = 0";
		$form = $this->myapp->getDataQueryDB6($queryForm);

		

		if ($form[0]->batchno > 0) {
			$this->createQRCode($form[0]->batchno);
		}

		if ($form[0]->company == "PT. ADNYANA") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else if ($form[0]->company == "PT. ANDHIKA LINES") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else if ($form[0]->company == "PT. INDAH BIMA PRIMA") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else if ($form[0]->company == "PT. ANDHINI EKA KARYA SEJAHTERA") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".png";
		}

		if (count($form) > 0 && isset($form[0])) {
			$queryFormDetail = "SELECT * FROM `form_detail` WHERE `id_form` = $id AND `sts_delete` = 0";
			$form_details = $this->myapp->getDataQueryDB6($queryFormDetail);
			// var_dump($form_details);
			// die;
			$form_details = array_filter($form_details, function($detail) {
				return !empty($detail->description) && !empty($detail->type) && !empty($detail->reason) && $detail->quantity > 0;
			});

			$qrCodeImgPath = base_url("assets/imgQRCodeForm/" . base64_encode($form[0]->batchno) . ".jpg");
			
			$data = array(
				'form' => $form[0],
				'form_details' => $form_details,
				'note' => isset($form[0]->note) ? $form[0]->note : 'No notes available',  // pastikan note tersedia
				'imageLogo' => "<img src=\"" . base_url($logo_company) . "\" alt=\"Company Logo\" height=\"50\" style=\"align-items: left; margin-bottom: -50px;\">",
				'qrCode' => "<img src=\"" . $qrCodeImgPath . "\" alt=\"QR Code\" height=\"100\" width=\"100\" />",
				'kadept' => null,
				'kadiv' => null,
				'nameKadept' => null,
				'nameKadiv' => null,
				'button' => $button
			);
			
			$mappingInfo = $this->getMappingInfo($form[0]->divisi, $form[0]->department, $form[0]->batchno);
			
			if ($form[0]->st_acknowledge == 'Y') {
				$data['kadept'] = "<img src=\"" . base_url(trim($mappingInfo['namefileKadept'])) . "\" alt=\"Kadept QR Code\" height=\"100\" width=\"100\" />";
				$data['nameKadept'] = $mappingInfo['nameKadept'];
			}

			if ($form[0]->st_approval == 'Y') {
				$data['kadiv'] = "<img src=\"" . base_url($mappingInfo['namafileKadiv']) . "\" alt=\"Kadiv QR Code\" height=\"100\" width=\"100\" />";
				$data['nameKadiv'] = $mappingInfo['nameKadiv'];
			}
			
			if ($typeView == 'request' && $form[0]->st_submit == 'N' && $form[0]->st_acknowledge == 'N' && $form[0]->st_approval == 'N') {
				$button .= "<button onclick=\"sendData({$form[0]->id});\" class=\"btn btn-primary btn-xs\" id=\"btnSubmit_{$form[0]->id}\" type=\"button\" title=\"Submit\"><i class=\"fa fa-send-o\"></i> Send</button>";
			}
			if($typeView == 'request' && $form[0]->st_submit == 'Y' && $form[0]->st_acknowledge == 'N' && $form[0]->st_approval == 'N'){
				$button .= "<button onclick=\"downloadPdf({$form[0]->id});\" class=\"btn btn-primary btn-xs\" id=\"btnDownload_{$form[0]->id}\" type=\"button\" title=\"Download\"><i class=\"fa fa-download\"></i> Download</button>";
			}
			if ($typeView == 'request' && $form[0]->st_submit == 'Y' && $form[0]->st_acknowledge == 'Y' && $form[0]->st_approval == 'N'){
				$button .= "<button onclick=\"downloadPdf({$form[0]->id});\" class=\"btn btn-primary btn-xs\" 		id=\"btnDownload_{$form[0]->id}\" type=\"button\" title=\"Download\">
							<i class=\"fa fa-download\"></i> Download
							</button>";
			}
			if ($typeView == 'acknowledge' && $form[0]->st_submit == 'Y' && $form[0]->st_acknowledge == 'N'){
				$button .= "<button onclick=\"acknowledgeData({$form[0]->id});\" class=\"btn btn-primary btn-xs\" type=\"button\" style=\"margin: 5px;\">
										<i class=\"fa fa-print\"></i> Acknowledge
									</button>";
			}
			if ($typeView == 'approval' && $form[0]->st_acknowledge == 'Y' && $form[0]->st_approval == 'N'){
				$button .= "<button onclick=\"approveData({$form[0]->id});\" class=\"btn btn-primary btn-xs\" type=\"button\" style=\"margin: 5px;\">
										<i class=\"fa fa-thumbs-up\"></i> Approve
									</button>";
			}
			if ($typeView == 'request' && $form[0]->st_submit == 'Y' && $form[0]->st_acknowledge == 'Y' && $form[0]->st_approval == 'Y')
			{
				$button .= "<button onclick=\"downloadPdf({$form[0]->id});\" class=\"btn btn-primary btn-xs\" id=\"btnDownload_{$form[0]->id}\" type=\"button\" title=\"Download\"><i class=\"fa fa-download\"></i> Download</button>";
			}

			$data['button'] = $button;

			print json_encode($data);

			// var_dump($data);
			// die;
		} else {
			show_error('Form not found', 404);
		}
	}
	
	function printPdf() {
		$id = $this->input->post('id');
		if ($id === null) {
			show_error('ID is missing', 400);
			return;
		}

		$button = "";
		$logo_company = "/assets/img";
		$form_details = array();

		$queryForm = "SELECT * FROM `form` WHERE `id` = $id AND `sts_delete` = 0";
		$form = $this->myapp->getDataQueryDB6($queryForm);

		if ($form[0]->batchno > 0) {
			$this->createQRCode($form[0]->batchno);
		}

		if ($form[0]->company == "PT. ADNYANA") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else if ($form[0]->company == "PT. ANDHIKA LINES") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else if ($form[0]->company == "PT. INDAH BIMA PRIMA") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else if ($form[0]->company == "PT. ANDHINI EKA KARYA SEJAHTERA") {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".jpg";
		} else {
			$logo_company .= "/" . str_replace(" ", "", $form[0]->company) . ".png";
		}
		
		if (count($form) > 0 && isset($form[0])) {
			$queryFormDetail = "SELECT * FROM `form_detail` WHERE `id_form` = $id AND `sts_delete` = 0";
			$form_details = $this->myapp->getDataQueryDB6($queryFormDetail);

			$form_details = array_filter($form_details, function($detail) {
				return !empty($detail->description) && !empty($detail->type) && !empty($detail->reason) && $detail->quantity > 0;
			});

			$qrCodeImgPath = base_url("assets/imgQRCodeForm/" . base64_encode($form[0]->batchno) . ".jpg");

			$data = array(
				'form' => $form[0],
				'form_details' => $form_details,
				'imageLogo' => "<img src=\"" . base_url($logo_company) . "\" alt=\"Company Logo\" height=\"50\" style=\"align-items: left; margin-bottom: -50px;\">",
				'qrCode' => "<img src=\"" . $qrCodeImgPath . "\" alt=\"QR Code\" height=\"100\" width=\"100\" />",  
				'kadept' => null,
				'kadiv' => null,
				'nameKadept' => null,
				'nameKadiv' => null
			);

			$mappingInfo = $this->getMappingInfo($form[0]->divisi, $form[0]->department, $form[0]->batchno);
		
			if ($form[0]->st_acknowledge == 'Y') {
				$data['kadept'] = "<img src=\"" . base_url(trim($mappingInfo['namefileKadept'])) . "\" alt=\"Kadept QR Code\" height=\"100\" width=\"100\" />";
				$data['nameKadept'] = $mappingInfo['nameKadept'];
			}

			if ($form[0]->st_approval == 'Y') {
				$data['kadiv'] = "<img src=\"" . base_url($mappingInfo['namafileKadiv']) . "\" alt=\"Kadiv QR Code\" height=\"100\" width=\"100\" />";
				$data['nameKadiv'] = $mappingInfo['nameKadiv'];
			}
			 
			$this->load->view('myApps/previewPrint', $data);
		} else {
			show_error('Form not found', 404);
		}
	}
		
	function getMappingInfo($division, $department, $batchno) {
		
		$queryBatch = "SELECT * FROM `form` WHERE `batchno` = '".$batchno."' AND sts_delete = '0'";
		$requestData = $this->myapp->getDataQueryDB6($queryBatch);

		if (!empty($requestData) && isset($requestData[0])) {
			$requestName = $requestData[0]->request_name;
			$qrcodeFile = '/assets/imgQRCodeForm/' . base64_encode($requestData[0]->batchno) . '.jpg';
		} else {
			$requestName = 'Nama Request';
			$qrcodeFile = '/assets/imgQRCodeForm/default.jpg'; 
		}
		
		$Mapping = array(
			'BOD / BOC' => array(
				'NON DEPARTMENT' => array( 
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namefileKadept' => $qrcodeFile, 
					'acknowledgeKadept' => $requestName
				),
				'PA' => array(
					'nameKadiv' => 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namefileKadept' => $qrcodeFile,
					'acknowledgeKadept' => $requestName	
				),
			),
			'CORPORATE FINANCE, STRATEGY & COMPLIANCE' => array(
				'NON DEPARTMENT' => array(
					'nameKadiv' => $requestName,
					'namafileKadiv' => $qrcodeFile,
					'approveKadiv' => $requestName,
					'nameKadept' => $requestName,
					'namefileKadept' => $qrcodeFile,
					'acknowledgeKadept' => $requestName	
				)
			), 
			'DRY BULK COMMERCIAL,OPERATION & AGENCY' => array(
				'COMMERCIAL' => array(
					'nameKadiv' => 'Ferry Nugroho',
					'namafileKadiv' => '/assets/ImgQRCodeForm/FerryNugroho.jpg',
					'approveKadiv' => 'Ferry Nugroho ',
					'nameKadept' => 'Rahadian Herbisworo',
					'namefileKadept' => '/assets/ImgQRCodeForm/RahadianHerbisworo.jpg',
					'acknowledgeKadept' => 'Rahadian Herbisworo'
				),
				'OPERATION' => array(
					'nameKadiv' => 'Ferry Nugroho',
					'namafileKadiv' => '/assets/ImgQRCodeForm/TimbulRiyadi.jpg',
					'approveKadiv' => 'Ferry Nugroho',
					'nameKadept' => 'Timbul Riyadi',
					'namefileKadept' => '/assets/ImgQRCodeForm/RahadianHerbisworo.jpg',
					'acknowledgeKadept' => 'Timbul Riyadi' 
				),
				'AGENCY' => array(
					'nameKadiv' => 'Ferry Nugroho',
					'namafileKadiv' => '/assets/ImgQRCodeForm/TimbulRiyadi.jpg',
					'approveKadiv' => 'Ferry Nugroho',
					'nameKadept' => 'Timbul Riyadi',
					'namefileKadept' => '/assets/ImgQRCodeForm/RahadianHerbisworo.jpg',
					'acknowledgeKadept' => 'Timbul Riyadi'
				)
			),
			'FINANCE' => array(
				'FINANCE' => array(
					'nameKadiv' => 'Sylvia Panghuriany',
					'namafileKadiv' => '/assets/ImgQRCodeForm/Sylvia.jpg',
					'approveKadiv' => 'Sylvia Panghuriany',
					'nameKadept' => 'Marita',
					'namefileKadept' => '/assets/ImgQRCodeForm/Marita.jpg',
					'acknowledgeKadept' => 'Marita'
				),
				'ACCOUNTING' => array(
					'nameKadiv' => 'Sylvia Panghuriany',
					'namafileKadiv' => '/assets/ImgQRCodeForm/Sylvia.jpg',
					'approveKadiv' => 'Sylvia Panghuriany', 
					'nameKadept' => 'Riko Ramdani',
					'namefileKadept' => '/assets/ImgQRCodeForm/RikoRamdani.jpg',
					'acknowledgeKadept' => 'Riko Ramdani'
				),
				'TAX' => array(
					'nameKadiv' => 'Sylvia Panghuriany',
					'namafileKadiv' => '/assets/ImgQRCodeForm/Sylvia.jpg',
					'approveKadiv' => 'Sylvia Panghuriany',
					'nameKadept' => 'Gunawan Effendi',
					'namefileKadept' => '/assets/ImgQRCodeForm/GunawanEffendi.jpg',
					'acknowledgeKadept' => 'Gunawan Effendi'
				)
			),
			'HUMAN CAPITAL & GA' => array(
				'HR' => array(
					'nameKadiv' => 'Salsabila Angling',
					'namafileKadiv' => '/assets/ImgQRCodeForm/SalsabilaAngling.jpg',
					'approveKadiv' => 'Salsabila Angling',
					'nameKadept' => 'Elan Harsono',
					'namefileKadept' => '/assets/ImgQRCodeForm/ElanHarsono.jpg',
					'acknowledgeKadept' => 'Elan Harsono'
				),
				'GA' => array(
					'nameKadiv' => 'Salsabila Angling',
					'namafileKadiv' => '/assets/ImgQRCodeForm/SalsabilaAngling.jpg',
					'approveKadiv' => 'Salsabila Angling',
					'nameKadept' => 'Catra Arisandi',
					'namefileKadept' => '/assets/ImgQRCodeForm/CatraArisandi.jpg',
					'acknowledgeKadept' => 'Catra Arisandi'
				) 
			),
			'NON DIVISION' => array(
				'SECRETARY' => array(
					'nameKadiv' => 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namefileKadept' => $qrcodeFile,
					'acknowledgeKadept' => $requestName	
				),
				'NON DEPARTMENT' => array(
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv'=> '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv'=> 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namefileKadept' => $qrcodeFile,
					'acknowledgeKadept' => $requestName   
				)
			),
			'OFFICE OPERATION' => array(
				'IT' => array(
					'nameKadiv' => 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'PribadiArijanto',
					'nameKadept' => 'Hendra Roesli',
					'namefileKadept' => '/assets/ImgQRCodeForm/HendraRoesli.jpg',
					'acknowledgeKadept' => 'Hendra Roesli' 
				),
				'LEGAL' => array(
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => 'Pribadi Arijanto',
					'namefileKadept' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'acknowledgeKadept' => 'Pribadi Arijanto' 
				),
				'PROCUREMENT' => array(
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => 'Deffandra Putra',
					'namefileKadept' => '/assets/ImgQRCodeForm/DeffandraPutra.jpg',
					'acknowledgeKadept' => 'Deffandra Putra'
				)
			),
			'OIL & GAS COMMERCIAL & OPERATION' => array(
				'COMMERCIAL' => array(
					'nameKadiv'=> 'Nick Djatnika',
					'namafileKadiv' => '/assets/ImgQRCodeForm/NickDjatnika.jpg',
					'approveKadiv' => 'Nick Djatnika',
					'nameKadept' => 'Aditya Ilham Nusantara',
					'namefileKadept' => '/assets/ImgQRCodeForm/Adityailham.jpg',
					'acknowledgeKadept' => 'Aditya Ilham Nusantara'
				),
				'OPERATION' => array(
					'nameKadiv'=> 'Nick Djatnika',
					'namafileKadiv' => '/assets/ImgQRCodeForm/NickDjatnika.jpg',
					'approveKadiv' => 'Nick Djatnika',
					'nameKadept' => 'Aditya Ilham Nusantara',
					'namefileKadept' => '/assets/ImgQRCodeForm/Adityailham.jpg',
					'acknowledgeKadept' => 'Aditya Ilham Nusantara'
				)
			),
			'SHIP MANAGEMENT' => array(
				'OWNER SUPERINTENDENT (TECHNICAL)' => array(
					'nameKadiv'=> 'Eddy Sukmono',
					'namafileKadiv' => '/assets/ImgQRCodeForm/EddySukmono.jpg',
					'approveKadiv' => 'Eddy Sukmono',
					'nameKadept' => 'Hari Joko Purnomo',
					'namefileKadept' => '/assets/ImgQRCodeForm/HariJoko.jpg',
					'acknowledgeKadept' => 'Hari Joko'
				),
				'CREWING' => array(
					'nameKadiv'=> 'Eddy Sukmono',
					'namafileKadiv' => '/assets/ImgQRCodeForm/EddySukmono.jpg',
					'approveKadiv' => 'Eddy Sukmono',
					'nameKadept' => 'Eva Marliana ',
					'namefileKadept' => '/assets/ImgQRCodeForm/EvaMarliana.jpg',
					'acknowledgeKadept' => 'Eva Marliana'
				),
				'QHSE'=> array(
					'nameKadiv'=> 'Eddy Sukmono',
					'namafileKadiv' => '/assets/ImgQRCodeForm/EddySukmono.jpg',
					'approveKadiv' => 'Eddy Sukmono',
					'nameKadept' => 'Hardi Gunarto',
					'namefileKadept' => '/assets/ImgQRCodeForm/HardiGunarto.jpg',
					'acknowledgeKadept' => 'Hardi Gunarto'
				) 
			)
			
		);

		if (isset($Mapping[$division])) {
			if (is_array($Mapping[$division])) {
				if (isset($Mapping[$division][$department])) {
					return $Mapping[$division][$department]; 
				} else if (isset($Mapping[$division]['department']) && $Mapping[$division]['department'] == $department) {
					return $Mapping[$division]; 
				}
			}
		}

		return null;
	}

	function userSetting() {
		$userid = $this->session->userdata('userIdMyApps');
		$query = "SELECT * FROM form_usersetting WHERE userid ='".$userid."' AND st_delete = '0'";
		$result = $this->myapp->getDataQueryDB6($query); 
		
		$buttonAck = "";
		$buttonApp = "";
		$buttonReq = "";
		$dataOut = array();

		if(count($result) > 0) {
			if($result[0]->nmDiv != ""){
				$buttonApp = '<div class="col-md-4">
								<button class="btn btn-primary btn-block" onclick="changeBtnNavigation(\'approval\');">
									<label>Approval</label>
								</button>
							</div>';
			} 
			if($result[0]->nmDept != ""){
				$buttonAck = '<div class="col-md-4">
								<button class="btn btn-primary btn-block" onclick="changeBtnNavigation(\'acknowledge\');">
									<label>Acknowledge</label>
								</button>
							</div>';
			}
		}

		$dataOut['buttonApp'] = $buttonApp;
		$dataOut['buttonAck'] = $buttonAck;

		return $dataOut;
	}

	
	function getBatchNo()
	{
		$batchNo = "1";
		$sql = " SELECT (batchno + 1) AS batchNo FROM tblempnosurat ORDER BY batchno DESC LIMIT 0,1 ";
		$data = $this->myapp->getDataQueryDB6($sql);

		if(count($data) > 0)
		{
			$batchNo = $data[0]->batchNo;
		}

		return $batchNo;
	}
	
	function saveFormRequest()
	{
		$data = $this->input->post();
		$status = "";
		$dataIns = array();
		$dateNow = date("Y-m-d");
		$userId = $this->session->userdata('userIdMyApps');
		$reqName = $this->session->userdata('fullNameMyApps');
		$IdForm = $data['txtIdForm'];
		$response = array();
 
		if (empty($IdForm)) {
			$dataIns = array(
				'batchno' => $this->getBatchNo(),
				'project_reference' => $data['txtprojectReference'],
				'purpose' => $data['txtpurpose'],
				'company' => $data['slcCompanyText'],
				'init_cmp' => $data['slcCompany'],
				'department' => $data['slcDepartment'],
				'location' => $data['txtlocation'],
				'divisi' => $data['slcDivisi'],
				'required_date' => isset($data['txtRequiredDate']) ? $data['txtRequiredDate'] : $dateNow,
				'userid_submit' => $userId,
				'add_date' => $dateNow,
				'request_name' => $reqName
			);
			try {
				$this->myapp->insDataDb6($dataIns, 'form');
				$IdForm = $this->db->insert_id();
				$this->addDataMyAppLetter($IdForm);
				$response = array(
					"status" => "Insert Success..!!",
					"id" => $IdForm,
					"project_reference" => $data['txtprojectReference'],
					"purpose" => $data['txtpurpose'],
					"company" => $data['slcCompanyText'],
					"location" => $data['txtlocation'],
					"divisi" => $data['slcDivisi']
				);
			} catch (Exception $ex) {
				$response = array(
					"status" => "Failed",
					"message" => $ex->getMessage()
				);
			}
		} else {
			$dataUpd = array(
				'project_reference' => $data['txtprojectReference'],
				'purpose' => $data['txtpurpose'],
				'company' => $data['slcCompanyText'],
				'init_cmp' => $data['slcCompany'],
				'department' => $data['slcDepartment'],
				'location' => $data['txtlocation'],
				'divisi' => $data['slcDivisi'],
				'required_date' => isset($data['txtRequiredDate']) ? $data['txtRequiredDate'] : $dateNow,
				'update_userid' => $userId,
				'update_date' => $dateNow,
				'request_name' => $reqName
			);

			try {
				$where = "id = '" . $IdForm . "'";
				$this->myapp->updateDataDb6("form", $dataUpd, $where);
				$this->addDataMyAppLetter($IdForm);
				$response = array(
					"status" => "Update Success",
					"id" => $IdForm
				);
			} catch (Exception $ex) {
				$response = array(
					"status" => "Failed",
					"message" => $ex->getMessage()
				);
			}
		}

		echo json_encode($response);
	}


	function saveFormRequestDetail() {
		$data = $this->input->post(); 
		$txtIdForm = $data['id_form'];  
		$currentDate = date("Y-m-d");
		$responseMessage = "";
			
		$arrDescriptions = explode('*', $data['descriptions']);
		$arrTypes = explode('*', $data['types']);
		$arrReasons = explode('*', $data['reasons']);
		$arrQuantities = explode('*', $data['quantities']);
		$arrNotes = explode('*', $data['notes']);
				
		$numEntries = count($arrDescriptions);

		for ($i = 0; $i < $numEntries; $i++) {
			if (empty($arrDescriptions[$i]) || empty($arrTypes[$i]) || empty($arrReasons[$i]) || $arrQuantities[$i]<=0) 
			{
				continue; 
			}
				
			$dataToInsert = array(
				'id_form'       => $txtIdForm,  
				'description'   => $arrDescriptions[$i],
				'type'          => $arrTypes[$i],
				'reason'        => $arrReasons[$i],
				'quantity'      => $arrQuantities[$i],
				'note'          => $arrNotes[$i],
				'add_userid'    => $this->session->userdata('userIdMyApps'),
				'add_date'      => $currentDate,
				'request_name'  => $this->session->userdata('fullNameMyApps')
			);

			try {
				$this->myapp->insDataDb6($dataToInsert, 'form_detail');
				$responseMessage = "Insert Success..!!";
			}catch (Exception $e) {
				$responseMessage = "Failed to Insert: " . $e->getMessage();
				break;
			}
		}
		try {
			$this->db->set('st_detail', 'Y');
			$this->db->where('id', $txtIdForm);
			$this->db->update('form');
		} catch (Exception $e) {
			$responseMessage = "Failed to update form detail: " . $e->getMessage();
		}

		echo json_encode($responseMessage);
	}

	function getOptMstDivisi($userDiv = "")
	{
		$opt = "<option value=\"\">- Select -</option>";
		
		$whereNya = "";

		if($userDiv != "")
		{
			$whereNya = " AND kddiv = ".$userDiv." ";
		}

		$sql = " SELECT * FROM tblmstdivisi WHERE deletests = '0' ".$whereNya." ORDER BY nmdiv ASC ";
		$rsl = $this->myapp->getDataQueryDb2($sql);

		foreach ($rsl as $key => $val)
		{
			$opt .= "<option value=\"".$val->nmdiv."\">".$val->nmdiv."</option>";
		}

		return $opt;
	}
    
    function getOptCompany()
	{
		$optNya = "<option value=\"\">- Select -</option>";
		
		$sql = "SELECT kdcmp, nmcmp, cmpcode
				FROM tblMstCmpNSrt 
				WHERE kdcmp IN ('02', '01', '21', '63', '09', '67') 
				AND deletests = '0' 
				ORDER BY FIELD(kdcmp, '02', '01', '21', '63', '09', '67') 
				LIMIT 6";
		
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $value)
		{
			$optNya .= "<option value=\"".$value->cmpcode."\">".$value->nmcmp."</option>";
		}
		return $optNya;
	}

}