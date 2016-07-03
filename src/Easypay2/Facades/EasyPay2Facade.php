<?php 

namespace CuongHuynh\EasyPay2\Facades;

class EasyPay2Facade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor() 
	{
		return "easypay2";
	}
}