<?
require(dirname(dirname(__FILE__)).'/ui/includes/db.php');
require(dirname(dirname(__FILE__)).'/ui/includes/class.phpmailer.php');
require(dirname(__FILE__).'/ReportHelper.php');
date_default_timezone_set('UTC');
$path = dirname(__FILE__);

set_time_limit(0);
$report_subscription_table = "report_subscription";
$user_table = "user";

function checkCronLog($cronjob) {
	global $mysqli;

	$sql = "select * from cron_log where filename = '".$cronjob."' and detail = '".date("Y-m-d")."'";
	$result = $mysqli->query($sql);
	while ($row=$result->fetch_object()) {
		return $row->status;
	}
}

function setCronLog($cronjob,$status) {
	global $mysqli;

	$sql = "select * from cron_log where filename = '".$cronjob."' and detail = '".date("Y-m-d")."'";
	$result = $mysqli->query($sql);
	$numrows = mysqli_num_rows($result);
	$datefield = "start_dts";
	if ($status == 1) {
		$datefield = "end_dts";
	}
	if ($numrows > 0) {
		$row   = mysqli_fetch_row($result);
		$sql = "update cron_log set ".$datefield." = '".date("Y-m-d H:i:s")."' where id = ".$row[0];
		$mysqli->query($sql);
	} else {
		$sql = "insert into cron_log (filename,detail,status,date,".$datefield.") values ('".$cronjob."','".date("Y-m-d")."','".$status."','".date("Y-m-d")."','".date("Y-m-d H:i:s")."')";
		$mysqli->query($sql);
	}	

}

// RUN MAINTENANCE
$bypass = 1;

//count for any feeds with no stats
$missing_feeds=0;
$total_rev=0;

if ( isset($argv[1]) ) {
    $dt = DateTime::createFromFormat("Y-m-d", $argv[1]);
    if ($dt !== false) $date=$dt->format("Y-m-d");
    else die('bad date format');
    $bypass = 1;
    $starthour = date('Y-m-d', strtotime($date)) ." ".$argv[2].":00:00";
	$endhour = date('Y-m-d', strtotime($date)) ." ".$argv[2].":59:59";
	$lasthourdate = date('Y-m-d', strtotime($date));
    //$day1 = date("U", strtotime($date));
} else {
	date_default_timezone_set('UTC');
    $day1 = date("U");
	$date = date('Y-m-d', strtotime(' -1 day'));
	$starthour = date('Y-m-d H:00:00', strtotime('-1 hour'));
    $endhour = date('Y-m-d H:59:59', strtotime('-1 hour'));
    $lasthourdate = date('Y-m-d', strtotime('-1 hour'));
}


$yesterday = date('Y-m-d', strtotime($date .' -1 day'));
echo "For date: ".$date.PHP_EOL;

$start = $date;
$today = date('Y-m-d', strtotime($start .' +1 day'));

// STEP 1:
// POPULATE METRIC BY DAY
echo "---------------------------------------------------".PHP_EOL;
echo "STEP 1: POPULATE METRIC BY DAY".PHP_EOL;
echo "---------------------------------------------------".PHP_EOL;

$sql = "SET SESSION sql_mode = '';";

if (!$mysqli->query($sql)) printf("5 Error: %s\n", $mysqli->error);

$sql="DELETE from metric_by_day WHERE date='$date'";
if (!$mysqli->query($sql)) printf("14 Error: %s\n", $mysqli->error);

$sql="
	INSERT INTO metric_by_day (rev_group_id, placement_id, DATE, rt_click_cnt, rt_imp_cnt, unq_imp_cnt, rev_click_cnt_est, revenue_est, quality, quality2, scored_click_cnt)
	SELECT metric.rev_group_id, placement_id, DATE, SUM(rt_click_cnt) AS rt_click_cnt, SUM(rt_imp_cnt) AS rt_imp_cnt,
		SUM(unq_imp_cnt) AS unq_imp_cnt,
		SUM(rev_click_cnt_est) AS rev_click_cnt_est, SUM(revenue_est) AS revenue_est,
		IFNULL(ROUND(SUM(quality * rev_click_cnt_est)/SUM(IF(quality > 0, rev_click_cnt_est, 0)),2), 0) AS quality,
		quality2,
		SUM(IF(quality > 0, rev_click_cnt_est, 0)) AS scored_click_cnt
	FROM metric
	WHERE DATE='$date' AND rev_group_id > 0
	GROUP BY rev_group_id, DATE, placement_id";
if (!$mysqli->query($sql)) printf("24 Error: %s\n", $mysqli->error);

// TODAY
$sql="DELETE from metric_by_day WHERE date='$today'";
if (!$mysqli->query($sql)) printf("14 Error: %s\n", $mysqli->error);

