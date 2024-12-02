<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowe	d');
require_once APPPATH . 'third_party/PHPMailer/class.phpmailer.php';
require_once APPPATH . 'third_party/PHPMailer/class.smtp.php';	

class Form extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
    	$this->load->model('myapp'); 
		$this->load->helper(array('form', 'url'));
	}
 
	function getDataForm($searchNya = "", $pageNya = "") { 
		$dataOut = array(); 
		$tr = '';  
		$no = 1;
		$limitNya = "10"; 
		$dataOut["listPage"] = "";
		$display = "10";
		$userType = $this->session->userdata('userTypeMyApps');
		$userDiv = trim($this->session->userdata('nmDiv')); 
		$userDept = trim($this->session->userdata('nmDept')); 
		$userId = $this->session->userdata('userIdMyApps');
		$userFullName = $this->session->userdata('fullNameMyApps');			
		$where = "WHERE sts_delete = '0' ";

		if ($userType == 'admin') {
			$sql = "SELECT * FROM form " . $where . " ORDER BY ID DESC";
		}else
		{
			if ($userDiv == 'OFFICE OPERATION' && $userDept == 'INFORMATION TECHNOLOGY')
			{
				$sql = "SELECT * FROM form " . $where . " ORDER BY ID DESC";
			}
			else 
			{
				$financeAccess = array(
					array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'NON DEPARTMENT'),
					array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE & CONTROL'),
					array('div' => 'FINANCE', 'dept' => 'TAX'),
					array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'ACCOUNTING & REPORTING'),
					array('div' => 'FINANCIAL CONTROLLER', 'dept' => 'FINANCE'),
				);
					
				$isFinanceUser = false;
				foreach ($financeAccess as $access) {
					if (strcasecmp($userDiv, $access['div']) == 0 && strcasecmp($userDept, $access['dept']) == 0) {
						$isFinanceUser = true;
						break;
					}
				}

				if ($isFinanceUser) {
					$where .= " AND divisi LIKE '%FINANCE%'";
				} else {
					$where .= " AND divisi = '" . $userDiv . "'";
				}
					
				$sql = "SELECT * FROM form " . $where . " ORDER BY ID DESC";
			}
		}

		if($searchNya == "search")
		{
			$txtSearch = $_POST['valSearch'];
			$idSlcType = $_POST['idSlcType'];

			if($idSlcType == "projectreference")
			{
				$where .= " AND project_reference LIKE '%".$txtSearch."%' ";
			}
			else if($idSlcType == "purpose")
			{
				$where .= " AND purpose LIKE '%".$txtSearch."%' ";
			}
			else if ($idSlcType == "company")
			{
				$where .= " AND company LIKE '%".$txtSearch."%' ";
			}
		}

		
		if ($searchNya == "" || $searchNya == "-") {
			$sqlCount = "SELECT id FROM form " . $where;
			$dataCount = $this->myapp->getDataQueryDB6($sqlCount);
			$dataPage = $this->getPaging(count($dataCount), $pageNya, $display);
			
			$limitNya = isset($dataPage['limit']) ? $dataPage['limit'] : "";
			$dataOut["listPage"] = $dataPage['listPage'];
			
			if ($pageNya != "") {
				$no = ($pageNya - 1) * $display + 1;
			}
		}

		$sql .= " " . $limitNya;

		$data = $this->myapp->getDataQueryDB6($sql);
			
		foreach ($data as $key => $value) {
			$status = '';
				
			if ($value->st_submit == 'Y' && $value->st_acknowledge == 'N') {
				$status = "Waiting Acknowledge <i class='fa fa-clock-o'></i>";
			}
			if ($value->st_acknowledge == 'Y' && $value->st_approval == 'N') {
				$status = "Waiting Approval <i class='fa fa-clock-o'></i>";
			}
			if ($value->st_approval == 'Y') {
				$status = "Approve Success <i class='fa fa-check'></i>";
			}
			if ($value->st_detail == 'Y') {
				$btnDetail = "<button onclick=\"editData('".$value->id."');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit_".$value->id."\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button>";
			}
			if ($value->st_submit == 'Y') {
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
		$dataOut["listPage"] = $dataOut['listPage'];
		$dataOut['getOptAcknowledge'] = $this->getOptAcknowledge();
		$dataOut['getOptApprove'] = $this->getOptApprove();	
		$dataOut['getOptCompany'] = $this->getOptCompany(); 
		$dataOut['getOptMstDivisi'] = $this->getOptMstDivisi();

		if ($searchNya == "search" || $pageNya != "") {
			echo json_encode($dataOut);
		} else {
			$this->load->view('myApps/form', $dataOut);
		}
		
	}

	function getPaging($countData = "", $pageNya = "", $display = "")
	{
		$limitNya = array();
		$listPage = "";
		$count = $countData;
		$page = $pageNya;
		$sLimit = "0";
		$eLimit = $display;
		$ttlList = ceil($count / $display);
		$linkLast = base_url('form/getDataForm/-/' . $ttlList);

		$listPage = "Total : " . number_format($count, 0) . " Data";
		
		if ($page != "") {
			$sLimit = ($display * ($page - 1));
			$eLimit = $display;
			$bfrPage = $page - 1;
			$aftPage = $page + 1;

			$linkBfr = base_url('form/getDataForm/-/' . $bfrPage);
			$linkAft = base_url('form/getDataForm/-/' . $aftPage);

			$listPage .= "<nav>";
			$listPage .= "<ul class=\"pagination pagination-sm\">";

			if ($page == 1) {
				$listPage .= "<li class=\"page-item disabled\"><span class=\"page-link\">First</span></li>";
			} else {
				$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . base_url('form/getDataForm') . "\">First</a></li>";
			}
			
			if ($page > 1) {
				$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $linkBfr . "\">" . $bfrPage . "</a></li>";
			}

			$listPage .= "<li class=\"page-item active\"><span class=\"page-link\">" . $page . "</span></li>";

			if ($page < $ttlList) {
				$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $linkAft . "\">" . $aftPage . "</a></li>";
			}

			if ($page < $ttlList) {
				$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $linkLast . "\">Last</a></li>";
			}

			$listPage .= "</ul>";
			$listPage .= "</nav>";
		} else {
			$listPage .= "<nav>";
			$listPage .= "<ul class=\"pagination pagination-sm\">";
			$listPage .= "<li class=\"page-item disabled\"><span class=\"page-link\">First</span></li>";

			if ($ttlList >= 3) {
				$ttlList = 3;
			}

			for ($lan = 1; $lan <= $ttlList; $lan++) {
				if ($lan == 1) {
					$listPage .= "<li class=\"page-item active\"><span class=\"page-link\">" . $lan . "</span></li>";
				} else {
					$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . base_url('form/getDataForm/-/' . $lan) . "\">" . $lan . "</a></li>";
				}
			}

			if ($ttlList > 2) {
				$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $linkLast . "\">Last</a></li>";
			}
			
			$listPage .= "</ul>";
			$listPage .= "</nav>";
		}

		$limitNya['limit'] = "LIMIT " . (int)$sLimit . ", " . (int)$eLimit;
		$limitNya['listPage'] = $listPage;
		return $limitNya;
	}

	function getEditForm() {
		$id_form = $this->input->post('id_form');
		$id = $this->input->post('id');

		if (empty($id_form)) {
			echo json_encode(array('status' => 'error', 'message' => 'ID Form tidak valid.'));
			return;
		}

		if (empty($id)) {
			echo json_encode(array('status' => 'error', 'message' => 'ID table form Tidak ada.'));
			return;
		}

		$sqlForm = "SELECT * FROM form WHERE id = '".$id."' AND sts_delete = '0'";
		$formQuery = $this->myapp->getDataQueryDB6($sqlForm);
		

		$sqlDetail = "SELECT * FROM form_detail WHERE id_form = '".$id_form."' AND sts_delete = '0'";
		$formDetails = $this->myapp->getDataQueryDB6($sqlDetail);

		$detailCount = count($formDetails);

		
		if ($formQuery && $detailCount > 0) {
			echo json_encode(array(
				'status' => 'success',
				'formData' => $formQuery, 
				'details' => $formDetails,    
				'detailCount' => $detailCount
			));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Data form atau detail tidak ditemukan.'));
		}
	}

	function saveEditFormRequest() {
		$data = $this->input->post();
		$txtIdForm = isset($data['txtIdEditForm']) ? $data['txtIdEditForm'] : null;

		if (empty($txtIdForm)) {
			echo json_encode(array("status" => "error", "message" => "ID Form is missing."));
			return;
		}
		
		$formData = array(
			'project_reference' => $this->input->post('txtprojectReferenceEdit'),
			'purpose' => $this->input->post('txtpurposeEdit'),
			'location' => $this->input->post('txtlocationEdit'),
			'company' => $this->input->post('slcCompanyText'),
			'init_cmp' => $this->input->post('slcCompanyEdit'),
			'divisi' => $this->input->post('slcDivisiEdit'),
			'required_date' => $this->input->post('txtRequiredDateEdit'),
			'name_acknowledge' => $this->input->post('slcAcknowledgeText'),
			'userid_acknowledge' => $this->input->post('slcAcknowledgeEdit'),
			'name_approve' => $this->input->post('slcApproveText'),
			'userid_approve' => $this->input->post('slcApproveEdit'),
			'update_date' => date('Y-m-d H:i:s'),
			'update_userid' => $this->session->userdata('userIdMyApps')
		);

		$formUpdateSuccess = $this->myapp->updateDataDb6('form', $formData, array('id' => $txtIdForm));
		$responseMessage = $formUpdateSuccess ? "Update Success..!!" : "Update form failed.";

		$details = array();
		$arrDescriptions = is_array($data['txtdescriptionEdit']) ? $data['txtdescriptionEdit'] : array();
		$arrTypes = is_array($data['txttypeEdit']) ? $data['txttypeEdit'] : array();
		$arrReasons = is_array($data['txtreasonEdit']) ? $data['txtreasonEdit'] : array();
		$arrQuantities = is_array($data['txtquantityEdit']) ? $data['txtquantityEdit'] : array();
		$arrNotes = is_array($data['txtnoteEdit']) ? $data['txtnoteEdit'] : array();
		$arrIdDetails = is_array($data['txtIdDetail']) ? $data['txtIdDetail'] : array();
		$arrIsDeleted = is_array($data['txtIdDetail_isDeleted']) ? $data['txtIdDetail_isDeleted'] : array();

		$numEntries = count($arrDescriptions);

		for ($i = 0; $i < $numEntries; $i++) {

			$dataToUpdate = array(
				'description' => $arrDescriptions[$i],
				'type' => $arrTypes[$i],
				'reason' => $arrReasons[$i],
				'quantity' => $arrQuantities[$i],
				'note' => $arrNotes[$i],
				'update_userid' => $this->session->userdata('userIdMyApps'),
				'update_date' => date('Y-m-d')
			);

			$idDetail = isset($arrIdDetails[$i]) ? $arrIdDetails[$i] : null;

			if ($idDetail) {
				$updateSuccess = $this->myapp->updateDataDb6('form_detail', $dataToUpdate, array('id' => $idDetail));
				if ($updateSuccess) {
					$details[] = $dataToUpdate;
				} else {
					log_message('info', 'No rows updated for id_detail: ' . $idDetail);
				}
			} else {
				$dataToUpdate['id_form'] = $txtIdForm;
				$insertId = $this->myapp->insDataDb6($dataToUpdate, 'form_detail');
				if ($insertId) {
					$dataToUpdate['id'] = $insertId; 
					$details[] = $dataToUpdate;
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
	
 	function updateSubmitStatus()
	{
		$IdForm = $this->input->post('id');
		$acknowledgeEmail = $this->input->post('acknowledgeEmail');
		$approveEmail = $this->input->post('approveEmail');
		$userid_submit = $this->session->userdata('userIdMyApps');
		$date_submit = date('Y-m-d');

		if (empty($acknowledgeEmail) || empty($approveEmail)) {
			echo json_encode(array('status' => 'failed', 'message' => 'Acknowledge and Approve emails must be selected.'));
			return;
		}

		$data = array(
			'st_submit' => 'Y',
			'userid_submit' => $userid_submit,
			'date_submit' => $date_submit
		);

		$this->myapp->updateDataDb6('form', $data, array('id' => $IdForm));
		$this->addDataMyAppLetter($IdForm);

		$emailResult = $this->sendRemindByEmail($IdForm, $acknowledgeEmail, $approveEmail);

		echo json_encode(array_merge(array('status' => 'success'), $emailResult));
	}

	function acknowledgeData() {
		$id_form = $this->input->post('id');
		$userid_submit = $this->session->userdata('userIdMyApps');
		$date_submit = date('Y-m-d');
		$userAcknowledge = $this->session->userdata('fullNameMyApps');
		$dateAcknowledge = date('Y-m-d');
		$qrcodeAck = $this->createQRCode($id_form, 'ack');
		$status = '';
		
		
		$data = array(
			'st_acknowledge' => 'Y',
			'userid_submit' => $userid_submit,
			'date_submit' => $date_submit,
			'user_acknowledge' => $userAcknowledge,
			'date_acknowledge' => $dateAcknowledge,
			'qrcode_acknowledge' => $qrcodeAck
		);
		
		try {
			$this->myapp->updateDataDb6('form', $data, array('id' => $id_form));
			$status = "success";
		} catch (\Throwable $ex) {
			$status = "Failed".$ex->getMessage();
		}
		
		print json_encode($status);
	}

	function approveData()
	{
		$id_form = $this->input->post('id');
		$userid_submit = $this->session->userdata('userIdMyApps');
		$date_submit = date('Y-m-d');
		$userApprove = $this->session->userdata('fullNameMyApps');
		$dateApprove = date('Y-m-d');
		$qrcodeApp = $this->createQRCode($id_form, 'app');
		$status = '';

		$data = array(
			'st_approval' => 'Y',
			'userid_submit' => $userid_submit,
			'date_submit' => $date_submit,
			'user_approve' => $userApprove,
			'date_approve' => $dateApprove,
			'qrcode_approve' => $qrcodeApp,
		);

		try {
			$this->myapp->updateDataDb6('form', $data, array('id' => $id_form));
			$status = "success";
		} catch (\Throwable $ex) {
			$status = "Failed".$ex->getMessage();
		}
		
		print json_encode($status);
	}


	function getAcknowledgeData() {
		$userType = $this->session->userdata('userTypeMyApps');
		$userDiv = trim($this->input->get('divisi')); 
    	$userDept = trim($this->input->get('department')); 

		$where = "WHERE st_submit = 'Y' 
				AND st_acknowledge = 'N' 
				AND sts_delete = 0";
		

		if ($userType == 'admin') {
			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
					FROM form " . $where;
		} elseif ($userDiv === 'OFFICE OPERATION' && $userDept === 'INFORMATION TECHNOLOGY'){
			$sql = "SELECT id, project_reference, purpose, company, location, divisi, department, sts_delete, batchno
                FROM form " . $where;
		} 
		else {

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
				if (!empty($userDiv)) {
					$where .= " AND divisi = '" . $userDiv . "'";
				}
				if (!empty($userDept)) {
					$where .= " AND department = '" . $userDept . "'";
				}
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

	function sendRemindByEmail($IdForm = "", $acknowledgeEmail = "", $approveEmail = "")
	{
		$mail = new PHPMailer(true);
		$subject = "";
		$isiEmail = "";

		$sql = "SELECT id, project_reference, purpose, request_name 
				FROM form 
				WHERE sts_delete = '0' AND id = '" . $IdForm . "'";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		if (count($rsl) > 0) {
			$subject = "Waiting Acknowledge and Approve Form IT Request From " . $rsl[0]->request_name;
			$isiEmail = $this->getContentSendMail($IdForm, $rsl[0]->request_name);

			$recipients = array($acknowledgeEmail, $approveEmail);

			try {
				
				$mail->isSMTP();
				$mail->Host = 'smtp.zoho.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'noreply@andhika.com';
				$mail->Password = 'PCWLzCWDQH8C';
				$mail->SMTPSecure = 'tls';
				$mail->Port = 587;

				$mail->setFrom('noreply@andhika.com', 'IT Request Notification');

			
				foreach ($recipients as $email) {
					$mail->addAddress($email);
				}

				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body = $isiEmail;

				if ($mail->send()) {
					return array('status' => 'success', 'message' => 'Data has been sent to Acknowledge.');
				} else {
					return array('status' => 'failed', 'message' => $mail->ErrorInfo);
				}
			} catch (Exception $e) {
				return array('status' => 'failed', 'message' => $e->getMessage());
			}
		}

		return array('status' => 'failed', 'message' => 'No data found for the provided form ID.');
	}


	function getContentSendMail($IdForm = '', $reqName = '')
	{
		$data = $this->getisiContent($IdForm);
		
		$isiMessage = "";

		$isiMessage .= "<p>";
			$isiMessage .= "*************************************************<br>";
			$isiMessage .= "PLEASE DO NOT REPLY THIS EMAIL..!!<br>";
			$isiMessage .= "*************************************************<br>";
		$isiMessage .= "</p>";

		$isiMessage .= "<b>&nbsp;***** ".$reqName." Send Form IT Request. Please Acknowledge and Approve it. *****</b>";

		$isiMessage .= "<table width=\"800px\" border=\"1\" cellpadding=\"10\" cellspacing=\"0\" style=\"margin-top:30px; border-collapse:collapse; font-family: Arial, sans-serif; color: #333; border: 1px solid #ddd;\">";
			$isiMessage .= $data["tr"];
		$isiMessage .= "</table>";

		$isiMessage .= "<p style=\"margin-top:20px; font-size:16px; font-weight:bold; text-align:center; color:#0056b3;\"><i>:::</i> Detail Form <i>:::</i></p>";

		$isiMessage .= "<table width=\"800px\" border=\"1\" cellpadding=\"10\" cellspacing=\"0\" style=\"border-collapse:collapse; font-family: Arial, sans-serif; color: #333; border: 1px solid #ddd;\">";
		$isiMessage .= "<thead>";
		$isiMessage .= "<tr style=\"background-color:#f1f1f1; color:#333; font-weight:bold; text-align:center;\">";
		$isiMessage .= "<th style=\"padding:8px; border: 1px solid #ddd;\">Description</th>";
		$isiMessage .= "<th style=\"padding:8px; border: 1px solid #ddd;\">Type</th>";
		$isiMessage .= "<th style=\"padding:8px; border: 1px solid #ddd;\">Quantity</th>";
		$isiMessage .= "<th style=\"padding:8px; border: 1px solid #ddd;\">Reason</th>";
		$isiMessage .= "<th style=\"padding:8px; border: 1px solid #ddd;\">Note</th>";
		$isiMessage .= "</tr>";
		$isiMessage .= "</thead>";
		$isiMessage .= "<tbody>";
		$isiMessage .= $data["trDet"];
		$isiMessage .= "</tbody>";
		$isiMessage .= "</table>";


		$isiMessage .= "<p>To respon this Request, please check <a href=\"http://myapps.andhika.com/observasi/myapps\" target=\"_blank\">www.myapps.andhika.com</a>. For kadept check in Acknowledge Menu, and for kadiv check in Approve menu.</p>";

		$isiMessage .= "<p>";
			$isiMessage .= "*************************************************<br>";
			$isiMessage .= "END OF NOTIFICATION<br>";
			$isiMessage .= "*************************************************<br>";
		$isiMessage .= "</p>";

		return $isiMessage;
	}

	function getisiContent($IdForm = '')
	{
		$dataOut = array();
		$tr = '';
		$trDet = '';

		$sql = "SELECT * FROM form WHERE id = '".$IdForm."' AND sts_delete = '0'";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $value)
		{
			$tr .= "<tr>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Project Reference</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->project_reference."</td>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Purpose</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->purpose."</td>";
				$tr .= "</tr>";
				$tr .= "<tr>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Company</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->company."</td>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Location</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->location."</td>";
				$tr .= "</tr>";
				$tr .= "<tr>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Divisi</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->divisi."</td>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Department</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->department."</td>";
				$tr .= "</tr>";
				$tr .= "<tr>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Required Date</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$this->convertReturnName($value->required_date)."</td>";
				$tr .= "</tr>";
				$tr .= "<tr>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Request Name</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->request_name."</td>";
				$tr .= "<td style=\"vertical-align:top; width:15%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; font-weight:bold;\">Date Request</td>";
				$tr .= "<td style=\"vertical-align:top; width:35%; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080;\"> ".$value->date_submit."</td>";
			$tr .= "</tr>";

		}

		$sqlDet = "SELECT * FROM form_detail WHERE id_form = '".$IdForm."' AND sts_delete = '0'";
		$rslDet = $this->myapp->getDataQueryDB6($sqlDet);

		foreach($rslDet as $key => $value)
		{
			$trDet .= "<tr>";	
				$trDet .= "<td align=\"center\" style=\"vertical-align:top; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080; font-size:12px;\">".$value->description."</td>";
				$trDet .= "<td align=\"center\" style=\"vertical-align:top; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080; font-size:12px;\">".$value->type."</td>";
				$trDet .= "<td align=\"center\" style=\"vertical-align:top; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080; font-size:12px;\">".$value->quantity."</td>";
				$trDet .= "<td align=\"center\" style=\"vertical-align:top; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080; font-size:12px;\">".$value->reason."</td>";
				$trDet .= "<td align=\"center\" style=\"vertical-align:top; padding:8px; background-color:#f9f9f9; border: 1px solid #ddd; color:#004080; font-size:12px;\">".$value->note."</td>";
			$trDet .= "</tr>";

		}
		
		$dataOut['tr'] = $tr;
		$dataOut['trDet'] = $trDet;

		return $dataOut;
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

	function createQRCode($id = "", $type = '')
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
		
		if($type == 'ack')
		{
			$imgName = 'acknowledge_'.base64_encode($id).'.jpg';
		}
		if($type == 'app')
		{
			$imgName = 'approve_'.base64_encode($id).'.jpg';
		}
		
		$params['data'] = "http://apps.andhika.com/observasi/myLetter/viewLetter/".base64_encode($id); 
		$params['level'] = 'H'; 
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; 
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); 

    	return $imgName;
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
	
	function printPdf($id) {
		
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
				'qrCodeAcknowledge' => null,
				'qrCodeApprove' => null,
				'nameKadept' => isset($form[0]->name_acknowledge) ? $form[0]->name_acknowledge : 'Not acknowledged',
    			'nameKadiv' => isset($form[0]->name_approve) ? $form[0]->name_approve : 'Not approved',
			);
		
			if ($form[0]->st_acknowledge == 'Y') {
				$data['qrCodeAcknowledge'] = "<img src=\"" . base_url("assets/imgQRCodeForm/" . $this->createQRCode($form[0]->id, 'ack')) . "\" alt=\"QR Code Acknowledge\" height=\"100\" width=\"100\" />";
			}

			if ($form[0]->st_approval == 'Y') {
				$data['qrCodeApprove'] = "<img src=\"" . base_url("assets/imgQRCodeForm/" . $this->createQRCode($form[0]->id, 'app')) . "\" alt=\"QR Code Approval\" height=\"100\" width=\"100\" />";
			}
			
			$this->load->view('myApps/previewPrint', $data);
		} else {
			show_error('Form not found', 404);
		}
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
				
			$form_details = array_filter($form_details, function($detail) {
				return !empty($detail->description) && !empty($detail->type) && !empty($detail->reason) && $detail->quantity > 0;
			});

			$qrCodeImgPath = base_url("assets/imgQRCodeForm/" . base64_encode($form[0]->batchno) . ".jpg");
				
			$data = array(
				'form' => $form[0],
				'form_details' => $form_details,
				'note' => isset($form[0]->note) ? $form[0]->note : 'No notes available',
				'imageLogo' => "<img src=\"" . base_url($logo_company) . "\" alt=\"Company Logo\" height=\"50\" style=\"align-items: left; margin-bottom: -50px;\">",
				'qrCode' => "<img src=\"" . $qrCodeImgPath . "\" alt=\"QR Code\" height=\"100\" width=\"100\" />",
				'qrCodeAcknowledge' => null,
				'qrCodeApprove' => null,
				'nameKadept' => isset($form[0]->name_acknowledge) ? $form[0]->name_acknowledge : 'Not acknowledged',
				'nameKadiv' => isset($form[0]->name_approve) ? $form[0]->name_approve : 'Not approved',
				'button' => $button
			);	
			if ($form[0]->st_acknowledge == 'Y') {
				$data['qrCodeAcknowledge'] = "<img src=\"" . base_url("assets/imgQRCodeForm/".$this->createQRCode($form[0]->id, 'ack'))."\" alt=\"QR Code Acknowledge\" height=\"100\" width=\"100\" />";
			}
			if ($form[0]->st_approval == 'Y') {
				$data['qrCodeApprove'] = "<img src=\"" . base_url("assets/imgQRCodeForm/".$this->createQRCode($form[0]->id, 'app'))."\" alt=\"QR Code Approval\" height=\"100\" width=\"100\" />";
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

		} else {
			show_error('Form not found', 404);
		}
	}

	function userSetting() {
		$userid = $this->session->userdata('userIdMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$query = "SELECT * FROM form_usersetting WHERE userid ='" . $userid . "' AND st_delete = '0'";
		$result = $this->myapp->getDataQueryDB6($query); 

		$buttonAck = "";
		$buttonApp = "";
		$dataOut = array();

		if ($userType === 'admin') {

			$buttonApp = '<div class="col-md-4">
							<button class="btn btn-primary btn-block" onclick="changeBtnNavigation(\'approval\');">
								<label>Approval</label>
							</button>
						</div>';
			$buttonAck = '<div class="col-md-4">
							<button class="btn btn-primary btn-block" onclick="changeBtnNavigation(\'acknowledge\');">
								<label>Acknowledge</label>
							</button>
						</div>';
		} else if (count($result) > 0) {
			if ($result[0]->nmDiv != "") {
				$buttonApp = '<div class="col-md-4">
								<button class="btn btn-primary btn-block" onclick="changeBtnNavigation(\'approval\');">
									<label>Approval</label>
								</button>
							</div>';
			}
			if ($result[0]->nmDept != "") {
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
	
	function saveFormRequestWithDetail() {
		$data = $this->input->post();
		$status = "";
		$dateNow = date("Y-m-d");
		$userId = $this->session->userdata('userIdMyApps');
		$reqName = $this->session->userdata('fullNameMyApps');
		$IdForm = $data['txtIdForm'];
		$response = array();
		$acknowledgeUserId = isset($data['slcAcknowledge']) ? $data['slcAcknowledge'] : null;
		$approveUserId = isset($data['slcApprove']) ? $data['slcApprove'] : null;

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
				'request_name' => $reqName,
				'name_acknowledge' => $data['slcAcknowledgeText'],
				'userid_acknowledge' => $acknowledgeUserId,
				'name_approve' => $data['slcApproveText'],
				'userid_approve' => $approveUserId,
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
				'request_name' => $reqName,
				'name_acknowledge' => $data['slcAcknowledgeText'],
				'userid_acknowledge' => $acknowledgeUserId,
				'name_approve' => $data['slcApproveText'],
				'userid_approve' => $approveUserId,
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

		$txtIdForm = $IdForm;  
		$arrDescriptions = explode('*', $data['descriptions']);
		$arrTypes = explode('*', $data['types']);
		$arrReasons = explode('*', $data['reasons']);
		$arrQuantities = explode('*', $data['quantities']);
		$arrNotes = explode('*', $data['notes']);
		$numEntries = count($arrDescriptions);
		$responseMessage = "";

		for ($i = 0; $i < $numEntries; $i++) {
			if (empty($arrDescriptions[$i]) || empty($arrTypes[$i]) || empty($arrReasons[$i]) || $arrQuantities[$i] <= 0) {
				continue;
			}
			
			$dataToInsert = array(
				'id_form'       => $txtIdForm,  
				'description'   => $arrDescriptions[$i],
				'type'          => $arrTypes[$i],
				'reason'        => $arrReasons[$i],
				'quantity'      => $arrQuantities[$i],
				'note'          => $arrNotes[$i],
				'add_userid'    => $userId,
				'add_date'      => $dateNow,
				'request_name'  => $reqName
			);

			try {
				$this->myapp->insDataDb6($dataToInsert, 'form_detail');
				$responseMessage = "Insert Success..!!";
			} catch (Exception $e) {
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

		$response['detail_status'] = $responseMessage;

		echo json_encode($response);
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
			$optNya .= "<option value=\"".$value->nmcmp."\">".$value->nmcmp."</option>";
		}
		return $optNya;
	}

	function getOptAcknowledge()
	{
		$optNya = "<option value=\"\">- Select -</option>";

		$sql = "SELECT userid, userfullnm, useremail FROM login WHERE userid IN ('00121', '00027', '00130', '00162', 
				'00092', '00118', '00178', '00002', '00128', '00172', '00030', '00151', '00012', '00107') 
				AND deletests = 0";
	
		$result = $this->myapp->getDataQueryDb2($sql);

		foreach ($result as $val) {
			$emailWithDomain = "{$val->useremail}@andhika.com";
			$optNya .= "<option value=\"{$val->userid}\" data-email=\"{$emailWithDomain}\">{$val->userfullnm}</option>";
		}

		return $optNya;
	}

	function getOptApprove()
	{
		$optNya = "<option value=\"\">- Select -</option>";

		$sql = "SELECT userid, userfullnm, useremail FROM login WHERE userid IN ('00054', '00061', '00116', '00166', 
				'00053', '00032') 
				AND deletests = 0";

		$result = $this->myapp->getDataQueryDb2($sql);

		foreach ($result as $val) {
			$emailWithDomain = "{$val->useremail}@andhika.com";
			$optNya .= "<option value=\"{$val->userid}\" data-email=\"{$emailWithDomain}\">{$val->userfullnm}</option>";
		}

		return $optNya;
	}

	function convertReturnName($dateNya = "")
	{
		$dt = explode("-", $dateNya);
		$tgl = $dt[2];
		$bln = $dt[1];
		$thn = $dt[0];
		if($bln == "01" || $bln == "1"){ $bln = "Jan"; }
		else if($bln == "02" || $bln == "2"){ $bln = "Feb"; }
		else if($bln == "03" || $bln == "3"){ $bln = "Mar"; }
		else if($bln == "04" || $bln == "4"){ $bln = "Apr"; }
		else if($bln == "05" || $bln == "5"){ $bln = "Mei"; }
		else if($bln == "06" || $bln == "6"){ $bln = "Jun"; }
		else if($bln == "07" || $bln == "7"){ $bln = "Jul"; }
		else if($bln == "08" || $bln == "8"){ $bln = "Agus"; }
		else if($bln == "09" || $bln == "9"){ $bln = "Sep"; }
		else if($bln == "10"){ $bln = "Okt"; }
		else if($bln == "11"){ $bln = "Nov"; }
		else if($bln == "12"){ $bln = "Des"; }

		return $tgl." ".$bln." ".$thn;
	}

} 