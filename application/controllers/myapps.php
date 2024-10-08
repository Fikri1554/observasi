<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myapps extends CI_Controller {
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
	function homeMyApps()
	{
		$this->load->view('myApps/home');
	}	
	
	function getMailRegInv($search = "")
	{
		$dataOut = array();
		$dNow = date("Ymd");
		$kdDivMyApps = $this->session->userdata('kdDivMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$userId = $this->session->userdata('userIdMyApps');
		$nmDiv = "";

		$trNya = "";
		$whereNya = "deletests = '0'";

		if($search == "")
		{
			$whereNya .= " AND  batchno = '".$dNow."'";

			if($userType == "user")
			{
				$dataDiv = $this->myapp->getDataDb2("*","tblmstdiv","kddiv = '".$kdDivMyApps."'");

				if(count($dataDiv) > 0)
				{
					$nmDiv = $dataDiv[0]->nmdiv;
				}

				if($nmDiv != "FINANCE & ACCOUNTING DIVISION" AND $nmDiv != "HR & SUPPORT DIVISION")
				{
					$nmDivIn = "'".$nmDiv."'";
					$dataUserDiv = $this->myapp->getData("*","userdivisi_custom_apps","user_id = '".$userId."'","divisi ASC");

					foreach ($dataUserDiv as $key => $val)
					{
						$nmDivIn .= ",'".$val->divisi."'";
					}

					$whereNya .= " AND unitname IN (".$nmDivIn.")";
				}
			}
		}else{
			$slcUnit = $_POST['searchUnit'];
			$sDate = $_POST['sDateSearch'];
			$eDate = $_POST['eDateSearch'];

			$whereNya .= " AND batchno BETWEEN '".$sDate."' AND '".$eDate."'";

			if($slcUnit != "all")
			{
				$whereNya .= " AND unitname = '".$slcUnit."' ";
			}
		}


		$dArr = array();
		$getData = $this->myapp->getDataDb3("*","mailinvoice",$whereNya);

		foreach ($getData as $key => $value)
		{
			$dArr[$value->unitname][$value->idmailinv]['idmailinv'] = $value->idmailinv;
			$dArr[$value->unitname][$value->idmailinv]['tipesenven'] = $value->tipesenven;
			$dArr[$value->unitname][$value->idmailinv]['sendervendor1'] = $value->sendervendor1;
			$dArr[$value->unitname][$value->idmailinv]['sendervendor2'] = $value->sendervendor2;
			$dArr[$value->unitname][$value->idmailinv]['sendervendor2name'] = $value->sendervendor2name;
			$dArr[$value->unitname][$value->idmailinv]['receivebyname'] = $value->receivebyname;
			$dArr[$value->unitname][$value->idmailinv]['mailinvno'] = $value->mailinvno;
			$dArr[$value->unitname][$value->idmailinv]['currency'] = $value->currency;
			$dArr[$value->unitname][$value->idmailinv]['urutan'] = $value->urutan;
			$dArr[$value->unitname][$value->idmailinv]['amount'] = $value->amount;
			$dArr[$value->unitname][$value->idmailinv]['remark'] = $value->remark;
			$dArr[$value->unitname][$value->idmailinv]['barcode'] = $value->barcode;
			$dArr[$value->unitname][$value->idmailinv]['batchno'] = $value->batchno;
			$dArr[$value->unitname][$value->idmailinv]['st_reject'] = $value->st_reject;
			$dArr[$value->unitname][$value->idmailinv]['rejectbyname'] = $value->rejectbyname;
			$dArr[$value->unitname][$value->idmailinv]['file_upload'] = $value->file_upload;
			$dArr[$value->unitname][$value->idmailinv]['companyname'] = $value->companyname;
		}		
		foreach ($dArr as $key => $val)
		{
			$btnReceive = "";
			$invAmount = "";
			$sender = "";
			$trNya .= " <tr>
							<td colspan=\"6\"><span class=\"input-group-text\" style=\"font-size:16px;\"><u>Mail Group : ".$key."</u></span ></td>
						</tr>";
			foreach ($val as $key1 => $valMailInv)
			{
				$btnUnRec = "";
				if($valMailInv['tipesenven'] == '1')
				{
					$sender = $valMailInv['sendervendor1'];
				}else{
					$sender = $valMailInv['sendervendor2']." - ".$valMailInv['sendervendor2name'];
				}
				if($valMailInv['receivebyname'] == "")
				{
					$btnReceive = "<button onclick=\"showModalAccept('".$valMailInv['idmailinv']."');\" type=\"submit\" id=\"btnAccept\" class=\"btn btn-primary btn-xs btn-block\" title=\"Accept\"><i class=\"fa fa-check-square-o\"></i> Accept</button>";
					$btnReceive .= "<button onclick=\"showModalReject('".$valMailInv['idmailinv']."');\" type=\"Reject\" id=\"btnReject\" class=\"btn btn-danger btn-xs btn-block\" title=\"Reject\"><i class=\"fa fa-ban\"></i> Reject</button>";
				}else{
					$usrRcv = explode("#", $valMailInv['receivebyname']);
					$df = explode(" ", $usrRcv[1]);
					$dateCnv = $this->convertDate($df[0],$df[1]);
					$btnReceive = "Accepted By : <br>".$usrRcv[0]."<br>".$dateCnv;
				}
				if($valMailInv['mailinvno'] == "")
				{
					$invAmount = "";
				}else{
					if($valMailInv['amount'] == "" || $valMailInv['amount'] == '0')
					{
						$invAmount = $valMailInv['mailinvno'];
					}else{
						$invAmount = $valMailInv['mailinvno']."<br>(".$valMailInv['currency'].") ".number_format($valMailInv['amount'],2);
					}
				}
				if($valMailInv['receivebyname'] != "")
				{
					$btnUnRec = "<input type=\"checkbox\" onclick=\"cekCheck();\" class=\"form-check-input\" value=\"".$valMailInv['idmailinv']."\" >";
				}
				if($valMailInv['st_reject'] == "1")
				{
					$btnUnRec = "";
					$btnReceive = "<i style=\"color:red;\">Rejected</i>";
					if($valMailInv['rejectbyname'] != "")
					{
						$usrRjc = explode("#", $valMailInv['rejectbyname']);
						$drj = explode(" ", $usrRjc[1]);
						$dateRjc = $this->convertDate($drj[0],$drj[1]);
						$btnReceive = "<i style=\"color:red;\">Rejected By : <br>".$usrRjc[0]."<br>".$dateRjc."</i>";
					}
				}

				$barcodeNo = $valMailInv['barcode'];

				if($valMailInv['file_upload'] != "")
				{
					$barcodeNo = "<a href=\"".base_url('../andhikaportal/invoiceRegister/templates/fileUpload')."/".$valMailInv['file_upload']."\" target=\"_blank\">".$valMailInv['barcode']."</a>";
				}

				if($valMailInv['companyname'] != "")
				{
					$sender .= " <i style=\"font-size:11px;color:#FF0000;\">(".$valMailInv['companyname'].")</i>";
				}

				$trNya .= "
							<tr>
								<td align=\"center\">".$valMailInv['urutan']."</td>
								<td>".$sender."<br>".$valMailInv['remark']."</td>
								<td align=\"center\">".$barcodeNo."</td>
								<td>".$invAmount."</td>
								<td align=\"center\">".$valMailInv['batchno']."</td>
								<td align=\"center\" style=\"vertical-align: middle;\">".$btnReceive."</td>
								<td align=\"center\" style=\"vertical-align: middle;\">".$btnUnRec."</td>
							</tr>
							";
			}
		}
		$dataOut['trNya'] = $trNya;
		if($search == "")
		{
			$dataOut['optUnit'] = $this->getOpsUnit($nmDiv);
			$this->load->view('myApps/mailRegInv',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}
	function updateDataReceive()
	{
		$dateNow = date("m-d-Y H:i:s");
		$fullName = rtrim($this->session->userdata('fullNameMyApps'));
		$usrAdd = $fullName."#".$dateNow;
		$id = $_POST['id'];
		$txtReason = $_POST['txtReason'];
		$status = "";

		try {
			$updateData['receivebyname'] = $usrAdd;
			$updateData['st_receive'] = '1';
			$updateData['reason_receive'] = $txtReason;
			$whereNya = "idmailinv = '".$id."' ";
			$this->myapp->updateDataDb3("mailinvoice",$updateData,$whereNya);
			$status = "Accept Success..!!";
			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	function unReceive()
	{
		$data = $_POST;
		$dtCheckedNya = $data['dtChecked'];
		$status = "";
		try {
			$idUpdate = "";
			for ($lan = 0; $lan < count($dtCheckedNya) ; $lan++)
			{
				if($idUpdate == "")
				{
					$idUpdate = "'".$dtCheckedNya[$lan]."'";
				}else{
					$idUpdate .= ",'".$dtCheckedNya[$lan]."'";
				}
			}
			$updateData['receivebyname'] = "";
			$updateData['st_receive'] = "0";
			$updateData['reason_receive'] = "";
			$updateData['rejectbyname'] = "";
			$updateData['st_reject'] = "0";
			$updateData['reason_reject'] = "";
			$whereNya = "idmailinv IN (".$idUpdate.") ";
			$this->myapp->updateDataDb3("mailinvoice",$updateData,$whereNya);
			$status = "Un Accept Success..!!";			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	function updateDataReject()
	{
		$dateNow = date("m-d-Y H:i:s");
		$fullName = rtrim($this->session->userdata('fullNameMyApps'));
		$usrAdd = $fullName."#".$dateNow;
		$id = $_POST['id'];
		$txtReason = $_POST['txtReason'];
		$status = "";

		try {
			$updateData['receivebyname'] = "";
			$updateData['st_receive'] = "0";
			$updateData['reason_receive'] = "";
			$updateData['rejectbyname'] = $usrAdd;
			$updateData['st_reject'] = '1';
			$updateData['reason_reject'] = $txtReason;

			$whereNya = "idmailinv = '".$id."' ";
			$this->myapp->updateDataDb3("mailinvoice",$updateData,$whereNya);
			$status = "Receive Success..!!";
			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
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
				$whereNya .= " AND A.startdt >= '".$data['sDate']."' AND A.enddt <= '".$data['eDate']."'";
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
								<button onclick=\"actionCuti('".$value->empno."','".$value->startdt."','".$value->enddt."');\" type=\"submit\" id=\"btnSearch\" class=\"btn btn-primary btn-xs\" title=\"Recieved\">Approve</button>
								<button onclick=\"actionReject('".$value->empno."','".$value->startdt."','".$value->enddt."');\" type=\"submit\" id=\"btnSearch\" class=\"btn btn-danger btn-xs\" title=\"Recieved\">Reject</button>
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

		$sql = "UPDATE tblempcuti SET stsleave = 'A',updusrdt = '".$usrNow."' WHERE empno = '".$data['empNo']."' AND startdt = '".$data['sDate']."' AND enddt = '".$data['eDate']."' ";
		$sqlIns = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$data['empNo']."','".$usrInsNow."')";
		try {
			$this->myapp->querySqlServer($sql,"update");
			$this->myapp->querySqlServer($sqlIns,"Insert");
			$this->insSendToHR($data['empNo'],$data['sDate'],$data['eDate']);
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

		$sql = "UPDATE tblempcuti SET stsleave = 'C',updusrdt = '".$usrNow."',remark = '".$data['remark']."' WHERE empno = '".$data['empNo']."' AND startdt = '".$data['sDate']."' AND enddt = '".$data['eDate']."' ";
		$sqlReject = "INSERT INTO tblRemindMe(notesdt,notes,notesfrom,notesto,addusrdt)VALUES('".$dateTImeNow."','".$noteNya."','00000','".$data['empNo']."','".$usrInsNow."')";
		print_r($sqlReject);exit;
		try {
			$this->myapp->querySqlServer($sql,"update");
			$this->myapp->querySqlServer($sqlReject,"Insert");
			$status = "Success..!!";
		} catch (Exception $e) {
			$status = "Failed ".$e;
		}

		print json_encode($status);
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
	function userSetting()
	{
		$dataOut = array();
		$trNya = "";
		$opt = "";

		$sql2 = "SELECT * FROM login WHERE active = 'Y' AND deletests = '0' ORDER BY userfullnm ASC";
		$dataUser = $this->myapp->getDataQueryDb2($sql2);
		foreach ($dataUser as $key => $val)
		{
			$opt .= "<option value=\"".$val->userid."\">".$val->userfullnm."</option>";
		}		
		$dataOut['optUsr'] = $opt;

		$trNya = "<tr><td colspan=\"4\" align=\"center\"> => Select Name <= </td></tr>";
		$dataOut['trNya'] = $trNya;
		$this->load->view('myApps/userSetting',$dataOut);
	}	
	function userSettingSearch()
	{
		$dataOut = array();
		$trNya = "";
		$opt = "";
		$no =1;

		$dataSearch = $_POST;
		$whereNya = " where A.user_id = '".$dataSearch['usrId']."' ";
		$sql = "SELECT A.*,B.name_apps FROM user_setting_apps A LEFT JOIN mst_app B ON A.apps = B.id ".$whereNya." ORDER BY A.fullname,B.name_apps ASC";
		$data = $this->myapp->getDataQuery($sql);		
		foreach ($data as $key => $value)
		{
			$trNya .= "
						<tr>
							<td align=\"center\">".$no."</td>
							<td>".$value->fullname."</td>
							<td>".$value->name_apps."</td>							
							<td align=\"center\">
								<button onclick=\"delData('".$value->id."');\" type=\"button\" id=\"btnDel\" class=\"btn btn-danger btn-xs\" title=\"Delete Data\">Delete</button>
							</td>
						</tr>
					 ";
			$no++;
		}
		print json_encode($trNya);		
	}
	function addUserSetting()
	{
		$data = $_POST;
		$dataIns = array();
		$status = "";

		$dataIns['user_id'] = $data['usrId'];
		$dataIns['fullname'] = $data['fullName'];
		$dataIns['apps'] = $data['myApps'];
		try {
			$this->myapp->insData("user_setting_apps",$dataIns);
			$status = "Insert Success..!!";
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	function userDivSetting()
	{
		$dataOut = array();
		$trNya = "";
		$opt = "";
		$optDiv = "";

		$sql = "SELECT * FROM login WHERE active = 'Y' AND deletests = '0' ORDER BY userfullnm ASC";
		$dataUser = $this->myapp->getDataQueryDb2($sql);
		foreach ($dataUser as $key => $val)
		{
			$opt .= "<option value=\"".$val->userid."\">".$val->userfullnm."</option>";
		}

		$sql2 = "SELECT kddiv,nmdiv FROM tblmstdiv WHERE deletests = '0' ORDER BY nmdiv ASC";
		$dataOptDiv = $this->myapp->getDataQueryDb2($sql2);
		foreach ($dataOptDiv as $key => $val)
		{
			$optDiv .= "<option value=\"".$val->nmdiv."\">".$val->nmdiv."</option>";
		}

		$dataOut['optDiv'] = $optDiv;
		$dataOut['optUsr'] = $opt;

		$trNya = "<tr><td colspan=\"4\" align=\"center\"> => Select Name <= </td></tr>";
		$dataOut['trNya'] = $trNya;
		$this->load->view('myApps/userDivSetting',$dataOut);
	}
	function userDivSettingSearch()
	{
		$dataOut = array();
		$trNya = "";
		$opt = "";
		$no =1;

		$dataSearch = $_POST;
		$whereNya = " where user_id = '".$dataSearch['usrId']."' ";

		$sql = "SELECT * FROM userdivisi_custom_apps ".$whereNya." ORDER BY divisi ASC";
		$data = $this->myapp->getDataQuery($sql);		
		foreach ($data as $key => $value)
		{
			$trNya .= "
						<tr>
							<td align=\"center\">".$no."</td>
							<td>".$value->fullname."</td>
							<td>".$value->divisi."</td>							
							<td align=\"center\">
								<button onclick=\"delData('".$value->id."');\" type=\"button\" id=\"btnDel\" class=\"btn btn-danger btn-xs\" title=\"Delete Data\">Delete</button>
							</td>
						</tr>
					 ";
			$no++;
		}
		print json_encode($trNya);		
	}
	function delUserDivSetting()
	{
		$id = $_POST['id'];
		$idWhere = "id = '".$id."'";
  		$this->myapp->delData("userdivisi_custom_apps",$idWhere);
	}
	function addUserDivSetting()
	{
		$data = $_POST;
		$dataIns = array();
		$status = "";

		$dataIns['user_id'] = $data['usrId'];
		$dataIns['fullname'] = $data['fullName'];
		$dataIns['divisi'] = $data['divisiNya'];

		try {
			$this->myapp->insData("userdivisi_custom_apps",$dataIns);
			$status = "Insert Success..!!";
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	function getOpsUnit($nmDiv = '')
	{
		$userId = $this->session->userdata('userIdMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$opt = "<option value=\"\">- Select Unit -</option>";
		$whereNya = "deletests = '0'";

		if($userType == "user")
		{
			$opt = "<option value=\"all\">All</option>";
			if($nmDiv != "FINANCE & ACCOUNTING DIVISION" AND $nmDiv != "HR & SUPPORT DIVISION")
			{
				$opt = "";
				$nmDivIn = "'".$nmDiv."'";
				$dataUserDiv = $this->myapp->getData("*","userdivisi_custom_apps","user_id = '".$userId."'","divisi ASC");

				foreach ($dataUserDiv as $key => $val)
				{
					$nmDivIn .= ",'".$val->divisi."'";
				}

				$whereNya .= " AND unitname IN (".$nmDivIn.")";
			}
		}else{
			$opt = "<option value=\"all\">All</option>";
		}

		$cekUnit = $this->myapp->getDataDb3("unitname","mailinvoice",$whereNya,"unitname ASC","unitname");
		if(count($cekUnit) > 0)
		{			
			foreach ($cekUnit as $key => $val)
			{
				$opt .= "<option value=\"".$val->unitname."\">".$val->unitname."</option>";
			}
		}

		return $opt;
	}
	function delUserSetting()
	{
		$id = $_POST['id'];
		$idWhere = "id = '".$id."'";
  		$this->myapp->delData("user_setting_apps",$idWhere);
	}
	function getOptMyApps()
	{
		$data = $_POST;
		$usrId = $data['usrId'];
		$fullName = $data['fullName'];
		$usrIdMyApp = "";
		$opt = "";

		$cekMyApps = $this->myapp->getData("*","user_setting_apps","user_id = '".$usrId."' ");
		if(count($cekMyApps) > 0)
		{
			foreach ($cekMyApps as $key => $val)
			{
				if($usrIdMyApp == "")
				{
					$usrIdMyApp = "'".$val->apps."'";
				}else{
					$usrIdMyApp .= ",'".$val->apps."'";
				}
			}
		}
		$whereNya = "";
		if($usrIdMyApp != "")
		{
			$whereNya = "id NOT IN(".$usrIdMyApp.")";
		}
		$cekMstApp = $this->myapp->getData("*","mst_app",$whereNya,"name_apps ASC");
		foreach ($cekMstApp as $key => $value)
		{
			$opt .= "<option value=\"".$value->id."\">".$value->name_apps."</option>";
		}
		print json_encode($opt);
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
	function cekShowMenuMyApps()
	{
		$userId = $_POST['userId'];
		$cekMyApps = $this->myapp->getJoin2("B.name_apps","user_setting_apps A","mst_app B","B.id = A.apps","LEFT","user_id = '".$userId."' ");
		print json_encode($cekMyApps);
	}
	function cekIntervalDay($sDate = "",$eDate = "")
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
		return $jml;
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
	function login()
	{
		$data = $_POST;
		$user = $data['user'];
		$pass = md5($data['pass']);
		$status = '';
		$whereNya = "username = '".$user."' AND userpass = '".$pass."' AND active = 'Y' AND deletests = '0' ";
		
		$cekLogin = $this->myapp->getDataDb2("*","login",$whereNya);
		
		if(count($cekLogin) > 0)
		{
			$sql = "SELECT jnsklm FROM tblmstemp WHERE empno = '".$cekLogin[0]->empno."' ";
			$jnsKelamin = $this->myapp->querySqlServer($sql);
			
			$this->session->set_userdata('userIdMyApps',$cekLogin[0]->userid);
			$this->session->set_userdata('empNo',$cekLogin[0]->empno);
			$this->session->set_userdata('fullNameMyApps',$cekLogin[0]->userfullnm);
			$this->session->set_userdata('userTypeMyApps',$cekLogin[0]->userjenis);
			$this->session->set_userdata('userInitial',$cekLogin[0]->userinithr);
			$this->session->set_userdata('nmDiv',$cekLogin[0]->nmdiv);
			$this->session->set_userdata('nmDept',$cekLogin[0]->nmdept);
			$this->session->set_userdata('hrAdm',$cekLogin[0]->hradm);
			$this->session->set_userdata('jnsKelamin',$jnsKelamin[0]->jnsklm);
			$this->session->set_userdata('kdDivMyApps',$cekLogin[0]->kddiv);
			$status = true;
		}
		else
		{
			$status = false;
		}
		print json_encode($status);
	}	
	function logout()
	{
		// $this->session->sess_destroy();
		$this->session->unset_userdata('userIdMyApps');
		$this->session->unset_userdata('empNo');
		$this->session->unset_userdata('fullNameMyApps');
		$this->session->unset_userdata('userTypeMyApps');
		$this->session->unset_userdata('userInitial');
		$this->session->unset_userdata('nmDiv');
		$this->session->unset_userdata('nmDept');
		$this->session->unset_userdata('hrAdm');
		$this->session->unset_userdata('jnsKelamin');
		$this->session->unset_userdata('kdDivMyApps');
		redirect(base_url("myapps"));
	}

	
}