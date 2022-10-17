<?php

namespace App\Http\Controllers;

use App\Models\Gold;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class GoldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gold  $gold
     * @return \Illuminate\Http\Response
     */
    public function show(Gold $gold)
    {
        return view("lineView");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gold  $gold
     * @return \Illuminate\Http\Response
     */
    public function edit(Gold $gold)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gold  $gold
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gold $gold)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gold  $gold
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gold $gold)
    {
        //
    }

    public function webhook(Request $request)
    {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config("line-bot.line-bot-access-token"));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => config("line-bot.line-bot-secret")]);

        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        if (empty($signature)){
            abort(400);
        }

        Log::info($request->getContent());

        try {
            $events = $bot->parseEventRequest($request->getContent(), $signature);
        } catch (InvalidSignatureException $e) {
            Log::error('Invalid signature');
            abort(400,'Invalid signature');
        } catch (InvalidEventRequestException $e) {
            Log::error('Invalid event request');
            abort(400,'Invalid event request');
        }


        foreach ($events as $event){
            if(!($event instanceof MessageEvent)) {
                Log::info('Non message event has come');
                continue;
            }
            if(!($event instanceof TextMessage ) && !($event instanceof StickerMessage)) {
                Log::info('Non text message or Sticker has come');
                continue;
            }

            $replyToken = $event->getReplyToken();
            if($event instanceof StickerMessage){
                $inputPackage = $event->getPackageId();
                $inputID = $event->getStickerId();
                $multiMessageBuilder = new MultiMessageBuilder();
                $multiMessageBuilder->add(new TextMessageBuilder($inputPackage));
                $multiMessageBuilder->add(new TextMessageBuilder($inputID));
                $response = $bot->replyMessage($replyToken, $multiMessageBuilder);


            }

            if($event instanceof TextMessage){
                $inputText = $event->getText();
                $replyText = '';
                if ($inputText === 'give me 10 scores'){
                    $replyText = Gold::inRandomOrder()->first()->name;
                }

                else {
                    Log::info('inputText: ' . $inputText);
                }
                $userId = $event->getUserId();
                $profile = $bot->getProfile($userId);
                $profile = $profile->getJSONDecodedBody();
                $displayName = $profile['displayName'];
                $pictureUrl = $profile['pictureUrl'];
                $statusMessage = $profile['statusMessage'];

                if ($replyText !== '') {
                    $response = $bot->replyText($replyToken, $replyText);

                    Log::info($response->getHTTPStatus().':'.$response->getRawBody());
                } else {
                    $multiMessageBuilder = new MultiMessageBuilder();
                    $multiMessageBuilder->add(new TextMessageBuilder($displayName));
                    $multiMessageBuilder->add(new TextMessageBuilder($statusMessage));
                    $multiMessageBuilder->add(new TextMessageBuilder($pictureUrl,$pictureUrl));
                    $response = $bot->replyMessage($replyToken, $multiMessageBuilder);
                }

            }

        }

        return response()->json([]);
    }

}
