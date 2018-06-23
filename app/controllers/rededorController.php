<?php
class Rededor extends Controller{

  public function soapdoc()
    {
      $this->view('redeDor/rededorSoap',null);
    }

  public function contratados()
    {
      $server = new SoapServer("http://192.168.218.168/rest/wsdl/contratados.wsdl");
      $server->setObject(new rededorModel());
      $server->addFunction("contratadoslist");
      $server->handle();
    }


  public function processoseletivo()
      {
        $server = new SoapServer("http://192.168.218.168/rest/wsdl/processo.wsdl");
        $server->setObject(new rededorModel());
        $server->addFunction("processoAction");
        $server->handle();
      }


  public function dadosfolha()
        {
          $server = new SoapServer("http://192.168.218.168/rest/wsdl/folha.wsdl");
          $server->setObject(new rededorModel());
          $server->addFunction("listcandidatos");
          $server->handle();
        }

  public function clientrededor()
    {
      $this->view('redeDor/clientRedeDor',null);
    }

}

 ?>
