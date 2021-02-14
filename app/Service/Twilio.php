<?php
namespace App\Service;

use Twilio\Rest\Client;
use App\Models\Box;
use App\Models\Easy;
use App\Models\User;
use App\Models\Telephone;
use App\Models\EasyTelephone;
use App\Models\Historique as HModel;
use Carbon\Carbon;

class Twilio {


    const ACTION_OPEN = 'open' ;

    const ACTION_CLOSE = 'close';

    const ACTION_DISABLE = 'disable' ;

    /**
     * @var Twilio\Rest\Client
     */
    private $client;

    public function __construct() {
        $this->client = new Client(config('twilio.sid'), config('twilio.token'));
        $this->client->setLogLevel('debug');
    }

    /**
     * Send twilio
     * 
     * @param string $to
     * @param string $body
     * @return void
     */
    public function send(String $to,String $body) {
        $this->client->messages->create(
            $to,
            [
                'from' => config('twilio.from'),
                'body' => $body
            ]
        );
    }

    /**
     * Format phone number
     * @param string $tel
     * @return string
     */
    public function format(string $tel)
    {
        return str_replace('+','',$tel);
    }

    /**
     * Format Date
     * @param string $date
     * @return string
     */
    public function formatDate(string $date)
    {
        $_date = Carbon::parse($date);
        $day = $_date->day;
        $month = $_date->month;
        $hour = $_date->hour;
        $minute = $_date->minute;

        if(strlen($day) < 2) $day = '0' . $day;
        if(strlen($month) < 2) $month = '0' . $month;
        if(strlen($hour) < 2) $hour = '0' . $hour;
        if(strlen($minute) < 2) $minute = '0' . $minute;

        return $_date->year . $month . $day . $hour . $minute;
    }

