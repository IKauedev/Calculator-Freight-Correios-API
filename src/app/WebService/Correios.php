<?php

namespace Kaue\ApiCorreios\app\WebService;

/**
 * Summary of Correios
 */
class Correios
{

    /*URL BASE da API */
    const URL_BASE = 'http://ws.correios.com.br';
    /*
    * Codigo de serviço dos correios
    * @var string
    *
    */

    const SERVICO_SEDEX_12 = '04782';
    const SERVICO_SEDEX = '04014';
    const SERVICO_SEDEX_HOJE = '04804';
    const SERVICO_SEDEX_10 = '04790';
    const SERVICO_PAC = '04510';

    /*
    * Codigo dos formatos do correio
    * @var string
    *
    */
    const FORMATO_CAIXA_PACOTE = 1;
    const FORMATO_ROLO_PRIMA = 2;
    const FORMATO_ENVELOPE = 3;
    /**
     * Codigo da empresa com contrato
     * @var string
     */
    private $codigoEmpresa ='';

        /**
     * Senha Empresa
     * @var string
     */
    private $senhaEmpresa='';


    /**
     * Metodo de definição de contrato de webService dos correios
     * 
     * @param mixed $codigoEmpresa
     * @param mixed $senhaEmpresa
     */
    public function __construct($codigoEmpresa ='',$senhaEmpresa='')  {
        $this->codigoEmpresa = $codigoEmpresa;
        $this->senhaEmpresa = $senhaEmpresa;
    }

    /**
     *  Metodo resposavel por calcular frete dos correios
     * 
     * @param string $codigoServico
     * @param string $cepOrigem
     * @param string $cepDestino
     * @param float $peso
     * @param int $formato
     * @param int $comprimento
     * @param int $altura
     * @param int $largura
     * @param int $diametro
     * @param boolean $maoPropria
     * @param int $valorDeclarado
     * @param boolean $avisoRecebimento
     * @return object
     */
    public function calcularFrente($codigoServico,
                                    $cepOrigem,
                                    $cepDestino,
                                    $peso,
                                    $formato,
                                    $comprimento,
                                    $altura,
                                    $largura,
                                    $diametro = 0,
                                    $maoPropria = false,
                                    $valorDeclarado = 0,
                                    $avisoRecebimento = false) {

          //Parametros da URL de CALCULO
          $parametros = [

            'nCdEmpresa' => $this->codigoEmpresa,
            'sDsSenha' => $this->senhaEmpresa,
            'nCdServico' => $codigoServico,
            'sCepOrigem' => $cepOrigem,
            'sCepDestino' => $cepDestino,
            'nVlPeso' => $peso,
            'nCdFromato' => $formato,
            'nvlComprimento' => $comprimento,
            'nvlAltura' => $altura,
            'nvlLargura' => $largura,
            'nVlDiametro' => $diametro,
            'sCdMaoPropria' => $maoPropria ? 'S' : 'N',
            'nVlValorDeclarado' => $valorDeclarado,
            'sCdAvisoRecebimento' => $avisoRecebimento ? 'S' : 'N',
            'StrRetorno' => 'xml'

          ];                

          //Query 
          $query = http_build_query($parametros);

          //Executa a consulta do frete
          $resultado = $this->getCorreios('/calculador/CalcPrecoPrazo.aspx?'.$query); //EndPoint


       
          //Retorna os dados do Frete calculado
          return $resultado ? $resultado->CServico : null;
    }
 
    /**
     * Metodo resposavel por executar a consulta GET no Webservice dos correios
     * @param String $resource
     * @return Object
     */

    public function getCorreios($resource) {
        //EndPoint
        $endPoint = self::URL_BASE.$resource;

       
        //Inicia o CURL
        $curl = curl_init();

        //Configurações do CURL
        curl_setopt_array($curl,[
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true, //Retornando o conteudo para uma variavel
            CURLOPT_CUSTOMREQUEST => 'GET'  //Definindo o tipo da requisição
        ]);

        echo "<pre>";
        print_r($curl);
        echo "</pre>";
        //Executa curl
        $response = curl_exec($curl);

        //Fecha a conexão do CURL 
        curl_close($curl);
        
        //retorna o XML instaciado
        return strlen($response) ? simplexml_load_string($response) : null;
    }
    
}