<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HobbyUser extends Model
{
    protected $fillable = [
        'user_id','hobby_id'
    ];

    protected $table = 'hobby_user';

    public function addHobbyToUser($userId, $hobbyId) {
        return $this->create([
            'user_id' => $userId,
            'hobby_id' => $hobbyId
        ]);
    }
}
