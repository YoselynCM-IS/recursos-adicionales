<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // ROLES
        \DB::table('roles')->insert([
            0 => [ 'role' => 'admin' ],
            1 => [ 'role' => 'teacher' ],
            2 => [ 'role' => 'student' ],
        ]);

        // USUARIO PRUEBA
        \DB::table('users')->insert([
            0 => [ 
                'role_id'   => 1, 
                'codigo'    => 'CODADMIN',
                'password'  => bcrypt('CODPRUEBA'),
                'name'      => 'YOS ADMIN',
                'email'     => 'yosadmin@gmail.com',
            ]
        ]);

        // TIPOS
        \DB::table('tipos')->insert([
            0 => [ 'tipo' => 'común' ],
            1 => [ 'tipo' => 'inglés' ]
        ]);
    }
}
