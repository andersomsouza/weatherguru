<?php

/**
 * Weather data from Yr.No
 */
class WeatherData
{
    public $maxima = 0;
    public $minima = 0;
    public $precipitacao = 0;


    /**
     * @return $this
     * @throws Exception
     */
    function getTempo()
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.yr.no/place/Brazil/Rio_Grande_do_Sul/Pelotas/forecast.xml");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
$xml = simplexml_load_string($output);
if ($xml === false) {
echo "Failed loading XML: ";
foreach (libxml_get_errors() as $error) {
echo "<br>", $error->message;

}
throw new Exception();
} else {
    $hoje = getdate();
    $precipitationHold = 0;
    $minimHold = 100;
    $maximHold = -100;
    foreach ($xml->forecast->tabular->children() as $time) {
        $tempo = strptime($time['from'], '%Y-%m-%dT%H:%M:%S');
        if ($tempo['tm_mday'] == $hoje['mday']) {
            $precipitationHold += $time->precipitation['value'];
            if ($time->temperature['value'] > $maximHold) {
                $maximHold = intval($time->temperature['value']);
            }
            if ($time->temperature['value'] < $minimHold) {
                $minimHold = intval($time->temperature['value']);
            }
        }
    }

    $this->maxima = $maximHold;
    $this->minima = $minimHold;
    $this->precipitacao = $precipitationHold;

    return $this;
}
}

}