<?php 

class Bitfinex {
	private $api_key;
	private $api_secret;
	private $api_version;
	private $base_url = 'https://api.bitfinex.com';
	
	public function __construct($api_key, $api_secret, $api_version = 'v1')
	{
		$this->api_key = $api_key;
		$this->api_secret = $api_secret;
		$this->api_version = $api_version;
	}
	
	public function new_offer($currency, $amount, $rate, $period, $direction = "lend")
	{
		$request = '/' . $this->api_version . '/offer/new';
		
		$data = array(
			'request' => $request,
			'currency' => $currency,
			'amount' => $amount,
			'rate' => $rate,
			'period' => $period,
			'direction' => $direction
		);
		
		return $this->send_signed_request($data);
	
	}
	
	public function cancel_offer($offer_id)
	{
		$request = '/' . $this->api_version . '/offer/cancel';
		
		$data = array(
			'request' => $request,
			'offer_id' => $offer_id
		);
		
		return $this->send_signed_request($data);
	}
	
	public function get_offer($offer_id)
	{
		$request = '/' . $this->api_version . '/offer/status';
		
		$data = array(
			'request' => $request,
			'offer_id' => $offer_id
		);
		
		return $this->send_signed_request($data);
	}
	
	public function get_trades()
	{
		$request = '/' . $this->api_version . '/trades/btcusd';
		
		$data = array(
			'request' => $request,
			'options' => array()
		);
		
		return $this->send_signed_request($data);
	}
	
	public function get_offers()
	{
		$request = '/' . $this->api_version . '/offers';
		
		$data = array(
			'request' => $request,
			'options' => array()
		);
		
		return $this->send_signed_request($data);
	}
	
	public function get_credits()
	{
		$request = '/' . $this->api_version . '/credits';
	
		$data = array(
			'request' => $request,
			'options' => array()
		);
		
		return $this->send_signed_request($data);
	}
	
	public function get_balances()
	{
		$request = '/' . $this->api_version . '/balances';
		
		$data = array(
			'request' => $request,
			'options' => array()
		);
		
		return $this->send_signed_request($data);
	}
	
	public function get_lendbook($symbol = "usd")
	{
		$request = '/v1/lendbook/' . $symbol;
		
		return $this->send_unsigned_request($request);
	
	}
	
	public function get_lends($symbol = "usd")
	{
		$request = '/v1/lends/' . $symbol;
		
		return $this->send_unsigned_request($request);
	
	}
	
	public function get_ticker($symbol = "btcusd")
	{
		$request = '/' . $this->api_version . '/ticker/' . $symbol;
		
		return $this->send_unsigned_request($request);
	}
	
	private function prepare_header($data)
	{
		$data['nonce'] = (string) number_format(round(microtime(true) * 100000),0,'.','');
		
		$payload = base64_encode(json_encode($data));
		$signature = hash_hmac('sha384', $payload, $this->api_secret);
		
		return array(
			'X-BFX-APIKEY: ' . $this->api_key,
			'X-BFX-PAYLOAD: ' . $payload,
			'X-BFX-SIGNATURE: ' . $signature
		);
	}
	
	private function send_signed_request($data)
	{
		$ch = curl_init();
		$url = $this->base_url . $data['request'];
		
		$headers = $this->prepare_header($data);
		
		curl_setopt_array($ch, array(
			CURLOPT_URL  => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POSTFIELDS => ""
		));
		
		if( !$result = curl_exec($ch) )
		{
			return false;
		} 
		else 
		{
			return json_decode($result, true);
		}
	}
	
	private function send_unsigned_request($request)
	{
		$ch = curl_init();
		$url = $this->base_url . $request;
		
		curl_setopt_array($ch, array(
			CURLOPT_URL  => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false
		));
		
		if( !$result = curl_exec($ch) )
		{
			return false;
		} 
		else
		{
			return json_decode($result, true);
		}
	}
}

?>