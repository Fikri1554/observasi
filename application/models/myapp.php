<?php
class Myapp extends CI_Model
{
	private $db2;
	function __construct()
    {
      parent::__construct();
	  $this->CI = get_instance();	  
    }

    function getDataQuery($query = "")
    {
    	$dataOut = array();
    	$dataOut = $this->db->query($query)->result();
    	return $dataOut;
    }

    function getData($slc = "",$db = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db = $this->load->database('default', TRUE);
		$this->db->select($slc);
		$this->db->from($db);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		if($order != "")
		{
			$this->db->order_by($order);
		}
		if($grp != "")
		{
			$this->db->group_by($order);
		}
		
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getJoin2($slc = "",$db1 = "",$db2 = "",$joinOn = "",$typeJoin = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db->select($slc);
		$this->db->from($db1);
		$this->db->join($db2,$joinOn,$typeJoin);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		if($order != "")
		{
			$this->db->order_by($order);
		}
		if($grp != "")
		{
			$this->db->group_by($order);
		}
		
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function insData($db = "",$insData = "")
	{
		$this->db->insert($db,$insData);
	}

	function delData($db = "",$idWhere = "")
	{
		$this->db->where($idWhere);
  		$this->db->delete($db);
	}
	
	function getDataDb2($slc = "",$db = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db2 = $this->load->database('andhikaportal', TRUE);

		$this->db2->select($slc);
		$this->db2->from($db);
		if($whereNya != "")
		{
			$this->db2->where($whereNya);
		}
		if($order != "")
		{
			$this->db2->order_by($order);
		}
		if($grp != "")
		{
			$this->db2->group_by($order);
		}		
		$query = $this->db2->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataQueryDb2($query = "",$typeQuery = "")
    {
    	$this->db2 = $this->load->database('andhikaportal', TRUE);
    	if($typeQuery == "")
    	{
	    	$dataOut = array();
	    	$dataOut = $this->db2->query($query)->result();
	    	return $dataOut;
	    }else{
	    	$this->db2->query($query);
	    }
    }

	function getDataDb3($slc = "",$db = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db3 = $this->load->database('invoiceregister', TRUE);
		$this->db3->select($slc);
		$this->db3->from($db);
		if($whereNya != "")
		{
			$this->db3->where($whereNya);
		}
		if($order != "")
		{
			$this->db3->order_by($order);
		}
		if($grp != "")
		{
			$this->db3->group_by($grp);
		}
		
		$query = $this->db3->get();
		$dataOut = $query->result();
		return $dataOut;
	}	
	
	function updateDataDb3($tbl = "",$dataUpdate = "",$whereNya = "")
	{
		$this->db3 = $this->load->database('invoiceregister', TRUE);

		$this->db3->where($whereNya);
		$this->db3->update($tbl,$dataUpdate);
	}

	function getDataDb4($slc = "",$db = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db4 = $this->load->database('vslReport', TRUE);
		$this->db4->select($slc);
		$this->db4->from($db);
		if($whereNya != "")
		{
			$this->db4->where($whereNya);
		}
		if($order != "")
		{
			$this->db4->order_by($order);
		}
		if($grp != "")
		{
			$this->db4->group_by($order);
		}
		
		$query = $this->db4->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataQueryDb4($query = "")
    {
    	$this->db2 = $this->load->database('vslReport', TRUE);
    	$dataOut = $this->db2->query($query)->result();
	    return $dataOut;
    }

	function insDataDb5($dataIns = "",$dbNya = "")
	{
		$this->db5 = $this->load->database('surveycustomer', TRUE);
		$this->db5->insert($dbNya,$dataIns);
		$getIdNya = $this->db5->insert_id();
		return $getIdNya;
	}

	function updateDataDb5($tbl = "",$dataUpdate = "",$whereNya = "")
	{
		$this->db5 = $this->load->database('surveycustomer', TRUE);

		$this->db5->where($whereNya);
		$this->db5->update($tbl,$dataUpdate);
	}

	function getDataDb5($slc = "",$db = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db5 = $this->load->database('surveycustomer', TRUE);
		$this->db5->select($slc);
		$this->db5->from($db);
		if($whereNya != "")
		{
			$this->db5->where($whereNya);
		}
		if($order != "")
		{
			$this->db5->order_by($order);
		}
		if($grp != "")
		{
			$this->db5->group_by($order);
		}
		
		$query = $this->db5->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataQueryDb5($query = "")
    {
    	$this->db5 = $this->load->database('surveycustomer', TRUE);
    	$dataOut = $this->db5->query($query)->result();
	    return $dataOut;
    }
	
	function getDataQueryDB6($query = "")
    {
    	$dataOut = array();
    	$this->db6 = $this->load->database('myapps', TRUE);    	
    	$dataOut = $this->db6->query($query)->result();
    	return $dataOut;
    }

    function insDataDb6($dataIns = "",$dbNya = "")
	{
		$this->db6 = $this->load->database('myapps', TRUE);
		$this->db6->insert($dbNya,$dataIns);
		$getIdNya = $this->db6->insert_id();
		return $getIdNya;
	}

	function updateDataDb6($tbl = "",$dataUpdate = "",$whereNya = "")
	{
		$this->db6 = $this->load->database('myapps', TRUE);

		$this->db6->where($whereNya);
		$this->db6->update($tbl,$dataUpdate);
	}
	
	function getDataQueryDBDahlia($query = "")
    {
    	$dataOut = array();
    	$this->dbDahlia = $this->load->database('myappsDahlia', TRUE);    	
    	$dataOut = $this->dbDahlia->query($query)->result();
    	return $dataOut;
    }

    function insDataDbDahlia($dataIns = "",$dbNya = "")
	{
		$this->dbDahlia = $this->load->database('myappsDahlia', TRUE);
		$this->dbDahlia->insert($dbNya,$dataIns);
		$getIdNya = $this->dbDahlia->insert_id();
		return $getIdNya;
	}

	function updateDataDbDahlia($tbl = "",$dataUpdate = "",$whereNya = "")
	{
		$this->dbDahlia = $this->load->database('myappsDahlia', TRUE);

		$this->dbDahlia->where($whereNya);
		$this->dbDahlia->update($tbl,$dataUpdate);
	}

	function querySqlServer($query = "",$typeQuery = "")
	{
		$this->dbSqlServer = $this->load->database('hrsysV11', TRUE);
		if($typeQuery == "")//untuk select data
		{
			$dataOut = $this->dbSqlServer->query($query)->result();
			return $dataOut;
		}else{
			$this->dbSqlServer->query($query);
		}
	}

	function insDataSqlServer($tbl = "",$insData = "")
	{
		$this->dbSqlServer = $this->load->database('hrsys', TRUE);
		$this->dbSqlServer->insert($tbl,$insData);
	}
	function uptDataSqlServer($tbl = "",$dataUpdate = "",$whereNya = "")
	{
		$this->dbSqlServer = $this->load->database('hrsys', TRUE);

		$this->dbSqlServer->where($whereNya);
		$this->dbSqlServer->update($tbl,$dataUpdate);
	}
	
	
}
?>