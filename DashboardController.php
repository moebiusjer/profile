<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use Yajra\Datatables\Datatables;
use DB;
use DateTime;
use DateInterval;
use Cache;
use App\Models\Utility;

use Hamcrest\Util;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Validator;
use App\Models\RevenueGroup;
use App\Models\TrafficAccount;
use App\Helpers\VH;

class DashboardController extends Controller
{

    //  INTIAL FUNCTION TO DISPLAY DASHBOARD STRUCTURE
	public function initiate(Request $request){

		return view('dashboard');

	}

	private function dateManager ($start = "",$end = "") {


	}

	public function chartRevenue(Request $request){

		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-31 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$chart = "";
		$xcolumn = "";
		$ycolumn = "";
		$xcolumn2 = "";
		$ycolumn2 = "";
		$xcolumn3 = "";
		$ycolumn3 = "";

		$aryprofit = array();
		$aryspend = array();
		$aryTotal = array();

		// GET 30 DAY PROFIT FROM END DATE
		$maindate = $end;

		// CLEAR ARRAYS
		$begin = new DateTime( $start );
		$end1   = new DateTime( $end );
		//$begin->sub(new DateInterval('P30D'));
		for($i = $begin; $i <= $end1; $i->modify('+1 day')){
			$aryprofit[$i->format("Y-m-d")] = "0.00";
			$aryspend[$i->format("Y-m-d")] = "0.00";
			$aryTotal[$i->format("Y-m-d")] = "0.00";

		}
		// END CLEAR

		// GET PROFIT
		$sql = "SELECT ROUND(SUM(revenue_est),2) AS revenue_est, ROUND(SUM(spend),2) AS spend, metric_by_revgroup.date as start_date,
			ROUND(SUM(revenue_est) - SUM(spend),2) AS profit
			FROM metric_by_revgroup
			LEFT JOIN rev_group rg ON rg.id = metric_by_revgroup.rev_group_id
			where metric_by_revgroup.date between '".$start."' and '".$end."' ";
		if ($admin == false) {
			$sql .= " AND rg.user_id = ".$request->session()->get('user_id');
		}
		$sql .= " group by metric_by_revgroup.date
			order by metric_by_revgroup.date";

		$resultProfit = DB::select($sql);

		$begin = new DateTime( $start );
		$end1   = new DateTime( $end );


		$diff = $end1->diff($begin)->format("%a");
		$totalprofit = 0.00;

		for($i = $begin; $i <= $end1; $i->modify('+1 day')){
			if (count($resultProfit) > 0) {
				foreach ($resultProfit as $profit) {
					$dateStart = date("Y-m-d",strtotime($profit->start_date));
					if ($dateStart == $i->format("Y-m-d")) {
						//echo $spend->start_date."|".$i->format("Y-m-d").": $".$spend->spend."<br>";
						$aryprofit[$i->format("Y-m-d")] = str_replace(",","",number_format($profit->revenue_est,2));
						$aryspend[$i->format("Y-m-d")] = str_replace(",","",number_format($profit->spend,2));
						$revenue = floatval($profit->revenue_est);
						$spend = floatval($profit->spend);
						//$totalprofit = $revenue - $spend;
						$totalprofit = floatval($profit->profit);
						$aryTotal[$i->format("Y-m-d")] = str_replace(",","",number_format($totalprofit,2));
					}
				}
			} else {
				$aryprofit[$i->format("Y-m-d")] = "0.00";
				$aryspend[$i->format("Y-m-d")] = "0.00";
				$aryTotal[$i->format("Y-m-d")] = "0.00";
			}
		}

		foreach($aryprofit as $key=>$val) {
			//echo $key." | ".$val."<br>";
			$xcolumn .= "\"".$key."\"";
			$ycolumn .= $val;
			if ($key != date("Y-m-d")) {
				$xcolumn .= ",";
				$ycolumn .= ",";
			}
		}

		foreach($aryspend as $key=>$val) {
			//echo $key." | ".$val."<br>";
			$xcolumn2 .= "\"".$key."\"";
			$ycolumn2 .= $val;
			if ($key != date("Y-m-d")) {
				$xcolumn2 .= ",";
				$ycolumn2 .= ",";
			}
		}

		foreach($aryTotal as $key=>$val) {
			//echo $key." | ".$val."<br>";
			$xcolumn3 .= "\"".$key."\"";
			$ycolumn3 .= $val;
			if ($key != date("Y-m-d")) {
				$xcolumn3 .= ",";
				$ycolumn3 .= ",";
			}
		}
		//END PROFIT

		//$chart .= $ycolumn."<br>";
    	$chart .= "<header class=\"panel-heading\"><h2 class=\"panel-title\">Revenue / Spend for Past ".$diff." Days (".$start." to ".$end.")</h2></header><canvas id=\"myChart\"></canvas>
				<script>
				var ctx = document.getElementById(\"myChart\");
				var myChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: [".$xcolumn."],
						datasets: [{
							label: 'Profit ($)',
							data: [".$ycolumn3."],
							backgroundColor: [
								'rgba(51, 204, 51, .5)'
							],
							borderColor: [
								'rgba(51, 204, 51,1)'
							],
							borderWidth: 1
							},{
							label: 'Spend ($)',
							data: [".$ycolumn2."],
							backgroundColor: [
								'rgba(255, 99, 132, .5)'
							],
							borderColor: [
								'rgba(255,99,132,1)'
							],
							borderWidth: 1
							},{
							label: 'Revenue ($)',
							data: [".$ycolumn."],
							backgroundColor: [
								'rgba(40, 163, 247, 0.2)'
							],
							borderColor: [
								'rgba(40, 163, 247,1)'
							],
							borderWidth: 1
							}
						]
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero:true
								}
							}]
						},
						aspectRatio: 3
					}
				});
				</script>";
		return $chart;

	}

	public function chartRevenueByTrafficAccount(Request $request){

		$arySpend = array();
		$tabledata = "";

		$userid = $request->session()->get('user_id');

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		$sql = "select t.id as traffic_account_id,sum(pbrg.revenue_est) as revenue, SUM(pbrg.spend) AS spend, tt.name, t.name as ta_name
			from metric_by_revgroup pbrg, rev_group r, traffic_account t, traffic_account_type tt
			where pbrg.date BETWEEN '".qnq($start)."' AND '".qnq($end)."'  AND t.active = 1
			and pbrg.rev_group_id = r.id
			and r.traffic_account_id = t.id
			and t.traffic_account_type_id = tt.id ";
		if ($admin == false) {
			$sql .= " and r.user_id = ".intval($request->session()->get('user_id'));
		}
		$sql .= " and (pbrg.spend > 0	OR pbrg.revenue_est > 0)
			group by t.id,tt.name, ta_name
			order by tt.name";

		$resultProfit = DB::select( DB::raw($sql) );
		$tableheader = "";
		$rev = 0.00;
		foreach ($resultProfit as $profit) {
			// GENERATE HEADER IF NECESSARY
			if ($tableheader != $profit->name) {
				//$tabledata .= "<tr class=\"bg-success\"><td colspan=\"5\"><strong>".$profit->name."</strong></td></tr>";
				$tableheader = $profit->name;
			}
			// END HEADER
			$trafficspend = $profit->spend;
			$roi = 0.00;
			$roi = floatval($roi);
			$trafficspend = floatval($trafficspend);
			$profitNum = floatval($profit->revenue - $trafficspend);
			//$profitNum = number_format($profitNum,2);
			$rev = $profit->revenue;

			if ($trafficspend > 0) {
				$roi = floatval($profitNum) / floatval($trafficspend);
				$roi = $roi*100;
				$roi = number_format($roi,2);
			}
			$tabledata .= "<tr><td align=\"right\">".$profit->name."</td><td><a href=\"javascript:openhome(".$profit->traffic_account_id.");\">".$profit->ta_name."</a></td><td align=\"right\">$".number_format($profit->revenue,2)."</td><td  align=\"right\">$".number_format($trafficspend,2)."</td><td  align=\"right\">$".number_format($profitNum,2)."</td><td align=\"right\">".$roi."%</td></tr>";
		}

		$tablehead = "<header class=\"panel-heading\"><h2 class=\"panel-title\">Profit by Traffic Account (".$start." to ".$end.")</h2></header><table class=\"table table-bordered table-condensed table-hover\" id=\"rbtaTable\"><thead><tr><th>Account Type</th><th>Traffic Account</th><th>Revenue</th><th>Cost</th><th>Profit</th><th>ROI</th></tr></thead><tbody>";
		$tablefoot = "</tbody></table>";

		return $tablehead.$tabledata.$tablefoot;
	}

	// @TODO This function has quite a few SQL injection bugs ($start/$end/$roasnum/etc)
	function ROASsidebyside(Request $request) {
		$today = date("Y-m-d",strtotime("-1 days"));
		$minspend = 50;
		$roasnum = 5;

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}
		if( !empty(Input::get('roasspend'))) { $minspend = Input::get('roasspend'); }
		if( !empty(Input::get('roasnum'))) { $roasnum = Input::get('roasnum'); }

		// @FIXED Sometimes spend ='s undefined
		if (!is_numeric($minspend)) {
		    $minspend = 0;
		}

		$yesterday = $end;
		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		// GET TODAY ROAS - TOP 5
		$dropdownlist = "<select id=\"roasnum\">";
		for($x=5;$x<=50;$x=$x+5) {
			$dropdownlist.= "<option value\"".$x."\"";
			if ($roasnum == $x) { $dropdownlist.=" selected=\"selected\""; }
			$dropdownlist.= ">".$x."</option>";
		}
		$dropdownlist.="</select>";

		$todaylist = "<table class=\"table table-condensed table-striped table-bordered table-hover\">";
		$todaylist .= "<tr><td colspan=\"5\"><strong>TOP ".$dropdownlist." ROAS for ".$start." to ".$end."</strong><div style=\"float:right;\"><div style=\"float:right;\"><button id=\"btnROASGo\" style=\"height: 20px;font-size: 10px;\" onclick=\"runROAS();\">GO</button></div><div style=\"float:right;\"><input type=\"text\" id=\"roasspend\" placeholder=\"ROAS Spend\" class=\"form-control input-sm\" style=\"width:80px;margin-right:3px;text-align:right;\" value=\"".$minspend."\"></div><div style=\"float:right;top:3px;position: relative;padding-right:5px;\">Min Spend: </div></div></td></tr>";
		$todaylist .= "<tr><td><strong>Name</strong></td><td align=\"right\"><strong>Revenue</strong></td><td align=\"right\"><strong>Spend</strong></td><td align=\"right\"><strong>Profit</strong></td><td align=\"right\"><strong>ROAS</strong></td></tr>";
		/*
		$sql = "SELECT rg.id,rg.name,pbrg.spend,pbrg.revenue,(pbrg.revenue - pbrg.spend) AS profit, (((pbrg.revenue - pbrg.spend)) / pbrg.spend)*100 AS ROAS
			FROM profit_by_rev_group pbrg
			INNER JOIN rev_group rg ON rg.id = pbrg.rev_group_id
			WHERE pbrg.spend >= ".$minspend." and profit_date = '".$yesterday."' ";
		if ($admin == false) {
			$sql .= " and rg.user_id = ".$request->session()->get('user_id');
		}
		$sql .= " ORDER BY ROAS DESC
			LIMIT 0,5";
		*/
		$sql = "SELECT rg.id,rg.name,sum(pbrg.spend) as spend,sum(pbrg.revenue_est) as revenue,(sum(pbrg.revenue_est) - sum(pbrg.spend)) AS profit, (((sum(pbrg.revenue_est) / sum(pbrg.spend)))) AS ROAS
			FROM metric_by_revgroup pbrg
			INNER JOIN rev_group rg ON rg.id = pbrg.rev_group_id
			WHERE  pbrg.date between '".$start."' and '".$end."' ";
		if ($admin == false) {
			$sql .= " and rg.user_id = ".$request->session()->get('user_id');
		}
		$sql .= "  GROUP BY rg.id, rg.name HAVING spend >= ".doubleval($minspend)."
			ORDER BY ROAS DESC
			LIMIT 0,".$roasnum;
		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {
			$roas = number_format($result->ROAS,2);
			$revenue = number_format($result->revenue,2);
			$spend = number_format($result->spend,2);
			$profit = number_format($result->profit,2);
			$todaylist .= "<tr><td><a href\"#\" class=\"kwmodallink\" style=\"cursor: pointer;\" data-toggle=\"modal\" data-target=\"#kwmodalpanel\" data-rgid=\"".$result->id."\" data-rgname=\"".$result->name."\" >".$result->name."</a></td><td align=\"right\">$".$revenue."</td><td align=\"right\">$".$spend."</td><td align=\"right\">$".$profit."</td><td align=\"right\">".$roas."</td></tr>";
		}

		// GET TODAY ROAS - BOTTOM 5
		//$todaylist .= "<table class=\"table table-condensed\">";
		$todaylist .= "<tr ><td colspan=\"5\"><strong>BOTTOM ".$roasnum." ROAS for ".$start." to ".$end."</strong></td></tr>";
		//$todaylist .= "<tr><td><strong>Name</strong></td><td align=\"right\"><strong>Revenue</strong></td><td align=\"right\"><strong>Spend</strong></td><td align=\"right\"><strong>Profit</strong></td><td align=\"right\"><strong>ROAS</strong></td></tr>";
		/*
		$sql = "SELECT rg.id,rg.name,pbrg.spend,pbrg.revenue,(pbrg.revenue - pbrg.spend) AS profit, (((pbrg.revenue - pbrg.spend)) / pbrg.spend)*100 AS ROAS
			FROM profit_by_rev_group pbrg
			INNER JOIN rev_group rg ON rg.id = pbrg.rev_group_id
			WHERE pbrg.spend >= ".$minspend." and profit_date = '".$yesterday."' AND ((((pbrg.revenue - pbrg.spend)) / pbrg.spend)) is not null
			ORDER BY ROAS ASC
			LIMIT 0,5";
		*/
		$sql = "SELECT rg.id,rg.name,sum(pbrg.spend) as spend,sum(pbrg.revenue_est) as revenue,(sum(pbrg.revenue_est) - sum(pbrg.spend)) AS profit, (((sum(pbrg.revenue_est) / sum(pbrg.spend)))) AS ROAS
			FROM metric_by_revgroup pbrg
			INNER JOIN rev_group rg ON rg.id = pbrg.rev_group_id
			WHERE  pbrg.date between '".$start."' and '".$end."' ";
		if ($admin == false) {
			$sql .= " and rg.user_id = ".$request->session()->get('user_id');
		}
		$sql .= "  GROUP BY rg.id, rg.name HAVING spend >= ".doubleval($minspend)."
			ORDER BY ROAS ASC
			LIMIT 0,".$roasnum;
		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {
			$roas = number_format($result->ROAS,2);
			$revenue = number_format($result->revenue,2);
			$spend = number_format($result->spend,2);
			$profit = number_format($result->profit,2);
			$todaylist .= "<tr><td><a href\"#\" class=\"kwmodallink\" style=\"cursor: pointer;\" data-toggle=\"modal\" data-target=\"#kwmodalpanel\" data-rgid=\"".$result->id."\" data-rgname=\"".$result->name."\" >".$result->name."</a></td><td align=\"right\">$".$revenue."</td><td align=\"right\">$".$spend."</td><td align=\"right\">$".$profit."</td><td align=\"right\">".$roas."</td></tr>";
		}

		$todaylist .= "</table><br>";

		return $todaylist;
	}

	function getScrubRate(Request $request) {
		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		$yesterday = date("Y-m-d",strtotime("-2 days"));

		$end = date("Y-m-d",strtotime("-1 days"));
		$start = date("Y-m-d",strtotime("-31 days"));

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-31 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$yesterday = $end;


		// GET YESTERDAY SCRUB RATE - TOP 10
		$todaylist = "<header class=\"panel-heading\"><h2 class=\"panel-title\">Scrub Rate for ".$start." to ".$end."</h2></header>";
		$todaylist .= "<table class=\"table table-condensed table-striped table-bordered table-hover\" id=\"tableScrub\">";
		$todaylist .= "<thead><tr><th>Hostname</th><th align=\"right\">RT Clicks</th><th align=\"right\">Paid Clicks</th><th align=\"right\">Scrub Rate</th></tr></thead><tbody>";
		$sql = "SELECT site.id, site.hostname,
			SUM(rev_click_cnt_est) as rev_clicks,
			SUM(rt_click_cnt) as rt_clicks,
		   (ROUND( SUM(rev_click_cnt_est)/SUM(rt_click_cnt), 4)) AS paid_rate,
			(ROUND( ((SUM(rt_click_cnt)-SUM(rev_click_cnt_est))/SUM(rt_click_cnt)), 4)) AS scrub
		FROM metric_by_revgroup mrg
		LEFT JOIN rev_group ON rev_group.id=mrg.rev_group_id
		LEFT JOIN traffic_account ON rev_group.traffic_account_id=traffic_account.id
		LEFT JOIN site ON traffic_account.site_id=site.id
		WHERE mrg.date between '".qnq($start)."' and '".qnq($end)."' ";
		if ($admin == false) {
			$sql .= " and rev_group.user_id = " . intval($request->session()->get('user_id'));
		}
		$sql .= " and site.active = 1
		GROUP BY id,hostname
		ORDER BY (ROUND( SUM(rev_click_cnt_est)/SUM(rt_click_cnt), 4)) DESC
		";

		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {


			$rtclicks = $result->rt_clicks;
			$rtclicks = number_format($rtclicks);
			$revclicks = number_format($result->rev_clicks);
			$scrubrate = $result->scrub;
			$scrubrate = number_format($scrubrate*100,2);
			$todaylist .= "<tr><td>".$result->hostname."</td><td align=\"right\">".$rtclicks."</td><td align=\"right\">".$revclicks."</td><td align=\"right\"><a href=\"#\" data-toggle=\"modal\" data-target=\"#scrubByRG\" onclick=\"openRGScrub(".$result->id.");\">".$scrubrate."%</a></td></tr>";
		}
		$todaylist .= "</tbody></table>";
		//dashboardScrubByRG
		return $todaylist;
	}

	function getTQProfit(Request $request) {
		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}	//
		$fltrRevenue 		=Input::get('fltrRevenue');
		$fltrRevenueComp	=Input::get('fltrRevenueComp');
		$fltrRPCcomp		=Input::get('fltrRPCcomp');
		$fltrRPC			=Input::get('fltrRPC');
		$fltrClicksCom		=Input::get('fltrClicksCom');
		$fltrClicks			=Input::get('fltrClicks');
		$fltrRPIComp		=Input::get('fltrRPIComp');
		$fltrRPI			=Input::get('fltrRPI');
		$fltrRPIComp		=Input::get('fltrRPIComp');
		$fltrImpComp		=Input::get('fltrImpComp');
		$fltrImp			=Input::get('fltrImp');
		$fltrRoas			=Input::get('fltrRoas');
		$fltrRoasComp		=Input::get('fltrRoasComp');
		$fltrDwt			=Input::get('fltrDwt');
		$fltrDwtComp		=Input::get('fltrDwtComp');

		$comp = ">=";
		/*
		$sql = "select s.hostname, r.traffic_account_id, p.keyword,
			p.rev_group_id, r.name as rev_group_name,
			ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS avg_rpi,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS avg_rpc,
			SUM(rev_click_cnt_est) AS total_clicks,
			SUM(unq_imp_cnt) as total_imps,
			SUM(revenue_est) AS total_revenue,
			ROUND(SUM(quality * rev_click_cnt_est)/SUM(IF(quality > 0, rev_click_cnt_est, 0)),2) AS avg_weighted_tq
		from metric m
		left join placement p on m.placement_id = p.id
		left join rev_group r on r.id = p.rev_group_id
		left join site s on p.site_id = s.id
		left join user u on p.user_id = u.id
		where date >= '".$start."' and date <= '".$end."'
		and p.active = 1 ";
		if ($admin == false) {
			$sql .= " and r.user_id = ".$request->session()->get('user_id');
		}

		$sql .= " group by s.hostname, r.traffic_account_id, p.rev_group_id, rev_group_name, p.keyword
		having avg_weighted_tq <= 4 ";

		*/
		$sql = "SELECT ta.id as traffic_account_id,ta.name AS taname, rg.id as rev_group_id,rg.name AS rgname, SUM(mbrg.rt_click_cnt) AS rt_click_cnt,
			SUM(mbrg.revenue_est) AS total_revenue, SUM(mbrg.spend) AS spend,
			IF(SUM(mbrg.unq_imp_cnt) > 0, ROUND(SUM(mbrg.revenue_est)/SUM(mbrg.unq_imp_cnt),3),0) AS avg_rpi,
			IF(SUM(mbrg.rev_click_cnt_est) > 0,ROUND(SUM(mbrg.revenue_est)/SUM(mbrg.rev_click_cnt_est),2),0) AS avg_rpc,
			SUM(rev_click_cnt_est) AS total_clicks,
			SUM(unq_imp_cnt) as total_imps, (SELECT dwt FROM rev_groups_days_with_traffic rgdwt WHERE rgdwt.rev_group_id = rg.id) AS dwt,
			sum(mbrg.revenue_est) - sum(mbrg.spend) AS profit

			FROM metric_by_revgroup mbrg
			LEFT JOIN rev_group rg ON rg.id = mbrg.rev_group_id
			LEFT JOIN traffic_account ta ON ta.id = rg.traffic_account_id
			WHERE mbrg.date >= '".qnq($start)."' and mbrg.date <= '".qnq($end)."' ";
		if ($admin == false) {
			$sql .= " and rg.user_id = ".intval($request->session()->get('user_id'));
		}
		$sql .= " GROUP BY ta.id,taname, rg.id, rgname
			HAVING rgname <> '' ";

		if ($fltrRevenue != "") {
			if ($fltrRevenueComp == 'gt') { $comp = " >= "; } else { $comp = " <= "; }
			$sql .= " and total_revenue ".$comp." ".$fltrRevenue;
		}
		if ($fltrRPC != "") {
			if ($fltrRPCcomp == 'gt') { $comp = " >= "; } else { $comp = " <= "; }
			$sql .= " and avg_rpc ".$comp." ".$fltrRPC;
		}
		if ($fltrClicks != "") {
			if ($fltrClicksCom == 'gt') { $comp = " >= "; } else { $comp = " <= "; }
			$sql .= " and total_clicks ".$comp." ".$fltrClicks;
		}
		if ($fltrRPI != "") {
			if ($fltrRPIComp == 'gt') { $comp = " >= "; } else { $comp = " <= "; }
			$sql .= " and avg_rpi ".$comp." ".$fltrRPI;
		}
		if ($fltrImp != "") {
			if ($fltrImpComp== 'gt') { $comp = " >= "; } else { $comp = " <= "; }
			$sql .= " and total_imps ".$comp." ".$fltrImp;
		}
		/*
		if ($fltrRoas != "") {
			if ($fltrRoasComp== 'gt') { $comp = " >= "; } else { $comp = " <= "; }
			$sql .= " and total_imps ".$comp." ".$fltrImp;
		}
		*/
		$sql .= " order by total_revenue desc";
		//echo $sql;

		$todaylist = "<header class=\"panel-heading\"><h2 class=\"panel-title\">Profit for ".$start." to ".$end."</h2><div style=\"position: relative;height:60px;\">";

		$todaylist.='<table width="450" align="right">
	<tbody>
		<tr>
			<td style="text-align:right;width:100px;"><strong>Revenue:</strong></td>
			<td style="width:10px;"><select id="revcomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="revs" style="width: 80px; height:20px; text-align: right;"></td>

			<td style="text-align:right;width:150px;"><strong>RPC:</strong></td>
			<td style="width:10px;"><select id="rpccomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="rpcfilter" style="width: 80px; height:20px; text-align: right;"></td>

			<td style="text-align:right;width:150px;"><strong>Clicks:</strong></td>
			<td style="width:10px;"><select id="clickscomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="clicksfilter" style="width: 80px; height:20px; text-align: right;"></td>

			<td style="text-align:right;width:150px;"><strong>RPI:</strong></td>
			<td style="width:10px;"><select id="rpicomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="rpifilter" style="width: 80px; height:20px; text-align: right;"></td>
		</tr>
		<tr>
			<td style="text-align:right;width:100px;"><strong>Impressions:</strong></td>
			<td style="width:10px;"><select id="impcomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="impfilter" style="width: 80px; height:20px; text-align: right;"></td>

			<!--<td style="text-align:right;width:150px;"><strong>ROAS:</strong></td>
			<td style="width:10px;"><select id="roascomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="roasfilter" style="width: 80px; height:20px; text-align: right;"></td>
			-->
			<td style="text-align:right;width:150px;" nowrap=""><strong>Days w/ Traffic:</strong></td>
			<td style="width:10px;"><select id="dwtcomp"><option value="gt"> &gt;= </option><option value="lt"> &lt;= </option></select></td>
			<td style="text-align:left;width:100px;"><input type="number" id="dwtfilter" style="width: 80px; height:20px; text-align: right;"></td>

			<td colspan="6" align="right"><button type="button" id="submitFilterButton" class="btn btn-primary btn-xs" onclick="doProfitQuery();" style="margin-right: 30px;">Submit</button></td>
		</tr>
	</tbody>
