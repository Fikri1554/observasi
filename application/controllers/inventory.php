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
            $tr .= "<tr id='row_" . $value->id . "'>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $no . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->id_name . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->ram . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->company . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->divisi . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->location . "</td>";
            $tr .= "<td style='text-align: center; font-size: 12px; padding: 8px; border: 1px solid #ddd;'>" . $value->hdd . "</td>";
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
        $dataOut['getOptMstDivisi'] = $this->getOptMstDivisi();

        $this->load->view('myApps/inventory', $dataOut);	
    }

    function getInventoryData()
    {
        $sql = "
            SELECT 
                f.company, 
                f.division, 
                f.location,
                i.*
            FROM inventory i
            LEFT JOIN form f ON f.id = i.form_id
            WHERE i.sts_delete = '0'
            ORDER BY i.id DESC
        ";

        $data = $this->myapp->getDataQueryDB6($sql);
        print_r($data);exit;
        $options = array();

        foreach ($data as $row) {
            $options[] = array(
                'company' => $row->company ?: 'N/A', 
                'division' => $row->division ?: 'N/A',
                'location' => $row->location ?: 'N/A'
            );
        }

        echo json_encode($options); 
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
            $optNya .= "<option value=\"".$value->nmcmp."\" data-cmpcode=\"".$value->cmpcode."\">".$value->nmcmp."</option>";
        }
        return $optNya;
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

    
}