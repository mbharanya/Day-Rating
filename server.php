<?php

require_once 'Config.php';

$dayRating = new DayRating($_POST);

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
			$this->validateParams();
			$this->saveToDb();
			echo $this->respond(true);
		}catch(Exception $e){
			$this->respond(false);
		}
	}

	private function validateParams(){
		if(!isset($this->params) || !is_array($this->params) || count($this->params) === 0){
			throw new Exception("Invalid data", 1);
		}
		if (!isset($this->params['rating']) || !is_numeric($this->params['rating'])){
			throw new Exception("Rating not set", 1);
		}
	}

	private function saveToDb(){
		$db = new mysqli(Config::HOSTNAME, Config::USERNAME, Config::PASSWORD, Config::DATABASE);
		
		$dateTimeNow = new DateTime('NOW');

		$startTimeToStillCountForToday = new DateTime(Config::START_TIME_TO_STILL_COUNT_FOR_TODAY);
		$endTimeToStillCountForToday = new DateTime(Config::END_TIME_TO_STILL_COUNT_FOR_TODAY);


		if ($dateTimeNow >= $startTimeToStillCountForToday && $dateTimeNow <= $endTimeToStillCountForToday){
			$dateTimeNow->sub(DateInterval::createFromDateString('1 day'));
		}

		$date = $dateTimeNow->format('Y-m-d');
		$sql = "SELECT * FROM records WHERE `timestamp` = '$date'";
		
		$result = $db->query($sql);
		$resultObj = $result->fetch_object();

		if ($result->num_rows === 0){
			$stmt = $db->prepare("INSERT INTO records VALUES (?, ?, ?, ?, ?)");
		
			$emptyId = null;
			$comments = htmlspecialchars($this->params['comment']);

			// echo '<pre>'.print_r($stmt,1).'</pre>';die();
			$now = new DateTime('NOW');
			$now = $now->format('y-m-d');
			$stmt->bind_param('iisss', $emptyId, $this->params['rating'], $comments, $date, $now);
			$stmt->execute();
			$stmt->close();
		}else{
			$stmt = $db->prepare("UPDATE records SET rating=?, comment=? WHERE id=".$resultObj->id);

			$comments = htmlspecialchars($this->params['comment']);

			$stmt->bind_param('is', $this->params['rating'], $comments);
			$stmt->execute();
			$stmt->close();
		}
	}

	private function respond($success){
		header('Content-Type: application/json');
		if (!$success){
			http_response_code(400);
			return json_encode(array('message' => 'Invalid request', 'success' => false));
		}
		return json_encode(array('message' => 'Yay', 'success'=> true));
	}
}
