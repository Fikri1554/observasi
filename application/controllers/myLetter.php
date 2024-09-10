<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MyLetter extends CI_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->model('myapp');
		$this->load->helper(array('form', 'url'));
	}

	function index()
	{
		$this->getData();
	}

	function getData($searchNya = "")
	{
		$dataOut = array();
		$nmDiv = $this->session->userdata('nmDiv');
		$trNya = "";
		$whereNya = " WHERE A.deletests = '0' ";
		$no = 1;
		$fullName = $this->session->userdata('fullNameMyApps');
		$userType = $this->session->userdata('userTypeMyApps');

		if($searchNya == "")
		{
			$monthNow = date("m");
			$yearNow = date("Y");

			$whereNya .= " AND MONTH(A.tglsurat) = '".$monthNow."' AND YEAR(A.tglsurat) = '".$yearNow."' ";
		}else{
			$sDate = $_POST['sDate'];
			$eDate = $_POST['eDate'];
			$cmpNya = $_POST['cmp'];

			if($cmpNya != "")
			{
				$whereNya .= " AND A.cmpcode = '".$cmpNya."' ";
			}

			if($sDate != "" AND $eDate != "")
			{
				$whereNya .= " AND A.tglsurat BETWEEN '".$sDate."' AND '".$eDate."' ";
			}
		}

		$sql = "SELECT A.batchno,A.tglsurat,A.nosurat,A.address,A.ket,A.createdby,A.copydoc,A.canceldoc,B.nmcmp 
				FROM tblempnosurat A
				LEFT JOIN tblMstCmpNSrt B ON B.cmpcode = A.cmpcode
				".$whereNya."
				ORDER BY A.tglsurat DESC,A.nosurat DESC";

		$data = $this->myapp->getDataQueryDB6($sql);

		foreach ($data as $key => $value)
		{
			$icnCopy = "";
			$icnBatal = "";

			if($value->copydoc == "1"){ $icnCopy = "<i class=\"fa fa-check-square-o\"></i>"; }
			if($value->canceldoc == "1"){ $icnBatal = "<i class=\"fa fa-check-square-o\"></i>"; }

			$linkAction = "";
			$linkSurat = $value->nosurat;
			if($userType == "admin" || trim($fullName) == trim($value->createdby) || strstr($nmDiv, 'HR & SUPPORT'))
			{
				$linkSurat = "<a style=\"cursor:pointer;\" onclick=\"viewQrCode('".$value->batchno."');\" title=\"View QR Code\">".$value->nosurat."</a>";
				$linkAction = "<button onclick=\"editData('".$value->batchno."');\" id=\"btnEdit_".$value->batchno."\" class=\"btn btn-danger btn-xs btn-block\" title=\"Edit\"><i class=\"fa fa-edit\"></i> Edit</button>";
			}

			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".$no."</td>";
				$trNya .= "<td>".$value->nmcmp."</td>";
				$trNya .= "<td align=\"center\">".$this->convertReturnName($value->tglsurat)."</td>";
				$trNya .= "<td>".$linkSurat."</td>";
				$trNya .= "<td>".$value->address."</td>";
				$trNya .= "<td>".$value->ket."</td>";
				$trNya .= "<td>".$value->createdby."</td>";
				$trNya .= "<td align=\"center\">".$icnCopy."</td>";
				$trNya .= "<td align=\"center\">".$icnBatal."</td>";
				$trNya .= "<td>".$linkAction."</td>";
			$trNya .= "</tr>";
			$no++;
		}
		
		$dataOut["getOptCompany"] = $this->getOptCompany();
		$dataOut["getOptSignOut"] = $this->getOptSignOut();
		$dataOut["trNya"] = $trNya;
		$dataOut["fullNameLogin"] = $fullName;
		if($searchNya == "")
		{
			$this->load->view('myApps/tableSurat',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}

	function editData()
	{
		$batchno = $_POST['batchNo'];
		$sql = "SELECT * FROM tblEmpNoSurat WHERE batchno = '".$batchno."' ";
		$data = $this->myapp->getDataQueryDB6($sql);		

		print json_encode($data);
	}

	function addUpdateData()
	{
		$data = $_POST;
		$dataIns = array();
		$status = "";
		$usrInit = $this->session->userdata('userInitial');
		$usrAddLogin = $usrInit."#".date("H:i")."#".date("d/m/Y");

		try {
				
				$dataIns["address"] = $data["alamat"];				
				$dataIns["ket"] = $data["keterangan"];
				$dataIns["createdby"] = $data["pembuatSurat"];

				if($data["copy"] == ""){ $dataIns["copydoc"] = "0"; } else { $dataIns["copydoc"] = "1"; }
				if($data["batal"] == ""){ $dataIns["canceldoc"] = "0"; } else { $dataIns["canceldoc"] = "1"; }

				if($data["batchNo"] == "")//save Data
				{
					$dataIns["tglsurat"] = $data["tgl"];
					$dataIns["cmpcode"] = $data["company"];
					$dataIns["issueddiv"] = $data["diKeluarkan"];
					$dataIns["signedby"] = $data["diTandaTangani"];
					//$dataIns["nosurat"] = $data["noSurat"];
					$dataIns["nosurat"] = $this->getNoSurat("return",$data["tgl"],$data["company"],$data["diKeluarkan"],$data["diTandaTangani"]);
					$dataIns["batchno"] = $this->getBatchNo();					
					$dataIns["addusrdt"] = $usrAddLogin;
					
					$this->myapp->insDataDb6($dataIns,"tblEmpNoSurat");
					$this->myapp->insDataDbDahlia($dataIns,"tblEmpNoSurat");
				}else{
					$dataIns["updusrdt"] = $usrAddLogin;

					$whereNya = "batchno = '".$data["batchNo"]."'";
					
					$this->myapp->updateDataDb6("tblEmpNoSurat",$dataIns,$whereNya);
					$this->myapp->updateDataDbDahlia("tblEmpNoSurat",$dataIns,$whereNya);
				}
				$status = "Submit Success..!!";
		} catch (Exception $ex) {
				$status = "Failed => ".$ex->getMessage();
		}
		
		print json_encode($status);
	}

	function viewQrCode()
	{
		$batchNo = $_POST['batchNo'];

		$dirFile = "./assets/imgQRCode/".base64_encode($batchNo).".jpg";

		if(file_exists($dirFile))
		{
			print json_encode(base_url('assets/imgQRCode/'.base64_encode($batchNo).".jpg"));
		}else{
			$this->createQRCode($batchNo);
			print json_encode(base_url('assets/imgQRCode/'.base64_encode($batchNo).".jpg"));
		}
	}

	function createQRCodeOri($batchNo = "")
	{
		$config = array();
		$this->load->library('ciqrcode');

		$config['cacheable']	= true;
		$config['cachedir']		= './assets/imgQRCode/';
		$config['errorlog']		= './assets/imgQRCode/';
		$config['imagedir']		= './assets/imgQRCode/';
		$config['quality']		= true;
		$config['size']			= '1024';
		$config['black']		= array(224,255,255);
		$config['white']		= array(0,0,128);//untuk ubah warna di libralies/qrcode/qrimage.php white default 0,0,0
		$this->ciqrcode->initialize($config);

		$imgName = base64_encode($batchNo).'.png';

		$params['data'] = "http://apps.andhika.com/observasi/myLetter/viewLetter/".base64_encode($batchNo); //data yang akan di jadikan QR CODE
		$params['level'] = 'H'; //H=High
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; //simpan image QR CODE ke folder assets/images/

		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
	}

	function createQRCode($batchNo = "")
	{
		$config = array();
		$this->load->library('ciqrcode');

		$config['cacheable']	= true;
		$config['cachedir']		= './assets/imgQRCode/';
		$config['errorlog']		= './assets/imgQRCode/';
		$config['imagedir']		= './assets/imgQRCode/';
		$config['quality']		= true;
		$config['size']			= '1024';
		$config['black']		= array(224,255,255);
		$config['white']		= array(0,0,128);//untuk ubah warna di libralies/qrcode/qrimage.php white default 0,0,0
		$this->ciqrcode->initialize($config);

		$imgName = base64_encode($batchNo).'.jpg';

		$params['data'] = "http://apps.andhika.com/observasi/myLetter/viewLetter/".base64_encode($batchNo); //data yang akan di jadikan QR CODE
		$params['level'] = 'H'; //H=High
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; //simpan image QR CODE ke folder assets/images/
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
	}

	function viewLetter($batchNo = "")
	{
		$dataOut = array();
		$batchNo = base64_decode($batchNo);

		$sql = "SELECT A.batchno,A.tglsurat,A.nosurat,A.address,A.ket,A.createdby,B.nmcmp 
				FROM tblempnosurat A
				LEFT JOIN tblMstCmpNSrt B ON B.cmpcode = A.cmpcode
				WHERE A.batchno = '".$batchNo."' ";
		$dataOut['data'] = $this->myapp->getDataQueryDB6($sql);
		$dataOut["tglSurat"] = $this->convertReturnName($dataOut['data'][0]->tglsurat);

		$this->load->view('myApps/tableViewSurat',$dataOut);
	}

	function getNoSurat($typeReturn = "",$tgl = "",$codeCmp = "",$codeKeluar = "",$codeTtd = "")
	{
		$noSurat = "1";		
		$dataOut = array();
		$cmpCode = "";
		$keluarCode = "";
		$ttdCode = "";

		if($typeReturn == "")
		{
			$data = $_POST;
			$dateNya = explode("-", $data['tgl']);
			$thnSurat = $dateNya[0];
			$blnSurat = $dateNya[1];

			$cmpCode = $data['codeCmp'];
			$keluarCode = $data['codeKeluar'];
			$ttdCode = $data['codeTtd'];
		}else{
			$dateNya = explode("-", $tgl);
			$thnSurat = $dateNya[0];
			$blnSurat = $dateNya[1];

			$cmpCode = $codeCmp;
			$keluarCode = $codeKeluar;
			$ttdCode = $codeTtd;
		}
		

		$sql = "SELECT * FROM tblEmpNoSurat
				WHERE cmpcode = '".$cmpCode."' AND YEAR(tglsurat) = '".$thnSurat."'
				ORDER BY nosurat DESC LIMIT 0,1";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		if(count($rsl) > 0)
		{
			$ns = explode("/", $rsl[0]->nosurat);
			$noSurat = $ns[0]+1;
			$noSurat = $this->createNo($noSurat,$cmpCode,$keluarCode,$ttdCode,$blnSurat,substr($thnSurat,2,4));
		}else{
			$noSurat = $this->createNo($noSurat,$cmpCode,$keluarCode,$ttdCode,$blnSurat,substr($thnSurat,2,4));
		}

		$dataOut["noSurat"] = $noSurat;

		if($typeReturn == "")
		{
			print json_encode($dataOut);
		}else{
			return $noSurat;
		}
	}

	function getBatchNo()
	{
		$batchNo = "1";
		$sql = " SELECT (batchno + 1) AS batchNo FROM tblempnosurat ORDER BY batchno DESC LIMIT 0,1 ";
		$data = $this->myapp->getDataQueryDB6($sql);

		if(count($data) > 0)
		{
			$batchNo = $data[0]->batchNo;
		}

		return $batchNo;
	}

	function createNo($noNya = "",$cdCmp = "",$cdKeluar = "",$cdTtd = "",$bln = "",$thn = "")
	{
		$dt = strlen($noNya);
		$outNo = "";
		if($dt == 1)
		{
			$outNo = "000".$noNya;
		}
		else if($dt == 2)
		{
			$outNo = "00".$noNya;
		}
		else if($dt == 3)
		{
			$outNo = "0".$noNya;
		}
		else{
			$outNo = $noNya;
		}		

		if($cdKeluar == $cdTtd)
		{
			$cdOutTtd = $cdKeluar;
		}else{
			$cdOutTtd = $cdKeluar."-".$cdTtd;
		}

		$outNo = $outNo."/".$cdCmp."/".$cdOutTtd."/".$bln.$thn;
		
		return $outNo;
	}

	function getOptSignOut()
	{
		$optNya = "";

		$sql = "SELECT nmdiv,divcode FROM tblMstDivNSrt WHERE deletests = '0' ORDER BY nmdiv ASC";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $value)
		{
			$optNya .= "<option value=\"".$value->divcode."\">".$value->nmdiv."</option>";
		}
		return $optNya;
	}

	function getOptCompany()
	{
		$optNya = "";

		$sql = "SELECT nmcmp,cmpcode FROM tblMstCmpNSrt WHERE deletests = '0' ORDER BY nmcmp ASC";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $value)
		{
			$optNya .= "<option value=\"".$value->cmpcode."\">".$value->nmcmp."</option>";
		}
		return $optNya;
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
		else if($bln == "08" || $bln == "8"){ $bln = "Agus"; }
		else if($bln == "09" || $bln == "9"){ $bln = "Sep"; }
		else if($bln == "10"){ $bln = "Okt"; }
		else if($bln == "11"){ $bln = "Nov"; }
		else if($bln == "12"){ $bln = "Des"; }

		return $tgl." ".$bln." ".$thn;
	}


}