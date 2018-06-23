<?php
	Class dadosprocModel extends Model {
		public $_table = " VW_DADOS_PROCESSO ";

		public function listdadosproc($fields,$where,$orderby){
			return $this->read($fields, $where, null , null, $orderby);
		}

		public function listdadosxml($fields,$where,$orderby){
			return $this->selectxml($fields, $where, null , null, $orderby);
		}


		public function listdadosjson($fields,$where,$orderby){
			return $this->selectjson($fields, $where, null , null, $orderby);
		}

	}
