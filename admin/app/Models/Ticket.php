<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';


    public function department(){
        return $this->belongsTo(TicketDepartment::class,'department_id');
    }

    public function priority(){
        return $this->belongsTo(TicketPriority::class,'priority_id');
    }

    public function seller(){
        return $this->hasOne(Admins::class,'id','user_id');
    }

    public function customer(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function admins($id){
        $ticket = Ticket::find($id);
        return Admins::whereIn('id', explode(',', $ticket->admin_ids))->get();
    }

    public function isImage($image){
        $extension = pathinfo($image, PATHINFO_EXTENSION);
        $imgExtArr = ['jpg', 'jpeg', 'png','JPG','JPEG','PNG', 'webp'];
        if(in_array($extension, $imgExtArr)){
            return true;
        }
        return false;
    }

}