$sql="
	INSERT INTO metric_by_day (rev_group_id, placement_id, DATE, rt_click_cnt, rt_imp_cnt, unq_imp_cnt, rev_click_cnt_est, revenue_est, quality, quality2, scored_click_cnt)
	SELECT metric.rev_group_id, placement_id, DATE, SUM(rt_click_cnt) AS rt_click_cnt, SUM(rt_imp_cnt) AS rt_imp_cnt,
		SUM(unq_imp_cnt) AS unq_imp_cnt,
		SUM(rev_click_cnt_est) AS rev_click_cnt_est, SUM(revenue_est) AS revenue_est,
		IFNULL(ROUND(SUM(quality * rev_click_cnt_est)/SUM(IF(quality > 0, rev_click_cnt_est, 0)),2), 0) AS quality,
		quality2,
		SUM(IF(quality > 0, rev_click_cnt_est, 0)) AS scored_click_cnt
	FROM metric
	WHERE DATE='$today' AND rev_group_id > 0
	GROUP BY rev_group_id, DATE, placement_id";
if (!$mysqli->query($sql)) printf("24 Error: %s\n", $mysqli->error);
// END POPULATE METRIC BY DAY


// STEP 2:
// EMAIL REPORTS
echo "---------------------------------------------------".PHP_EOL;
echo "STEP 2: EMAIL REPORTS ".PHP_EOL;
echo "---------------------------------------------------".PHP_EOL;

setCronLog("email_reports",2);
$sql_email="SELECT * FROM creds WHERE `type` = 'report'";
$result_email = $mysqli->query($sql_email);
$email = "";
$obj = mysqli_fetch_row($result_email);
$gmail_user = $obj[2];
$gmail_pw =  $obj[3];

echo "User: ".$gmail_user.PHP_EOL;
$mysqli_asu = $mysqli;

$requiredCount = 0;
$sql="SELECT * FROM variable_values WHERE variable = 'email_report_script_dependancies'";
$result = $mysqli_asu->query($sql);
$count_found = 0;
while ($obj = $result->fetch_object()) {
	$dependancies = unserialize($obj->value);
}
$dependancies = "'" . implode("','", $dependancies) . "'";
$dependancies_array = explode(",", $dependancies);
$requiredCount = count($dependancies_array);
$MidnightYesterday = (new DateTime("UTC"))->setTime(0,0)->sub(new DateInterval("P1D"));
$Yesterday = $MidnightYesterday->format("Y-m-d");
$sql="SELECT * FROM cron_log WHERE status = 1 AND detail='$Yesterday' AND filename IN ($dependancies)";
$result = $mysqli_asu->query($sql);
$count_found =0;
while ($obj = $result->fetch_object()) {
	$count_found++;
}
if ($count_found < $requiredCount)  {
	echo 'stats import incomplete for '. $Yesterday . "  There were only " . $count_found . " records of " . $requiredCount . " found.".PHP_EOL;
} else {
	//loop through all report subscriptions
	$sql="SELECT report_id, user_id,report.name, date_range FROM $report_subscription_table 
	inner join report on ($report_subscription_table.report_id = report.id)
	order by user_id, report.name";

	$result = $mysqli_asu->query($sql);
	$continue = 1;
	if (!$result){
		echo "There were no reports found script terminated".PHP_EOL;
		$continue = 0;
	}

	if ($continue == 1) {
		while ($subscription = $result->fetch_object()) {
			try{
				if (!isset($email_body[$subscription->user_id])){
		            $email_body[$subscription->user_id] = "";
		        }
				$email_body[$subscription->user_id] .= getReportHTML($subscription->report_id, $subscription->user_id,$subscription->date_range);
			} catch(Exception $e) {
				echo $e->getMessage() . PHP_EOL . "</br>";
				echo "FATAL ERROR EXCEPTION OUTPUT!" . PHP_EOL;
			}
		}

		foreach($email_body as $key => $value) {
			$user = getUser($key);
			$email = $user[0];
			$username = $user[1];
		    $value = "ASU Report for the date: " . $Yesterday . PHP_EOL . PHP_EOL . $value;	
		    email('ASU Reports', $value, $email);
		    echo "Email report sent to ".$email.PHP_EOL;		
		}

		setCronLog("email_reports",1);
	}
}

