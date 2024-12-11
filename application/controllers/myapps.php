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
		$trAnotherNya = "";
		$whereNya = "deletests = '0'";
		$nmDivIn = "";

		if($search == "")
		{
			//$dNow = '20200511';
			$whereNya .= " AND  batchno = '".$dNow."'";

			if($userType == "user")
			{
				$dataDiv = $this->myapp->getDataDb2("*","tblmstdiv","kddiv = '".$kdDivMyApps."'");

				if(count($dataDiv) > 0)
				{
					$nmDiv = $dataDiv[0]->nmdiv;
				}

				if($nmDiv != "FINANCE & ACCOUNTING DIVISION" AND $nmDiv != "FINANCIAL CONTROLLER")
				{
					if($nmDiv != "")
					{
						$nmDivIn = "'".$nmDiv."'";
					}

					$dataUserDiv = $this->myapp->getData("*","userdivisi_custom_apps","user_id = '".$userId."'","divisi ASC");

					foreach ($dataUserDiv as $key => $val)
					{
						if($nmDivIn == "")
						{
							$nmDivIn = "'".$val->divisi."'";
						}else{
							$nmDivIn .= ",'".$val->divisi."'";
						}
					}
					$whereNya .= " AND unitname IN (".$nmDivIn.")";
				}
				$trAnotherNya = $this->getAnotherApprove($userId,$nmDivIn);
			}
		}else{
			$slcUnit = $_POST['searchUnit'];
			$searchData = $_POST['searchData'];
			$nmDivIn = "'".$_POST['searchUnit']."'";
			$sDate = $_POST['sDateSearch'];
			$eDate = $_POST['eDateSearch'];

			$whereNya .= " AND batchno BETWEEN '".$sDate."' AND '".$eDate."'";

			if($slcUnit != "all")
			{
				$whereNya .= " AND unitname = '".$slcUnit."' ";
			}
			
			if($searchData != "")
			{
				$whereNya .= " AND (sendervendor1 LIKE '%".$searchData."%' OR sendervendor2name LIKE '%".$searchData."%') ";
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
			$dArr[$value->unitname][$value->idmailinv]['another_approve'] = $value->another_approve;
			$dArr[$value->unitname][$value->idmailinv]['st_another_approve'] = $value->st_another_approve;
			$dArr[$value->unitname][$value->idmailinv]['byname_another_approve'] = $value->byname_another_approve;
			$dArr[$value->unitname][$value->idmailinv]['userId_another_approve'] = $value->userId_another_approve;
			$dArr[$value->unitname][$value->idmailinv]['reason_reject'] = $value->reason_reject;
			$dArr[$value->unitname][$value->idmailinv]['reason_receive'] = $value->reason_receive;
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
				$stRemarkToolTips = "";
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
					$btnReceive = "Accepted By : <br>- ".$usrRcv[0]."<br>".$dateCnv;
					$stRemarkToolTips = "title=\"".$valMailInv['reason_receive']."\"";

					if($valMailInv['another_approve'] == "Y")
					{
						if($valMailInv['byname_another_approve'] != "")
						{
							$usrRcvApprv = explode("#", $valMailInv['byname_another_approve']);
							$dfApprv = explode(" ", $usrRcvApprv[1]);
							$dateCnvApprv = $this->convertDate($dfApprv[0],$dfApprv[1]);

							if($valMailInv['userId_another_approve'] == $userId)
							{
								if($valMailInv['st_another_approve'] == "0")
								{
									$btnReceive .= "<button onclick=\"showModalAcceptAnother('".$valMailInv['idmailinv']."');\" type=\"submit\" id=\"btnAccept\" class=\"btn btn-primary btn-xs btn-block\" title=\"Accept Another\"><i class=\"fa fa-check-square-o\"></i> Accept Another</button>";
									$btnReceive .= "<button onclick=\"showModalReject('".$valMailInv['idmailinv']."');\" type=\"Reject\" id=\"btnReject\" class=\"btn btn-danger btn-xs btn-block\" title=\"Reject Another\"><i class=\"fa fa-ban\"></i> Reject Another</button>";
								}else{
									$btnReceive .= "<br>- ".$usrRcvApprv[0]."<br>".$dateCnvApprv;
								}								
							}else{
								if($valMailInv['st_another_approve'] == "0")
								{
									$btnReceive .= "<br>Waiting Approval : <br>-".$usrRcvApprv[0];
								}else{
									$btnReceive .= "<br>- ".$usrRcvApprv[0]."<br>".$dateCnvApprv;
								}
							}
						}
					}
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
					$stRemarkToolTips = "title=\"".$valMailInv['reason_reject']."\"";
					
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
								<td align=\"left\" style=\"vertical-align: middle;\" ".$stRemarkToolTips.">".$btnReceive."</td>
								<td align=\"center\" style=\"vertical-align: middle;\">".$btnUnRec."</td>
							</tr>
							";
			}
		}
		$dataOut['trNya'] = $trAnotherNya;
		$dataOut['trNya'] .= $trNya;
		if($search == "")
		{
			$dataOut['optUnit'] = $this->getOpsUnit($nmDiv);
			$dataOut['optName'] = $this->getOptName();
			$this->load->view('myApps/mailRegInv',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}
	
	function getAnotherApprove($userId='',$divName='')
	{
		$trNya = "";
		if($divName != "")
		{
			$trNya = "";
			$whereNya = "deletests = '0' AND another_approve = 'Y' AND st_another_approve = '0' AND userId_another_approve = '".$userId."' AND unitname NOT IN (".$divName.")";

			$dArr = array();
			$getAnother = $this->myapp->getDataDb3("*","mailinvoice",$whereNya);
			foreach ($getAnother as $key => $value)
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
				$dArr[$value->unitname][$value->idmailinv]['st_another_approve'] = $value->st_another_approve;
			}
			foreach ($dArr as $key => $val)
			{
				$trNya .= " <tr>
								<td colspan=\"6\"><span class=\"input-group-text\" style=\"font-size:16px;\"><u>Another Group : ".$key."</u></span ></td>
							</tr>";
				foreach ($val as $key1 => $valMailInv)
				{
					$btnUnRec = "";
					$btnReceive = "";
					$sender = "";
					$invAmount = "";
					if($valMailInv['tipesenven'] == '1')
					{
						$sender = $valMailInv['sendervendor1'];
					}else{
						$sender = $valMailInv['sendervendor2']." - ".$valMailInv['sendervendor2name'];
					}
					if($valMailInv['receivebyname'] != "")
					{
						$usrRcv = explode("#", $valMailInv['receivebyname']);
						$df = explode(" ", $usrRcv[1]);
						$dateCnv = $this->convertDate($df[0],$df[1]);
						$btnReceive = "Accepted By : <br>".$usrRcv[0]."<br>".$dateCnv;

						$btnUnRec = "<input type=\"checkbox\" onclick=\"cekCheck();\" class=\"form-check-input\" value=\"".$valMailInv['idmailinv']."\" >";					
					}

					if($valMailInv['st_another_approve'] == "0")
					{

						$btnReceive .= "<button onclick=\"showModalAcceptAnother('".$valMailInv['idmailinv']."');\" type=\"submit\" id=\"btnAccept\" class=\"btn btn-primary btn-xs btn-block\" title=\"Accept Another\"><i class=\"fa fa-check-square-o\"></i> Accept Another</button>";
						$btnReceive .= "<button onclick=\"showModalReject('".$valMailInv['idmailinv']."');\" type=\"Reject\" id=\"btnReject\" class=\"btn btn-danger btn-xs btn-block\" title=\"Reject Another\"><i class=\"fa fa-ban\"></i> Reject Another</button>";
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
		}
		return $trNya;
	}
	function updateDataReceive()
	{
		$dateNow = date("m-d-Y H:i:s");
		$fullName = rtrim($this->session->userdata('fullNameMyApps'));
		$userId = rtrim($this->session->userdata('userIdMyApps'));
		$usrAdd = $fullName."#".$dateNow;
		$id = $_POST['id'];
		$txtReason = $_POST['txtReason'];
		$anotherApr = $_POST['anotherApr'];
		$slcAprvUserId = $_POST['slcAprvUserId'];
		$slcAprvName = $_POST['slcAprvName']."#".$dateNow;
		$txtTypeDataModal = $_POST['txtTypeDataModal'];
		$status = "";

		try {

			if($txtTypeDataModal == "")
			{
				$updateData['receivebyuserid'] = $userId;
				$updateData['receivebyname'] = $usrAdd;
				$updateData['st_receive'] = '1';
				$updateData['reason_receive'] = $txtReason;

				if($anotherApr == "Y")
				{
					$updateData['another_approve'] = $anotherApr;
					$updateData['userId_another_approve'] = $slcAprvUserId;
					$updateData['byname_another_approve'] = $slcAprvName;
				}
			}else{
				$updateData['reason_receive'] = $txtReason;
				$updateData['st_another_approve'] = "1";
				$updateData['userId_another_approve'] = $userId;
				$updateData['byname_another_approve'] = $usrAdd;
			}

			$whereNya = "idmailinv = '".$id."' ";
			$this->myapp->updateDataDb3("mailinvoice",$updateData,$whereNya);
			
			$this->sentNotifDesktop($id);
			$status = "Accept Success..!!";
			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	function sentNotifDesktop($idMailInv = '')
	{
		$reqName = "";
		$barcode = "";
		$transNo = "";
		$dateNow = date("Y-m-d H:i:s");
		$userInit = $this->session->userdata('userInitial');
		$addUsrDate = $userInit."#".date('H:i')."#".date('d/m/Y');

		$getData = $this->myapp->getDataDb3("*","mailinvoice","deletests = '0' AND idmailinv = '".$idMailInv."' ");

		if(count($getData) > 0)
		{
			$noteNya = "Invoice Register Confirmed By ".$getData[0]->receivebyname.", Invoice No : ".$getData[0]->mailinvno.", Barcode : ".$getData[0]->barcode;

			if($getData[0]->another_approve == 'Y' AND $getData[0]->st_another_approve == '1')
			{
				$noteNya = "Invoice Register Confirmed By ".$getData[0]->byname_another_approve.", Invoice No : ".$getData[0]->mailinvno;
			}

			$getUserNotif = $this->myapp->getDataDb7("*","tblusernotif","modul_name = 'invoiceregister'");

			foreach ($getUserNotif as $key => $val)
			{
				$sqlIns = "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$dateNow."', '".$noteNya."', '00000', '".$val->empno."', '".$addUsrDate."');";
				$this->myapp->querySqlServer($sqlIns,"insert");
			}
		}
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
			$updateData['receivebyuserid'] = "00000";
			$updateData['receivebyname'] = "";
			$updateData['st_receive'] = "0";
			$updateData['reason_receive'] = "";
			$updateData['rejectbyname'] = "";
			$updateData['st_reject'] = "0";
			$updateData['reason_reject'] = "";
			$updateData['another_approve'] = "N";
			$updateData['st_another_approve'] = "0";
			$updateData['userId_another_approve'] = "00000";
			$updateData['byname_another_approve'] = "";

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
			$updateData['another_approve'] = "N";
			$updateData['st_another_approve'] = "0";
			$updateData['userId_another_approve'] = "00000";
			$updateData['byname_another_approve'] = "";

			$whereNya = "idmailinv = '".$id."' ";
			$this->myapp->updateDataDb3("mailinvoice",$updateData,$whereNya);
			$status = "Receive Success..!!";
			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	
	function getConfirmPaymentAdvance($search = "")
	{
		$dataOut = array();
		$dNow = date("Ymd");
		$kdDivMyApps = $this->session->userdata('kdDivMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$userId = $this->session->userdata('userIdMyApps');
		$nmDiv = "";

		$trNya = "";
		$whereNya = "st_delete = '0'";

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

				if($nmDiv != "FINANCE & ACCOUNTING DIVISION" AND $nmDiv != "FINANCIAL CONTROLLER" AND $nmDiv != "HR & SUPPORT DIVISION" AND $nmDiv != "HUMAN CAPITAL & GA")
				{
					$nmDivIn = "";
					if($nmDiv != "")
					{
						$nmDivIn = "'".$nmDiv."'";
					}

					$dataUserDiv = $this->myapp->getData("*","userdivisi_custom_apps","user_id = '".$userId."'","divisi ASC");

					foreach ($dataUserDiv as $key => $val)
					{
						if($nmDivIn == "")
						{
							$nmDivIn = "'".$val->divisi."'";
						}else{
							$nmDivIn .= ",'".$val->divisi."'";
						}
					}
					if($nmDivIn != "")
					{
						$whereNya .= " AND divisi IN (".$nmDivIn.")";
					}
				}
			}
		}else{
			$slcUnit = $_POST['searchUnit'];
			$sDate = $_POST['sDateSearch'];
			$eDate = $_POST['eDateSearch'];

			$whereNya .= " AND batchno BETWEEN '".$sDate."' AND '".$eDate."'";

			if($slcUnit != "all")
			{
				$whereNya .= " AND divisi = '".$slcUnit."' ";
			}
		}

		$dArr = array();
		$getData = $this->myapp->getDataDb7("*","datapayment",$whereNya);

		foreach ($getData as $key => $value)
		{
			$dArr[$value->divisi][$value->id]['id'] = $value->id;
			$dArr[$value->divisi][$value->id]['batchno'] = $value->batchno;
			$dArr[$value->divisi][$value->id]['request_name'] = $value->request_name;
			$dArr[$value->divisi][$value->id]['accountsendervendor'] = $value->accountsendervendor;
			$dArr[$value->divisi][$value->id]['sendervendor'] = $value->sendervendor;
			$dArr[$value->divisi][$value->id]['voyage_no'] = $value->voyage_no;
			$dArr[$value->divisi][$value->id]['vessel_code'] = $value->vessel_code;
			$dArr[$value->divisi][$value->id]['vessel_name'] = $value->vessel_name;
			$dArr[$value->divisi][$value->id]['company_name'] = $value->company_name;
			$dArr[$value->divisi][$value->id]['barcode'] = $value->barcode;
			$dArr[$value->divisi][$value->id]['invoice_date'] = $value->invoice_date;
			$dArr[$value->divisi][$value->id]['invoice_due_date'] = $value->invoice_due_date;
			$dArr[$value->divisi][$value->id]['mailinvno'] = $value->mailinvno;
			$dArr[$value->divisi][$value->id]['currency'] = $value->currency;
			$dArr[$value->divisi][$value->id]['amount'] = $value->amount;
			$dArr[$value->divisi][$value->id]['remark'] = $value->remark;
			$dArr[$value->divisi][$value->id]['st_submit'] = $value->st_submit;
			$dArr[$value->divisi][$value->id]['st_confirm'] = $value->st_confirm;
			$dArr[$value->divisi][$value->id]['confirm_userId'] = $value->confirm_userId;
			$dArr[$value->divisi][$value->id]['confirm_userDate'] = $value->confirm_userDate;
			$dArr[$value->divisi][$value->id]['reject_status'] = $value->reject_status;
			$dArr[$value->divisi][$value->id]['reject_userId'] = $value->reject_userId;
			$dArr[$value->divisi][$value->id]['reject_date'] = $value->reject_date;
		}

		foreach ($dArr as $key => $val)
		{
			$no = 1;
			$btnReceive = "";
			$invAmount = "";
			$sender = "";
			$trNya .= " <tr>
							<td colspan=\"6\"><span class=\"input-group-text\" style=\"font-size:16px;\"><u>Mail Group : ".$key."</u></span ></td>
						</tr>";
			foreach ($val as $key1 => $vals)
			{
				$reqName = strtoupper($vals['request_name']);

				if($vals['st_confirm'] == "N")
				{
					$btnReceive = "<button onclick=\"acceptNya('".$vals['id']."');\" type=\"submit\" id=\"btnAccept\" class=\"btn btn-primary btn-xs btn-block\" title=\"Accept\"><i class=\"fa fa-check-square-o\"></i> Confirm</button>";
					$btnReceive .= "<button onclick=\"showModalReject('".$vals['id']."');\" type=\"Reject\" id=\"btnReject\" class=\"btn btn-danger btn-xs btn-block\" title=\"Reject\"><i class=\"fa fa-ban\"></i> Reject</button>";
				}else{
					$btnReceive = "Accepted";
					if($vals['confirm_userId'] != "00000")
					{
						$usrRcv = $this->myapp->getDataDb2("userfullnm","login","userid = '".$vals['confirm_userId']."'");
						$dateCnv = $this->convertDateOnly($vals['confirm_userDate']);

						$btnReceive = "Accepted By : <br>".$usrRcv[0]->userfullnm."<br>".$dateCnv;
					}
				}

				if($vals['st_submit'] == "N" AND $vals['reject_status'] == "Y")
				{
					$btnReceive = "<i style=\"color:red;\">Rejected</i>";
					if($valMailInv['reject_userId'] != "00000")
					{
						$rejectUser = $this->myapp->getDataDb2("userfullnm","login","userid = '".$vals['reject_userId']."'");
						$rejectDate = $this->convertDateOnly($vals['reject_date']);

						$btnReceive = "Rejected By :<br>".$rejectUser[0]->userfullnm."<br>".$rejectDate;
					}
				}

				$trNya .= "
							<tr>
								<td align=\"center\">".$no."</td>
								<td>".$vals['batchno']."</td>
								<td>".$reqName." <i style=\"font-size:11px;color:#FF0000;\">(".$vals['company_name'].")</i><br>".$vals['remark']."</td>
								<td>".$vals['barcode']."</td>
								<td>".$vals['mailinvno']."<br><i style=\"font-size:11px;color:#FF0000;\">(".$vals['currency'].") ".number_format($vals['amount'],2)."</i></td>
								<td align=\"center\" style=\"vertical-align: middle;\">".$btnReceive."</td>
							</tr>
							";
				$no++;
			}
		}
		$dataOut['trNya'] = $trNya;
		if($search == "")
		{
			$dataOut['optUnit'] = $this->getOpsUnit($nmDiv);
			$this->load->view('myApps/confirmPaymentAdvance',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}

	function exportDataMailInvoiceRegister() {
		$postData = json_decode(file_get_contents("php://input"), true);

		$slcUnit = isset($postData['searchUnit']) ? $postData['searchUnit'] : null;
		$sDate = isset($postData['sDateSearch']) ? $postData['sDateSearch'] : null;
		$eDate = isset($postData['eDateSearch']) ? $postData['eDateSearch'] : null;

		if (empty($sDate) || empty($eDate)) {
			header('Content-Type: application/json');
			echo json_encode(array("error" => "Start date and end date are required"));
			return;
		}

		$kdDivMyApps = $this->session->userdata('kdDivMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$userId = $this->session->userdata('userIdMyApps');
		$nmDiv = "";

		$whereNya = "deletests = '0'";
		$whereNya .= " AND batchno BETWEEN '" . $sDate . "' AND '" . $eDate . "'";

		if (!empty($slcUnit) && $slcUnit !== "all") {
			$whereNya .= " AND unitname = '" . $this->db->escape_str($slcUnit) . "'";
		} elseif ($userType === "user") {
			$dataDiv = $this->myapp->getDataDb2("*", "tblmstdiv", "kddiv = '" . $this->db->escape_str($kdDivMyApps) . "'");
			if (!empty($dataDiv)) {
				$nmDiv = $dataDiv[0]->nmdiv;
			}

			if (!in_array($nmDiv, array("FINANCE & ACCOUNTING DIVISION", "FINANCIAL CONTROLLER"))) {
				$dataUserDiv = $this->myapp->getData("*", "userdivisi_custom_apps", "user_id = '" . $this->db->escape_str($userId) . "'", "divisi ASC");
				$divisiList = array_map(function ($val) {
					return "'" . $this->db->escape_str($val->divisi) . "'";
				}, $dataUserDiv);

				if (!empty($divisiList)) {
					$whereNya .= " AND unitname IN (" . implode(",", $divisiList) . ")";
				}
			}
		}

		$getData = $this->myapp->getDataDb3("*", "mailinvoice", $whereNya);

		$result = array();
		foreach ($getData as $value) {
			$sender = $value->tipesenven === '1' ? $value->sendervendor1 : $value->sendervendor2 . " - " . $value->sendervendor2name;
			$companyName = $value->companyname ? " (" . $value->companyname . ")" : "";
			$invoiceDetail = $value->amount && $value->amount != '0'
				? $value->mailinvno . " (" . $value->currency . ") " . number_format($value->amount, 2)
				: $value->mailinvno;

			$result[] = array(
				'Batch No' => $value->batchno,
				'Sender (Company) / Remark' => $sender . $companyName . "\n" . $value->remark,
				'Barcode' => $value->barcode,
				'Invoice No / Amount' => $invoiceDetail,
			);
		}

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	
	function exportDataConfirmPaymentAdvance() {
		$postData = json_decode(file_get_contents("php://input"), true);

		$slcUnit = isset($postData['searchUnit']) ? $postData['searchUnit'] : null;
		$sDate = isset($postData['sDateSearch']) ? $postData['sDateSearch'] : null;
		$eDate = isset($postData['eDateSearch']) ? $postData['eDateSearch'] : null;
		$button = "";

		if (empty($sDate) || empty($eDate)) {
			header('Content-Type: application/json');
			echo json_encode(array("error" => "Start date and end date are required"));
			return;
		}

		$kdDivMyApps = $this->session->userdata('kdDivMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$userId = $this->session->userdata('userIdMyApps');
		$nmDiv = "";
		
		if ($userType !== 'admin') {
			header('Content-Type: application/json');
			echo json_encode(array("error" => "You do not have permission to export data."));
			return;
		}
		
		$whereNya = "st_delete = 0";

		$whereNya .= " AND batchno BETWEEN '".$sDate."' AND '".$eDate."'";

		if (!empty($slcUnit) && $slcUnit !== "all") {
			$whereNya .= " AND divisi = '" . $this->db->escape_str($slcUnit) . "'";
		} elseif ($userType === "user") {
			$dataDiv = $this->myapp->getDataDb2("*", "tblmstdiv", "kddiv = '" . $this->db->escape_str($kdDivMyApps) . "'");
			if (!empty($dataDiv)) {
				$nmDiv = $dataDiv[0]->nmdiv;
			}

			if (!in_array($nmDiv, array("FINANCE & ACCOUNTING DIVISION", "FINANCIAL CONTROLLER", "HR & SUPPORT DIVISION", "HUMAN CAPITAL & GA"))) {
				$dataUserDiv = $this->myapp->getData("*", "userdivisi_custom_apps", "user_id = '" . $this->db->escape_str($userId) . "'", "divisi ASC");
				$divisiList = array_map(function ($val) {
					return "'" . $this->db->escape_str($val->divisi) . "'";
				}, $dataUserDiv);

				if (!empty($divisiList)) {
					$whereNya .= " AND divisi IN (" . implode(",", $divisiList) . ")";
				}
			}
		}
		

		$getData = $this->myapp->getDataDb7("batchno, request_name, company_name, remark, barcode, mailinvno, amount", "datapayment", $whereNya);

		$result = array();
		foreach ($getData as $value) {
			$result[] = array(
				'Batch No' => $value->batchno,
				'Sender (Company) / Remark' => $value->request_name . " (" . $value->company_name . ")\n" . $value->remark,
				'Barcode' => $value->barcode,
				'Invoice No / Amount' => $value->mailinvno . " / " . number_format($value->amount, 2),
			);
		}


		header('Content-Type: application/json');
		echo json_encode($result);
	}

	
	function updateDataPaymentAdvance()
	{
		$userId = $this->session->userdata('userIdMyApps');
		$dateNow = date("Y-m-d");
		$id = $_POST['id'];
		$status = "";

		try {
			$updateData['st_confirm'] = 'Y';
			$updateData['confirm_userId'] = $userId;
			$updateData['confirm_userDate'] = $dateNow;
			$whereNya = "id = '".$id."' ";
			$this->myapp->updateDataDb7("datapayment",$updateData,$whereNya);
			$status = "Accept Success..!!";
			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	
	function rejectPaymentAdvance()
	{
		$userId = $this->session->userdata('userIdMyApps');
		$dateNow = date("Y-m-d");
		$id = $_POST['id'];
		$status = "";
		$remarkReject = $_POST['txtReason'];

		try {			
			$updateData['reject_status'] = 'Y';
			$updateData['reject_userId'] = $userId;
			$updateData['reject_date'] = $dateNow;
			$updateData['reject_remark'] = $remarkReject;
			$updateData['st_submit'] = 'N';
			$whereNya = "id = '".$id."' ";

			$this->myapp->updateDataDb7("datapayment",$updateData,$whereNya);
			$status = "Reject Success..!!";
			
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
	}
	function getUploadSupportingDoc($search = "")
	{
		$dataOut = array();
		$dNow = date("Ymd");
		$kdDivMyApps = $this->session->userdata('kdDivMyApps');
		$userType = $this->session->userdata('userTypeMyApps');
		$userId = $this->session->userdata('userIdMyApps');
		$trNya = "";
		$no =1;

		$whereNya = "deletests = '0'";

		if($search != "")
		{
			$txtBarcode = $_POST['txtBarcode'];
			$slcType = $_POST['slcType'];

			if($slcType == "voucher")
			{
				$whereNya .= " AND batchno like '%".$txtBarcode."%' ";
				$data = $this->myapp->getDataDb8("*","tblvoucher",$whereNya);

				foreach ($data as $key => $val)
				{
					$senderVendor = $val->kepada;
					$barcode = $val->barcode;

					if($val->file_upload != "")
					{
						if($barcode == "")
						{
							$barcode = "<a href=\"".base_url('../andhikaportal/voucher/templates/fileUpload')."/".$val->file_upload."\" target=\"_blank\">View File</a>";
						}else{
							$barcode = "<a href=\"".base_url('../andhikaportal/voucher/templates/fileUpload')."/".$val->file_upload."\" target=\"_blank\">".$barcode."</a>";
						}
					}

					$amountNya = $val->amount;

					$btn = "<button type=\"button\" id=\"btnCancelSearch\" onclick=\"showModalUploadVoucher('".$val->idvoucher."','".$val->barcode."');\" class=\"btn btn-danger btn-xs\" title=\"Upload File\">Upload</button>";

					$trNya .= "<tr>";
						$trNya .= "<td align=\"center\">".$no.$btn."</td>";
						$trNya .= "<td align=\"center\">".$val->batchno."</td>";
						$trNya .= "<td>".$senderVendor."</td>";
						$trNya .= "<td align=\"center\">".$barcode."</td>";
						$trNya .= "<td>".$val->companyname."</td>";
						$trNya .= "<td>".$val->invno."</td>";
						$trNya .= "<td>(".$val->currency.") <span style=\"float: right;\">".number_format($amountNya,2)."</span></td>";
					$trNya .= "</tr>";
					$no++;	
				}

			}else{

				$whereNya .= " AND SUBSTR(barcode, 1, 1)='A' AND barcode like '%".$txtBarcode."%' ";
				$data = $this->myapp->getDataDb3("*","mailinvoice",$whereNya);

				if(count($data) > 0)
				{
					foreach ($data as $key => $val)
					{
						$senderVendor = $val->sendervendor1;
						$barcode = $val->barcode;

						if($senderVendor == "")
						{
							$senderVendor = $val->sendervendor2name;
						}

						if($val->file_upload != "")
						{
							$barcode = "<a href=\"".base_url('../andhikaportal/invoiceRegister/templates/fileUpload')."/".$val->file_upload."\" target=\"_blank\">".$val->barcode."</a>";
						}

						$amountNya = ($val->amount + $val->addi) - $val->deduc;

						if($slcType == "invoiceRegisterDueDate")
						{
							$btn = "<button type=\"button\" id=\"btnChangeDueDate\" onclick=\"showModalChangeDueDate('".$val->idmailinv."','".$val->barcode."')\" class=\"btn btn-primary btn-xs\" title=\"Change Due Date\">Due Date</button>";
						}else{
							$btn = "<button type=\"button\" id=\"btnCancelSearch\" onclick=\"showModalUpload('".$val->idmailinv."','".$val->barcode."');\" class=\"btn btn-danger btn-xs\" title=\"Upload File\">Upload</button>";
						}

						$trNya .= "<tr>";
							$trNya .= "<td align=\"center\">".$no.$btn."</td>";
							$trNya .= "<td align=\"center\">".$val->batchno."</td>";
							$trNya .= "<td>".$senderVendor."</td>";
							$trNya .= "<td align=\"center\">".$barcode."</td>";
							$trNya .= "<td>".$val->companyname."</td>";
							$trNya .= "<td>".$val->mailinvno."</td>";
							$trNya .= "<td align=\"center\">".$this->convertReturnName($val->tglinvoice)."</td>";
							$trNya .= "<td align=\"center\">".$this->convertReturnName($val->tglexp)."</td>";
							$trNya .= "<td align=\"right\">(".$val->currency.")<br><span style=\"float: right;\">".number_format($amountNya,2)."</span></td>";
						$trNya .= "</tr>";
						$no++;
					}
				}
			}
		}else{
			$trNya .= "<tr>";
				$trNya .= "<td colspan=\"9\" align=\"center\">:: NO DATA ::</td>";
			$trNya .= "</tr>";
		}

		$dataOut['trNya'] = $trNya;

		if($search == "")
		{
			$this->load->view('myApps/uploadSupportingDoc',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}
	function getDataModalSupportingDoc($typeDoc = "")
	{
		$dataOut = array();
		$id = $_POST['id'];

		$dataOut['batchNo'] = "";
		$dataOut['barcode'] = "";
		$dataOut['senderVendor'] = "";
		$dataOut['company'] = "";
		$dataOut['invNo'] = "";
		$dataOut['amountNya'] = "";
		$dataOut['invDate'] = "";
		$dataOut['receiveDate'] = "";
		$dataOut['receiveDateFormat'] = "";
		$dataOut['dayNya'] = "";
		$dataOut['dueDate'] = "";

		if($typeDoc == "voucher")
		{
			$whereNya = "deletests = '0' AND idvoucher = '".$id."' ";
			$data = $this->myapp->getDataDb8("*","tblvoucher",$whereNya);

			if(count($data) > 0)
			{
				$senderVendor = $data[0]->kepada;

				$amountNya = $data[0]->amount;

				$dataOut['batchNo'] = $data[0]->batchno;
				$dataOut['barcode'] = $data[0]->barcode;
				$dataOut['senderVendor'] = $senderVendor;
				$dataOut['company'] = $data[0]->companyname;
				$dataOut['invNo'] = $data[0]->invno;
				$dataOut['amountNya'] = "(".$data[0]->currency.") &nbsp ".number_format($amountNya,2);
				$dataOut['remark'] = $data[0]->additional;
			}
		}else{
			$whereNya = "deletests = '0' AND SUBSTR(barcode, 1, 1)='A' AND idmailinv = '".$id."' ";

			$data = $this->myapp->getDataDb3("*","mailinvoice",$whereNya);

			if(count($data) > 0)
			{
				$senderVendor = $data[0]->sendervendor1;

				if($senderVendor == "")
				{
					$senderVendor = $data[0]->sendervendor2name;
				}

				$amountNya = ($data[0]->amount + $data[0]->addi) - $data[0]->deduc;

				$dataOut['batchNo'] = $data[0]->batchno;
				$dataOut['barcode'] = $data[0]->barcode;
				$dataOut['senderVendor'] = $senderVendor;
				$dataOut['company'] = $data[0]->companyname;
				$dataOut['invNo'] = $data[0]->mailinvno;
				$dataOut['amountNya'] = "(".$data[0]->currency.") &nbsp ".number_format($amountNya,2);
				$dataOut['remark'] = $data[0]->remark;
				$dataOut['receiveDate'] = $data[0]->receivedate;
				$dataOut['invDate'] = $this->convertReturnName($data[0]->tglinvoice);
				$dataOut['receiveDateFormat'] = $this->convertReturnName($data[0]->receivedate);
				$dataOut['dayNya'] = $data[0]->dueday;
				$dataOut['dueDate'] = $data[0]->tglexp;
			}
		}
		
		print json_encode($dataOut);
	}
	function updateDataModalSupportingDoc()
	{
		$data = $_POST;
		$dataIns = array();
		$status = "";
		$usrInit = $this->session->userdata('userInitial');
		$usrAddLogin = $usrInit."/".date("Ymd")."/".date("H:i:s");		

		$typeDoc = $data['typeDoc'];
		$id = $data['id'];
		$barcode = $data['barcode'];
		$cekFile = $data['cekFile'];
		$remark = $data['remark'];

		try {

			if($typeDoc == "voucher")
			{
				$dir = "./../andhikaPortal/voucher/templates/fileUpload";
				if($cekFile != "")
				{
					$newFileName = "voucher_".$id;
					$fileName = $_FILES["fileUploadNya"]["name"];
					$fileName = $this->uploadFile($_FILES["fileUploadNya"]['tmp_name'],$dir,$fileName,$newFileName);

					$dataIns['file_upload'] = $fileName;
				}
				
				// $dataIns['remark'] = $remark;
				$dataIns['updusrdt'] = $usrAddLogin;

				$whereNya = "idvoucher = '".$id."'";
				$this->myapp->updateDataDb8("tblvoucher",$dataIns,$whereNya);

			}else{
				$dir = "./../andhikaPortal/invoiceRegister/templates/fileUpload";
				if($cekFile != "")
				{
					$newFileName = $barcode;
					$fileName = $_FILES["fileUploadNya"]["name"];
					$fileName = $this->uploadFile($_FILES["fileUploadNya"]['tmp_name'],$dir,$fileName,$newFileName);

					$dataIns['file_upload'] = $fileName;
				}
				
				$dataIns['remark'] = $remark;
				$dataIns['updusrdt'] = $usrAddLogin;

				$whereNya = "idmailinv = '".$id."'";
				$this->myapp->updateDataDb3("mailinvoice",$dataIns,$whereNya);
			}
			
			$status = "Save Success..!!";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessage();
		}
		
		print $status;
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

		$cekUnit = $this->myapp->getDataDb3("unitname","mailinvoice","deletests = '0'","unitname ASC","unitname");
		foreach ($cekUnit as $keys => $vals)
		{
			$optDiv .= "<option value=\"".$vals->unitname."\">".$vals->unitname."</option>";
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
	function getOptName()
	{
		$opt = "";

		$sql2 = "SELECT * FROM login WHERE active = 'Y' AND deletests = '0' ORDER BY userfullnm ASC";
		$dataUser = $this->myapp->getDataQueryDb2($sql2);
		foreach ($dataUser as $key => $val)
		{
			$opt .= "<option value=\"".$val->userid."\">".$val->userfullnm."</option>";
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
	function uploadFile($tmpFile = "",$dir = "",$fileName = "",$newFileName = "")
	{
		$dt = explode(".", $fileName);
		$newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);
		move_uploaded_file($tmpFile, $dir."/".$fileName);
		rename($dir."/".$fileName, $dir."/".$newFileName);
		return $newFileName;
	}
	function getDueDate()
	{
		$dataOut = array();
		$idInvReg = $_POST['idInvReg'];
		$dayNya = $_POST['dayNya'];
		$dueDateNya = $_POST['dueDateNya'];
		$txtReveiceDate = $_POST['txtReveiceDate'];
		$type = $_POST['type'];
		$newDueDate = $dueDateNya;
		$newDayNya = $dayNya;

		if($type == "dayNya")
		{
			if($dayNya == "")
			{
				$dayNya = 0;
			}
			if($dayNya > 0)
			{
				$dayNya = $dayNya - 1;
			}
			
			$date = new DateTime($txtReveiceDate);
			$date->modify("+".$dayNya." day");
			$newDueDate = $date->format('Y-m-d');
		}else{
			$date1 = new DateTime($txtReveiceDate);
			$date2 = new DateTime($dueDateNya);
			$intervalNya = $date1->diff($date2);

			$newDayNya = $intervalNya->days;
		}

		$dataOut['newDayNya'] = $newDayNya;
		$dataOut['newDueDate'] = $newDueDate;

		print json_encode($dataOut);
	}
	function saveDataDueDate()
	{
		$data = $_POST;
		$dataUpdt = array();
		$status = "";
		$idmailinv = $data['id'];

		try {

			$dataUpdt['dueday'] = $data['dayNya'];
			$dataUpdt['tglexp'] = $data['dueDateNya'];

			$whereNya = "idmailinv = '".$idmailinv."' ";
			$this->myapp->updateDataDb3("mailinvoice",$dataUpdt,$whereNya);

			$status = "Submit Success..!!";
		} catch (Exception $e) {
			$status = "Failed =>".$e;
		}
		print json_encode($status);
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
	function convertDateOnly($dateNya = "")
	{
		$expDate = explode("-", $dateNya);
		$dtReturn = $expDate[2]."-".$expDate[1]."-".$expDate[0];
		
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
	function cekSesseion()
	{
		$status = "ada";

		if(!$this->session->userdata('userIdMyApps'))
		{
			$status = "";
		}
		print json_encode($status);
	}
	function convertReturnName($dateNya = "")
	{
		$dt = explode("-", $dateNya);
		$tgl = explode(" ", $dt[2]);
		$tgl = $tgl[0];
		$bln = $dt[1];
		$thn = $dt[0];
		if($bln == "01" || $bln == "1"){ $bln = "Jan"; }
		else if($bln == "02" || $bln == "2"){ $bln = "Feb"; }
		else if($bln == "03" || $bln == "3"){ $bln = "Mar"; }
		else if($bln == "04" || $bln == "4"){ $bln = "Apr"; }
		else if($bln == "05" || $bln == "5"){ $bln = "Mei"; }
		else if($bln == "06" || $bln == "6"){ $bln = "Jun"; }
		else if($bln == "07" || $bln == "7"){ $bln = "Jul"; }
		else if($bln == "08" || $bln == "8"){ $bln = "Agt"; }
		else if($bln == "09" || $bln == "9"){ $bln = "Sep"; }
		else if($bln == "10"){ $bln = "Okt"; }
		else if($bln == "11"){ $bln = "Nov"; }
		else if($bln == "12"){ $bln = "Des"; }

		return $tgl." ".$bln." ".$thn;
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
			$this->session->set_userdata('nmDept', $cekLogin[0]->nmdept);
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
		$this->session->unset_userdata('hrAdm');
		$this->session->unset_userdata('jnsKelamin');
		$this->session->unset_userdata('kdDivMyApps');
		redirect(base_url("myapps"));
	}

	
}