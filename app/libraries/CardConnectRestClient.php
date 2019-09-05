<?php
namespace App\libraries;

use GuzzleHttp\Client;///Client

class CardConnectRestClient {
	private $url = "";
	private $public_key = "";

	private $OP_POST   = "POST";
	private $OP_PUT    = "PUT";
	private $OP_GET    = "GET";
	private $OP_DELETE = "DELETE";

	private $ENDPOINT_AUTH       = "auth";
	private $ENDPOINT_CAPTURE    = "capture";
	private $ENDPOINT_VOID       = "void";
	private $ENDPOINT_REFUND     = "refund";
	private $ENDPOINT_INQUIRE    = "inquire";
	private $ENDPOINT_SETTLESTAT = "settlestat";
	private $ENDPOINT_DEPOSIT    = "deposit";
	private $ENDPOINT_PROFILE    = "profile";

	private $USER_AGENT     = "CardConnectRestClient-PHP";
	private $CLIENT_VERSION = "1.0";


	/**
	* Constructor to create a new CardConnectRestClient object
	*
	* @param string $ccurl CardConnect REST URL (https://sitename.cardconnect.com:6443/cardconnect/rest/)
	* @param string $user Username and Password converted to base64
	*/
	public function __construct($ccurl, $public_key) {
		if (self::isEmpty($ccurl)) throw new InvalidArgumentException("url parameter is required");
		if (self::isEmpty($public_key)) throw new InvalidArgumentException("Key parameter is required");

		if (!self::endsWith($ccurl, "/")) $ccurl .= "/";
		
		$this->url = $ccurl;
		$this->public_key = $public_key;
	}

	/**
	* Sends an Authorize Transaction request via REST
	*
	* @param array $request Array representing an authorization request
	* @return array Array representing an authorization response
	*/
	public function authorizeTransaction($request) {
		return self::send($this->ENDPOINT_AUTH, $this->OP_PUT, $request);
	}


