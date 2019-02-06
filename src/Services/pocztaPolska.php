<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 2019-01-11
 * Time: 23:05
 */

namespace App\Services;


class pocztaPolska
{

    public function sprawdzPrzesylke($numer)
    {
        $poczta = new \SoapClient('https://tt.poczta-polska.pl/Sledzenie/services/Sledzenie?wsdl',
            array(
                'trace' => 1,
                'exceptions' => true,
            ));

        $WssHeader = new WseAuthSoapHeader("sledzeniepp", "PPSA");
        $poczta->__setSoapHeaders(array($WssHeader));
        $sprawdzPrzesylke = array(
            'numer' => $numer
        );
        if ($danePrzesylki = $poczta->sprawdzPrzesylke($sprawdzPrzesylke)->return->status == 0 && isset($poczta->sprawdzPrzesylke($sprawdzPrzesylke)->return->status)) {
            $danePrzesylki = $poczta->sprawdzPrzesylke($sprawdzPrzesylke)->return->danePrzesylki->zdarzenia->zdarzenie;
            return $danePrzesylki;
        } else {
            return false;
        }


    }
}