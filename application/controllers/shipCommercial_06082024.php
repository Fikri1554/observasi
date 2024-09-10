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

		print json_encode($dataOut);
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

