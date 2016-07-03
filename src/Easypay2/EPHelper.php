<?php 

namespace CuongHuynh\EasyPay2;

class EPHelper
{
	/**
	 * Hash data by Sha512 standard
	 * @param  string $data
	 * @return string
	 */
	public static function hashSha512($data)
	{
		return hash('sha512', $data);
	}

	/**
	 * Generate unique string random
	 * @return string
	 */
	public static function uniqueStringRandom()
	{
		return date('Ymdhms');
	}

	
	/**
	 * Compute validity period from now
	 * @param  integer $munites
	 * @return string
	 */
	public static function nextDatetimeFromCurrent($munites, $format = 'Y-m-d-H:i:s')
	{
		return date($format, strtotime("+$munites min"));
	}

}