<?php
	Class brqModel extends Model {
		public $_table = " CANDIDATO, ENDERECO, TITULO_ELEITOR,  DOC_MILITAR, IDENTIDADE, HABILITACAO, CTPS ";

		public function listCandidatos($fields,$where,$orderby){
			return $this->read($fields, $where, null , null, $orderby);
		}

		public function callProcedure($name, $parans){
			return $this->procedure($name, $parans);
		}



	}
