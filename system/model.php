<?php
ini_set('memory_limit', '-1');
class Model{
    protected $db;
    public $_table;
    public $_database;

    public function getConnection() {
      $conn = oci_connect($_SESSION["databaseuser"] , $_SESSION["databaseuser"] .'#sdsds', '200.244.55.141/prd1', 'utf8');
        return $conn;
    }

    public function insert(array $data){
     ini_set('max_execution_time', 900);
     foreach($data as $inds => $values) {
            $campos[] = $inds;
            $valores[] = $values;
     }
        $campos = implode(",", $campos);
        $valores = "'".implode("','", $valores)."'";
        $sql = "INSERT INTO ". $this->_table ." (".$campos.") VALUES (".$valores.")";
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        $r = oci_execute($stid);
        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
       oci_close($conn);
       return 'ok';
    }

    public function read( $fields, $where = null, $limit = null , $offset = null, $orderby = null){
        ini_set('max_execution_time', 900);
        $where = ($where != null ?  " WHERE ".$where."" : "");
        $limit = ($limit != null ?  " LIMIT ".$limit."" : "");
        $offset = ($offset != null ?  " OFFSET ".$offset."" : "");
        $orderby = ($orderby != null ?  " ORDER BY ".$orderby."" : "");
        $sql  = "select ". $fields ." from ".$this->_table." ".$where. " ".$orderby. " ".$limit. " ".$offset. " ";
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        //session_start("stid");
        $_SESSION["stid"] = $sql;
        $r =oci_execute($stid);

        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
        $data = array();
        while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            $data[] = $row;
        }
        $data = json_decode(json_encode($data));
        oci_close($conn);
        return $data;
    }


    public function readManual($sql){
        ini_set('max_execution_time', 900);
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        //session_start("stid");
        $_SESSION["stid"] = $sql;
        $r =oci_execute($stid);

        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
        $data = array();
        while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            $data[] = $row;
        }
        $data = json_decode(json_encode($data));
        oci_close($conn);
        return $data;
    }



    public function readfieldform( $fields, $where = null, $orderby = null, $typeform){
      ini_set('max_execution_time', 900);
      $where = ($where != null ?  " WHERE ".$where."" : "");
        $limit = ($limit != null ?  " LIMIT ".$limit."" : "");
        $offset = ($offset != null ?  " OFFSET ".$offset."" : "");
        $orderby = ($orderby != null ?  " ORDER BY ".$orderby."" : "");
        $sql  = "select ". $fields ." from ".$this->_table." ".$where. " ".$orderby. " ".$limit. " ".$offset. " ";
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        //session_start("stid");
        $_SESSION["stid"] = $sql;
        $r =oci_execute($stid);

        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
        $data = array();
        $list = "";
        if ($typeform == 'select'){
            while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
              $list .=  "<option value='".$row["CODIGO"]."'>".$row["NOME"]."</option>";
            }
        }
        oci_close($conn);
        return $list;
    }


    public function selectxml( $fields, $where = null, $limit = null , $offset = null, $orderby = null){
        ini_set('max_execution_time', 900);
        $where = ($where != null ?  " WHERE ".$where."" : "");
        $limit = ($limit != null ?  " LIMIT ".$limit."" : "");
        $offset = ($offset != null ?  " OFFSET ".$offset."" : "");
        $orderby = ($orderby != null ?  " ORDER BY ".$orderby."" : "");
        $sql  = "select ". $fields ." from ".$this->_table." ".$where. " ".$orderby. " ".$limit. " ".$offset. " ";
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        //session_start("stid");
        $_SESSION["stid"] = $sql;
        $r =oci_execute($stid);

        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
        $data = array();

        $xml =  '<?xml version="1.0" encoding="utf-8"?>'. "\n";
        $xml .=  "<DATA>\n";
        while($data = oci_fetch_assoc($stid)) {
          $xml .=  "  <LINE>\n";

          foreach($data as $key => $value) {
            $xml .= "    <".$key.">".$value."</".$key.">\n";
          }
          $xml .=  "  </LINE>\n";
        }
        $xml .= "</DATA>\n";
        return $xml;
    }


    public function selectmanual($sql){
      ini_set('max_execution_time', 900);
      $conn = $this->getConnection();
      $stid = oci_parse($conn, $sql);
      $r =oci_execute($stid);
      if (!$r) {
            $e = oci_error($stid);  // For oci_execute errors pass the statement handle
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%".($e['offset']+1)."s", "^");
            print  "\n</pre>\n";
      }
      $data = array();
      $xml =  array();
      while($data = oci_fetch_assoc($stid)) {
          $xml[] = $data['XML']->load();
      }
      return $xml;
    }


    public function readsimples( $fields, $where = null, $limit = null , $offset = null, $orderby = null){
        ini_set('max_execution_time', 900);
        $where = ($where != null ?  " WHERE ".$where."" : "");
        $limit = ($limit != null ?  " LIMIT ".$limit."" : "");
        $offset = ($offset != null ?  " OFFSET ".$offset."" : "");
        $orderby = ($orderby != null ?  " ORDER BY ".$orderby."" : "");
        $sql  = "select ". $fields ." from ".$this->_table." ".$where. " ".$orderby. " ".$limit. " ".$offset. " ";
        //echo $sql;
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        //session_start("stid");
        $_SESSION["stid"] = $sql;
        $r =oci_execute($stid);

        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
          $data = array();
          $xml =  '';
          while($data = oci_fetch_assoc($stid)) {
              $xml .= $data['XML']->load();
          }
          return $xml;
    }



    public function selectxmlsimples( $sql){
      ini_set('max_execution_time', 900);
      $conn = $this->getConnection();
      $stid = oci_parse($conn, $sql);
      $r =oci_execute($stid);
      if (!$r) {
            $e = oci_error($stid);  // For oci_execute errors pass the statement handle
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%".($e['offset']+1)."s", "^");
            print  "\n</pre>\n";
      }
      $data = array();
        $xml =  '';
        while($data = oci_fetch_assoc($stid)) {
          foreach($data as $key => $value) {
            $xml .= "        <".$key.">".$value."</".$key.">\n";
          }
        }
        return $xml;
    }





    public function selectjson( $fields, $where = null, $limit = null , $offset = null, $orderby = null){
        ini_set('max_execution_time', 900);
        $where = ($where != null ?  " WHERE ".$where."" : "");
        $limit = ($limit != null ?  " LIMIT ".$limit."" : "");
        $offset = ($offset != null ?  " OFFSET ".$offset."" : "");
        $orderby = ($orderby != null ?  " ORDER BY ".$orderby."" : "");
        $sql  = "select ". $fields ." from ".$this->_table." ".$where. " ".$orderby. " ".$limit. " ".$offset. " ";
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        //session_start("stid");
        $_SESSION["stid"] = $sql;
        $r =oci_execute($stid);

        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
        $data = array();

          $json =  "[\n";
          $i=0;
        while($data = oci_fetch_assoc($stid)) {
          if ($i==0){
            $json .=  "  {\n";
          } else {
            $json .=  "  ,{\n";
          }
          $i=$i+1;
          $j = 0;
          foreach($data as $key => $value) {
            if ($j==0){
              $json .= '    "'.$key.'":"'.$value.'",'. "\n";
            } else {
              $json .= '    ,"'.$key.'":"'.$value.'"'. "\n";
            }
            $j=$j+1;
          }
          $json .=  "  }\n";
        }
        $json .= "]\n";
        return $json;
    }


    public function update(array $data, $where){
      ini_set('max_execution_time', 900);
      $trimwhere =   trim($where);
      if ((!empty($trimwhere)) && ($trimwhere != "1=1")) {
        foreach($data as $inds => $values) {
            $campos[] = $inds . " = '".$values."'";
        }
        $campos = implode(" , ", $campos);
        $sql = "update ".$this->_table. " set ". $campos . " where " . $where . "" ;
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        $r = oci_execute($stid);
        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
       oci_close($conn);
      }
    }


    public function InsertUpdateDeleteManual($sql){
      $conn = $this->getConnection();
      $stid = oci_parse($conn, $sql);
      $r = oci_execute($stid);
      if (!$r) {
            $e = oci_error($stid);  // For oci_execute errors pass the statement handle
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%".($e['offset']+1)."s", "^");
            print  "\n</pre>\n";
      }
     oci_close($conn);
    }

    public function delete($where){
        ini_set('max_execution_time', 900);
        $where = ' 1 = 1 and ' . $where;
        $sql  = "delete from ".$this->_table." where ".$where. " ";
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $sql);
        $r = oci_execute($stid);
        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
       oci_close($conn);
    }

    public function procedure($name, $parans){
    ini_set('max_execution_time', 900);
    if (!empty($parans)) {
        $procedure = "begin ". $name . "; end;";
    } else{
        $procedure = "begin ". $name."(".$parans."); end;";
    }
        $conn = $this->getConnection();
        $stid = oci_parse($conn, $procedure);
        $r = oci_execute($stid);
        if (!$r) {
              $e = oci_error($stid);  // For oci_execute errors pass the statement handle
              print htmlentities($e['message']);
              print "\n<pre>\n";
              print htmlentities($e['sqltext']);
              printf("\n%".($e['offset']+1)."s", "^");
              print  "\n</pre>\n";
        }
       oci_close($conn);
       return 'Executado com sucesso!';
    }
}
