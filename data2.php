<?php

require "settings.php";

$nodb = 0;
if ($argv[1] == 'nodb') {
	$nodb = 1;
	echo "NOT UPDATING DB".PHP_EOL;
}

$mysqli = new mysqli("localhost",$mysql_user,$mysql_password,$mysql_database);
if ($mysqli->connect_errno) {
//        file_put_contents($logfile, $mysqli->connect_error."\n", FILE_APPEND | LOCK_EX);
		die($mysqli->connect_error);
}

//510 south
stopIdToMysql('8132','510'); // 510 S at Sussex

//29 south
stopIdToMysql('14799','29'); // 29 S at Wilson Stn
stopIdToMysql('2107','29'); // 29 S at Wilson
stopIdToMysql('2024','29'); // 29 S at Bridgeland
stopIdToMysql('2076','29'); // 29 S at Orfus
stopIdToMysql('2070','29'); // 29 S at Lawrence
stopIdToMysql('2054','29'); // 29 S at Glencairn
stopIdToMysql('2046','29'); // 29 S at Eglinton
stopIdToMysql('2083','29'); // 29 S at Rogers
stopIdToMysql('2094','29'); // 29 S at St CLair
stopIdToMysql('2039','29'); // 29 S at Davenport
stopIdToMysql('2044','29'); // 29 S at Dupont
stopIdToMysql('2013','29'); // 29 S at Bloor
stopIdToMysql('2034','29'); // 29 S at College
stopIdToMysql('2041','29'); // 29 S at Dundas
stopIdToMysql('15030','29'); // 29 S at Queen
stopIdToMysql('2072','29'); // 29 S at Liberty

//501 east
stopIdToMysql('5511','501'); //Long Branch
stopIdToMysql('5163','501'); //LAKE SHORE at 30TH STREET
stopIdToMysql('5182','501'); //LAKE SHORE at KIPLING
stopIdToMysql('5172','501'); //LAKE SHORE at 7TH STREET
stopIdToMysql('5197','501'); //LAKE SHORE at ROYAL YORK
stopIdToMysql('5195','501'); //LAKE SHORE at PARK LAWN
stopIdToMysql('5506','501'); //HUMBER LOOP
stopIdToMysql('14282','501'); //QUEENSWAY at WINDERMERE EAST SIDE
stopIdToMysql('6908','501'); //QUEENSWAY at PARKSIDE
stopIdToMysql('6912','501'); //QUEENSWAY at RONCESVALLES
stopIdToMysql('6840','501'); //QUEEN at LANSDOWNE
stopIdToMysql('6834','501'); //QUEEN at DUFFERIN
stopIdToMysql('6850','501'); //QUEEN at SHAW
stopIdToMysql('6827','501'); //QUEEN at BATHURST
stopIdToMysql('6853','501'); //QUEEN at SPADINA
stopIdToMysql('6858','501'); //QUEEN at UNIVERSITY
stopIdToMysql('3082','501'); //QUEEN at BAY
stopIdToMysql('6861','501'); //QUEEN at YONGE
stopIdToMysql('3050','501'); //QUEEN at JARVIS
stopIdToMysql('3070','501'); //QUEEN at SHERBOURNE
stopIdToMysql('3064','501'); //QUEEN at PARLIAMENT
stopIdToMysql('3032','501'); //QUEEN at BROADVIEW
stopIdToMysql('3035','501'); //QUEEN at CARLAW
stopIdToMysql('3030','501'); //QUEEN at JONES
stopIdToMysql('3056','501'); //QUEEN at LESLIE
stopIdToMysql('3048','501'); //QUEEN at GREENWOOD
stopIdToMysql('4557','501'); //QUEEN at COXWELL
stopIdToMysql('11845','501'); //QUEEN at KINGSTON RD
stopIdToMysql('6824','501'); //QUEEN at WOODBINE
stopIdToMysql('6821','501'); //QUEEN at WINEVA


////////////////////////////////////////////////////////////

function stopIdToMysql($stopId,$routeTag) {
	global $mysqli, $nodb;

	//print "trying ".$stopId."\n";
	
	$url = 'http://webservices.nextbus.com/service/publicXMLFeed?command=predictions&a=ttc&stopId='.$stopId.'&routeTag='.$routeTag;

	$xml = simplexml_load_file($url);

	foreach ($xml->children() as $predic) {
		if (isset($predic->direction->prediction[0]["minutes"])) {
			if ($predic->direction->prediction[0]["branch"] == $routeTag) {

				$iquery = sprintf("INSERT INTO basic (stopId,minutes,branch, vehicle, dirTag,stamp) VALUES('%s','%s','%s','%s','%s',NOW())",
					$stopId,
					$predic->direction->prediction[0]["minutes"],
					$predic->direction->prediction[0]["branch"],
					$predic->direction->prediction[0]["vehicle"],
					$predic->direction->prediction[0]["dirTag"]);
				if (!$nodb) {
					if (!$mysqli->query($iquery)) {
						die("MySQL error: (" . $mysqli->errno . ") " . $mysqli->error);
					}
				}
			}
			//print "yes!\n";
		} else { 
			print "no\n\n\n\n"; 
			var_dump($predic);
			print "\n\n---\n\n";
			var_dump($xml);
		}
	}
}
?>