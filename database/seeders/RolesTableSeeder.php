<?php
// database/seeders/RolesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'Admin', 'description' => 'Administrator role']);
        Role::create(['name' => 'User', 'description' => 'Regular user role']);
        Role::create(['name' => 'Hotel Manager', 'description' => 'Hotel manager role']);
    }
}
