<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComments extends Model
{
    protected $table = 'ticket_comment';

    public function seller(){
        return $this->hasOne(Admins::class,'id','user_id');
    }

    public function customer(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function admin(){
    	return $this->hasOne(Admins::class,'id','admin_id');
    }

}
