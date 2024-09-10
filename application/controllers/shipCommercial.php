<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ShipCommercial extends CI_Controller {
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
	function getVoyageEstNew($var1 = "",$pagePar = "")
	{
		$dataOut = array();
		$yNow = date("Y");
		$trNya = "";
		$no = 1;		
		$whereNya = "deletests = '0'";

		$sql = "SELECT id,title,total_cargo,floating_crane_pmt,additif_pmt,other_pmt FROM commercial_voyage_est WHERE st_delete = '0'";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $val)
		{
			$ttlCargo = 0;
			$ttlFloating = 0;
			$ttlAddt = 0;
			$ttlOther = 0;
			$ttlCostPMT = 0;

			$ttlCargo = $val->total_cargo;
			$ttlFloating = $ttlCargo * $val->floating_crane_pmt;
			$ttlAddt = $ttlCargo * $val->additif_pmt;
			$ttlOther = $ttlCargo * $val->other_pmt;
			$ttlCostPMT = $ttlFloating + $ttlAddt + $ttlOther;

			$btnActionFreight = "<button id=\"btnFreight\" onclick=\"getDataFreight('".$val->id."');\" class=\"btn btn-warning btn-xs btn-block\" title=\"Freight Table\"><i class=\"glyphicon glyphicon-th\"></i> Freight</button>";
			$btnActionView = "<button id=\"btnEdit\" onclick=\"viewModalVoyageEstNew('".$val->id."');\" class=\"btn btn-primary btn-xs btn-block\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>";
			$btnActionEdit = "<button id=\"btnEdit\" onclick=\"editDataNew('".$val->id."');\" class=\"btn btn-success btn-xs btn-block\" title=\"Edit\"><i class=\"fa fa-edit\"></i> Edit</button>";
			$btnActionDel = "<button id=\"btnDel\" onclick=\"delDataNew('".$val->id."');\" class=\"btn btn-danger btn-xs btn-block\" title=\"Delete\"><i class=\"fa fa-ban\"></i> Delete</button>";

			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".$no."</td>";
				$trNya .= "<td>".$val->title."</td>";
				$trNya .= "<td align=\"center\" style=\"width:5%;\">".$btnActionView."</td>";
				$trNya .= "<td align=\"center\" style=\"width:5%;\">".$btnActionFreight."</td>";
				$trNya .= "<td align=\"center\" style=\"width:5%;\">".$btnActionEdit."</td>";
				$trNya .= "<td align=\"center\" style=\"width:5%;\">".$btnActionDel."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['optPeriode'] = $this->getOptPriceBunker();
		$dataOut['trNya'] = $trNya;
		
		$this->load->view('myApps/viewVoyageEst',$dataOut);
	}
	function getFreightVoyEst()
	{
		$idCommVoyEst = $_POST['id'];
		$dataOut = array();
		$trNya = "";
		$trAnalisNya = "";
		$no = 1;
		$noRow = 1;

		$sql = "SELECT * FROM commercial_report_voyage WHERE st_delete = '0' AND id_voyEst = '".$idCommVoyEst."' ";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" title=\"Edit Data\" onclick=\"editDataReportVoyEstEdit('".$val->id."');\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= "&nbsp<button class=\"btn btn-danger btn-xs\" title=\"Delete Data\" onclick=\"delDataReportVoyEst('".$val->id."','".$idCommVoyEst."');\"><i class=\"fa fa-ban\"></i></button";

			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".$no."</td>";
				$trNya .= "<td align=\"center\">".$btnAct."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->total_earning_idr,2)."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->total_earning_usd,2)."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->freightbase_idr_ton,2)."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->tce,2)."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->freightbase_usd_ton,2)."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->gross_profit)."</td>";
				$trNya .= "<td align=\"right\">".number_format($val->add_comm)."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$tblAnalis = $this->getTableAnalisName($idCommVoyEst);
		$dataEstVoy = $this->getTableCommVoyageEst($idCommVoyEst);

		if($tblAnalis['statusData'] == "new")
		{
			foreach ($tblAnalis['nameArr'] as $key => $val)
			{
				$trAnalisNya .= "<tr>";
					$trAnalisNya .= "<td style=\"font-size:10px;\" id=\"analisName_".$noRow."\">".$key."</td>";
					$trAnalisNya .= "<td style=\"font-size:10px;text-align:right;\" id=\"analisValue_".$noRow."\">".$val."</td>";
				$trAnalisNya .= "</tr>";

				$noRow++;
			}
		}else{
			foreach ($tblAnalis['nameArr'] as $key => $val)
			{
				$trAnalisNya .= "<tr>";
					$trAnalisNya .= "<td style=\"font-size:10px;\" id=\"analisName_".$noRow."\">".$key."</td>";
					$trAnalisNya .= "<td style=\"font-size:10px;text-align:right;\" id=\"analisValue_".$noRow."\">".$val."</td>";
				$trAnalisNya .= "</tr>";

				$noRow++;
			}
		}
		
		$dataOut['totalCargo'] = $dataEstVoy['totalCargo'];
		$dataOut['totalFX'] = $dataEstVoy['totalFX'];
		$dataOut['totalAddComm'] = $dataEstVoy['totalAddComm'];
		$dataOut['totalActRV'] = $dataEstVoy['totalActRV'];
		$dataOut['totalOptCost'] = $dataEstVoy['totalOptCost'];

		$dataOut['titleNya'] = $tblAnalis['title'];
		$dataOut['totalRow'] = $noRow;
		$dataOut['trAnalisNya'] = $trAnalisNya;
		$dataOut['trNya'] = $trNya;

		print json_encode($dataOut);
	}
	function getTableAnalisName($idCommVoyEst = '')
	{
		$dataOut = array();
		$nameArr = array();
		$datetimeNow = date("Y-m-d H:i");
		$dateNow = date("Y-m-d")." 00:00";
		$statusData = "new";

		$sqlCek = "SELECT id,name,name_value FROM commercial_analisa_voyage Where st_delete = '0' AND id_voyEst = '".$idCommVoyEst."' ORDER BY id ASC ";
		$rslCek = $this->myapp->getDataQueryDB6($sqlCek);

		$sql = "SELECT * FROM commercial_voyage_est WHERE st_delete = '0' AND id = '".$idCommVoyEst."' ";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		$dataOut['title'] = $rsl[0]->title;
		
		if(count($rslCek) > 0)
		{
			$ttlNya = 0;
			$demmurage = "";
			$statusData = "update";
			foreach ($rslCek as $key => $val)
			{
				$valName = $val->name_value;

				if($val->name == "TA Loading" OR $val->name == "Comm Loading" OR $val->name == "Compl Loading" OR $val->name == "TD Loading" OR $val->name == "TA Disch" OR $val->name == "Comm Disch" OR $val->name == "Compl Disch" OR $val->name == "TD Disch" OR $val->name == "TA Loading (Next Shipment)")
				{
					$valName = $this->convertReturnNameWithTime($val->name_value);
				}

				$nameArr[$val->name] = $valName;
			}
			$formHidden = "<input type=\"hidden\" id=\"txtIdEditTblAnalisVoy\" value=\"\">";
			$formHidden .= "<input type=\"hidden\" id=\"txtIdEstVoyage\" value=\"".$idCommVoyEst."\">";
			$formHidden .= "<input type=\"hidden\" id=\"txtTotalTempAnalis\" value=\"".$ttlNya."\">";
			$formHidden .= "<input type=\"hidden\" id=\"txtIdHiddenDemmurage\" value=\"".$demmurage."\">";
			$formHidden .= "<button id=\"btnSaveTblAnalis\" onclick=\"saveTableAnalisVoyage();\" class=\"btn btn-primary btn-xs btn-block\" title=\"Save Data\">UPDATE</button>";
			$formHidden .="<button id=\"btnCancelTblAnalis\" onclick=\"backPage();\" class=\"btn btn-danger btn-xs btn-block\" title=\"Cancel Data\">Cancel</button>";

		}else{
			$ttlNya = 0;			

			$cargoTotal = $rsl[0]->total_cargo;
			$speedLdn = $rsl[0]->sea_speed_laden;
			$speedBlst = $rsl[0]->sea_speed_ballast;
			$steamLdn = $rsl[0]->distance_laden / $speedLdn / 24;
			$steamBlst = $rsl[0]->distance_ballast / $speedBlst / 24;
			$lrNya = $rsl[0]->actual_load_rate;
			$drNya = $rsl[0]->actual_disch_rate;
			$oprLoadPort = $cargoTotal / $lrNya;
			$oprDischPort = $cargoTotal / $drNya;
			$ttLoadPort = $rsl[0]->actual_tt_lp;
			$ttDischPort = $rsl[0]->actual_tt_dp;
			$waitLp = $rsl[0]->waiting_lp;
			$waitDp = $rsl[0]->waiting_dp;
			$ttlPortWorkDur = $oprLoadPort + $oprDischPort;
			$ttlPortDuration = $oprLoadPort + $oprDischPort + $ttLoadPort + $ttDischPort + $waitLp + $waitDp;
			$ttlDuration = $ttlPortDuration + $steamLdn + $steamBlst;
			$foLdn = $rsl[0]->ifo_cons_seaLadden / 0.945 * 1000;
			$foBlst = $rsl[0]->ifo_cons_seaBallast / 0.945 * 1000;
			$doLdn = $rsl[0]->mgo_cons_seaLaden / 0.854 * 1000;
			$doBlst = $rsl[0]->mgo_cons_seaBallast / 0.854 * 1000;
			$foPortWork = $rsl[0]->ifo_cons_portWorking / 0.945 * 1000;
			$doPortWork = $rsl[0]->mgo_cons_portWorking / 0.854 * 1000;
			$foPortIdle = $rsl[0]->ifo_cons_portIdle / 0.945 * 1000;
			$doPortIdle = $rsl[0]->mgo_cons_portIdle / 0.854 * 1000;
			$foPrice = $rsl[0]->ifo_after_disc;
			$doPrice = $rsl[0]->mgo_after_disc;
			$ldnBunkerCost = ($steamLdn * $foLdn * $foPrice) + ($steamLdn * $doLdn * $doPrice);
			$blstBunkerCost = ($steamBlst * $foBlst * $foPrice) + ($steamBlst * $doBlst * $doPrice);
			$portWorkBunkerCost = ($ttlPortWorkDur * $foPortWork * $foPrice) + ($ttlPortWorkDur * $doPortWork * $doPrice);
			$portIdleBunkerCost = (($waitDp + $waitLp + $ttLoadPort + $ttDischPort) * $foPortIdle * $foPrice) + (($waitDp + $waitLp + $ttLoadPort + $ttDischPort) * $doPortIdle * $doPrice);
			$ttlBunkerCost = $ldnBunkerCost + $blstBunkerCost + $portWorkBunkerCost +$portIdleBunkerCost;
			$pdALP = $rsl[0]->pda_lp;
			$pdADP = $rsl[0]->pda_dp;
			$otherCostPMT = $rsl[0]->other_cost * $cargoTotal;
			$allowTimeLP = $rsl[0]->allow_lp_day;
			$actualTimeLP = $rsl[0]->actual_lp_day;
			$allowTimeDP = $rsl[0]->allow_dp_day;
			$actualTimeDP = $rsl[0]->actual_dp_day;
			$allowPortDay = $allowTimeLP + $allowTimeDP;
			$actualPortDay = $actualTimeLP + $actualTimeDP;

			$dp = $actualPortDay;
			$dm = $actualPortDay;

			if($actualPortDay < $allowPortDay)
			{
				$dp = $allowPortDay - $actualPortDay;
			}

			if($actualPortDay > $allowPortDay)
			{
				$dm = $actualPortDay - $allowPortDay;
			}

			$desPatch = $dp * $rsl[0]->despatch * $rsl[0]->fx;
			$demmurage = $dm * $rsl[0]->demmurage * $rsl[0]->fx;

			// $commLoadNya = $this->hitungTglAnalisVoyage("2024-08-28 13:57",4.42);
			$commLoadNya = $this->hitungTglAnalisVoyage($dateNow,$rsl[0]->waiting_lp);
			$compLoadNya = $this->hitungTglAnalisVoyage($commLoadNya,$oprLoadPort);
			$tdLoad = $this->hitungTglAnalisVoyage($compLoadNya,$steamLdn);
			$taDisch = $this->hitungTglAnalisVoyage($compLoadNya,$steamLdn);			
			$complDisch = $this->hitungTglAnalisVoyage($taDisch,$oprDischPort);
			$tdDisch = $this->hitungTglAnalisVoyage($complDisch,$waitDp);
			$newTaLoad = $this->hitungTglAnalisVoyage($tdDisch,$steamBlst);

			$ttlNya = $ttlBunkerCost + $pdALP + $pdADP + $otherCostPMT + $desPatch;

			$formHidden = "<input type=\"hidden\" id=\"txtIdEditTblAnalisVoy\" value=\"\">";
			$formHidden .= "<input type=\"hidden\" id=\"txtIdEstVoyage\" value=\"".$idCommVoyEst."\">";
			$formHidden .= "<input type=\"hidden\" id=\"txtTotalTempAnalis\" value=\"".$ttlNya."\">";
			$formHidden .= "<input type=\"hidden\" id=\"txtIdHiddenDemmurage\" value=\"".$demmurage."\">";

			$nameArr = array("LOADING PORT"=>$rsl[0]->load_port,
							"DISCHARGING PORT"=>$rsl[0]->discharge_port,
							"DISTANCE LADEN"=>$rsl[0]->distance_laden,
							"DISTANCE BALLAST TO LOAD PORT"=>$rsl[0]->distance_ballast,
							"TA Loading"=>$this->convertReturnNameWithTime($dateNow)."<input type=\"hidden\" id=\"txtTglHid_5\" value=\"".$dateNow."\">",
							"Comm Loading"=>$this->convertReturnNameWithTime($commLoadNya)."<input type=\"hidden\" id=\"txtTglHid_6\" value=\"".$commLoadNya."\">",
							"Compl Loading"=>$this->convertReturnNameWithTime($compLoadNya)."<input type=\"hidden\" id=\"txtTglHid_7\" value=\"".$compLoadNya."\">",
							"TD Loading"=>$this->convertReturnNameWithTime($compLoadNya)."<input type=\"hidden\" id=\"txtTglHid_8\" value=\"".$compLoadNya."\">",
							"TA Disch"=>$this->convertReturnNameWithTime($taDisch)."<input type=\"hidden\" id=\"txtTglHid_9\" value=\"".$taDisch."\">",
							"Comm Disch"=>$this->convertReturnNameWithTime($taDisch)."<input type=\"hidden\" id=\"txtTglHid_10\" value=\"".$taDisch."\">",
							"Compl Disch"=>$this->convertReturnNameWithTime($complDisch)."<input type=\"hidden\" id=\"txtTglHid_11\" value=\"".$complDisch."\">",
							"TD Disch"=>$this->convertReturnNameWithTime($tdDisch)."<input type=\"hidden\" id=\"txtTglHid_12\" value=\"".$tdDisch."\">",
							"TA Loading (Next Shipment)"=>$this->convertReturnNameWithTime($newTaLoad)."<input type=\"hidden\" id=\"txtTglHid_13\" value=\"".$newTaLoad."\">",
							"Speed Laden"=>number_format($speedLdn,2),
							"Speed Ballast"=>number_format($speedBlst,2),
							"Steam Laden"=>number_format($steamLdn,2),
							"Steam Ballast"=>number_format($steamBlst,2),
							"L/R"=>number_format($lrNya,2),
							"D/R"=>number_format($drNya,2),
							"Vol"=>number_format($cargoTotal,2),
							"Operation Load Port"=>number_format($oprLoadPort,2),
							"Operation Disch Port"=>number_format($oprDischPort,2),
							"TT Load Port"=>number_format($ttLoadPort,2),
							"TT Disch Port"=>number_format($ttDischPort,2),
							"Waiting Load Port"=>number_format($waitLp,2),
							"Waiting Disch Port"=>number_format($waitDp,2),
							"Total Port Working Duration"=>number_format($ttlPortWorkDur,2),
							"Total Port Duration"=>number_format($ttlPortDuration,2),
							"Total Duration"=>number_format($ttlDuration,2),
							"FO Laden"=>number_format($foLdn,2),
							"FO Ballast"=>number_format($foBlst,2),
							"DO Laden"=>number_format($doLdn,2),
							"DO Ballast"=>number_format($doBlst,2),
							"FO Port Working"=>number_format($foPortWork,2),
							"DO Port Working"=>number_format($doPortWork,2),
							"FO Port Idle"=>number_format($foPortIdle,2),
							"DO Port Idle"=>number_format($doPortIdle,2),
							"FO Price"=>number_format($foPrice,2),
							"DO Price"=>number_format($doPrice,2),
							"Laden Bunker Cost"=>number_format($ldnBunkerCost,2), 
							"Ballast Bunker Cost"=>number_format($blstBunkerCost,2),
							"Port Working Bunker Cost"=>number_format($portWorkBunkerCost,2),
							"Port Idle Bunker Cost"=>number_format($portIdleBunkerCost,2),
							"Total Bunker Cost"=>number_format($ttlBunkerCost,2),
							"PDA L/P"=>number_format($pdALP,2),
							"PDA D/P"=>number_format($pdADP,2),
							"Ship Unloader"=>"<input type=\"text\" id=\"txtFormInput_47\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"Cleaning Holds"=>"<input type=\"text\" id=\"txtFormInput_48\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"Other Cost PMT/Voy in total"=>number_format($otherCostPMT,2),
							"Allowable Time L/P"=>number_format($allowTimeLP,2),
							"Actual Time L/P"=>number_format($actualTimeLP,2),
							"Allowable Time D/P"=>number_format($allowTimeDP,2),
							"Actual Time D/P"=>number_format($actualTimeDP,2),
							"Allowable Port Days"=>number_format($allowPortDay,2),
							"Actual Port Days"=>number_format($actualPortDay,2),
							"Despatch"=>number_format($desPatch,2),
							"Demmurage"=>number_format($demmurage,2),
							"FW in Port"=>"<input type=\"text\" id=\"txtFormInput_58\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('freshwater');\" style=\"text-align:right;\">",
							"FW at Sea"=>"<input type=\"text\" id=\"txtFormInput_59\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('freshwater');\" style=\"text-align:right;\">",
							"Total Fresh Water"=>"<label id=\"lblInput_60\"></label>",
							"Premi (Tug Assist)"=>"<input type=\"text\" id=\"txtFormInput_61\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"Other Cost"=>"<input type=\"text\" id=\"txtFormInput_62\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"Commission"=>"<input type=\"text\" id=\"txtFormInput_63\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"PPH"=>"<input type=\"text\" id=\"txtFormInput_64\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"Perizinan"=>"<input type=\"text\" id=\"txtFormInput_65\" class=\"form-control input-sm\" value=\"0\" oninput=\"hitungTblAnalisVoy('');\" style=\"text-align:right;\">",
							"Total Operating Cost"=>"<label id=\"lblInput_66\"></label>",
							$formHidden."<button id=\"btnSaveTblAnalis\" onclick=\"saveTableAnalisVoyage();\" class=\"btn btn-primary btn-xs btn-block\" title=\"Save Data\">Save</button>"=>"<button id=\"btnCancelTblAnalis\" onclick=\"backPage();\" class=\"btn btn-danger btn-xs btn-block\" title=\"Cancel Data\">Cancel</button>"
							);
		}

		$dataOut['statusData'] = $statusData;
		$dataOut['nameArr'] = $nameArr;

		return $dataOut;
	}
	function getTableCommVoyageEst($idCommVoyEst = '')
	{
		$dataOut = array();
		$totalCargo = 0;
		$totalFX = 0;
		$totalAddComm = 0;
		$totalActRV = 0;
		$totalOptCost = 0;

		$sqlAnalis = "SELECT * FROM commercial_analisa_voyage WHERE st_delete = '0' AND id_voyEst = '".$idCommVoyEst."' AND name = 'Total Operating Cost' ";
		$rslAnalis = $this->myapp->getDataQueryDB6($sqlAnalis);

		if(count($rslAnalis) > 0)
		{
			$totalOptCost = $rslAnalis[0]->name_value;

			if($totalOptCost != "")
			{
				$totalOptCost = str_replace(',','',$totalOptCost);
			}
		}

		$sql = "SELECT * FROM commercial_voyage_est WHERE st_delete = '0' AND id = '".$idCommVoyEst."' ";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		if(count($rsl) > 0)
		{
			$totalCargo = $rsl[0]->total_cargo;
			$totalFX =  $rsl[0]->fx;
			$totalAddComm =  $rsl[0]->addcomm_pmt;
			$totalActRV =  $rsl[0]->actual_rv_day;
		}

		$dataOut['totalCargo'] = $totalCargo;
		$dataOut['totalFX'] = $totalFX;
		$dataOut['totalAddComm'] = $totalAddComm;
		$dataOut['totalActRV'] = $totalActRV;
		$dataOut['totalOptCost'] = $totalOptCost;

		return $dataOut;
	}
	function getVoyageEst($var1 = "",$pagePar = "")
	{
		$dataOut = array();
		$yNow = date("Y");
		$trNya = "";
		$whereNya = "deletests = '0'";
		$no = 1;
		$pageNow = 1;
		$pageNya = "";
		$display = 10;
		$sLimit = 1;
		$eLimit = $display;

		if($var1 == "page")
		{
			$eLimit = $pagePar * $display;
			$sLimit = ($eLimit - $display) + 1;
			$pageNow = $pagePar;
		}

		$sql = " SELECT * FROM 
				 (
					SELECT row_number() over (order by addusrdt desc) as ttlRow,filenm,uniquekey,addusrdt
					FROM operasi..tblvoyest WHERE ".$whereNya."
				 ) AS rowNya
				 WHERE ttlRow >= ".$sLimit." AND ttlRow <= ".$eLimit." order by ttlRow ";

		if($var1 == "search")
		{
			$searchNya = $_POST['searchNya'];
			$whereNya .= " AND filenm like '%".$searchNya."%' ";
			$sql = " SELECT filenm,uniquekey,addusrdt FROM operasi..tblvoyest WHERE ".$whereNya." ORDER BY addusrdt DESC";
		}
		$sqlCount = "SELECT COUNT (*) AS total FROM operasi..tblvoyest WHERE ".$whereNya;
		$dataCount = $this->myapp->querySqlServer($sqlCount,"");

		$data = $this->myapp->querySqlServer($sql,"");
		if($var1 != "search")
		{
			$pageNya = $this->createPaging($dataCount[0]->total,$pageNow);
		}
		
		if(count($data) > 0)
		{
			foreach ($data as $key => $value)
			{
				if($var1 != "search") { $no = $value->ttlRow; }
				$trNya .= "
							<tr>
								<td align=\"center\">".$no."</td>
								<td><a style=\"cursor:pointer;\" onclick=\"editEstVoyage('".$value->uniquekey."','view');\">".$value->filenm."</a></td>
								<td align=\"center\">".$this->converDate($value->addusrdt)."</td>
								<td align=\"center\">
									<button id=\"btnEdit\" onclick=\"editEstVoyage('".$value->uniquekey."','edit');\" class=\"btn btn-info btn-xs\" name=\"btnEdit\" title=\"Edit\">
									<i class=\"fa fa-edit\"></i> Edit</button>
									<button type=\"button\" id=\"btnShowCost\" onclick=\"actShowCost('".$value->uniquekey."');\" class=\"btn btn-danger btn-xs\" title=\"Show Cost\"><i class=\"fa fa-check-square-o\"></i> Show Cost</button>
									<button type=\"button\" id=\"btnTceTable\" onclick=\"actTceTable('".$value->uniquekey."');\" class=\"btn btn-danger btn-xs\" title=\"TCE Table\"><i class=\"fa fa-folder-open-o\"></i> TCE Table</button>
									<button type=\"button\" id=\"btnFreTable\" onclick=\"actFreightTable('".$value->uniquekey."');\" class=\"btn btn-danger btn-xs\" title=\"Freight Table\"><i class=\"fa fa-exchange fa-sp\"></i> Freight Table</button>
									<button type=\"button\" id=\"btnFreRateDetail\" onclick=\"actRateDetail('".$value->uniquekey."');\" class=\"btn btn-danger btn-xs\" title=\"Freight Rate Detail\"><i class=\"fa fa-file-archive-o\"></i> F. Rate Detail</button>
									<button type=\"button\" id=\"btnSensAnalys\" onclick=\"actSensAnalys('".$value->uniquekey."');\" class=\"btn btn-danger btn-xs\" title=\"Sensitivity Analysis\"><i class=\"glyphicon glyphicon-th\"></i> Sens. Analysis</button>
								</td>
							</tr>
							";
				$no++;
			}
		}
		$dataOut['dataTr'] = $trNya;
		$dataOut['pageNya'] = $pageNya;
		if($var1 == "search")
		{
			if($trNya == ""){ $dataOut['dataTr'] = "<tr><td colspan=\"4\" align=\"center\">No Result..!!</td></tr>"; }
			print json_encode($dataOut);			
		}else{
			$this->load->view('myApps/voyageEst',$dataOut);
		}
	}
	function createPaging($ttlData = "",$pageNow = "")
	{
		$pageNya = "";
		$display = 10;
		$startPage = 1;		
		$lastPage = CEIL($ttlData/$display);
		$cP1 = "page-item";
		$cP2 = "page-item";
		$cP3 = "page-item";

		if($pageNow != "" AND $pageNow > 1)
		{
			$startPage = $pageNow-1;
			$cP2 = "page-item active";
		}
		else if($pageNow != "" AND $pageNow == 3 AND $lastPage == 3)
		{
			$cP3 = "page-item active";
		}
		else{
			$cP1 = "page-item active";
		}

		$startPage2 = $startPage +1;
		$startPage3 = $startPage +2;
		// print_r(base_url());exit;
		$pageNya .= "<ul class=\"pagination pagination-sm\" style=\"margin:0px;\">";
			$pageNya .= "<li class=\"page-item\">";
			$pageNya .= "<a class=\"page-link\" href=\"".base_url('ShipCommercial/getVoyageEst/page/1')."\" aria-label=\"Previous\">";
				$pageNya .= "<span aria-hidden=\"true\">&laquo;</span>";
			$pageNya .= "</a>";
			$pageNya .= "</li>";
			$pageNya .= "<li class=\"".$cP1."\"><a class=\"page-link\" href=\"".base_url('ShipCommercial/getVoyageEst/page/'.$startPage.'')."\">".$startPage."</a></li>";
			if($lastPage > 1)
			{
				$pageNya .= "<li class=\"".$cP2."\"><a class=\"page-link\" href=\"".base_url('ShipCommercial/getVoyageEst/page/'.$startPage2.'')."\">".$startPage2."</a></li>";
				if($pageNow < $lastPage)
				{
					$pageNya .= "<li class=\"".$cP3."\"><a class=\"page-link\" href=\"".base_url('ShipCommercial/getVoyageEst/page/'.$startPage3.'')."\">".$startPage3."</a></li>";
				}
				
				if($lastPage > ($pageNow+1))
				{
					$pageNya .= "<li class=\"page-item\"><a> ...</a></li>";
					$pageNya .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".base_url('ShipCommercial/getVoyageEst/page/'.$lastPage.'')."\">".$lastPage."</a></li>";
				}
			}
		$pageNya .= "</ul>";

		return $pageNya;
		// print_r(CEIL($ttlData/$display));
		
		// echo "<pre>";
		// // print_r($ttlData);
		// print_r($ttlData);exit;
	}
	function saveDataEstimate()
	{
		$data = $_POST;
		$dataIns = array();
		$userId = $this->session->userdata('userIdMyApps');
		$dateNow = date('Y-m-d');
		$status = "";

		$idEdit = $data['idEdit'];

		$dataIns['date_prepared'] = $data['txtDate_prepared'];
		$dataIns['title'] = $data['txtBunkerTitle'];
		$dataIns['cargo'] = $data['txtCargo'];
		$dataIns['vessel_type'] = $data['txtVslType'];
		$dataIns['total_cargo'] = $data['txtCargoShipment'];
		$dataIns['load_port'] = $data['txtLoadPort'];
		$dataIns['discharge_port'] = $data['txtDischPort'];
		$dataIns['allow_load_rate'] = $data['txtAllowLoad'];
		$dataIns['allow_disch_rate'] = $data['txtAllowDisch'];
		$dataIns['actual_load_rate'] = $data['txtActualLoad'];
		$dataIns['actual_disch_rate'] = $data['txtActualDisch'];
		$dataIns['allowable_tt_lp'] = $data['txtAllowTTLP'];
		$dataIns['allowable_tt_dp'] = $data['txtAllowTTDP'];
		$dataIns['actual_tt_lp'] = $data['txtActualTTLP'];
		$dataIns['actual_tt_dp'] = $data['txtActualTTDP'];
		$dataIns['waiting_lp'] = $data['txtWaitingLP'];
		$dataIns['waiting_dp'] = $data['txtWaitingDP'];
		$dataIns['demmurage_curr'] = $data['slcDemmurage'];
		$dataIns['demmurage'] = $data['txtDemmurage'];
		$dataIns['despatch_curr'] = $data['slcDespatch'];
		$dataIns['despatch'] = $data['txtDespatch'];
		$dataIns['distance_laden'] = $data['txtDistanceLaden'];
		$dataIns['distance_ballast'] = $data['txtDistanceBallast'];
		$dataIns['sea_speed_laden'] = $data['txtSeaSpeedLaden'];
		$dataIns['sea_speed_ballast'] = $data['txtSeaSpeedBallast'];
		$dataIns['sailing_laden'] = $data['txtSailingLaden'];
		$dataIns['sailing_ballast'] = $data['txtSailingBallast'];
		$dataIns['sailing_total'] = $data['txtTotalSailingDays'];
		$dataIns['allow_lp_day'] = $data['txtAllowLPDays'];
		$dataIns['allow_dp_day'] = $data['txtAllowDPDays'];
		$dataIns['allow_port_day'] = $data['txtAllowPortDays'];
		$dataIns['actual_lp_day'] = $data['txtActualLPDays'];
		$dataIns['actual_dp_day'] = $data['txtActualDPDays'];
		$dataIns['actual_port_day'] = $data['txtActualPortDays'];
		$dataIns['allow_rv_day'] = $data['txtAllowRVDays'];
		$dataIns['actual_rv_day'] = $data['txtActualRVDays'];
		$dataIns['banker_price_periodId'] = $data['slcBunkerPricePeriodId'];
		$dataIns['banker_price_period'] = $data['slcBunkerPricePeriodName'];
		$dataIns['ifo_price'] = $data['txtIFOprice'];
		$dataIns['mgo_price'] = $data['txtMGOPrice'];
		$dataIns['disc_on_ifo'] = $data['txtDiscOnIFO'];
		$dataIns['disc_on_mgo'] = $data['txtDiscOnMFO'];
		$dataIns['ifo_after_disc'] = $data['txtIFOPriceAfterDisc'];
		$dataIns['mgo_after_disc'] = $data['txtMGOPriceAfterDisc'];
		$dataIns['ifo_cons_seaLadden'] = $data['txtIFOConsSeaLdn'];
		$dataIns['ifo_cons_seaBallast'] = $data['txtIFOConsSeaBllst'];
		$dataIns['mgo_cons_seaLaden'] = $data['txtMGOConsSeaLdn'];
		$dataIns['mgo_cons_seaBallast'] = $data['txtMGOConsSeaBllst'];
		$dataIns['ifo_cons_portIdle'] = $data['txtIFOConsPortIdle'];
		$dataIns['ifo_cons_portWorking'] = $data['txtIFOConsPortWrkg'];
		$dataIns['mgo_cons_portIdle'] = $data['txtMGOConsPortIdle'];
		$dataIns['mgo_cons_portWorking'] = $data['txtMGOConsPortWrkg'];
		$dataIns['pda_lp_curr'] = $data['slcPDALP'];
		$dataIns['pda_lp'] = $data['txtPDALP'];
		$dataIns['pda_dp_curr'] = $data['slcPDADP'];
		$dataIns['pda_dp'] = $data['txtPDADP'];
		$dataIns['no_of_ship'] = $data['txtNumberShips'];
		$dataIns['max_cargo'] = $data['txtMaxCgoQty'];
		$dataIns['addcomm_pmt'] = $data['txtAddCommPMT'];
		$dataIns['other_cost_curr'] = $data['slcOtherCostPMT'];
		$dataIns['other_cost'] = $data['txtOtherCostPMT'];
		$dataIns['fx_curr'] = $data['slcFXRpUsd'];
		$dataIns['fx'] = $data['txtFXRpUsd'];
		$dataIns['floating_crane_pmt'] = $data['txtFloatingCrane'];
		$dataIns['additif_pmt'] = $data['txtAdditif'];
		$dataIns['other_pmt'] = $data['txtOther'];

		try {

			if($idEdit == "")// save data
			{
				$dataIns['add_userId'] = $userId;
				$dataIns['add_date'] = $dateNow;

				$this->myapp->insDataDb6($dataIns,"commercial_voyage_est");
			}else{
				$dataIns['edit_userId'] = $userId;
				$dataIns['edit_date'] = $dateNow;
				
				$whereNya = "id = '".$data['idEdit']."'";
				$this->myapp->updateDataDb6("commercial_voyage_est",$dataIns,$whereNya);
			}
			
			$status = "Success..!!";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessages();
		}

		print $status;
	}
	function addEstVoyage()
	{
		$data = $_POST;
		$stData = "";
		$dataIns = array();
		$usrInit = $this->session->userdata('userInitial');
		$dateNow = date("Ymd#h:i");
		$usrNow = $dateNow."#".$usrInit;

		$dataIns['vsl'] = $data['vslName'];
		$dataIns['vslcargo'] = $data['cargoType'];
		$dataIns['filenm'] = $data['fileName'];
		$dataIns['voy'] = $data['typeTrip'];
		$dataIns['bal'] = $data['txtTypeTrip'];
		$dataIns['lport'] = $data['lPort'];
		$dataIns['lportcost'] = $data['lPortCost'];
		$dataIns['lwtgday'] = $data['lPortWtgDay'];
		$dataIns['dport'] = $data['dPort'];
		$dataIns['dportcost'] = $data['dPortCost'];
		$dataIns['dwtgday'] = $data['dPortWtgDay'];
		$dataIns['dwt'] = $data['vslDwt'];
		$dataIns['drft'] = $data['vslDrft'];
		$dataIns['speedl'] = $data['speedL'];
		$dataIns['speedb'] = $data['speedB'];
		$dataIns['foldn'] = $data['foLdn'];
		$dataIns['foblst'] = $data['foBlst'];
		$dataIns['stemdo'] = $data['stemDo'];
		$dataIns['portdo'] = $data['portDo'];
		$dataIns['portfo'] = $data['portFo'];
		$dataIns['v1dist'] = $data['distLp'];
		$dataIns['v2dist'] = $data['distDp'];
		$dataIns['v1seamar'] = $data['seeMarginLp'];
		$dataIns['v2seamar'] = $data['seeMarginDp'];
		$dataIns['vpricefo'] = $data['priceFo'];
		$dataIns['vpricedo'] = $data['priceDo'];
		$dataIns['vetaets'] = $data['etaEts'];
		$dataIns['draftbss'] = $data['drftBss'];
		$dataIns['tpc'] = $data['tpc'];
		$dataIns['lrate'] = $data['loadingRate'];
		$dataIns['drate'] = $data['dischRate'];
		$dataIns['dmg'] = $data['demmurage'];
		$dataIns['dpch'] = $data['despatch'];
		$dataIns['ltt'] = $data['ttLp'];
		$dataIns['dtt'] = $data['ttDp'];
		$dataIns['term'] = $data['term'];
		$dataIns['comm'] = $data['lessComm'];
		$dataIns['frgtax'] = $data['fragTax'];
		$dataIns['handle'] = $data['handling'];
		$dataIns['ohpi'] = $data['ohPI'];
		$dataIns['carsize1'] = $data['cargoSize1'];
		$dataIns['carsize2'] = $data['cargoSize2'];
		$dataIns['carsize3'] = $data['cargoSize3'];
		$dataIns['carsize4'] = $data['cargoSize4'];
		$dataIns['carsize5'] = $data['cargoSize5'];
		$dataIns['carsize6'] = $data['cargoSize6'];
		$dataIns['carsize7'] = $data['cargoSize7'];
		$dataIns['tcefm'] = $data['tceFrom'];
		$dataIns['tceto'] = $data['tceTo'];
		$dataIns['ifcar'] = $data['ifCrgOnly'];
		$dataIns['currency'] = $data['curr'];		

		if($data['idEdit'] == "") // add Data
		{
			$dataIns['addusrdt'] = $usrNow;
			$this->myapp->insDataSqlServer("operasi..tblvoyest",$dataIns);
			$stData = "Success..!!";
		}else{
			$dataIns['updusrdt'] = $usrNow;
			$whereNya = "uniquekey = '".$data['idEdit']."'";
			$this->myapp->uptDataSqlServer("operasi..tblvoyest",$dataIns,$whereNya);
			$stData = "Success..!!";
		}
		print json_encode($stData);
	}
	function viewModalVoyageEstNew()
	{
		$dataOut = array();
		$id = $_POST['id'];

		$sql = "SELECT * FROM commercial_voyage_est WHERE st_delete = '0' AND id = '".$id."' ";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		$ttlCargo = $rsl[0]->total_cargo;
		$ttlFloating = $ttlCargo * $rsl[0]->floating_crane_pmt;
		$ttlAddt = $ttlCargo * $rsl[0]->additif_pmt;
		$ttlOther = $ttlCargo * $rsl[0]->other_pmt;

		$ttlCostPMT = $ttlFloating + $ttlAddt + $ttlOther;

		$dataOut['datePrepared'] = $this->convertReturnName($rsl[0]->date_prepared);
		$dataOut['ttlFloating'] = number_format($ttlFloating,0);
		$dataOut['ttlAddt'] = number_format($ttlAddt,0);
		$dataOut['ttlOther'] = number_format($ttlOther,0);
		$dataOut['ttlCostPMT'] = number_format($ttlCostPMT,0);

		$dataOut['data'] = $rsl;

		print json_encode($dataOut);
	}
	function getDataModalBunkerRegion($searchNya = '')
	{
		$dataOut = array();
		$trNya = "";
		$no = 1;

		$whereNya = " WHERE st_delete = '0' ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND periode LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM commercial_bunker ".$whereNya." ORDER BY periode_month ASC,periode ASC";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAction = "<button id=\"btnEdit\" onclick=\"editDataModalBunker('".$val->id."');\" class=\"btn btn-success btn-xs btn-block\" title=\"Edit\"><i class=\"fa fa-edit\"></i> Edit</button>";
			$btnAction .= "<button id=\"btnDel\" onclick=\"delDataModalBunker('".$val->id."');\" class=\"btn btn-danger btn-xs btn-block\" title=\"Delete\"><i class=\"fa fa-ban\"></i> Delete</button>";

			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".$no."</td>";
				$trNya .= "<td>".$val->periode."</td>";
				$trNya .= "<td align=\"center\">".strtoupper($val->curr_mfo)." ".number_format($val->price_mfo,2)."</td>";
				$trNya .= "<td align=\"center\">".strtoupper($val->curr_mgo)." ".number_format($val->price_mgo,2)."</td>";
				$trNya .= "<td>".$val->remark."</td>";
				$trNya .= "<td align=\"center\">".$btnAction."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		
		print json_encode($dataOut);
	}
	function saveDataModalBunker()
	{
		$data = $_POST;
		$dataIns = array();
		$userId = $this->session->userdata('userIdMyApps');
		$dateNow = date('Y-m-d');
		$status = "";

		$idEdit = $data['idEdit'];

		$dataIns['periode_month_name'] = $data['slcModalBunkerPeriodMonthName'];
		$dataIns['periode_month'] = $data['slcModalBunkerPeriodMonthId'];
		$dataIns['periode'] = $data['txtModalBunkerPeriod'];
		$dataIns['curr_mfo'] = $data['slModalBunkerCurrMFO'];
		$dataIns['price_mfo'] = $data['txtModalBunkerPriceMFO'];
		$dataIns['curr_mgo'] = $data['slcModalBunkerCurrMGO'];
		$dataIns['price_mgo'] = $data['txtModalBunkerPriceMGO'];
		$dataIns['remark'] = $data['txtModalBunkerRemark'];

		try {

			if($idEdit == "")// save data
			{
				$dataIns['add_userId'] = $userId;
				$dataIns['add_date'] = $dateNow;

				$this->myapp->insDataDb6($dataIns,"commercial_bunker");
			}else{
				$dataIns['edit_userId'] = $userId;
				$dataIns['edit_date'] = $dateNow;
				
				$whereNya = "id = '".$data['idEdit']."'";
				$this->myapp->updateDataDb6("commercial_bunker",$dataIns,$whereNya);
			}
			
			$status = "Success..!!";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessages();
		}

		print $status;
	}
	function saveTableAnalisVoyage()
	{
		$data = $_POST;
		$status = "";
		$idEstVoy = $data['idEstVoy'];
		$idEdit = $data['idEdit'];
		$userAddDate = $this->session->userdata('fullNameMyApps')."#".date("Y-m-d H:i");

		$tempData = explode("<=>", $data['tempVar']);

		try {
	
			for ($lan=0; $lan < (count($tempData)-1); $lan++)
			{
				$dataIns = array();

				$dataIns['id_voyEst'] = $idEstVoy;

				$dataVal = explode("*",$tempData[$lan]);

				$nameNya = $dataVal[0];
				$valNya = $dataVal[1];

				if($nameNya == "^") { $nameNya = ""; }
				if($valNya == "^") { $valNya = ""; }

				if($nameNya != "")
				{
					$dataIns['name'] = $nameNya;
					$dataIns['name_value'] = $valNya;

					if($idEdit == "")// save data
					{
						$dataIns['add_data'] = $userAddDate;

						$this->myapp->insDataDb6($dataIns,"commercial_analisa_voyage");
					}else{
						$dataIns['edit_data'] = $userAddDate;
						
						$whereNya = "id = '".$data['idEdit']."'";
						$this->myapp->updateDataDb6("commercial_analisa_voyage",$dataIns,$whereNya);
					}
				}			
			}
			$status = "success";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessages();
		}

		print_r($status);
	}
	function saveDataReportVoyEst()
	{
		$data = $_POST;
		$dataIns = array();
		$tempidEdit = array();
		$tempEarIdr = array();
		$tempEarUsd = array();
		$tempFBInput = array();
		$tempTCE = array();
		$tempFBUsd = array();
		$tempGrossProfit = array();
		$tempAddComm = array();
		$userAddDate = $this->session->userdata('fullNameMyApps')."#".date("Y-m-d H:i");
		$status = "";

		$idVoyEst = $data['idVoyEst'];

		$tempidEdit = explode("*", $data['idEdit']);
		$tempEarIdr = explode("*", $data['earIDR']);
		$tempEarUsd = explode("*", $data['earUSD']);
		$tempFBInput = explode("*", $data['freighBaseInput']);
		$tempTCE = explode("*", $data['tceUSD']);
		$tempFBUsd = explode("*", $data['freighBaseUSD']);
		$tempGrossProfit = explode("*", $data['grossProfit']);
		$tempAddComm = explode("*", $data['addComm']);

		try {

			for ($lan=0; $lan < count($tempidEdit); $lan++)
			{
				$dataIns['id_voyEst'] = $idVoyEst;
				$dataIns['total_earning_idr'] = str_replace(",","",$tempEarIdr[$lan]);
				$dataIns['total_earning_usd'] = str_replace(",","",$tempEarUsd[$lan]);
				$dataIns['freightbase_idr_ton'] = str_replace(",","",$tempFBInput[$lan]);
				$dataIns['freightbase_usd_ton'] = str_replace(",","",$tempFBUsd[$lan]);
				$dataIns['tce'] = str_replace(",","",$tempTCE[$lan]);
				$dataIns['gross_profit'] = str_replace(",","",$tempGrossProfit[$lan]);
				$dataIns['add_comm'] = str_replace(",","",$tempAddComm[$lan]);

				if($tempidEdit[$lan] == "^")// save data
				{
					$dataIns['add_data'] = $userAddDate;

					$this->myapp->insDataDb6($dataIns,"commercial_report_voyage");
				}else{
					$dataIns['edit_data'] = $userAddDate;
					
					$whereNya = "id = '".$tempidEdit[$lan]."'";
					$this->myapp->updateDataDb6("commercial_report_voyage",$dataIns,$whereNya);
				}
			}
			
			$status = "Success..!!";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessages();
		}

		print $status;
	}
	function getEdit()
	{
		$data = $_POST;
		$dataOut = array();

		if($data['action'] == "editEstVoyage")
		{
			$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['uniqueKey']."' ";
			$data = $this->myapp->querySqlServer($sql,"");
			$dataOut['data'] = $data;
		}
		else if($data['action'] == "editEstVoyNew")
		{
			$ttlCargo = 0;
			$ttlFloating = 0;
			$ttlAddt = 0;
			$ttlOther = 0;
			$ttlCostPMT = 0;

			$sql = "SELECT * FROM commercial_voyage_est WHERE id = '".$data['id']."' ";
			$data = $this->myapp->getDataQueryDB6($sql,"");

			if(count($data) > 0)
			{
				$ttlCargo = $data[0]->total_cargo;
				$ttlFloating = $ttlCargo * $data[0]->floating_crane_pmt;
				$ttlAddt = $ttlCargo * $data[0]->additif_pmt;
				$ttlOther = $ttlCargo * $data[0]->other_pmt;

				$ttlCostPMT = $ttlFloating + $ttlAddt + $ttlOther;
			}


			$dataOut['ttlFloating'] = number_format($ttlFloating,0);
			$dataOut['ttlAddt'] = number_format($ttlAddt,0);;
			$dataOut['ttlOther'] = number_format($ttlOther,0);
			$dataOut['ttlCostPMT'] = number_format($ttlCostPMT,0);
			$dataOut['data'] = $data;
		}
		else if($data['action'] == "editDataModalBunker")
		{
			$sql = "SELECT * FROM commercial_bunker WHERE id = '".$data['id']."' ";
			$data = $this->myapp->getDataQueryDB6($sql,"");

			$dataOut['data'] = $data;
		}
		else if($data['action'] == "editDataReportVoyEstEdit")
		{
			$sql = "SELECT * FROM commercial_report_voyage WHERE id = '".$data['id']."' ";
			$data = $this->myapp->getDataQueryDB6($sql,"");

			$dataOut['data'] = $data;
		}

		print json_encode($dataOut);
	}
	function delData()
	{
		$data = $_POST;
		$valData = array();
		$id = $data['id'];
		$tbl = "";
		$stData = "";

		try {
			$tbl = "commercial_voyage_est";
			$whereNya = "id = '".$id."'";
			$valData['st_delete'] = "1";

			$this->myapp->updateDataDb6($tbl,$valData,$whereNya);
			$stData = "Delete Success..!!";
		} catch (Exception $e) {
			$stData = "Failed =>".$e;
		}
		print json_encode($stData);
	}
	function delDataModalBunker()
	{
		$data = $_POST;
		$valData = array();
		$id = $data['id'];
		$tbl = "";
		$stData = "";

		try {
			$tbl = "commercial_bunker";
			$whereNya = "id = '".$id."'";
			$valData['st_delete'] = "1";

			$this->myapp->updateDataDb6($tbl,$valData,$whereNya);
			$stData = "Delete Success..!!";
		} catch (Exception $e) {
			$stData = "Failed =>".$e;
		}
		print json_encode($stData);
	}
	function delDataReportVoyEst()
	{
		$data = $_POST;
		$valData = array();
		$id = $data['id'];
		$tbl = "";
		$stData = "";

		try {
			$tbl = "commercial_report_voyage";
			$whereNya = "id = '".$id."'";
			$valData['st_delete'] = "1";

			$this->myapp->updateDataDb6($tbl,$valData,$whereNya);
			$stData = "Delete Success..!!";
		} catch (Exception $e) {
			$stData = "Failed =>".$e;
		}
		print json_encode($stData);
	}
	function getActShowCost()
	{
		$data = $_POST;
		$dataOut = array();

		$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['uniqueKey']."' ";
		$dataNya = $this->myapp->querySqlServer($sql,"");

		if(count($dataNya) > 0)
		{
			$carSize = $dataNya[0]->carsize1;
			$seaDay1 = ROUND((($dataNya[0]->v1seamar+100)/100) * (($dataNya[0]->v1dist/$dataNya[0]->speedl)/24),2);
			$seaDay2 = ROUND((($dataNya[0]->v2seamar+100)/100) * (($dataNya[0]->v2dist/$dataNya[0]->speedb)/24),2);
			if($dataNya[0]->voy == '1')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}
			else if($dataNya[0]->voy == '2')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foblst,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}else{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foblst,2);
			}
			$seaDo1 = ROUND($seaDay1 * $dataNya[0]->stemdo,2);
			$seaDo2 = ROUND($seaDay2 * $dataNya[0]->stemdo,2);

			$lCrgDay = ROUND(($carSize/$dataNya[0]->lrate) + $this->hitHari($dataNya[0]->ltt),2);
			$dCrgDay = ROUND(($carSize/$dataNya[0]->drate) + $this->hitHari($dataNya[0]->dtt),2);

			$lPortFo = ROUND($lCrgDay * $dataNya[0]->portfo,2);
			$dPortFo = ROUND($dCrgDay * $dataNya[0]->portfo,2);
			$lportDo = ROUND(($lCrgDay + $dataNya[0]->lwtgday) * $dataNya[0]->portdo,2);
			$dportDo = ROUND(($dCrgDay + $dataNya[0]->dwtgday) * $dataNya[0]->portdo,2);

			$stemFo = ROUND($seaFo1 + $seaFo2 + $lPortFo + $dPortFo,2);
			$stemDo = ROUND($seaDo1 + $seaDo2 + $lportDo + $dportDo,2);

			$ttlVoyCost = ROUND($dataNya[0]->lportcost + $dataNya[0]->dportcost + ($stemFo * $dataNya[0]->vpricefo) + ($stemDo * $dataNya[0]->vpricedo) + $dataNya[0]->handle + $dataNya[0]->ohpi,2);
			$ttlFoCost = ROUND($stemFo * $dataNya[0]->vpricefo,2);
			$ttlDoCost = ROUND($stemDo * $dataNya[0]->vpricedo,2);
			$ttlFODOCost = $ttlFoCost + $ttlDoCost;
			$ttlCostTon = ROUND($ttlVoyCost/$carSize,2);

			$dataOut['ttlVoyCost'] = number_format($ttlVoyCost,2);
			$dataOut['ttlFoCost'] = number_format($ttlFoCost,2);
			$dataOut['ttlDoCost'] = number_format($ttlDoCost,2);
			$dataOut['ttlFODOCost'] = number_format($ttlFODOCost,2);
			$dataOut['ttlCostTon'] = number_format($ttlCostTon,2);
		}		
		print json_encode($dataOut);
	}
	function getViewTceTable()
	{
		$data = $_POST;
		$dataOut = array();
		$dataDetail = array();
		$dataDetailHead = array();
		$frmNya = "";
		$toNya = "";
		$intvNya = "";
		$trNya = "";
		
		$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['txtIdUniqKey']."' ";
		$dataNya = $this->myapp->querySqlServer($sql,"");
		if(count($dataNya) > 0)
		{
			$frmNya = $data['txtIdFrom'];
			$toNya = $data['txtIdTo'];
			$intvNya = $data['slcIntrvlTceTbl'];
			
			$seaDay1 = ROUND((($dataNya[0]->v1seamar+100)/100) * ($dataNya[0]->v1dist/$dataNya[0]->speedl/24),2);
			$seaDay2 = ROUND((($dataNya[0]->v2seamar+100)/100) * ($dataNya[0]->v2dist/$dataNya[0]->speedb/24),2);
			if($dataNya[0]->voy == '1')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}
			else if($dataNya[0]->voy == '2')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foblst,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}else{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foblst,2);
			}
			$seaDo1 = ROUND($seaDay1 * $dataNya[0]->stemdo,2);
			$seaDo2 = ROUND($seaDay2 * $dataNya[0]->stemdo,2);
			$cekUrut = 1;
			for ($lan=1; $lan <= 7 ; $lan++)
			{
				$nmCSz = "carsize".$lan;
				$carSize = $dataNya[0]->$nmCSz;
				$lCrgDay = ROUND(($carSize/$dataNya[0]->lrate) + $this->hitHari($dataNya[0]->ltt),2);
				$dCrgDay = ROUND(($carSize/$dataNya[0]->drate) + $this->hitHari($dataNya[0]->dtt),2);
				$lPortFo = ROUND($lCrgDay * $dataNya[0]->portfo,2);
				$dPortFo = ROUND($dCrgDay * $dataNya[0]->portfo,2);
				$lportDo = ROUND(($lCrgDay + $dataNya[0]->lwtgday) * $dataNya[0]->portdo,2);
				$dportDo = ROUND(($dCrgDay + $dataNya[0]->dwtgday) * $dataNya[0]->portdo,2);
				$stemFo = ROUND($seaFo1 + $seaFo2 + $lPortFo + $dPortFo,2);
				$stemDo = ROUND($seaDo1 + $seaDo2 + $lportDo + $dportDo,2);
				$ttlVoyDur = ROUND($seaDay1 + $seaDay2 + $lCrgDay + $dCrgDay + $dataNya[0]->lwtgday+$dataNya[0]->dwtgday,2);
				
				$tempArr = array();
				$dataDetailHead['headName'][] = $carSize;
				for ($hal=$frmNya; $hal < $toNya+$intvNya ; $hal = $hal+$intvNya)
				{
					$grsRev = ROUND($hal * $carSize,2);
					$netRev = ROUND($grsRev * (1-$dataNya[0]->comm/100 - $dataNya[0]->frgtax/100),2);

					$ttlVoyCost = ROUND($dataNya[0]->lportcost + $dataNya[0]->dportcost + ($stemFo * $dataNya[0]->vpricefo) + ($stemDo * $dataNya[0]->vpricedo) + $dataNya[0]->handle + $dataNya[0]->ohpi,2);
					$tceVoy = ROUND($netRev - $ttlVoyCost,2);
					$tceNet = ROUND($tceVoy/$ttlVoyDur,2);
					$tce = ROUND($tceNet * (1 + ($dataNya[0]->comm / 100)),2);

					if($cekUrut == 1)
					{
						$dataDetailHead['urut'][] = $hal;
					}					
					$tempArr[] = $tce;
				}
				$dataDetail[] = $tempArr;
				$cekUrut++;
			}
			$nTr = 1;
			$trNya = "<tr>";
			foreach ($dataDetailHead['headName'] as $keyHead => $valHead)
			{
				if($nTr == 1)
				{
					$trNya .= " <td align=\"center\" style=\"height: 25px;\">Freight</td> 
								<td align=\"center\">".number_format($valHead,2)."</td>";
				}else{
					$trNya .= " <td align=\"center\">".number_format($valHead,2)."</td> ";
				}
				$nTr++;
			}
			$trNya .= "</tr>";
			for ($ast=0; $ast < count($dataDetailHead['urut']) ; $ast++)
			{
				$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".number_format($dataDetailHead['urut'][$ast],2)."</td>";
				for ($dev=0; $dev < count($dataDetailHead['headName']); $dev++)
				{
					$trNya .= "<td align=\"right\" style=\"padding-right:5px;\">".$dataDetail[$dev][$ast]."</td>";
				}
				$trNya .= "</tr>";
			}

			$dataOut['trNya'] = $trNya;
			$dataOut['vessel'] = $dataNya[0]->vsl;
			$dataOut['lPort'] = $dataNya[0]->lport;
			$dataOut['dPort'] = $dataNya[0]->dport;
			$dataOut['cargoType'] = $dataNya[0]->vslcargo;
			$dataOut['lRate'] = $dataNya[0]->lrate;
			$dataOut['dRate'] = $dataNya[0]->drate;
			$dataOut['lblYoyage'] = "TCE TABLE";
			$dataOut['fileName'] = "TCE Table";
		}
		$this->load->view('myApps/viewVoyage',$dataOut);
	}
	function getViewFreightTable()
	{
		$data = $_POST;
		$dataOut = array();
		$dataDetail = array();
		$dataDetailHead = array();
		$frmNya = "";
		$toNya = "";
		$intvNya = "";
		$trNya = "";
		
		$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['txtIdUniqKeyFreight']."' ";
		$dataNya = $this->myapp->querySqlServer($sql,"");
		
		if(count($dataNya) > 0)
		{
			$frmNya = $dataNya[0]->tcefm;
			$toNya = $dataNya[0]->tceto;
			$intvNya = 100;
			
			$seaDay1 = ROUND((($dataNya[0]->v1seamar+100)/100) * ($dataNya[0]->v1dist/$dataNya[0]->speedl/24),2);
			$seaDay2 = ROUND((($dataNya[0]->v2seamar+100)/100) * ($dataNya[0]->v2dist/$dataNya[0]->speedb/24),2);
			if($dataNya[0]->voy == '1')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}
			else if($dataNya[0]->voy == '2')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foblst,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}else{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foblst,2);
			}
			$seaDo1 = ROUND($seaDay1 * $dataNya[0]->stemdo,2);
			$seaDo2 = ROUND($seaDay2 * $dataNya[0]->stemdo,2);
			$cekUrut = 1;
			for ($lan=1; $lan <= 7 ; $lan++)
			{
				$nmCSz = "carsize".$lan;
				$carSize = $dataNya[0]->$nmCSz;
				$lCrgDay = ROUND(($carSize/$dataNya[0]->lrate) + $this->hitHari($dataNya[0]->ltt),2);
				$dCrgDay = ROUND(($carSize/$dataNya[0]->drate) + $this->hitHari($dataNya[0]->dtt),2);
				$lPortFo = ROUND($lCrgDay * $dataNya[0]->portfo,2);
				$dPortFo = ROUND($dCrgDay * $dataNya[0]->portfo,2);
				$lportDo = ROUND(($lCrgDay + $dataNya[0]->lwtgday) * $dataNya[0]->portdo,2);
				$dportDo = ROUND(($dCrgDay + $dataNya[0]->dwtgday) * $dataNya[0]->portdo,2);
				$stemFo = ROUND($seaFo1 + $seaFo2,2);
				$stemDo = ROUND($seaDo1 + $seaDo2,2);
				$ttlVoyDur = ROUND($seaDay1 + $seaDay2 + $lCrgDay + $dCrgDay + $dataNya[0]->lwtgday+$dataNya[0]->dwtgday,2);
				
				$tempArr = array();
				$dataDetailHead['headName'][] = $carSize;
				for ($hal=$frmNya; $hal < $toNya+$intvNya ; $hal = $hal+$intvNya)
				{
					$tce = $hal;
					$tceNet = ROUND($tce/(100+$dataNya[0]->comm)*100,2);
					$tceVoy = ROUND($tceNet*$ttlVoyDur,2);
					$netRev = ROUND($tceVoy+$dataNya[0]->lportcost+$dataNya[0]->dportcost+(($lPortFo+$dPortFo+$stemFo)* $dataNya[0]->vpricefo)+(($lportDo+$dportDo+$stemDo)* $dataNya[0]->vpricedo),2);
					$grsRev = ROUND($netRev/(100-$dataNya[0]->comm-$dataNya[0]->frgtax)*100,2);
					$frgRate = ROUND($grsRev/$carSize,2);
					if($cekUrut == 1)
					{
						$dataDetailHead['urut'][] = $hal;
					}					
					$tempArr[] = $frgRate;
				}
				$dataDetail[] = $tempArr;
				$cekUrut++;
			}
			$nTr = 1;
			$trNya = "<tr>";			
			foreach ($dataDetailHead['headName'] as $keyHead => $valHead)
			{
				if($nTr == 1)
				{
					$trNya .= " <td align=\"center\" style=\"height: 25px;\">Freight</td> 
								<td align=\"center\">".number_format($valHead,2)."</td>";
				}else{
					$trNya .= " <td align=\"center\">".number_format($valHead,2)."</td> ";
				}
				$nTr++;
			}
			$trNya .= "</tr>";
			for ($ast=0; $ast < count($dataDetailHead['urut']) ; $ast++)
			{
				$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".number_format($dataDetailHead['urut'][$ast],2)."</td>";
				for ($dev=0; $dev < count($dataDetailHead['headName']); $dev++)
				{
					$trNya .= "<td align=\"right\" style=\"padding-right:5px;\">".number_format($dataDetail[$dev][$ast],2)."</td>";
				}
				$trNya .= "</tr>";
			}

			$dataOut['trNya'] = $trNya;
			$dataOut['vessel'] = $dataNya[0]->vsl;
			$dataOut['lPort'] = $dataNya[0]->lport;
			$dataOut['dPort'] = $dataNya[0]->dport;
			$dataOut['cargoType'] = $dataNya[0]->vslcargo;
			$dataOut['lRate'] = $dataNya[0]->lrate;
			$dataOut['dRate'] = $dataNya[0]->drate;
			$dataOut['lblYoyage'] = "Freight TABLE";
			$dataOut['fileName'] = "Freight Table";
		}
		$this->load->view('myApps/viewVoyage',$dataOut);
	}
	function getViewFreightRateDetail__()
	{
		$data = $_POST;
		$dataOut = array();
		$dataDetail = array();
		$dataDetailHead = array();
		$cargoSize = "";
		$freightRate = "";
		$intvNya = "";
		$trNya = "";
		
		$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['txtIdUniqKeyRateDetail']."' ";
		$dataNya = $this->myapp->querySqlServer($sql,"");
		// echo "<pre>";print_r($dataNya);exit;
		if(count($dataNya) > 0)
		{
			$carSize = $data['txtCargoSize'];
			$freightRate = $data['txtFreightRate'];
			
			$seaDay1 = ROUND((($dataNya[0]->v1seamar+100)/100) * ($dataNya[0]->v1dist/$dataNya[0]->speedl/24),2);
			$seaDay2 = ROUND((($dataNya[0]->v2seamar+100)/100) * ($dataNya[0]->v2dist/$dataNya[0]->speedb/24),2);
			if($dataNya[0]->voy == '1')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}
			else if($dataNya[0]->voy == '2')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foblst,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}else{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foblst,2);
			}
			$seaDo1 = ROUND($seaDay1 * $dataNya[0]->stemdo,2);
			$seaDo2 = ROUND($seaDay2 * $dataNya[0]->stemdo,2);
			$cekUrut = 1;
			$lCrgDay = ROUND(($carSize/$dataNya[0]->lrate) + $this->hitHari($dataNya[0]->ltt),2);
			$dCrgDay = ROUND(($carSize/$dataNya[0]->drate) + $this->hitHari($dataNya[0]->dtt),2);
			$lPortFo = ROUND($lCrgDay * $dataNya[0]->portfo,2);
			$dPortFo = ROUND($dCrgDay * $dataNya[0]->portfo,2);
			$lportDo = ROUND(($lCrgDay + $dataNya[0]->lwtgday) * $dataNya[0]->portdo,2);
			$dportDo = ROUND(($dCrgDay + $dataNya[0]->dwtgday) * $dataNya[0]->portdo,2);
			$stemFo = ROUND($seaFo1 + $seaFo2 + $lPortFo + $dPortFo,2);
			$stemDo = ROUND($seaDo1 + $seaDo2 + $lportDo + $dportDo,2);
			$ttlVoyDur = ROUND($seaDay1 + $seaDay2 + $lCrgDay + $dCrgDay + $dataNya[0]->lwtgday+$dataNya[0]->dwtgday,2);
			
			$grsRev = ROUND($freightRate * $carSize,2);
			$netRev = ROUND($grsRev - ($grsRev*$dataNya[0]->comm/100) - ($grsRev*$dataNya[0]->frgtax/100),2);

			$ttlVoyCost = ROUND($dataNya[0]->lportcost + $dataNya[0]->dportcost + ROUND($stemFo * $dataNya[0]->vpricefo,2) + ROUND($stemDo * $dataNya[0]->vpricedo,2) + $dataNya[0]->handle + $dataNya[0]->ohpi,2);
			$tceVoy = ROUND($netRev - $ttlVoyCost,2);
			$tceNet = ROUND($tceVoy/$ttlVoyDur,2);
			$tceGros = ROUND($tceNet * (100 + $dataNya[0]->comm) / 100,2);
			
			$cekVar = ($dataNya[0]->ifcar/$carSize)*$ttlVoyDur;
			// echo "<pre>";print_r($tceVoy);exit;
			if($cekVar > 365)
			{
				$footerDay = $cekVar/365;
			}
			if($dataNya[0]->currency == '1'){ $typeKurs = "USD"; } else { $typeKurs = "IDR"; }
			$dataOut['carSize'] = number_format($this->cekDataNol($dataNya[0]->carsize1),2);
			$dataOut['lblYoyage'] = "VOYAGE ESTIMATE in ".$typeKurs;
			$dataOut['vessel'] = $dataNya[0]->vsl;
			$dataOut['lPort'] = $this->cekDataNol($dataNya[0]->lport);
			$dataOut['dPort'] = $this->cekDataNol($dataNya[0]->dport);
			$dataOut['cargoType'] = $this->cekDataNol($dataNya[0]->vslcargo);
			$dataOut['vslDwt'] = number_format($this->cekDataNol($dataNya[0]->dwt),2);
			$dataOut['vslDrft'] = number_format($this->cekDataNol($dataNya[0]->drft),2);
			$dataOut['tpc'] = number_format($this->cekDataNol($dataNya[0]->tpc),2);
			$dataOut['speedL'] = number_format($this->cekDataNol($dataNya[0]->speedl),2);
			$dataOut['speedB'] = number_format($this->cekDataNol($dataNya[0]->speedb),2);
			$dataOut['foLdn'] = number_format($this->cekDataNol($dataNya[0]->foldn),2);
			$dataOut['foBlst'] = number_format($this->cekDataNol($dataNya[0]->foblst),2);
			$dataOut['stemDo'] = number_format($this->cekDataNol($dataNya[0]->stemdo),2);
			$dataOut['portDo'] = number_format($this->cekDataNol($dataNya[0]->portdo),2);
			$dataOut['portFo'] = number_format($this->cekDataNol($dataNya[0]->portfo),2);

			if(trim($dataNya[0]->bal) != "")
			{
				$dataOut['portName'] = $dataNya[0]->bal."<br>";
			}
			$dataOut['portName'] .= $dataNya[0]->lport;

			$dataOut['portName2'] .= $dataNya[0]->dport."<br>".$dataNya[0]->lport;

			$dataOut['dist1'] = number_format($this->cekDataNol($dataNya[0]->v1dist),2);
			$dataOut['dist2'] = number_format($this->cekDataNol($dataNya[0]->v2dist),2);
			$dataOut['seaMar1'] = number_format($this->cekDataNol($dataNya[0]->v1seamar),2);
			$dataOut['seaMar2'] = number_format($this->cekDataNol($dataNya[0]->v2seamar),2);
			$dataOut['seaDay1'] = number_format($seaDay1,2);
			$dataOut['seaDay2'] = number_format($seaDay2,2);
			$dataOut['seaFo1'] = number_format($seaFo1,2);
			$dataOut['seaFo2'] = number_format($seaFo2,2);
			$dataOut['seaDo1'] = number_format($seaDo1,2);
			$dataOut['seaDo2'] = number_format($seaDo2,2);
			$dataOut['priceFo'] = number_format($this->cekDataNol($dataNya[0]->vpricefo),2);
			$dataOut['priceDo'] = number_format($this->cekDataNol($dataNya[0]->vpricedo),2);
			$dataOut['etaEts'] = number_format($this->cekDataNol($dataNya[0]->vetaets),2);
			$dataOut['portCost1'] = number_format($this->cekDataNol($dataNya[0]->lportcost),2);
			$dataOut['portCost2'] = number_format($this->cekDataNol($dataNya[0]->dportcost),2);
			$dataOut['draftBss'] = number_format($this->cekDataNol($dataNya[0]->draftbss),2);
			$dataOut['crgDay1'] = number_format($lCrgDay,2);
			$dataOut['crgDay2'] = number_format($dCrgDay,2);
			$dataOut['wtgDay1'] = number_format($this->cekDataNol($dataNya[0]->lwtgday),2);
			$dataOut['wtgDay2'] = number_format($this->cekDataNol($dataNya[0]->dwtgday),2);
			$dataOut['portFo1'] = number_format($lPortFo,2);
			$dataOut['portFo2'] = number_format($dPortFo,2);
			$dataOut['portDo1'] = number_format($lportDo,2);
			$dataOut['portDo2'] = number_format($dportDo,2);
			$dataOut['stemFo'] = number_format($stemFo,2);
			$dataOut['stemDo2'] = number_format($stemDo,2);
			$dataOut['loadingRate'] = number_format($this->cekDataNol($dataNya[0]->lrate),2);
			$dataOut['dischRate'] = number_format($this->cekDataNol($dataNya[0]->drate),2);
			$dataOut['dmg'] = number_format($this->cekDataNol($dataNya[0]->dmg),2);
			$dataOut['des'] = number_format($this->cekDataNol($dataNya[0]->dpch),2);
			$dataOut['ttlp'] = number_format($this->cekDataNol($dataNya[0]->ltt),2);
			$dataOut['ttdp'] = number_format($this->cekDataNol($dataNya[0]->dtt),2);
			$dataOut['terms'] = $dataNya[0]->term;
			$dataOut['frtRate'] = number_format($this->cekDataNol($freightRate),2);
			$dataOut['lessComm'] = number_format($this->cekDataNol($dataNya[0]->comm),2);


			$dataOut['fileName'] = "Freight Rate Detail";
		}
		$this->load->view('myApps/viewVoyFreightDetail',$dataOut);
	}
	function getViewFreightRateDetail()
	{
		$data = $_POST;
		$dataOut = array();
		$dataDetail = array();
		$dataDetailHead = array();
		$cargoSize = "";
		$freightRate = "";
		$intvNya = "";
		$trNya = "";

		$data['txtIdUniqKeyRateDetail'] = '579BDE0F-0AD7-4F85-BECB-5ACA95AF79CC';
		
		$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['txtIdUniqKeyRateDetail']."' ";
		$dataNya = $this->myapp->querySqlServer($sql,"");
		// echo "<pre>";print_r($dataNya);exit;
		if(count($dataNya) > 0)
		{
			$carSize = $data['txtCargoSize'];
			$freightRate = $data['txtFreightRate'];
			
			$seaDay1 = ROUND((($dataNya[0]->v1seamar+100)/100) * ($dataNya[0]->v1dist/$dataNya[0]->speedl/24),2);
			$seaDay2 = ROUND((($dataNya[0]->v2seamar+100)/100) * ($dataNya[0]->v2dist/$dataNya[0]->speedb/24),2);
			if($dataNya[0]->voy == '1')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}
			else if($dataNya[0]->voy == '2')
			{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foblst,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
			}else{
				$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
				$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foblst,2);
			}
			$seaDo1 = ROUND($seaDay1 * $dataNya[0]->stemdo,2);
			$seaDo2 = ROUND($seaDay2 * $dataNya[0]->stemdo,2);
			$cekUrut = 1;
			$lCrgDay = ROUND(($carSize/$dataNya[0]->lrate) + $this->hitHari($dataNya[0]->ltt),2);
			$dCrgDay = ROUND(($carSize/$dataNya[0]->drate) + $this->hitHari($dataNya[0]->dtt),2);
			$lPortFo = ROUND($lCrgDay * $dataNya[0]->portfo,2);
			$dPortFo = ROUND($dCrgDay * $dataNya[0]->portfo,2);
			$lportDo = ROUND(($lCrgDay + $dataNya[0]->lwtgday) * $dataNya[0]->portdo,2);
			$dportDo = ROUND(($dCrgDay + $dataNya[0]->dwtgday) * $dataNya[0]->portdo,2);
			$stemFo = ROUND($seaFo1 + $seaFo2 + $lPortFo + $dPortFo,2);
			$stemDo = ROUND($seaDo1 + $seaDo2 + $lportDo + $dportDo,2);
			$ttlVoyDur = ROUND($seaDay1 + $seaDay2 + $lCrgDay + $dCrgDay + $dataNya[0]->lwtgday+$dataNya[0]->dwtgday,2);
			
			$grsRev = ROUND($freightRate * $carSize,2);
			$netRev = ROUND($grsRev - ($grsRev*$dataNya[0]->comm/100) - ($grsRev*$dataNya[0]->frgtax/100),2);

			$ttlVoyCost = ROUND($dataNya[0]->lportcost + $dataNya[0]->dportcost + ROUND($stemFo * $dataNya[0]->vpricefo,2) + ROUND($stemDo * $dataNya[0]->vpricedo,2) + $dataNya[0]->handle + $dataNya[0]->ohpi,2);
			$tceVoy = ROUND($netRev - $ttlVoyCost,2);
			$tceNet = ROUND($tceVoy/$ttlVoyDur,2);
			$tceGros = ROUND($tceNet * (100 + $dataNya[0]->comm) / 100,2);
			
			$cekVar = ($dataNya[0]->ifcar/$carSize)*$ttlVoyDur;
			// echo "<pre>";print_r($tceVoy);exit;
			if($cekVar > 365)
			{
				$footerDay = $cekVar/365;
			}
			if($dataNya[0]->currency == '1'){ $typeKurs = "USD"; } else { $typeKurs = "IDR"; }
			$dataOut['carSize'] = number_format($this->cekDataNol($dataNya[0]->carsize1),2);
			$dataOut['lblYoyage'] = "VOYAGE ESTIMATE in ".$typeKurs;
			$dataOut['vessel'] = $dataNya[0]->vsl;
			$dataOut['lPort'] = $this->cekDataNol($dataNya[0]->lport);
			$dataOut['dPort'] = $this->cekDataNol($dataNya[0]->dport);
			$dataOut['cargoType'] = $this->cekDataNol($dataNya[0]->vslcargo);
			$dataOut['vslDwt'] = number_format($this->cekDataNol($dataNya[0]->dwt),2);
			$dataOut['vslDrft'] = number_format($this->cekDataNol($dataNya[0]->drft),2);
			$dataOut['tpc'] = number_format($this->cekDataNol($dataNya[0]->tpc),2);
			$dataOut['speedL'] = number_format($this->cekDataNol($dataNya[0]->speedl),2);
			$dataOut['speedB'] = number_format($this->cekDataNol($dataNya[0]->speedb),2);
			$dataOut['foLdn'] = number_format($this->cekDataNol($dataNya[0]->foldn),2);
			$dataOut['foBlst'] = number_format($this->cekDataNol($dataNya[0]->foblst),2);
			$dataOut['stemDo'] = number_format($this->cekDataNol($dataNya[0]->stemdo),2);
			$dataOut['portDo'] = number_format($this->cekDataNol($dataNya[0]->portdo),2);
			$dataOut['portFo'] = number_format($this->cekDataNol($dataNya[0]->portfo),2);

			if($dataNya[0]->voy == '2')
			{
				$dataOut['portName'] = $dataNya[0]->bal;
				$dataOut['portName2'] = $dataNya[0]->lport;
				$dataOut['portName3'] = $dataNya[0]->dport;
			}else{
				$dataOut['portName'] = $dataNya[0]->lport;
				$dataOut['portName2'] = $dataNya[0]->dport;
				$dataOut['portName3'] = $dataNya[0]->lport;
			}

			$dataOut['dist1'] = number_format($this->cekDataNol($dataNya[0]->v1dist),2);
			$dataOut['dist2'] = number_format($this->cekDataNol($dataNya[0]->v2dist),2);
			$dataOut['seaMar1'] = number_format($this->cekDataNol($dataNya[0]->v1seamar),2);
			$dataOut['seaMar2'] = number_format($this->cekDataNol($dataNya[0]->v2seamar),2);
			$dataOut['seaDay1'] = number_format($seaDay1,2);
			$dataOut['seaDay2'] = number_format($seaDay2,2);
			$dataOut['seaFo1'] = number_format($seaFo1,2);
			$dataOut['seaFo2'] = number_format($seaFo2,2);
			$dataOut['seaDo1'] = number_format($seaDo1,2);
			$dataOut['seaDo2'] = number_format($seaDo2,2);
			$dataOut['priceFo'] = number_format($this->cekDataNol($dataNya[0]->vpricefo),2);
			$dataOut['priceDo'] = number_format($this->cekDataNol($dataNya[0]->vpricedo),2);
			$dataOut['etaEts'] = number_format($this->cekDataNol($dataNya[0]->vetaets),2);
			$dataOut['portCost1'] = number_format($this->cekDataNol($dataNya[0]->lportcost),2);
			$dataOut['portCost2'] = number_format($this->cekDataNol($dataNya[0]->dportcost),2);
			$dataOut['draftBss'] = number_format($this->cekDataNol($dataNya[0]->draftbss),2);
			$dataOut['crgDay1'] = number_format($lCrgDay,2);
			$dataOut['crgDay2'] = number_format($dCrgDay,2);
			$dataOut['wtgDay1'] = number_format($this->cekDataNol($dataNya[0]->lwtgday),2);
			$dataOut['wtgDay2'] = number_format($this->cekDataNol($dataNya[0]->dwtgday),2);
			$dataOut['portFo1'] = number_format($lPortFo,2);
			$dataOut['portFo2'] = number_format($dPortFo,2);
			$dataOut['portDo1'] = number_format($lportDo,2);
			$dataOut['portDo2'] = number_format($dportDo,2);
			$dataOut['stemFo'] = number_format($stemFo,2);
			$dataOut['stemDo2'] = number_format($stemDo,2);
			$dataOut['loadingRate'] = number_format($this->cekDataNol($dataNya[0]->lrate),2);
			$dataOut['dischRate'] = number_format($this->cekDataNol($dataNya[0]->drate),2);
			$dataOut['dmg'] = number_format($this->cekDataNol($dataNya[0]->dmg),2);
			$dataOut['des'] = number_format($this->cekDataNol($dataNya[0]->dpch),2);
			$dataOut['ttlp'] = $dataNya[0]->ltt;
			$dataOut['ttdp'] = $dataNya[0]->dtt;
			$dataOut['terms'] = $dataNya[0]->term;
			$dataOut['frtRate'] = number_format($freightRate,2);
			$dataOut['lessComm'] = number_format($this->cekDataNol($dataNya[0]->comm),2);
			$dataOut['portCost'] = number_format(($this->cekDataNol($dataNya[0]->lportcost)+$this->cekDataNol($dataNya[0]->dportcost)),2);
			$dataOut['foCost'] = number_format(ROUND($stemFo * $this->cekDataNol($dataNya[0]->vpricefo),2),2);
			$dataOut['handling'] = number_format($this->cekDataNol($dataNya[0]->handle),2);
			$dataOut['doCost'] = number_format(ROUND($stemDo * $this->cekDataNol($dataNya[0]->vpricedo),2),2);
			$dataOut['ohPI'] = number_format($this->cekDataNol($dataNya[0]->ohpi),2);
			$dataOut['ttlFuelCost'] = number_format(ROUND($dataOut['foCost']+$dataOut['doCost'],2),2);
			$dataOut['ladenDay'] = ROUND($seaDay1,2);
			$dataOut['ladenDayPcnt'] = ROUND(($seaDay1/$ttlVoyDur)*100,2);
			$dataOut['ballastDay'] = ROUND($seaDay2,2);
			$dataOut['ballastDayPcnt'] = ROUND(($seaDay2/$ttlVoyDur)*100,2);
			$dataOut['portDays'] = ROUND($lCrgDay + $dCrgDay + $this->cekDataNol($dataNya[0]->lwtgday) + $this->cekDataNol($dataNya[0]->dwtgday),2);
			$dataOut['portDaysPcnt'] = 100 - ROUND(($seaDay1/$ttlVoyDur)*100,2) - ROUND(($seaDay2/$ttlVoyDur)*100,2);
			$dataOut['frtRev'] = number_format($grsRev,2);
			$dataOut['dayAllowdL'] = ROUND($lCrgDay + $this->cekDataNol($dataNya[0]->lwtgday),2);
			$dataOut['netRev'] = number_format($netRev,2);
			$dataOut['dayAllowdD'] = number_format(ROUND($dCrgDay + $this->cekDataNol($dataNya[0]->dwtgday),2),2);
			$dataOut['ttlVoyCost'] = number_format($ttlVoyCost,2);
			$dataOut['desPatch'] = number_format(ROUND($this->cekDataNol($dataNya[0]->dpch),2),2);
			$dataOut['tceVoy'] = number_format($tceVoy,2);
			$dataOut['comm'] = number_format(ROUND($grsRev * $this->cekDataNol($dataNya[0]->comm/100),2),2);
			$dataOut['foLoad'] = number_format($lPortFo,2);
			$dataOut['foDisch'] = number_format($dPortFo,2);
			$dataOut['frgTax'] = number_format(ROUND($grsRev * $this->cekDataNol($dataNya[0]->frgtax/100),2),2);
			$dataOut['doLoad'] = number_format($lportDo,2);
			$dataOut['doDisch'] = number_format($dportDo,2);
			$dataOut['costTon'] = number_format(ROUND($ttlVoyCost/$carSize,2),2);
			$dataOut['loadCost'] = number_format(ROUND($this->cekDataNol($dataNya[0]->lportcost),2),2);
			$dataOut['dischCost'] = number_format(ROUND($this->cekDataNol($dataNya[0]->dportcost),2),2);
			$dataOut['voyDur'] = number_format($ttlVoyDur,2);
			$dataOut['voySurp'] = number_format($tceNet,2);
			$dataOut['tceGrosUp'] = $this->cekDataNol($dataNya[0]->comm);
			$dataOut['tce'] = number_format($tceGros,2);
			$dataOut['ttlRound'] = ROUND(365/$ttlVoyDur);
			$dataOut['ttlCargo'] = number_format(ROUND(365/$ttlVoyDur) * $carSize,2);

			if($dataNya[0]->voy == '2')
			{
				$tr = "
						<tr>
							<td>".$dataOut['portName']."</td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
							<td align=\"center\">".$dataOut['stemFo']."</td>
							<td align=\"center\">".$dataOut['stemDo2']."</td>
						</tr>
						<tr>
							<td>".$dataOut['portName2']."</td>
							<td align=\"center\">".$dataOut['portCost1']."</td>
							<td align=\"center\">".$dataOut['draftBss']."</td>
							<td align=\"center\">".$dataOut['crgDay1']."</td>
							<td align=\"center\">".$dataOut['wtgDay1']."</td>
							<td align=\"center\">".$dataOut['portFo1']."</td>
							<td align=\"center\">".$dataOut['portDo1']."</td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
						</tr>
						<tr>
							<td>".$dataOut['portName3']."</td>
							<td align=\"center\">".$dataOut['portCost2']."</td>
							<td align=\"center\"></td>
							<td align=\"center\">".$dataOut['crgDay2']."</td>
							<td align=\"center\">".$dataOut['wtgDay2']."</td>
							<td align=\"center\">".$dataOut['portFo2']."</td>
							<td align=\"center\">".$dataOut['portDo2']."</td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
						</tr>
						";
			}else{
				$tr = "
						<tr>
							<td>".$dataOut['portName']."</td>
							<td align=\"center\">".$dataOut['portCost1']."</td>
							<td align=\"center\">".$dataOut['draftBss']."</td>
							<td align=\"center\">".$dataOut['crgDay1']."</td>
							<td align=\"center\">".$dataOut['wtgDay1']."</td>
							<td align=\"center\">".$dataOut['portFo1']."</td>
							<td align=\"center\">".$dataOut['portDo1']."</td>
							<td align=\"center\">".$dataOut['stemFo']."</td>
							<td align=\"center\">".$dataOut['stemDo2']."</td>
						</tr>
						<tr>
							<td>".$dataOut['portName2']."</td>
							<td align=\"center\">".$dataOut['portCost2']."</td>
							<td align=\"center\"></td>
							<td align=\"center\">".$dataOut['crgDay2']."</td>
							<td align=\"center\">".$dataOut['wtgDay2']."</td>
							<td align=\"center\">".$dataOut['portFo2']."</td>
							<td align=\"center\">".$dataOut['portDo2']."</td>
							<td align=\"center\"></td>
							<td align=\"center\"></td>
						</tr>
						<tr>
							<td colspan=\"8\">".$dataOut['portName']."</td>
						</tr>
						";
			}
			$dataOut['trNya'] = $tr;
			$dataOut['fileName'] = "Freight Rate Detail";
		}
		$this->load->view('myApps/viewVoyFreightDetail',$dataOut);
	}
	function getViewSensAnalys()
	{
		$data = $_POST;
		$dataOut = array();
		$tempArr = array();
		$trNya = "";

		$sql = "SELECT * FROM operasi..tblvoyest WHERE uniquekey = '".$data['txtIdUniqKeySensAnalys']."' ";
		$dataNya = $this->myapp->querySqlServer($sql,"");

		if(count($dataNya) > 0)
		{
			$carSize = $data['txtCargoSizeSensAnalys'];
			$frmNya = $data['txtIdFromSensAnalys'];
			$toNya = $data['txtIdToSensAnalys'];
			$intvNya = $data['slcIntrvlSensAnalys'];

			$dataOut['vessel'] = $dataNya[0]->vsl;
			$dataOut['lPort'] = $dataNya[0]->lport;
			$dataOut['dPort'] = $dataNya[0]->dport;
			$dataOut['cargoType'] = $dataNya[0]->vslcargo;
			$dataOut['lRate'] = $dataNya[0]->lrate;
			$dataOut['dRate'] = $dataNya[0]->drate;

			for ($hal=$frmNya; $hal < $toNya+$intvNya ; $hal = $hal+$intvNya)
			{
				$frmNya = $hal;				

				$tempArr['freight'][] = $hal;
				$tempArr['grossRev'][] = ROUND($hal * $carSize,2);
				$tempArr['voyCost'][] = ROUND($hal * $carSize,2);

				$tempArr1 = array();
				$tceEst = 0;
				for ($lan = 1; $lan <= 6; $lan++)
				{
					$seaDay1 = ROUND((($dataNya[0]->v1seamar+100)/100) * ($dataNya[0]->v1dist/$dataNya[0]->speedl/24),2);
					$seaDay2 = ROUND((($dataNya[0]->v2seamar+100)/100) * ($dataNya[0]->v2dist/$dataNya[0]->speedb/24),2);
					if($lan == 2)
					{
						$seaDay1 = $seaDay1 + 1;
					}

					if($dataNya[0]->voy == '1')
					{
						$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
						$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
					}
					else if($dataNya[0]->voy == '2')
					{
						$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foblst,2);
						$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foldn,2);
					}else{
						$seaFo1 = ROUND($seaDay1 * $dataNya[0]->foldn,2);
						$seaFo2 = ROUND($seaDay2 * $dataNya[0]->foblst,2);
					}
			
					$seaDo1 = ROUND($seaDay1 * $dataNya[0]->stemdo,2);
					$seaDo2 = ROUND($seaDay2 * $dataNya[0]->stemdo,2);
					$lCrgDay = ROUND($carSize/$dataNya[0]->lrate + $this->hitHari($dataNya[0]->ltt),2);
					$dCrgDay = ROUND($carSize/$dataNya[0]->drate + $this->hitHari($dataNya[0]->dtt),2);					

					if($lan == 3)
					{
						$lCrgDay = $lCrgDay + 1;
					}
					else if($lan == 4)
					{
						$lCrgDay = $lCrgDay - 1;
					}
					else if($lan == 5)
					{
						$dCrgDay = $dCrgDay + 1;
					}
					else if($lan == 6)
					{
						$dCrgDay = $dCrgDay - 1;
					}

					$lPortFo = ROUND($lCrgDay * $dataNya[0]->portfo,2);
					$dPortFo = ROUND($dCrgDay * $dataNya[0]->portfo,2);
					$lportDo = ROUND(($lCrgDay + $dataNya[0]->lwtgday) * $dataNya[0]->portdo,2);
					$dportDo = ROUND(($dCrgDay + $dataNya[0]->dwtgday) * $dataNya[0]->portdo,2);
					$stemFo = ROUND($seaFo1 + $seaFo2 + $lPortFo + $dPortFo,2);
					$stemDo = ROUND($seaDo1 + $seaDo2 + $lportDo + $dportDo,2);

					$voyDur = ROUND($seaDay1 + $seaDay2 + $lCrgDay + $dCrgDay + $dataNya[0]->lwtgday+$dataNya[0]->dwtgday,2);
					$grsRev = ROUND($hal * $carSize,2);
					$netRev = ROUND($grsRev * (1 - $dataNya[0]->comm / 100 - $dataNya[0]->frgtax/100),2);
					$tceVoy = $netRev - ($dataNya[0]->lportcost+$dataNya[0]->dportcost + ROUND($stemFo * $dataNya[0]->vpricefo,2) + ROUND($stemDo * $dataNya[0]->vpricedo,2) + $dataNya[0]->handle + $dataNya[0]->ohpi);
					$tceNet = ROUND($tceVoy/$voyDur,2);
					$tce = ROUND($tceNet * (1 + $dataNya[0]->comm/100),2);

					if($lan == 1)
					{
						$tempArr1['tce'.$lan] = $tce;
						$tceEst = $tce;
					}else{
						$tempArr1['tce'.$lan] = $tce - $tceEst;
					}
					
				}
				array_push($tempArr, $tempArr1);
			}

			if(count($tempArr) > 0)
			{
				$no = 1;
				for ($a=0; $a < count($tempArr['freight']); $a++)
				{
					$trNya .= "
								<tr>
									<td align=\"center\">".$no."</td>
									<td align=\"center\">".number_format($tempArr['freight'][$a],2)."</td>
									<td align=\"center\">".number_format($tempArr['grossRev'][$a],2)."</td>
									<td align=\"center\">".number_format($tempArr['grossRev'][$a],2)."</td>
									<td align=\"center\">".number_format($tempArr[$a]['tce1'],2)."</td>
									<td align=\"center\">".number_format($tempArr[$a]['tce2'],2)."</td>
									<td align=\"center\">".number_format($tempArr[$a]['tce3'],2)."</td>
									<td align=\"center\">".number_format($tempArr[$a]['tce4'],2)."</td>
									<td align=\"center\">".number_format($tempArr[$a]['tce5'],2)."</td>
									<td align=\"center\">".number_format($tempArr[$a]['tce6'],2)."</td>
								</tr>
							  ";
					$no++;
				}
			}

			$dataOut['trNya'] = $trNya;
			$dataOut['fileName'] = "Sensitivity Analysis";
		}
		$this->load->view('myApps/viewSensAnalys',$dataOut);
	}
	function getOptPriceBunker()
	{
		$opt = "";

		$sql = "SELECT * FROM commercial_bunker WHERE st_delete = '0' ORDER BY periode_month ASC,periode ASC";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		$opt .= "<option value=\"\">- Select -</option>";
		foreach ($rsl as $key => $val)
		{
			$opt .= "<option value=\"".$val->id."\">".$val->periode."</option>";
		}

		return $opt;
	}
	function getPriceBunkerById()
	{
		$dataOut = array();
		$idBunker = $_POST['id'];
		$mfo = 0;
		$mgo = 0;

		$sql = "SELECT * FROM commercial_bunker WHERE st_delete = '0' AND id = '".$idBunker."' ";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		if(count($rsl) > 0)
		{
			$mfo = $rsl[0]->price_mfo;
			$mgo = $rsl[0]->price_mgo;
		}

		$dataOut['mfo'] = $mfo;
		$dataOut['mgo'] = $mgo;

		print json_encode($dataOut);
	}
	function exportDataPDF($id = '')
	{
		$dataOut = array();

		$sql = "SELECT * FROM commercial_voyage_est WHERE st_delete = '0' AND id = '".$id."' ";
		$rsl = $this->myapp->getDataQueryDB6($sql);

		$ttlCargo = $rsl[0]->total_cargo;
		$ttlFloating = $ttlCargo * $rsl[0]->floating_crane_pmt;
		$ttlAddt = $ttlCargo * $rsl[0]->additif_pmt;
		$ttlOther = $ttlCargo * $rsl[0]->other_pmt;

		$ttlCostPMT = $ttlFloating + $ttlAddt + $ttlOther;

		$dataOut['datePrepared'] = $this->convertReturnName($rsl[0]->date_prepared);
		$dataOut['ttlFloating'] = number_format($ttlFloating,0);
		$dataOut['ttlAddt'] = number_format($ttlAddt,0);
		$dataOut['ttlOther'] = number_format($ttlOther,0);
		$dataOut['ttlCostPMT'] = number_format($ttlCostPMT,0);

		$dataOut['data'] = $rsl;

		$this->load->view('myApps/exportDataPdfEstVoyage',$dataOut);
	}
	function hitungTglAnalisVoyage($dtNow = '',$mntNya = '')
	{
		$dN = explode(' ', $dtNow);	
		$jm = explode(':', $dN[1]);
		$pl = explode('.', $mntNya);

		$jamTmb = "0.".$pl[1];

		$jamTmb = $jamTmb * 24;		
		$plTmb = explode('.', $jamTmb);

		$mntTmb = "0.".$plTmb[1];
		$mntTmb = $mntTmb * 60;
		$plMntTmb = explode('.', $mntTmb);

		$tgl = $dN[0];
		$jam = $jm[0];
		$mnt = $jm[1];

		$dayTmb = $pl[0];
		$jamTmb = $plTmb[0];
		$mntTmb = $plMntTmb[0];
		
		$newJam = $jam + $jamTmb;
		$newMnt = $mnt + $mntTmb;

		if($newMnt > 60)
		{
			$newMnt = $newMnt - 60;
			$newJam = $newJam +1;
		}

		if($newJam > 23)
		{
			$newJam = $newJam - 24;
			$dayTmb = $dayTmb +1;
		}		

		$dnNew = date('Y-m-d', strtotime($tgl." +".$dayTmb." day"));

		if(strlen($newMnt) < 2)
		{
			$newMnt = "0".$newMnt;
		}

		if(strlen($newJam) < 2)
		{
			$newJam = "0".$newJam;
		}

		$dnNew = $dnNew." ".$newJam.":".$newMnt;

		// print_r($dnNew);exit;

		return $dnNew;
	}
	function cekDataNol($dataNya)
	{
		$dtRtn = "";
		$exp1 = explode(".", $dataNya);
		if($exp1[0] == "")
		{
			$dtRtn = '0.'.$exp1[1];
		}else{
			$dtRtn = $dataNya;
		}
		return $dtRtn;
	}
	function hitHari($ltt = "")
	{
		$lttNya = 0;
		$ttl1Hari = 86400; //satuan detik dalam 1 hari(1jam=3600 detik)
		if($ltt != "")
		{
			$exp = explode(":", $ltt);
			$tMnt1 = $exp[0] * 3600; //ubah ke detik(jam)
			$tMnt2 = $exp[1] * 60; //ubah ke detik(menit)
			$ttlMenit = $tMnt1 + $tMnt2;

			$lttNya = $ttlMenit/$ttl1Hari;
		}
		return number_format($lttNya,2);
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
	function convertReturnNameWithTime($dateNya = "")
	{
		$dtNya = explode(" ", $dateNya);
		$dt = explode("-", $dtNya[0]);

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
		else if($bln == "08" || $bln == "8"){ $bln = "Agt"; }
		else if($bln == "09" || $bln == "9"){ $bln = "Sep"; }
		else if($bln == "10"){ $bln = "Okt"; }
		else if($bln == "11"){ $bln = "Nov"; }
		else if($bln == "12"){ $bln = "Des"; }

		return $tgl." ".$bln." ".$thn. " ".$dtNya[1];
	}
	function converDate($dateNya = "")
	{
		$dr = "";
		$exp = explode("#",$dateNya);
		$thn = substr($exp[0], 0,4);
		$bln = substr($exp[0], 4,2);
		$tgl = substr($exp[0], 6,2);

		$dr = $tgl." ".$this->convertBulan($bln)." ".$thn;
		$dr .= " ".$exp[1];
		return $dr;
	}
	function convertBulan($bln)
	{
		$nmBln = "";
		if($bln == "1" || $bln == "01") { $nmBln = "Jan"; }
		if($bln == "2" || $bln == "02") { $nmBln = "Feb"; }
		if($bln == "3" || $bln == "03") { $nmBln = "Mar"; }
		if($bln == "4" || $bln == "04") { $nmBln = "Apr"; }
		if($bln == "5" || $bln == "05") { $nmBln = "Mei"; }
		if($bln == "6" || $bln == "06") { $nmBln = "Jun"; }
		if($bln == "7" || $bln == "07") { $nmBln = "Jul"; }
		if($bln == "8" || $bln == "08") { $nmBln = "Agus"; }
		if($bln == "9" || $bln == "09") { $nmBln = "Sep"; }
		if($bln == "10" || $bln == "10") { $nmBln = "Okt"; }
		if($bln == "11" || $bln == "11") { $nmBln = "Nov"; }
		if($bln == "12" || $bln == "12") { $nmBln = "Des"; }

		return $nmBln;
	}
	
}

