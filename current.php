<?php

require_once 'Config.php';

$dayRating = new DayRating($_GET);

/**
* DayRating
*/
class DayRating {
	private $params;

	function __construct(array $params){
		$this->params = $params;
		$this->main();
	}

	public function main(){
		try{
			if (isset($this->params['allData'])){
				echo $this->respond($this->getAllData());
			}else{
				echo $this->respond($this->getCurrentData());
			}
		}catch(Exception $e){
			$this->respond(print_r($e,1));
		}
	}


	private function getCurrentData(){
		$db = new mysqli(Config::HOSTNAME, Config::USERNAME, Config::PASSWORD, Config::DATABASE);
		
		$dateTime = new DateTime('NOW');

		$startTimeToStillCountForToday = new DateTime(Config::START_TIME_TO_STILL_COUNT_FOR_TODAY);
		$endTimeToStillCountForToday = new DateTime(Config::END_TIME_TO_STILL_COUNT_FOR_TODAY);


		if ($dateTime >= $startTimeToStillCountForToday && $dateTime <= $endTimeToStillCountForToday){
			$dateTime->sub(DateInterval::createFromDateString('1 day'));
		}


		$date = $dateTime->format('Y-m-d');
		$sql = "SELECT * FROM records WHERE `timestamp` = '$date'";
		$result = $db->query($sql);
		$resultArr = $result->fetch_assoc();

		return $resultArr;
	}

	private function getAllData(){
		$db = new mysqli(Config::HOSTNAME, Config::USERNAME, Config::PASSWORD, Config::DATABASE);

		$sql = "SELECT * FROM records ORDER BY timestamp DESC LIMIT 0,30";
		$result = $db->query($sql);
		$resultArr = array();
		$row = array();
		while ($row = $result->fetch_assoc()){
			$dateTs = new DateTime($row['timestamp']);

			$row['isMonday'] = $this->isMonday($dateTs);
			$row['timestamp'] = $dateTs->format('D d.m.Y');
			$resultArr[] = $row;
		}
		
		return $resultArr;
	}

	private function isMonday(DateTime $date){
		return $date->format('w') === '1';
	}

	private function respond($data){
		header('Content-Type: application/json');
		if (!is_array($data)){
			header('HTTP/1.0 400 Bad Request');
			return json_encode(array('message' => $data, 'success' => false));
		}
		return json_encode($data);
	}
}
