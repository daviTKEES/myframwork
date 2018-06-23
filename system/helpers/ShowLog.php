<?php
class ShowLog  extends Model { // Quando você da um extends Model, vc está assumindo que a class está automaticamente pegando  as características da class Model
	public $_table    = "vw_integracao_wsdl_result";
	public function returnlog($keydata) {
  			return $this->read('MENSAGEM, CAMPO_ERRO, VALOR_CAMPO, KEY_DATA', "KEY_DATA = '". $keydata."'", null , null, null);
    }
}