function getReportHTML($report_id, $user_id,$date_range)
{
	//check for cached report html and return if found
	//only cache reports that not user specific
	//if (isset($cache[$report_id])) RETURN $cache[$report_id];
	//$startdate = new DateTime();
	global $dbg;

	$enddate = (new DateTime("UTC"))->setTime(0,0)->sub(new DateInterval("P1D"));

	if($date_range == "Yesterday"){
        if($dbg)echo "Yesterday<br/>";
        if($dbg)var_dump($enddate);
		$startdate = clone $enddate;
		$startdate = $startdate->format("Y-m-d");
		$enddate = $enddate->format("Y-m-d");
	} elseif($date_range == "Last 7 Days"){
        if($dbg)echo "Last 7 Days<br/>";
		$startdate = clone $enddate;
        if($dbg)var_dump($startdate);
		$startdate->sub(new DateInterval("P7D"));
        if($dbg)var_dump($startdate);
		$startdate = $startdate->format("Y-m-d");
		$enddate = $enddate->format("Y-m-d");
        if($dbg)echo "1:Start Date: " . $startdate . "<br/>";
        if($dbg)echo "1:End Date: " . $enddate . "<br/>";
	} elseif($date_range == "Month to Date"){
        if($dbg)echo "Month to Date<br/>";
        if($dbg)var_dump($enddate);
		$startdate = DateTime::createFromFormat('Y-m-d', date("Y-m-t", strtotime("-1 month") ) );
		$startdate->add(new DateInterval("P1D"));
		$startdate = $startdate->format("Y-m-d");
		$enddate = $enddate->format("Y-m-d");
	}
	GLOBAL $mysqli_asu;
	GLOBAL $mysqli_vern;
	
	//get report details
	$sql="SELECT report.name, report.sql, report_source_id FROM report WHERE id=$report_id";
	
	$result = $mysqli_asu->query($sql);
	if (!$result) echo('Report Not Found: '.$report_id);
	$report = $result->fetch_object();

	//figure out which db server we want to use
	if ($report->report_source_id==1) {
		$mysqli=$mysqli_asu;
	}
	elseif ($report->report_source_id==2) {
		$mysqli=$mysqli_vern;
	}
	else {
		echo('no source id found');
	}
	
	$InputArray = array();
	$InputArray['start_date'] = $startdate;
	$InputArray['end_date'] = $enddate;
	$InputArray['user_id'] = $user_id;
	$InputArray['report_id'] = $report_id;
	//replace wild cards found in report SQL e.g. {user_id}

	
	//replace any wildcards
    if($dbg)echo "Start Date: " . $startdate . "<br/>";
    if($dbg)echo "End Date: " . $enddate. "<br/>";
    if($dbg)echo "Date Range: " . $date_range. "<br/>";
	
	  $report->sql = str_replace('{user_id_where}', $user_id, $report->sql);
	  $report->sql = str_replace('{form_start_date}', $startdate, $report->sql);
	  $report->sql = str_replace('{form_end_date}', $enddate, $report->sql);
    $report->sql = "# email reports report id: $report_id " . PHP_EOL . $report->sql;
    $report->sql = "# email reports Name: $report->name " . PHP_EOL . $report->sql;
	//$report->sql=str_replace('{rev_group_where}', " AND 1 = 1 ", $report->sql);
	if($dbg){
		var_dump($report->sql);
	}
    $table = "<!---" . $report->sql . "--->";
    $table .= "<h3>$report->name for $date_range</h3><table border=1 cellspacing=0>\n\r";
    
		// START NEW CODE TO REPLACE DEPRECATED CODE BELOW

		$rh = new ReportHelper(null,$InputArray,$mysqli);
	
		$rh->getReport($rh->request);
		if($rh->SQLError){
			$table .= "<tr><td style='color:red;'><strong>There was an error in the SQL this report failed Developers should be notified !</strong></td></tr></table>";
			return $table;
		}
		// END NEW CODE TO REPLACE DEPRECATED CODE BELOW
		$header = '<tr>';
		foreach($rh->columnNames as $ColumnName){
			$header .= "<th  style='text-align: center;'>$ColumnName</th>";
		}
	  $header .= "</tr>\n\r";
	  $table .= $header;
	  //var_dump($rh->data);
		if(count($rh->data) > 0){
			foreach ($rh->data as $row) {
				$header = '<tr>';
				foreach ($row as $value) {
					
					$header .= "<td style='text-align: right;'>$value</td>";
				}
				$header .= "</tr>\n\r";
				$table .= $header;
			}
			$rh->DoASUCronTotalRow();
			$table .= "<tr>";
			//var_dump($rh->CRONTotals);
			foreach($rh->CRONTotals as $CRONTotal){
				$table .= "<th  style='text-align: right;'>$CRONTotal</th>";
			}
			$table .= "</tr>";
			$table .= "</table>\n\r";
		} else {
			echo "<tr><td>Error No data returned from query<br/></td></tr>";
			$table = "<br/><br/><h3>$report->name for $date_range has no data  at " . __LINE__  . " !</h3>";
		}
	//add html to cache array for re-use -only if sql has no specific {wildcard} stuff
	//$cache[$report_id]=$table;
	RETURN $table;
	// END NEW CODE TO REPLACE DEPRECATED CODE BELOW
}

