<?php
namespace App\Service;

use Twilio\Rest\Client;
use App\Models\Box;
use Carbon\Carbon;

class Twilio {


    const ACTION_OPEN = 'open' ;

    const ACTION_CLOSE = 'close';

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
     * @return bool
     */
    public function addTelBox(Box $box,string $telephone,string $debut,string $fin,bool $unlimited = true)
    {
        $msg = "" ;
        if($unlimited == true) {
            $msg = $box->pass . "A0". rand(10,20) ."#". $this->format($telephone) ."#";
        } else {
            $msg = $box->pass . "A0". rand(10,20) ."#". $this->format($telephone) ."#" .$this->formatDate($debut). "#" .$this->formatDate($fin). "#";
        }

        try {
            $this->send($box->telephone,$msg);
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
     * @return bool
     */
    public function editAccess(Box $box, string $action)
    {
        $todo = 'AUT' ;
        if($action == self::ACTION_OPEN) {
            $todo = 'ALL' ;
        }
        
        $message = $box->pass . $todo .'#' ;
        try {
            $this->send($box->telephone,$message);
            return true;
        }catch(\Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

}