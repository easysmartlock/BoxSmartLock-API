<?php

namespace App\Service;

class Constante {

    const API_OK = 'OK';

    const API_KO = 'KO';

    const PROP_ETAT = 'etat';

    const PROP_MESSAGE = 'message';

    const PROP_DATA = 'data' ;

    /**
     * Retourne cadre logique
     * @return array
     */
    public static function getReponse()
    {
        return [
            self::PROP_ETAT => self::API_KO,
            self::PROP_MESSAGE => '' ,
            self::PROP_DATA => null
        ];
    }

}