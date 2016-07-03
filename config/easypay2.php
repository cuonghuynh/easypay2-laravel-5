<?php 

return [
	'sandbox_flag' => true,
	
	'sandbox' => [
		'endpoint' => 'https://test.wirecard.com.sg/easypay2/paymentpage.do?',
		'mid' => 'xxx',
		'security_key' => 'xxx',
		'security_seq' => 'xxx',
	],

	'live' => [
		'endpoint' => '',
		'mid' => '',
		'security_key' => '',
		'security_seq' => '',
	]
];