function getUser($user_id)
{
	GLOBAL $mysqli_asu;
	global $user_table;
	//get report details
	$sql="SELECT email,name FROM $user_table WHERE id=$user_id";
	$result = $mysqli_asu->query($sql);
	if (!$result) echo('User Not Found: '.$user_id);
	$user = $result->fetch_object();
	RETURN array($user->email,$user->name);
}

function email($subject, $message, $to)
{
	GLOBAL $gmail_user;
	GLOBAL $gmail_pw;
	date_default_timezone_set('UTC');
	$mail             = new PHPMailer();
	$body = $message;
	$mail->IsSMTP(); // telling the class to use SMTP
	//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	
	$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
	
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	
	$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
	
	$mail->Username   = $gmail_user;  // GMAIL username
	
	$mail->Password   = $gmail_pw;            // GMAIL password
	
	$mail->SetFrom($gmail_user, $subject);
	$mail->AddReplyTo($gmail_user, $subject);
	
	$mail->Subject    = $subject;
	
	$mail->MsgHTML($body);
	
	$address = $to;
	$addresses = explode(',', $address);
	for ($i=0; $i < count($addresses); $i++) {
		$mail->AddAddress($addresses[$i], "user");
	}
	
	//$mail->AddAddress($address, "John Doe");
	
	if(!$mail->Send()) {
		
		echo "Mailer Error: " . $mail->ErrorInfo;
		
	} else {
		
		echo "Message sent!";
		
	}
}
// END EMAIL REPORTS


// NORMALIZATION

// CHECK TO SEE IF ALL IMPORTS RAN
echo "---------------------------------------------------".PHP_EOL;
echo "STEP 3: NORMALIZATION ".PHP_EOL;
echo "---------------------------------------------------".PHP_EOL;


// EXT IDS
echo PHP_EOL."UPDATING REV GROUP EXTERNAL IDS...".PHP_EOL;
$sql = "select ta.id, s.campaign_id, s.campaign_name
from tray_spend s, traffic_account ta
where date = '".$today."'
and ta.publisher_account_id = s.account_id
group by ta.id, s.campaign_id, s.campaign_name
order by ta.id, s.campaign_id, s.campaign_name ";
$results = $mysqli->query($sql);
$numrows = $results->num_rows;
$starttime = microtime(true);
$i = 0;

while($obj=$results->fetch_object()) {

    $sql = "update rev_group set externalid = '" . $obj->campaign_id . "' where name = '" . $mysqli->real_escape_string($obj->campaign_name) . "' and traffic_account_id = '".$obj->id . "' and (externalid = '' or externalid IS NULL)";
    $extid = $mysqli->query($sql);
    if (!$extid) {
        printf("Errormessage: %s\n", $mysqli->error);
    } else {
      echo ".";
    }
} 

