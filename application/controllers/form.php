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
		$tr = "";
		$where = "WHERE sts_delete = '0' ";
		$no = 1;
		$userType = $this->session->userdata('userTypeMyApps');
		$fullName = $this->session->userdata('fullNameMyApps');
		$btnDetail = "";
		$btnView = "";

		$sql = "SELECT * FROM form " . $where;
		$data = $this->myapp->getDataQueryDB6($sql);

		foreach($data as $key => $value) {
			$btnDetail = "<button onclick=\"addDetail('".$value->id."');\" title=\"Add Detail\" class=\"btn btn-primary btn-xs\" id=\"btnAdd\" type=\"button\"><i class=\"glyphicon glyphicon-plus\" ></i></button>";
			$btnExport = "<button onclick=\"ViewPrint('".$value->id."');\" class=\"btn btn-success btn-xs\" id=\"btnEdit\" type=\"button\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>";
			
			$tr .= "<tr>";
				$tr .= "<td align=\"center\" style=\"font-size:12px;vertical-align:top;\">".$no."</td>";
				$tr .= "<td align=\"center\">".$btnDetail."</td>";
				$tr .= "<td align=\"center\" style=\"font-size:12px;vertical-align:top;\">".$value->project_reference."</td>";
				$tr .= "<td align=\"center\" style=\"font-size:12px;vertical-align:top;\">".$value->purpose."</td>";
				$tr .= "<td align=\"left\" style=\"font-size:12px;vertical-align:top;\">".$value->company."</td>";
				$tr .= "<td align=\"center\" style=\"font-size:12px;vertical-align:top;\">".$value->location."</td>";
				$tr .= "<td align=\"left\" style=\"font-size:12px;vertical-align:top;\">".$value->divisi."</td>";
				$tr .= "<td align=\"center\" style=\"font-size:12px;vertical-align:top;\">".$btnExport."</td>";
			$tr .= "</tr>";
			
			$no++;
		}


		$dataOut['tr'] = $tr;
		$dataOut['getOptCompany'] = $this->getOptCompany(); 
		$dataOut['getOptMstDivisi'] = $this->getOptMstDivisi();
		$this->load->view('myApps/form', $dataOut);
	}

	function previewPrint($id) {
		$id = intval($id);

		
		$queryForm = "SELECT * FROM `form` WHERE `id` = $id AND `sts_delete` = 0";
		$form = $this->myapp->getDataQueryDB6($queryForm);

		
		if (count($form) > 0) {
			
			$company_name = $form[0]->company;
			error_log("Company name from DB: " . $company_name); 

			$company_logo = $this->getCompanyLogo($company_name);

			$queryFormDetail = "SELECT * FROM `form_detail` WHERE `id_form` = $id AND `sts_delete` = 0";
			$form_details = $this->myapp->getDataQueryDB6($queryFormDetail);

			$data = array(
				'form' => $form[0],
				'form_details' => $form_details,
				'company_logo' => $company_logo
			);

			
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
				'add_userid'        => $userId,
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

	public function saveFormRequestDetail() {
		$data = $this->input->post(); 
		$response = array(); 
		$dateNowDet = date("Y-m-d"); 
		$userIdDet = $this->session->userdata('userIdMyApps'); 
		$reqNameDet = $this->session->userdata('fullNameMyApps');  

		// Data input dari form
		$arrDesc = isset($data['description']) ? $data['description'] : ''; 
		$arrType = isset($data['type']) ? $data['type'] : ''; 
		$arrReason = isset($data['reason']) ? $data['reason'] : ''; 
		$arrQuantity = isset($data['quantity']) ? $data['quantity'] : 0; 
		$arrRequiredDate = isset($data['required_date']) ? $data['required_date'] : '0000-00-00'; 
		$arrNote = isset($data['note']) ? $data['note'] : '';

		$dataInsDet = array(
			'id_form' => $data['id_form'],
			'description' => $arrDesc,
			'type' => $arrType,
			'reason' => $arrReason,
			'quantity' => $arrQuantity,
			'required_date' => $arrRequiredDate,
			'note' => $arrNote,
			'add_userId' => $userIdDet,
			'add_date' => $dateNowDet,
			'request_name' => $reqNameDet
		);

		try {
			// Insert data baru
			$this->myapp->insDataDb6($dataInsDet, "form_detail");
			$response['status'] = "Insert Success!";
			
			$this->db->select('*');
			$this->db->where('sts_delete', 0);
			$query = $this->myapp->getDataQueryDB6('form_detail');
			$response['data'] = $query->result_array(); 
			
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
			$optNya .= "<option value=\"".$value->nmcmp."\" data-logo=\"".$logo_url."\">".$value->nmcmp."</option>";
		}
		return $optNya;
	}


    
}