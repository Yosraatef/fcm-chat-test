<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\User;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chats = Chat::all();
        return view('home',compact('chats'));
    }
    public function createChat(Request $request){
      //dd(auth()->user());
      $inputs = $request->all();
      $message = $inputs['message'];
      $chat = new Chat([
        'sender_id' => auth()->user()->id,
        'sender_name' => auth()->user()->name,
        'message' => $message,
      ]);
      $this->broadcastMessage(auth()->user()->name,$message);
      $chat->save();
      return redirect()->back();
    }
    private function broadcastMessage($senderName , $message){
      $optionBuilder = new OptionsBuilder();
      $optionBuilder->setTimeToLive(60*20);

      $notificationBuilder = new PayloadNotificationBuilder('new message : ' . $senderName);
      $notificationBuilder->setBody($message)
      				    ->setSound('default')
                  ->setClickAction('http://127.0.0.1:8000/home');

      $dataBuilder = new PayloadDataBuilder();
      $dataBuilder->addData([
        'sender_name' => $senderName ,
        'message' => $message,
      ]);

      $option = $optionBuilder->build();
      $notification = $notificationBuilder->build();
      $data = $dataBuilder->build();

      // You must change it to get your tokens
      $tokens = User::all()->pluck('fcm_token')->toArray();
       //dd($tokens);
      $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

      return $downstreamResponse->numberSuccess();
    }
}
