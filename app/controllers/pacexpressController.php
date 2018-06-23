<?php
class Pacexpress extends Controller{
    public function processoseletivo()
    {
    		$array = apache_request_headers();
        $token = $array['token'];
        $KeyCheck = new AuthenticationHelper();
      	$AuthorizationOk = $KeyCheck->GetAuthentication($token,null,null);
    		if ($AuthorizationOk)
        {
            $inputJSON = file_get_contents('php://input');

            $Keydata        = new KeyData();
          	$Keydata        = $Keydata->geraKey(7, true, true, false);

            $insertprocesso = new CreateXML();
    		    $db             = $insertprocesso->insertTabelTemp($inputJSON, $Keydata);

            $createprocesso = new pacexpressModel();
		        $db             = $createprocesso->callProcedure('prc_integracao_vagas_in', null);

            $return         = new ShowLog();
            $datas['list']  = $return->returnlog($Keydata);

    		} else {
            $datas['list']  =  array( (object) array('access' => 'denied'));
    	  }
        $this->view('pacexpress/return',$datas);
   }

   public function report(){
       $this->view('pacexpress/report',null);
   }

   public function reportlog(){
     $requestData    = $_REQUEST;
     $showLog        = new ReportLogHelper();
     $datas['list']  = $showLog->showDataReport($requestData);
     echo $datas['list'];
   }

   public function detaillog(){
       $key_data      = filter_input(INPUT_POST, 'KEY_DATA');
       $dadostemp      = new procsellogModel();
       $datas['list']  = $dadostemp->showDatatempPacExpress($key_data,1);
       //echo $key_data;
       $this->view('pacexpress/detail',$datas);
   }

   public function dadosfolha(){
     $array = apache_request_headers();
     $token = $array['token'];
     $KeyCheck = new AuthenticationHelper();
     $AuthorizationOk = $KeyCheck->GetAuthentication($token,null,null);
     if ($AuthorizationOk)
     {
    		$candidatos = new dadosdpModel();
        $datas['list'] = $candidatos->listdadosdp('*',null,null);
     } else {
         $datas['list']  =  array( (object) array('access' => 'denied'));
     }
     $this->view('pacexpress/return',$datas);
  }


   public function soapdoc()
    {
      $this->view('redeDor/rededorSoap',null);
    }

    public function contratadossoap()
      {
        $server = new SoapServer("http://192.168.218.168/rest/wsdl/contratados.wsdl");
        $server->setObject(new pacexpressModel());
        $server->addFunction("contratadoslist");
        $server->handle();
      }


    public function processoseletivosoap()
        {
          $server = new SoapServer("http://192.168.218.168/rest/wsdl/pacexpress.wsdl");
          $server->setObject(new pacexpressModel());
          $server->addFunction("processoSOAP");
          $server->handle();
        }


    public function dadosfolhasoap()
          {
            $server = new SoapServer("http://192.168.218.168/rest/wsdl/folha.wsdl");
            $server->setObject(new pacexpressModel());
            $server->addFunction("listcandidatos");
            $server->handle();
          }


    public function teste()
          {

            if (isset($_SERVER['HTTP_ORIGIN'])) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            $array = apache_request_headers();
            print_r($array);

            $uploaddir = realpath('./') . '/';
            //$uploadfile = $uploaddir . basename($_FILES['file']['name']);
            
            echo 'Here is some more debugging info:';
            echo "\n<hr />\n";
            print_r($_FILES);
            echo "\n<hr />\n";
            print_r($_POST);
            print "</pr" . "e>\n"; 
            echo "\n<hr />\n";
            file_put_contents($_FILES['file']['name'],file_get_contents($_FILES['file']['tmp_name']));
          }



}
