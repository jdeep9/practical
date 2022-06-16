<?php

namespace App;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Get the hobbies associated with the user.
     */
    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function getHobbyId($data){
        return $this->where('name', $data['hobby_name'])->first();
    }

    public function getUserListByHobby($data) {

        $hobbies = $this->getHobbyId($data);

        return $this->find($hobbies->id)->user;
    }
}
