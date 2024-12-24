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
            $status = '';
            $btnDetail = '';        

            if ($value->sts_input == 'Y') {
				$btnDetail = "<button onclick=\"editData('".$value->id."');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit_".$value->id."\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button>";
			}

            $tr .= "<tr id='row_" . $value->id . "'>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $no . "</td>";
            $tr .= "<td align='center'>" . $btnDetail . "</td>"; 
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->id_name . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->ram . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->company . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->divisi . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->location . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->harddisk . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->windows . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->win_serial . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->user . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->tanggal_beli . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->history_user . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->po . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;' id='status_" . $value->id . "'>" . $status . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>Action</td>";
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
        $stData = "";
        
        $valData['id'] = isset($data['txtIdInventory']) ? $data['txtIdInventory'] : '';
        $valData['id_name'] = isset($data['idname']) ? $data['idname'] : '';
        $valData['ram'] = isset($data['ram']) ? $data['ram'] : '';
        $valData['company'] = isset($data['company']) ? $data['company'] : '';  
        $valData['init_cmp'] = isset($data['init_cmp']) ? $data['init_cmp'] : '';  
        $valData['divisi'] = isset($data['divisi']) ? $data['divisi'] : '';  
        $valData['location'] = isset($data['txtlocation']) ? $data['txtlocation'] : '';
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
        $options = '<option value="">Select Company</option>';
        foreach ($result as $row) {
            $options .= '<option value="' . $row->company . '">' . $row->company . '</option>';
        }
        return $options;
    }

    function getOptDivisiByCompany() {
        $company = $this->input->post('company'); 

        $company = $this->db->escape($company); 

        $sql = "SELECT DISTINCT divisi FROM form WHERE sts_delete = '0' AND company = $company ORDER BY divisi ASC";
        $result = $this->myapp->getDataQueryDB6($sql);

        $options = '<option value="">Select Divisi</option>';
        foreach ($result as $row) {
            $options .= '<option value="' . htmlspecialchars($row->divisi) . '">' . htmlspecialchars($row->divisi) . '</option>';
        }

        echo $options; 
    }


    function getOptLocation() {
        $sql = "SELECT DISTINCT location FROM form WHERE sts_delete = '0' ORDER BY location ASC";
        $result = $this->myapp->getDataQueryDB6($sql);
        $options = '<option value="">Select Location</option>';
        foreach ($result as $row) {
            $options .= '<option value="' . $row->location . '">' . $row->location . '</option>';
        }
        return $options;
    }

    function getOptJenisPerangkat()
    {
        $opt = "<option value=\"\">- Select -</option>";

        $sql = "SELECT * FROM jenis_perangkat WHERE sts_delete = '0'";
        
        $rsl = $this->myapp->getDataQueryDB6($sql);

        foreach($rsl as $key => $val)
        {
            $opt .= "<option value=\"".$val->nama_perangkat."\">".$val->nama_perangkat."</option>";
        }

        return $opt;
    }

    
}