$sql = "select * from cron_log where date = '".$today."' AND (filename = 'import_daily_parked' OR filename = 'import_daily_inuvo' OR filename = 'import_daily_ia')";
$resultCheck = $mysqli->query($sql);	
$rowcount=mysqli_num_rows($resultCheck);
echo "Num of data imports: ".$rowcount.PHP_EOL;
if ($rowcount < 3 && $bypass == 0) {
	die("Everything not in yet!".PHP_EOL);
} else {
	$continue = 1;
	while($row = mysqli_fetch_array($resultCheck)) {
		if ($row["status"] == "1") {
			echo $row["filename"]." has been successfully imported.".PHP_EOL;
		} else {
			echo $row["filename"]." is still processing.".PHP_EOL;
			$continue = 0;
		}
	}
	if ($continue == 1 || $bypass == 1) {
		$sql = "delete from profit_by_traffic_account where profit_date = '".$start."'";
		$mysqli->query($sql);

		$sql = "delete from metric_running_spend where spend_date = '".$today."'";
		$mysqli->query($sql);

		// PROFIT BY TRAFFIC ACCOUNT
		$sql = "insert into profit_by_traffic_account (traffic_account_id,profit_date,revenue) 
		(
		SELECT traffic_account.id AS traffic_account_id, metric_by_day.DATE AS profit_date,
					SUM(metric_by_day.revenue_est) AS revenue_est
					FROM traffic_account 
					INNER JOIN traffic_account_type ON traffic_account.traffic_account_type_id = traffic_account_type.id
					LEFT JOIN rev_group ON rev_group.traffic_account_id = traffic_account.id
					LEFT JOIN metric_by_day ON metric_by_day.rev_group_id=rev_group.id
					WHERE metric_by_day.DATE = '".$start."'
					GROUP BY traffic_account.id, metric_by_day.DATE)";
		$mysqli->query($sql) or die($mysqli->error);
		//echo $sql."<br>";

		$sql = "SELECT s.traffic_account_id,s.start_date, SUM(s.spend) AS spend
			FROM spend s
			WHERE s.start_date = '".$start."'
			GROUP BY s.traffic_account_id, s.start_date
			ORDER BY s.traffic_account_id,s.start_date";
		$resultsM = $mysqli->query($sql);	
		while($row = mysqli_fetch_array($resultsM)) {
			$sql = "update profit_by_traffic_account set spend = '".$row["spend"]."' where traffic_account_id = '".$row["traffic_account_id"]."' and profit_date = '".$row["start_date"]."'";
			$mysqli->query($sql);
		}

		// METRIC BY REV GROUP

		// WALK THROUGH ALL REV GROUPS FOR THE DATE
		$sql = "select '".$start."' as date,m.rev_group_id,  sum(unq_imp_cnt) as impressions, sum(rt_click_cnt) as rt_clicks, sum(rt_imp_cnt) as rt_impressions, sum(rev_click_cnt_est) as rev_clicks,
		sum(rev_imp_cnt) as rev_impressions, sum(revenue_est) as revenue, sum(scored_click_cnt) as scored_clicks
		from metric_by_day m, rev_group r
		where m.rev_group_id = r.id
		and date = '".$start."'
		group by m.rev_group_id";
		$results = $mysqli->query($sql);
		while($row = mysqli_fetch_array($results)) {
			// CHECK TO SEE IF REV GROUP EXISTS FOR THAT DATE 
			$rowcount = 0;
			$sql = "select * from metric_by_revgroup where date = '".$start."' and rev_group_id = '".$row["rev_group_id"]."'";
			$resultCheck = $mysqli->query($sql);
			$rowcount=mysqli_num_rows($resultCheck);
			//echo "Num records: ".$rowcount.PHP_EOL;
			if ($rowcount > 0) {
				// UPDATE
				$sql = "update metric_by_revgroup set unq_imp_cnt = '".$row["impressions"]."',";
				$sql.= "rt_click_cnt = '".$row["rt_clicks"]."',";
				$sql.= "rt_imp_cnt = '".$row["rt_impressions"]."',";
				$sql.= "rev_click_cnt_est = '".$row["rev_clicks"]."',";
				$sql.= "rev_imp_cnt = '".$row["rev_impressions"]."',";
				$sql.= "revenue_est = '".$row["revenue"]."',";
				$sql.= "scored_click_cnt  = '".$row["scored_clicks"]."' where date = '".$start."' and rev_group_id = '".$row["rev_group_id"]."'";
				//echo $sql."<br>";
				$mysqli->query($sql);
			} else {
				// INSERT
				$sql = "insert into metric_by_revgroup (date,rev_group_id,unq_imp_cnt,rt_click_cnt,rt_imp_cnt,rev_click_cnt_est,rev_imp_cnt,revenue_est,scored_click_cnt) values (";
				$sql.= "'".$start."',";
				$sql.= "'".$row["rev_group_id"]."',";
				$sql.= "'".$row["impressions"]."',";
				$sql.= "'".$row["rt_clicks"]."',";
				$sql.= "'".$row["rt_impressions"]."',";
				$sql.= "'".$row["rev_clicks"]."',";
				$sql.= "'".$row["rev_impressions"]."',";
				$sql.= "'".$row["revenue"]."',";
				$sql.= "'".$row["scored_clicks"]."')";
				//echo $sql."<br>";
				$mysqli->query($sql);		
			}
			echo ".";
		}


		$profit = 0.00;
		/*
		$sql = "SELECT s.start_date, SUM(s.spend) AS spend, rg.id as rgid
			FROM spend s
			inner JOIN rev_group rg ON s.campaign_id = rg.externalid
			WHERE s.start_date = '".$today."'
			GROUP BY rg.id, s.campaign_id, s.start_date";
		$resultsM = $mysqli->query($sql);	
		while($row = mysqli_fetch_array($resultsM)) {
			$sql = "update metric_by_revgroup set spend = '".$row["spend"]."' where rev_group_id = '".$row["rgid"]."' and date = '".$row["start_date"]."'";
			$mysqli->query($sql);
		}
		*/
		$sql = "select id, revenue_est, spend from metric_by_revgroup where date = '".$start."' ";
		$results = $mysqli->query($sql);	
		while($row = mysqli_fetch_array($results)) {
			$profit = floatval($row["revenue_est"]) - floatval($row["spend"]);
			$sql = "update metric_by_revgroup set profit = '".$profit."' where id = ".$row["id"];
			$mysqli->query($sql);
		}

		// DO TODAY
		echo PHP_EOL."NOW DOING DATE: ".$today."!".PHP_EOL;
		$sql = "delete from profit_by_traffic_account where profit_date = '".$today."'";
		$mysqli->query($sql);
		// PROFIT BY TRAFFIC ACCOUNT
		$sql = "insert into profit_by_traffic_account (traffic_account_id,profit_date,revenue) 
		(
		SELECT traffic_account.id AS traffic_account_id, metric_by_day.DATE AS profit_date,
					SUM(metric_by_day.revenue_est) AS revenue_est
					FROM traffic_account 
					INNER JOIN traffic_account_type ON traffic_account.traffic_account_type_id = traffic_account_type.id
					LEFT JOIN rev_group ON rev_group.traffic_account_id = traffic_account.id
					LEFT JOIN metric_by_day ON metric_by_day.rev_group_id=rev_group.id
					WHERE metric_by_day.DATE = '".$today."'
					GROUP BY traffic_account.id, metric_by_day.DATE)";
		$mysqli->query($sql) or die($mysqli->error);
		//echo $sql."<br>";

		$sql = "SELECT s.traffic_account_id,s.start_date, SUM(s.spend) AS spend
			FROM spend s
			WHERE s.start_date = '".$today."'
			GROUP BY s.traffic_account_id, s.start_date
			ORDER BY s.traffic_account_id,s.start_date";
		
		$resultsM = $mysqli->query($sql);	
		while($row = mysqli_fetch_array($resultsM)) {
			$sql = "update profit_by_traffic_account set spend = '".$row["spend"]."' where traffic_account_id = '".$row["traffic_account_id"]."' and profit_date = '".$row["start_date"]."'";
			$mysqli->query($sql);
		}

		// METRIC BY REV GROUP

		// WALK THROUGH ALL REV GROUPS FOR THE DATE
		$sql = "select '".$today."' as date,m.rev_group_id,  sum(unq_imp_cnt) as impressions, sum(rt_click_cnt) as rt_clicks, sum(rt_imp_cnt) as rt_impressions, sum(rev_click_cnt_est) as rev_clicks,
		sum(rev_imp_cnt) as rev_impressions, sum(revenue_est) as revenue, sum(scored_click_cnt) as scored_clicks
		from metric_by_day m, rev_group r
		where m.rev_group_id = r.id
		and date = '".$today."'
		group by m.rev_group_id";

		$results = $mysqli->query($sql);
		while($row = mysqli_fetch_array($results)) {
			// CHECK TO SEE IF REV GROUP EXISTS FOR THAT DATE 
			$rowcount = 0;
			$sql = "select * from metric_by_revgroup where date = '".$today."' and rev_group_id = '".$row["rev_group_id"]."'";
			$resultCheck = $mysqli->query($sql);
			$rowcount=mysqli_num_rows($resultCheck);
			//echo "Num records: ".$rowcount.PHP_EOL;
			if ($rowcount > 0) {
				// UPDATE
				$sql = "update metric_by_revgroup set unq_imp_cnt = '".$row["impressions"]."',";
				$sql.= "rt_click_cnt = '".$row["rt_clicks"]."',";
				$sql.= "rt_imp_cnt = '".$row["rt_impressions"]."',";
				$sql.= "rev_click_cnt_est = '".$row["rev_clicks"]."',";
				$sql.= "rev_imp_cnt = '".$row["rev_impressions"]."',";
				$sql.= "revenue_est = '".$row["revenue"]."',";
				$sql.= "scored_click_cnt  = '".$row["scored_clicks"]."' where date = '".$today."' and rev_group_id = '".$row["rev_group_id"]."'";
				//echo $sql."<br>";
				$mysqli->query($sql);
			} else {
				// INSERT
				$sql = "insert into metric_by_revgroup (date,rev_group_id,unq_imp_cnt,rt_click_cnt,rt_imp_cnt,rev_click_cnt_est,rev_imp_cnt,revenue_est,scored_click_cnt) values (";
				$sql.= "'".$today."',";
				$sql.= "'".$row["rev_group_id"]."',";
				$sql.= "'".$row["impressions"]."',";
				$sql.= "'".$row["rt_clicks"]."',";
				$sql.= "'".$row["rt_impressions"]."',";
				$sql.= "'".$row["rev_clicks"]."',";
				$sql.= "'".$row["rev_impressions"]."',";
				$sql.= "'".$row["revenue"]."',";
				$sql.= "'".$row["scored_clicks"]."')";
				//echo $sql."<br>";
				$mysqli->query($sql);		
			}
			echo ".";
		}
		echo PHP_EOL."NOW UPDATING METRIC BY REVGROUP...".PHP_EOL;
		

		// SQL TO GET TODAY'S HOURLY TOTAL
		$sql = "SELECT rg.id, SUM(ts.spend) AS spend 
				FROM tray_spend ts 
				INNER JOIN rev_group rg ON rg.externalid = ts.campaign_id
				WHERE ts.date = '".$today."' AND ts.hour <> -1
				GROUP BY rg.id";
		echo "sql: ".$sql.PHP_EOL;

		$results = $mysqli->query($sql);	
		while($row = mysqli_fetch_array($results)) {
			$sql = "UPDATE metric_by_revgroup mrg 
				SET mrg.spend = '".$row["spend"]."'	WHERE mrg.date = '".$today."' and mrg.rev_group_id = '".$row["id"]."'";
			$mysqli->query($sql);
			echo ".";
		}		

		$sql = "SELECT mrg.rev_group_id as rgid, sum(mhr.searches) AS imps, sum(mhr.clicks) AS clicks, sum(mhr.revenue) AS total, mrg.spend AS spend 
				FROM metric_by_revgroup mrg 
				INNER JOIN metric_hourly_rev mhr ON mrg.rev_group_id = mhr.rev_group_id AND mhr.rev_date ='".$today."'
				WHERE mrg.date ='".$today."'
				GROUP BY mrg.rev_group_id";
		$results = $mysqli->query($sql);	
		while($row = mysqli_fetch_array($results)) {
			$sql = "UPDATE metric_by_revgroup mrg 
				SET mrg.rev_click_cnt_est = '".$row["clicks"]."', mrg.rev_imp_cnt = '".$row["imps"]."', mrg.revenue_est = '".$row["total"]."'	WHERE mrg.date = '".$today."' and mrg.rev_group_id = '".$row["rgid"]."'";
			$mysqli->query($sql);
			//echo ".";
			echo $sql.PHP_EOL;
		}

		$sql = "UPDATE metric_by_revgroup mrg set mrg.profit = mrg.revenue_est - mrg.spend where mrg.date = '".$today."'";
		$mysqli->query($sql);

		
	}
}

