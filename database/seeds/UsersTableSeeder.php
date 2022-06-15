<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $superAdmin = User::create([
            'email' => 'superadmin@practical.com',
            'password' => bcrypt('Test@123'),
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone'=>'8980072914',
            'email_verified_at'=>null,
            'image'=>'image_2022_04_26T18_19_58_396Z.png',
            'status'=>'Active',
        ]);

        $superAdminRole = Role::findByName('Super Admin');
        $superAdmin->syncRoles($superAdminRole);
    }
}
