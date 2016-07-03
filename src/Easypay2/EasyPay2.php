<?php

namespace CuongHuynh\EasyPay2;

use Redirect;
use Session;
use CuongHuynh\EasyPay2\Constants\EPCurrency;

class EasyPay2
{
	private $config;
	private $apiParams;

	public function __construct($config)
	{
		$this->config    = $config;
		$this->apiParams = [];
		$this->init();
	}

	/**
	 * Init
	 * @return void
	 */
	private function init()
	{
		if ($this->config['sandbox_flag']) {
			$this->endpoint     = $this->config['sandbox']['endpoint'];
			$this->mid          = $this->config['sandbox']['mid'];
			$this->security_key = $this->config['sandbox']['security_key'];
			$this->security_seq = $this->config['sandbox']['security_seq'];
		} else {
			$this->endpoint     = $this->config['live']['endpoint'];
			$this->mid          = $this->config['live']['mid'];
			$this->security_key = $this->config['live']['security_key'];
			$this->security_seq = $this->config['live']['security_seq'];
		}

		$this->version = 2;
		$this->cur = EPCurrency::SINGAPORE;
	}

	/**
	 * Generate signature, format based on security sequence string
	 * @return string
	 */
	private function generateSignature()
	{
		$data = $this->getHashDataFromSecuritySeq() . $this->security_key;

		return EPHelper::hashSha512($data);
	}

	/**
	 * Get Hash Data from Security sequence string
	 * @return string
	 */
	public function getHashDataFromSecuritySeq()
	{
		$securitySeq = $this->security_seq;
		$seqArray    = explode(',', $securitySeq);
		$data        = '';

		foreach ($seqArray as $key => $value) {
			$data .= $this->$value;
		}

		return $data;
	}

	/**
	 * Do transaction
	 * @param  integer $munites Indicates transaction validity period (munites)
	 * @return redirect
	 */
	public function makeTransaction($munites = 5)
	{
		$this->validity  = EPHelper::nextDatetimeFromCurrent($munites);
		$this->signature = $this->generateSignature();
		
		return $this;
	}

	/**
	 * Send transaction to WireCard
	 * @return mixed
	 */
	public function requestUrl()
	{
		$params = $this->apiParams;
		unset($params['endpoint']);
		unset($params['security_key']);
		unset($params['security_seq']);

		$temp = [];

		foreach ($params as $key => $value) {
			$temp[] = $key . "=" . urlencode($value);
		}
		
		$queryString = implode('&', $temp);
		$url = $this->endpoint . $queryString;
		return $url;
	}

	/**
	 * Magic method for setter
	 * @param string $property
	 * @param mixed $value   
	 */
	public function __set($property, $value)
	{
		$this->apiParams[$property] = $value;
	}

	/**
	 * Magic method for getter
	 * @param  string $property
	 * @return mixed          
	 */
	public function __get($property)
	{
		if (!array_key_exists($property, $this->apiParams)) {
			return null;
		}

		return $this->apiParams[$property];
	}

	/**
	 * Magic method for tostring
	 * @return array
	 */
	public function __toString()
	{
		return $this->apiParams;
	}

	/**
	 * Set method for facade
	 * @param string $property
	 * @param mixed $value   
	 */
	public function set($property, $value)
	{
		$this->{$property} = $value;
	}

	/**
	 * Get method for facade
	 * @param  string $property
	 * @return mixed          
	 */
	public function get($property)
	{
		return $this->{$property};
	}
}