</table>';

		$todaylist.="</div></header><table class=\"table table-condensed table-striped table-bordered table-hover\" id=\"tableTQProfit\">";
		$todaylist .= "<thead><tr><th>Traffic Account</th><th>Rev Group</th><th class=\"dt-body-right\">Total Impressions<br><span id=\"sumImp\"></span></th><th class=\"dt-body-right\">RT Clicks<br><span id=\"sumRTClicks\"></span></th><th class=\"dt-body-right\">Rev Clicks<br><span id=\"sumRevClicks\"></span></th><th  class=\"dt-body-right\">CTR<br><span id=\"sumCTR\"></span></th><th class=\"dt-body-right\">Avg RPI<br><span id=\"sumRPI\"></span></th><th class=\"dt-body-right\">Avg RPC<br><span id=\"sumRPC\"></span></th><th class=\"dt-body-right\">Total Revenue<br><span id=\"sumRev\"></span></th><th class=\"dt-body-right\">Total Spend<br><span id=\"sumSpend\"></span></th><th class=\"dt-body-right\">Profit<br><span id=\"sumProfit\"></span></th><th  class=\"dt-body-right\">ROAS<br><span id=\"sumROAS\"></span></th><th class=\"dt-body-right\">Days w/Traffic</tr></thead><tbody>";

		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {


			$dwt = $result->dwt;
			$ctr = 0;
			$roas = 0;
			$profit = 0.00;
			$spend = 0.00;
			$revenue = 0.00;

			$revenue = floatval($result->total_revenue);
			if ( empty(floatval($result->spend)) ) {
				$roas = 0;
			} else {
				$spend = floatval($result->spend);
				//$roas = (floatval($revenue)-$spend)/floatval($spend);
				$roas = (floatval($revenue))/floatval($spend);
			}
			$roas = round($roas, 2);
			//$profit = floatval($revenue) - floatval($spend);
			$profit = floatval($result->profit);

			if ($result->total_imps > 0 && $result->rt_click_cnt > 0){
				$ctr = $result->rt_click_cnt / $result->total_imps;
			} elseif ($result->total_imps > 0 && $result->total_clicks > 0){
				$ctr = $result->total_clicks / $result->total_imps;
			}

			$ctr = number_format($ctr*100,2,'.', '');
			$spend = number_format($spend,2,'.', '');
			$revenue = number_format($result->total_revenue,2,'.', '');
			$profit = number_format($profit,2,'.', '');
			$rowstring = "<tr><td><a href=\"javascript:openhome(".$result->traffic_account_id.");\">".$result->taname."</a></td><td><a href\"#\" class=\"kwmodallink\" style=\"cursor: pointer;\" data-toggle=\"modal\" data-target=\"#kwmodalpanel\" data-rgid=\"".$result->rev_group_id."\" data-rgname=\"".$result->rgname."\" >".$result->rgname."</a></td><td align=\"right\">".number_format($result->total_imps,0)."</td><td align=\"right\">".number_format($result->rt_click_cnt,0)."</td><td align=\"right\">".number_format($result->total_clicks,0)."</td><td align=\"right\">".$ctr."%</td><td align=\"right\">".$result->avg_rpi."</td><td align=\"right\">".$result->avg_rpc."</td><td align=\"right\">".$revenue."</td><td align=\"right\">".$spend."</td><td align=\"right\">".$profit."</td><td align=\"right\">".$roas."</td><td align=\"right\">".$dwt."</td></tr>";
			if ($fltrDwt != "") {
				if ($fltrDwtComp== 'gt') {
					if ($dwt >= intval($fltrDwt)) {
						$todaylist .= $rowstring;
					}
				} else {
					if ($dwt <= intval($fltrDwt)) {
						$todaylist .= $rowstring;
					}
				}
			} else {
				$todaylist .= $rowstring;
			}
		}
		$todaylist .= "</tbody></table>";

		return $todaylist;
		}

	function getBreakdown(Request $request) {
		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$today = $end;
		$yesterday = $start;



		$today1 = strtotime($today);
		$previous_week = strtotime("-1 week +1 day",$today1);
		$trueyesterdaySTT = strtotime("-1 day",$today1);

		$start_week = strtotime("last sunday midnight",$previous_week);
		$end_week = strtotime("next saturday",$start_week);

		$start_week = date("Y-m-d",$start_week);
		$end_week = date("Y-m-d",$end_week);

		$trueyesterday = date("Y-m-d",$trueyesterdaySTT);
		$aryLastWeekRPI = array();
		$aryYesterdayRPI = array();

		$aryLastWeekRPC = array();
		$aryYesterdayRPC = array();


		// LAST WEEK FIRST
		/*
		$sql = "select r.id, r.name as rev_group_name, ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS avg_rpi,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS avg_rpc
			from metric_by_day m
			left join rev_group r on r.id = m.rev_group_id

			where DATE >= '".$start_week."'
			AND DATE <= '".$end_week."'";
		if ($admin == false) {
			$sql .= " and r.user_id = ".$request->session()->get('user_id');
		}
		$sql .= " group BY r.id, rev_group_name";
		*/
		// @FIXED user_id sometimes blank (if user not logged in, not a critical bug but eh)
		$sql = "select r.id, r.name as rev_group_name, ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS avg_rpi,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS avg_rpc
			from metric_by_day m
			left join rev_group r on r.id = m.rev_group_id

			where DATE >= '".qnq($start)."'
			AND DATE <= '".qnq($end)."'";
		if ($admin == false) {
			$sql .= " and r.user_id = ".intval($request->session()->get('user_id'));
		}
		$sql .= " group BY r.id, rev_group_name";
		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {
			$aryLastWeekRPI[$result->id] = $result->avg_rpi;
			$aryLastWeekRPC[$result->id] = $result->avg_rpc;
		}

		// END LAST WEEK

		// YESTERDAY
		/*
		$sql = "select r.id, r.name as rev_group_name, ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS avg_rpi,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS avg_rpc
			from metric_by_day m
			left join rev_group r on r.id = m.rev_group_id

			where DATE = '".$yesterday."' ";
		if ($admin == false) {
			$sql .= " and r.user_id = ".$request->session()->get('user_id');
		}
		$sql .= " group BY r.id, rev_group_name";
		*/
		$sql = "select r.id, r.name as rev_group_name, ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS avg_rpi,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS avg_rpc
			from metric_by_day m
			left join rev_group r on r.id = m.rev_group_id

			where DATE = '".$trueyesterday."' ";
		if ($admin == false) {
			$sql .= " and r.user_id = ".intval($request->session()->get('user_id'));
		}
		$sql .= " group BY r.id, rev_group_name";

		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {
			$aryYesterdayRPI[$result->id] = $result->avg_rpi;
			$aryYesterdayRPC[$result->id] = $result->avg_rpc;
		}

		// END YESTERDAY

		// TODAY
		$todaylist = "<header class=\"panel-heading\"><h2 class=\"panel-title\">Breakdown for the previous week starting ".$start."</h2></header><table class=\"table table-condensed table-striped table-bordered table-hover\" id=\"breakdownTable\">";
		$todaylist .= "<thead><tr><th>Rev Group</th><th align=\"right\">RPI ".date("n/j",strtotime($start))." to ".date("n/j",strtotime($end))."</th><th align=\"right\">RPC ".date("n/j",strtotime($start))." to ".date("n/j",strtotime($end))."</th><th align=\"right\">RPI ".date("n/j",strtotime($trueyesterday))."</th><th align=\"right\">RPC ".date("n/j",strtotime($trueyesterday))."</th><th align=\"right\">RPI ".date("n/j",strtotime($today))."</th><th align=\"right\">RPC ".date("n/j",strtotime($today))."</th></tr></thead><tbody>";
		$sql = "select r.id, r.name as rev_group_name, ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS avg_rpi,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS avg_rpc
			from metric_by_day m
			left join rev_group r on r.id = m.rev_group_id
			where DATE = '".qnq($today)."' ";
		if ($admin == false) {
			$sql .= " and r.user_id = ".intval($request->session()->get('user_id'));
		}
		$sql .= " group BY r.id, rev_group_name ORDER BY ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) desc";
		$results = DB::select( DB::raw($sql) );
		$lastweekrpi = 0.00;
		$lastweekrpc = 0.00;
		$yesterdayrpi = 0.00;
		$yesterdayrpc = 0.00;
		$todayrpi = 0.00;
		$todayrpc = 0.00;
		foreach ($results as $result) {
			if (array_key_exists($result->id,$aryLastWeekRPI)) { $lastweekrpi = number_format($aryLastWeekRPI[$result->id],2); } else {$lastweekrpi = "0.00";}
			if (array_key_exists($result->id,$aryLastWeekRPC)) { $lastweekrpc = number_format($aryLastWeekRPC[$result->id],2); } else {$lastweekrpc = "0.00";}
			if (array_key_exists($result->id,$aryYesterdayRPI)) { $yesterdayrpi = number_format($aryYesterdayRPI[$result->id],2); } else {$yesterdayrpi = "0.00";}
			if (array_key_exists($result->id,$aryYesterdayRPC)) { $yesterdayrpc = number_format($aryYesterdayRPC[$result->id],2); } else {$yesterdayrpc = "0.00";}
			$todayrpi = number_format($result->avg_rpi,2);
			$todayrpc = number_format($result->avg_rpc,2);
			if ($lastweekrpi > 0 || $lastweekrpc > 0 || $yesterdayrpi > 0 || $yesterdayrpc > 0 || $todayrpi > 0 || $todayrpc > 0) {
				$todaylist .= "<tr><td><a href\"#\" class=\"kwmodallink\" style=\"cursor: pointer;\" data-toggle=\"modal\" data-target=\"#kwmodalpanel\" data-rgid=\"".$result->id."\" data-rgname=\"".$result->rev_group_name."\" >".substr($result->rev_group_name,0,50)."</a></td><td align=\"right\">".$lastweekrpi."</td><td align=\"right\">".$lastweekrpc."</td><td align=\"right\">".$yesterdayrpi."</td><td align=\"right\">".$yesterdayrpc."</td><td align=\"right\">".$todayrpi."</td><td align=\"right\">".$todayrpc."</td></tr>";
			}
		}

		// END TODAY
		$todaylist .= "</tbody></table>";

		return $todaylist;
	}

	function get5dayTQ(Request $request) {
		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$yesterday = $start;
		$today = $end;

		$yesterday1 = strtotime($yesterday);
		$today = date("Y-m-d",strtotime("-1 days",$yesterday1));
		$minus1day = date("Y-m-d",strtotime("-2 days",$yesterday1));
		$minus2day = date("Y-m-d",strtotime("-3 days",$yesterday1));
		$minus3day = date("Y-m-d",strtotime("-4 days",$yesterday1));
		$minus4day = date("Y-m-d",strtotime("-5 days",$yesterday1));

		$day0 = "";
		$day1 = "";
		$day2 = "";
		$day3 = "";
		$day4 = "";

		$keyword = "";

		$sql = "SELECT m.date, s.hostname, p.keyword, m.rev_group_id, rg.name as rev_group_name,
			ROUND(SUM(revenue_est)/SUM(unq_imp_cnt),3) AS AVG_RPI,
			ROUND(SUM(revenue_est)/SUM(rev_click_cnt_est),2) AS AVG_RPC,
			ROUND(SUM(rev_click_cnt_est)/SUM(unq_imp_cnt),3) AS AVG_CTR,
			SUM(rev_click_cnt_est) AS Total_click_cnt,
			SUM(revenue_est) AS Total_Rev,
			ROUND(SUM(quality * rev_click_cnt_est)/SUM(IF(quality > 0, rev_click_cnt_est, 0)),2) AS AVG_WeightedTypeTQ
		from metric m
		left join placement p on m.placement_id = p.id
		left join site s on p.site_id = s.id
		LEFT JOIN rev_group rg ON rg.id = m.rev_group_id
		where DATE >= '".qnq($today)."' - INTERVAL 4 DAY
		AND DATE <= '".qnq($today)."' and p.resolution_id = 1 ";
		if ($admin == false) {
			$sql .= " AND p.user_id = ".intval($request->session()->get('user_id'));
		}
		$sql .= " group by m.date, s.hostname, p.keyword, m.rev_group_id, rg.name
		having
			 AVG_WeightedTypeTQ <= 4
		order by s.hostname, p.keyword, m.date";
		$results = DB::select( DB::raw($sql) );
		$todaylist = "<header class=\"panel-heading\"><h2 class=\"panel-title\">Low TQ Keywords starting on ".$today ."</h2></header><table id=\"lowtqtable\" class=\"table table-condensed table-striped table-bordered table-hover\">";
		$todaylist .= "<thead><tr><th>Hostname</th><th>Rev Group</th><th>Keyword</th><th align=\"right\">-1</th><th align=\"right\">-2</th><th align=\"right\">-3</th><th align=\"right\">-4</th><th align=\"right\">-5</th></tr></thead><tbody>";
		foreach ($results as $result) {
			if ($keyword != $result->keyword) {
				$day0 = "";
				$day1 = "";
				$day2 = "";
				$day3 = "";
				$day4 = "";

				$keyword = $result->keyword;
			}
			//echo $keyword.": ".$result->date;
			if ($day0 == "" && $result->date == $today) { $day0 = $result->AVG_WeightedTypeTQ; }
			if ($day1 == "" && $result->date == $minus1day) { $day1 = $result->AVG_WeightedTypeTQ; }
			if ($day2 == "" && $result->date == $minus2day) { $day2 = $result->AVG_WeightedTypeTQ; }
			if ($day3 == "" && $result->date == $minus3day) { $day3 = $result->AVG_WeightedTypeTQ; }
			if ($day4 == "" && $result->date == $minus4day) { $day4 = $result->AVG_WeightedTypeTQ; }
			/*
			echo " | day0: ".$today.", value: ".$day0." ";
			echo " | day1: ".$minus1day.", value: ".$day1." ";
			echo " | day2: ".$minus2day.", value: ".$day2." ";
			echo " | day3: ".$minus3day.", value: ".$day3." ";
			echo " | day4: ".$minus4day.", value: ".$day4."<br>";
			*/
			if ($day0 != "" && $day1 != "" && $day2 != "" && $day3 != "" && $day4 != "") {
				$todaylist .= "<tr><td>".$result->hostname."</td><td><a href\"#\" class=\"kwmodallink\" style=\"cursor: pointer;\" data-toggle=\"modal\" data-target=\"#kwmodalpanel\" data-rgid=\"".$result->rev_group_id."\" data-rgname=\"".$result->rev_group_name."\" >".$result->rev_group_name."</a></td><td>".$result->keyword."</td><td align=\"right\">".$day0."</td><td align=\"right\">".$day1."</td><td align=\"right\">".$day2."</td><td align=\"right\">".$day3."</td><td align=\"right\">".$day4."</td></tr>";
			}
		}
		$todaylist .= "</tbody></table>";

		return $todaylist;
	}

	function getScrubByRG(Request $request) {

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		$ta = Input::get('ta');

		$sql = "SELECT site.id AS siteid, rg.id AS rgid, rg.name, SUM(mbrg.rt_click_cnt) AS rt_clicks, SUM(mbrg.rev_click_cnt_est) AS rev_clicks, (ROUND( SUM(rev_click_cnt_est)/SUM(rt_click_cnt), 4)) AS paid_rate
			FROM site
			LEFT JOIN rev_group rg ON rg.site_id = site.id
			LEFT JOIN metric_by_revgroup mbrg ON mbrg.rev_group_id = rg.id
			WHERE site.id = ".$ta." AND mbrg.DATE >= '".$start."' and mbrg.DATE <='".$end."' ";
		if ($admin == false) {
			$sql .= "and rg.user_id = ".$request->session()->get('user_id');
		}
		$sql .= "
			GROUP BY site.id, rg.id, rg.name
			HAVING rt_clicks > 0
			ORDER BY (ROUND( SUM(rev_click_cnt_est)/SUM(rt_click_cnt), 4)) DESC";
		/*
		$sql = "SELECT mrg.date as this_date, rev_group.name, rev_group.id,
			SUM(rev_click_cnt_est) as rev_clicks,
			SUM(rt_click_cnt) as rt_clicks,
		   (ROUND( SUM(rev_click_cnt_est)/SUM(rt_click_cnt), 4)) AS paid_rate
		FROM metric_by_revgroup mrg
		LEFT JOIN rev_group ON rev_group.id=mrg.rev_group_id
		WHERE mrg.DATE between '".$start."' and '".$end."' ";
		if ($admin == false) {
			$sql .= "and rev_group.user_id = ".$request->session()->get('user_id');
		}
		$sql .= " AND rev_group.traffic_account_id = ".$ta ."
		GROUP BY rev_group.name, this_date
		ORDER BY (ROUND( SUM(rev_click_cnt_est)/SUM(rt_click_cnt), 4)) DESC";
		*/

		// GET YESTERDAY SCRUB RATE - TOP 10
		$todaylist = "<strong>Scrub Rate by Rev Group for ".$start." to ".$end."</strong><table class=\"table table-condensed table-striped table-bordered table-hover\" id=\"modalScrub\">";
		$todaylist .= "<thead><tr><th>Rev Group</th><th align=\"right\">RT Clicks</th><th align=\"right\">Paid Clicks</th><th align=\"right\">Scrub Rate</th></tr></thead></tbody>";


		$results = DB::select( DB::raw($sql) );
		foreach ($results as $result) {
			/*
			$scrubrate = number_format($result->paid_rate,4);
			if ($scrubrate != 0.00) {
				$scrubrate = ($scrubrate - 1)* -100;
			}
			*/

			$rtclicks = $result->rt_clicks;
			$rtclicks = number_format($rtclicks);
			$revclicks = number_format($result->rev_clicks);
			if ($rtclicks > 0) {
				$scrubrate = ((floatval($rtclicks)-floatval($revclicks))/floatval($rtclicks))*100;
				$scrubrate = number_format($scrubrate,2);
			} else {
				$scrubrate = "0.00";
			}
			$todaylist .= "<tr><td><a href\"#\" class=\"kwmodallink\" style=\"cursor: pointer;\" data-toggle=\"modal\" data-target=\"#kwmodalpanel\" data-rgid=\"".$result->rgid."\" data-rgname=\"".$result->name."\" >".$result->name."</a></td><td align=\"right\">".$rtclicks."</td><td align=\"right\">".$revclicks."</td><td align=\"right\">".$scrubrate."%</td></tr>";
		}
		$todaylist .= "</tbody></table>";

		return $todaylist;

	}

	function setTrafficID(Request $request) {
		if( !empty(Input::get('traffic_id'))) {
			$request->session()->set('traffic_account_id', Input::get('traffic_id'));
		}

		return Input::get('traffic_id');
	}

	function getKW(Request $request) {

		if( !empty($request->session()->get('start_date')) ) {  $start = $request->session()->get('start_date'); } else { $start = date("Y-m-d",strtotime("-2 days")); }
		if( !empty($request->session()->get('end_date')) ) {  $end = $request->session()->get('end_date'); } else { $end = date("Y-m-d",strtotime("-1 days")); }

		if( !empty(Input::get('startdate'))) { $start = Input::get('startdate'); $request->session()->set('start_date', $start);}
		if( !empty(Input::get('enddate'))) { $end = Input::get('enddate'); $request->session()->set('end_date', $end);}

		$admin = false;
		if ($request->session()->get('role') != null) {
		  if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
			$admin = true;
		  } else {
			$admin = false;
		  }
		}

		$rev_group_id = Input::get('rgid');
		$rev_group_name = Input::get('rgname');
		$active_only = Input::get('active');
		//if( !empty(Input::get('active'))) {$active_only = Input::get('active'); } else {$active_only = 1; }

		$activecheck = ' checked="checked" ';
		if( !empty(Input::get('res'))) {$resolution_id = Input::get('res'); } else {$resolution_id = "ALL"; }

		$res_where = "";
		if($active_only == 1){
		  $res_where .= ' AND placement.active = 1 ';
		  $activecheck = "";
		}
		if($resolution_id != null && $resolution_id != "ALL"){
		  $res_where .= "and placement.resolution_id = " . $resolution_id;
		}

		$sql= "SELECT placement.id as placement_id,
                  placement.keyword,
                  placement.weight,
                  placement.lander_id,
                  placement.resolution_id,
                  placement.created_at,
                  SUM(rt_click_cnt) AS rt_click_cnt,
                  SUM(unq_imp_cnt) AS rt_imp_cnt,
                  AVG(click_limit) AS click_limit,
                  SUM(rev_click_cnt_est) AS rev_click_cnt_est,
                  SUM(revenue_est) AS revenue_est,
                  ROUND(SUM(quality * rev_click_cnt_est)/SUM(IF(quality > 0, rev_click_cnt_est, 0)),2) AS tq,
                  placement.active as active,
                  rev_group.traffic_account_id as traffic_account_id,
                  rev_group.entry_url as entry_url,
                  rev_group.id as rgid
            FROM
                rev_group
                    LEFT JOIN
                placement ON placement.rev_group_id = rev_group.id
                    LEFT JOIN
                traffic_account on traffic_account.id = rev_group.traffic_account_id
                    LEFT JOIN
                metric_by_day ON metric_by_day.placement_id = placement.id and metric_by_day.date BETWEEN "
		  . q($start) . " AND " . q($end)
		  . " WHERE placement.rev_group_id = " . q($rev_group_id) . $res_where
		  . " group by keyword,rev_group.traffic_account_id "
		  . " , rev_group.entry_url, rev_group.id "
		  . " order by keyword,placement_id ";
		//echo "<!--- YOBA " . DB::raw($sql)  . "---><br/>";
		$results = DB::select( DB::raw($sql));

		$resolution_text = "ALL";
		switch ($resolution_id) {
			case "1":
				$resolution_text = "Phone";
				break;
			case "2":
				$resolution_text = "Desktop";
				break;
		}

		$rescontent = '<option value="1" ';
		if ($resolution_id == 1) { $rescontent .= 'selected="selected"'; }
		$rescontent.= '>Phone</option><option value="2" ';
		if ($resolution_id == 2) { $rescontent .= 'selected="selected"'; }
		$rescontent.= '>Desktop</option><option value="ALL" ';
		if ($resolution_id == 'ALL') { $rescontent .= 'selected="selected"'; }
		$rescontent.= '>ALL</option>';

		$content = '<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title myModalLabelKeywords" id="myModalLabelKeywords">Keywords for Revenue Group Id: '.$rev_group_id.'&nbsp;&nbsp;-&nbsp;&nbsp;<span style="color:#4C8EEC;">'.$rev_group_name.'</span>&nbsp;&nbsp;-&nbsp;&nbsp;Device(s):&nbsp;'.$resolution_text.' showing.   '.$start.' to '.$end.'</h4>
							<input type="hidden" id="kwrgid" value="'.$rev_group_id.'"><input type="hidden" id="kwrgname" value="'.$rev_group_name.'">
							<div id="kwsummary"><div style="width:100%; height: 70px; background-color: #d4edda;">
									<table style="width:100%;" cellpadding="15">
										<tbody><tr>
											<td align="center" valign="middle" height="70">
												<table>
													<tbody><tr>
														<td class="tdpadding" valign="baseline"><span class="kwBigNumber">$24,111.09</span><br><span class="kwLittleNumber">SPEND</span></td>
														<td class="tdpadding" valign="baseline"><span class="kwBigNumber">$28,124.35</span><br><span class="kwLittleNumber">REVENUE</span></td>
														<td class="tdpadding" valign="baseline"><span class="kwBigNumber" style="color:green;">$4,013.26</span><br><span class="kwLittleNumber">PROFIT</span></td>
														<td class="tdpadding" valign="baseline"><span class="kwBigNumber" style="color:green;">1.17</span><br><span class="kwLittleNumber">ROAS</span></td>
														<td class="tdpadding" valign="baseline"><span class="kwBigNumber" style="color:green;">69</span><br><span class="kwLittleNumber">AGE</span></td>
														<td class="tdpadding"><span class="dynamicbar2"><canvas width="158" height="40" style="display: inline-block; width: 158px; height: 40px; vertical-align: top;"></canvas></span></td>
													</tr>
												</tbody></table>
											</td>
										</tr>
									</tbody></table>
								</div>	</div>


						  <div style="padding-left:20px;width:100%;">

							<table class="positionOnly">
							  <tbody><tr class="positionOnly">
								<!--<td class="positionOnly">Start Date:</td>
								<td class="positionOnly"><input class="form-control datelength keywordsStartDate hasDatepicker" name="keywordsStartDate" id="keywordsStartDate" value="2017-03-07"></td>

								<td class="positionOnly">End Date: </td>
								<td class="positionOnly"><input class="form-control datelength keywordsEndDate hasDatepicker" name="keywordsEndDate" id="keywordsEndDate" value="2017-03-07"></td>

								<td class="positionOnly">Date Range: </td>
								<td class="positionOnly">
								  <select id="date_rangeKeywordsModal" class="asPhoneKeywords" name="date_rangeKeywordsModal"><option value="0" selected="selected">Select a date range</option><option value="2019-04-16|2019-04-16">Today</option><option value="2019-04-15|2019-04-15">Yesterday</option><option value="2019-04-14|2019-04-16">This week</option><option value="2019-04-10|2019-04-16">Last 7 Days</option><option value="2019-04-07|2019-04-13">Last week</option><option value="2019-04-03|2019-04-16">Last 14 days</option><option value="2019-03-18|2019-04-16">Last 30 days</option><option value="2019-04-01|2019-04-16">Month To Date</option><option value="2019-03-01|2019-03-31">Last Month</option></select>
								</td>-->
								<td class="positionOnly">Resolution: </td>
								<td class="positionOnly"><select class="asPhoneKeywords" name="kwresolution" id="kwresolution" onchange="refreshKWPanel();">
									'.$rescontent.'
								  </select>        &nbsp;
								</td><td class="positionOnly"><input id="show_inactive_keywords" style="font-size: 13px;" name="show_inactive_keywords" type="checkbox" '.$activecheck.'
								onclick="refreshKWPanel();"></td>
								<td class="positionOnly"><span style="font-size: 13px;vertical-align:middle;">Show Inactive</span></td>
								<td class="positionOnly"><input id="autoweighter" style="font-size: 13px;" name="autoweighter" type="checkbox" checked="checked" ></td>
								<td class="positionOnly"><span style="font-size: 13px;vertical-align:middle;">Use AutoWeighter</span></td>
							  </tr>
							</tbody></table>
						  </div>

						  <div id="keywordsInnerDiv" class="modal-body loading">

							<input type="hidden" name="viewKeyWordsRevGroup" id="viewKeyWordsRevGroup" value="'.$rev_group_id.'">
							<div id="KWdatatable" class="span4" style="width:100% !important;">

							  <div id="kwdt_wrapper" class="form-inline dt-bootstrap no-footer">
								<div class="row">
									<div class="col-sm-12">
										<table id="dashboardkwdt" class="display table-striped no-footer table-hover" cellspacing="0" width="100%" role="grid" aria-describedby="kwdt_info" style="width: 100%;">
											<thead>
												<tr role="row">
													<th aria-controls="kwdt" style="width: 30%;">Keyword<br> Totals and averages:</th>
													<th class="dt-body-right " aria-controls="kwdt"  style="width: 6%;">RT_imps<br><span id="kwtotal_imps">0</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"  style="width: 6%;">RT_Clicks<br><span id="kwtotal_rtclicks">0</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"  style="width: 6%;">Rev Clicks<br><span id="kwtotal_revclicks">0</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"   style="width: 6%;">CTR<br><span id="kwtotal_ctr">0%</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"  style="width: 6%;">RPI<br><span id="kwtotal_rpi">$ 0.00</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"   style="width: 6%;">RPC<br><span id="kwtotal_rpc">$ 0.00</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"   style="width: 6%;">Rev<br><span id="kwtotal_rev">$0.00</span></th>
													<th class="dt-body-right sorting" tabindex="0" aria-controls="kwdt"  style="width: 6%;">TQ</th>
													<th class="centercolumnhome dt-body-right sorting_disabled"  aria-label="Weight " style="width: 6%;">Weight<br><span class="kwtot_8"></span></th>
													<th class="centercolumnhome sorting_disabled"  style="width: 6%;">Limit<br><span class="tkwtot_9"></span></th>
													<th class="dt-body-center centercolumnhome sorting_disabled"  style="width: 4%;">Active</th>
													<th class="dt-body-center centercolumnhome"  aria-label="Reset" style="width: 5%;">Reset</th>
													<th class="dt-body-center centercolumnhome"  aria-label="" style="width: 1%;"><input onclick="checkAllOrNoneKeywords($(this))" type="checkbox" id="keywordSelectAll"></th>
												</tr>
											</thead>
											<tbody>';

								foreach($results as $result){
									$ctr = 0.00;
									$rpi = 0.00;
									if ($result->rt_imp_cnt == "") { $rt_imps = 0; } else { $rt_imps = $result->rt_imp_cnt; }
									if ($result->rt_click_cnt == "") { $rt_clicks = 0; } else { $rt_clicks = $result->rt_click_cnt; }
									if ($result->rev_click_cnt_est == "") { $rev_clicks = 0; } else { $rev_clicks = $result->rev_click_cnt_est; }
									if ($result->rt_imp_cnt > 0 && $result->rt_click_cnt > 0){
										$ctr = $result->rt_click_cnt / $result->rt_imp_cnt;
									} elseif ($result->rt_imp_cnt > 0 && $result->rev_click_cnt_est > 0){
										$ctr = $result->rev_click_cnt_est / $result->rt_imp_cnt;
									}
									if ($result->rt_imp_cnt > 0) {$rpi = $result->revenue_est/$result->rt_imp_cnt; } else {$rpi = 0.00; }
									if ($result->rev_click_cnt_est > 0) {$rpc = $result->revenue_est/$result->rev_click_cnt_est; } else {$rpc = 0.00; }

									$ctr = $ctr * 100;
									$active = "yes";
									$changeActive = "yes";
									if ($result->active == 0) {$active = "no"; $changeActive = "no";}

									$kwdrweight = "<select id=\"kwdrweight".$result->placement_id."\" onchange=\"updateweight(".$result->placement_id.",'".$rev_group_id."','".$result->keyword."','".$resolution_id."');\">";
									if ($result->weight == 0) {
										$kwdrweight.= "<option value=\"-1\"";
										if ($result->weight == 0) { $kwdrweight.= " selected "; }
										$kwdrweight.= ">Auto 0</option>";
									}
									for ($x=100;$x>0;$x-=5) {
									  $kwdrweight.= "<option value=\"".$x."\"";
									  if ($x == $result->weight) { $kwdrweight.= " selected "; }
									  $kwdrweight.= ">".$x."</option>";
									}
									$kwdrweight.= "</select>";
								  //}
								  // CREATE SELECT FOR KEYWORD LIMIT
								  $kwdrlimit = "<select id=\"kwdrlimit".$result->placement_id."\"  onchange=\"updatelimit(".$result->placement_id.",'".$rev_group_id."','".$result->keyword."');\">";
								  for ($x=0;$x<=100;$x+=5) {
									$kwdrlimit.= "<option value=\"".$x."\"";
									if ($x == number_format($result->click_limit, 0, ".", ",")) { $kwdrlimit.= " selected "; }
									$kwdrlimit.= ">".$x."</option>";
								  }
								  $kwdrlimit.= "</select>";
									$content .= '<tr>
													<td>'.$result->keyword.'</td>
													<td class="dt-body-right">'.$rt_imps.'</td>
													<td class="dt-body-right">'.$rt_clicks.'</td>
													<td class="dt-body-right">'.$rev_clicks.'</td>
													<td class="dt-body-right">'.number_format($ctr,2).'%</td>
													<td class="dt-body-right">$'.number_format($rpi,2).'</td>
													<td class="dt-body-right">$'.number_format($rpc,2).'</td>
													<td class="dt-body-right">'.number_format($result->revenue_est,2).'</td>
													<td class="dt-body-right">'.$result->tq.'</td>
													<td class="dt-body-right">'.$kwdrweight.'</td>
													<td class="dt-body-right">'.$kwdrlimit.'</td>
													<td class="dt-body-center centercolumnhome"><a href="javascript:changeKWStatus('.$rev_group_id.',\''.$result->keyword.'\',\''.$changeActive.'\');" href="#">'.$active.'</a></td>
													<td class="dt-body-center centercolumnhome"><a href="javascript:resetKW('.$rev_group_id.',\''.$result->keyword.'\');" href="#">Reset</a></td>
													<td class="dt_body-center centercolumnhome"><input class="keywordsCheckboxes" type="checkbox" value="'.$result->keyword.'|'.$rev_group_id.'|'.$resolution_id.'"></td>
												</tr>';

								}
									$content .= '</tbody>
										</table>
									</div>
								</div>
						  </div>
							<div id="tabs">
								<ul>
									<li><a href="#tabs-1">Historic Keyword RPC</a></li>
									<li><a href="#tabs-2">Add Keywords</a></li>
									<li><a href="#tabs-3">Notes</a></li>
								</ul>
								<div id="tabs-1">
									<div id="suggestedKeywordsListDiv">
										<table style="width:100%;">
											<tbody>
												<tr>
													<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;"><input class="addSuggest" id="addSuggest_0" type="checkbox" value="undefined"><label for="addSuggest_0"><span style="vertical-align: top;">&nbsp;&nbsp;$undefined&nbsp;&nbsp;undefined</span> (undefined)</label></td>
													<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;"><input class="addSuggest" id="addSuggest_1" type="checkbox" value="undefined"><label for="addSuggest_1"><span style="vertical-align: top;">&nbsp;&nbsp;$undefined&nbsp;&nbsp;undefined</span> (undefined)</label></td>
													<td style="padding: 0px 0px 0px 6px;vertical-align: bottom;"><input class="addSuggest" id="addSuggest_2" type="checkbox" value="undefined"><label for="addSuggest_2"><span style="vertical-align: top;">&nbsp;&nbsp;$undefined&nbsp;&nbsp;undefined</span> (undefined)</label>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<button style="margin-top: 10px; display: none;" id="keywordSuggestionButton" type="button" class="btn btn-primary btn-xs historicKeywords">Show Historical RPCs</button>
									<button style="margin-top: 10px;" id="addHistoricKeywordsButton" type="button" class="btn btn-primary btn-xs historicKeywords">Add Selected Keywords</button>
								</div>
								<div id="tabs-2">
									<span id="keywordNote"><strong><i style="color:orangered">Keywords added will only be added to the selected resolution at top of screen</i></strong></span><br>
									<span id="modalKeyword3CharacterAlert" class="alertLexo" style="display:none;">All keywords must be longer than 3 characters.</span><br>
									<label for="keyword(s):" class="asFilterLabelRight">Keyword(s):</label>
									<textarea autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="form-control" id="addrevgroupform_keyword" name="keyword" cols="70" rows="7"></textarea><br>
									<button style="margin-top:10px" id="KW_modalAddKeywords_keywordPage" type="button" class="btn btn-primary btn-xs" onclick="KW_addkeywords();">Add Keywords</button>
									<div style="float:right;position:relative;top: 10px;"><label for="kwweight" class="asFilterLabelRight">Weight: </label><select id="kwweight">
									  <option value="100">100</option><option value="95">95</option><option value="90">90</option><option value="85">85</option><option value="80">80</option><option value="75">75</option><option value="70">70</option><option value="65">65</option><option value="60">60</option><option value="55">55</option><option value="50">50</option><option value="45">45</option><option value="40">40</option><option value="35">35</option><option value="30">30</option><option value="25">25</option><option value="20">20</option><option value="15">15</option><option value="10" selected="">10</option><option value="5">5</option>
									</select> <label for="kwlimit" class="asFilterLabelRight">Limit: </label><select id="kwlimit"><option value="0" selected="selected">0</option><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option><option value="60">60</option><option value="65">65</option><option value="70">70</option><option value="75">75</option><option value="80">80</option><option value="85">85</option><option value="90">90</option><option value="95">95</option><option value="100">100</option></select></div>

								</div>
								<div id="tabs-3">
									<!-- New section for NOTES -->
									<div id="kwdt_wrapper3" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<table cellpadding="5" style="border:1px">
											<tbody>
												<tr>
													<td class="bottom" style="vertical-align: top;padding-right: 10px;">
														<span id="keywordNote"><strong><i style="color:black">Log notes about this Revenue Group</i></strong></span><br>
														<span id="modalKeyword3CharacterAlert" class="alertLexo" style="display:none;">All keywords must be longer than 3 characters.</span><br>
														<label for="keyword(s):" class="asFilterLabelRight" style="width: 74px;"></label>
														<textarea class="form-control" id="rgnotes" name="rgnotes" cols="70" rows="7"></textarea><br>
														<button style="margin-top:10px" id="KW_modalAddNotes_keywordPage" type="button" class="btn btn-primary btn-xs" onclick="addNotes();">Add Notes</button>
													</td>
													<td rowspan="2" style="width:2px;background-color: #cccccc;border-collapse:collapse;padding:0px; "></td>
													<td rowspan="2" style="background-color: #ffffff;border-collapse: collapse;"></td>
													<td style="vertical-align: top; width:69%;">
														<div id="notesDiv"><label for="keyword(s):" class="asFilterLabelRight"><u>Notes</u></label></div>
														<div id="rgnotes_list" style="width:100%;height:150px;overflow-y: scroll;">
															<div class="table-responsive">
																<table id="tableNotes" class="table table-hover table-borderless" style="width:100%;">
																	<tbody>
																	</tbody>
																</table>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- End NOTES -->
								</div>
							</div>

						</div><!-- /.modal-content -->
					  ';

					return $content;

	}

	function getUserID(Request $request) {

		$user_id = $request->session()->get('user_id');
		return $user_id;

	}

    function hourly(Request $request) {
        $admin = false;
        if ($request->session()->get('role') != null) {
            if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
                $admin = true;
            } else {
                $admin = false;
            }
        }

        $returnTable = '<header class="panel-heading"><h2 class="panel-title">Hourly Data</h2></header>
        <table id="hourlyTable" class="table table-condensed table-striped table-bordered table-hover">
            <thead>
                <tr> 
                    <th>Name</th>
                    <th>Yesterday\'s RPC</th>
                    <th>Today\'s RPC</th>
                    <th>Yesterday\'s Revenue</th>
                    <th>Today\'s Revenue</th>
                    <th>Yesterday\'s CTR</th>
                    <th>Today\'s CTR</th>
                </tr>
            </thead>
            <tbody>';
        $sql = "SELECT user_id,traffic_account_name, rev_group_name
                     , sum(CASE WHEN date =  CURDATE() THEN revenue else 0 END) AS revenueToday
                     , sum(CASE WHEN date =  CURDATE()-1 THEN revenue else 0 END) AS revenueYesterday
                     , sum(CASE WHEN date =  CURDATE() THEN rpc else 0 END) AS rpcToday
                     , sum(CASE WHEN date =  CURDATE()-1 THEN rpc else 0 END) AS rpcYesterday
                     , sum(CASE WHEN date =  CURDATE() THEN ctr else 0 END) AS ctrToday
                     , sum(CASE WHEN date =  CURDATE()-1 THEN ctr else 0 END) AS ctrYesterday
                       
                  FROM hourlyData ";
        if ($admin === false) {
            $sql .= " where user_id = ".$request->session()->get('user_id');
        }
        $sql .= " GROUP BY user_id, traffic_account_name, rev_group_name ";

        $results = DB::select( DB::raw($sql));
        foreach ($results as $result) {
            $rpcYesterday = $result->rpcYesterday;
            $rpcToday = $result->rpcToday;
            $revenueToday = $result->revenueToday;
            $revenueYesterday = $result->revenueYesterday;
            $ctrToday = $result->ctrToday;
            $ctrYesterday = $result->ctrYesterday;

            if ($rpcToday == '') {$rpcToday = '0';}
            if ($rpcYesterday == '') {$rpcYesterday = '0';}
            if ($revenueToday == '') {$revenueToday = '0';}
            if ($revenueYesterday == '') {$revenueYesterday = '0';}
            if ($ctrToday == '') {$ctrToday = '0';}
            if ($ctrYesterday == '') {$ctrYesterday = '0';}

            $rpccolor = '#ffffff';
            $revenuecolor = '#ffffff';
            $ctrcolor = '#ffffff';

            $rpcFontcolor = '#000000';
            $revenueFontcolor = '#000000';
            $ctrFontcolor = '#000000';

            $rpcFontweight = '400';
            $revenueFontweight = '400';
            $ctrFontweight = '400';


            //if ($rpcToday > $rpcYesterday) {$rpccolor = '#e6ffee';}
            if ($rpcToday > $rpcYesterday) {$rpccolor = '#00cc66'; $rpcFontcolor = '#ffffff'; $rpcFontweight = 700;}
            if ($rpcToday < $rpcYesterday) {$rpccolor = '#ffb3d1';}

            //if ($revenueToday > $revenueYesterday) {$revenuecolor = '#e6ffee';}
            if ($revenueToday > $revenueYesterday) {$revenuecolor = '#00cc66';$revenueFontcolor = '#ffffff'; $rpcFontweight = 700;}
            if ($revenueToday < $revenueYesterday) {$revenuecolor = '#ffb3d1';}

            //if ($ctrToday > $ctrYesterday) {$ctrcolor = '#e6ffee';}
            if ($ctrToday > $ctrYesterday) {$ctrcolor = '#00cc66';$ctrFontcolor = '#ffffff'; $ctrFontweight = 700;}
            if ($ctrToday < $ctrYesterday) {$ctrcolor = '#ffb3d1';}

            $returnTable .= '
                    <tr>
                        <td><a href="#" title="'.$result->traffic_account_name.'->'.$result->rev_group_name.'">'.$result->rev_group_name.'</a></td>
                        <td>'.$rpcYesterday.'</td>
                        <td style="background-color: '.$rpccolor.';color: '.$rpcFontcolor.';font-weight: '.$rpcFontweight.';">'.$rpcToday.'</td>
                        <td>'.$revenueYesterday.'</td>
                        <td style="background-color: '.$revenuecolor.';color: '.$revenueFontcolor.';font-weight: '.$revenueFontweight.';">'.$revenueToday.'</td>
                        <td align="right">'.number_format($ctrYesterday,2).'%</td>
                        <td align="right" style="background-color: '.$ctrcolor.';color: '.$ctrFontcolor.';font-weight: '.$ctrFontweight.';">'.number_format($ctrToday,2).'%</td>
                    </tr>';
        }
        $returnTable .= '
            </tbody>
        </table>';

        return $returnTable;
    }

    function hourlyKW(Request $request) {
        $admin = false;
        if ($request->session()->get('role') != null) {
            if( strtolower($request->session()->get('role')) == strtolower("Admin") ){
                $admin = true;
            } else {
                $admin = false;
            }
        }

        $returnTable = '<header class="panel-heading"><h2 class="panel-title">Data by Keyword</h2></header>
        <table id="hourlyKWTable" class="table table-condensed table-striped table-bordered table-hover">
            <thead>
                <tr> 
                    <th>Keyword</th>
                    <th>Yesterday\'s RPC</th>
                    <th>Today\'s RPC</th>
                    <th>Yesterday\'s Revenue</th>
                    <th>Today\'s Revenue</th>
                    <th>Yesterday\'s CTR</th>
                    <th>Today\'s CTR</th>
                </tr>
            </thead>
            <tbody>';
        $sql = "SELECT keyword
                     , sum(CASE WHEN date =  CURDATE() THEN revenue else 0 END) AS revenueToday
                     , sum(CASE WHEN date =  CURDATE()-1 THEN revenue else 0 END) AS revenueYesterday
                     , sum(CASE WHEN date =  CURDATE() THEN rpc else 0 END) AS rpcToday
                     , sum(CASE WHEN date =  CURDATE()-1 THEN rpc else 0 END) AS rpcYesterday
                     , sum(CASE WHEN date =  CURDATE() THEN ctr else 0 END) AS ctrToday
                     , sum(CASE WHEN date =  CURDATE()-1 THEN ctr else 0 END) AS ctrYesterday
                       
                  FROM hourlyKWData ";
        if ($admin === false) {
            $sql .= " where user_id = ".$request->session()->get('user_id');
        }
        $sql .= " GROUP BY keyword";

        $results = DB::select( DB::raw($sql));
        foreach ($results as $result) {
            $rpcYesterday = $result->rpcYesterday;
            $rpcToday = $result->rpcToday;
            $revenueToday = $result->revenueToday;
            $revenueYesterday = $result->revenueYesterday;
            $ctrToday = $result->ctrToday;
            $ctrYesterday = $result->ctrYesterday;

            if ($rpcToday == '') {$rpcToday = '0';}
            if ($rpcYesterday == '') {$rpcYesterday = '0';}
            if ($revenueToday == '') {$revenueToday = '0';}
            if ($revenueYesterday == '') {$revenueYesterday = '0';}
            if ($ctrToday == '') {$ctrToday = '0';}
            if ($ctrYesterday == '') {$ctrYesterday = '0';}

            $rpccolor = '#ffffff';
            $revenuecolor = '#ffffff';
            $ctrcolor = '#ffffff';

            $rpcFontcolor = '#000000';
            $revenueFontcolor = '#000000';
            $ctrFontcolor = '#000000';

            $rpcFontweight = '400';
            $revenueFontweight = '400';
            $ctrFontweight = '400';


            //if ($rpcToday > $rpcYesterday) {$rpccolor = '#e6ffee';}
            if ($rpcToday > $rpcYesterday) {$rpccolor = '#00cc66'; $rpcFontcolor = '#ffffff'; $rpcFontweight = 700;}
            if ($rpcToday < $rpcYesterday) {$rpccolor = '#ffb3d1';}

            //if ($revenueToday > $revenueYesterday) {$revenuecolor = '#e6ffee';}
            if ($revenueToday > $revenueYesterday) {$revenuecolor = '#00cc66';$revenueFontcolor = '#ffffff'; $rpcFontweight = 700;}
            if ($revenueToday < $revenueYesterday) {$revenuecolor = '#ffb3d1';}

            //if ($ctrToday > $ctrYesterday) {$ctrcolor = '#e6ffee';}
            if ($ctrToday > $ctrYesterday) {$ctrcolor = '#00cc66';$ctrFontcolor = '#ffffff'; $ctrFontweight = 700;}
            if ($ctrToday < $ctrYesterday) {$ctrcolor = '#ffb3d1';}
            //00cc66
            $returnTable .= '
                    <tr>
                        <td>'.$result->keyword.'</td>
                        <td>'.$rpcYesterday.'</td>
                        <td style="background-color: '.$rpccolor.';color: '.$rpcFontcolor.';font-weight: '.$rpcFontweight.';">'.$rpcToday.'</td>
                        <td>'.$revenueYesterday.'</td>
                        <td style="background-color: '.$revenuecolor.';color: '.$revenueFontcolor.';font-weight: '.$revenueFontweight.';">'.$revenueToday.'</td>
                        <td align="right">'.number_format($ctrYesterday,2).'%</td>
                        <td align="right" style="background-color: '.$ctrcolor.';color: '.$ctrFontcolor.';font-weight: '.$ctrFontweight.';">'.number_format($ctrToday,2).'%</td>
                    </tr>';
        }
        $returnTable .= '
            </tbody>
        </table>';

        return $returnTable;
    }
}
