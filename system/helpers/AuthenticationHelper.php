<?php
class AuthenticationHelper  extends Model { // Quando voc� da um extends Model, vc est� assumindo que a class est� automaticamente pegando para as caracter�sticas da class Model
  public $_table    = "AUTHENTICATION";

  public function GetAuthentication($Authorization,$Login,$Password)
  {
    $where  = " token = '".$Authorization."' ";
    $return = $this->read('count(1) VAL', $where, null , null, null);
    $val = $return[0]->VAL;
    if ($val == 0) {
      return false;
    }else{
      return true;
    }
	}
}
