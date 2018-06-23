  <?php
  class Brq extends Controller{
      public function showvagas()
      {
      		$array = apache_request_headers();
          $token =   $array['token'];
          //$token =  'BRQ0101X41';
          //$token = $this->getParam('token'); // Caso o cliente tenha dificuldade de passar o header, o token pode ser passado via url: /brq/showvagasxml/token/BRQ0101X41

          $KeyCheck = new AuthenticationHelper();
        	$AuthorizationOk = $KeyCheck->GetAuthentication($token,null,null);
      		if ($AuthorizationOk)
          {    $data_abertura_ini = $this->getParam('data_abertura_ini');
               $data_abertura_fim = $this->getParam('data_abertura_fim');
      		     $vagas      = new listprocxmlModel();
               if ((empty($data_abertura_ini)) || (empty($data_abertura_fim))) {
                 $datas['list'] = $vagas->montaxmlProc("cod_proc_seletivo = 32916");
               }else{
                 $datas['list'] = $vagas->montaxmlProc("DATA_ABERTURA BETWEEN '".$data_abertura_ini."' AND '".$data_abertura_fim."'");
               }
      		} else {
               $datas['list'] =  '<data><access>denied</access></data>';
      		}

          $typereturn = $this->getParam('typereturn');

          if ($typereturn=='XML'){
            $this->view('brq/homexml',$datas);
          }else{
            //$this->view('brq/homejson',$datas);
            $xml = simplexml_load_string($datas['list']);
            $datas['list'] = json_encode($xml);
            $vagas      = new ConvertJsonHelper();
            $datas['list'] = $vagas->simpleJsontoUTF8($datas['list']);
            $this->view('brq/homejson',$datas);

          }
     }

    public function candidatovagas()
    {
        $array = apache_request_headers();
        $token = $array['token'];
        //$token =  'BRQ0101X41';
        //$token = $this->getParam('token'); // Caso o cliente tenha dificuldade de passar o header, o token pode ser passado via url: /brq/showvagasxml/token/BRQ0101X41
        $KeyCheck = new AuthenticationHelper();
        $AuthorizationOk = $KeyCheck->GetAuthentication($token,null,null);
        if ($AuthorizationOk)
        {    $vagas         = new vwcandprocModel();
             $datas['list'] = $vagas->montaxmlCandProc('COD_CANDIDATO in (18978,59485,153152,154705,164916,213081,213138,213313,214604,214622,214760,216660)');
        } else {
             $datas['list'] =  '<data><access>denied</access></data>';
        }

        $typereturn = $this->getParam('typereturn');
        if ($typereturn=='XML'){
          $this->view('brq/homexml',$datas);
        }else{
          $xml = simplexml_load_string($datas['list']);
          $datas['list'] = json_encode($xml);
          $vagas      = new ConvertJsonHelper();
          $datas['list'] = $vagas->simpleJsontoUTF8($datas['list']);
          $this->view('brq/homejson',$datas);
        }
    }



  }
