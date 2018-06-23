PROMPT CREATE OR REPLACE PROCEDURE prc_proc_seletivo_webservice
CREATE OR REPLACE PROCEDURE prc_proc_seletivo_webservice
-----------------------------------------------------------------------------------------------------
-- Name:PRC_PROC_SELETIVO_WEBSERVICE
-- Desc:Recebe novo processo via webservice
-- Auth:Davi Amaral
-- Date:16/06/2017
-- Dependency:Tabelas integracao, integracao_layout e tabela_temporaria preenchidas. E ser chamada PELO WEBSERVICE.
-----------------------------------------------------------------------------------------------------
AS

  v_count                 integer;
  v_resultado             integer;
  v_reconhecimento        integer;
  v_erro                  varchar2(4000);
  v_temp                  varchar2(4000);
  v_fail                  boolean;
  no_proc_found           exception;
  no_data                 exception;
  no_proc_etp_found       exception;
  v_proc_sel              processo_seletivo.codigo%type;
  v_ordem                 number(10) := 0;
  v_etapa                 integer;
  v_log_proc              varchar2(4000);
  v_key                   varchar2(1000);
  v_cod_cargo             number(10,0);
  cod_candidato_aux       number(10,0);
  login_aux               number(10,0);
  endidate_aux            number(10,0);
  processo_aux            number(10,0);
  cargo_aux               number(10,0);
  undade_aux              number(10,0);
  v_req_aux               number(10,0);
  setor_aux               number(10,0);
  email_aux               varchar2(400);
  nome_completo_aux       varchar2(400);
  codigo_aux              number(10,0);
  login_aux_2             varchar2(400);
  v_url                   varchar2(1000);

  num_requis_aux          varchar2(400);
  motivo_aux              varchar2(400);
  grade_aux               varchar2(400);
  step_aux                varchar2(400);
  step_aux_masc           varchar2(400);
  tipo_recrutamento_aux   varchar2(400);
  cod_posicao_aux         varchar2(400);
  posica_aux              varchar2(400);
  cod_diretoria_aux       varchar2(400);
  diretoria_aux           varchar2(400);
  cod_regional_aux        varchar2(400);
  regional_aux            varchar2(400);
  cod_cluster_aux         varchar2(400);
  desc_cluster_aux        varchar2(400);
  cod_motivo_aux          varchar2(400);
  keydata_aux             varchar2(400);

  matri_gestor_aux        varchar2(400);

  nome_gestor_aux         varchar2(400);
  tel_gestor_aux          varchar2(400);
  email_gestor_aux        varchar2(400);
  idiomas_aux             varchar2(100);
  nivel_idiomas_aux      varchar2(100);
  cod_idiomas_aux        varchar2(100);
  cod_nivel_idiomas_aux  varchar2(100);
  IDIOMA_REQ             varchar2(100);
  IDIOMA_ING_AVANC       varchar2(100);
  IDIOMA_ING_INTER       varchar2(100);
  IDIOMA_ING_BASIC       varchar2(100);
  IDIOMA_ESP_AVANC       varchar2(100);
  IDIOMA_ESP_INTER       varchar2(100);
  IDIOMA_ESP_BASIC       varchar2(100);

  NIVEL_ING_AVANC       varchar2(100);
  NIVEL_ING_INTER       varchar2(100);
  NIVEL_ING_BASIC       varchar2(100);
  NIVEL_ESP_AVANC       varchar2(100);
  NIVEL_ESP_INTER       varchar2(100);
  NIVEL_ESP_BASIC       varchar2(100);
  v_corpo2 VARCHAR2(4000);


  req   UTL_HTTP.REQ;
  resp  UTL_HTTP.RESP;
  value VARCHAR2(1024);


  CURSOR proc_seletivo_cur IS
    SELECT   REQP_SEQ,
   NOME,
   COD_EMPRESA,
   NOME_EMPRESA,
   CODIGO_UNIDADE,
   UNIDADE_ORGANIZACIONAL,
   DATA_REQUISICAO,
   DATA_IMPORTACAO,
   ID_REQUISITANTE,
   REQUISITANTE,
   ID_GESTOR_REQUISICAO,
   GESTOR_REQUISICAO,
   ID_CARGO,
   CARGO,
   CODIGO_TIPO_COLABORADOR,
   TIPO_COLABORADOR,
   SALARIO,
   SIGLA_PAIS_LOCAL,
   PAIS_LOCAL,
   SIGLA_ESTADO_LOCAL,
   ESTADO_LOCAL,
   CIDADE_LOCAL,
   CODIGO_AREA_ATUACAO,
   AREA_ATUACAO,
   POSICOES,
   JUSTIFICATIVA,
   OBSERVACAO,
   ID_SELEC_RESP,
   NOME_SELEC_RESP,
   EMAIL_SELEC_RESP,
   PCD,
   FORMACAO_ACADEMICA,
   EXPERIENCIA_PROFISSIONAL,
   IDIOMAS,
   HORARIO_TRABALHO,
   OUTROS,
   CONCHECIMENTO_TECNICO,
   RESPONSABILIDADES,
   CARACTERISTICAS,
   TIPO_ATIVIDADE,
    DATA_ENVIO,
    KEY_DATA,
    COD_INTEGRACAO
  FROM vw_xml_vaga_ativa VW ;
