<?php
class AuthenticationHelper  extends Model { // Quando você da um extends Model, vc está assumindo que a class está automaticamente pegando para as características da class Model
  public $_table    = "AUTHENTICATION";
  public $_database = "pacexpress";

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
