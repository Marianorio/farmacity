<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Medicamentos', 'descripcion' => 'Medicamentos con y sin receta, de venta libre y bajo receta'],
            ['nombre' => 'Higiene Personal', 'descripcion' => 'Productos para el cuidado e higiene personal'],
            ['nombre' => 'Cuidado de Niños', 'descripcion' => 'Productos infantiles, pañales, leches, y cuidado del bebé'],
            ['nombre' => 'Cosméticos', 'descripcion' => 'Maquillaje, labiales, sombras y productos de belleza'],
            ['nombre' => 'Perfumería', 'descripcion' => 'Perfumes, colonias y fragancias'],
            ['nombre' => 'Cuidado Facial', 'descripcion' => 'Cremas, limpiadores faciales, protectores solares'],
            ['nombre' => 'Cuidado Capilar', 'descripcion' => 'Shampoos, acondicionadores, tratcapilares'],
            ['nombre' => 'Vitaminas y Suplementos', 'descripcion' => 'Complejos vitamínicos, suplementos dietarios, minerales'],
            ['nombre' => 'Primeros Auxilios', 'descripcion' => 'Vendas, gasas, apósitos, alcohol, agua oxigenada'],
            ['nombre' => 'Salud Sexual', 'descripcion' => 'Preservativos, anticonceptivos, Tests de embarazo'],
            ['nombre' => 'Cuidado Bucal', 'descripcion' => 'Pastas dentales, cepillos, enjuagues bucales'],
            ['nombre' => 'Diabetes', 'descripcion' => 'Medicamentos, tiras reactivas, lancetas, insulinas'],
            ['nombre' => 'Dermocosmética', 'descripcion' => 'Productos dermatológicos medicados y de cuidado'],
            ['nombre' => 'Nutrición', 'descripcion' => 'Suplementos nutricionales, proteínas, barras energéticas'],
            ['nombre' => 'Otros', 'descripcion' => 'Productos varios no categorizados'],
        ]);
    }
}
