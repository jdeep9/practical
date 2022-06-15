<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use App\Role;
use App\Hobby;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','password','first_name','last_name','phone','image','status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the hobbies associated with the user.
     */
    // public function userHobbies(){
    //     return $this->hasMany(Hobby::class,'user_id');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response $user
    **/
    public function createUser($data) {
        $user =  $this->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' =>  Hash::make($data['password']),
            'image' => $data['image'],
            'status' => $data['status'],
        ]);
        // dd($user);

        $role = Role::findByName('User');
        $user->syncRoles($role);

        return $user;
    }

    /**
     * Update one record of the resource.
     *
     * @param  \Illuminate\Http\Request  $data, $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser($data, $id)
    {
        // dd($data);
        return $this->where('id', $id)->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'image' => $data['image'],
            'status' => $data['status'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUser($id) {
        return $this->where('id', $id)->delete();
    }
}
