@extends('layouts.app')

@section('content')
<style>
.chat-container{
  display: flex;
  flex-direction: column;
}
.chat{
  border: 1px solid gray;
  border-radius: 3px;
  width: 50%;
  padding: 0.5em;
}
.chat-left{
  background-color: white;
  align-self: start;
}
.chat-right{
  background-color: #adff2f7f;
  align-self: end;
content-align:right;
}
.message-input-container{
  position: fixed;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: white;
  border: 1px solid gray;
  padding: 1em;
}
</style>
<div class="container" style="margin-bottom:480px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                  <div class="chat-container">
                    @if(count($chats) === 0)
                    <p>There is not chat yet</p>
                    @endif
                    @foreach($chats as $chat)
                      @if($chat->sender_id === Auth::user()->id)
                      <p class="chat chat-right">
                        <b>{{$chat->sender_name}}: </b><br>
                        {{$chat->message}}
                      </p>
                      @else
                      <p class="chat chat-left">
                        <b>{{$chat->sender_name}}: </b><br>
                        {{$chat->message}}
                      </p>
                      @endif
                    @endforeach


                  </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="message-input-container">
  <form action="" method="post">
    @csrf
    <div class="form-group">
        <label>Message</lable>
        <input type="text" name="message" class="form-control">
    </div>
    <div class="form-group">

        <button type="submit" class="btn btn-primary">Send message</button>
    </div>
  </form>
</div>
@endsection
@section('scripts')
<script>
const messaging = firebase.messaging();

messaging.usePublicVapidKey('BBJdHr_G_fL9BBozfMSvB32dDJpcQpd540aj8RUS7yElEj_AZWUK9QriVDdUdApjEbNg2rthek91pGynN2RdhEs');

 function sendTokenToServer(fcm_token){
   const user_id = '{{Auth::user()->id}}';
   // console.log($user_id);
  axios.post('/api/save-token',{
    fcm_token , user_id}).then(res => {
      console.log(res);
    });
}
function retrieveToken(){
  messaging.getToken().then((currentToken) => {
    if (currentToken) {
      // Send the token to your server and update the UI if necessary
      console.log('Token received : ' + currentToken );
      sendTokenToServer(currentToken);
    } else {
       alert('you should allow Notifications! ');
      // Show permission request UI
      //console.log('No registration token available. Request permission to generate one.');
      // ...
    }
  }).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
    // ...
  });
}

retrieveToken();
// Get registration token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
messaging.onTokenRefresh(() => {
  retrieveToken();
});

messaging.onMessage((payload)=>{
  console.log('Message received .');
  console.log(payload);
  location.reload();
});
</script>
@endsection
