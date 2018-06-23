<?php
class Amxv7 extends Controller{

  public function amx_rp_teste()
      {
        $xml = 'http://192.168.218.168/webcamaleao/xml/novas2/Servico_Requisicao_Teste_SIT.xml';

        $xml = file_get_contents($xml);
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
        $xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
        $xml = str_replace("<soap:Body>", '', $xml);
        $xml = str_replace('<RHOWS_RS_REQUISICAOOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO">', '', $xml);
        $xml = str_replace("</RHOWS_RS_REQUISICAOOutput>", '', $xml);
        $xml = str_replace("</soap:Body>", '', $xml);
        $xml = str_replace("</soap:Envelope>", '', $xml);
        $xml = utf8_encode($xml);
        $xml = new SimpleXMLElement($xml);
        $result = $xml->xpath('//requisicao');
        $result =json_decode(json_encode($result));
        print_r ($result);
        foreach ($result as $key => $value) {
          echo $key;
        }

      }

  		public function createXMLrpElegiveis(){
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
  
        $xml = 'http://192.168.218.168/webcamaleao/xml/RP7/elegiveis_48105.xml';
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
        //echo $xml;
        $xml = new SimpleXMLElement($xml);
        $result = $xml->xpath('//RequisicaoELEGIVEIS');

        $result =json_decode(json_encode($result));
        $result = ($result[0]->MATRICULA);
        print_r($result);
        $arrlength = count($result) ;
          $i = 0;
        //while ($i < $arrlength ) {
        //  echo $result[$i] . ' / ' ;
          //$db = $this->InsertUpdateDeleteManual("insert into funcionarios_elegiveis(cod_candidato, numero_vaga_claro, matricula) values ( (select max(cod_candidato)  from funcionario where matricula = '".$result[$i]."'),".$rp." , ".$result[$i]." ) "  );
        //  $i = $i + 1;
        //}	
          
      }    

  public function amx_rp()
      {
      /* <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rhow="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO">
           <soapenv:Header/>
           <soapenv:Body>
              <rhow:RHOWS_RS_REQUISICAOInput>
                 <rhow:P_IDENTIFICACAO-VARCHAR2-IN>DEE315C7E8CBE23FFE7B9DE681881A97</rhow:P_IDENTIFICACAO-VARCHAR2-IN>
                 <rhow:P_RESULT-XMLTYPE-OUT/>
              </rhow:RHOWS_RS_REQUISICAOInput>
           </soapenv:Body>
        </soapenv:Envelope>  */

        $xml = 'http://192.168.218.168/webcamaleao/xml/RP7/requisicao_rp.xml';
        $xml = file_get_contents($xml);
   //     $xml = utf8_decode ( $xml ); 
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
        $xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
        $xml = str_replace("<soap:Body>", '', $xml);
        $xml = str_replace('<RHOWS_RS_REQUISICAOOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO">', '', $xml);
        $xml = str_replace("</RHOWS_RS_REQUISICAOOutput>", '', $xml);
        $xml = str_replace("</soap:Body>", '', $xml);
        $xml = str_replace("</soap:Envelope>", '', $xml);
   //     $xml = utf8_encode($xml);
        $xml = new SimpleXMLElement($xml);


        $sql="declare
              c1 clob;
              c2 varchar2(32000);
            begin
              c1 := 'string';
              c2 := '".$xmldados."';
              dbms_lob.append(c1, c2);
              INSERT INTO  integracao_wsdl_log (cod_integracao, data_envio,   msg_resposta,valor_campo, tip_reg, result2) VALUES
                                                (11, SYSDATE ,  'XML Recebido pela Integração =>".$value->REQP_SEQ."', ".$value->REQP_SEQ.",  1, c1);

              commit;                                  
            end;"



        $result = $xml->xpath('//requisicao');
        $result =json_decode(json_encode($result));

       $rp = new amxModel();
       $db      = $rp->createXMLrp($result);
       $rp = new amxModel();
       $db      = $rp->callProcedure('prc_proc_seletivo_webservice', null);
       echo 'ok';
      }