    /**
     * Ajout un telephone box
     * 
     * @param App\Models\Box
     * @param string $tel
     * @param User $user
     * @return bool
     */
    public function addTelBox(Box $box,string $telephone,string $debut,string $fin,bool $unlimited = true,string $ordre,User $user)
    {
        $msg = "" ;
        $ordre = $ordre + Telephone::LIMIT;
        if(strlen($ordre) < 2) {
            $ordre = '0' . $ordre;
        }
        if($unlimited == true) {
            $msg = $box->pass . "A0". $ordre ."#". $this->format($telephone) ."#";
        } else {
            $msg = $box->pass . "A0". $ordre ."#". $this->format($telephone) ."#" .$this->formatDate($debut). "#" .$this->formatDate($fin). "#";
        }

        try {
            $this->send($box->telephone,$msg);
            Historique::save($box->id,HModel::modelBox,HModel::ajoutTel,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * ouvre ou restreint access
     * 
     * @param Box $box
     * @param string $action
     * @param User $user
     * @return bool
     */
    public function editAccess(Box $box, string $action, User $user)
    {
        $todo = 'AUT' ;
        if($action == self::ACTION_OPEN) {
            $todo = 'ALL' ;
        }
        
        $message = $box->pass . $todo .'#' ;
        try {
            $this->send($box->telephone,$message);
            Historique::save($box->id,HModel::modelBox,HModel::access,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * modifie durée ouverture porte
     * 
     * @param Box $box
     * @param string $duration
     * @param User $user
     * @return bool
     */
    public function editDuration(Box $box, string $duration, User $user)
    {
        $message = $box->pass .'GOT' . $duration .'#' ;
        try {
            $this->send($box->telephone,$message);
            Historique::save($box->id,HModel::modelBox,HModel::duration,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * modifie SMS ouverture
     * 
     * @param Box $box
     * @param string $action
     * @param User $user
     * @return bool
     */
    public function editSMS(Box $box, string $action, User $user)
    {
        $message = $box->pass .'GON10#' . $box->hebergement .'#' ;
        if($action == self::ACTION_DISABLE) {
            $message = $box->pass .'GON##' ;    
        }
        try {
            $this->send($box->telephone,$message);
            Historique::save($box->id,HModel::modelBox,HModel::sms,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * Modification mot de passe Box
     * 
     * @param Box $box
     * @param string $pass
     * @param User $user
     * @return bool
     */
    public function setBoxPassword(Box $box, string $pass, User $user)
    {
        $message = $pass .'P' . $box->pass ;
        try {
            $this->send($box->telephone,$message);
            Historique::save($box->id,HModel::modelBox,HModel::modifPass,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }


    /**
     * Demande la liste des telephones
     * 
     * @param Box $box
     * @param User $user
     * @return bool
     */
    public function requestPhone(Box $box, User $user)
    {
        $message = $box->pass. 'AL001#020#';
        try {
            $this->send($box->telephone,$message);
            Historique::save($box->id,HModel::modelBox,HModel::listeTel,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }       
    }

    /**
     * Suppresion telephone
     * @param Box $box
     * @param Telephone $telephone
     * @param User $user
     * @return bool
     */
    public function delPhone(Box $box, Telephone $telephone, User $user)
    {
        $message = $box->pass. 'A'.$telephone->ordre.'##';
        try {
            $this->send($box->telephone,$message);
            Historique::save($box->id,HModel::modelBox,HModel::suppressionTel,$user);
            
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * Modifier mot de passe serrure
     * @param Easy $easy
     * @param string $pass
     * @param User $user
     * @return bool
     */
    public function setEasyPassword(Easy $easy, string $pass, User $user)
    {
        $message = $pass .'P' . $easy->pass ;
        try {
            $this->send($easy->telephone,$message);
            Historique::save($easy->id,HModel::modelEasy,HModel::modifPass,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }


    /**
     * Ajout un telephone serrure
     * 
     * @param App\Models\Easy
     * @param string $tel
     * @param string $debut
     * @param string $fin
     * @param bool $unlimited
     * @param User $user
     * @return bool
     */
    public function addTelEasy(Easy $e,string $telephone,string $debut,string $fin,bool $unlimited = true,string $ordre, User $user)
    {
        $msg = "" ;

        $ordre = $ordre + Telephone::LIMIT;
        if(strlen($ordre) < 2) {
            $ordre = '0' . $ordre;
        }

        if($unlimited == true) {
            $msg = $e->pass . "A0". $ordre ."#". $this->format($telephone) ."#";
        } else {
            $msg = $e->pass . "A0". $ordre ."#". $this->format($telephone) ."#" .$this->formatDate($debut). "#" .$this->formatDate($fin). "#";
        }

        try {
            $this->send($e->telephone,$msg);
            Historique::save($e->id,HModel::modelEasy,HModel::ajoutTel,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * ouvre ou restreint access
     * 
     * @param Easy $e
     * @param string $action
     * @param User $user
     * @return bool
     */
    public function editEasyAccess(Easy $e, string $action, User $user)
    {
        $todo = 'AUT' ;
        if($action == self::ACTION_OPEN) {
            $todo = 'ALL' ;
        }
        
        $message = $e->pass . $todo .'#' ;
        try {
            $this->send($e->telephone,$message);
            Historique::save($e->id,HModel::modelEasy,HModel::access,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * modifie durée ouverture porte
     * 
     * @param Easy $e
     * @param string $duration
     * @param User $user
     * @return bool
     */
    public function editEasyDuration(Easy $e, string $duration, User $user)
    {
        $message = $e->pass .'GOT' . $duration .'#' ;
        try {
            $this->send($e->telephone,$message);
            Historique::save($e->id,HModel::modelEasy,HModel::duration,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * Demande la liste des telephones de la serrure
     * 
     * @param Easy $e
     * @param User $user
     * @return bool
     */
    public function requestEasyPhone(Easy $e, User $user)
    {
        $message = $e->pass. 'AL001#020#';
        try {
            $this->send($e->telephone,$message);
            Historique::save($e->id,HModel::modelEasy,HModel::listeTel,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }       
    }

    /**
     * Suppresion telephone serrure
     * @param Easy $e
     * @param EasyTelephone $telephone
     * @param User $user
     * @return bool
     */
    public function delEasyPhone(Easy $e, EasyTelephone $telephone, User $user)
    {
        $message = $e->pass. 'A'.$telephone->ordre.'##';
        try {
            $this->send($e->telephone,$message);
            Historique::save($e->id,HModel::modelEasy,HModel::suppressionTel,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    /**
     * modifie SMS ouverture
     * 
     * @param Easy $e
     * @param string $action
     * @param User $user
     * @return bool
     */
    public function editEasySMS(Easy $e, string $action, User $user)
    {
        $message = $e->pass .'GON10#' . $e->hebergement .'#' ;
        if($action == self::ACTION_DISABLE) {
            $message = $e->pass .'GON##' ;    
        }
        try {
            $this->send($e->telephone,$message);
            Historique::save($e->id,HModel::modelEasy,HModel::sms,$user);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

}