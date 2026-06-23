<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObraSocialSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('obras_sociales')->insert([
            [
                'nombre' => 'I.A.S.E.P.',
                'cuit' => '30-54678912-3',
                'fecha_convenio' => '2024-01-15',
                'fecha_vencimiento_convenio' => '2025-12-31',
                'codigo_validacion' => 'IASEP2024',
            ],
            [
                'nombre' => 'Sancor Salud',
                'cuit' => '30-71123456-7',
                'fecha_convenio' => '2024-03-01',
                'fecha_vencimiento_convenio' => '2026-02-28',
                'codigo_validacion' => 'SANCOR01',
            ],
            [
                'nombre' => 'UOCRA',
                'cuit' => '30-65321487-9',
                'fecha_convenio' => '2024-06-01',
                'fecha_vencimiento_convenio' => '2025-12-31',
                'codigo_validacion' => 'UOCRA24',
            ],
            [
                'nombre' => 'Galeno ART',
                'cuit' => '30-50789123-4',
                'fecha_convenio' => '2024-04-10',
                'fecha_vencimiento_convenio' => '2026-04-10',
                'codigo_validacion' => 'GALENO01',
            ],
            [
                'nombre' => 'Swiss Medical',
                'cuit' => '30-69325874-1',
                'fecha_convenio' => '2024-02-15',
                'fecha_vencimiento_convenio' => '2027-02-15',
                'codigo_validacion' => 'SWISSMD',
            ],
            [
                'nombre' => 'OSDE',
                'cuit' => '30-54612389-5',
                'fecha_convenio' => '2024-01-01',
                'fecha_vencimiento_convenio' => '2028-12-31',
                'codigo_validacion' => 'OSDE210',
            ],
            [
                'nombre' => 'Unión Personal',
                'cuit' => '30-58963214-7',
                'fecha_convenio' => '2024-05-20',
                'fecha_vencimiento_convenio' => '2026-05-20',
                'codigo_validacion' => 'UPERSONAL',
            ],
            [
                'nombre' => 'Medicus',
                'cuit' => '30-61234897-2',
                'fecha_convenio' => '2024-03-15',
                'fecha_vencimiento_convenio' => '2027-03-15',
                'codigo_validacion' => 'MEDICUS01',
            ],
            [
                'nombre' => 'OSPACA',
                'cuit' => '30-54789632-1',
                'fecha_convenio' => '2024-07-01',
                'fecha_vencimiento_convenio' => '2025-12-31',
                'codigo_validacion' => 'OSPACA24',
            ],
            [
                'nombre' => 'PAMI',
                'cuit' => '30-50000001-2',
                'fecha_convenio' => '2024-01-01',
                'fecha_vencimiento_convenio' => '2026-12-31',
                'codigo_validacion' => 'PAMI2024',
            ],
            [
                'nombre' => 'OSECAC',
                'cuit' => '30-50123456-7',
                'fecha_convenio' => '2024-04-01',
                'fecha_vencimiento_convenio' => '2025-12-31',
                'codigo_validacion' => 'OSECAC01',
            ],
            [
                'nombre' => 'OSPE',
                'cuit' => '30-54781236-9',
                'fecha_convenio' => '2024-08-15',
                'fecha_vencimiento_convenio' => '2026-08-15',
                'codigo_validacion' => 'OSPE2024',
            ],
            [
                'nombre' => 'Salud Integral',
                'cuit' => '30-62547891-4',
                'fecha_convenio' => '2024-06-01',
                'fecha_vencimiento_convenio' => '2027-05-31',
                'codigo_validacion' => 'SINTEGRAL',
            ],
            [
                'nombre' => 'Federada Salud',
                'cuit' => '30-50321478-5',
                'fecha_convenio' => '2024-09-01',
                'fecha_vencimiento_convenio' => '2026-09-01',
                'codigo_validacion' => 'FEDERADA',
            ],
        ]);
    }
}