// END NORMALIZATION



// STEP 4: DB UPDATE RESOLUTION
echo "---------------------------------------------------".PHP_EOL;
echo "STEP 4: DB UPDATE RESOLUTION ".PHP_EOL;
echo "---------------------------------------------------".PHP_EOL;

$sql = "SELECT * FROM ((select p.id
from rev_group r, placement p
where r.id = p.rev_group_id
and r.entry_url like '%searchresults%'
and (
        (p.resolution_id = 1 and ad_id <> 158) OR
        (p.resolution_id = 2 and ad_id <> 156)
    )
and r.active = 1
and p.active = 1
and r.site_id in (15,23,10,9,21,25,28)
)) as pl";
$results = $mysqli->query($sql);
$counter = 0;
while($row = mysqli_fetch_array($results)) {
	$sql = "update placement set ad_id = IF(resolution_id = 1, 158, IF(resolution_id = 2, 156, ad_id)) where id = ".$row["id"];
	$mysqli->query($sql);
	$counter++;
}
echo "Affected rows: " .$counter.PHP_EOL;

$sql = "SELECT * FROM ((select p.id
from rev_group r, placement p
where r.id = p.rev_group_id
and r.entry_url like '%searchresults%'
and (
        (p.resolution_id = 1 and ad_id <> 193) OR
        (p.resolution_id = 2 and ad_id <> 193)
    )
