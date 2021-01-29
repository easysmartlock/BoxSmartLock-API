<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;
use Twilio\Security\RequestValidator;
use Mail;
use App\Mail\Receipt;
use App\Models\Box;
use App\Models\Easy;
use App\Models\Telephone;
use App\Models\Response;

/**
 * Webhook controller for twilio
 */
class WebhookController extends Controller {

    /**
     * main action
     * 
     * @param Request $request
     */
    public function index(Request $request) 
    {
        $signature = in_array('HTTP_X_TWILIO_SIGNATURE',$_SERVER) ?  $_SERVER["HTTP_X_TWILIO_SIGNATURE"] : '';
        $validator = new RequestValidator(config('twilio.token'));
        $post = $request->all();
        $url = route('webhook');

        if ($validator->validate($signature, $url, $post)) {
            $msg = "Confirmed to have come from Twilio.";
        } else {
            $msg = "NOT VALID. It might have been spoofed!";
        }

        $body = $request->input('Body');
        $from = $request->input('From');

        $reponse = new Response();
        $reponse->from = $from;
        $reponse->body = $body;
        $reponse->save();

        $box = Box::where('telephone', $from)->first();
        if(!empty($box)) {
            foreach($box->telephones as $telephone) {
                $telephone->delete();
            }
            $tabs = $this->parseInput($body);
            if(count($tabs) > 0) {
                foreach($tabs as $tab) {
                    if(!empty($tab)) {
                        $data = explode(':', $tab);
                        if(count($data) > 1) {
                            $telephone = new Telephone();
                            $telephone->box()->associate($box);
                            $telephone->ordre = $data[0];
                            if(stripos($data[1], 'empty') === false) {
                                $telephone->telephone = $data[1];
                            } 
                            $telephone->save();
                        }
                    }
                }
            }          
        }
        $easy = Easy::where('telephone', $from)->first();

        Mail::to('lala.misa.09@googlemail.com')->send(new Receipt($body));

        $response = new MessagingResponse();
        $response->message($msg);
        return response($msg, 200);
    }

    private function parseInput(string $str)
    {
        $tab = explode("\n", $str);
        return $tab;
    }

}