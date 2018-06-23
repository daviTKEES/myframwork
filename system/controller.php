<?php

class Controller extends System{
    protected function view ($name,$vars = null) {
    	if (is_array($vars) && count($vars) >0)
    		extract($vars, EXTR_PREFIX_ALL, 'view' );  // Adiciona um prefixo ao nome de todas as variáveis definido por prefix , NO CASO VIEW_VARIAVEL

        require_once('app/views/'.$name.'.phtml');
    }
}

