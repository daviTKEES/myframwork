<?php
	Class pacexpressModel extends Model {
		public $_table    = "tabela_temporaria";

		public function listCandidatos($fields,$where,$orderby){
			return $this->read($fields, $where, null , null, $orderby);
		}

		public function callProcedure($name, $parans){
			return $this->procedure($name, null);
		}
	}

	public function processoSOAP(){
		$dados = func_get_args();
		$dados = array ('token' => $dados[0],
										 'JSON' => $dados[1]);

		$KeyCheck = new AuthenticationHelper();
		$AuthorizationOk = $KeyCheck->GetAuthentication($dados['token'],null,null);
		if ($AuthorizationOk)
		{
			return $dados;
			$inputJSON = $dados['token'];

			$Keydata        = new KeyData();
			$Keydata        = $Keydata->geraKey(7, true, true, false);

			$insertprocesso = new CreateXML();
			$db             = $insertprocesso->insertTabelTemp($inputJSON, $Keydata);

			$db 						=  $this->procedure('prc_integracao_vagas_in', null);

			$return         = new ShowLog();
			return $return->returnlog($Keydata);

		}else {
			return array( (object) array('access' => 'denied'));
		}
	}
