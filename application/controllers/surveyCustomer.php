<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class SurveyCustomer extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->model('myapp');
		$this->load->helper(array('form', 'url'));
	}

	function index()
	{
		
	}

	function getDataSurvey($var1 = "",$pagePar = "")
	{
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$pageNow = 1;
		$display = 10;
		$sLimit = 0;
		$eLimit = $display;

		if($var1 == "page")
		{
			$eLimit = $pagePar * $display;
			$sLimit = ($eLimit - $display);
			$pageNow = $pagePar;
		}

		$sqlCount = "SELECT COUNT(A.id) AS total FROM data_customer A
					 RIGHT JOIN customer_survey B ON B.id_customer = A.id AND B.id != '' WHERE A.sts_delete = '0' GROUP BY A.company ";
		$dataCount = $this->myapp->getDataQueryDb5($sqlCount,"");

		$sqlCust = " SELECT A.* FROM data_customer A
					 RIGHT JOIN customer_survey B ON B.id_customer = A.id AND B.id != ''
					 WHERE A.sts_delete = '0' GROUP BY A.company ORDER BY A.date_survey DESC LIMIT ".$sLimit." ,".$display." ";
					 // print_r($sqlCust);exit;
		$dataCust = $this->myapp->getDataQueryDb5($sqlCust);
		
		if($pageNow > 1)
		{
			$no = (($pageNow-1)*10)+1;
		}
		
		for($lan=0;$lan<count($dataCust);$lan++)
		{
			$trNya .= " <tr>
							<td align=\"center\">".$no."</td>
							<td align=\"center\"><a style=\"cursor:pointer;\" onclick=\"viewSurveyCust('".$dataCust[$lan]->id."');\">".$this->convertReturnName($dataCust[$lan]->date_survey)."</a></td>
							<td>".$dataCust[$lan]->company."</td>
							<td>".$dataCust[$lan]->name."</td>
							<td>".$dataCust[$lan]->position."</td>
							<td align=\"center\"><a style=\"cursor:pointer;\" onclick=\"viewSummari('".$dataCust[$lan]->id."');\" >".$dataCust[$lan]->status_survey."</a></td>
						</tr> ";
			$no++;
		}
		$dataOut['trNya'] = $trNya;
		$dataOut['pageNya'] = $this->createPaging(count($dataCount),$pageNow);
		$dataOut['ttlData'] = "Total : ".count($dataCount)." Data ";
		$this->load->view('surveyCustomer/surveyListCust',$dataOut);
	}

	function getSurveyCust()
	{
		$dataOut = array();
		$idCust = $_POST['idCust'];
		$trNya = "";
		$noChk = 1;	

		$sql = " SELECT A.*,B.grp_name,B.survey_name,B.v1_unimportant,B.v1_slightly,B.v1_moderately,B.v1_important,B.v1_very,B.v2_veryPoor,B.v2_poor,B.v2_fair,B.v2_good,B.v2_excellent,B.remark FROM data_customer A
				 RIGHT JOIN customer_survey B ON B.id_customer = A.id
				 WHERE A.sts_delete = '0' AND A.id = '".$idCust."'
				";
		$data = $this->myapp->getDataQueryDb5($sql);
		for($lan=0;$lan<count($data);$lan++)
		{
			$v1_1 = "";
			$v1_2 = "";
			$v1_3 = "";
			$v1_4 = "";
			$v1_5 = "";
			$v2_1 = "";
			$v2_2 = "";
			$v2_3 = "";
			$v2_4 = "";
			$v2_5 = "";
			if($data[$lan]->v1_unimportant > 0){ $v1_1 = "&#10004;"; }
			if($data[$lan]->v1_slightly > 0){ $v1_2 = "&#10004;"; }
			if($data[$lan]->v1_moderately > 0){ $v1_3 = "&#10004;"; }
			if($data[$lan]->v1_important > 0){ $v1_4 = "&#10004;"; }
			if($data[$lan]->v1_very > 0){ $v1_5 = "&#10004;"; }
			if($data[$lan]->v2_veryPoor > 0){ $v2_1 = "&#10004;"; }
			if($data[$lan]->v2_poor > 0){ $v2_2 = "&#10004;"; }
			if($data[$lan]->v2_fair > 0){ $v2_3 = "&#10004;"; }
			if($data[$lan]->v2_good > 0){ $v2_4 = "&#10004;"; }
			if($data[$lan]->v2_excellent > 0){ $v2_5 = "&#10004;"; }
			$trNya .= "<tr>";
				$trNya .= " <td>".$noChk.".</td>";
				$trNya .= " <td>
								<label id=\"idLbl_".$data[$lan]->id."\"><b><u>".strtoupper($data[$lan]->grp_name)."</u></b><br>".$data[$lan]->survey_name."</label>
							</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v1_1."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v1_2."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v1_3."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v1_4."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v1_5."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v2_1."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v2_2."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v2_3."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v2_4."</td>";
				$trNya .= " <td style=\"text-align:center;\">".$v2_5."</td>";
				$trNya .= " <td><textarea class=\"form-control\" disabled=\"disabled\" id=\"idRemark_".$data[$lan]->id."\">".$data[$lan]->remark."</textarea></td>";
			$trNya .= "</tr>";

			$noChk++;
		}

		$trNya .= " <tr>
						<td colspan=\"2\">Any other performance  not written on above list :</td>
						<td colspan=\"11\"><textarea class=\"form-control\" disabled=\"disabled\" id=\"idOtherPerform\">".$data[0]->other_performance."</textarea></td>
					</tr>
					<tr>
						<td colspan=\"2\">Comment/ Suggestion :</td>
						<td colspan=\"11\"><textarea class=\"form-control\" disabled=\"disabled\" id=\"idCommentSuggestion\">".$data[0]->comment_suggestion."</textarea></td>
					</tr>
					<tr>
						<td colspan=\"2\">Total Pelayanan :</td>
						<td colspan=\"11\"><textarea class=\"form-control\" disabled=\"disabled\" id=\"idTotalPelayanan\">".$data[0]->hasil_pelayanan."</textarea></td>
					</tr>";
		
		$dataOut['trNya'] = $trNya;
		print_r(json_encode($dataOut));
	}

	function createSurvey($typeSurvey = "")
	{
		$dataOut = array();
		$dNow = date("Y-m-d");
		$dataOut['dateNow'] = $this->convertReturnName($dNow);
		$dataOut['typeSurvey'] = $typeSurvey;
		if($typeSurvey == "")
		{
			exit;
		}
		$this->load->view('surveyCustomer/surveyCust',$dataOut);
	}

	function addGetQuestion()
	{
		$dataOut = array();
		$trNya = "";
		$getIdNya = "";
		$company = $_POST['cmpny'];
		$name = $_POST['nmCust'];
		$position = $_POST['positionCust'];
		$surveyType = $_POST['surveyType'];
		$lblV2 = "Satisfactory Rating";

		$sqlCek = "SELECT * FROM data_customer WHERE date_survey = '".date("Y-m-d")."' AND company = '".$company."' AND name = '".$name."' ";
		$dataCek = $this->myapp->getDataQueryDb5($sqlCek);

		if(count($dataCek)== 0)
		{
			$dataIns['date_survey'] = date("Y-m-d");
			$dataIns['company'] = $company;
			$dataIns['name'] = $name;
			$dataIns['position'] = $position;
			$dataIns['survey_type'] = $surveyType;
			$dataIns['add_date'] = date("Y-m-d");

			$getIdNya = $this->myapp->insDataDb5($dataIns,"data_customer");

			$trNya = $this->getDataQuestion($surveyType);
			if($surveyType == "charter")
			{
				$lblV2 = "Perfomance Rating";
			}

			$dataOut['trNya'] = $trNya;
			$dataOut['idCust'] = $getIdNya;
			$dataOut['lblV2'] = $lblV2;
			$dataOut['dataCek'] = "kosong";
		
			print json_encode($dataOut);
		}else{
			$dataOut['dataCek'] = "ada";
			print json_encode($dataOut);
		}
	}

	function getDataQuestion($surveyType = "",$viewType = "")
	{
		$trNya = "";
		$idNya = "";
		$noChk = 1;
		if($surveyType == "charter")
		{
			$slc = "*";
			$dbNya = "mst_charter";
			$whereNya = "";			
		}else if($surveyType == "owner")
		{
			$slc = "*";
			$dbNya = "mst_owner";
			$whereNya = "";
		}else{
			$slc = "*";
			$dbNya = "mst_others";
			$whereNya = "";
		}
		
		$dataGrp = $this->myapp->getDataDb5($slc,"mst_grpsurvey","");

		foreach ($dataGrp as $key => $value)
		{
			$data = $this->myapp->getDataDb5($slc,$dbNya,"id_grpSurvey = '".$value->id."' AND sts_delete = '0'");
			if(count($data) > 0)
			{
				$trNya .= "<tr>";
					$trNya .= "<td colspan=\"10\" style=\"font-size:14px;\"><b>&raquo; <u><label>".strtoupper($value->grp_name)."</label></u></b></td>";
				$trNya .= "</tr>";
			}			
			for($lan=0;$lan<count($data);$lan++)
			{
				$trNya .= "<tr>";
					$trNya .= " <td>".$noChk.".</td>";
					$trNya .= " <td><label id=\"idLbl_".$data[$lan]->id."\">".$data[$lan]->survey_name."</label>
									<input type=\"hidden\" id=\"idGrpLbl_".$data[$lan]->id."\" value=\"".$value->grp_name."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v1_".$data[$lan]->id."\" type=\"checkbox\" value=\"unimportant\" name=\"unimportant_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v1_".$data[$lan]->id."\" type=\"checkbox\" value=\"slightly\" name=\"slightly_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v1_".$data[$lan]->id."\" type=\"checkbox\" value=\"moderately\" name=\"moderately_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v1_".$data[$lan]->id."\" type=\"checkbox\" value=\"important\" name=\"important_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v1_".$data[$lan]->id."\" type=\"checkbox\" value=\"very\" name=\"very_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v2_".$data[$lan]->id."\" type=\"checkbox\" value=\"very pool\" name=\"verypool_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v2_".$data[$lan]->id."\" type=\"checkbox\" value=\"poor\" name=\"poor_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v2_".$data[$lan]->id."\" type=\"checkbox\" value=\"fair\" name=\"fair_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v2_".$data[$lan]->id."\" type=\"checkbox\" value=\"good\" name=\"good_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td style=\"text-align:center;\">
									<input class=\"form-check-input\" onclick=\"checkOnlyOne(this.id,this.name);\" id=\"v2_".$data[$lan]->id."\" type=\"checkbox\" value=\"excellent\" name=\"excellent_".$data[$lan]->id."\">
								</td>";
					$trNya .= " <td><textarea class=\"form-control\" id=\"idRemark_".$data[$lan]->id."\"></textarea></td>";
				$trNya .= "</tr>";
				if($idNya == "")
				{
					$idNya = $data[$lan]->id;
				}else{
					$idNya .= "^".$data[$lan]->id;
				}
				$noChk++;
			}
		}
		$trNya .= " <tr>
						<td colspan=\"2\">Any other performance  not written on above list :</td>
						<td colspan=\"11\"><textarea class=\"form-control\" id=\"idOtherPerform\"></textarea></td>
					</tr>
					<tr>
						<td colspan=\"2\">Comment/ Suggestion :</td>
						<td colspan=\"11\"><textarea class=\"form-control\" id=\"idCommentSuggestion\"></textarea></td>
					</tr>
					<tr>
						<td colspan=\"2\">Total Pelayanan :</td>
						<td colspan=\"11\">
							<select class=\"form-control\" id=\"slcPelayanan\">
								<option value=\"puas\">Puas</option>
								<option value=\"tidak puas\">Tidak Puas</option>
							</select>
						</td>
					</tr>";

		$trNya .= "<input type=\"hidden\" id=\"idCustSurvey\" name=\"idCustSurvey\" value=\"".$idNya."\">";
	
		return $trNya;
	}

	function addSurveyQuestion()
	{
		$dataIns = array();
		$updateData = array();
		$valNya = $_POST['valNya'];
		$otherPerform = $_POST['otherPerform'];
		$commentSuggestion = $_POST['commentSuggestion'];
		$hslPelayanan = $_POST['hslPelayanan'];
		$idCustomer = "";
		$stExec = "";

		try {	
			$arr = explode("||",$valNya);
			for($lan=0;$lan<count($arr);$lan++)
			{
				$arrSrvy = explode("^", $arr[$lan]);
				for($hal=0;$hal<count($arrSrvy);$hal++)
				{
					$idCustomer = $arrSrvy[5];
					$dataIns['id_customer'] = $arrSrvy[5];
					$dataIns['grp_name'] = $arrSrvy[0];
					$dataIns['survey_name'] = $arrSrvy[1];
					if($arrSrvy[2] == "unimportant"){ $dataIns['v1_unimportant'] = '1'; }
					else if($arrSrvy[2] == "slightly"){ $dataIns['v1_slightly'] = '2'; }
					else if($arrSrvy[2] == "moderately"){ $dataIns['v1_moderately'] = '3'; }
					else if($arrSrvy[2] == "important"){ $dataIns['v1_important'] = '4'; }
					else if($arrSrvy[2] == "very"){ $dataIns['v1_very'] = '5'; }

					if($arrSrvy[3] == "very pool"){ $dataIns['v2_veryPoor'] = '1'; }
					else if($arrSrvy[3] == "poor"){ $dataIns['v2_poor'] = '2'; }
					else if($arrSrvy[3] == "fair"){ $dataIns['v2_fair'] = '3'; }
					else if($arrSrvy[3] == "good"){ $dataIns['v2_good'] = '4'; }
					else if($arrSrvy[3] == "excellent"){ $dataIns['v2_excellent'] = '5'; }
					$dataIns['remark'] = $arrSrvy[4];					
				}
				$this->myapp->insDataDb5($dataIns,"customer_survey");
				$dataIns = array();
			}
			$updateData['status_survey'] = $this->hitungCSI($idCustomer);
			$updateData['other_performance'] = $otherPerform;
			$updateData['comment_suggestion'] = $commentSuggestion;
			$updateData['hasil_pelayanan'] = $hslPelayanan;
			$whereNya = "id = '".$idCustomer."' ";
			$this->myapp->updateDataDb5("data_customer",$updateData,$whereNya);
			$stExec = "Success..!!";
		} catch (Exception $e) {
			$stExec = "Failed..!! ".$e;
		}

		print_r(json_encode($stExec));
	}

	function viewSummary()
	{
		$dataOut = array();
		$trNya = "";
		$ttlV1 = 0;
		$ttlIP = 0;
		$idCust = $_POST['idCust'];
		$sql = " SELECT id,survey_name, (v1_unimportant + v1_slightly + v1_moderately + v1_important + v1_very) as ttlV1, (v2_veryPoor + v2_poor + v2_fair + v2_good + v2_excellent) as ttlV2,( (v1_unimportant + v1_slightly + v1_moderately + v1_important + v1_very)*(v2_veryPoor + v2_poor + v2_fair + v2_good + v2_excellent) ) as totalIP FROM customer_survey WHERE id_customer = '".$idCust."'";
		$data = $this->myapp->getDataQueryDb5($sql);

		for ($lan = 0; $lan < count($data); $lan++)
		{
			$no = $lan+1;
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">Q".$no."</td>";
				$trNya .= "<td align=\"center\">".$data[$lan]->ttlV1."</td>";
				$trNya .= "<td align=\"center\">".$data[$lan]->ttlV2."</td>";
				$trNya .= "<td align=\"center\">".$data[$lan]->totalIP."</td>";
			$trNya .= "</tr>";
			$ttlV1 = $ttlV1 + $data[$lan]->ttlV1;
			$ttlIP = $ttlIP + $data[$lan]->totalIP;
		}
			$trNya .= "<tr>
							<td align=\"right\">Total :</td>
							<td align=\"center\" style=\"font-weight: bold;\">".$ttlV1." ( Y )</td>
							<td></td>
							<td align=\"center\" style=\"font-weight: bold;\">".$ttlIP." ( T )</td>
						</tr>";
		$dataOut['trNya'] = $trNya;
		$dataOut['trNyaCriteria'] = $this->getTblCriteria($ttlV1,$ttlIP);
		print_r(json_encode($dataOut));
	}

	function getTblCriteria($Y = "",$T = "")
	{
		$trNya = "";
		$bgClr1 = "";
		$bgClr2 = "";
		$bgClr3 = "";
		$bgClr4 = "";
		$bgClr5 = "";

		$hsl = ($T/(5*$Y))*100;
		$stSvry = $this->getstTxtSurvey($hsl);

		if($stSvry == "Tidak Puas") { $bgClr1 = "#D6E4F2;"; }
		else if($stSvry == "Kurang Puas") { $bgClr2 = "#D6E4F2;"; }
		else if($stSvry == "Cukup Puas") { $bgClr3 = "#D6E4F2;"; }
		else if($stSvry == "Puas") { $bgClr4 = "#D6E4F2;"; }
		else if($stSvry == "Sangat Puas") { $bgClr5 = "#D6E4F2;"; }

		$trNya .= " <tr>
						<td style=\"background-color:".$bgClr5."\" align=\"center\">1</td>
						<td style=\"background-color:".$bgClr5."\">81% - 100%</td>
						<td style=\"background-color:".$bgClr5."\">Sangat Puas</td>
					</tr>";
		$trNya .= " <tr>
						<td style=\"background-color:".$bgClr4."\" align=\"center\">2</td>
						<td style=\"background-color:".$bgClr4."\">66% - 80.99%</td>
						<td style=\"background-color:".$bgClr4."\">Puas</td>
					</tr>";
		$trNya .= " <tr>
						<td style=\"background-color:".$bgClr3."\" align=\"center\">3</td>
						<td style=\"background-color:".$bgClr3."\">51% - 65.99%</td>
						<td style=\"background-color:".$bgClr3."\">Cukup Puas</td>
					</tr>";
		$trNya .= " <tr>
						<td style=\"background-color:".$bgClr2."\" align=\"center\">4</td>
						<td style=\"background-color:".$bgClr2."\">35% - 50.99%</td>
						<td style=\"background-color:".$bgClr2."\">Kurang Puas</td>
					</tr>";
		$trNya .= " <tr>
						<td style=\"background-color:".$bgClr1."\" align=\"center\">5</td>
						<td style=\"background-color:".$bgClr1."\">0% - 34.99%</td>
						<td style=\"background-color:".$bgClr1."\">Tidak Puas</td>
					</tr>";
		$trNya .= "<tr>
						<td colspan=\"3\" align=\"center\" style=\"background-color:#19282F;color:#FFF;\"> CSI = ( T/(5*Y) ) * 100 %</td>
					</tr>";
		$trNya .= "<tr>
						<td colspan=\"3\" align=\"center\"> CSI = ( ".$T."/(5*".$Y.") ) * 100 %</td>
					</tr>";
		$trNya .= "<tr>
						<td colspan=\"3\" align=\"center\"> CSI = <b>".number_format($hsl,2)." %</b></td>
					</tr>";
		return $trNya;
	}

	function hitungCSI($idCust = "")
	{
		$csi = 0;
		$stNya = "";
		$sql = " SELECT SUM( v1_unimportant + v1_slightly + v1_moderately + v1_important + v1_very ) AS total_I, SUM( (v1_unimportant + v1_slightly + v1_moderately + v1_important + v1_very) * ( v2_veryPoor + v2_poor + v2_fair + v2_good + v2_excellent ) ) AS total_IxP FROM customer_survey WHERE id_customer = '".$idCust."' ";
		$data = $this->myapp->getDataQueryDb5($sql);
		if (count($data) > 0)
		{
			$Y = $data[0]->total_I;
			$T = $data[0]->total_IxP;
			$csi = ($T/(5 * $Y)) * 100;

			$stNya = $this->getstTxtSurvey($csi);
		}
		return $stNya;
	}

	function getstTxtSurvey($csi = "")
	{
		$stNya = "";
		if($csi < 35)
		{
			$stNya = "Tidak Puas";
		}else if($csi >= 35 AND $csi < 51)
		{
			$stNya = "Kurang Puas";
		}else if($csi >= 51 AND $csi < 66)
		{
			$stNya = "Cukup Puas";
		}else if($csi >= 66 AND $csi < 81)
		{
			$stNya = "Puas";
		}else if($csi >= 81 )
		{
			$stNya = "Sangat Puas";
		}
		return $stNya;
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
			$pageNya .= "<a class=\"page-link\" href=\"".base_url('surveyCustomer/getDataSurvey/page/1')."\" aria-label=\"Previous\">";
				$pageNya .= "<span aria-hidden=\"true\">&laquo;</span>";
			$pageNya .= "</a>";
			$pageNya .= "</li>";
			$pageNya .= "<li class=\"".$cP1."\"><a class=\"page-link\" href=\"".base_url('surveyCustomer/getDataSurvey/page/'.$startPage.'')."\">".$startPage."</a></li>";
			if($lastPage > 1)
			{
				$pageNya .= "<li class=\"".$cP2."\"><a class=\"page-link\" href=\"".base_url('surveyCustomer/getDataSurvey/page/'.$startPage2.'')."\">".$startPage2."</a></li>";
				if($pageNow < $lastPage AND $pageNow > 2)
				{
					$pageNya .= "<li class=\"".$cP3."\"><a class=\"page-link\" href=\"".base_url('surveyCustomer/getDataSurvey/page/'.$startPage3.'')."\">".$startPage3."</a></li>";
				}
				
				if($lastPage > ($pageNow+1))
				{
					$pageNya .= "<li class=\"page-item\"><a> ...</a></li>";
					$pageNya .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".base_url('surveyCustomer/getDataSurvey/page/'.$lastPage.'')."\">".$lastPage."</a></li>";
				}
			}
		$pageNya .= "</ul>";

		return $pageNya;
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

