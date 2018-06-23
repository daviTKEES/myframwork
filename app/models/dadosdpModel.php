<?php
	Class dadosdpModel extends Model {
		public $_table = " VW_DADOS_DP t";

	
		Public function listdadosdp($where){
			 $xml =  "<CONTRATADOS_RP>\n";
	  	    $xml .= $this->readsimples('t.XML.getClobVal() XML', $where, null , null, null);
	        $xml .=  "</CONTRATADOS_RP>\n";
			return $xml;
		}
		


	}
