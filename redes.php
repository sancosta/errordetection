<?php

//NOME: SAMUEL COSTA 
//RA: 21508670
//DISCIPLINA: REDE DE COMPUTADORES
//EXECUTION : php -S localhost:8000

$dados = (!empty($_POST["dados"]))      ? $_POST["dados"]   : "";
$metod = (!empty($_POST["metod"]))      ? $_POST["metod"]   : "";
$simer = (!empty($_POST["emissor"]))    ? $_POST["emissor"] : "";
$obser;

switch($metod){
    case "01":
        $obser = "";
        $response["DADO_EMITIDO"] = emissorparidadesimples($dados);
        $response["DADO_RECEBIDO"] = (empty($simer)) ? $response["DADO_EMITIDO"] : $simer ;
        $response["DADO_IS_VALID"] = receptorparidadesimples($response["DADO_RECEBIDO"]);
        $response["OBSERVACAO"] = $obser;
        break;
    case "02":
        $obser = "";
        $response["DADO_EMITIDO"] = $dados;
        $response["DADO_RECEBIDO"] = (empty($simer)) ? $response["DADO_EMITIDO"] : $simer ;
        $response["DADO_IS_VALID"] = receptorparidadedupla($response["DADO_RECEBIDO"]);
        $response["OBSERVACAO"] = $obser;
        break;
    case "03":
        $obser = "";
        $response["DADO_EMITIDO"] = emissorcrc($dados);
        $response["DADO_RECEBIDO"] = (empty($simer)) ? $response["DADO_EMITIDO"] : $simer ;
        $response["DADO_IS_VALID"] = receptorcrc($response["DADO_RECEBIDO"]);
        $response["OBSERVACAO"] = $obser;
        break;
    case "04":
        $obser = "";
        $response["DADO_EMITIDO"] = emissorchecksum($dados);
        $response["DADO_RECEBIDO"] = (empty($simer)) ? $response["DADO_EMITIDO"] : $simer ;
        $response["DADO_IS_VALID"] = receptorchecksum($response["DADO_RECEBIDO"]);
        $response["OBSERVACAO"] = $obser;
        break;
    case "05":
        $obser = "";
        $response["DADO_EMITIDO"] = emissorhamming($dados);
        $response["DADO_RECEBIDO"] = (empty($simer)) ? $response["DADO_EMITIDO"] : $simer ;
        $response["DADO_IS_VALID"] = receptorhamming($response["DADO_RECEBIDO"]);
        $response["OBSERVACAO"] = $obser;
        break;
    default:
        $response["OBSERVACAO"] = "MÉTODO NÃO IDENTIFICADO!";
}

echo json_encode($response);

//DETECÇÃO DE ERROS

//01 - PARIDADE DUPLA

//RECEPTOR PARA VALIDAR POR PARIDADE DUPLA
function receptorparidadedupla($bin){

    global $obser;
    $return = false;
    $array = str_split($bin, 8);
    $arrayvertical[8];
    for($i = 0; $i < count($array); $i++){
        $array[$i] = str_split($array[$i]);
        if(array_sum($array[$i]) % 2 != 0){
            $obser .= "</br><b>ERRO DETECTADO:</b> BLOCO {$i} ESTA CORROMPIDO";
            $return = true;
        }
    }
    for($j = 0; $j < count($array[0]); $j++){
        $arrayvertical[$j] = array();
    }
    for($j = 0; $j < count($array); $j++){
        for($i = 0; $i < count($array[0]); $i++){
            array_push($arrayvertical[$i], $array[$j][$i]);
        }
    }
    for($i = 0; $i < count($arrayvertical); $i++){
        if(array_sum($arrayvertical[$i]) % 2 != 0){
            $obser .= "</br><b>ERRO DETECTADO:</b> COLUNA {$i} ESTA CORROMPIDA";
            $return = true;
        }
    }
    if(!$return){
        $obser .= "</br><b>BLOCOS RECEBIDOS SEM ERROS:</b> TIPO - PARIDADE DUPLA";
    }

    return $return ;

}

