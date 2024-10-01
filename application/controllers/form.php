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
		$nmDiv = $this->session->userdata('nmDiv');
		$tr = '';  
		$where = "WHERE sts_delete = '0' ";
		$no = 1;
		$userType = $this->session->userdata('userTypeMyApps');
		$fullName = $this->session->userdata('fullNameMyApps');
		$btnDetail = "";
		$btnView = "";
		$btnDelete = "";
		$btnDelete = "";
		

		$sql = "SELECT * FROM form " . $where;
		$data = $this->myapp->getDataQueryDB6($sql);

		foreach($data as $key => $value) {
			$status = '';
			
			if($value->st_submit === 'Y' && $value->st_acknowledge === 'N') {
				$status = "Waiting Acknowledge <i class='fa fa-clock-o'></i>";
			}

			if($value->st_acknowledge === 'Y' && $value->st_approval === 'N') {
				$status = "Waiting Approval <i class='fa fa-clock-o'></i>";
			}

			if($value->st_approval === 'Y') {
				$status = "Approve Success <i class='fa fa-check'></i>";
			}
			
			if ($value->st_detail === 'Y') {
				$btnDetail = "<button onclick=\"editData('".$value->id."');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button>";
			} else {
				$btnDetail = "<button onclick=\"addDetail('".$value->id."');\" title=\"Add Detail\" class=\"btn btn-primary btn-xs\" id=\"btnAdd\" type=\"button\"><i class=\"glyphicon glyphicon-plus\"></i></button>";
			}

			if($value->st_submit === 'Y') {
				$btnExport = "<button onclick=\"ViewPrint('".$value->id."');\" class=\"btn btn-success btn-xs\" type=\"button\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>";
				$btnDetail = '';
				$btnDelete = '';
				$btnSubmit = '';
			} else {
				$btnExport = "<button onclick=\"ViewPrint('".$value->id."');\" class=\"btn btn-success btn-xs\" id=\"btnView_".$value->id."\" type=\"button\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>";
				$btnDelete = "<button onclick=\"delData('".$value->id."');\" class=\"btn btn-danger btn-xs\" id=\"btnDelete_".$value->id."\" type=\"button\" title=\"Delete\"><i class=\"fa fa-trash-o\"></i> Delete</button>";
				$btnSubmit = "<button onclick=\"sendData('".$value->id."');\" class=\"btn btn-primary btn-xs\" id=\"btnSubmit_".$value->id."\" type=\"button\" title=\"Submit\"><i class=\"fa fa-send-o\"></i> Send</button>";
			}

			
			$tr .= "<tr id='row_".$value->id."'>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$no."</td>";
				$tr .= "<td align='center'>".$btnDetail."</td>"; 
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->project_reference."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->purpose."</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;'>".$value->company."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->location."</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;'>".$value->divisi."</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;' id='status_".$value->id."'>".$status."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$btnExport.$btnDelete.$btnSubmit."</td>";
			$tr .= "</tr>";


			$no++;
		}

		$dataOut['tr'] = $tr;
		$dataOut['getOptCompany'] = $this->getOptCompany(); 
		$dataOut['getOptMstDivisi'] = $this->getOptMstDivisi();
		$this->load->view('myApps/form', $dataOut);
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
		$sessionDiv = $this->session->userdata('nmDiv');
		
		$sql = "SELECT id, project_reference, purpose, company, location, divisi, sts_delete 
				FROM form 
				WHERE st_submit = 'Y' 
				AND st_acknowledge = 'N' AND divisi LIKE '%$sessionDiv%'
				AND sts_delete = 0";

		$query = $this->myapp->getDataQueryDB6($sql);

		if ($query) {
			$resultArray = array();

			foreach ($query as $row) {
				$resultArray[] = array(
					'id' => $row->id,
					'project_reference' => $row->project_reference,
					'purpose' => $row->purpose,
					'company' => $row->company,
					'location' => $row->location,
					'divisi' => $row->divisi,
					'sts_delete' => $row->sts_delete
				);
			}

			echo json_encode(array('data' => $resultArray));
		} else {
			echo json_encode(array('data' => array()));
		}
	}

	function getApprovalData()
	{
		$sql = "SELECT id, project_reference, purpose, company, location, divisi, sts_delete
				FROM form
				WHERE st_acknowledge = 'Y' 
				AND st_approval = 'N'
				AND sts_delete = 0";

		$query = $this->myapp->getDataQueryDB6($sql);

		if($query)
		{
			$resulArrayApproval = array();
			foreach ($query as $value) {
				$resulArrayApproval[] = array(
					'id' => $value->id,
					'project_reference' => $value->project_reference,
					'purpose' => $value->purpose,
					'company' => $value->company,
					'location' => $value->location,
					'divisi' => $value->divisi,
					'sts_delete' => $value->sts_delete
				);
			}
			echo json_encode(array('data' => $resulArrayApproval));
		}
		else{
			echo json_encode(array('data' => array()));
		}
	}
	
	function editData() {
		$idForm = $this->db->escape_str($this->input->post('idForm'));

		$sql = "SELECT * FROM form_detail WHERE id_form = '$idForm' AND sts_delete = '0'";
		$result = $this->myapp->getDataQueryDB6($sql);

		print json_encode($result);
	}

	function saveEditedData() {
		$data = $this->input->post();	

		if (isset($data['formData']) && isset($data['txtIdEditForm'])) {
			$formData = $data['formData'];
			$idForm = $data['txtIdEditForm'];

			if ($formData && $idForm) {
				foreach ($formData as $data) {
					$description = isset($data['txtdescription']) ? $data['txtdescription'] : null;
					$type = isset($data['txttype']) ? $data['txttype'] : null;

					if ($description && $type) {
						$dataToUpdate = array(
							'description' => $description,
							'type' => $type,
							'reason' => isset($data['txtreason']) ? $data['txtreason'] : null,
							'quantity' => isset($data['txtquantity']) ? $data['txtquantity'] : null,
							'required_date' => isset($data['txtrequired_date']) ? $data['txtrequired_date'] : null,
							'note' => isset($data['note']) ? $data['note'] : null,
							'update_userId' => $this->session->userdata('userIdMyApps'),
							'update_date' => date('Y-m-d H:i:s')
						);

						// Update data di database
						$result = $this->myapp->updateDataDb6($dataToUpdate, 'form_detail', array(
							'id_form' => $idForm,
						));
					}
				}

				echo json_encode(array('success' => true, 'message' => 'Data successfully updated!'));
			} else {
				echo json_encode(array('success' => false, 'message' => 'No data to update or missing id_form.'));
			}
		} else {
			echo json_encode(array('success' => false, 'message' => 'Invalid request data.'));
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
		$config['white']		= array(0,0,128);//untuk ubah warna di libralies/qrcode/qrimage.php white default 0,0,0
		$this->ciqrcode->initialize($config);

		$imgName = base64_encode($id).'.jpg';

		$params['data'] = "http://apps.andhika.com/observasi/myLetter/viewLetter/".base64_encode($id); //data yang akan di jadikan QR CODE
		$params['level'] = 'H'; //H=High
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; //simpan image QR CODE ke folder assets/images/
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
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

				// Update the form table with the batchno
				$updateSql = array('batchno' => $batchno);
				$this->myapp->updateDataDb6("form", $updateSql, array('id' => $IdForm));

				// Generate QR code with batchno
				$imgName = $this->createQRCode($batchno);
			}
		} catch (Exception $e) {
			$imgName = "Failed => " . $e->getMessage();
		}
		return $imgName;
	}

	function previewPrint($id) {
		$id = intval($id);
		$logo_company = "/assets/img";
		$kadept = "";
		$kadiv = "";

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

		if (count($form) > 0) {
			$queryFormDetail = "SELECT * FROM `form_detail` WHERE `id_form` = $id AND `sts_delete` = 0";
			$form_details = $this->myapp->getDataQueryDB6($queryFormDetail);

			$qrCodeImgPath = base_url("assets/imgQRCodeForm/" . base64_encode($form[0]->batchno) . ".jpg");

			// Prepare base data
			$data = array(
				'form' => $form[0],
				'form_details' => $form_details,
				'imageLogo' => "<img src=\"" . base_url($logo_company) . "\" alt=\"Company Logo\" height=\"50\" style=\"align-items: left; margin-bottom: -50px;\">",
				'qrCode' => "<img src=\"" . $qrCodeImgPath . "\" alt=\"QR Code\" height=\"100\" width=\"100\" />",  // User QR code from batchno
				'kadept' => null,
				'kadiv' => null,
				'nameKadept' => null,
				'nameKadiv' => null
			);

			$mappingInfo = $this->getMappingInfo($form[0]->divisi, $form[0]->department);
	
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

	function getMappingInfo($division, $department) {
		
		$batchno = $this->getBatchNo();

		$queryBatch = "SELECT * FROM `form` WHERE `batchno` = '" . $this->db->escape_str($batchno) . "' AND sts_delete = '0'";
		$requestData = $this->myapp->getDataQueryDB6($queryBatch);

		if ($requestData) {
			$requestName = $requestData->request_name;
		} else {
			$requestName = 'Nama Request'; 
		}
		
		$qrcodeFile = '/assets/imgQRCodeForm/' . base64_encode($requestData[0]->batchno) . '.jpg';
		
		$Mapping = array(
			'BOD / BOC' => array(
				'Non Department' => array(
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namafileKadept' => $qrcodeFile, 
					'acknowledgeKadept' => $requestName
				),
				'PA (Personal Assistent' => array(
					'nameKadiv' => 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namafileKadept' => $qrcodeFile,
					'acknowledgeKadept' => $requestName	
				),
			),
			'CORPORATE FINANCE, STRATEGY & COMPLIANCE' => array(
				'Non Department' => array(
					'nameKadiv' => 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namafileKadept' => $qrcodeFile,
					'acknowledgeKadept' => $requestName	
				)
			),
			'DRY BULK COMMERCIAL,OPERATION & AGENCY' => array(
				'Commercial' => array(
					'nameKadiv' => 'Ferry Nugroho',
					'namafileKadiv' => '/assets/ImgQRCodeForm/FerryNugroho.jpg',
					'approveKadiv' => 'Ferry Nugroho ',
					'nameKadept' => 'Rahadian Herbisworo',
					'namefileKadept' => '/assets/ImgQRCodeForm/RahadianHerbisworo.jpg',
					'acknowledgeKadept' => 'Rahadian Herbisworo'
				),
				'Operation' => array(
					'nameKadiv' => 'Ferry Nugroho',
					'namafileKadiv' => '/assets/ImgQRCodeForm/TimbulRiyadi.jpg',
					'approveKadiv' => 'Ferry Nugroho',
					'nameKadept' => 'Timbul Riyadi',
					'namefileKadept' => '/assets/ImgQRCodeForm/RahadianHerbisworo.jpg',
					'acknowledgeKadept' => 'Timbul Riyadi'
				),
				'Agency' => array(
					'nameKadiv' => 'Ferry Nugroho',
					'namafileKadiv' => '/assets/ImgQRCodeForm/TimbulRiyadi.jpg',
					'approveKadiv' => 'Ferry Nugroho',
					'nameKadept' => 'Timbul Riyadi',
					'namefileKadept' => '/assets/ImgQRCodeForm/RahadianHerbisworo.jpg',
					'acknowledgeKadept' => 'Timbul Riyadi'
				)
			),
			'FINANCE' => array(
				'Finance' => array(
					'nameKadiv' => 'Sylvia Panghuriany',
					'namafileKadiv' => '/assets/ImgQRCodeForm/Sylvia.jpg',
					'approveKadiv' => 'Sylvia Panghuriany',
					'nameKadept' => 'Marita',
					'namefileKadept' => '/assets/ImgQRCodeForm/Marita.jpg',
					'acknowledgeKadept' => 'Marita'
				),
				'Accounting' => array(
					'nameKadiv' => 'Sylvia Panghuriany',
					'namafileKadiv' => '/assets/ImgQRCodeForm/Sylvia.jpg',
					'approveKadiv' => 'Sylvia Panghuriany', 
					'nameKadept' => 'Riko Ramdani',
					'namefileKadept' => '/assets/ImgQRCodeForm/RikoRamdani.jpg',
					'acknowledgeKadept' => 'Riko Ramdani'
				),
				'Tax' => array(
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
				'Secretary' => array(
					'nameKadiv' => 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => $requestName,
					'namafileKadept' => $qrcodeFile,
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
					'aknowledgeKadept' => 'Hendra Roesli' 
				),
				'Legal' => array(
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => 'Pribadi Arijanto',
					'namefileKadept' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'aknowledgeKadept' => 'Pribadi Arijanto' 
				),
				'Procurement' => array(
					'nameKadiv'=> 'Pribadi Arijanto',
					'namafileKadiv' => '/assets/ImgQRCodeForm/PribadiArijanto.jpg',
					'approveKadiv' => 'Pribadi Arijanto',
					'nameKadept' => 'Deffandra Putra',
					'namefileKadept' => '/assets/ImgQRCodeForm/DeffandraPutra.jpg',
					'acknowledgeKadept' => 'Deffandra Putra'
				)
			),
			'OIL & GAS COMMERCIAL & OPERATION' => array(
				'Commercial' => array(
					'nameKadiv'=> 'Nick Djatnika',
					'namafileKadiv' => '/assets/ImgQRCodeForm/NickDjatnika.jpg',
					'approveKadiv' => 'Nick Djatnika',
					'nameKadept' => 'Aditya Ilham Nusantara',
					'namefileKadept' => '/assets/ImgQRCodeForm/Adityailham.jpg',
					'acknowledgeKadept' => 'Aditya Ilham Nusantara'
				),
				'Operation' => array(
					'nameKadiv'=> 'Nick Djatnika',
					'namafileKadiv' => '/assets/ImgQRCodeForm/NickDjatnika.jpg',
					'approveKadiv' => 'Nick Djatnika',
					'nameKadept' => 'Aditya Ilham Nusantara',
					'namefileKadept' => '/assets/ImgQRCodeForm/Adityailham.jpg',
					'acknowledgeKadept' => 'Aditya Ilham Nusantara'
				)
			),
			'SHIP MANAGEMENT' => array(
				'Owner Superintendent (Technical)' => array(
					'nameKadiv'=> 'Eddy Sukmono',
					'namafileKadiv' => '/assets/ImgQRCodeForm/EddySukmono.jpg',
					'approveKadiv' => 'Eddy Sukmono',
					'nameKadept' => 'Hari Joko Purnomo',
					'namefileKadept' => '/assets/ImgQRCodeForm/HariJoko.jpg',
					'acknowledgeKadept' => 'Hari Joko'
				),
				'Crewing' => array(
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
					return $Mapping[$division][$department]; // Return by division and department
				} elseif (isset($Mapping[$division]['department']) && $Mapping[$division]['department'] == $department) {
					return $Mapping[$division]; // Return by division only (if no department match)
				}
			}
		}

		// Return null if no match found
		return null;
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

		if (empty($IdForm)) {
			$dataIns = array(
				'batchno' => $this->getBatchNo(),
				'project_reference' => $data['txtprojectReference'],
				'purpose'           => $data['txtpurpose'],
				'company'           => $data['slcCompanyText'],
				'init_cmp'          => $data['slcCompany'], 
				'department'		=> $data['slcDepartment'],
				'location'          => $data['txtlocation'],
				'divisi'            => $data['slcDivisi'],
				'userid_submit'     => $userId,
				'add_date'          => $dateNow,
				'request_name'      => $reqName
			);
			try {
				// Insert data
				$this->myapp->insDataDb6($dataIns, 'form');
				$IdForm = $this->db->insert_id(); // Get the last inserted form ID
				$this->addDataMyAppLetter($IdForm); // Add to the MyAppLetter
				$status = "Insert Success..!!";
			} catch (Exception $ex) {
				$status = "Failed => " . $ex->getMessage();
			}
		} else {
			$dataUpd = array(
				'project_reference' => $data['txtprojectReference'],
				'purpose'           => $data['txtpurpose'],
				'company'           => $data['slcCompanyText'], 
				'init_cmp'          => $data['slcCompany'],
				'department'		=> $data['slcDepartement'],
				'location'          => $data['txtlocation'],
				'divisi'            => $data['slcDivisi'],
				'update_userid'     => $userId,
				'update_date'       => $dateNow,
				'request_name'      => $reqName
			);

			try {
				$where = "id = '" . $IdForm . "'";
				// Update data
				$this->myapp->updateDataDb6("form", $dataUpd, $where);
				$this->addDataMyAppLetter($IdForm); // Ensure batchno is updated
				$status = "Update Success..!!";
			} catch (Exception $ex) {
				$status = "Failed => " . $ex->getMessage();
			}
		}

		print json_encode($status);
	}

	
	function saveFormRequestDetail() {
		$data = $this->input->post(); 
		$txtIdForm = $data['id_form'];  
		$currentDate = date("Y-m-d");
		$responseMessage = "";

		// Split the received string data into arrays
		$arrDescriptions = explode('*', $data['descriptions']);
		$arrTypes = explode('*', $data['types']);
		$arrReasons = explode('*', $data['reasons']);
		$arrQuantities = explode('*', $data['quantities']);
		$arrRequiredDates = explode('*', $data['required_dates']);
		$arrNotes = explode('*', $data['notes']);

		
		$numEntries = count($arrDescriptions);

		for ($i = 0; $i < $numEntries; $i++) {
			// Prepare data for insertion
			$dataToInsert = array(
				'id_form'       => $txtIdForm,  // Form ID remains constant
				'description'   => $arrDescriptions[$i],
				'type'          => $arrTypes[$i],
				'reason'        => $arrReasons[$i],
				'quantity'      => $arrQuantities[$i],
				'required_date' => $arrRequiredDates[$i],
				'note'          => $arrNotes[$i],
				'add_userid'    => $this->session->userdata('userIdMyApps'),
				'add_date'      => $currentDate,
				'request_name'  => $this->session->userdata('fullNameMyApps')
			);

			// Attempt to insert the record
			try {
				$this->myapp->insDataDb6($dataToInsert, 'form_detail');
				$responseMessage = "Insert Success..!!";
			} catch (Exception $e) {
				$responseMessage = "Failed to Insert: " . $e->getMessage();
				break;
			}
		}

		// Update st_detail in form table after all records are inserted
		try {
			$this->db->set('st_detail', 'Y');
			$this->db->where('id', $txtIdForm);
			$this->db->update('form');
		} catch (Exception $e) {
			$responseMessage = "Failed to update form detail: " . $e->getMessage();
		}

		// Return response message
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