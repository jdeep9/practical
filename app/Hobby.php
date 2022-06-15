<?php

namespace App;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    protected $fillable = [
        'user_id','name'
    ];

    /**
     * Get the hobbies associated with the user.
     */
    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function getUserListByHobby($data) {
        $hobbies = $this->where('name', $data['hobby_name'])->first();

        return $this->find($hobbies->id)->user;
    }
}
