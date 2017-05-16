<?php
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php';

//use Facebook\autoload;
class ApiFitness{
	
	public $fb;
	public $arrSpeed = array();
	public $arrStartTime = array();
	
	
	public function __construct(){
		 $this->fb = new Facebook\Facebook([
		  'app_id' => '1907688449474139',
		  'app_secret' => '259fa460424322a327019ae0e4d8608d',
		  'default_graph_version' => 'v2.9',
		  ]);

		$helper = $this->fb->getRedirectLoginHelper();

		$permissions = ['user_actions.fitness']; // optional


		$helper = $this->fb->getRedirectLoginHelper();

		define('APP_URL', 'http://localhost:82/phpsdk/');

		$permissions = ['user_actions.fitness']; 
			
		try {
			if (isset($_SESSION['facebook_access_token'])) {
				$accessToken = $_SESSION['facebook_access_token'];
			} else {
		  		$accessToken = $helper->getAccessToken();
			}
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		 	
		 	echo 'Graph returned an error: ' . $e->getMessage();
		  	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		 	
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}
		if (isset($accessToken)) {
			if (isset($_SESSION['facebook_access_token'])) {
				$this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
			} else {
				
				$_SESSION['facebook_access_token'] = (string) $accessToken;
			  	
				$oAuth2Client = $fb->getOAuth2Client();
				
				$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
				$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
				
				$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
			}
			
			if (isset($_GET['code'])) {
				header('Location: ./');
			}
			
			try {
				$user = $this->fb->get('/me');
				$user = $user->getGraphNode()->asArray();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				
				echo 'Graph returned an error: ' . $e->getMessage();
				session_destroy();
				
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
				
		} else {
			
			$loginUrl = $helper->getLoginUrl(APP_URL, $permissions);
			echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
		}
	}





	public function getRuns(){

		$getFitnessRuns = $this->fb->get('/me/fitness.runs');
		$getFitnessRuns = $getFitnessRuns->getGraphEdge()->asArray();

		$arrSpeed = array();
		$arrStartTime = array();

			foreach ($getFitnessRuns as $key => $value) {

				$distance1 = $value['data']['course']['title'];
				$distance2 = str_replace(' ', '', $distance1);
				$distance3 = substr($distance2, 0, -5);
				$distance4 = (float)$distance3;
				$starttime = $value['start_time']->format('Y-m-d H:i:s');
				$endtime = $value['end_time']->format('Y-m-d H:i:s');
				$to_time = strtotime($endtime);
				$from_time = strtotime($starttime);
				$result = round(abs($to_time - $from_time) / 60,2);
				$result1 = (float)$result;
				$sum = $result1 / $distance4;
				$arrSpeed[] = $sum;
				$arrStartTime[] = $starttime;

			}

		$arraySpeedToStart = array_combine($arrStartTime, $arrSpeed);
		
		if(!empty($arraySpeedToStart)){

			$value = max($arraySpeedToStart);
			$running = array_search($value, $arraySpeedToStart);
			echo "</pre>";
			echo 'Bieganie: Trening z  '. $running;
			echo "</pre>";
		
		}else{

			echo 'Bieganie: BRAK DANYCH';

		}

	}

	

	public function getWalks(){

		$getFitnessWalks = $this->fb->get('/me/fitness.walks');
		$getFitnessWalks = $getFitnessWalks->getGraphEdge()->asArray();

		$arrSpeed = array();
		$arrStartTime = array();

			foreach ($getFitnessWalks as $key => $value) {
					
				$distance1 = $value['data']['course']['title'];
				$distance2 = str_replace(' ', '', $distance1);
				$distance3 = substr($distance2, 0, -5);
				$distance4 = (float)$distance3;
				$starttime = $value['start_time']->format('Y-m-d H:i:s');
				$endtime = $value['end_time']->format('Y-m-d H:i:s');
				$to_time = strtotime($endtime);
				$from_time = strtotime($starttime);
				$result = round(abs($to_time - $from_time) / 60,2);
				$result1 = (float)$result;
				$sum = $result1 / $distance4;
				$arrSpeed[] = $sum;
				$arrStartTime[] = $starttime;

			}
		$arraySpeedToStart = array_combine($arrStartTime, $arrSpeed);
		
		if(!empty($arraySpeedToStart)){

			$value = max($arraySpeedToStart);
			$walking= array_search($value, $arraySpeedToStart);
			echo "<pre>";
			echo 'Chodzenie: Trening z  '. $walking;
			echo "</pre>";
		
		}else{

			echo 'Chodzenie: BRAK DANYCH';

		}
	}



	public function getBikes(){

		$getFitnessBikes = $this->fb->get('/me/fitness.bikes');
		$getFitnessBikes = $getFitnessBikes->getGraphEdge()->asArray();

		$arrSpeed = array();
		$arrStartTime = array();

			foreach ($getFitnessBikes as $key => $value) {
					
				$distance1 = $value['data']['course']['title'];
				$distance2 = str_replace(' ', '', $distance1);
				$distance3 = substr($distance2, 0, -5);
				$distance4 = (float)$distance3;
				$starttime = $value['start_time']->format('Y-m-d H:i:s');
				$endtime = $value['end_time']->format('Y-m-d H:i:s');
				$to_time = strtotime($endtime);
				$from_time = strtotime($starttime);
				$result = round(abs($to_time - $from_time) / 60,2);
				$result1 = (float)$result;
				$sum = $result1 / $distance4;
				$arrSpeed[] = $sum;
				$arrStartTime[] = $starttime;

			}

		$arraySpeedToStart = array_combine($arrStartTime, $arrSpeed);
		
		if(!empty($arraySpeedToStart)){
		
			$value = max($arraySpeedToStart);
			$biking = array_search($value, $arraySpeedToStart);
			
			echo "<pre>";
			echo 'Jazda na rowerze: Trening z  '. $biking;
			echo "</pre>";
		
		}else{
			
			echo 'Jazda na rowerze: BRAK DANYCH';

		} 
	
	}	

}

	  	


	


$object = new ApiFitness;
$object->getRuns();
echo "<br>";
$object->getBikes();
echo "<br>";
$object->getWalks();