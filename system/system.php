<?php

class System{
    private $_url;
    private $_explode;
    public $_controller;
    public $_action;
    public $_params;

    public function __construct() {
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();
    }

    private function setUrl(){
        $_GET['url'] = (isset($_GET['url']) ? $_GET['url'] : 'index/index_action' );
        $this->_url = $_GET['url']; //setando a url digitada
    }

    private function setExplode(){
        $this->_explode = explode('/', $this->_url); // Separando por barra => This=_url porque é o que está com o Get["url") do htaccess
    }

    private function setController(){
        $this->_controller = $this->_explode[0];
        // Eu ja setei o explode como um array que separa as barras a minha url no setExplode
        // O primeiro indece [0] é sempre o nome do controller então nesse caso estamos atribuindo a variável ao controller
    }

    private function setAction(){
        $ac = (!isset($this->_explode[1]) || $this->_explode[1]==null || $this->_explode[1]=="index" ? "index_action" : $this->_explode[1]);
        $this->_action = $ac; // no caso é o segundo índice do explode quer será a action
    }

    private function setParams(){
        unset ( $this->_explode[0], $this->_explode[1]);  //unset() destrói a variável especificada. Então retirei os dois primeiros ídices;
        if ( end($this->_explode) == null)
            array_pop ($this->_explode); // array_pop — Retira um elemento do final do array - para caso mande um parametro nulo : Exemplo : nome/davi/sobrenome/amaral/idade/40/  ( nesse caso o / vai ser desprezado);
        $i=0;
        if(!empty($this->_explode)) {
            foreach ($this->_explode as $val){
              if ($i % 2 == 0) {
                 $ind[] = $val;
              } else {
                  $value[] = $val;
              }
              $i++;
            }
        }else{
            $ind = array();
            $value = array();
        }

        if ( count($ind)== count($value) && !empty($ind) && !empty($value)){
            $this->_params = array_combine($ind, $value );
            /*array_combine
             * Cria um array usando os valores do array keys como chaves e os
             * valores do array values como valores correspondentes.
             * Exemplo #1 Um simples exemplo usando array_combine()
                $a = array('verde', 'vermelho', 'amarelo');
                $b = array('abacate', 'maçã', 'banana');
                $c = array_combine($a, $b);
                print_r($c);
                O exemplo acima irá imprimir:
                    [green]  => abacate
                    [red]    => maçã
                    [yellow] => banana  */
        }else{
            $this->_params = array();
        }

    }

    public function getParam($name = null){
        if ($name != null){
           return $this->_params[$name];
        } else {
           return $this->_params;
        }

    }

    public function run(){
        $_SESSION["databaseuser"] = $this->_controller;   
        $controller_path = CONTROLLERS . $this->_controller . 'Controller.php';

        require_once($controller_path);
        $app = new $this->_controller();

        $action = $this->_action;
        $app->$action();
    }


}