and r.active = 1
and r.site_id in (6,7,19,30,31)
and p.active = 1)) as pl";
$results = $mysqli->query($sql);
$counter = 0;
while($row = mysqli_fetch_array($results)) {
	$sql = "update placement set ad_id = IF(resolution_id = 1, 193, IF(resolution_id = 2, 193, ad_id)) where id = ".$row["id"];
	$mysqli->query($sql);
	$counter++;
}
echo "Affected rows: " .$counter.PHP_EOL;

echo "New Parked:".PHP_EOL;
$sql = "SELECT * FROM ((select p.id
from rev_group r, placement p
where r.id = p.rev_group_id
and r.entry_url like '%searchresults%'
and (
(p.resolution_id = 1 and ad_id <> 196) OR
(p.resolution_id = 2 and ad_id <> 197)
)
and r.active = 1
and p.active = 1
and r.site_id in (11,12,13,14,24,26)
)) as pl";
$results = $mysqli->query($sql);
$counter = 0;
while($row = mysqli_fetch_array($results)) {
    $sql = "update placement set ad_id = IF(resolution_id = 1, 196, IF(resolution_id = 2, 197, ad_id)) where id = ".$row["id"];
    $mysqli->query($sql);
    $counter++;
}
echo "Affected rows: " .$counter.PHP_EOL;