//01 - PARIDADE SIMPLES

//EMISSOR DE PARIDADE SIMPLES
function emissorparidadesimples($bin){

    global $obser;
    $array = str_split($bin);
    if(array_sum($array) % 2 == 0){
        array_push($array,0);
    } else {
        array_push($array,1);
    }

    return implode("",$array);

}

//RECEPTOR PARA PARIDADE SIMPLES
function receptorparidadesimples($bin){

    global $obser;
    $return = false;
    $array = str_split($bin);
    if(array_sum($array) % 2 != 0){
        $obser .= "</br><b>ERRO DETECTADO:</b> O BLOCO ESTA CORROMPIDO";
        $return = true;
    } else {
        $obser .= "</br><b>BLOCO RECEBIDO SEM ERROS:</b> TIPO - PARIDADE SIMPLES";
    }

    return $return;
    
}

//02 - CYCLIC REDUNDANCY CHECK (CRC)

//EMISSOR CRC
function emissorcrc($bin){

    global $obser;
    $dividendo = $bin."000";
    $array = str_split($dividendo);
    $divisor = "1101";
    $quociente = "1";
    $resto = "";
    $auxiliar = $array[0].$array[1].$array[2].$array[3];

    for($i = 4; $i <= count($array); $i++){
        $resto = decbin(bindec($auxiliar) ^ bindec($divisor));
        $resto .= $array[$i];
        $resto = str_pad($resto, 4, "0", STR_PAD_LEFT);
        $auxiliar = $resto;
        if(substr($resto,0,1) == "0"){
            $divisor = "0000";
            $quociente .= "0";
        } else {
            $divisor = "1101";
            $quociente .= "1";
        }
    }
    
    $quociente = substr($quociente, 0, -1);
    $resto = substr($resto, -3);

    $obser .= "</br><b>EMISSOR CRC</b>";
    $obser .= "</br><b>BINÁRIO:</b> {$bin}";
    $obser .= "</br><b>RESTO:</b> {$resto}";
    $obser .= "</br><b>RESULTADO DA EMISSAO:</b> {$bin}{$resto}";

    return $bin.$resto;

}

//RECEPTOR CRC
function receptorcrc($bin){

    global $obser;
    $array = str_split($bin);
    $divisor = "1101";
    $quociente = "1";
    $resto = "";
    $auxiliar = $array[0].$array[1].$array[2].$array[3];

    for($i = 4; $i <= count($array); $i++){
        $resto = decbin(bindec($auxiliar) ^ bindec($divisor));
        $resto .= $array[$i];
        $resto = str_pad($resto, 4, "0", STR_PAD_LEFT);
        $auxiliar = $resto;
        if(substr($resto,0,1) == "0"){
            $divisor = "0000";
            $quociente .= "0";
        } else {
            $divisor = "1101";
            $quociente .= "1";
        }
    }
  
    $quociente = substr($quociente, 0, -1);
    $resto = substr($resto, -3);
    $dado = substr($bin, 0, -3);

    $obser .= "</br></br><b>RECEPTOR CRC</b>";
    $obser .= "</br><b>BINÁRIO:</b> {$bin}";
    $obser .= "</br><b>RESTO:</b> {$resto}";
    $obser .= "</br><b>DADOS RECEBIDOS:</b> {$dado}";

    return (boolean) bindec($resto);

}

//03 - CHECKSUM

//EMISSOR CHECKSUM
function emissorchecksum($bin){

    global $obser;
    $segmentos = str_split($bin, 8);
    $checksum = $segmentos[0];

    $sob = 0;
    $res;

    for($i = 1; $i < count($segmentos); $i++){
        for($j = 7; $j >= 0; $j--) {
            $res = ((bindec($checksum{$j}) ^ bindec($segmentos[$i]{$j})) ^ $sob).$res;
            if($sob){
                $sob = bindec($checksum{$j}) | bindec($segmentos[$i]{$j});
            } else {
                $sob = bindec($checksum{$j}) & bindec($segmentos[$i]{$j});
            }
        }
        $checksum = $res;
    }

    $checksum = str_replace("A","0",str_replace("0","1",str_replace("1","A",$checksum)));

    return $bin.$checksum;

}

