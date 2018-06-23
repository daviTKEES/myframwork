<?php
	Class GlobalempresasModel extends Model {
		public $_table = "candidato";

		public function listCandidatos($fields,$where,$orderby){
			return $this->read($fields, $where, null , null, $orderby);
		}

	}