BEGIN


  dbms_application_info.set_client_info('AMX.PRC_PROC_SELETIVO_WEBSERVICE');

  pkg_integracao2.cco_log;

  --prc_read_integration_xml(2);

  FOR rec IN proc_seletivo_cur LOOP

    BEGIN
     v_key := rec.key_data;

     v_req_aux := rec.REQP_SEQ;


    SELECT Count(1) INTO v_count FROM VW_PROCESSO_SELETIVO WHERE NUMERO_VAGA_CLARO = rec.reqp_seq;

   IF v_count = 0 THEN

     idiomas_aux := NULL;
     nivel_idiomas_aux :=NULL;



  IDIOMA_ING_AVANC := NULL;
  IDIOMA_ING_INTER := NULL;
  IDIOMA_ING_BASIC := NULL;
  IDIOMA_ESP_AVANC := NULL;
  IDIOMA_ESP_INTER := NULL;
  IDIOMA_ESP_BASIC := NULL;

  NIVEL_ING_AVANC  := NULL;
  NIVEL_ING_INTER  := NULL;
  NIVEL_ING_BASIC  := NULL;
  NIVEL_ESP_AVANC  := NULL;
  NIVEL_ESP_INTER  := NULL;
  NIVEL_ESP_BASIC  := NULL;
  IDIOMA_REQ       := NULL;

     SELECT INGLES_AVANCADO INTO IDIOMA_REQ FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1;

     IF IDIOMA_REQ IS NOT NULL THEN
          idiomas_aux   := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 2)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;
          nivel_idiomas_aux  := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 3)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;

          v_etapa := 9200;

                SELECT codigo INTO IDIOMA_ING_AVANC
                FROM   lst_idioma
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = idiomas_aux;
          v_etapa := 9250;
                SELECT codigo INTO NIVEL_ING_AVANC
                FROM lst_nivel_conhecimento
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = nivel_idiomas_aux
                AND aplicado = 'IDIOMA' ;
          v_etapa := 9260;
          INSERT INTO idioma_aux_rp (cod_RP,IDIOMA,NIVEL,ORDEM) VALUES (rec.REQP_SEQ,IDIOMA_ING_AVANC,NIVEL_ING_AVANC,1);
          COMMIT;
     END IF;

     IDIOMA_REQ       := NULL;
     SELECT INGLES_INTERMEDIARIO INTO IDIOMA_REQ FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1;

     IF IDIOMA_REQ IS NOT NULL THEN
          idiomas_aux   := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 2)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;
          nivel_idiomas_aux  := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 3)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;

          v_etapa := 9300;
                SELECT codigo INTO IDIOMA_ING_INTER
                FROM   lst_idioma
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = idiomas_aux;
          v_etapa := 9350;
                SELECT codigo INTO   NIVEL_ING_INTER
                FROM lst_nivel_conhecimento
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = nivel_idiomas_aux
                AND aplicado = 'IDIOMA' ;
          v_etapa := 9360;
          INSERT INTO idioma_aux_rp (cod_RP,IDIOMA,NIVEL,ORDEM) VALUES (rec.REQP_SEQ,IDIOMA_ING_INTER,NIVEL_ING_INTER,2);
          COMMIT;

     END IF;

     IDIOMA_REQ       := NULL;
     SELECT INGLES_BASICO INTO IDIOMA_REQ FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1;

     IF IDIOMA_REQ IS NOT NULL THEN
          idiomas_aux   := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 2)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;
          nivel_idiomas_aux  := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 3)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;

          v_etapa := 9400;

                SELECT codigo INTO IDIOMA_ING_BASIC
                FROM   lst_idioma
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = idiomas_aux;
          v_etapa := 9450;
                SELECT codigo INTO NIVEL_ING_BASIC
                FROM lst_nivel_conhecimento
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = nivel_idiomas_aux
                AND aplicado = 'IDIOMA' ;

          v_etapa := 9460;
          INSERT INTO idioma_aux_rp (cod_RP,IDIOMA,NIVEL,ORDEM) VALUES (rec.REQP_SEQ,IDIOMA_ING_BASIC,NIVEL_ING_BASIC,3);
          COMMIT;
     END IF;


     IDIOMA_REQ       := NULL;
     SELECT ESPANHOL_AVANCADO INTO IDIOMA_REQ FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1;

     IF IDIOMA_REQ IS NOT NULL THEN
          idiomas_aux   := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 2)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;
          nivel_idiomas_aux  := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 3)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;

          v_etapa := 9500;

                SELECT codigo INTO IDIOMA_ESP_AVANC
                FROM   lst_idioma
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = idiomas_aux;
          v_etapa := 955;
                SELECT codigo INTO NIVEL_ESP_AVANC
                FROM lst_nivel_conhecimento
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = nivel_idiomas_aux
                AND aplicado = 'IDIOMA' ;

          v_etapa := 9560;
          INSERT INTO idioma_aux_rp (cod_RP,IDIOMA,NIVEL,ORDEM) VALUES (rec.REQP_SEQ,IDIOMA_ESP_AVANC,NIVEL_ESP_AVANC,4);
          COMMIT;
     END IF;


     IDIOMA_REQ       := NULL;
          v_etapa := 9565;
     SELECT ESPANHOL_INTERMEDIARIO INTO IDIOMA_REQ FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1;

     IF IDIOMA_REQ IS NOT NULL THEN
          idiomas_aux   := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 2)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;
          nivel_idiomas_aux  := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 3)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;

          v_etapa := 9600;

                SELECT codigo INTO IDIOMA_ESP_INTER
                FROM   lst_idioma
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = idiomas_aux;
          v_etapa := 9650;
                SELECT codigo INTO NIVEL_ESP_INTER
                FROM lst_nivel_conhecimento
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = nivel_idiomas_aux
                AND aplicado = 'IDIOMA' ;

          v_etapa := 9660;
          INSERT INTO idioma_aux_rp (cod_RP,IDIOMA,NIVEL,ORDEM) VALUES (rec.REQP_SEQ,IDIOMA_ESP_INTER,NIVEL_ESP_INTER,5);
          COMMIT;
     END IF;


     IDIOMA_REQ       := NULL;
     SELECT ESPANHOL_BASICO INTO IDIOMA_REQ FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1;

     IF IDIOMA_REQ IS NOT NULL THEN
          idiomas_aux   := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 2)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;
          nivel_idiomas_aux  := (Trim(Upper(translate((regexp_substr(IDIOMA_REQ, '[^ ]+', 1, 3)),'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC'))))  ;

          v_etapa := 9600;

                SELECT codigo INTO IDIOMA_ESP_BASIC
                FROM   lst_idioma
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = idiomas_aux;
          v_etapa := 9650;
                SELECT codigo INTO NIVEL_ESP_BASIC
                FROM lst_nivel_conhecimento
                WHERE (Trim(Upper(translate(NOME,'âàãáÁÂÀÃéêÉÊíÍóôõÓÔÕüúÜÚÇç','AAAAAAAAEEEEIIOOOOOOUUUUCC')))) = nivel_idiomas_aux
                AND aplicado = 'IDIOMA' ;

          v_etapa := 9660;
          INSERT INTO idioma_aux_rp (cod_RP,IDIOMA,NIVEL,ORDEM) VALUES (rec.REQP_SEQ,IDIOMA_ESP_BASIC,NIVEL_ESP_BASIC,6);
          COMMIT;
     END IF;




     SELECT Count (1) INTO  v_count  from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq ;
     IF v_count > 0 THEN

        v_etapa := 10000;
        select regexp_substr(MOTIVO, '[^;]+', 1, 1)  into cod_motivo_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;


         select regexp_substr(MOTIVO, '[^;]+', 1, 1)  into cod_motivo_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;


        v_etapa := 10001;
        SELECT codigo INTO motivo_aux FROM lst_motivo_contrato WHERE cod_amx = COD_MOTIVO_aux;

        v_etapa := 10050;
        select grade  into      grade_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;

        v_etapa := 10100;
        select Trim(step)   into      step_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;

        v_etapa := 10101;
        step_aux_masc := REPLACE(step_aux,'.',',');
        v_etapa := 11102;
        step_aux_masc := Trim(step_aux_masc);
        v_etapa := 11103;
        --step_aux_masc := to_char(step_aux_masc, 'FM999G999G999D90', 'nls_numeric_characters='',.''');

        v_etapa := 11104;
        select (SELECT codigo FROM tipo_recrutamento WHERE Upper(nome) = Upper(cpl.tipo_recrutamento))   into  tipo_recrutamento_aux
        from vw_xml_vaga_ativa_compl cpl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;


        v_etapa := 12000;
        select regexp_substr(posicao, '[^;]+', 1, 1) cod_posicao  into cod_posicao_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;

        v_etapa := 12010;
        select regexp_substr(posicao, '[^;]+', 1, 2) posicao     into  posica_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq  AND ROWNUM = 1;


        v_etapa := 13000;
        select regexp_substr(diretoria, '[^;]+', 1, 1) cod_diretoria into cod_diretoria_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;

        v_etapa := 13010;
        select regexp_substr(diretoria, '[^;]+', 1, 2) diretoria  into    diretoria_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1;


        v_etapa := 14000;
        select regexp_substr(regional, '[^;]+', 1, 1) cod_regional into   cod_regional_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1;

        v_etapa := 14010;
        select regexp_substr(regional, '[^;]+', 1, 2) regional   into     regional_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1;

        v_etapa := 15000;
        select regexp_substr(ccluster, '[^;]+', 1, 1) cod_cluster into    cod_cluster_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1;

        v_etapa := 15010;
        select regexp_substr(ccluster, '[^;]+', 1, 2) desc_cluster into   desc_cluster_aux
        from vw_xml_vaga_ativa_compl where num_requis = rec.reqp_seq AND ROWNUM = 1 ;

     END IF;

     select count(1) into v_count from lst_diretoria where  Trim(cod_amx) =  Trim(cod_diretoria_aux);


    IF cod_diretoria_aux IS NOT NULL THEN
     if v_count > 0 then
         v_etapa := 14020;
        update lst_diretoria set nome = diretoria_aux where Trim(cod_amx) =  Trim(cod_diretoria_aux);
     else
        v_etapa := 14030;
       insert into lst_diretoria (nome, ordem, cod_amx, visivel) values (diretoria_aux,1,cod_diretoria_aux,'A');
     end if;
    end if;


    select count(1) into v_count from lst_regional where  Trim(cod_amx) =  Trim(cod_regional_aux);

    IF cod_regional_aux IS NOT NULL THEN
     if v_count > 0 then
         v_etapa := 14020;
        update lst_regional set nome = regional_aux where  Trim(cod_amx) =  Trim(cod_regional_aux);
     else
        v_etapa := 14030;
       insert into lst_regional (nome, ordem, cod_amx, visivel) values (regional_aux,1,cod_regional_aux,'A');
     end if;
    end if;


     select count(1) into v_count from lst_cluster where  Trim(cod_amx) =  Trim(cod_cluster_aux);

    IF cod_cluster_aux IS NOT NULL THEN
      if v_count > 0 then
          v_etapa := 15020;
          update lst_cluster set nome = desc_cluster_aux where Trim(cod_amx) = Trim(cod_cluster_aux);
      else
          v_etapa := 15030;
        insert into lst_cluster (nome, ordem, cod_amx, visivel) values (desc_cluster_aux,1,cod_cluster_aux,'A');
      end if;
    end if;

     v_etapa := 16010;
     SELECT Count(1) INTO v_count
     FROM lst_cargo
     WHERE  Trim(COD_AMX) =  Trim(rec.ID_CARGO);


     IF rec.ID_CARGO IS NOT NULL THEN
      if v_count > 0 then
          v_etapa := 16020;
          UPDATE lst_cargo SET COD_AMX = rec.ID_CARGO, visivel = 'A'  WHERE COD_AMX = Trim(rec.ID_CARGO);
      else
          v_etapa := 16030;
          INSERT INTO lst_cargo(nome, visivel, cod_AMX, ordem) VALUES(rec.CARGO, 'A', rec.ID_CARGO, 1 );
      end if;
    end if;



     v_etapa := 17010;
     select count(1) into v_count from lst_unidade_organizacional where Trim(cod_amx) = Trim(rec.CODIGO_UNIDADE);


     IF rec.CODIGO_UNIDADE IS NOT NULL THEN
      if v_count > 0 then
          v_etapa := 17020;
          update lst_unidade_organizacional set nome = rec.unidade_organizacional
          where trim(cod_amx) = trim(rec.codigo_unidade);
      else
          v_etapa := 17030;
        insert into lst_unidade_organizacional (nome, ordem, cod_amx, visivel) values (Trim(rec.UNIDADE_ORGANIZACIONAL),1,Trim(rec.CODIGO_UNIDADE),'A');
      end if;
     END IF;

    v_etapa := 19000;

    SELECT max(EMAIL),
            Max(NOME_COMPLETO)
       INTO email_gestor_aux,
            nome_gestor_aux
       FROM CANDIDATO
     WHERE  CODIGO = (SELECT max(COD_CANDIDATO) FROM FUNCIONARIO
                      WHERE MATRICULA = rec.ID_GESTOR_REQUISICAO);


    IF nome_gestor_aux IS NULL THEN
      v_etapa := 19010;
      SELECT max(EMAIL),
            Max(NOME_COMPLETO)
       INTO email_gestor_aux,
            nome_gestor_aux
       FROM CANDIDATO
        WHERE  CODIGO = (SELECT max(COD_CANDIDATO) FROM FUNCIONARIO
                         WHERE MATRICULA = rec.ID_REQUISITANTE);
    END IF;

    IF nome_gestor_aux IS NULL THEN
      v_etapa := 19020;
       nome_gestor_aux := rec.REQUISITANTE;
    END IF;

    IF nome_gestor_aux IS NULL THEN
      v_etapa := 19030;
       nome_gestor_aux := rec.GESTOR_REQUISICAO;
    END IF;



   v_etapa := 20000;


   DBMS_OUTPUT.PUT_LINE('salario: '|| rec.SALARIO );



   INSERT INTO PROCESSO_SELETIVO (cod_form,cod_empresa,cod_login,data_ins,data_upd,data_status,status,DADOS)
   SELECT 8, 2, 2, SYSDATE data_ins , SYSDATE data_upd ,SYSDATE data_status  ,0 ,
     XMLELEMENT("DADOS"
    ,XMLELEMENT("NOME"        ,LIMPAMSWORDFUNC(rec.REQP_SEQ||' - '||rec.CARGO ))
    ,XMLELEMENT(CARGO         ,(SELECT max(CODIGO) FROM lst_cargo WHERE COD_AMX = rec.ID_CARGO))
    ,XMLELEMENT(DATA_INICIO   , To_Char(SYSDATE,'YYYY-MM-DD') )
    ,XMLELEMENT(GESTOR        ,nome_gestor_aux)
    ,XMLELEMENT(EMAIL_GESTOR  ,email_gestor_aux)
    ,XMLELEMENT(TEL_GESTOR    ,tel_gestor_aux)
    ,XMLELEMENT(SOLICITANTE_RESP, (SELECT MAX(CODIGO) FROM LOGIN_ADMINISTRATIVO  WHERE TRIM(EMAIL) = TRIM(REC.EMAIL_SELEC_RESP) AND COD_GRUPO_PRIVILEGIO IN (2,3)))
    ,XMLELEMENT(COD_ANALISTA, (SELECT MAX(CODIGO) FROM LOGIN_ADMINISTRATIVO  WHERE TRIM(EMAIL) = TRIM(REC.EMAIL_SELEC_RESP) AND COD_GRUPO_PRIVILEGIO IN (2,3)))
    ,XMLELEMENT(LOCAL_TRABALHO , COD_REGIONAL_AUX)
    ,XMLELEMENT(NOME_POSICAO , POSICA_AUX)
    ,XMLELEMENT(SALARIO,rec.SALARIO)
    ,XMLELEMENT(OBSERVACOES,rec.OBSERVACAO)
    ,XMLELEMENT(DESCRICAO,null)
    ,XMLELEMENT(JUSTIFICATIVA,rec.JUSTIFICATIVA)
    ,XMLELEMENT(COD_INTERNO,  COD_POSICAO_AUX)
    ,XMLELEMENT(PERFIL_PROFISSIONAL, NULL)
    ,XMLELEMENT(TIPO_COLABORADOR, (SELECT max(CODIGO) FROM lst_tipo_colaborador WHERE COD_AMX = REC.CODIGO_TIPO_COLABORADOR))
    ,XMLELEMENT(TIPO_CONTRATO, NULL)
    ,XMLELEMENT(VAGAS,rec.POSICOES)
    ,XMLELEMENT(NOME_ANUNCIO,LIMPAMSWORDFUNC(rec.REQP_SEQ||' - '||rec.CARGO ))
    ,XMLELEMENT(PUBLICAR_ANUNCIO, 'N')
    ,XMLELEMENT(MOTIVO_CONTRATO, motivo_aux)
    ,XMLELEMENT(ESTADO,(SELECT max(CODIGO) FROM REGION WHERE acrregion = rec.SIGLA_ESTADO_LOCAL))
    ,XMLELEMENT(DIRETORIA,(select max(CODIGO) from lst_diretoria where cod_amx = cod_diretoria_aux))
    ,XMLELEMENT(AREA, REC.AREA_ATUACAO)
    ,XMLELEMENT(COD_AREA, REC.CODIGO_AREA_ATUACAO)
    ,XMLELEMENT(TIPO_RECRUTAMENTO, tipo_recrutamento_aux)
    ,XMLELEMENT(NUMERO_VAGA_CLARO, rec.REQP_SEQ)
    ,XMLELEMENT(COD_CIDADE, (SELECT max(CODIGO) FROM CITY WHERE fkregion = (SELECT max(CODIGO) FROM REGION WHERE acrregion = rec.SIGLA_ESTADO_LOCAL) AND Upper(NOME) = Upper(rec.CIDADE_LOCAL)))
    ,XMLELEMENT(DATA_ABRE, To_Char(SYSDATE,'YYYY-MM-DD') )
    ,XMLELEMENT(COD_CLUSTER, (select max(codigo) from lst_cluster where cod_amx = cod_cluster_aux))
    ,XMLELEMENT(UNIDADE_NEGOCIO, NULL)
    ,XMLELEMENT(GRADE, grade_aux)
    ,XMLELEMENT(STEP, step_aux)

    ,XMLELEMENT(STEP_MASC, step_aux_masc)


    ,XMLELEMENT(HORARIO_TRABALHO, (SELECT HORARIO_TRABALHO FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1))
    ,XMLELEMENT(COD_UNIDADE_ORGANIZACIONAL, (SELECT max(codigo) from lst_unidade_organizacional where cod_amx = rec.codigo_unidade))



    ,XMLELEMENT(DESCREQUISICAO_1_1, (SELECT FORMACAO_ACADEMICA FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1))
    ,XMLELEMENT(DESCREQUISICAO_2_1, (SELECT EXPERIENCIA_PROFISSIONAL FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1))
    ,XMLELEMENT(DESCREQUISICAO_3_1, (SELECT CARACTERISTICAS FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1))
    ,XMLELEMENT(DESCREQUISICAO_4_1, (SELECT RESPONSABILIDADES FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1))
    ,XMLELEMENT(DESCREQUISICAO_5_2, NULL)
    ,XMLELEMENT(DESCREQUISICAO_6_2, NULL)
    ,XMLELEMENT(DESCREQUISICAO_7_2, (SELECT CONCHECIMENTO_TECNICO FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ  AND ROWNUM = 1))
    ,XMLELEMENT(DESCREQUISICAO_8_2, (SELECT OUTROS FROM vw_xml_vaga_ativa_requisitos WHERE  num_requis = rec.REQP_SEQ AND ROWNUM = 1))


    ,XMLELEMENT("ETAPAS"
    ,XMLELEMENT(VALOR ,  XMLAttributes(1 as "ordem"), '3')
    ,XMLELEMENT(VALOR ,  XMLAttributes(1 as "ordem"), '4')
    ,XMLELEMENT(VALOR ,  XMLAttributes(2 as "ordem"), '15')
    ,XMLELEMENT(VALOR ,  XMLAttributes(3 as "ordem"), '12')
    ,XMLELEMENT(VALOR ,  XMLAttributes(4 as "ordem"), '99'))

    ,XMLELEMENT(CAMPOS_ANUNCIO
    ,XMLELEMENT(VALOR ,  XMLAttributes(1 as "ordem"), 'DESCRICAO')
    ,XMLELEMENT(VALOR ,  XMLAttributes(2 as "ordem"), 'DESCREQUISICAO_4_1')
    ,XMLELEMENT(VALOR ,  XMLAttributes(3 as "ordem"), 'DESCREQUISICAO_2_1')
    ,XMLELEMENT(VALOR ,  XMLAttributes(4 as "ordem"), 'DESCREQUISICAO_7_2,')
    ,XMLELEMENT(VALOR ,  XMLAttributes(5 as "ordem"), 'DESCREQUISICAO_1_2')
    ,XMLELEMENT(VALOR ,  XMLAttributes(6 as "ordem"), 'DESCREQUISICAO_5_2')
    ,XMLELEMENT(VALOR ,  XMLAttributes(7 as "ordem"), 'DESCREQUISICAO_6_2')
    ,XMLELEMENT(VALOR ,  XMLAttributes(8 as "ordem"), 'DIRETORIA')
    ,XMLELEMENT(VALOR ,  XMLAttributes(9 as "ordem"), 'LOCAL_TRABALHO')
    ,XMLELEMENT(VALOR ,  XMLAttributes(10 as "ordem"), 'ESTADO')
    ,XMLELEMENT(VALOR ,  XMLAttributes(11 as "ordem"), 'COD_CIDADE')
    ,XMLELEMENT(VALOR ,  XMLAttributes(12 as "ordem"), 'OBS_CANDIDATO'))


    ,XMLELEMENT(IDIOMAS
      ,(SELECT XMLAGG (
        XMLELEMENT("VALOR",  XMLAttributes(r.ordem "ordem"),
        XMLELEMENT(IDIOMA, XMLCDATA(r.idioma)),
        XMLELEMENT(IDIOMA_NIVEL, XMLCDATA(r.nivel))
        )
       )
     FROM idioma_aux_rp R WHERE cod_rp = rec.REQP_SEQ)
    )


   )
     FROM DUAL;


     INSERT INTO  integracao_wsdl_log (cod_integracao, data_envio,  chave,  msg_resposta,valor_campo, tip_reg) VALUES
                                      (11, SYSDATE ,  v_key, 'Processo Seletivo Gravado => '||rec.REQP_SEQ,rec.REQP_SEQ,  1);


     COMMIT;


  END IF;


    EXCEPTION

      WHEN OTHERS THEN
        v_erro := To_Char(To_Char(SQLCODE) || '-' || To_Char(SQLERRM));
        ROLLBACK;
        v_fail := TRUE;
        INSERT INTO integracao_proc_seletivo_log(data, descricao, rotina, key_data) VALUES(SYSDATE, 'Erro na v_etapa '||v_etapa||' erro:' || v_erro || ' => ' || v_log_proc|| ' => ' ||v_req_aux, 'prc_proc_seletivo_webservice',v_key);
        v_erro := 'Entre em contato com o helpdesk';

       INSERT INTO  integracao_wsdl_log (cod_integracao, data_envio,  chave,  msg_resposta,valor_campo, tip_reg) VALUES
                  (11, SYSDATE ,  v_key, 'Erro na v_etapa '||v_etapa||' erro:' || v_erro || 'Erro no processo => '||v_req_aux ,v_req_aux,  1);



          v_corpo2 := 'Erro na v_etapa '||v_etapa||' erro:' || v_erro || 'Erro no processo => '||v_req_aux || '  prc_proc_seletivo_webservice';

          prc_envia_email_html( P_DESTINO => 'damaral@i-hunter.com',
                                P_ASSUNTO => 'Erro na integração de processo seletivo - CLAROV7',
                                P_BCC     => 'davisallesamaral@gmail.com',
                                P_HTML    => ihunter_pkg.wrapemailbody(v_corpo2));



        COMMIT;

    END;

  END LOOP;

  IF v_fail THEN
    INSERT INTO integracao_log(status,COD_INTEGRACAO,data, descricao, obs, rotina) VALUES('ADVERTÊNCIA' ,11,SYSDATE, 'ADVERTÊNCIA - Um ou mais registros não foram processados - Razão: ' || v_erro, 'VERIFICAR TABELA INTEGRACAO_PROC_SELETIVO_LOG', 'prc_proc_seletivo_webservice');
  ELSE
    INSERT INTO integracao_log(status,COD_INTEGRACAO,data, descricao, rotina) VALUES( 'SUCESSO' ,11,SYSDATE, 'SUCESSO', 'prc_proc_seletivo_webservice');

    update tabela_temporaria set lida = 1
    where cod_integracao = 12 AND To_Date(data_envio, 'dd/mm/yyyy') = To_Date(sysdate, 'dd/mm/yyyy') ;

    update tabela_temporaria set lida = 1
    where cod_integracao = 15 AND To_Date(data_envio, 'dd/mm/yyyy') = To_Date(sysdate, 'dd/mm/yyyy') ;

    update tabela_temporaria set lida = 1
    where cod_integracao = 11 AND To_Date(data_envio, 'dd/mm/yyyy') = To_Date(sysdate, 'dd/mm/yyyy') ;

     COMMIT;

  END IF;

  COMMIT;

  pkg_integracao2.cco_log;


