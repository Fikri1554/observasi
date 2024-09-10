<?php
class Observasi extends CI_Model
{
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

    function getLatLong($slc = "",$tbl = "",$whereNya = "",$orderNya)
    {
    	$dsn1 = 'mysql://root:@localhost/andhikaportal';
		$this->db1 = $this->load->database($dsn1, true);

		$this->db1->select($slc);
		$this->db1->from($tbl);
		if($whereNya != "")
		{
			$this->db1->where($whereNya);
		}
		if($orderNya != "")
		{
			$this->db1->order_by($orderNya);
		}
	
		$query = $this->db1->get();
		$dataOut = $query->result();
		return $dataOut;

    }
	
	function getDataObservasi($searchWhere = "",$userId = "",$userType = "",$stExport = "")
	{
			
		$this->db->select("a.*,b.name as namaKapal,c.name as namaJabatan");
		$this->db->from("observasi a");
		$this->db->join("mst_vessel b","b.id = a.id_kapal","LEFT");
		$this->db->join("mst_jabatan c","c.id = a.id_jabatan","LEFT");
		$this->db->where("a.sts_delete","0");
		if ($userType != "admin")
		{
			$idJabatan = $this->session->userdata('idJabatan');
			$idVesselLogin = $this->session->userdata('idVesselLogin');
			if($idJabatan == "1" || $idJabatan == "2")//1=master,2=c/o
			{
				$this->db->where("a.id_kapal",$idVesselLogin);
			}else{
				$this->db->where("a.id_user",$userId);
			}
		}
		if($searchWhere != "")
		{
			$this->db->where($searchWhere);
		}else{
			// $monthNoww = "a.tgl_observasi >='".date("Y-m")."-01' AND a.tgl_observasi <= '".date("Y-m")."-31' ";
			$monthNoww = "YEAR(a.tgl_observasi) = '".date("Y")."' AND MONTH(a.tgl_observasi) = '".date("m")."' ";
			$this->db->where($monthNoww);
		}
		if($stExport == "")//jika bukan export
		{
			$this->db->order_by("a.nama_pengamat","ASC");
			$this->db->order_by("a.id","desc");
		}else{
			$this->db->order_by("c.rank","asc");
		}
		
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataLogin($whereNya = "")
	{
		$this->db->select("a.*,c.name as namaKapal,b.name as namaJabatan");
		$this->db->from("login a");
		$this->db->join("mst_jabatan b","b.id = a.id_jabatan","LEFT");
		$this->db->join("mst_vessel c","c.id = a.vessel","LEFT");
		if($whereNya == "")
		{
			$this->db->where("a.sts_delete","0");
		}else{
			$this->db->where($whereNya);
		}
		$this->db->order_by("a.id_name","ASC");
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataEdit($tbl = "",$whereNya = "")
	{
		$this->db->select("*");
		$this->db->from($tbl);
		$this->db->where($whereNya);
	
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function updateData($whereNya = "",$data,$tbl = "")
	{
		$this->db->where($whereNya);
		$this->db->update($tbl,$data);
	}

	function cekData($whereNya = "",$tbl = "")
	{
		$this->db->select("COUNT(*)");
		$this->db->from($tbl);
		$this->db->where($whereNya);
		$query =$this->db->count_all_results();
		return $query;
	}

	function delDataCek($whereNya = "",$tbl = "")
	{
		$this->db->where($whereNya);
  		$this->db->delete($tbl);
	}

	function getDataAll($tbl = "",$whereNya = "",$fieldNya = "")
	{
		if($fieldNya == "")
		{
			$this->db->select("*");
		}else{
			$this->db->select($fieldNya);
		}
		$this->db->from($tbl);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		$this->db->order_by("id");
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataJabatan($tbl = "",$whereNya = "")
	{
		$this->db->select("*");
		$this->db->from($tbl);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		$this->db->order_by("rank");
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getDataCheckedObservasi($tbl1 = "",$tbl2 = "",$onNya = "",$whereNya = "")
	{
		$this->db->select("*");
		$this->db->from($tbl1);
		$this->db->join($tbl2,$onNya,"LEFT");
		$this->db->where($whereNya);
	
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}






	// function delData()
	// {
	// 	$this->db->where('id',$id);
 //  		$this->db->delete('karyawan');
	// 	redirect(base_url('front/observasi'));
	// }

	
	
	
}
?>