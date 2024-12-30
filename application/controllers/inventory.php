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
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->harddisk . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->windows. "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->win_serial . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->user . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->tanggal_beli . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->history_user . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->po ."</td>";
				$tr .= "<td align='center' style='font-size:12px;vertical-align:top;' id='status_" . $value->id . "'>".$value->status."</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->brand . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->port . "</td>";
                $tr .= "<td align='center' style='font-size:12px;vertical-align:top;'>" . $value->size . "</td>";
				$tr .= "</tr>";
            $no++;
        }

        $dataOut['tr'] = $tr;
        $dataOut['getOptReqName'] = $this->getOptReqName();
        $dataOut['getOptCompany'] = $this->getOptCompany(); 
        $dataOut['getOptJenisPerangkat'] = $this->getOptJenisPerangkat();
        $dataOut['getOptLocation'] = $this->getOptLocation();

        $this->load->view('myApps/inventory', $dataOut);	
    }
    
    function addInventory()
    {
        $data = $_POST;
        $valData = array();
        $stData = "";
        
        $valData['id'] = isset($data['txtIdInventory']) ? $data['txtIdInventory'] : '';
        $valData['id_name'] = isset($data['idname']) ? $data['idname'] : '';
        $valData['ram'] = isset($data['ram']) ? $data['ram'] : '';
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
        $valData['port'] = isset($data['port']) ? $data['port'] : '';
        $valData['size'] = isset($data['size']) ? $data['size'] : '';

        if ($data['txtIdInventory'] == "") {
            try {
                $this->myapp->insDataDb6($valData, "inventory");  
                $txtIdInventory = $this->db->insert_id();
                $this->db->set('sts_input', 'Y');
                $this->db->where('id', $txtIdInventory);
                $this->db->update('inventory');
                $stData = "Insert Success..!!";
            } catch (Exception $e) {
                $stData = "Failed =>" . $e;
            }
        } else {
            try {
                $where = "id = '" . $data['txtIdInventory'] . "'";
                $this->myapp->updateDataDb6($where, $valData, "inventory");
                $this->db->set('sts_input', 'Y');
                $this->db->where('id', $data['txtIdInventory']);
                $this->db->update('inventory');
                $stData = "Update Success..!!";
            } catch (Exception $e) {
                $stData = "Failed =>" . $e;
            }
        }

        print json_encode($stData); 
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
        $sql = "SELECT DISTINCT jenis_perangkat FROM form WHERE sts_delete = '0' ORDER BY jenis_perangkat ASC";
        $result = $this->myapp->getDataQueryDB6($sql);
        $options = '<option value="">-Select-</option>';
        foreach ($result as $row) {
            $options .= '<option value ="'.$row->jenis_perangkat.'">'.$row->jenis_perangkat.'</option>';
        }
        return $options;
    }
 
    function getOptReqName()
    {
        $sql = "SELECT DISTINCT request_name FROM form WHERE sts_delete = '0' ORDER BY request_name ASC";
        $result = $this->myapp->getDataQueryDB6($sql);
        $options = '<option value="">-Select-</option>';
        foreach ($result as $row) {
            $options .- '<option value ="'.$row->request_name.'">'.$row->request_name.'</option>';
        }
        return $options;
    }
    
}