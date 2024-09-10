<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Front extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();		
		$this->load->model('observasi');
		$this->load->helper(array('form', 'url'));
	}
	function index()
	{
		$this->load->view('front/login');
		// $this->load->view('front/menu');
	}
	function searchData()
	{
		$searchWhere = "";
		$sDate = $_POST['sDateSearch'];
		$eDate = $_POST['eDateSearch'];
		$sVessel = $_POST["slcVesselSearch"];
		$slcJnsObs = $_POST["slcJnsObs"];
		if ($sDate != "" & $eDate != "")
		{
			$searchWhere .= "a.tgl_observasi >='".$sDate."' AND a.tgl_observasi <= '".$eDate."' "; 
		}else{
			$searchWhere .= "a.tgl_observasi >='".date("Y-m")."-01' AND a.tgl_observasi <= '".date("Y-m")."-31' ";	
		}
		if ($sVessel != "")
		{
			//if ($sDate == "" & $eDate == "")
			// if($searchWhere == "")
			// {
			// 	$searchWhere .= "a.id_kapal = '".$sVessel."'";
			// }else{
			// 	$searchWhere .= " AND a.id_kapal = '".$sVessel."'";
			// }
			$searchWhere .= " AND a.id_kapal = '".$sVessel."'";
		}
		if($slcJnsObs != "")
		{
			// if($searchWhere == "")
			// {
			// 	$searchWhere .= "a.jns_observasi = '".$slcJnsObs."'";
			// }else{
			// 	$searchWhere .= " AND a.jns_observasi = '".$slcJnsObs."'";
			// }
			$searchWhere .= " AND a.jns_observasi = '".$slcJnsObs."'";
		}
		$this->observasi($searchWhere,"search");
	}
	public function observasi($searchWhere = "",$typeData = "")
	{
		if(!$this->session->userdata('userId')) { $this->logout(); }
		
		$dataTR = "";
		$cekEmail = "";
		$userId = $this->session->userdata('userId');
		$userType = $this->session->userdata('userType');
		$cekEmail = $this->observasi->getDataAll("login","id = '".$userId."'","email");
		$cekEmailNya = $cekEmail[0]->email;
		if($cekEmailNya != "")
		{
			if ($typeData == "")
			{
				if($userType == "admin")
				{
					$dataOut["jabatan"] = $this->getJabatan();
				}else{
					$idJabatan = $this->session->userdata('idJabatan');
					$dataOut["jabatan"] = $this->getJabatan($idJabatan);
				}
				$dataOut["vessel"] = $this->getVessel();
				$dataOut["pelindungDiri"] = $this->getDataCheckBox("","pelindung_diri");
				$dataOut["alatKerja"] = $this->getDataCheckBox("","alat_kerja");
				$dataOut["lingkunganKerja"] = $this->getDataCheckBox("","lingkungan_kerja");
				$dataOut["posisiPekerja"] = $this->getDataCheckBox("","posisi_kerja");
				$dataOut["ergonomik"] = $this->getDataCheckBox("","ergonomik");
				$dataOut["sistemKerja"] = $this->getDataCheckBox("","sistem_kerja");
			}

			$data = $this->observasi->getDataObservasi($searchWhere,$userId,$userType);

			$no = 1;
			foreach ($data as $key => $value) 
			{
				$dataTR .= "<tr>";
					$dataTR .= "<td align=\"center\">".$no."</td>";
					$dataTR .= "<td align=\"center\">";
						$dataTR .= "<a target=\"_blank\" class=\"btn btn-link btn-sm\" title=\"View Detail\" href=\"detailData/".base64_encode($value->id)."\">".$this->getMonth($value->tgl_observasi)."</a>";
					$dataTR .= "</td>";
					$dataTR .= "<td>".ucwords($value->jns_observasi)."</td>";
					$dataTR .= "<td>".$value->namaKapal."</td>";
					$dataTR .= "<td>".$value->catatan_detail."</td>";
					$dataTR .= "<td>".$value->nama_pengamat."</td>";
					$dataTR .= "<td>".$value->namaJabatan."</td>";
					$dataTR .= "<td align=\"center\">";
					if($userType == "admin" || $userId == $value->id_user)
					{
						$dataTR .= " <button type=\"button\" id=\"btnEdit\" class=\"btn btn-warning btn-xs\" onclick=\"getEdit('".$value->id."');\" >Edit</button>";
						$dataTR .= " <a class=\"btn btn-danger btn-xs\" onclick=\"return confirm('Are you sure want to delete..??')\" href=\"delObs/".$value->id."\">Delete</a>";
					}
					$dataTR .= "</td>";
				$dataTR .= "</tr>";
				$no ++;
			}
			$dataOut["dataObservasi"] = $dataTR;
			if ($typeData == "")//tampil saat awal
			{
				$this->load->view("front/observasi",$dataOut);
			}
			else
			{
				print json_encode($dataTR);
			}
		}else{
			$this->load->view("front/pageEmail",$userId);
		}
	}
	function saveObs()
	{
		$stData = "";
		$data = $_POST;
		$dateNow = date("m-d-Y H:i:s");
		$idEdit = $_POST['idEdit'];
		$userId = $this->session->userdata('userId');

		try {
			if ($idEdit == "")//jika kosong di insert
			{
				$dataInsObs['jns_observasi'] = $data['slcJnsObs'];
				$dataInsObs['nama_pengamat'] = $data['namaPengamat'];
				$dataInsObs['id_jabatan'] = $data['jabatan'];
				$dataInsObs['tgl_observasi'] = $data['dateObs'];
				$dataInsObs['id_kapal'] = $data['namaKapal'];
				$dataInsObs['detail_Lokasi_observasi'] = $data['detailLokObs'];
				$dataInsObs['catatan_detail'] = $data['catatanDetail'];
				$dataInsObs['dampak'] = $data['dampakTerjadi'];
				$dataInsObs['tindakan'] = $data['tindakan'];
				$dataInsObs['jns_observasi_lain'] = $data['lainNya'];
				$dataInsObs['id_user'] = $userId;
				$dataInsObs['add_date'] = $dateNow;

				$this->db->insert("observasi",$dataInsObs);
				$getIdNya = $this->db->insert_id();			
			}else{
				$dataUpdObs['jns_observasi'] = $data['slcJnsObs'];
				$dataUpdObs['nama_pengamat'] = $data['namaPengamat'];
				$dataUpdObs['id_jabatan'] = $data['jabatan'];
				$dataUpdObs['tgl_observasi'] = $data['dateObs'];
				$dataUpdObs['id_kapal'] = $data['namaKapal'];
				$dataUpdObs['detail_Lokasi_observasi'] = $data['detailLokObs'];
				$dataUpdObs['catatan_detail'] = $data['catatanDetail'];
				$dataUpdObs['dampak'] = $data['dampakTerjadi'];
				$dataUpdObs['tindakan'] = $data['tindakan'];
				$dataUpdObs['jns_observasi_lain'] = $data['lainNya'];
				$dataUpdObs['id_user'] = $userId;
				$dataUpdObs['edit_date'] = $dateNow;

				$whereNya = "id = '".$idEdit."'";
				$this->observasi->updateData($whereNya,$dataUpdObs,"observasi");
				$getIdNya = $idEdit;
			}
		
			if ($getIdNya != "")
			{
				if($idEdit != "")//jika proses update
				{
					$cekPelindung = $this->observasi->cekData("idObservasi = '".$getIdNya."'","pelindungandiri_temp");
					if ($cekPelindung > 0) 
					{
						$this->observasi->delDataCek("idObservasi = '".$getIdNya."'","pelindungandiri_temp");	
					}
					$cekAlatKerja = $this->observasi->cekData("idObservasi = '".$getIdNya."'","alatkerja_temp");
					if ($cekAlatKerja > 0) 
					{
						$this->observasi->delDataCek("idObservasi = '".$getIdNya."'","alatkerja_temp");	
					}
					$cekLingKerja = $this->observasi->cekData("idObservasi = '".$getIdNya."'","lingkungankerja_temp");
					if ($cekLingKerja > 0) 
					{
						$this->observasi->delDataCek("idObservasi = '".$getIdNya."'","lingkungankerja_temp");	
					}
					$cekPosisiKerja = $this->observasi->cekData("idObservasi = '".$getIdNya."'","posisikerja_temp");
					if ($cekPosisiKerja > 0) 
					{
						$this->observasi->delDataCek("idObservasi = '".$getIdNya."'","posisikerja_temp");	
					}
					$cekErgonomik = $this->observasi->cekData("idObservasi = '".$getIdNya."'","ergonomik_temp");
					if ($cekErgonomik > 0) 
					{
						$this->observasi->delDataCek("idObservasi = '".$getIdNya."'","ergonomik_temp");	
					}
					$cekSysKerja = $this->observasi->cekData("idObservasi = '".$getIdNya."'","sistemkerja_temp");
					if ($cekSysKerja > 0) 
					{
						$this->observasi->delDataCek("idObservasi = '".$getIdNya."'","sistemkerja_temp");	
					}
				}

				for ($la=0; $la < count($_POST['alatPelindungDiri']); $la++) 
				{
					$dataInsAltPel['idObservasi'] = $getIdNya;
					$dataInsAltPel['idPelindunganDiri'] = $_POST['alatPelindungDiri'][$la];
					$this->db->insert("pelindungandiri_temp",$dataInsAltPel);
				}
				for ($na=0; $na < count($_POST['alatKerja']); $na++) 
				{
					$dataInsAltKer['idObservasi'] = $getIdNya;
					$dataInsAltKer['idAlatKerja'] = $_POST['alatKerja'][$na];
					$this->db->insert("alatkerja_temp",$dataInsAltKer);
				}
				for ($ha=0; $ha < count($_POST['lingKerja']); $ha++) 
				{
					$dataInsLingKer['idObservasi'] = $getIdNya;
					$dataInsLingKer['idLingkunganKerja'] = $_POST['lingKerja'][$ha];
					$this->db->insert("lingkungankerja_temp",$dataInsLingKer);
				}
				for ($li=0; $li < count($_POST['posisiKerja']); $li++) 
				{
					$dataInsPosKer['idObservasi'] = $getIdNya;
					$dataInsPosKer['idPosisiKerja'] = $_POST['posisiKerja'][$li];
					$this->db->insert("posisikerja_temp",$dataInsPosKer);
				}
				for ($mun=0; $mun < count($_POST['ergonomik']); $mun++) 
				{
					$dataInsErgo['idObservasi'] = $getIdNya;
					$dataInsErgo['idErgonomik'] = $_POST['ergonomik'][$mun];
					$this->db->insert("ergonomik_temp",$dataInsErgo);
				}
				for ($ast=0; $ast < count($_POST['sysKerja']); $ast++) 
				{
					$dataInsSysKer['idObservasi'] = $getIdNya;
					$dataInsSysKer['idSistemKerja'] = $_POST['sysKerja'][$ast];
					$this->db->insert("sistemkerja_temp",$dataInsSysKer);
				}
			}

			$stData = "sukses";
		} catch (Exception $e) {
			$stData = "Gagal.. =>".$e;
		}
		print json_encode($stData);
	}
	function getEdit()
	{
		$idEdit = $_POST['id'];

		$dataOut['dataObs'] = $this->observasi->getDataEdit("observasi","id = '".$idEdit."'");
		$dataOut['dataPelDiri'] = $this->observasi->getDataEdit("pelindungandiri_temp","idObservasi = '".$idEdit."'");
		$dataOut['alatKerja'] = $this->observasi->getDataEdit("alatkerja_temp","idObservasi = '".$idEdit."'");
		$dataOut['lingkunganKerja'] = $this->observasi->getDataEdit("lingkungankerja_temp","idObservasi = '".$idEdit."'");
		$dataOut['posisiPekerja'] = $this->observasi->getDataEdit("posisikerja_temp","idObservasi = '".$idEdit."'");
		$dataOut['ergonomik'] = $this->observasi->getDataEdit("ergonomik_temp","idObservasi = '".$idEdit."'");
		$dataOut['sistemKerja'] = $this->observasi->getDataEdit("sistemkerja_temp","idObservasi = '".$idEdit."'");

		
		print json_encode($dataOut);
	}
	function getVessel($id = "")
	{
		$dataOutVessel = "";
		$whereNya = "";
		if($id != "")
		{
			$whereNya = "id = '".$id."'";
		}
		$dataVessel = $this->observasi->getDataAll("mst_vessel",$whereNya);
		foreach ($dataVessel as $key => $value) 
		{
			$dataOutVessel .= "<option value=\"".$value->id."\">".$value->name."</option>";
		}
		return $dataOutVessel;
	}
	function getJabatan($id = "")
	{
		$dataOut = "";
		$whereNya = "";
		if($id != "")
		{
			$whereNya = "id = '".$id."'";
		}
		$dataJabatan = $this->observasi->getDataJabatan("mst_jabatan",$whereNya);
		foreach ($dataJabatan as $key => $value) 
		{
			$dataOut .= "<option value=\"".$value->id."\">".$value->name."</option>";
		}
		return $dataOut;
	}
	function getDataCheckBox($id = "",$tbl = "")
	{
		$dataOutPelDi = "";
		$dataPelDi = $this->observasi->getDataAll($tbl);
		foreach ($dataPelDi as $key => $value)
		{
			$dataOutPelDi .=" <label class=\"form-check-label\">
								<input type=\"checkbox\" id=\"".$tbl."\" name=\"".$tbl."[]\" class=\"form-check-input\" value=\"".$value->id."\" onclick=\"disAbleCheckBox()\" > ".$value->keterangan."&nbsp
							</label>";
		}
		return $dataOutPelDi;
	}
	function delObs($id)
	{
		$data['sts_delete'] = "1";
		$data['del_date'] = date("m-d-Y H:i:s");

		$whereNya = "id = '".$id."'";
		$this->observasi->updateData($whereNya,$data,"observasi");

		redirect(base_url('front/observasi'));
	}
	function exportData()
	{
		ob_start();
		$sDate = $_POST['idSdateSearch'];
		$eDate = $_POST['idEdateSearch'];
		$slcVessel = $_POST['idSlcVslSearch'];
		$slcKatObsSearch = $_POST['idSlcKatObsSearch'];
		$dataTR = "";
		$whereNya = "";
		$userId = $this->session->userdata('userId');
		$userType = $this->session->userdata('userType');

		if ($sDate != "" & $eDate != "")
		{
			$whereNya .= "a.tgl_observasi >='".$sDate."' AND a.tgl_observasi <= '".$eDate."' "; 
		}
		if ($slcVessel != "")
		{
			if ($sDate == "" & $eDate == "")
			{
				$whereNya .= "a.id_kapal = '".$slcVessel."'";
			}else{
				$whereNya .= " AND a.id_kapal = '".$slcVessel."'";
			}
			
		}
		if($slcKatObsSearch != "")
		{
			if($whereNya == "")
			{
				$whereNya .= "a.jns_observasi = '".$slcKatObsSearch."'";
			}else{
				$whereNya .= " AND a.jns_observasi = '".$slcKatObsSearch."'";
			}
		}
		$data = $this->observasi->getDataObservasi($whereNya,$userId,$userType,"exportObs");
		header("Content-Type: application/vnd.ms-excel");
		echo "<table width=\"100%\">";
			echo "<tr>
					<td colspan=\"5\" align=\"center\">
						<label style=\"font-size: 28pt;font-weight: bold;\">PT. ADNYANA</label>
					</td>
				</tr>";
			echo "<tr colspan=\"2\">
					<td colspan=\"5\" align=\"center\">
						<label> DATA OBSERVASI</label>
					</td>
				</tr>";
		echo "</table>";

		echo "<table border=\"1\">";
		echo "<tr style=\"background-color:#D2D2D2;\">";
			echo "<th align=\"center\" >No</th>";
			echo "<th align=\"center\" >Tgl Observasi</th>";
			echo "<th align=\"center\" >Kategori Observasi</th>";
			echo "<th align=\"center\" >Nama Kapal</th>";
			echo "<th align=\"center\" >Lokasi Observasi</th>";
			echo "<th align=\"center\" >Nama Pengamat</th>";
			echo "<th align=\"center\" >Jabatan</th>";
			echo "<th align=\"center\" >Catatan Detail</th>";
			echo "<th align=\"center\" >Dampak</th>";
			echo "<th align=\"center\" >Tindakan</th>";
			echo "<th align=\"center\" >Jenis Observasi</th>";
		echo "</tr>";

		$no = 1;
		foreach ($data as $key => $value) 
		{
			$jO = "";
			if($value->jns_observasi_lain == "")
			{
				$jO = $this->cekJenisObs($value->id);
			}else{
				$jO = $value->jns_observasi_lain;
			}
			echo "<tr>
					<td align=\"center\">".$no."</td>
					<td align=\"center\">".$value->tgl_observasi."</td>
					<td align=\"center\">".$value->jns_observasi."</td>
					<td>".$value->namaKapal."</td>
					<td>".$value->detail_Lokasi_observasi."</td>
					<td>".$value->nama_pengamat."</td>
					<td>".$value->namaJabatan."</td>
					<td>".$value->catatan_detail."</td>
					<td>".$value->dampak."</td>
					<td>".$value->tindakan."</td>
					<td>".$jO."</td>
				</tr>";
			$no ++;
		}
		
		echo "</table>";

		header("Content-disposition: attachment; filename=dataObservasi.xls");
		ob_end_flush();
	}
	function cekJenisObs($idObs = "")
	{
		$jensObs = "";
		$sql = "SELECT A.id,CASE WHEN Z.keterangan is not null THEN Z.keterangan WHEN  X.keterangan is not null THEN X.keterangan WHEN V.keterangan is not null THEN V.keterangan WHEN N.keterangan is not null THEN N.keterangan WHEN M.keterangan is not null THEN M.keterangan WHEN L.keterangan is not null THEN L.keterangan END AS jenObs
				FROM observasi A
				LEFT JOIN alatkerja_temp B ON B.idObservasi = A.id
				LEFT JOIN alat_kerja Z ON Z.id = B.idAlatKerja
				LEFT JOIN ergonomik_temp C ON C.idObservasi = A.id
				LEFT JOIN ergonomik X ON X.id = C.idErgonomik
				LEFT JOIN lingkungankerja_temp D ON D.idObservasi = A.id
				LEFT JOIN lingkungan_kerja V ON V.id = D.idLingkunganKerja
				LEFT JOIN pelindungandiri_temp E ON E.idObservasi = A.id
				LEFT JOIN pelindung_diri N ON N.id = E.idPelindunganDiri
				LEFT JOIN posisikerja_temp F ON F.idObservasi = A.id
				LEFT JOIN posisi_kerja M ON M.id = F.idPosisiKerja
				LEFT JOIN sistemkerja_temp G ON G.idObservasi = A.id
				LEFT JOIN sistem_kerja L ON L.id = G.idSistemKerja
				WHERE A.id = '".$idObs."' ";
		$rsl = $this->observasi->getDataQuery($sql);
		if(count($rsl) > 0)
		{
			$jensObs = $rsl[0]->jenObs;			
		}
		return $jensObs;
	}
	function detailData($id)
	{
		$userId = $this->session->userdata('userId');
		$userType = $this->session->userdata('userType');
		$idDetail = base64_decode($id);
		$searchWhere = "a.id = '".$idDetail."'";
		$dtPerDiri = "";
		$dtAlatKer = "";
		$dtLingKer = "";
		$dtPosKer = "";
		$dtErgonomik = "";
		$dtSisKer = "";

		$data = $this->observasi->getDataObservasi($searchWhere,$userId,$userType);
		$dataPelindungDiri = $this->observasi->getDataCheckedObservasi("pelindungandiri_temp a","pelindung_diri b","b.id = a.idPelindunganDiri","a.idObservasi = '".$idDetail."'");
		$dataAlatKerja = $this->observasi->getDataCheckedObservasi("alatkerja_temp a","alat_kerja b","b.id = a.idAlatKerja","a.idObservasi = '".$idDetail."'");
		$dataLingKerja = $this->observasi->getDataCheckedObservasi("lingkungankerja_temp a","lingkungan_kerja b","b.id = a.idLingkunganKerja","a.idObservasi = '".$idDetail."'");
		$dataPosisiKerja = $this->observasi->getDataCheckedObservasi("posisikerja_temp a","posisi_kerja b","b.id = a.idPosisiKerja","a.idObservasi = '".$idDetail."'");
		$dataErgonomik = $this->observasi->getDataCheckedObservasi("ergonomik_temp a","ergonomik b","b.id = a.idErgonomik","a.idObservasi = '".$idDetail."'");
		$dataSisKerja = $this->observasi->getDataCheckedObservasi("sistemkerja_temp a","sistem_kerja b","b.id = a.idSistemKerja","a.idObservasi = '".$idDetail."'");

		foreach ($dataPelindungDiri as $key => $value) 
		{
			if ($dtPerDiri == "")
			{
				$dtPerDiri = $value->keterangan;
			}else{
				$dtPerDiri .= ", ".$value->keterangan;
			}
		}
		foreach ($dataAlatKerja as $key => $value1) 
		{
			if ($dtAlatKer == "")
			{
				$dtAlatKer = $value1->keterangan;
			}else{
				$dtAlatKer .= ", ".$value1->keterangan;
			}
		}
		foreach ($dataLingKerja as $key => $value2) 
		{
			if ($dtLingKer == "")
			{
				$dtLingKer = $value2->keterangan;
			}else{
				$dtLingKer .= ", ".$value2->keterangan;
			}
		}
		foreach ($dataPosisiKerja as $key => $value3) 
		{
			if ($dtPosKer == "")
			{
				$dtPosKer = $value3->keterangan;
			}else{
				$dtPosKer .= ", ".$value3->keterangan;
			}
		}
		foreach ($dataErgonomik as $key => $value4) 
		{
			if ($dtErgonomik == "")
			{
				$dtErgonomik = $value4->keterangan;
			}else{
				$dtErgonomik .= ", ".$value4->keterangan;
			}
		}
		foreach ($dataSisKerja as $key => $value5) 
		{
			if ($dtSisKer == "")
			{
				$dtSisKer = $value5->keterangan;
			}else{
				$dtSisKer .= ", ".$value5->keterangan;
			}
		}

		$dataOut['tgl_observasi'] = $this->getMonth($data[0]->tgl_observasi);
		$dataOut['jns_observasi'] = $data[0]->jns_observasi;
		$dataOut['nama_pengamat'] = $data[0]->nama_pengamat;
		$dataOut['detail_Lokasi_observasi'] = $data[0]->detail_Lokasi_observasi;
		$dataOut['catatan_detail'] = $data[0]->catatan_detail;
		$dataOut['dampak'] = $data[0]->dampak;
		$dataOut['tindakan'] = $data[0]->tindakan;
		$dataOut['namaKapal'] = $data[0]->namaKapal;
		$dataOut['namaJabatan'] = $data[0]->namaJabatan;
		$dataOut['lainNya'] = $data[0]->jns_observasi_lain;
		$dataOut['dtPerDiri'] = $dtPerDiri;
		$dataOut['dtAlatKer'] = $dtAlatKer;
		$dataOut['dtLingKer'] = $dtLingKer;
		$dataOut['dtPosKer'] = $dtPosKer;
		$dataOut['dtErgonomik'] = $dtErgonomik;
		$dataOut['dtSisKer'] = $dtSisKer;

		// echo "<pre>";
		// print_r($dataOut);exit;
		$this->load->view("front/detailData",$dataOut);
	}

	function updateMailPass()
	{
		$stData = "";
		$data = $_POST;
		$userId = $this->session->userdata('userId');
		$dataUpd['email'] = $data['email'];
		$dataUpd['password'] = base64_encode($data['pass']);
		$whereNya = "id = '".$userId."'";

		try {
			$this->observasi->updateData($whereNya,$dataUpd,"login");
			$stData = "sukses";
		} catch (Exception $em) {
			$stData = "Gagal.. =>".$e;
		}
		print json_encode($stData);		
	}
	
	function login()
	{
		$dataOut = array();
		$data = $_POST;
		$user = $data['user'];
		// $pass = md5($data['pass']);
		$pass = base64_encode($data['pass']);
		$status = '';
		$whereNya = "username = '".$user."' AND password = '".$pass."' AND sts_delete  = '0'";

		$cekLogin = $this->observasi->getDataAll("login",$whereNya);

		if(count($cekLogin) > 0)
		{
			$this->session->set_userdata('userId',$cekLogin[0]->id);
			$this->session->set_userdata('fullName',$cekLogin[0]->full_name);
			$this->session->set_userdata('userType',$cekLogin[0]->user_type);
			$this->session->set_userdata('idVesselLogin',$cekLogin[0]->vessel);
			$this->session->set_userdata('stExport',$cekLogin[0]->export);
			$this->session->set_userdata('idJabatan',$cekLogin[0]->id_jabatan);
			$dataOut['status'] = true;
		}
		else
		{
			$cekUsr = $this->observasi->getDataAll("login","sts_delete  = '0' AND username = '".$user."'");
			if(count($cekUsr) > 0)
			{
				$dataOut['user'] = true;
			}else{
				$dataOut['user'] = false;
			}
			$dataOut['status'] = false;
		}
		// print_r($this->session->userdata('userId'));exit;
		print json_encode($dataOut);
	}
	function logout()
	{
		// $this->session->sess_destroy();	userId
		$this->session->unset_userdata('userId');
		$this->session->unset_userdata('fullName');
		$this->session->unset_userdata('userType');
		$this->session->unset_userdata('idVesselLogin');
		$this->session->unset_userdata('stExport');
		$this->session->unset_userdata('idJabatan');
		redirect(base_url());
	}

	function getMonth($dateNya = "")
	{
		$dateConvert = "";
		$tgl = "";
		$bulan = "";
		$thn = "";

		$tgl = substr($dateNya, 8,2);
		$bulan = substr($dateNya, 5,2);
		$thn = substr($dateNya, 0,4);

		Switch ($bulan)
		{
		    case "01" : $bulan="Januari";
		        Break;
		    case "02" : $bulan="Februari";
		        Break;
		    case "03" : $bulan="Maret";
		        Break;
		    case "04" : $bulan="April";
		        Break;
		    case "05" : $bulan="Mei";
		        Break;
		    case "06" : $bulan="Juni";
		        Break;
		    case "07" : $bulan="Juli";
		        Break;
		    case "08" : $bulan="Agustus";
		        Break;
		    case "09" : $bulan="September";
		        Break;
		    case "10" : $bulan="Oktober";
		        Break;
		    case "11" : $bulan="November";
		        Break;
		    case "12" : $bulan="Desember";
		        Break;
		}
		$dateConvert = $tgl." ".$bulan." ".$thn;

		return $dateConvert;
	}

}