<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cuti extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->model('myapp');
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{
		$this->load->view('myApps/login');
	}
	function getCuti($searchNya = "")
	{
		$dataOut = array();
		$empNo = $this->session->userdata('empNo');
		$nmDiv = $this->session->userdata('nmDiv');
		$hrAdm = $this->session->userdata('hrAdm');
		$trNya = "";
		$empNoCek = "";
		$no = 1;
		$status = "";
		$whereNya = "A.deletests = '0'";
		
		if($hrAdm == "N")
		{
			$whereNya .= " AND B.bossempno = '".$empNo."'";
		}
		
		if($searchNya == "")
		{
			$whereNya .= " AND A.stsleave = 'P'";
		}else{
			$data = $_POST;			
			if($data['searchName'] != "")
			{
				$whereNya .= " AND B.nama LIKE '%".$data['searchName']."%'";
			}
			if($data['stCuti'] != "")
			{
				$whereNya .= " AND A.stsleave = '".$data['stCuti']."'";
			}
			if($data['sDate'] != "" AND $data['eDate'] != "")
			{
				$whereNya .= " AND A.startdt >= '".$data['sDate']."' AND A.startdt <= '".$data['eDate']."'";
			}
		}		

		$sql = " SELECT A.*,CONVERT(varchar,A.startdt,106) as sDate,CONVERT(varchar,A.enddt,106) as eDate,B.nama,B.bossempno FROM tblempcuti A LEFT JOIN tblmstemp B ON A.empno = B.empno WHERE ".$whereNya." ORDER BY entrydt DESC";
		$data = $this->myapp->querySqlServer($sql);
		foreach ($data as $key => $value)
		{
			$stButton = "";
			if($value->stsleave == "A"){ $status = "Approved"; }
			if($value->stsleave == "C"){ $status = "Cancel"; }
			if($value->stsleave == "P")
			{
				$status = "Pending";
				$stButton = "
								<button onclick=\"actionCuti('".$value->empno."','".$value->startdt."','".$value->enddt."','".$value->uniquekey."');\" type=\"submit\" id=\"btnSearch\" class=\"btn btn-primary btn-xs\" title=\"Recieved\">Approve</button>
								<button onclick=\"actionReject('".$value->empno."','".$value->startdt."','".$value->enddt."','".$value->uniquekey."');\" type=\"submit\" id=\"btnSearch\" class=\"btn btn-danger btn-xs\" title=\"Recieved\">Reject</button>
							";
			}
			$ttlHari = $this->cekIntervalDay($value->sDate,$value->eDate);
			$trNya .= " <tr>
							<td align=\"center\">".$no."</td>
							<td>".$value->nama."</td>
							<td align=\"center\">".$value->sDate."</td>
							<td align=\"center\">".$value->eDate."</td>
							<td align=\"center\">".$ttlHari."</td>
							<td>".$value->remark."</td>
							<td align=\"center\">".$status."</td>
							<td align=\"center\">".$stButton."</td>
						</tr>";
			$no++;
		}
		if($searchNya == "")
		{
			$dataOut['trNya']=$trNya;
			$this->load->view('myApps/cuti',$dataOut);
		}else{
			print json_encode($trNya);
		}
	}
	function approve()
	{
		$status = "";
		$data = $_POST;
		$usrInit = $this->session->userdata('userInitial');
		$dateNow = date("Ymd#h:i");
		$usrNow = $dateNow."#".$usrInit;
		$dateInsNow = date("h:i#d/m/Y");
		$usrInsNow = $usrInit."#".$dateInsNow;
		$dateTImeNow = date("Y-m-d h:i");
		$usrFullName = $this->session->userdata('fullNameMyApps');
		$noteNya = "Cuti Anda telah disetujui oleh ".trim($usrFullName);

		$sql = "UPDATE tblEmpCuti SET stsleave = 'A',updusrdt = '".$usrNow."' WHERE empno = '".$data['empNo']."' AND startdt = '".$data['sDate']."' AND enddt = '".$data['eDate']."' AND uniquekey = '".$data['uniqKey']."' ";
		$sqlIns = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$data['empNo']."','".$usrInsNow."')";
		try {
			$this->myapp->querySqlServer($sql,"update");
			$this->myapp->querySqlServer($sqlIns,"Insert");
			$this->insSendToHR($data['empNo'],$data['sDate'],$data['eDate']);
			$this->cekAbsen($data['empNo'],$data['sDate'],$data['eDate']);
			$this->cekActivity($data['empNo'],$data['sDate'],$data['eDate'],'Approve');
			$status = "Success..!!";
		} catch (Exception $e) {
			$status = "Failed ".$e;
		}
		print json_encode($status);
	}
	function reject()
	{
		$status = "";
		$data = $_POST;
		$usrInit = $this->session->userdata('userInitial');
		$dateNow = date("Ymd#h:i");
		$usrNow = $dateNow."#".$usrInit;
		$dateInsNow = date("h:i#d/m/Y");
		$usrInsNow = $usrInit."#".$dateInsNow;
		$dateTImeNow = date("Y-m-d h:i");
		$usrFullName = $this->session->userdata('fullNameMyApps');
		$noteNya = "Cuti Anda dibatalkan oleh ".trim($usrFullName);

		$whereNya="WHERE id='".$data['empNo']."' AND absdt >= '".$data['sDate']."' AND absdt <= '".$data['eDate']."'";

		$sql = "UPDATE tblempcuti SET stsleave = 'C',updusrdt = '".$usrNow."',remark = '".$data['remark']."' WHERE empno = '".$data['empNo']."' AND startdt = '".$data['sDate']."' AND enddt = '".$data['eDate']."' AND uniquekey = '".$data['uniqKey']."' ";

		$sqlReject = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$data['empNo']."','".$usrInsNow."')";

		$sqlAbsen = "UPDATE tblAbsence SET abssts =0, source ='0', absremark ='', updusrdt='".$usrNow."' ".$whereNya;
		print_r($sqlAbsen);exit;
		try {
			$this->myapp->querySqlServer($sql,"Update");
			$this->myapp->querySqlServer($sqlReject,"Insert");
			$this->myapp->querySqlServer($sqlAbsen,"Update");
			$this->cekActivity($data['empNo'],$data['sDate'],$data['eDate'],'Reject');
			$status = "Success..!!";
		} catch (Exception $e) {
			$status = "Failed ".$e;
		}

		print json_encode($status);
	}
	function cekAbsen($empNo = "",$sDate = "",$eDate = "")
	{
		$whereNya = "";
		$usrInit = $this->session->userdata('userInitial');
		$dateInsNow = date("Ymd#h:i");
		$usrInsNow = $dateInsNow."#".$usrInit;
		$sqlIns = "";
		if($empNo != "" AND $sDate != "" AND $eDate != "")
		{
			$whereNya = "WHERE id = '".$empNo."' AND absdt >= '".$sDate."' AND absdt <= '".$eDate."' ";
			$sql = " SELECT * FROM tblAbsence ".$whereNya;
			$data = $this->myapp->querySqlServer($sql);
			if(count($data) > 0)
			{
				$sqlUpt = " UPDATE tblAbsence SET abssts = '4',source = 'CTI',absremark = 'CUTI (apps)',updusrdt = '".$usrInsNow."' ".$whereNya;
				$this->myapp->querySqlServer($sqlUpt,"update");
			}else{
				$sd = new DateTime($sDate);
				$ed = new DateTime($eDate);
				$ttlHari = $sd->diff($ed);
				$ttlHari = ($ttlHari->d)+1;
				for ($lan = 0; $lan < $ttlHari; $lan++)
				{
					$dateIns = date("Y-m-d",strtotime($sDate.'+ '.$lan.' days'));
					$stCek = $this->cekIntervalDay($dateIns,$dateIns,'cekDate');
					if($stCek == "")
					{
						$sqlIns = " INSERT INTO tblAbsence (id,absdt,absin,absout,abssts,source,absremark,addusrdt) VALUES ('".$empNo."','".$dateIns."','00:00','00:00','4','CTI','CUTI (apps)','".$usrInsNow."') ";
						$this->myapp->querySqlServer($sqlIns,"insert");
					}
				}				
			}
		}
	}
	function cekActivity($empNo = "",$sDate = "",$eDate = "",$typeCek = "")
	{
		$data = array();
		$dateInsNow = date("Ymd/h:i:s");
		$usrInsNow = "HRSYS/".$dateInsNow;
		$sd = new DateTime($sDate);
		$ed = new DateTime($eDate);
		$ttlHari = $sd->diff($ed);
		$ttlHari = ($ttlHari->days)+1;

		$uId = $this->myapp->getDataQueryDb2(" SELECT userid,userfullnm FROM login WHERE empno = '".$empNo."' AND deletests = '0' ");
		$userId = $uId[0]->userid;
		$fullName = $uId[0]->userfullnm;

		for ($lan = 0; $lan < $ttlHari; $lan++)
		{
			$dateIns = date("Y-m-d",strtotime($sDate.'+ '.$lan.' days'));
			$stCek = $this->cekIntervalDay($dateIns,$dateIns,'cekDate');
			$exp = explode("-", $dateIns);
			if($stCek == "")//jika bukan hari minggu,sabtu dan hari libur
			{
				$whereNya = "ownerid = '".$userId."' AND tanggal = '".$exp[2]."' AND bulan = '".$exp[1]."' AND tahun = '".$exp[0]."' AND deletests = '0' ";
				$sqlUpd = " UPDATE tblactivity SET deletests = '1',updusrdt = '".$usrInsNow."',delusrdt = '".$usrInsNow."' ".$whereNya;
				$this->myapp->getDataQueryDb2($sqlUpd,"Update");

				if($typeCek == "Approve")
				{
					$urut = $lan+1;
					$sqlIns = " INSERT INTO tblactivity (urutan,ownerid,ownername,tanggal,bulan,tahun,activity,bosread,bosreadjob,bosapprove,cuti,sakit,ijin,addusrdt) VALUES ('".$urut."','".$userId."','".$fullName."','".$exp[2]."','".$exp[1]."','".$exp[0]."','LEAVE','Y','Y','Y','Y','N','N','".$usrInsNow."')";
					$this->myapp->getDataQueryDb2($sqlIns,"Insert");
				}
			}			
		}
	}
	function insSendToHR($empNo = "",$sDate = "",$eDate = "")
	{
		$dateTImeNow = date("Y-m-d h:i");
		$fullName = "";
		$usrInit = $this->session->userdata('userInitial');
		$dateInsNow = date("h:i#d/m/Y");
		$usrInsNow = $usrInit."#".$dateInsNow;

		$sql = "SELECT empno FROM login WHERE deletests = '0' AND hradm = 'Y' ";
		$data = $this->myapp->getDataQueryDb2($sql);
		$sqlName = "SELECT userfullnm FROM login WHERE deletests = '0' AND empno = '".$empNo."' ";
		$dataName = $this->myapp->getDataQueryDb2($sqlName);
		if(count($dataName) > 0)
		{
			$fullName = $dataName[0]->userfullnm;
		}
		$exp = explode(" ", $sDate);
		$sDate = date_format(date_create($exp[0]),"d-m-Y");
		$exp2 = explode(" ", $eDate);
		$eDate = date_format(date_create($exp2[0]),"d-m-Y");
		$noteNya = "Bapak/Ibu, ".$fullName." mengambil cuti pada ".$sDate." s/d ".$eDate;
		if(count($data) > 0)
		{
			foreach ($data as $key => $value)
			{
				$sqlIns = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$value->empno."','".$usrInsNow."')";
				$this->myapp->querySqlServer($sqlIns,"Insert");
			}
		}
	}
	function getHistory($searchNya = "")
	{
		$dataOut = array();
		$empNo = $this->session->userdata('empNo');
		$trNya = "";
		$no = 1;
		$status = "";
		$yearNow = date("Y");
		$trOpt = "";
		$ttlDayCuti = 0;

		for ($lan = $yearNow; $lan >= ($yearNow-4) ; $lan--)
		{
			$trOpt .= "<option value=".$lan.">".$lan."</option>";
		}
		
		if($searchNya != "")
		{
			$data = $_POST;
			$yearNow = $data['yearSearch'];
		}
		$sql = " SELECT CONVERT(varchar,A.startdt,106) as sDate,CONVERT(varchar,A.enddt,106) as eDate,A.*,B.nama
				 FROM hrsys..tblEmpCuti A
				 LEFT JOIN tblmstemp B ON A.empno = B.empno
				 WHERE A.empno = '".$empNo."' AND YEAR(A.startdt) = '".$yearNow."' AND A.deletests = '0' ORDER BY A.startdt DESC";
		$data = $this->myapp->querySqlServer($sql);
		foreach ($data as $key => $value)
		{
			if($value->stsleave == "A"){ $status = "Di Setujui"; }
			if($value->stsleave == "P"){ $status = "Di Proses"; }
			if($value->stsleave == "C"){ $status = "Di Batalkan"; }
			$trNya .= " <tr>
							<td align=\"center\">".$no."</td>
							<td align=\"center\">".$value->nama."</td>
							<td align=\"center\">".$value->sDate."</td>
							<td align=\"center\">".$value->eDate."</td>
							<td>".$value->remark."</td>
							<td align=\"center\">".$status."</td>
						</tr>";
			$no++;
			$ttlHari = $this->cekIntervalDay($value->sDate,$value->eDate);
			if($value->stsleave == "A")
			{
				$ttlDayCuti = $ttlDayCuti + $ttlHari;
			}			
		}
		$dataOut['trNya']=$trNya;
		$dataOut['sisaCuti']=$this->getSisaCuti($empNo,$ttlDayCuti);
		$dataOut['trOpt']=$trOpt;
		if($searchNya == "")
		{
			$this->load->view('myApps/historyCuti',$dataOut);
		}else{
			print json_encode($trNya);
		}		
	}
	function getSisaCuti($empNo = "",$ttlDayCuti = "")
	{
		$yearNow = date("Y");
		$sisaCuti = 12;

		$sql = " SELECT * FROM hrsys..tblempcutioth WHERE empno = '".$empNo."' AND YEAR(startdt) = '".$yearNow."' ";
		$data = $this->myapp->querySqlServer($sql);
		if(count($data) > 0)
		{
			$sisaExt = $data[0]->xtdadvday;
			$kdrLuasa = $sisaExt - $data[0]->usedday;
			$sisaCuti = $sisaCuti + $sisaExt - $kdrLuasa;
		}
		return $sisaCuti - $ttlDayCuti;
	}
	function convertDate($dateNya = "",$timeNya = "")
	{
		$expDate = explode("-", $dateNya);
		$dtReturn = $expDate[1]."-".$expDate[0]."-".$expDate[2];
		if($timeNya != "")
		{
			$dtReturn .= " ".$timeNya;
		}
		return $dtReturn;
	}
	function cekIntervalDay($sDate = "",$eDate = "",$typeCek = "")
	{
		$sDate = new DateTime($sDate);
		$eDate = new DateTime($eDate);
		$eDate = $eDate->modify("+1 day");
		$jml = 0;

		$dateRange = new DatePeriod($sDate, new DateInterval('P1D'), $eDate);
		foreach($dateRange as $date)
		{
		    $daterange1 = $date->format("Y-m-d");
		    $datetime = DateTime::createFromFormat('Y-m-d', $daterange1);
		    $day = $datetime->format('D');
		    if($day!="Sun" && $day!="Sat")
			{		        
		        $stCek = $this->cekTglLibur($daterange1);
		        if($stCek == "")
		        {
		        	$jml ++;
		        }
		    }
		}
		if($typeCek == "")
		{
			return $jml;
		}else{
			$stCek = "";
			if($jml == 0 ){ $stCek = "ada"; }
			return $stCek;
		}		
	}
	function cekTglLibur($dateNya = "")
	{
		$stCek = "";
		$ex = explode("-", $dateNya);
		$sql = " SELECT * FROM tblmsthrlibur WHERE tahun = '".$ex[0]."' AND bulan = '".$ex[1]."' AND tanggal = '".$ex[2]."' ";
		$data = $this->myapp->querySqlServer($sql);
		if(count($data) > 0 ){ $stCek = "ada"; }
		return $stCek;
	}
	
}

