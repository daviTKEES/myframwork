  <?php
  class Brqv7 extends Controller{
      public function processos()
      {
          // Verificando se estý sendo enviado o token via header
      		$array = apache_request_headers();
          $token =   $array['token'];
          //$token =  'BRQ0101X41';
          //$token = $this->getParam('token'); // Caso o cliente tenha dificuldade de passar o header, o token pode ser passado via url: /brq/showvagasxml/token/BRQ0101X41

          // Verificando se a solicitaýýo do token tem permissýo para consultar
          $KeyCheck = new AuthenticationHelper();
        	$AuthorizationOk = $KeyCheck->GetAuthentication($token,null,null);
          $inputJSON = file_get_contents('php://input');
          $arrayfields =  json_decode($inputJSON);
          $data_abertura_ini = '';
          $data_abertura_fim = '';
          $typereturn = '';
          $typereturn = (string) $arrayfields->typereturn;

      		if ($AuthorizationOk)
          {
               $data_abertura_ini = (string) $arrayfields->data_abertura_ini;
               $data_abertura_fim = (string) $arrayfields->data_abertura_fim;
      		     $vagas      = new listprocxmlModel();
               if ((empty($data_abertura_ini)) || (empty($data_abertura_fim))) {
                 $datas['list'] = $vagas->montaxmlProc(null);
               }else{
                 $datas['list'] = $vagas->montaxmlProc("DATA_ABERTURA BETWEEN to_date('".$data_abertura_ini."','DD/MM/YYYY') AND to_date('".$data_abertura_fim."','DD/MM/YYYY')");
               }
      		} else {
               $datas['list'] =  '<data><access>denied</access></data>';
      		}

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

    public function candidatos()
    {
        $array = apache_request_headers();
        $token = $array['token'];
        //$token =  'BRQ0101X41';
        //$token = $this->getParam('token'); // Caso o cliente tenha dificuldade de passar o header, o token pode ser passado via url: /brq/showvagasxml/token/BRQ0101X41
        $KeyCheck = new AuthenticationHelper();
        $AuthorizationOk = $KeyCheck->GetAuthentication($token,null,null);

        print_r($_POST);
        $arrayfields = file_get_contents('php://input');
        $typereturn = (string) $arrayfields->typereturn;

        if ($AuthorizationOk)
        {
             $data_update_ini = (string) $arrayfields->data_update_ini;
             $data_update_end = (string) $arrayfields->data_update_end;
             $vagas         = new vwcandprocModel();
             if ((empty($data_update_ini)) || (empty($data_update_end))) {
               $datas['list'] = $vagas->montaxmlCandProc(null);
             }else{
                 $datas['list'] = $vagas->montaxmlCandProc("DATA_ATUALIZACAO BETWEEN to_date('".$data_update_ini."','DD/MM/YYYY') AND to_date('".$data_update_end."','DD/MM/YYYY')");
           }
        } else {
             $datas['list'] =  '<data><access>denied</access></data>';
        }

        $typereturn = $this->getParam('typereturn');
        if ($typereturn=='XML'){
          $this->view('brq/homexml',$datas);
        }else{
          $xml = simplexml_load_string($datas['list']);
          $datas['list'] = json_encode($xml);
          $vagas         = new ConvertJsonHelper();
          $datas['list'] = $vagas->simpleJsontoUTF8($datas['list']);
          $this->view('brq/homejson',$datas);
        }
    }



  }