EXCEPTION
  WHEN no_data THEN
    INSERT INTO integracao_log(status,COD_INTEGRACAO,data, descricao, obs, rotina) VALUES( 'SUCESSO' ,11,SYSDATE, 'SUCESSO' , 'Nenhum registro encontrado', 'prc_proc_seletivo_webservice');
    update tabela_temporaria set lida = 1
    where cod_integracao = 12 AND To_Date(data_envio, 'dd/mm/yyyy') = To_Date(sysdate, 'dd/mm/yyyy') ;

    update tabela_temporaria set lida = 1
    where cod_integracao = 15 AND To_Date(data_envio, 'dd/mm/yyyy') = To_Date(sysdate, 'dd/mm/yyyy') ;

    update tabela_temporaria set lida = 1
    where cod_integracao = 11 AND To_Date(data_envio, 'dd/mm/yyyy') = To_Date(sysdate, 'dd/mm/yyyy') ;


    COMMIT;

  WHEN OTHERS THEN
    v_erro := To_Char(To_Char(SQLCODE)|| '-' ||To_Char(SQLERRM));

    ROLLBACK;

    INSERT INTO integracao_log(status,COD_INTEGRACAO,data, descricao, obs, rotina) VALUES( 'FALHA', 11,SYSDATE, 'FALHA' ,v_erro, 'prc_proc_seletivo_webservice');

    COMMIT;


END PRC_PROC_SELETIVO_WEBSERVICE;
/

GRANT EXECUTE ON prc_proc_seletivo_webservice TO cand_clarov7;
GRANT EXECUTE ON prc_proc_seletivo_webservice TO cand_fiergs;
GRANT EXECUTE ON prc_proc_seletivo_webservice TO conn_clarov7;
GRANT EXECUTE ON prc_proc_seletivo_webservice TO conn_fiergs;
