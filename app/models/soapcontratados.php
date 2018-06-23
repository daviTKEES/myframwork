<?php
Class listprocxmlModel extends Model {
	public $_table = " VW_PROCESSO_SEL_XML t ";

	public function montaxmlProc($where){
		$xml =  "<PROCESSOS>\n";
		$xml .= $this->readsimples('t.XML.getClobVal() XML', $where, null , null, 't.DATA_ABERTURA');
		$xml .=  "</PROCESSOS>\n";
		return $xml;
	}

	public function montaCSVProc($where){
		$xml =  "<PROCESSOS>\n";
		$xml .= $this->readsimples('t.XML.getClobVal() XML', $where, null , null, 't.DATA_ABERTURA');
		$xml .=  "</PROCESSOS>\n";
		return $xml;
	}

}
