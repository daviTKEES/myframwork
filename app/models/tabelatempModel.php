<?php
	Class tabelatempModel extends Model {
		public $_table = " TABELA_TEMPORARIA ";

		public function integrationAction($dados,$keyval, $cod_integracao){
			 ini_set('max_execution_time', 300);
			 $array = array(
				"cod_integracao" => $cod_integracao,
				"arquivo_xml" => $dados,
				"cod_login" => 2,
				"key_data" => $keyval	);
			  return $this->insert($array);
		}
	}
