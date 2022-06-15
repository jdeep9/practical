<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $fillable = [];

    protected $guard_name = 'api';
}
