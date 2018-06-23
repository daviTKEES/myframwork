<?php
	Class dependentesModel extends Model {
		public $_table = " vw_soap_dependentes ";

		public function readDependentes($where){
			$xml = $this->selectmanual('select t.XML.getClobVal() XML from  vw_soap_dependentes t where '.$where);
			return $xml;
		}

	}
