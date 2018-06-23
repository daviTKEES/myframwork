<?php
	Class procsellogModel extends Model {
		public $_table = " vw_xml_proc_seletivo_log ";

		public function showDatatempPacExpress($key_data,$cod_integracao){

		$fields =	"Nvl(STATUS, '-') STATUS,
						   Nvl(NOME, '-') NOME,
						   Nvl(SOLICITANTE_RESP, '-') SOLICITANTE_RESP,
						   Nvl(COD_AREA_ATUACAO, '-') COD_AREA_ATUACAO,
						   Nvl(DESC_AREA_ATUACAO, '-') DESC_AREA_ATUACAO,
						   Nvl(CARGO, '-') CARGO,
						   Nvl(DESC_CARGO, '-') DESC_CARGO,
						   Nvl(EMPRESA, '-') EMPRESA,
						   Nvl(DESC_EMPRESA, '-') DESC_EMPRESA,
						   Nvl(DATA_INICIO, '-') DATA_INICIO,
						   Nvl(DESCRICAO, '-') DESCRICAO,
						   Nvl(OBSERVACOES, '-') OBSERVACOES,
						   Nvl(GESTOR, '-') GESTOR,
						   Nvl(TEL_GESTOR, '-') TEL_GESTOR,
						   Nvl(TIPO_CONTRATO, '-') TIPO_CONTRATO,
						   Nvl(VAGAS, '-') VAGAS,
						   Nvl(ESTADO, '-') ESTADO,
						   Nvl(CIDADE, '-') CIDADE,
						   Nvl(LOCAL_TRABALHO, '-') LOCAL_TRABALHO,
						   Nvl(PERFIL_PROFISSIONAL, '-') PERFIL_PROFISSIONAL,
						   Nvl(FAIXA_SALARIAL, '-') FAIXA_SALARIAL,
						   Nvl(MOTIVO_CONTRATO, '-') MOTIVO_CONTRATO,
						   Nvl(DESC_FORMACAO, '-') DESC_FORMACAO,
						   Nvl(DESC_EXP_PROF, '-'),
						   Nvl(COMPORTAMENTAIS, '-') DESC_EXP_PROF,
						   Nvl(RESP_CARGO, '-') RESP_CARGO,
						   Nvl(KEY_DATA, '-') KEY_DATA  ,
						   COD_INTEGRACAO,
						   Nvl(OBSERVACAO , '-') OBSERVACAO" ;

			return $this->read($fields, " COD_INTEGRACAO = '".$cod_integracao."' and KEY_DATA = '".$key_data."'", null , null, null);

		}

	}
