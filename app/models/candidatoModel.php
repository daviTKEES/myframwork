<?php
	Class candidatoModel extends Model {
		public $_table = "CANDIDATO";

		public function listCandidatos($fields,$where,$orderby){
			return $this->read($fields, $where, null , null, $orderby);
		}

	}
