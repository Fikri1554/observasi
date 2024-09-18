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

		$sql = "SELECT * FROM form " . $where;
		$data = $this->myapp->getDataQueryDB6($sql);

		foreach($data as $key => $value) {

			if ($value->st_detail === 'Y') {
				$btnDetail = "<button onclick=\"editData('".$value->id."');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button>";
			} else {
				$btnDetail = "<button onclick=\"addDetail('".$value->id."');\" title=\"Add Detail\" class=\"btn btn-primary btn-xs\" id=\"btnAdd\" type=\"button\"><i class=\"glyphicon glyphicon-plus\"></i></button>";
			}

			
			$btnExport = "<button onclick=\"ViewPrint('".$value->id."');\" class=\"btn btn-success btn-xs\" id=\"btnView\" type=\"button\" title=\"View\" style=\"margin-right: 5px;\"><i class=\"fa fa-eye\"></i> View</button>";
			$btnDelete = "<button onclick=\"delData('".$value->id."');\" class=\"btn btn-danger btn-xs\" id=\"btnDelete\" type=\"button\" title=\"Delete\"><i class=\"fa fa-trash-o\"></i> Del</button>";

			
			$tr .= "<tr id='row_".$value->id."'>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$no."</td>";
				$tr .= "<td align='center'>".$btnDetail."</td>"; 
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->project_reference."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->purpose."</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;'>".$value->company."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->location."</td>";
				$tr .= "<td align='left' style='font-size:12px;vertical-align:top;'>".$value->divisi."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$btnExport.$btnDelete."</td>";
			$tr .= "</tr>";

			$no++;
		}

		$dataOut['tr'] = $tr;
		$dataOut['getOptCompany'] = $this->getOptCompany(); 
		$dataOut['getOptMstDivisi'] = $this->getOptMstDivisi();
		$this->load->view('myApps/form', $dataOut);
	}
 	
	function editData() {
		$data = $_POST;
		$id = $data['idForm'];

		$id = $this->db->escape_str($id);

		$sql = "SELECT * FROM form_detail WHERE id_form = '".$id."'";
		$result = $this->myapp->getDataQueryDB6($sql);

		print json_encode($result);
	}


	function previewPrint($id) {
		
		$id = intval($id);
		$logo_company = "/assets/img";
		$imgQRcode = "/assets/imgQRcode";
		

		$queryForm = "SELECT * FROM `form` WHERE `id` = $id AND `sts_delete` = 0";
		$form = $this->myapp->getDataQueryDB6($queryForm);
		
		
		if($form[0]->company == "PT. ADNYANA"){
			$logo_company .= "/".str_replace(" ", "",$form[0]->company).".jpg";
		} 
		else if($form[0]->company == "PT. ANDHIKA LINES"){
			$logo_company .= "/".str_replace(" ", "",$form[0]->company).".jpg";
		}
		else if ($form[0]->company == "PT. INDAH BIMA PRIMA") {
			$logo_company .= "/".str_replace(" ", "",$form[0]->company).".jpg";
		}
		else{
			$logo_company .= "/".str_replace(" ", "",$form[0]->company).".png";
		}

		if (count($form) > 0) {

			$queryFormDetail = "SELECT * FROM `form_detail` WHERE `id_form` = $id AND `sts_delete` = 0";
			$form_details = $this->myapp->getDataQueryDB6($queryFormDetail);

			$data = array(
				'form' => $form[0],
				'form_details' => $form_details,
				'imageLogo' => "<img src=\"".base_url($logo_company)."\" alt=\"Company Logo\" height=\"45\" style=\"align-items: left; margin-bottom: -50px;\">"
			);

			//print_r($data);exit;
			$this->load->view('myApps/previewPrint', $data);
		} else {
			show_error('Form not found', 404);
		}
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
				'project_reference' => $data['txtprojectReference'],
				'purpose'           => $data['txtpurpose'],
				'company'           => $data['slcCompany'],
				'location'          => $data['txtlocation'],
				'divisi'            => $data['slcDivisi'],
				'userid_submit'        => $userId,
				'add_date'          => $dateNow,
				'request_name'      => $reqName
			);

			try {
				// Insert data
				$this->myapp->insDataDb6($dataIns, "form");
				$status = "Insert Success..!!";
			} catch (Exception $ex) {
				$status = "Failed => " . $ex->getMessage();
			}
		} else {
			$dataUpd = array(
				'project_reference' => $data['txtprojectReference'],
				'purpose'           => $data['txtpurpose'],
				'company'           => $data['slcCompany'],
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
				$status = "Update Success..!!";
			} catch (Exception $ex) {
				$status = "Failed => " . $ex->getMessage();
			}
		}

		print json_encode($status);
	}

	function saveFormRequestDetail() {
		$data = $_POST;
		$response = array(); 
		$dateNowDet = date("Y-m-d"); 
		$userIdDet = $this->session->userdata('userIdMyApps'); 
		$reqNameDet = $this->session->userdata('fullNameMyApps');  

		$arrDesc = isset($data['description']) ? $data['description'] : ''; 
		$arrType = isset($data['type']) ? $data['type'] : ''; 
		$arrReason = isset($data['reason']) ? $data['reason'] : ''; 
		$arrQuantity = isset($data['quantity']) ? $data['quantity'] : 0; 
		$arrRequiredDate = isset($data['required_date']) ? $data['required_date'] : '0000-00-00'; 
		$arrNote = isset($data['note']) ? $data['note'] : '';

		$dataInsDet = array(
			'id_form'       => $data['id_form'],  // Tetap gunakan id_form untuk form_detail
			'description'   => $arrDesc,
			'type'          => $arrType,
			'reason'        => $arrReason,
			'quantity'      => $arrQuantity,
			'required_date' => $arrRequiredDate,
			'note'          => $arrNote,
			'add_userid'    => $userIdDet,
			'add_date'      => $dateNowDet,
			'request_name'  => $reqNameDet
		);

		try {
			// Insert data ke form_detail
			if ($this->myapp->insDataDb6($dataInsDet, 'form_detail')) {
				$response['status'] = "Insert Success!";
				
				$this->db->select('id_form');
				$this->db->where('id_form', $data['id_form']);
				$formDetailData = $this->db->get('form_detail')->row();

				if ($formDetailData) {
					$this->db->set('st_detail', 'Y');
					$this->db->where('id', $data['id_form']); // Asumsikan id_form adalah id yang sama di form
					$this->db->update('form');
				}

				// Fetch data terbaru dari form_detail
				$this->db->select('*');
				$this->db->where('sts_delete', 0);
				$query = $this->db->get('form_detail');
				$response['data'] = $query->result_array();
			} else {
				throw new Exception("Insert failed.");
			}
		} catch (Exception $e) {
			$response['status'] = "Failed to Insert: " . $e->getMessage();
		}

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
	
		$sql = "SELECT kdcmp, nmcmp 
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


    
}