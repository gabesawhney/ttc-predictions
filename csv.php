<?php

//OUTPUT to csv for Jose

$mysqli = new mysqli("localhost",'nextbus',"n52DA6nW4svx4C7M",'nextbus');
if ($mysqli->connect_errno) {
//        file_put_contents($logfile, $mysqli->connect_error."\n", FILE_APPEND | LOCK_EX);
		die($mysqli->connect_error);
}


//filename: routeTag-stopId-YYYYMMDD.csv
//figure out which day to output

//foreach minute of the day
//output: time, prediction

//29 south
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '14799');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2107');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2024');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2076');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2070');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2054');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2046');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2083');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2094');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2039');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2044');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2013');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2034');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2041');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '15030');
$filestooutput[] = array('routeTag'=> '29', 'stopId' => '2072');

//501 east
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5511');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5163');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5182');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5172');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5197');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5195');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '5506');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '14282');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6908');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6912');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6840');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6834');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6850');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6827');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6853');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6858');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3082');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6861');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3050');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3070');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3064');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3032');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3035');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3030');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3056');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '3048');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '4557');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '11845');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6824');
$filestooutput[] = array('routeTag'=> '501', 'stopId' => '6821');


foreach ($filestooutput as $fileinfo) {
	$datetooutput = new DateTime('2014-02-22 0:0:0');
	$datetostop = new DateTime('2014-02-22 0:0:0');
	$datetostop->add(new DateInterval('PT24H'));
	$filename = $fileinfo['routeTag']."-".$fileinfo['stopId']."-".$datetooutput->format('Ymd').".csv";
	print ">>>>".$filename.PHP_EOL;
	file_put_contents($filename,"time,prediction\n",FILE_APPEND | LOCK_EX);

//var_dump($datetooutput);
//var_dump($datetostop);

	while ($datetooutput < $datetostop) {

		$query = sprintf("SELECT * FROM basic WHERE branch = '%s' AND stopId = '%s' AND stamp BETWEEN '%s' AND '%s' LIMIT 1",
			$fileinfo['routeTag'], $fileinfo['stopId'],
			$datetooutput->format('Y-m-d H:i:').'00',
			$datetooutput->format('Y-m-d H:i:').'59');

//print $query.PHP_EOL;

		if ($result = $mysqli->query($query)) {
			if ($row = $result->fetch_assoc()) {
				file_put_contents($filename,sprintf ("%s,%s\n",
					$datetooutput->format('H:i'),
					$row['minutes']),FILE_APPEND | LOCK_EX);
			} else { 
				//print "x"; 
			}
			$result->free();
		} else { 
			//print "z"; 
		}


		$datetooutput->add(new DateInterval('PT1M'));
	}
}

?>