<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory Extends CI_Controller{

    function __construct()
	{           
		parent::__construct();
    	$this->load->model('myapp'); 
		$this->load->helper(array('form', 'url'));
	}
    
    function getDataInventory() { 
        $dataOut = array(); 
        $tr = '';  
        $no = 1;    
        $userType = $this->session->userdata('userTypeMyApps');
        $userDiv = trim($this->session->userdata('nmDiv')); 
        $userDept = trim($this->session->userdata('nmDept')); 
        $userId = $this->session->userdata('userIdMyApps');
        $userFullName = $this->session->userdata('fullNameMyApps');			
        $where = "WHERE sts_delete = '0' ";
        
        $sql = "SELECT * FROM inventory " . $where . " ORDER BY ID DESC";
        
        $data = $this->myapp->getDataQueryDB6($sql);
   
        foreach ($data as $key => $value) {
            $btnDetail = '';        

            if ($value->sts_input == 'Y') {
				$btnDetail = "<button onclick=\"editData('".$value->id."');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit_".$value->id."\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button>";
			}

            $tr .= "<tr id='row_" . $value->id . "'>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $no . "</td>";
				$tr .= "<td align='center'>" . $btnDetail . "</td>"; 
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>".$value->company."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->id_name . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->divisi . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->location . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->jenisperangkat . "</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->ram . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->processor . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->harddisk . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->windows. "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->win_serial . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->user . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->tanggal_beli . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->history_user . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->po ."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;' id='status_" . $value->id . "'>".$value->status."</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->brand ."</td>";
				$tr .= "</tr>";
            $no++;
        }

        $dataOut['tr'] = $tr;
        $dataOut['getOptCompany'] = $this->getOptCompany(); 
        $dataOut['getOptJenisPerangkat'] = $this->getOptJenisPerangkat();
        $dataOut['getOptLocation'] = $this->getOptLocation();

        $this->load->view('myApps/inventory', $dataOut);	
    }
    
    function addInventory()
    {
        $data = $_POST;
        $valData = array();
        $requiredFields = array(
            'idname', 'ram', 'harddisk', 'windows', 'winserial', 
            'user', 'tanggalbeli', 'historyuser', 'po', 
            'status', 'brand', 'processor', 'company', 
            'divisi', 'location', 'jenisperangkat'
        );
        $stData = "";
        $missingFields = array();

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }

        if (isset($data['txtIdInventory']) && empty($data['txtIdInventory'])) {
            echo json_encode(array('status' => 'error', 'message' => 'ID Inventory tidak valid!'));
            return;
        }

        if (!empty($missingFields)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Field berikut tidak boleh kosong: ' . implode(', ', $missingFields)
            ));
            return;
        }


        $valData['id'] = isset($data['txtIdInventory']) ? $data['txtIdInventory'] : '';
        $valData['id_name'] = isset($data['idname']) ? $data['idname'] : '';
        $valData['ram'] = isset($data['ram']) ? $data['ram'] : '';
        $valData['processor'] = isset($data['processor']) ? $data['processor'] : '';
        $valData['company'] = isset($data['company']) ? $data['company'] : '';
        $valData['divisi'] = isset($data['divisi']) ? $data['divisi'] : '';
        $valData['location'] = isset($data['location']) ? $data['location'] : '';
        $valData['jenisperangkat'] = isset($data['jenisperangkat']) ? $data['jenisperangkat'] : '';
        $valData['harddisk'] = isset($data['harddisk']) ? $data['harddisk'] : '';
        $valData['windows'] = isset($data['windows']) ? $data['windows'] : '';
        $valData['win_serial'] = isset($data['winserial']) ? $data['winserial'] : '';
        $valData['user'] = isset($data['user']) ? $data['user'] : '';
        $valData['tanggal_beli'] = date("Y-m-d");
        $valData['history_user'] = isset($data['historyuser']) ? $data['historyuser'] : '';
        $valData['po'] = isset($data['po']) ? $data['po'] : '';
        $valData['status'] = isset($data['status']) ? $data['status'] : '';
        $valData['brand'] = isset($data['brand']) ? $data['brand'] : '';

        if (!empty($data['txtIdInventory'])) { 
            // Proses UPDATE
            try {
                $where = array('id' => $data['txtIdInventory']); 
                $this->myapp->updateDataDb6('inventory', $valData, $where);  

                $this->db->set('sts_input', 'Y');
                $this->db->where('id', $data['txtIdInventory']);
                $this->db->update('inventory');  

                $stData = "Update Success..!!";
            } catch (Exception $e) {
                $stData = "Failed => " . $e->getMessage();
            }
        } else { 
            try {
                $this->myapp->insDataDb6($valData, "inventory");  
                $txtIdInventory = $this->db->insert_id();

                $this->db->set('sts_input', 'Y');
                $this->db->where('id', $txtIdInventory);
                $this->db->update('inventory');  

                $stData = "Insert Success..!!";
            } catch (Exception $e) {
                $stData = "Failed => " . $e->getMessage();
            }
        }

        print json_encode($stData);
    }

    
    function getInvetoryById()
    {
        $id = $this->input->get('id');
        $sql = "SELECT * FROM inventory WHERE id = '".$id."'";  
        $result = $this->myapp->getDataQueryDB6($sql);

        if(!empty($result))
        {
            echo json_encode(array('success' => true, 'data' => $result[0]));
        }
        else{
             echo json_encode(array('error' => false));
        }
    }

    function getOptCompany() {
        $sql = "SELECT DISTINCT company FROM form WHERE sts_delete = '0' ORDER BY company ASC";
        $result = $this->myapp->getDataQueryDB6($sql);
        $options = '<option value="">-Select-</option>';
        foreach ($result as $row) {
            $company = htmlspecialchars($row->company);
            $options .= '<option value="' . $company . '">' . $company . '</option>';
        }
        return $options;
    }


    function getOptDivisiByCompany() {
        $company = $this->input->post('company'); 

        $company = $this->db->escape($company); 

        $sql = "SELECT DISTINCT divisi FROM form WHERE sts_delete = '0' AND company = $company ORDER BY divisi ASC";
        $result = $this->myapp->getDataQueryDB6($sql);

        $options = '<option value="">-Select-</option>';
        foreach ($result as $row) {
            $options .= '<option value="' . htmlspecialchars($row->divisi) . '">' . htmlspecialchars($row->divisi) . '</option>';
        }

        echo $options; 
    }

    function getOptLocation() {
        $sql = "SELECT DISTINCT location FROM form WHERE sts_delete = '0' ORDER BY location ASC";
        $result = $this->myapp->getDataQueryDB6($sql);
        $options = '<option value="">-Select-</option>';
        foreach ($result as $row) {
            $options .= '<option value="' . $row->location . '">' . $row->location . '</option>';
        }
        return $options;
    }

    function getOptJenisPerangkat()
    {
        $sql = "SELECT DISTINCT jenisperangkat 
                FROM form_detail
                WHERE sts_delete = '0' 
                ORDER BY jenisperangkat ASC";
        
        $result = $this->myapp->getDataQueryDB6($sql);
        $options = '<option value="">-Select-</option>';
        
        foreach ($result as $row) {
            $options .= '<option value ="' . htmlspecialchars($row->jenisperangkat, ENT_QUOTES, 'UTF-8') . '">'
                        . htmlspecialchars($row->jenisperangkat, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        
        return $options;
    }

    
}