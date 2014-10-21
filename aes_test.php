#!/usr/bin/php

<?php

	function encrypt($input, $key) 
	{
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$input = pkcs5_pad($input, $size); 
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
		mcrypt_generic_init($td, $key, $iv); 
		$data = mcrypt_generic($td, $input); 
		mcrypt_generic_deinit($td); 
		mcrypt_module_close($td); 
		$data = base64_encode($data); 
		return $data; 
	} 

	function decrypt($sStr, $sKey)
	{
		$decrypted= mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sKey, base64_decode($sStr), MCRYPT_MODE_ECB);
		$dec_s = strlen($decrypted); 
		$padding = ord($decrypted[$dec_s-1]); 
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}	

	function pkcs5_pad ($text, $blocksize) 
	{ 
		$pad = $blocksize - (strlen($text) % $blocksize); 
		return $text . str_repeat(chr($pad), $pad); 
	} 

	$key = 'CouponExt9001234';
	$plain = '이것은 암호화될 데이터입니다.';
	printf("*plain     : [%s]\n", $plain);

	$cipher = encrypt($plain, $key);
	printf("*encrypted : [%s]\n", $cipher);

	$decipher = decrypt($cipher, $key);
	printf("*decrypted : [%s]\n", $decipher);
?>
