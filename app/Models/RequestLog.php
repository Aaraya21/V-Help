<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = ['service_request_id', 'user_id', 'status', 'note'];

    public function user() { return $this->belongsTo(User::class); }
}
