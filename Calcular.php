<?php

use Kaue\ApiCorreios\app\WebService\Correios;

include 'vendor/autoload.php';


$obCorreios = new Correios();


//Dados para o calculo do frete
$codigoServico = Correios::SERVICO_SEDEX;
$cepOrigem = "07739800"; //Defina o ponto de partida
$cepDestino = "42809130"; // Destino final
$peso = 4;
$formato = Correios::FORMATO_CAIXA_PACOTE;
$comprimento = 15;
$altura = 15;
$largura = 15;
$diametro = 0;
$maoPropria = false;
$valorDeclarado = 0;
$avisoRecebimento = false;


//Executa o calculo de frete
$frete = $obCorreios->calcularFrente(

$codigoServico,
$cepOrigem,
$cepDestino,
$peso,
$formato,
$comprimento,
$altura,
$largura,
$diametro,
$maoPropria,
$valorDeclarado,
$avisoRecebimento

);

//Verifica o resultado 
if(!$frete) {
    die("Problemas ao calcular o frete");
}


//Verifica o erro
if(strlen($frete->MsgErro)) {
    die ("Erro: ".$frete->MsgErro);
}

//Imprime os Dados da consulta

echo "CEP Origem:" . $cepOrigem . "\n";
echo "CEP Destino:" . $cepDestino . "\n";
echo "Valor: " .$frete->Valor . "\n";
echo "Prazo: " . $frete-> PrazoEntrega . "\n";