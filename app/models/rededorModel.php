<?php
	Class rededorModel extends Model {
		public $_table = " CANDIDATO ";

		public function contratadoslist(){
			$dados = func_get_args();
			$dados = array ('login' => $dados[0],
			                 'password' => $dados[1],
			                 'JSON' => $dados[2]);

		  $KeyCheck = new AuthenticationHelper();
			$AuthorizationOk = $KeyCheck->GetAuthentication(Null,$dados['login'],$dados['password']);
			if ($AuthorizationOk)
			{
				return $dados;
			}else {
				return array( (object) array('EMPLOYMENTID' => 'denied'));
			}
		}

		public function processoAction(){
			$dados = func_get_args();
			$dados = array ('login' => $dados[0],
			                 'password' => $dados[1],
			                 'JSON' => $dados[2]);

		  $KeyCheck = new AuthenticationHelper();
			$AuthorizationOk = $KeyCheck->GetAuthentication(Null,$dados['login'],$dados['password']);
			if ($AuthorizationOk)
			{
				return $dados;
			}else {
				return array( (object) array('EMPLOYMENTID' => 'denied'));
			}
		}

		public function listcandidatos(){
			$dados = func_get_args();
			$dados = array ('login' => $dados[0],
			                 'password' => $dados[1],
			                 'JSON' => $dados[2]);

		  $KeyCheck = new AuthenticationHelper();
			$AuthorizationOk = $KeyCheck->GetAuthentication(Null,$dados['login'],$dados['password']);
			if ($AuthorizationOk)
			{
				return $dados;
			}else {
				return array( (object) array('EMPLOYMENTID' => 'denied'));
			}
		}


	}
