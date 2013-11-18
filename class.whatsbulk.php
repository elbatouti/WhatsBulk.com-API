<?php
class Whatsbulk {
	private $key = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"; //key obtained from http://whatsbulk.com
	public $service = ""; // service wanted
	public $get_params = ""; // rest of URL
	public $post_params = "";  // key-value pair array
	public $url = "http://whatsbulk.apiary.io/api/";  // for production the value is "http://my.whatsbulk.com/api/"
	
	function __construct() {

	}

	public function getBalance() {
		$this->service = "balance";
		return $this->simple_get();
	}

	public function getLists() {
		$this->service = "lists";
		return $this->simple_get();
	}

	public function getQueues() {
		$this->service = "queues";
		return $this->simple_get();
	}

	public function getStats($status = "all") {
		$this->service = "stats";
		$this->get_params = '/' . $status;
		return $this->simple_get();
	}

	public function getStatsByMessageId($message_id, $status = "all", $per_page = 500, $page = 1) {
		if (!$message_id)
			return false;

		$this->service = "stats_by_message_id";
		$this->get_params = '/' . $message_id . '/' . $status . '/' . $per_page . '/' . $page;
		return $this->simple_get();
	}

	public function sendToNumber($number, $message) {
		$this->service = "send_to_number";
		$this->post_params = array("number" => $number, "message" => $message);
		$this->simple_post();
	}

	public function sendToList($list_id, $message, $queue_id) {
		$this->service = "send_to_predefined_list";
		$this->post_params = array("list_id" => $list_id, "message" => $message, "queue_id" => $queue_id);
		$this->simple_post();
	}

	public function sendToNewList($numbers, $message, $list_name, $queue_id, $save_list = false) {
		$this->service = "send_to_new_list";
		$this->post_params = array("numbers" => $numbers, "message" => $message, "name" => $list_name, "queue_id" => $queue_id, "save_list" => $save_list);
		$this->simple_post();
	}

	private function simple_get() {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $this->url . $this->key . '/' . $this->service . $this->get_params);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $out = curl_exec($ch);
	    $info = curl_getinfo($ch);
	    curl_close ($ch);
	    $result = @json_decode($out);
	    if (in_array($info['http_code'], range(200, 206))) {
	        return array("status" => "OK", "result" => $result);
	    }
	    return array("status" => "error", "result" => $result);
	}

	private function simple_post() {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $this->url . $this->key . '/' . $this->service);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->post_params));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $out = curl_exec($ch);
	    $info = curl_getinfo($ch);
	    curl_close ($ch);
	    $result = @json_decode($out);
	    if (in_array($info['http_code'], range(200, 206))) {
	        return array("status" => "OK", "result" => $result);
	    }
	    return array("status" => "error", "result" => $result);
	}
}