//RECEPTOR CHECKSUM
function receptorchecksum($bin){

    global $obser;    
    $segmentos = str_split($bin, 8);
    $segmentos = array_reverse($segmentos);
    $checksum = $segmentos[0];
    
    $sob = 0;
    $res;

    for($i = 1; $i < count($segmentos); $i++){
        for($j = 7; $j >= 0; $j--) {
            $res = ((bindec($checksum{$j}) ^ bindec($segmentos[$i]{$j})) ^ $sob).$res;
            if($sob){
                $sob = bindec($checksum{$j}) | bindec($segmentos[$i]{$j});
            } else {
                $sob = bindec($checksum{$j}) & bindec($segmentos[$i]{$j});
            }
        }
        $checksum = $res;
        $res = null;
    }

    $checksum = str_replace("A","0",str_replace("0","1",str_replace("1","A",$checksum)));

    $return = (boolean) bindec($checksum);

    $obser .= "</br><b>CHECKSUM CALCULADO:</b> {$checksum}";

    if($return){
        $obser .= "</br><b>ERRO DETECTADO:</b> CHECKSUM INVÁLIDO";
    } else {
        $obser .= "</br><b>BLOCO RECEBIDO SEM ERROS:</b> TIPO - CHECKSUM";
    }    

    return $return;

}

//CORREÇÃO DE ERROS

//04 - HAMMING

//EMISSOR HAMMING
function emissorhamming($bin){
    
    global $obser;    
    $position = array(0,1,3,7,15,31);
    $array = str_split($bin);
    $array = array_reverse($array);
    foreach($position as $pos){
        if(count($array) >= $pos){
            array_splice($array, $pos, 0, "R");
        }
    }

    $string = implode("",$array);

    foreach($position as $pos){
        if(count($array) >= $pos){
            $auxiliar = str_split($string, ($pos + 1));
            
            for($i = 1; $i < count($auxiliar); $i +=2){
                unset($auxiliar[$i]);    
            }

            if(array_sum(str_split(substr(implode("",$auxiliar), 1))) % 2 == 0){
                $array[$pos] = 0;
            } else {
                $array[$pos] = 1;
            }

            $string = substr($string, ($pos + 1));
        }
    }
    
    return implode("",array_reverse($array));

}

//RECEPTOR HAMMING
function receptorhamming($bin){
    
    global $obser;    
    $position = array(0,1,3,7,15,31);
    $array = str_split($bin);
    $array = array_reverse($array);

    $string = implode("",$array);
    $analise = array();
    foreach($position as $pos){
        if(count($array) >= $pos){
            $auxiliar = str_split($string, ($pos + 1));
            
            for($i = 1; $i < count($auxiliar); $i +=2){
                unset($auxiliar[$i]);    
            }

            if(array_sum(str_split(implode("",$auxiliar))) % 2 == 0){
                array_unshift($analise, 0);
            } else {
                array_unshift($analise, 1);
            }

            $string = substr($string, ($pos + 1));
        }
    }
    
    $position_erro = bindec(implode("",$analise));
    
    if($position_erro != 0){

        $array[$position_erro - 1] = ($array[$position_erro - 1] == 0) ? 1 : 0 ;
        $bin_corrigido = implode("",array_reverse($array));    

        $obser .= "</br><b>ERRO DETECTADO POR HAMMING:</b> POSIÇÃO {$position_erro} CORROMPIDA";
        $obser .= "</br><b>BINÁRIO EMITIDO:</b> {$bin}";
        $obser .= "</br><b>BINÁRIO CORRIGIDO:</b> {$bin_corrigido}";

    } else {

        $obser .= "</br><b>BLOCO RECEBIDO SEM ERROS:</b> TIPO - HAMMING";

    }

    return (boolean) $position_erro;

}