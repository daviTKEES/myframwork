<?php
class ReportLogHelper  extends Model { // Quando vocÃª da um extends Model, vc estÃ¡ assumindo que a class estÃ¡ automaticamente pegando  as caracterÃ­sticas da class Model
	public $_table    = "VW_INTEGRACAO_WSDL_RESULT"; // Essa é a view ou Tabela que está sendo consultada nesse helper

	public function showDataReport($requestData) {
		$columns = array(
		// datatable column index  => database column name (Estamos verificando aqui quais as colunas serão apresentadas no relatório)
			0 =>'CODIGO',
			1 =>'INTEGRACAO',
			2 => 'DATA',
			3 => 'TIP_REG',
			4 => 'CHAVE',
			5 => 'MENSAGEM',
			6 => 'CAMPO_ERRO',
			7 => 'VALOR_CAMPO'
		);

		$row      = $this->read('COUNT(1) VALOR', null, null , null, null);
		for($x = 0; $x < count($row); $x++) {
			$totalData = $row[$x]->VALOR;
		}

		$sql=" 1=1";
		if( !empty($requestData['columns'][2]['search']['value']) ){
			$sql.=" AND To_Char(DATA, 'DD/MM/YYYY HH24:MI:SS') LIKE '".$requestData['columns'][2]['search']['value']."%' ";
		}

		if( !empty($requestData['columns'][3]['search']['value']) ){
			$sql.=" AND TIP_REG = '".$requestData['columns'][3]['search']['value']."' ";
		}

		if( !empty($requestData['columns'][4]['search']['value']) ){
			$sql.=" AND CHAVE LIKE '".$requestData['columns'][4]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][6]['search']['value']) ){
			$sql.=" AND CAMPO_ERRO LIKE '".$requestData['columns'][6]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][1]['search']['value']) ){
		    $sql.=" AND INTEGRACAO LIKE '".$requestData['columns'][1]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][5]['search']['value']) ){
		    $sql.=" AND MENSAGEM LIKE '".$requestData['columns'][5]['search']['value']."%' ";
		}

		$row = $this->read('COUNT(1) VALOR', $sql, null , null, null);
		for($x = 0; $x < count($row); $x++) {
			$totalFiltered = $row[$x]->VALOR;
		}

		$row = $this->read('COUNT(1) VALOR', $sql, null , null, null);

		$incrow = ($requestData['start']+1);
		$fimrow = ($requestData['start']+ $requestData['length']);

		$button= '<a href="#" onclick="verdatail(\'\'trocar\'\')"><i class="glyphicon glyphicon-search"></i></a>';

		$sql = " SELECT CODIGO , INTEGRACAO, To_Char(DATA, 'DD/MM/YYYY HH24:MI:SS') DATA, nvl(TIP_REG,0) TIP_REG , CHAVE, MENSAGEM, CAMPO_ERRO, VALOR_CAMPO FROM ";
		$sql.= " ( SELECT KEY_DATA CODIGO , INTEGRACAO,DATA, TIP_REG, CHAVE, MENSAGEM, CAMPO_ERRO,  REPLACE('".$button."','trocar',KEY_DATA) VALOR_CAMPO, ";
		$sql.= " ROW_NUMBER() OVER ";
		$sql.= " (ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'].") Row_Num";
		$sql.= " FROM VW_INTEGRACAO_WSDL_RESULT ";
		$sql.=" where  1=1 ";

		if( !empty($requestData['columns'][2]['search']['value']) ){
			$sql.=" AND To_Char(DATA, 'DD/MM/YYYY HH24:MI:SS') LIKE '".$requestData['columns'][2]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][4]['search']['value']) ){
			$sql.=" AND CHAVE LIKE '".$requestData['columns'][4]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][3]['search']['value']) ){
			$sql.=" AND TIP_REG = '".$requestData['columns'][3]['search']['value']."' ";
		}
		if( !empty($requestData['columns'][6]['search']['value']) ){
			$sql.=" AND CAMPO_ERRO LIKE '".$requestData['columns'][6]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][1]['search']['value']) ){
		    $sql.=" AND INTEGRACAO LIKE '".$requestData['columns'][1]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][5]['search']['value']) ){
		    $sql.=" AND MENSAGEM LIKE '".$requestData['columns'][5]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][0]['search']['value']) ){
		    $sql.=" AND KEY_DATA LIKE '".$requestData['columns'][0]['search']['value']."%' ";
		}
		$sql.= " )  where  Row_Num BETWEEN ".$incrow." and ". $fimrow ."   ";

		$row = $this->selectmanual($sql);
		for($x = 0; $x < count($row); $x++) {
			$nestedData=array();
			$nestedData[] = $row[$x]->CODIGO;
			$nestedData[] = $row[$x]->INTEGRACAO;
			$nestedData[] = $row[$x]->DATA;
			$nestedData[] = $row[$x]->TIP_REG;
			$nestedData[] = $row[$x]->CHAVE;
			$nestedData[] = $row[$x]->MENSAGEM;
			$nestedData[] = $row[$x]->CAMPO_ERRO;
			$nestedData[] = $row[$x]->VALOR_CAMPO;
			$data[] = $nestedData;
		}

		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
					"recordsTotal"    => intval( $totalData ),  // total number of records
					"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
					"data"            => $data   // total data array
					);

		return json_encode($json_data);  // send data as json format

    }
}