	/**
	* Sends a Capture Transaction request via REST
	*
	* @param array $request Array representing a capture request
	* @return array Array representing a capture response
	*/
	public function captureTransaction($request) {
		return self::send($this->ENDPOINT_CAPTURE, $this->OP_PUT, $request);
	}
	
	
	/**
	* Sends a Void Transaction request via REST
	*
	* @param array $request Array representing a void request
	* @return array Array representing a void response
	*/
	public function voidTransaction($request) {
		return self::send($this->ENDPOINT_VOID, $this->OP_PUT, $request);
	}
	
	
	/**
	* Sends a Refund Transaction request via REST
	*
	* @param array $request Array representing a refund request
	* @return array Array representing a refund response
	*/
	public function refundTransaction($request) {
		return self::send($this->ENDPOINT_REFUND, $this->OP_PUT, $request);
	}
	
	
	/**
	* Sends an Inquire Transaction request via REST
	*
	* @param string $merchid Merchant ID
	* @param string $retref RetRef from previous authorization/capture response 
	* @return array Array representing an inquire response
	*/
	public function inquireTransaction($merchid, $retref) {
		if (self::isEmpty($merchid)) throw new InvalidArgumentException("Missing required parameter: merchid");
		if (self::isEmpty($retref)) throw new InvalidArgumentException("Missing required parameter: retref");
		
		$url = $this->ENDPOINT_INQUIRE . "/" . $retref . "/" . $merchid;
		return self::send($url, $this->OP_GET, null);
	}
	
	
	/**
	* Sends a Settlement Status request via REST
	*
	* @param string $merchid Merchant ID
	* @param string $date Settlement Date
	* @return array Array representing the requested settlement status
	*/
	public function settlementStatus($merchid = "", $date = "") {
		if ((!self::isEmpty($merchid) && self::isEmpty($date)) || (self::isEmpty($merchid) && !self::isEmpty($date))) 
			throw new InvalidArgumentException("Both merchid and date parameters are required, or neither");
		
		$url;
		if (self::isEmpty($merchid) || self::isEmpty($date)) {
			$url = $this->ENDPOINT_SETTLESTAT;
		} else {
			$url = $this->ENDPOINT_SETTLESTAT . "?date=" . $date . "&merchid=" . $merchid;
		}
		
		return self::send($url, $this->OP_GET, null);
	}
	
	
	/**
	* Sends a Deposit Status request via REST
	*
	* @param string $merchid Merchant ID
	* @param string $date Deposit Date
	* @return array Array representing the requested deposit status
	*/
	public function depositStatus($merchid = "", $date = "") {
		if ((!self::isEmpty($merchid) && self::isEmpty($date)) || (self::isEmpty($merchid) && !self::isEmpty($date)))
			throw new InvalidArgumentException("Both merchid and date parameters are required, or neither");
		
		$url;
		if (self::isEmpty($merchid) || self::isEmpty($date)) {
			$url = $this->ENDPOINT_DEPOSIT;
		} else {
			$url = $this->ENDPOINT_DEPOSIT . "?merchid=" . $merchid . "&date=" . $date;
		}
		return self::send($url, $this->OP_GET, null);
	}
	
	
	/**
	* Retrieves the specified profile via REST
	*
	* @param string $profileid Profile ID
	* @param string $accountid Optional Account ID
	* @param string $merchid Merchant ID
	* @return array Array representing the retrieved profile
	*/
	public function profileGet($profileid, $accountid = "", $merchid) {
		if (self::isEmpty($profileid)) throw new InvalidArgumentException("Missing required parameter: profileid");
		if (self::isEmpty($merchid)) throw new InvalidArgumentException("Missing required parameter: merchid");
		
		$url = $this->ENDPOINT_PROFILE . "/" . $profileid . "/" . $accountid . "/" . $merchid;
		return self::send($url, $this->OP_GET, null);
	}
	
	
	/**
	* Deletes the specified profile via REST
	*
	* @param string $profileid Profile ID
	* @param string $accountid Optional Account ID
	* @param string $merchid Merchant ID
	* @return array Array representing the results of the profile deletion
	*/
	public function profileDelete($profileid, $accountid = "", $merchid) {
		if (self::isEmpty($profileid)) throw new InvalidArgumentException("Missing required parameter: profileid");
		if (self::isEmpty($merchid)) throw new InvalidArgumentException("Missing required parameter: merchid");
		
		$url = $this->ENDPOINT_PROFILE . "/" . $profileid . "/" . $accountid . "/" . $merchid;
		return self::send($url, $this->OP_DELETE, null);
	}
	
	
	/**
	* Creates or updates a profile via REST
	*
	* @param array $request Array representing the Profile create/update request
	* @return array Array representing the profile creation
	*/
	public function profileCreate($request) {
		return self::send($this->ENDPOINT_PROFILE, $this->OP_PUT, $request);
	}
	
	
	// Returns true if a string is null or empty string
	static function isEmpty($s) {
		if (is_null($s)) return true;
		if (strlen($s) <= 0) return true;
		return false;
	}
	
	// Checks the last character of a string
	static function endsWith($s, $char) {
		return $char === "" || substr($s, -strlen($char)) === $char;
	}

	// Private method for sending HTTP REST request to CardConnect
	private function send($endpoint, $operation, $request) {

		$client = new Client(['headers' => [
			'Content-Type' => 'application/json', 'Authorization' => $this->public_key]
		]);

		$tempUrl = $this->url.$endpoint;
		$response = ""; 
		try {		    
			// Send request to rest service
			switch ($operation) {
				case ($this->OP_PUT):
					$response = $client->put("$tempUrl",  ['body' => json_encode($request)]);
					break;
				case ($this->OP_GET):
					$response = $client->get("/$endpoint", $request);
					break;
				case ($this->OP_POST):
				 	$response = $client->post("/$endpoint", $request);
					break;
				case ($this->OP_DELETE):
					$response = $client->delete("/$endpoint", $request);
					break;
			}
		} catch (Exception $e) {
			echo "Caught exception when sending request : " .  $e->getMessage();
		}

		return $response;
	}
}

?>
