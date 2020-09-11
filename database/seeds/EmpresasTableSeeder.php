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
            'rut' => '76783908-1',
            'razon_social' => 'SAMMI LTDA.',
            'correo' => 'guillermo.sammi@gmail.com',
            'rubro' => 'Servicios Automotrices y Otros',
            'ciudad' => 'San Carlos',
            'representante_legal' => 'Guillermo Martinez',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tipos_vehiculos')->insert([
            'id' => 1,
            'nombre' => 'Autos, Camionetas y 4x4',
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }
}
