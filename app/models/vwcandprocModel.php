<?php
	Class vwcandprocModel extends Model {
		public $_table = " vw_xml_cand_completo t ";

		public function montaxmlCandProc($where){
	  	$xml =  "<CANDIDATOS>\n";
			$xml .= $this->readsimples('t.XML.getClobVal() XML', $where, null , null, null);
	   	$xml .=  "</CANDIDATOS>\n";
			return $xml;
		}

		public function montaCSVCandProc($where){
	  	$xml =  "<CANDIDATOS>\n";
			$xml .= $this->readsimples('t.XML.getClobVal() XML', $where, null , null, null);
	   	$xml .=  "</CANDIDATOS>\n";
			return $xml;
		}


	}
