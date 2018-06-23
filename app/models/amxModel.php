<?php
	Class amxModel extends Model {
		public $_table = " TABELA_TEMPORARIA ";

		public function callProcedure($name, $parans){
			return $this->procedure($name, $parans);
		}

		public function UpdateCandProcSel($cod_candidato){
			return $this->InsertUpdateDeleteManual('update candidato_processo_seletivo set data_envio_ppl = sysdate where cod_situacao_processo = 6 and data_envio_ppl IS NULL and cod_candidato = '. $cod_candidato );
		}


		public function readEmCadastramento(){
			$xml = $this->readManual("select COD_CANDIDATO , (SELECT CPF FROM CANDIDATO WHERE CODIGO = F.COD_CANDIDATO) CPF from  FUNCIONARIO F WHERE  SITUACAO = 'EM CADASTRAMENTO'");
			return $xml;
		}

		public function createXMLrpCompl($rp){
			$i = 0;

			/*<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:amx="http://xmlns.oracle.com/orawsv/RHOWS/AMX_RS_REQUISICAO_COMPL">
			   <soapenv:Header/>
			   <soapenv:Body>
			      <amx:AMX_RS_REQUISICAO_COMPLInput>
			         <amx:P_IDENTIFICACAO-VARCHAR2-IN>DEE315C7E8CBE23FFE7B9DE681881A97</amx:P_IDENTIFICACAO-VARCHAR2-IN>
			         <amx:P_NUM_REQUIS-NUMBER-IN>NNNNNNN</amx:P_NUM_REQUIS-NUMBER-IN>
			         <amx:P_RESULT-XMLTYPE-OUT/>
			      </amx:AMX_RS_REQUISICAO_COMPLInput>
			   </soapenv:Body>
			</soapenv:Envelope>*/

			$xml = 'http://192.168.218.168/webcamaleao/xml/RP7/Requisicao_'.$rp.'.xml';
			$xml = file_get_contents($xml);
			$xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
			$xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
			$xml = str_replace("<soap:Body>", '', $xml);
			$xml = str_replace('<AMX_RS_REQUISICAO_COMPLOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/AMX_RS_REQUISICAO_COMPL">', '', $xml);
			$xml = str_replace("</AMX_RS_REQUISICAO_COMPLOutput>", '', $xml);
			$xml = str_replace("</soap:Body>", '', $xml);
			$xml = str_replace("</soap:Envelope>", '', $xml);
			$xml = utf8_encode($xml);
			echo $xml;
			$xml = new SimpleXMLElement($xml);
			$result = $xml->xpath('//AMX_REQ_COMPL');
			$result =json_decode(json_encode($result));

			foreach ($result as $key => $value) {
				$xmldados = "<DADOS>";
				if(!is_object($value->NUM_REQUIS)) $xmldados .= "<NUM_REQUIS>".$value->NUM_REQUIS ."</NUM_REQUIS>";
				else  $xmldados .= "<NUM_REQUIS></NUM_REQUIS>";
				if(!is_object($value->MOTIVO)) $xmldados .= "<MOTIVO>".$value->MOTIVO ."</MOTIVO>";
				else  $xmldados .= "<MOTIVO></MOTIVO>";
				if(!is_object($value->GRADE)) $xmldados .= "<GRADE>".$value->GRADE ."</GRADE>";
				else  $xmldados .= "<GRADE></GRADE>";
				if(!is_object($value->STEP)) $xmldados .= "<STEP>".$value->STEP ."</STEP>";
				else  $xmldados .= "<STEP></STEP>";
				if(!is_object($value->TIPO_RECRUTAMENTO)) $xmldados .= "<TIPO_RECRUTAMENTO>".$value->TIPO_RECRUTAMENTO ."</TIPO_RECRUTAMENTO>";
				else  $xmldados .= "<TIPO_RECRUTAMENTO></TIPO_RECRUTAMENTO>";
				if(!is_object($value->DIRETORIA)) $xmldados .= "<DIRETORIA>".$value->DIRETORIA ."</DIRETORIA>";
				else  $xmldados .= "<DIRETORIA></DIRETORIA>";
				if(!is_object($value->POSICAO)) $xmldados .= "<POSICAO>".$value->POSICAO ."</POSICAO>";
				else  $xmldados .= "<POSICAO></POSICAO>";
				if(!is_object($value->REGIONAL)) $xmldados .= "<REGIONAL>".$value->REGIONAL ."</REGIONAL>";
				else  $xmldados .= "<REGIONAL></REGIONAL>";
				if(!is_object($value->CLUSTER)) $xmldados .= "<CLUSTER>".$value->CLUSTER ."</CLUSTER>";
				else  $xmldados .= "<CLUSTER></CLUSTER>";
				//if(!is_object($value->UNIDADE_NEGOCIOS)) $xmldados .= "<UNIDADE_NEGOCIOS>".$value->UNIDADE_NEGOCIOS ."</UNIDADE_NEGOCIOS>";
				//else  $xmldados .= "<UNIDADE_NEGOCIOS></UNIDADE_NEGOCIOS>";
				$xmldados .= "</DADOS>";

				$i=$i+1;
				//echo $xmldados;
				$Keydata = new KeyData();
        $Key      = $Keydata->geraKey(7, true, true, false);

				$tabletemp = new tabelatempModel();
				$db      = $tabletemp->integrationAction($xmldados, $Key,12);
				$xmldados = "";
			}

		}


		public function createXMLrpElegiveis($rp){
			$i = 0;
			/*<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rhow="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO_ELEG">
			<soapenv:Header/>
			<soapenv:Body>
				<rhow:RHOWS_RS_REQUISICAO_ELEGInput>
					<rhow:P_IDENTIFICACAO-VARCHAR2-IN></rhow:P_IDENTIFICACAO-VARCHAR2-IN>
					<rhows:P_NUM_REQUIS-NUMBER-IN></rhows:P_NUM_REQUIS-NUMBER-IN>
					<rhow:P_RESULT-XMLTYPE-OUT/>
				</rhow:RHOWS_RS_REQUISICAOELEGInput>
			</soapenv:Body>
			</soapenv:Envelope>*/

			$xml = 'http://192.168.218.168/webcamaleao/xml/RP7/elegiveis_'.$rp.'.xml';
			$xml = file_get_contents($xml);
			$xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
			$xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
			$xml = str_replace("<soap:Body>", '', $xml);
			$xml = str_replace('<RHOWS_RS_REQUISICAOOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO_ELEG">', '', $xml);
			$xml = str_replace('<RHOWS_RS_REQUISICAOOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO">', '', $xml);
			$xml = str_replace('<RHOWS_RS_REQ_ELEGOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQ_ELEG">', '', $xml);
			$xml = str_replace("</RHOWS_RS_REQUISICAOOutput>", '', $xml);
			$xml = str_replace("</RHOWS_RS_REQ_ELEGOutput>", '', $xml);
			$xml = str_replace("</soap:Body>", '', $xml);
			$xml = str_replace("</soap:Envelope>", '', $xml);
			$xml = utf8_encode($xml);
			$xml = new SimpleXMLElement($xml);
			$result = $xml->xpath('//RequisicaoELEGIVEIS');
      		$result =json_decode(json_encode($result));
			$result = ($result[0]->MATRICULA);
			$arrlength = count($result) ;
			echo $arrlength;
			$i = 0;
			if ($arrlength >> 0) {
				while ($i < $arrlength ) {
					//echo $result[$i] . ' / ' ;
					$db = $this->InsertUpdateDeleteManual("insert into funcionarios_elegiveis(cod_candidato, numero_vaga_claro, matricula, cod_proc_seletivo) values ( (select max(cod_candidato)  from funcionario where matricula = '".$result[$i]."'),".$rp." , ".$result[$i]." , (select codigo from vw_processo_seletivo where numero_vaga_claro = ".$rp." ))  "  );
					$i = $i + 1;
				}	
			}else{
				echo("entrou");
				$Arrycod_candidato['list'] =  $this->readManual("select COD_CANDIDATO,MATRICULA FROM FUNCIONARIO WHERE  SITUACAO in ('EM CADASTRAMENTO','ATIVO')");
				foreach ($Arrycod_candidato['list'] as $key => $value) {
					$cod_candidato =   $value->COD_CANDIDATO;
					$matricula	   =   $value->MATRICULA;
					$db = $this->InsertUpdateDeleteManual("insert into funcionarios_elegiveis(cod_candidato, numero_vaga_claro, matricula, cod_proc_seletivo) values ( ".$cod_candidato.",".$rp." , ".$matricula." , (select codigo from vw_processo_seletivo where numero_vaga_claro = ".$rp." )) "  );
				}
					
			}
				
		}


		public function createXMLrp(array $result){
			$i = 0;
			foreach ($result as $key => $value) {
				$xmldados = "<DADOS>";
				$rp	=	$value->REQP_SEQ;
				if(!is_object($value->REQP_SEQ)) $xmldados .= "<REQP_SEQ>".$value->REQP_SEQ ."</REQP_SEQ>";
				else  $xmldados .= "<REQP_SEQ></REQP_SEQ>";
				if(!is_object($value->REQP_SEQ)) $xmldados .= "<NOME>".$value->REQP_SEQ .'-'. $value->CARGO."</NOME>";
				else  $xmldados .= "<NOME></NOME>";
				if(!is_object($value->CODIGO_EMPRESA)) $xmldados .= "<COD_EMPRESA>".$value->CODIGO_EMPRESA."</COD_EMPRESA>";
				else  $xmldados .= "<COD_EMPRESA></COD_EMPRESA>";
				if(!is_object($value->EMPRESA)) $xmldados .= "<NOME_EMPRESA>".$value->EMPRESA."</NOME_EMPRESA>";
				else  $xmldados .= "<NOME_EMPRESA></NOME_EMPRESA>";
				if(!is_object($value->CODIGO_UNIDADE)) $xmldados .= "<CODIGO_UNIDADE>".$value->CODIGO_UNIDADE."</CODIGO_UNIDADE>";
				else  $xmldados .= "<CODIGO_UNIDADE></CODIGO_UNIDADE>";
				if(!is_object($value->UNIDADE_ORGANIZACIONAL)) $xmldados .= "<UNIDADE_ORGANIZACIONAL>".$value->UNIDADE_ORGANIZACIONAL."</UNIDADE_ORGANIZACIONAL>";
				else  $xmldados .= "<UNIDADE_ORGANIZACIONAL></UNIDADE_ORGANIZACIONAL>";
				if(!is_object($value->DATA_REQUISICAO)) $xmldados .= "<DATA_REQUISICAO>".$value->DATA_REQUISICAO."</DATA_REQUISICAO>";
				else  $xmldados .= "<DATA_REQUISICAO></DATA_REQUISICAO>";
				if(!is_object($value->DATA_IMPORTACAO)) $xmldados .= "<DATA_IMPORTACAO>".$value->DATA_IMPORTACAO."</DATA_IMPORTACAO>";
				else  $xmldados .= "<DATA_IMPORTACAO></DATA_IMPORTACAO>";
				if(!is_object($value->ID_REQUISITANTE)) $xmldados .= "<ID_REQUISITANTE>".$value->ID_REQUISITANTE."</ID_REQUISITANTE>";
				else  $xmldados .= "<ID_REQUISITANTE></ID_REQUISITANTE>";
				if(!is_object($value->REQUISITANTE)) $xmldados .= "<REQUISITANTE>".$value->REQUISITANTE."</REQUISITANTE>";
				else  $xmldados .= "<REQUISITANTE></REQUISITANTE>";
				if(!is_object($value->ID_GESTOR_REQUISICAO)) $xmldados .= "<ID_GESTOR_REQUISICAO>".$value->ID_GESTOR_REQUISICAO."</ID_GESTOR_REQUISICAO>";
				else  $xmldados .= "<ID_GESTOR_REQUISICAO></ID_GESTOR_REQUISICAO>";
				if(!is_object($value->GESTOR_REQUISICAO)) $xmldados .= "<GESTOR_REQUISICAO>".$value->GESTOR_REQUISICAO."</GESTOR_REQUISICAO>";
				else  $xmldados .= "<GESTOR_REQUISICAO></GESTOR_REQUISICAO>";
				if(!is_object($value->ID_CARGO)) $xmldados .= "<ID_CARGO>".$value->ID_CARGO."</ID_CARGO>";
				else  $xmldados .= "<ID_CARGO></ID_CARGO>";
				if(!is_object($value->CARGO)) $xmldados .= "<CARGO>".$value->CARGO."</CARGO>";
				else  $xmldados .= "<CARGO></CARGO>";
				if(!is_object($value->CODIGO_TIPO_COLABORADOR)) $xmldados .= "<CODIGO_TIPO_COLABORADOR>".$value->CODIGO_TIPO_COLABORADOR."</CODIGO_TIPO_COLABORADOR>";
				else  $xmldados .= "<CODIGO_TIPO_COLABORADOR></CODIGO_TIPO_COLABORADOR>";
				if(!is_object($value->TIPO_COLABORADOR)) $xmldados .= "<TIPO_COLABORADOR>".$value->TIPO_COLABORADOR."</TIPO_COLABORADOR>";
				else  $xmldados .= "<TIPO_COLABORADOR></TIPO_COLABORADOR>";
				if(!is_object($value->SALARIO)) $xmldados .= "<SALARIO>".$value->SALARIO."</SALARIO>";
				else  $xmldados .= "<SALARIO></SALARIO>";
				if(!is_object($value->SIGLA_PAIS_LOCAL)) $xmldados .= "<SIGLA_PAIS_LOCAL>".$value->SIGLA_PAIS_LOCAL."</SIGLA_PAIS_LOCAL>";
				else  $xmldados .= "<SIGLA_PAIS_LOCAL></SIGLA_PAIS_LOCAL>";
				if(!is_object($value->PAIS_LOCAL)) $xmldados .= "<PAIS_LOCAL>".$value->PAIS_LOCAL."</PAIS_LOCAL>";
				else  $xmldados .= "<PAIS_LOCAL></PAIS_LOCAL>";
				if(!is_object($value->SIGLA_ESTADO_LOCAL)) $xmldados .= "<SIGLA_ESTADO_LOCAL>".$value->SIGLA_ESTADO_LOCAL."</SIGLA_ESTADO_LOCAL>";
				else  $xmldados .= "<SIGLA_ESTADO_LOCAL></SIGLA_ESTADO_LOCAL>";
				if(!is_object($value->ESTADO_LOCAL)) $xmldados .= "<ESTADO_LOCAL>".$value->ESTADO_LOCAL."</ESTADO_LOCAL>";
				else  $xmldados .= "<ESTADO_LOCAL></ESTADO_LOCAL>";
				if(!is_object($value->CIDADE_LOCAL)) $xmldados .= "<CIDADE_LOCAL>".$value->CIDADE_LOCAL."</CIDADE_LOCAL>";
				else  $xmldados .= "<CIDADE_LOCAL></CIDADE_LOCAL>";
				if(!is_object($value->CODIGO_AREA_ATUACAO)) $xmldados .= "<CODIGO_AREA_ATUACAO>".$value->CODIGO_AREA_ATUACAO."</CODIGO_AREA_ATUACAO>";
				else  $xmldados .= "<CODIGO_AREA_ATUACAO></CODIGO_AREA_ATUACAO>";
				if(!is_object($value->AREA_ATUACAO)) $xmldados .= "<AREA_ATUACAO>".$value->AREA_ATUACAO."</AREA_ATUACAO>";
				else  $xmldados .= "<AREA_ATUACAO></AREA_ATUACAO>";
				if(!is_object($value->POSICOES)) $xmldados .= "<POSICOES>".$value->POSICOES."</POSICOES>";
				else  $xmldados .= "<POSICOES></POSICOES>";
				if(!is_object($value->JUSTIFICATIVA)) $xmldados .= "<JUSTIFICATIVA>".$value->JUSTIFICATIVA."</JUSTIFICATIVA>";
				else  $xmldados .= "<JUSTIFICATIVA></JUSTIFICATIVA>";
				if(!is_object($value->OBSERVACAO)) $xmldados .= "<OBSERVACAO>".$value->OBSERVACAO."</OBSERVACAO>";
				else  $xmldados .= "<OBSERVACAO></OBSERVACAO>";
				if(!is_object($value->ID_SELEC_RESP)) $xmldados .= "<ID_SELEC_RESP>".$value->ID_SELEC_RESP."</ID_SELEC_RESP>";
				else  $xmldados .= "<ID_SELEC_RESP></ID_SELEC_RESP>";
				if(!is_object($value->NOME_SELEC_RESP)) $xmldados .= "<NOME_SELEC_RESP>".$value->NOME_SELEC_RESP."</NOME_SELEC_RESP>";
				else  $xmldados .= "<NOME_SELEC_RESP></NOME_SELEC_RESP>";
				if(!is_object($value->EMAIL_SELEC_RESP)) $xmldados .= "<EMAIL_SELEC_RESP>".$value->EMAIL_SELEC_RESP."</EMAIL_SELEC_RESP>";
				else  $xmldados .= "<EMAIL_SELEC_RESP></EMAIL_SELEC_RESP>";

				$xmldadosreq = "<DADOS>";
				if(!is_object($value->REQP_SEQ)) {
						$xmldadosreq .= "<REQP_SEQ>".$value->REQP_SEQ ."</REQP_SEQ>";
					} else {
						$xmldadosreq .= "<REQP_SEQ></REQP_SEQ>" ;
					}
 				$xmldadosreq .= "</DADOS>";
				$Keydata = new KeyData();
				$Key      = $Keydata->geraKey(7, true, true, false);

				$tabletemp = new tabelatempModel();
				$db      = $tabletemp->integrationAction($xmldadosreq, $Key,15);



				$requisitos= $result[$i]->REQUISITOS;
				if(isset($requisitos->RequisicaoREQUISITOS)){
					$valarray = count($requisitos->RequisicaoREQUISITOS);
					if (count($requisitos->RequisicaoREQUISITOS) == 1){
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'PCD DOMINIO SIM OU NAO'){
							$db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','PCD', XMLType('<PCD>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</PCD>'))   where existsnode(ps.arquivo_xml,'/DADOS/PCD')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'FORMACAO ACADEMICA'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','FORMACAO_ACADEMICA', XMLType('<FORMACAO_ACADEMICA>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</FORMACAO_ACADEMICA>'))   where existsnode(ps.arquivo_xml,'/DADOS/FORMACAO_ACADEMICA')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'EXPERIENCIA PROFISSIONAL'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','EXPERIENCIA_PROFISSIONAL', XMLType('<EXPERIENCIA_PROFISSIONAL>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</EXPERIENCIA_PROFISSIONAL>'))   where existsnode(ps.arquivo_xml,'/DADOS/EXPERIENCIA_PROFISSIONAL')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
						}

						if ($requisitos->RequisicaoREQUISITOS->NOME == '01 INGLES AVANCADO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','INGLES_AVANCADO', XMLType('<INGLES_AVANCADO>".substr($requisitos->RequisicaoREQUISITOS->NOME, 0, 3500)."</INGLES_AVANCADO>'))   where existsnode(ps.arquivo_xml,'/DADOS/INGLES_AVANCADO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == '02 INGLES INTERMEDIARIO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','INGLES_INTERMEDIARIO', XMLType('<INGLES_INTERMEDIARIO>".substr($requisitos->RequisicaoREQUISITOS->NOME, 0, 3500)."</INGLES_INTERMEDIARIO>'))   where existsnode(ps.arquivo_xml,'/DADOS/INGLES_INTERMEDIARIO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == '03 INGLES BASICO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','INGLES_BASICO', XMLType('<INGLES_BASICO>".substr($requisitos->RequisicaoREQUISITOS->NOME, 0, 3500)."</INGLES_BASICO>'))   where existsnode(ps.arquivo_xml,'/DADOS/INGLES_BASICO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == '04 ESPANHOL AVANCADO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','ESPANHOL_AVANCADO', XMLType('<ESPANHOL_AVANCADO>".substr($requisitos->RequisicaoREQUISITOS->NOME, 0, 3500)."</ESPANHOL_AVANCADO>'))   where existsnode(ps.arquivo_xml,'/DADOS/ESPANHOL_AVANCADO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == '05 ESPANHOL INTERMEDIARIO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','ESPANHOL_INTERMEDIARIO', XMLType('<ESPANHOL_INTERMEDIARIO>".substr($requisitos->RequisicaoREQUISITOS->NOME, 0, 3500)."</ESPANHOL_INTERMEDIARIO>'))   where existsnode(ps.arquivo_xml,'/DADOS/ESPANHOL_INTERMEDIARIO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == '06 ESPANHOL BASICO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','ESPANHOL_BASICO', XMLType('<ESPANHOL_BASICO>".substr($requisitos->RequisicaoREQUISITOS->NOME, 0, 3500)."</ESPANHOL_BASICO>'))   where existsnode(ps.arquivo_xml,'/DADOS/ESPANHOL_BASICO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
						}

						if ($requisitos->RequisicaoREQUISITOS->NOME == 'HORARIO DE TRABALHO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','HORARIO_TRABALHO', XMLType('<HORARIO_TRABALHO>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</HORARIO_TRABALHO>'))   where existsnode(ps.arquivo_xml,'/DADOS/HORARIO_TRABALHO')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'OUTROS'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','OUTROS', XMLType('<OUTROS>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</OUTROS>'))   where existsnode(ps.arquivo_xml,'/DADOS/OUTROS')=0  and cod_integracao = 15 and key_data = '". $Key . " ." );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'CONHECIMENTO TECNICO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','CONCHECIMENTO_TECNICO', XMLType('<CONCHECIMENTO_TECNICO>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</CONCHECIMENTO_TECNICO>'))   where existsnode(ps.arquivo_xml,'/DADOS/CONCHECIMENTO_TECNICO')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'RESPONSABILIDADE DO CARGO'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','RESPONSABILIDADES', XMLType('<RESPONSABILIDADES>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</RESPONSABILIDADES>'))   where existsnode(ps.arquivo_xml,'/DADOS/RESPONSABILIDADES')=0  and cod_integracao = 15 and key_data = '". $Key. "'"  );
						}
						if ($requisitos->RequisicaoREQUISITOS->NOME == 'CARACTERISTICAS COMPORTAMENTAIS'){
							 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','CARACTERISTICAS', XMLType('<CARACTERISTICAS>".substr($requisitos->RequisicaoREQUISITOS->DESCRICAO, 0, 3500)."</CARACTERISTICAS>'))   where existsnode(ps.arquivo_xml,'/DADOS/CARACTERISTICAS')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
						}
					}else{
						foreach($requisitos->RequisicaoREQUISITOS as $key=>$value){

								if ($value->NOME == 'PCD DOMINIO SIM OU NAO'){
									$db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','PCD', XMLType('<PCD>".substr($value->DESCRICAO, 0, 2000)."</PCD>'))   where existsnode(ps.arquivo_xml,'/DADOS/PCD')=0  and cod_integracao = 15 and key_data = '". $Key. "'"  );
								}
 								if ($value->NOME == 'FORMACAO ACADEMICA'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','FORMACAO_ACADEMICA', XMLType('<FORMACAO_ACADEMICA>".substr($value->DESCRICAO, 0, 2000)."</FORMACAO_ACADEMICA>'))   where existsnode(ps.arquivo_xml,'/DADOS/FORMACAO_ACADEMICA')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
								}
								if ($value->NOME == 'EXPERIENCIA PROFISSIONAL'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','EXPERIENCIA_PROFISSIONAL', XMLType('<EXPERIENCIA_PROFISSIONAL>".substr($value->DESCRICAO, 0, 2000)."</EXPERIENCIA_PROFISSIONAL>'))   where existsnode(ps.arquivo_xml,'/DADOS/EXPERIENCIA_PROFISSIONAL')=0  and cod_integracao = 15 and key_data = '". $Key . "'" );
								}
								if ($value->NOME == 'IDIOMA'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','IDIOMAS', XMLType('<IDIOMAS>".substr($value->DESCRICAO, 0, 2000)."</IDIOMAS>'))   where existsnode(ps.arquivo_xml,'/DADOS/IDIOMAS')=0  and cod_integracao = 15 and key_data = '". $Key. "'"  );
								}
								if ($value->NOME == '01 INGLES AVANCADO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','INGLES_AVANCADO', XMLType('<INGLES_AVANCADO>".substr($value->NOME, 0, 3500)."</INGLES_AVANCADO>'))   where existsnode(ps.arquivo_xml,'/DADOS/INGLES_AVANCADO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == '02 INGLES INTERMEDIARIO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','INGLES_INTERMEDIARIO', XMLType('<INGLES_INTERMEDIARIO>".substr($value->NOME, 0, 3500)."</INGLES_INTERMEDIARIO>'))   where existsnode(ps.arquivo_xml,'/DADOS/INGLES_INTERMEDIARIO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == '03 INGLES BASICO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','INGLES_BASICO', XMLType('<INGLES_BASICO>".substr($value->NOME, 0, 3500)."</INGLES_BASICO>'))   where existsnode(ps.arquivo_xml,'/DADOS/INGLES_BASICO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == '04 ESPANHOL AVANCADO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','ESPANHOL_AVANCADO', XMLType('<ESPANHOL_AVANCADO>".substr($value->NOME, 0, 3500)."</ESPANHOL_AVANCADO>'))   where existsnode(ps.arquivo_xml,'/DADOS/ESPANHOL_AVANCADO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == '05 ESPANHOL INTERMEDIARIO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','ESPANHOL_INTERMEDIARIO', XMLType('<ESPANHOL_INTERMEDIARIO>".substr($value->NOME, 0, 3500)."</ESPANHOL_INTERMEDIARIO>'))   where existsnode(ps.arquivo_xml,'/DADOS/ESPANHOL_INTERMEDIARIO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == '06 ESPANHOL BASICO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','ESPANHOL_BASICO', XMLType('<ESPANHOL_BASICO>".substr($value->NOME, 0, 3500)."</ESPANHOL_BASICO>'))   where existsnode(ps.arquivo_xml,'/DADOS/ESPANHOL_BASICO')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}


								if ($value->NOME == 'HORARIO DE TRABALHO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','HORARIO_TRABALHO', XMLType('<HORARIO_TRABALHO>".substr($value->DESCRICAO, 0, 2000)."</HORARIO_TRABALHO>'))   where existsnode(ps.arquivo_xml,'/DADOS/HORARIO_TRABALHO')=0  and cod_integracao = 15 and key_data = '". $Key. "'"  );
								}
								if ($value->NOME == 'OUTROS'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','OUTROS', XMLType('<OUTROS>".substr($value->DESCRICAO, 0, 2000)."</OUTROS>'))   where existsnode(ps.arquivo_xml,'/DADOS/OUTROS')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == 'CONHECIMENTO TECNICO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','CONCHECIMENTO_TECNICO', XMLType('<CONCHECIMENTO_TECNICO>".substr($value->DESCRICAO, 0, 2000)."</CONCHECIMENTO_TECNICO>'))   where existsnode(ps.arquivo_xml,'/DADOS/CONCHECIMENTO_TECNICO')=0  and cod_integracao = 15 and key_data = '". $Key. "'");
								}
								if ($value->NOME == 'RESPONSABILIDADE DO CARGO'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','RESPONSABILIDADES', XMLType('<RESPONSABILIDADES>".substr($value->DESCRICAO, 0, 2000)."</RESPONSABILIDADES>'))   where existsnode(ps.arquivo_xml,'/DADOS/RESPONSABILIDADES')=0  and cod_integracao = 15 and key_data = '". $Key . "'"  );
								}
								if ($value->NOME == 'CARACTERISTICAS COMPORTAMENTAIS'){
									 $db =  $this->InsertUpdateDeleteManual("update TABELA_TEMPORARIA ps set ps.arquivo_xml = insertChildXML(ps.arquivo_xml,'/DADOS','CARACTERISTICAS', XMLType('<CARACTERISTICAS>".substr($value->DESCRICAO, 0, 2000)."</CARACTERISTICAS>'))   where existsnode(ps.arquivo_xml,'/DADOS/CARACTERISTICAS')=0  and cod_integracao = 15 and key_data = '". $Key. "'"   );
								}

							}
					}
				}

				$requisitos= $result[$i]->DADOS_ADICIONAIS;
				if(isset($requisitos->RequisicaoDADOS_ADICIONAIS)){
					 $xmldados .= '<TIPO_ATIVIDADE>'.$requisitos->RequisicaoDADOS_ADICIONAIS->CONTEUDO.'</TIPO_ATIVIDADE>';
				}

				$xmldados .= "</DADOS>";

				$i=$i+1;
				//echo $xmldados;

				$Key      = $Keydata->geraKey(7, true, true, false);
				$tabletemp = new tabelatempModel();
				$db      = $tabletemp->integrationAction($xmldados, $Key,11);

				$db      = $this->createXMLrpCompl($rp);
				//$db      = $this->createXMLrpElegiveis($rp);
				
				$xmldados = "";
			}
			 return $xmldados;

		}


		public function createXMLrpSit(array $result){
			$i = 0;
			foreach ($result as $key => $value) {
				$xmldados = "<DADOS>";
        		$xmldados .=  !is_object($value->NUM_REQUIS) ? "<NUM_REQUIS>".$value->NUM_REQUIS ."</NUM_REQUIS>" : "<NUM_REQUIS></NUM_REQUIS>";
				$xmldados .=  !is_object($value->SITUACAO) ? "<SITUACAO>".$value->SITUACAO ."</SITUACAO>" : "<SITUACAO></SITUACAO>";
				$xmldados .=  !is_object($value->DATA_SITUACAO) ? "<DATA_SITUACAO>".$value->DATA_SITUACAO ."</DATA_SITUACAO>" : "<DATA_SITUACAO></NUM_REQUIS>";
				$xmldados .= "</DADOS>";
				$i=$i+1;
				//echo $xmldados;
				$Keydata = new KeyData();
				$Key      = $Keydata->geraKey(7, true, true, false);
				$tabletemp = new tabelatempModel();
				$db      = $tabletemp->integrationAction($xmldados, $Key,16);
				$xmldados = "";
			}
			 return $xmldados;

		}



	}
