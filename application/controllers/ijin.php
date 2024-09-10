<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ijin extends CI_Controller {
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
	function requestIjin()
	{
		$dataOut = array();

		$dataOut['optIjin'] = $this->getMstIjin("slcOpt");

		$this->load->view('myApps/requestIjin',$dataOut);
	}
	function searchIjin()
	{
		$data = $_POST;
		$dataOut = array();
		$stHariKrj = "";

		$sqlCek = " SELECT * FROM tblmstijin WHERE kdijin = '".$data['slcValue']."'";
		$dataVal = $this->myapp->querySqlServer($sqlCek);

		if(count($dataVal) > 0)
		{
			$jml = "";
			$sDate = $data['sDate'];
			$eDate = "";
			$stHariKrj = $dataVal[0]->harikerja;
			if($dataVal[0]->jmlbln != '0')
			{
				$jns = "bulan";
				$jml = $dataVal[0]->jmlbln;
				$eDate = new DateTime($sDate);
				$eDate = $eDate->modify("+".$jml." month");
				$eDate = $eDate->modify("- 1 day");
				$eDate = $eDate->format("Y-m-d");
				
			}else{
				$jns = "hari";
				$jml = $dataVal[0]->jmlhari;
				$jml1 = $jml;
				for ($lan=0; $lan < $jml1; $lan++)
				{
					$eDate = new DateTime($sDate);
					$eDate = $eDate->modify("+".$lan." day");
					$eDate = $eDate->format("Y-m-d");
					$cekDay = $this->cekIntervalDay($eDate,$eDate,"cekDay");
					if($cekDay != "" AND $stHariKrj == '1')
					{
						$jml1 = $jml1+1;
					}
				}
			}
			$dataOut['dataDetail'] = $this->displayDayNya($sDate,$jml,$jns,$stHariKrj);
			$dataOut['durasiDate'] = $this->convertReturnName($sDate)." - ".$this->convertReturnName($eDate);
			$dataOut['sDate'] = $sDate;
			$dataOut['eDate'] = $eDate;
			$dataOut['teksTotal'] = "Total Waktu : ".$jml." ".$jns;
		}
		// print_r($dataOut);exit;
		print json_encode($dataOut);
	}
	function displayDayNya($sDate = "",$jml = "",$bulan = "",$stHariKrj = "")
	{
		$trNya = "";
		$no = 0;
		$sd = new DateTime($sDate);

		if($bulan == "hari")
		{			
			$jml1 = $jml;
			for ($lan=0; $lan < $jml1; $lan++)
			{
				$no++;
				$stNya = "Ijin";
				$tC = "";
				$ed1 = new DateTime($sDate);
				$ed = $ed1->modify("+".$lan." day");
				$ed = $ed->format("Y-m-d");

				$cekDay = $this->cekIntervalDay($ed,$ed,"cekDay");
				if($cekDay != "" AND $stHariKrj == '1')
				{
					$jml1 = $jml1+1;
					$stNya = "OFF";
					$tC = "color:red;";
				}
				$day = $ed1->format('D');
				if($day == "Sun" || $day == "Sat")
				{
					$stNya = "OFF";
					$tC = "color:red;";
				}
				$hari = $this->convertDay($day);
				$tgl = $this->convertReturnName($ed);
				$trNya .= "
							<tr>
								<td align=\"center\">".$no."</td>
								<td align=\"center\">".$tgl."</td>
								<td align=\"center\">".$hari."</td>
								<td style=\"".$tC."\">".$stNya."</td>
							</tr>
							";
			}
		}else{
			$ed = $sd->modify("+".$jml." month");
			$ed = $sd->modify("- 1 day");
			$eDate = $ed->format("Y-m-d");

			$sDate = new DateTime($sDate);
			$eDate = new DateTime($eDate);
			$eDate = $eDate->modify("+1 day");

			$dateRange = new DatePeriod($sDate, new DateInterval('P1D'), $eDate);
			foreach($dateRange as $date)
			{
				$no++;
				$stNya = "Ijin";
				$tC = "";
				$daterange1 = $date->format("Y-m-d");
			    $datetime = DateTime::createFromFormat('Y-m-d', $daterange1);
			    $day = $datetime->format('D');

			    $hari = $this->convertDay($day);
			    $tgl = $this->convertReturnName($daterange1);

			    $cekDay = $this->cekIntervalDay($daterange1,$daterange1,"cekDay");
			    if($cekDay != "")
				{
					$stNya = "OFF";
					$tC = "color:red;";
				}

				$trNya .= "
							<tr>
								<td align=\"center\">".$no."</td>
								<td align=\"center\">".$tgl."</td>
								<td align=\"center\">".$hari."</td>
								<td style=\"".$tC."\">".$stNya."</td>
							</tr>
							";
			}

		}
		
		return $trNya;
	}
	function saveIjin()
	{
		$dataOut = array();
		$data = $_POST;
		$empNo = $this->session->userdata('empNo');
		$dateNow = date("Y-m-d");
		$dateInsNow = date("Ymd#h:i");
		$usrInit = $this->session->userdata('userInitial');
		$usrNow = $dateInsNow."#".$usrInit;
		$stNya = "";

		try {
			$sqlBtNo = "SELECT (MAX(batchno)+1) as batchNo from tblempijin";
			$dataBtchNo = $this->myapp->querySqlServer($sqlBtNo,"");
			$newBatchNo = $dataBtchNo[0]->batchNo;
			$sqlEmpNo = " INSERT INTO tblempijin(batchno,entrydt,empno,startdt,enddt,stsleave,remark,leavesrc,kdijin,addusrdt)VALUES('".$newBatchNo."','".$dateNow."','".$empNo."','".$data['sDate']."','".$data['eDate']."','P','".$data['ijinName']."','IJN','".$data['kdIjin']."','".$usrNow."')";
			$this->myapp->querySqlServer($sqlEmpNo,"Insert");
			$this->sendReminmeToUp($empNo);
			$stNya = "Insert Success..!!";
		} catch (Exception $e) {
			$stNya = "Failed..!!";
		}		
		
		print json_encode($stNya);
	}
	function getApproveIjin($searchNya = "")
	{
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$empNo = $this->session->userdata('empNo');
		$hrAdm = $this->session->userdata('hrAdm');
		$whereNya = "A.deletests = '0'";

		if($hrAdm == "N")
		{
			$whereNya .= " AND (B.bossempno = '".$empNo."' OR B.empno = '".$empNo."')";
		}

		if($searchNya == "")
		{
			//$whereNya .= " AND A.stsleave = 'A'";
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

		$cekUnder = $this->myapp->querySqlServer(" SELECT * FROM tblmstemp WHERE bossempno = '".$empNo."' ");
		if(count($cekUnder) > 0){ $cekBwhan = "ada"; }else{ $cekBwhan = "tidak"; }

		$sql = " SELECT A.*,CONVERT(varchar,A.startdt,106) as sDate,CONVERT(varchar,A.enddt,106) as eDate,B.nama,B.bossempno FROM tblempijin A LEFT JOIN tblmstemp B ON A.empno = B.empno WHERE ".$whereNya." ORDER BY entrydt DESC";		
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
								<button onclick=\"actionIjin('".$value->empno."','".$value->startdt."','".$value->enddt."','".$value->uniquekey."');\" type=\"submit\" id=\"btnSearch\" class=\"btn btn-primary btn-xs\" title=\"Recieved\">Approve</button>
								<button onclick=\"actionReject('".$value->empno."','".$value->startdt."','".$value->enddt."','".$value->uniquekey."');\" type=\"submit\" id=\"btnSearch\" class=\"btn btn-danger btn-xs\" title=\"Recieved\">Reject</button>
							";
			}

			if($hrAdm == "N" AND $cekBwhan == "tidak") { $stButton = ""; }
			if($value->empno == $empNo){ $stButton = ""; }

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
		$dataOut['trNya'] = $trNya;
		if($searchNya == "")
		{
			$this->load->view('myApps/approveIjin',$dataOut);
		}else{
			print json_encode($trNya);
		}		
	}
	function approveIjin()
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
		$noteNya = "Pengajuan Ijin Anda telah disetujui oleh ".trim($usrFullName);

		$sql = "UPDATE tblempijin SET stsleave = 'A',updusrdt = '".$usrNow."' WHERE empno = '".$data['empNo']."' AND startdt = '".$data['sDate']."' AND enddt = '".$data['eDate']."' AND uniquekey = '".$data['uniqKey']."' ";
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
	function rejectIjin()
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
		$noteNya = "Ijin Anda pada ".$this->convertDateRemindme($data['sDate'])." dibatalkan oleh ".trim($usrFullName);

		$sql = "UPDATE tblempijin SET stsleave = 'C',updusrdt = '".$usrNow."',remark = '".$data['remark']."' WHERE empno = '".$data['empNo']."' AND startdt = '".$data['sDate']."' AND enddt = '".$data['eDate']."' ";
		$sqlReject = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$data['empNo']."','".$usrInsNow."')";

		try {
			$this->myapp->querySqlServer($sql,"update");
			$this->myapp->querySqlServer($sqlReject,"Insert");
			$status = "Success..!!";
		} catch (Exception $e) {
			$status = "Failed ".$e;
		}

		print json_encode($status);
	}
	function getMstIjin($typeData = "")
	{
		$dataOut = array();
		$trNya = "";

		$sql = " SELECT * FROM tblmstijin ORDER BY nmijin ASC ";
		$data = $this->myapp->querySqlServer($sql);

		if($typeData == "slcOpt")
		{
			foreach ($data as $key => $val)
			{
				$trNya .= "<option value=".$val->kdijin.">".$val->nmijin."</option>";
			}
			return $trNya;
		}else{
			return $dataOut;
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
		$noteNya = "Bapak/Ibu, ".trim($fullName)." mengambil Ijin pada ".$sDate." s/d ".$eDate;
		if(count($data) > 0)
		{
			foreach ($data as $key => $value)
			{
				$sqlIns = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$value->empno."','".$usrInsNow."')";
				$this->myapp->querySqlServer($sqlIns,"Insert");
			}
		}
	}
	function sendReminmeToUp($empNo = "")
	{
		$dateTImeNow = date("Y-m-d h:i");
		$usrInit = $this->session->userdata('userInitial');
		$dateInsNow = date("h:i#d/m/Y");
		$usrInsNow = $usrInit."#".$dateInsNow;

		$getEmpNoBos = $this->myapp->querySqlServer("SELECT bossempno,nama FROM tblmstemp WHERE empno = '".$empNo."' AND deletests = '0'");
		// echo "<pre>";print_r($getEmpNoBos);exit;
		if(count($getEmpNoBos) > 0)
		{
			$noteNya = "Bapak/Ibu, mohon konfirmasinya untuk ijin yang diajukan oleh ".$getEmpNoBos[0]->nama;
			$sqlIns = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$getEmpNoBos[0]->bossempno."','".$usrInsNow."')";
			$this->myapp->querySqlServer($sqlIns,"Insert");
		}		
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
				$sqlUpt = " UPDATE tblAbsence SET abssts = '3',source = 'IJN',absremark = 'IJIN (apps)',updusrdt = '".$usrInsNow."' ".$whereNya;
				$this->myapp->querySqlServer($sqlUpt,"update");
			}else{
				$sd = new DateTime($sDate);
				$ed = new DateTime($eDate);
				$ttlHari = $sd->diff($ed);
				$ttlHari = ($ttlHari->days)+1;

				for ($lan = 0; $lan < $ttlHari; $lan++)
				{
					$dateIns = date("Y-m-d",strtotime($sDate.'+ '.$lan.' days'));
					$stCek = $this->cekIntervalDay($dateIns,$dateIns,'cekDate');
					if($stCek == "")
					{
						$sqlIns = " INSERT INTO tblAbsence (id,absdt,absin,absout,abssts,source,absremark,addusrdt) VALUES ('".$empNo."','".$dateIns."','00:00','00:00','3','IJN','IJIN (apps)','".$usrInsNow."') ";
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
				$whereNya = "WHERE ownerid = '".$userId."' AND tanggal = '".$exp[2]."' AND bulan = '".$exp[1]."' AND tahun = '".$exp[0]."' AND deletests = '0' ";
				$sqlUpd = " UPDATE tblactivity SET deletests = '1',updusrdt = '".$usrInsNow."',delusrdt = '".$usrInsNow."' ".$whereNya;
				$this->myapp->getDataQueryDb2($sqlUpd,"Update");

				if($typeCek == "Approve")
				{
					$urut = $lan+1;
					$sqlIns = " INSERT INTO tblactivity (urutan,ownerid,ownername,tanggal,bulan,tahun,activity,bosread,bosreadjob,bosapprove,cuti,sakit,ijin,addusrdt) VALUES ('".$urut."','".$userId."','".$fullName."','".$exp[2]."','".$exp[1]."','".$exp[0]."','PERMISSION','Y','Y','Y','N','N','Y','".$usrInsNow."')";
					$this->myapp->getDataQueryDb2($sqlIns,"Insert");
				}
			}			
		}
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
	function convertDateRemindme($dateNya = "")
	{
		$expDate = explode(" ", $dateNya);
		$drtn = $expDate[0];
		$expDrtn = explode("-", $drtn);
		$dtReturn = $expDrtn[2]."/".$expDrtn[1]."/".$expDrtn[0];
		
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
			if($jml == 0 ){ $stCek = "ada"; }//jika jumlah 0 berarti hari sabtu,minggu atau hari libur
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
	function convertReturnName($dateNya = "")
	{
		$dt = explode("-", $dateNya);
		$tgl = $dt[2];
		$bln = $dt[1];
		$thn = $dt[0];
		if($bln == "01" || $bln == "1"){ $bln = "Jan"; }
		else if($bln == "02" || $bln == "2"){ $bln = "Feb"; }
		else if($bln == "03" || $bln == "3"){ $bln = "Mar"; }
		else if($bln == "04" || $bln == "4"){ $bln = "Apr"; }
		else if($bln == "05" || $bln == "5"){ $bln = "Mei"; }
		else if($bln == "06" || $bln == "6"){ $bln = "Jun"; }
		else if($bln == "07" || $bln == "7"){ $bln = "Jul"; }
		else if($bln == "08" || $bln == "8"){ $bln = "Agus"; }
		else if($bln == "09" || $bln == "9"){ $bln = "Sep"; }
		else if($bln == "10"){ $bln = "Okt"; }
		else if($bln == "11"){ $bln = "Nov"; }
		else if($bln == "12"){ $bln = "Des"; }

		return $tgl." ".$bln." ".$thn;
	}
	function convertDay($dayNya = "")
	{
		$hari = "";
		if($dayNya == "Mon"){ $hari = "Senin"; }
		else if($dayNya == "Tue"){ $hari = "Selasa"; }
		else if($dayNya == "Wed"){ $hari = "Rabu"; }
		else if($dayNya == "Thu"){ $hari = "Kamis"; }
		else if($dayNya == "Fri"){ $hari = "Jum`at"; }
		else if($dayNya == "Sat"){ $hari = "Sabtu"; }
		else if($dayNya == "Sun"){ $hari = "Minggu"; }

		return $hari;
	}
	
	
}

