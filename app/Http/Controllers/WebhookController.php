<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;
use Twilio\Security\RequestValidator;
use Mail;
use App\Mail\Receipt;
use App\Models\Box;
use App\Models\Easy;
use App\Models\User;
use App\Models\Telephone;
use App\Models\Response;
use App\Service\Twilio;

/**
 * Webhook controller for twilio
 */
class WebhookController extends Controller {

    /**
     * main action
     * 
     * @param Request $request
     */
    public function index(Request $request,Twilio $twilio) 
    {
        $signature = in_array('HTTP_X_TWILIO_SIGNATURE',$_SERVER) ?  $_SERVER["HTTP_X_TWILIO_SIGNATURE"] : '';
        $validator = new RequestValidator(config('twilio.token'));
        $post = $request->all();
        $url = route('webhook');

        //if ($validator->validate($signature, $url, $post)) {
            $msg = "Confirmed to have come from Twilio.";
            $body = $request->input('Body');
            $from = $request->input('From');

            $easy = null;
            $box = null;

            $reponse = new Response();
            $reponse->from = $from;
            $reponse->body = $body;

            //box
            $box = Box::where('telephone', $from)->first();
            if($box) {
                $reponse->user_id = $box->user_id;
            }
            //easy
            $easy = Easy::where('telephone', $from)->first();
            if($easy) {
                $reponse->user_id = $easy->user_id;   
            }

            $reponse->save();
        //} else {
        //    $msg = "NOT VALID. It might have been spoofed!";
        //}


        if(!empty($reponse) && !empty($reponse->user_id)) {
            $user = User::find($reponse->user_id);
            if($user) {
                try {
                    $twilio->send($user->telephone,$body);
                } catch(\Exception $e) {
                    
                }
            }            
        }

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