      public function amx_rp_situation()
          {
            $xml = 'http://192.168.218.168/webcamaleao/xml/SITUACAO/rp_fechamento.xml';
            $xml = file_get_contents($xml);
            $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
            $xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
            $xml = str_replace("<soap:Body>", '', $xml);
            $xml = str_replace('<RHOWS_RS_REQ_ENCOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQ_ENC">', '', $xml);
            $xml = str_replace("</RHOWS_RS_REQ_ENCOutput>", '', $xml);
            $xml = str_replace("</soap:Body>", '', $xml);
            $xml = str_replace("</soap:Envelope>", '', $xml);
            $xml = utf8_encode($xml);
            $xml = new SimpleXMLElement($xml);
            $result = $xml->xpath('//REQ');
            $result =json_decode(json_encode($result));
           $rp = new amxModel();
           $db      = $rp->createXMLrpSit($result);
           $rp = new amxModel();
           $db      = $rp->callProcedure('PRC_PROC_SEL_SIT_WEBSERVICE', null);
           echo 'ok';
          }



  public function amx_employee()
      {
        ini_set('memory_limit', '-1');

        $integration = $this->getParam('integretion');
        if ($integration == 'ativo') { $xml = 'http://192.168.218.168/webcamaleao/xml/contratados.xml';
        }else if ($integration == 'desligado') { $xml = 'http://192.168.218.168/webcamaleao/xml/demitidos.xml';
        }else if ($integration == 'cadastramento') { $xml = 'http://192.168.218.168/webcamaleao/xml/emespera/cadastramento.xml';}

        $xml = file_get_contents($xml);
        $xml = str_replace("<?xml version='1.0' encoding='UTF-8'?>", '', $xml);
        $xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
        $xml = str_replace("<soap:Body>", '', $xml);
        $xml = str_replace('<AMX_RS_DADOS_CADASTRAISOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/AMX_RS_DADOS_CADASTRAIS">', '', $xml);
        $xml = str_replace("</AMX_RS_DADOS_CADASTRAISOutput>", '', $xml);
        $xml = str_replace("</soap:Body>", '', $xml);
        $xml = str_replace("</soap:Envelope>", '', $xml);
        $xml = str_replace("\n", '', $xml);
        $xml = utf8_encode($xml);
        $xml = new SimpleXMLElement($xml);
        $result = $xml->xpath('//FUNCIONARIO');
        $result =json_decode(json_encode($result));

        foreach ($result as $key => $value) {
						$Keydata = new KeyData();
						$Key      = $Keydata->geraKey(7, true, true, false);
						$xmldados = "<DADOS>";
						if (array_key_exists("MATRICULA", $value)){
							if(!is_object($value->MATRICULA)) $xmldados .= "<MATRICULA>$value->MATRICULA</MATRICULA>";
							else  $xmldados .= "<MATRICULA></MATRICULA>";
						}else{ $xmldados .= "<MATRICULA></MATRICULA>"; }
						if (array_key_exists("NOME", $value)){
							if(!is_object($value->NOME)) $xmldados .= "<NOME>".(string) $value->NOME."</NOME>";
							else  $xmldados .= "<NOME></NOME>";
						}else{ $xmldados .= "<NOME></NOME>"; }
						if (array_key_exists("DATA_ADMISSAO", $value)){
							if(!is_object($value->DATA_ADMISSAO)) $xmldados .= "<DATA_ADMISSAO>$value->DATA_ADMISSAO</DATA_ADMISSAO>";
							else  $xmldados .= "<DATA_ADMISSAO></DATA_ADMISSAO>";
						}else{ $xmldados .= "<DATA_ADMISSAO></DATA_ADMISSAO>"; }
						if (array_key_exists("DATA_NASCIMENTO", $value)){
							if(!is_object($value->DATA_NASCIMENTO)) $xmldados .= "<DATA_NASCIMENTO>$value->DATA_NASCIMENTO</DATA_NASCIMENTO>";
							else  $xmldados .= "<DATA_NASCIMENTO></DATA_NASCIMENTO>";
						}else{  $xmldados .= "<DATA_NASCIMENTO></DATA_NASCIMENTO>"; }
						if (array_key_exists("CPF", $value)){
							if(!is_object($value->CPF)) $xmldados .= "<CPF>$value->CPF</CPF>";
							else  $xmldados .= "<CPF></CPF>";
						}else{    $xmldados .= "<CPF></CPF>"; }
						if (array_key_exists("SEXO", $value)){
							if(!is_object($value->SEXO)) $xmldados .= "<SEXO>$value->SEXO</SEXO>";
							else  $xmldados .= "<SEXO></SEXO>";
						}else{ $xmldados .= "<SEXO></SEXO>"; }
						if (array_key_exists("TIPO_COLAB_COD", $value)){
							if(!is_object($value->TIPO_COLAB_COD)) $xmldados .= "<TIPO_COLAB_COD>$value->TIPO_COLAB_COD</TIPO_COLAB_COD>";
							else  $xmldados .= "<TIPO_COLAB_COD></TIPO_COLAB_COD>";
					 }else{ $xmldados .= "<TIPO_COLAB_COD></TIPO_COLAB_COD>"; }
					 if (array_key_exists("TIPO_COLAB", $value)){
							if(!is_object($value->TIPO_COLAB)) $xmldados .= "<TIPO_COLAB>$value->TIPO_COLAB</TIPO_COLAB>";
							else  $xmldados .= "<TIPO_COLAB></TIPO_COLAB>";
					 }else{ $xmldados .= "<TIPO_COLAB></TIPO_COLAB>"; }
					 if (array_key_exists("NOME_GESTOR", $value)){
							if(!is_object($value->NOME_GESTOR)) $xmldados .= "<NOME_GESTOR>$value->NOME_GESTOR</NOME_GESTOR>";
							else  $xmldados .= "<NOME_GESTOR></NOME_GESTOR>";
						}else{ $xmldados .= "<NOME_GESTOR></NOME_GESTOR>";}
					 if (array_key_exists("EMAIL_GESTOR", $value)){
							if(!is_object($value->EMAIL_GESTOR)) $xmldados .= "<EMAIL_GESTOR>$value->EMAIL_GESTOR</EMAIL_GESTOR>";
							else  $xmldados .= "<EMAIL_GESTOR></EMAIL_GESTOR>";
					 }else{ $xmldados .= "<EMAIL_GESTOR></EMAIL_GESTOR>"; }
					 if (array_key_exists("NIVEL_GRADE", $value)){
							if(!is_object($value->NIVEL_GRADE)) $xmldados .= "<NIVEL_GRADE>$value->NIVEL_GRADE</NIVEL_GRADE>";
							else  $xmldados .= "<NIVEL_GRADE></NIVEL_GRADE>";
					 }else{ $xmldados .= "<NIVEL_GRADE></NIVEL_GRADE>";  }
					 if (array_key_exists("UNIDADE", $value)){
								if(!is_object($value->UNIDADE)) $xmldados .= "<UNIDADE>$value->UNIDADE</UNIDADE>";
								else  $xmldados .= "<UNIDADE></UNIDADE>";
					 }else{ $xmldados .= "<UNIDADE></UNIDADE>"; }
						$xmldados .= "</DADOS>";

            $insertfunc = new tabelatempModel();
						if ($integration == 'ativo') {
							$db      = $insertfunc->integrationAction($xmldados, $Key,8);
						}else if ($integration == 'desligado') {
							$db      = $insertfunc->integrationAction($xmldados, $Key,9);
						}else if ($integration == 'cadastramento') {
							$db      = $insertfunc->integrationAction($xmldados, $Key,10);
						}
			}
      $insertfunc = new amxModel();
			if ($integration == 'ativo') {
				$db      = $insertfunc->callProcedure('prc_func_ativo_webservice', null);
			}else if ($integration == 'desligado') {
				$db      = $insertfunc->callProcedure('prc_func_desligado_webservice', null);
			}else if ($integration == 'cadastramento') {
				$db      = $insertfunc->callProcedure('prc_func_cadastra_webservice', null);
			}

      echo 'Executado com sucesso!';
  }

