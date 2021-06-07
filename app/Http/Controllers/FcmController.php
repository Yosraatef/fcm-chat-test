<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
class FcmController extends Controller
{
    public function index(Request $request){
      $input = $request->all();
      $fcm_token = $input['fcm_token'];
      $user_id = $input['user_id'];
      $user = User::findOrFail($user_id);
      $user->fcm_token = $fcm_token;
      $user->save();
      return response()->json([
        'success' => true ,
        'message' => 'user token updated successfully',
    ]);
    }
}
