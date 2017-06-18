<?php
//CodeBird script to access twitter
require_once('codebird-php/src/codebird.php');
//File with strings to login in twitter
require('TwitterAuth.php');
//Class developed for get and store weather variables.
require('WeatherData.php');

$cb = \Codebird\Codebird::getInstance();
\Codebird\Codebird::setConsumerKey(twitterAuth::$consumerKey, twitterAuth::$consumerSecret);
$cb->setToken(twitterAuth::$tokenKey, twitterAuth::$tokenSecret);
var_dump(getdate());


$prev = new WeatherData();
for ($i = 0; $i < 5; $i++) {
    try {
        $prev->getTempo();
        break;
    } catch (Exception $e) {
        echo 'Deu erro!';
    }
}
var_dump($prev);

$bomdia = "Bom dia, Pelotas! ";
$status = "";
//muito frio		
if ($prev->minima < 10) {

    $status .= $bomdia . "Hoje é um dia díficil de sair da cama, minima de " . $prev->minima . "ºC. ";
} else {
    $status .= $bomdia . "Hoje o dia começa normal nos pampas gaúchos, minima de " . $prev->minima . "ºC. ";
}
//quente
if ($prev->maxima == $prev->minima) {
    $status .= "E tende à permanecer o dia assim, sem grandes alterações. ";
} elseif ($prev->maxima - $prev->minima > 7) {
    $status .= "À tarde a temperatura varia " .( $prev->maxima - $prev->minima ). "ºC, com máxima de " . $prev->maxima . "ºC. ";
} else {
    $status .= "Sem variações radicais, a temperatura máxima é de " . $prev->maxima . "ºC. ";
}


$params = array(
    'status' => $status
);


//se vai chover
if ($prev->precipitacao > 0) {
    $status2 = "";
//chuva moderada
    if ($prev->precipitacao < 5) {
        if ($prev->maxima < 18) {
            $status2 .= "Nesse dia frio e de pouca chuva (" . $prev->precipitacao . "mm), apenas um guarda-chuva serve.";
        } else {
            $status2 .= "Nesse dia quente, a chuva de " . $prev->precipitacao . "mm pode ajudar à refrescar!";
        }
    } elseif ($prev->precipitacao > 5 && $prev->precipitacao < 40) {
        if ($prev->maxima < 18) {
            $status2 .= "Nesse dia frio, a chuva de " . $prev->precipitacao . "mm já obriga os pelotenses a utilizar canoas em determinados cruzamentos da cidade.";
        } else {
            $status2 .= "Com o calor de até ".$prev->maxima."ºC e chuva de " . $prev->precipitacao . "mm, em certos lugares de Pelotas já é preciso cruzar de bote.";
        }
    } else {
        if ($prev->maxima < 18) {
            $status2 .= "A previsão para hoje é de " . $prev->precipitacao . "mm, suficiente para ir trabalhar/estudar de barco.";
        } else {
            $status2 .= "Dia quente, porém " . $prev->precipitacao . "mm de chuva, em Pelotas a circulação é feita por barcos";
        }
    }

    $params2 = array(
        'status' => $status2
    );

    for ($a = 0; $a < 5; $a++) {
        try {
            $reply = $cb->statuses_update($params2);
            var_dump($reply);
            break;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    echo $status2 . "\n";
}
for ($a = 0; $a < 5; $a++) {
    try {
        $reply = $cb->statuses_update($params);
        var_dump($reply);
        break;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


echo $status . "\n";
die();
