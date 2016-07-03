<?php 

return [
	'sandbox_flag' => true,
	
	'sandbox' => [
		'endpoint' => 'https://test.wirecard.com.sg/easypay2/paymentpage.do?',
		'mid' => '20151111011',
		'security_key' => 'ABC123456',
		'security_seq' => 'amt,ref,cur,mid,transtype',
	],

	'live' => [
		'endpoint' => '',
		'mid' => '',
		'security_key' => '',
		'security_seq' => '',
	]
];