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