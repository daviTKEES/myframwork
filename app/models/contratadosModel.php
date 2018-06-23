<?php
	Class contratadosModel extends Model {
		public $_table = " vw_soap_candidatos_contratados t ";

		public function readNewEmployees($cod_candidato){
			$xml = $this->selectmanual('select t.XML.getClobVal() XML from  vw_soap_candidatos_contratados t where '. $cod_candidato);
			return $xml;
		}

		public function readEmCadastramentoCod(){
			$xml = $this->readManual("select COD_CANDIDATO from vw_soap_candidatos_contratados");
			return $xml;
		}





	}
