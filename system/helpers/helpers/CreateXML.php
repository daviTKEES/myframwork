<?php
class CreateXML  extends Model { // Quando você da um extends Model, vc está assumindo que a class está automaticamente pegando  as características da class Model
	public $_table    = "tabela_temporaria";
	public $_database = "pacexpress";
	public function insertTabelTemp($inputJSON, $keydata){
		$array = json_decode($inputJSON);
		$xml = "";
		$xml = $xml . '<WEBSERVICE><REGISTRO>';
		$xml = $xml . '<KEY_DATA>'.$keydata.'</KEY_DATA>';
		foreach($array as $x => $x_value) {
			 $xml = $xml . "<".$x.">".$x_value."</".$x.">";
		}
		$xml = $xml .  '</REGISTRO></WEBSERVICE>';
		$db  = $this->insert($arrayName = array(
																					'COD_INTEGRACAO' => 1 ,
																					'ARQUIVO_XML' => $xml ,
																					'ARQUIVO_XLS' => '',
																					'COD_LOGIN'=> 1,
																					'KEY_DATA' =>$keydata));
	}
}
