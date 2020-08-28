<?php

use Illuminate\Database\Seeder;

class EmpresasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->insert([
            'rut' => '76747632-9',
            'razon_social' => 'Informatica Fernando Romero Rodríguez E.I.R.L.',
            'correo' => 'fernando.romero20@gmail.com',
            'rubro' => 'Servicios Integrales de Informatica',
            'ciudad' => 'San Carlos',
            'representante_legal' => 'Fernando Romero R.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tipos_vehiculos')->insert([
            'id' => 1,
            'nombre' => 'Autos, Camionetas y 4x4',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('vehiculos')->insert([
            'id' => 1,
            'patente' => 'BRBF19',
            'marca' => 'Nissan',
            'modelo' => 'Navara 2008 Azul',
            'nombre_propietario' => 'Fernando',
            'fono_propietario' => '953333094',
            'tipo_vehiculo' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
