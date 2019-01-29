<?php
class Amx extends Controller{
  public function amx_rp()
      {
        $xml = 'http://192.168.218.168/webcamaleao/xml/rp_5.xml';
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
        /*$array = $result[0]->ELEGIVEIS->ID_FUNCIONARIO;
        foreach ($array as $key => $value) {
            echo "Elegivel_$key => : $value<br />\n";
        }*/
       $rp = new amxModel();
       $db      = $rp->createXMLrp($result);
       $rp = new amxModel();
       $db      = $rp->callProcedure('prc_proc_seletivo_webservice', null);
       echo 'ok';
      }


  public function amx_employee()
      {
        ini_set('memory_limit', '-1');

        $integration = $this->getParam('integretion');
        if ($integration == 'ativo') { $xml = 'http://192.168.218.168/webcamaleao/xml/contratados.xml';
        }else if ($integration == 'desligado') { $xml = 'http://192.168.218.168/webcamaleao/xml/demitidos.xml';
        }else if ($integration == 'cadastramento') { $xml = 'http://192.168.218.168/webcamaleao/xml/espera.xml';}

        $xml = file_get_contents($xml);
        $xml = str_replace("<?xml version='1.0' encoding='UTF-8'?>", '', $xml);
        $xml = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">', '', $xml);
        $xml = str_replace("<soap:Body>", '', $xml);
        $xml = str_replace('<AMX_RS_DADOS_CADASTRAISOutput xmlns="http://xmlns.oracle.com/orawsv/RHOWS/AMX_RS_DADOS_CADASTRAIS">', '', $xml);
        $xml = str_replace("</AMX_RS_DADOS_CADASTRAISOutput>", '', $xml);
        $xml = str_replace("</soap:Body>", '', $xml);
        $xml = str_replace("</soap:Envelope>", '', $xml);
        $xml = str_replace("\n", '', $xml);
        $xml = str_replace(" ", '', $xml);
        $xml = utf8_encode($xml);
        $xml = new SimpleXMLElement($xml);
        $result = $xml->xpath('//FUNCIONARIO');
        $result =json_decode(json_encode($result));
        $insertfunc = new amxModel();
        $insertfunc      = $insertfunc->createXMLemployee($result,$integration);
        echo 'Executado com sucesso!';
  }

  public function amx_new_employee()
      {
        $readNewEmployees = new amxModel();
        $datas['list'] = $readNewEmployees->readNewEmployees(null);
      //  print_r ($datas['list']);
        $Arrlength = count($datas['list']);
        for ($x = 0; $x <$Arrlength; ++ $x) {
            //echo $datas['list'][$x];
            //echo " ";
            $xmlfile = $datas['list'][$x];
            $fp = fopen('meus_links'.$x.'.xml', 'w+');
            fwrite($fp, $xmlfile);
            fclose($fp);
        }
        return "ok";
      }

  public function amx_dependents()
      {
        $readNewDependents = new amxModel();
        $datas['list'] = $readNewDependents->readDependentes(null);
        $Arrlength = count($datas['list']);
        for ($x = 0; $x <$Arrlength; ++ $x) {
            $xmlfile = $datas['list'][$x];
            $fp = fopen('meus_links_dep'.$x.'.xml', 'w+');
            fwrite($fp, $xmlfile);
            fclose($fp);
        }
      }
}
?>
