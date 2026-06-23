<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('proveedores')->insert([
            [
                'nombre' => 'Drogueria Norte',
                'contacto' => 'Javier Romero',
                'direccion' => 'Fontana 236, Formosa Capital',
                'telefono' => '3704430959',
                'email' => 'Nortedrogueria@gmail.com',
                'informacion_adicional' => 'Principal distribuidora de medicamentos del norte argentino',
            ],
            [
                'nombre' => 'Drogueria Flores',
                'contacto' => 'Mauro Rios',
                'direccion' => 'Av Siempre Viva 742, Formosa',
                'telefono' => '3704432300',
                'email' => 'DFlores@gmail.com',
                'informacion_adicional' => 'Especializada en productos de higiene y cosmética',
            ],
            [
                'nombre' => 'DOVE S.R.L.',
                'contacto' => 'Luz Godoy',
                'direccion' => 'Napoleon Uriburu 250, Formosa',
                'telefono' => '3704607080',
                'email' => 'Dove@yahoo.com',
                'informacion_adicional' => 'Laboratorio multinacional de cuidado personal',
            ],
            [
                'nombre' => 'Drogueria Sur',
                'contacto' => 'Carlos Mendez',
                'direccion' => 'Calle Falsa 123, Ciudad, Provincia',
                'telefono' => '1155551234',
                'email' => 'DrogueriaSur@gmail.com',
                'informacion_adicional' => 'Distribuidora nacional con depósito en Buenos Aires',
            ],
            [
                'nombre' => 'Laboratorios Roemmers',
                'contacto' => 'Ana Laura Perez',
                'direccion' => 'Av. Del Libertador 6969, CABA',
                'telefono' => '1147896500',
                'email' => 'ventas@roemmers.com.ar',
                'informacion_adicional' => 'Laboratorio farmacéutico líder en Argentina',
            ],
            [
                'nombre' => 'Bayer S.A.',
                'contacto' => 'Martin Gonzalez',
                'direccion' => 'Carlos Pellegrini 1145, CABA',
                'telefono' => '1143198000',
                'email' => 'argentina@bayer.com',
                'informacion_adicional' => 'Multinacional alemana de fármacos y productos de consumo',
            ],
            [
                'nombre' => 'Elea Phoenix',
                'contacto' => 'Romina Acosta',
                'direccion' => 'Av. San Martin 3985, CABA',
                'telefono' => '1152785700',
                'email' => 'info@elea.com',
                'informacion_adicional' => 'Laboratorio nacional con amplio portafolio de medicamentos',
            ],
            [
                'nombre' => 'Raffo S.A.',
                'contacto' => 'Diego Martinez',
                'direccion' => 'Av. Rivadavia 5412, CABA',
                'telefono' => '1146538800',
                'email' => 'ventas@raffo.com.ar',
                'informacion_adicional' => 'Laboratorio argentino de especialidades medicinales',
            ],
            [
                'nombre' => 'Bagó S.A.',
                'contacto' => 'Patricia Lopez',
                'direccion' => 'Bernardo de Irigoyen 248, CABA',
                'telefono' => '1143447000',
                'email' => 'infobago@bago.com.ar',
                'informacion_adicional' => 'Grupo farmacéutico líder en Latinoamérica',
            ],
            [
                'nombre' => 'GSK Argentina',
                'contacto' => 'Fernando Ruiz',
                'direccion' => 'Av. Del Libertador 602, CABA',
                'telefono' => '1147785000',
                'email' => 'gsk.argentina@gsk.com',
                'informacion_adicional' => 'GlaxoSmithKline: medicamentos éticos y de consumo',
            ],
            [
                'nombre' => 'Distribuidora Farmacéutica del Litoral',
                'contacto' => 'Sofia Benitez',
                'direccion' => 'Belgrano 1580, Santa Fe',
                'telefono' => '3424556789',
                'email' => 'ventas@dflitoral.com.ar',
                'informacion_adicional' => 'Distribución mayorista en toda la región litoral',
            ],
            [
                'nombre' => 'Procter & Gamble Argentina',
                'contacto' => 'Lucia Fernandez',
                'direccion' => 'Av. Corrientes 2871, CABA',
                'telefono' => '1143258900',
                'email' => 'pg.argentina@pg.com',
                'informacion_adicional' => 'Bienes de consumo masivo, higiene y cuidado personal',
            ],
            [
                'nombre' => 'Unilever Argentina',
                'contacto' => 'Guillermo Torres',
                'direccion' => 'Av. Del Libertador 1850, CABA',
                'telefono' => '1147776500',
                'email' => 'contacto@unilever.com.ar',
                'informacion_adicional' => 'Productos de cuidado personal, hogar y alimentos',
            ],
            [
                'nombre' => 'Laboratorios Casasco',
                'contacto' => 'Claudia Sosa',
                'direccion' => 'Av. Callao 2120, CABA',
                'telefono' => '1149635800',
                'email' => 'info@casasco.com.ar',
                'informacion_adicional' => 'Laboratorio argentino de medicamentos genéricos',
            ],
        ]);
    }
}