// NEW SECTION 1/3/2020
$sql = "SELECT * FROM ((select p.id
from rev_group r, placement p
where r.id = p.rev_group_id
and r.entry_url like '%searchresults%'
and (
        (p.resolution_id = 1 and ad_id <> 199) OR
        (p.resolution_id = 2 and ad_id <> 198)
    )
and r.active = 1
and p.active = 1
and r.site_id = 16
)) as pl";
$results = $mysqli->query($sql);
$counter = 0;
while($row = mysqli_fetch_array($results)) {
    $sql = "update placement set ad_id = IF(resolution_id = 1, 199, IF(resolution_id = 2, 198, ad_id)) where id = ".$row["id"];
    $mysqli->query($sql);
    $counter++;
}
echo "Affected rows: " .$counter.PHP_EOL;
echo "STEP 5 DONE!".PHP_EOL;
// END STEP 4

// STEP 5: UPDATE METRIC BY KW
echo "---------------------------------------------------".PHP_EOL;
echo "STEP 5: UPDATE METRIC BY KW ".PHP_EOL;
echo "---------------------------------------------------".PHP_EOL;

$sql = "select id from metric_by_keyword_log where date = '".$starthour."'";
$resultLog = $mysqli->query($sql);    
if($resultLog->num_rows == 0) {

    $sql = "select CAST(l.dts AS DATE) as dateof,l.advertiser, count(*) as ad_ct, p.rev_group_id, keyword
    from log l, placement p
    where l.description = 'click'
    and p.id = l.placement_id
    and l.dts between '".$starthour."' and '".$endhour."'
    group by l.advertiser, p.keyword, p.rev_group_id, CAST(l.dts AS DATE)
    order by CAST(l.dts AS DATE),ad_ct desc
    ";

    $result = $mysqli->query($sql);
    while ($row = mysqli_fetch_array($result)) {
        $prevQuery = "select id from metric_by_keyword where date = '".$lasthourdate."' and hostname = '".$row["advertiser"]."' and rev_group_id = '".$row["rev_group_id"]."' and keyword = '".$row["keyword"]."' ";
        $prevResult = $mysqli->query($prevQuery);    
        if($prevResult->num_rows > 0){
            $row2 = mysqli_fetch_array($prevResult);
            $mysqli->query("update metric_by_keyword set click_cnt = (click_cnt+".$row["ad_ct"].") where id = ".$row2["id"]);
            echo "updated ID: ".$row2["id"].PHP_EOL;
        }else{
            $mysqli->query("insert into metric_by_keyword (date,hostname,click_cnt,rev_group_id,keyword) values (CAST('".$row["dateof"]."' AS DATE),'".$row["advertiser"]."','".$row["ad_ct"]."','".$row["rev_group_id"]."','".$row["keyword"]."')");
            echo "inserted NEW for ".$row["advertiser"].PHP_EOL;
        }

    }
    $mysqli->query("insert into metric_by_keyword_log (date) values ('".$starthour."')");
} else {
    echo "Already ran for ".$starthour;
}
echo "STEP 5 DONE!".PHP_EOL;
// END STEP 5


// STEP 6 - UPDATE HOURLY SPEND
echo "---------------------------------------------------".PHP_EOL;
echo "STEP 6: UPDATE HOURLY SPEND ".PHP_EOL;
echo "---------------------------------------------------".PHP_EOL;

$profit = 0.00;
$sql = "SELECT s.start_date, SUM(s.spend) AS spend, rg.id as rgid
	FROM spend s
	inner JOIN rev_group rg ON s.campaign_id = rg.externalid
	WHERE s.start_date = '".$today."'
	GROUP BY rg.id, s.campaign_id, s.start_date";
$resultsM = $mysqli->query($sql);	
while($row = mysqli_fetch_array($resultsM)) {
	$sql = "update metric_by_revgroup set spend = '".$row["spend"]."' where rev_group_id = '".$row["rgid"]."' and date = '".$row["start_date"]."'";
	$mysqli->query($sql);
}

$sql = "select id, revenue_est, spend from metric_by_revgroup where date = '".$today."' ";
$results = $mysqli->query($sql);	
while($row = mysqli_fetch_array($results)) {
	$profit = floatval($row["revenue_est"]) - floatval($row["spend"]);
	$sql = "update metric_by_revgroup set profit = '".$profit."' where id = ".$row["id"];
	$mysqli->query($sql);
}

// END HOURLY SPEND 



?>

