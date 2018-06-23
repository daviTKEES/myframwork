<?php
class ConvertJsonHelper  {
	public function jsontoUTF8($array){
		$json = preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($array));
		return urldecode(stripslashes($json));
	}

	public function simpleJsontoUTF8($json){
		$json = preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", $json);
		return urldecode(stripslashes($json));
	}

}