  public function amx_new_employee()
      {
        $readEnviar = new contratadosModel();
        $Arrycod_candidato['list'] = $readEnviar->readEmCadastramentoCod('cod_candidato',null,null);
        print_r($Arrycod_candidato['list']);
        foreach ($Arrycod_candidato['list'] as $key => $value) {
            $cod_candidato =   $value->COD_CANDIDATO;
            echo $cod_candidato;
            $readNewEmployees = new contratadosModel();
            $datas['list'] = $readNewEmployees->readNewEmployees('cod_candidato = '.$cod_candidato);
          //  print_r ($datas['list']);
            $Arrlength = count($datas['list']);
            for ($x = 0; $x <$Arrlength; ++ $x) {
                //echo $datas['list'][$x];
                //echo " ";
                $xmlfile = '';
                $xmlfile = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rhow="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_CANDIDATO"><soapenv:Header/> ';
                $xmlfile .= $datas['list'][$x];
                $xmlfile .= '</soapenv:Envelope>';

                $dom = new DOMDocument('1.0', 'utf-8');
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($xmlfile);
                $dom->formatOutput = TRUE;
                $xmlfile = $dom->saveXml($dom->documentElement);

                $xmlfile = str_replace('<?xml version="1.0"?>', '', $xmlfile);
                $xmlfile = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $xmlfile);

                $xmlfile = utf8_decode ( $xmlfile );

                $fp = fopen('cadastro_contratado'.$cod_candidato.'_'.$x.'.xml', 'w+');
                fwrite($fp, $xmlfile);
                fclose($fp);
            }
            //$dataEnvioPL = new amxModel();
            //$result = $dataEnvioPL->UpdateCandProcSel($cod_candidato);

        }
    }

  public function amx_dependents()
      {
        $readEmCadastramento = new amxModel();
        $Arrycod_candidato= $readEmCadastramento->readEmCadastramento();
  //    print_r ($Arrycod_candidato);
        $codLenght= count($Arrycod_candidato);
        for ($x = 0; $x <$codLenght; $x++) {
          //print_r ($Arrycod_candidato[$x]);
          //die;
          $cod_candidato = ($Arrycod_candidato[$x]->COD_CANDIDATO) ;
          $cpf_candidato = ($Arrycod_candidato[$x]->CPF) ;
          echo($cod_candidato . ' <br> ');
          $readNewDependents = new dependentesModel();
          $datas = $readNewDependents->readDependentes(' cod_candidato = '. $cod_candidato);
          $Arrlength = count($datas);
          if ($Arrlength >> 0) {
              echo($Arrlength);
              for ($i = 0; $i < $Arrlength;  $i++) {
                  $xmlfile = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rhow="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_DEPENDENTES_AUX"> <soapenv:Header/>';
                  $xmlfile .= $datas[$i];
                  $xmlfile .= '</soapenv:Envelope>';
                  echo($xmlfile);
                  echo('entrou ' . $i );

                  $dom = new DOMDocument;
                  $dom->preserveWhiteSpace = FALSE;
                  $dom->loadXML($xmlfile);
                  $dom->formatOutput = TRUE;
                  $xmlfile = $dom->saveXml();
                  $xmlfile = str_replace('<?xml version="1.0"?>', '', $xmlfile);
                  $xmlfile = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $xmlfile);

                  $xmlfile = utf8_decode ( $xmlfile );
                  $fp = fopen('dependente'.$cpf_candidato.'_'.$i.'.xml', 'w+');
                  fwrite($fp, $xmlfile);
                  fclose($fp);
              }
           }
       }
     }

     public function testelegiveis(){
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

			$xml = 'http://192.168.218.168/webcamaleao/xml/RP7/elegiveis.xml';
			$xml = file_get_contents($xml);
			$xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
			$xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
			$xml = str_replace("<soap:Body>", '', $xml);
			$xml = str_replace('<RHOWS_RS_REQUISICAOOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/RHOWS_RS_REQUISICAO_ELEG">', '', $xml);
			$xml = str_replace("</RHOWS_RS_REQUISICAOOutput>", '', $xml);
			$xml = str_replace("</soap:Body>", '', $xml);
			$xml = str_replace("</soap:Envelope>", '', $xml);
			$xml = utf8_encode($xml);
			echo $xml;
			$xml = new SimpleXMLElement($xml);
			$result = $xml->xpath('//RequisicaoELEGIVEIS');
      $result =json_decode(json_encode($result));
      $result = ($result[0]->MATRICULA);
      $arrlength = count($result);
      for ($x = 0; $x<$arrlength; ++$x) {
        return $this->InsertUpdateDeleteManual("insert into funcionarios_elegiveis(cod_candidato, numero_vaga_claro, matricula) values ( (select cod_candidato  from funcionario where matricula = '".$result[$x]."'),".$rp." , ".$result[$x]."  "  );
      }
      
		}


}
?>
