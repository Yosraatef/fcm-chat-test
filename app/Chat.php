<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  protected $table = 'chates';
  protected $fillable = [
      'sender_name', 'sender_id', 'message'
  ];
}
