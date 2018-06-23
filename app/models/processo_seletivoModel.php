<?php
	Class processo_seletivoModel extends Model {
		public $_table = " processo_seletivo ";

		public function listProcesso_seletivo($fields,$where,$orderby){
			return $this->read($fields, $where, null , null, $orderby);
		}

	}
