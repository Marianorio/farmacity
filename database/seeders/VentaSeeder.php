<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentaSeeder extends Seeder
{
    private function insertVenta(array $data): void
    {
        DB::table('ventas')->insert($data);
    }

    public function run(): void
    {
        // ============================================================
        // VENTAS - 40 registros con datos realistas
        // Se insertan en grupos que comparten las mismas columnas (PostgreSQL no permite
        // diferente cantidad de columnas en un multi-INSERT).
        // ============================================================

        // Grupo 1: Activas sin obra social (10 columnas, sin motivo_anulacion)
        $activasSinOS = [
            ['numero_cliente' => '00001', 'fecha' => '2024-10-05 09:15:00', 'subtotal' => 520.00, 'impuestos' => 109.20, 'descuento' => 0.00, 'total' => 629.20, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00002', 'fecha' => '2024-10-08 10:30:00', 'subtotal' => 380.00, 'impuestos' => 79.80, 'descuento' => 0.00, 'total' => 459.80, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00006', 'fecha' => '2024-11-03 09:30:00', 'subtotal' => 890.00, 'impuestos' => 186.90, 'descuento' => 0.00, 'total' => 1076.90, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00007', 'fecha' => '2024-11-05 16:15:00', 'subtotal' => 420.00, 'impuestos' => 88.20, 'descuento' => 0.00, 'total' => 508.20, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00009', 'fecha' => '2024-11-15 13:00:00', 'subtotal' => 7200.00, 'impuestos' => 1512.00, 'descuento' => 0.00, 'total' => 8712.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00012', 'fecha' => '2024-11-28 11:15:00', 'subtotal' => 750.00, 'impuestos' => 157.50, 'descuento' => 0.00, 'total' => 907.50, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00015', 'fecha' => '2024-12-08 09:30:00', 'subtotal' => 400.00, 'impuestos' => 84.00, 'descuento' => 0.00, 'total' => 484.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00005', 'fecha' => '2024-12-10 09:20:00', 'subtotal' => 1000.00, 'impuestos' => 210.00, 'descuento' => 0.00, 'total' => 1210.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00016', 'fecha' => '2024-12-14 11:30:00', 'subtotal' => 3800.00, 'impuestos' => 798.00, 'descuento' => 0.00, 'total' => 4598.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00019', 'fecha' => '2025-01-08 14:30:00', 'subtotal' => 520.00, 'impuestos' => 109.20, 'descuento' => 0.00, 'total' => 629.20, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00023', 'fecha' => '2025-01-22 08:00:00', 'subtotal' => 1100.00, 'impuestos' => 231.00, 'descuento' => 0.00, 'total' => 1331.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00025', 'fecha' => '2025-02-02 09:30:00', 'subtotal' => 680.00, 'impuestos' => 142.80, 'descuento' => 0.00, 'total' => 822.80, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00028', 'fecha' => '2025-02-12 16:45:00', 'subtotal' => 420.00, 'impuestos' => 88.20, 'descuento' => 0.00, 'total' => 508.20, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00029', 'fecha' => '2025-02-15 10:30:00', 'subtotal' => 8500.00, 'impuestos' => 1785.00, 'descuento' => 0.00, 'total' => 10285.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00031', 'fecha' => '2025-03-03 09:00:00', 'subtotal' => 1400.00, 'impuestos' => 294.00, 'descuento' => 0.00, 'total' => 1694.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00033', 'fecha' => '2025-03-10 11:45:00', 'subtotal' => 950.00, 'impuestos' => 199.50, 'descuento' => 0.00, 'total' => 1149.50, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00035', 'fecha' => '2025-03-20 08:30:00', 'subtotal' => 580.00, 'impuestos' => 121.80, 'descuento' => 0.00, 'total' => 701.80, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ACTIVA'],
        ];
        $this->insertVenta($activasSinOS);

        // Grupo 2: Activas con obra social (mismas 10 columnas)
        $activasConOS = [
            ['numero_cliente' => '00003', 'fecha' => '2024-10-12 14:45:00', 'subtotal' => 1500.00, 'impuestos' => 315.00, 'descuento' => 180.00, 'total' => 1635.00, 'id_usuario' => 1, 'id_obra_social' => 6, 'codigo_validacion' => 'OSDE210', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00005', 'fecha' => '2024-10-22 11:00:00', 'subtotal' => 2300.00, 'impuestos' => 483.00, 'descuento' => 345.00, 'total' => 2438.00, 'id_usuario' => 1, 'id_obra_social' => 2, 'codigo_validacion' => 'SANCOR01', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00008', 'fecha' => '2024-11-10 10:45:00', 'subtotal' => 3800.00, 'impuestos' => 798.00, 'descuento' => 950.00, 'total' => 3648.00, 'id_usuario' => 1, 'id_obra_social' => 10, 'codigo_validacion' => 'PAMI2024', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00011', 'fecha' => '2024-11-22 17:30:00', 'subtotal' => 1400.00, 'impuestos' => 294.00, 'descuento' => 210.00, 'total' => 1484.00, 'id_usuario' => 1, 'id_obra_social' => 7, 'codigo_validacion' => 'UPERSONAL', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00013', 'fecha' => '2024-12-01 08:45:00', 'subtotal' => 1600.00, 'impuestos' => 336.00, 'descuento' => 400.00, 'total' => 1536.00, 'id_usuario' => 1, 'id_obra_social' => 3, 'codigo_validacion' => 'UOCRA24', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00014', 'fecha' => '2024-12-07 15:20:00', 'subtotal' => 2500.00, 'impuestos' => 525.00, 'descuento' => 375.00, 'total' => 2650.00, 'id_usuario' => 1, 'id_obra_social' => 9, 'codigo_validacion' => 'OSPACA24', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00001', 'fecha' => '2024-12-10 08:41:00', 'subtotal' => 1600.00, 'impuestos' => 336.00, 'descuento' => 550.00, 'total' => 1386.00, 'id_usuario' => 1, 'id_obra_social' => 2, 'codigo_validacion' => 'SANCOR01', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00002', 'fecha' => '2024-12-10 09:07:00', 'subtotal' => 1000.00, 'impuestos' => 210.00, 'descuento' => 250.00, 'total' => 960.00, 'id_usuario' => 1, 'id_obra_social' => 2, 'codigo_validacion' => 'SANCOR01', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00004', 'fecha' => '2024-12-10 09:18:00', 'subtotal' => 1600.00, 'impuestos' => 336.00, 'descuento' => 550.00, 'total' => 1386.00, 'id_usuario' => 1, 'id_obra_social' => 1, 'codigo_validacion' => 'IASEP2024', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00006', 'fecha' => '2024-12-11 00:59:00', 'subtotal' => 2040.00, 'impuestos' => 428.40, 'descuento' => 100.00, 'total' => 1468.40, 'id_usuario' => 1, 'id_obra_social' => 1, 'codigo_validacion' => 'IASEP2024', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00017', 'fecha' => '2024-12-18 16:00:00', 'subtotal' => 1100.00, 'impuestos' => 231.00, 'descuento' => 165.00, 'total' => 1166.00, 'id_usuario' => 1, 'id_obra_social' => 5, 'codigo_validacion' => 'SWISSMD', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00018', 'fecha' => '2025-01-05 10:15:00', 'subtotal' => 750.00, 'impuestos' => 157.50, 'descuento' => 112.50, 'total' => 795.00, 'id_usuario' => 1, 'id_obra_social' => 8, 'codigo_validacion' => 'MEDICUS01', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00020', 'fecha' => '2025-01-12 09:00:00', 'subtotal' => 6200.00, 'impuestos' => 1302.00, 'descuento' => 1240.00, 'total' => 6262.00, 'id_usuario' => 1, 'id_obra_social' => 10, 'codigo_validacion' => 'PAMI2024', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00022', 'fecha' => '2025-01-18 16:30:00', 'subtotal' => 1800.00, 'impuestos' => 378.00, 'descuento' => 270.00, 'total' => 1908.00, 'id_usuario' => 1, 'id_obra_social' => 13, 'codigo_validacion' => 'SINTEGRAL', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00024', 'fecha' => '2025-01-25 13:15:00', 'subtotal' => 4200.00, 'impuestos' => 882.00, 'descuento' => 1050.00, 'total' => 4032.00, 'id_usuario' => 1, 'id_obra_social' => 11, 'codigo_validacion' => 'OSECAC01', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00026', 'fecha' => '2025-02-05 14:00:00', 'subtotal' => 2500.00, 'impuestos' => 525.00, 'descuento' => 500.00, 'total' => 2525.00, 'id_usuario' => 1, 'id_obra_social' => 4, 'codigo_validacion' => 'GALENO01', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00027', 'fecha' => '2025-02-08 11:00:00', 'subtotal' => 1300.00, 'impuestos' => 273.00, 'descuento' => 195.00, 'total' => 1378.00, 'id_usuario' => 1, 'id_obra_social' => 14, 'codigo_validacion' => 'FEDERADA', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00030', 'fecha' => '2025-02-20 08:15:00', 'subtotal' => 750.00, 'impuestos' => 157.50, 'descuento' => 112.50, 'total' => 795.00, 'id_usuario' => 1, 'id_obra_social' => 7, 'codigo_validacion' => 'UPERSONAL', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00032', 'fecha' => '2025-03-06 14:30:00', 'subtotal' => 3000.00, 'impuestos' => 630.00, 'descuento' => 750.00, 'total' => 2880.00, 'id_usuario' => 1, 'id_obra_social' => 3, 'codigo_validacion' => 'UOCRA24', 'estado' => 'ACTIVA'],
            ['numero_cliente' => '00034', 'fecha' => '2025-03-15 16:00:00', 'subtotal' => 2200.00, 'impuestos' => 462.00, 'descuento' => 440.00, 'total' => 2222.00, 'id_usuario' => 1, 'id_obra_social' => 12, 'codigo_validacion' => 'OSPE2024', 'estado' => 'ACTIVA'],
        ];
        $this->insertVenta($activasConOS);

        // Grupo 3: Anuladas (11 columnas, incluyen motivo_anulacion)
        $anuladas = [
            ['numero_cliente' => '00004', 'fecha' => '2024-10-18 08:20:00', 'subtotal' => 820.00, 'impuestos' => 172.20, 'descuento' => 0.00, 'total' => 992.20, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ANULADA', 'motivo_anulacion' => 'Cliente se arrepintió de la compra'],
            ['numero_cliente' => '00010', 'fecha' => '2024-11-20 09:00:00', 'subtotal' => 620.00, 'impuestos' => 130.20, 'descuento' => 93.00, 'total' => 657.20, 'id_usuario' => 1, 'id_obra_social' => 4, 'codigo_validacion' => 'GALENO01', 'estado' => 'ANULADA', 'motivo_anulacion' => 'Error en el descuento aplicado'],
            ['numero_cliente' => '00001', 'fecha' => '2024-12-04 10:00:00', 'subtotal' => 1000.00, 'impuestos' => 210.00, 'descuento' => 500.00, 'total' => 710.00, 'id_usuario' => 1, 'id_obra_social' => 1, 'codigo_validacion' => 'IASEP2024', 'estado' => 'ANULADA', 'motivo_anulacion' => 'Mal cálculo del total'],
            ['numero_cliente' => '00003', 'fecha' => '2024-12-10 09:14:00', 'subtotal' => 1640.00, 'impuestos' => 344.40, 'descuento' => 550.00, 'total' => 1434.40, 'id_usuario' => 1, 'id_obra_social' => 2, 'codigo_validacion' => 'SANCOR01', 'estado' => 'ANULADA', 'motivo_anulacion' => 'Verificar si devuelve el producto'],
            ['numero_cliente' => '00021', 'fecha' => '2025-01-15 11:45:00', 'subtotal' => 900.00, 'impuestos' => 189.00, 'descuento' => 0.00, 'total' => 1089.00, 'id_usuario' => 1, 'id_obra_social' => null, 'codigo_validacion' => null, 'estado' => 'ANULADA', 'motivo_anulacion' => 'Producto dañado'],
        ];
        $this->insertVenta($anuladas);

        // ============================================================
        // DETALLE DE VENTAS - ~100 registros
        // ============================================================
        DB::table('detalle_ventas')->insert([
            // Venta 1 (Oct 5)
            ['id_venta' => 1, 'id_producto' => 1, 'cantidad' => 1, 'precio_unitario' => 520.00, 'descuento' => 0.00, 'precio_final' => 520.00, 'subtotal' => 520.00],

            // Venta 2 (Oct 8)
            ['id_venta' => 2, 'id_producto' => 2, 'cantidad' => 1, 'precio_unitario' => 380.00, 'descuento' => 0.00, 'precio_final' => 380.00, 'subtotal' => 380.00],

            // Venta 3 (Oct 12) - OSDE
            ['id_venta' => 3, 'id_producto' => 6, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 10.00, 'precio_final' => 900.00, 'subtotal' => 900.00],
            ['id_venta' => 3, 'id_producto' => 21, 'cantidad' => 1, 'precio_unitario' => 600.00, 'descuento' => 10.00, 'precio_final' => 540.00, 'subtotal' => 540.00],
            ['id_venta' => 3, 'id_producto' => 22, 'cantidad' => 1, 'precio_unitario' => 60.00, 'descuento' => 0.00, 'precio_final' => 60.00, 'subtotal' => 60.00],

            // Venta 4 (Oct 18) ANULADA
            ['id_venta' => 4, 'id_producto' => 19, 'cantidad' => 1, 'precio_unitario' => 700.00, 'descuento' => 0.00, 'precio_final' => 700.00, 'subtotal' => 700.00],
            ['id_venta' => 4, 'id_producto' => 23, 'cantidad' => 1, 'precio_unitario' => 120.00, 'descuento' => 0.00, 'precio_final' => 120.00, 'subtotal' => 120.00],

            // Venta 5 (Oct 22) - Sancor Salud
            ['id_venta' => 5, 'id_producto' => 3, 'cantidad' => 1, 'precio_unitario' => 890.00, 'descuento' => 15.00, 'precio_final' => 756.50, 'subtotal' => 756.50],
            ['id_venta' => 5, 'id_producto' => 4, 'cantidad' => 1, 'precio_unitario' => 750.00, 'descuento' => 15.00, 'precio_final' => 637.50, 'subtotal' => 637.50],
            ['id_venta' => 5, 'id_producto' => 9, 'cantidad' => 1, 'precio_unitario' => 950.00, 'descuento' => 15.00, 'precio_final' => 807.50, 'subtotal' => 807.50],

            // Venta 6 (Nov 3)
            ['id_venta' => 6, 'id_producto' => 3, 'cantidad' => 1, 'precio_unitario' => 890.00, 'descuento' => 0.00, 'precio_final' => 890.00, 'subtotal' => 890.00],

            // Venta 7 (Nov 5)
            ['id_venta' => 7, 'id_producto' => 11, 'cantidad' => 1, 'precio_unitario' => 420.00, 'descuento' => 0.00, 'precio_final' => 420.00, 'subtotal' => 420.00],

            // Venta 8 (Nov 10) - PAMI
            ['id_venta' => 8, 'id_producto' => 1, 'cantidad' => 2, 'precio_unitario' => 520.00, 'descuento' => 25.00, 'precio_final' => 390.00, 'subtotal' => 780.00],
            ['id_venta' => 8, 'id_producto' => 2, 'cantidad' => 2, 'precio_unitario' => 380.00, 'descuento' => 25.00, 'precio_final' => 285.00, 'subtotal' => 570.00],
            ['id_venta' => 8, 'id_producto' => 4, 'cantidad' => 1, 'precio_unitario' => 750.00, 'descuento' => 25.00, 'precio_final' => 562.50, 'subtotal' => 562.50],
            ['id_venta' => 8, 'id_producto' => 5, 'cantidad' => 2, 'precio_unitario' => 620.00, 'descuento' => 25.00, 'precio_final' => 465.00, 'subtotal' => 930.00],
            ['id_venta' => 8, 'id_producto' => 22, 'cantidad' => 3, 'precio_unitario' => 680.00, 'descuento' => 25.00, 'precio_final' => 510.00, 'subtotal' => 1530.00],

            // Venta 9 (Nov 15)
            ['id_venta' => 9, 'id_producto' => 17, 'cantidad' => 1, 'precio_unitario' => 7200.00, 'descuento' => 0.00, 'precio_final' => 7200.00, 'subtotal' => 7200.00],

            // Venta 10 (Nov 20) ANULADA - Galeno
            ['id_venta' => 10, 'id_producto' => 3, 'cantidad' => 1, 'precio_unitario' => 890.00, 'descuento' => 15.00, 'precio_final' => 756.50, 'subtotal' => 756.50],
            ['id_venta' => 10, 'id_producto' => 7, 'cantidad' => 1, 'precio_unitario' => 2500.00, 'descuento' => 15.00, 'precio_final' => 2125.00, 'subtotal' => 2125.00],

            // Venta 11 (Nov 22) - Union Personal
            ['id_venta' => 11, 'id_producto' => 8, 'cantidad' => 1, 'precio_unitario' => 3800.00, 'descuento' => 15.00, 'precio_final' => 3230.00, 'subtotal' => 3230.00],

            // Venta 12 (Nov 28)
            ['id_venta' => 12, 'id_producto' => 19, 'cantidad' => 1, 'precio_unitario' => 700.00, 'descuento' => 0.00, 'precio_final' => 700.00, 'subtotal' => 700.00],

            // Venta 13 (Dic 1) - UOCRA
            ['id_venta' => 13, 'id_producto' => 4, 'cantidad' => 1, 'precio_unitario' => 750.00, 'descuento' => 25.00, 'precio_final' => 562.50, 'subtotal' => 562.50],
            ['id_venta' => 13, 'id_producto' => 7, 'cantidad' => 1, 'precio_unitario' => 2500.00, 'descuento' => 25.00, 'precio_final' => 1875.00, 'subtotal' => 1875.00],
            ['id_venta' => 13, 'id_producto' => 14, 'cantidad' => 1, 'precio_unitario' => 980.00, 'descuento' => 0.00, 'precio_final' => 980.00, 'subtotal' => 980.00],

            // Venta 14 (Dic 4) - IASEP ANULADA
            ['id_venta' => 14, 'id_producto' => 9, 'cantidad' => 1, 'precio_unitario' => 950.00, 'descuento' => 50.00, 'precio_final' => 475.00, 'subtotal' => 475.00],
            ['id_venta' => 14, 'id_producto' => 21, 'cantidad' => 1, 'precio_unitario' => 1100.00, 'descuento' => 50.00, 'precio_final' => 550.00, 'subtotal' => 550.00],

            // Venta 15 (Dic 7) - OSPACA
            ['id_venta' => 15, 'id_producto' => 1, 'cantidad' => 1, 'precio_unitario' => 520.00, 'descuento' => 15.00, 'precio_final' => 442.00, 'subtotal' => 442.00],
            ['id_venta' => 15, 'id_producto' => 2, 'cantidad' => 2, 'precio_unitario' => 380.00, 'descuento' => 15.00, 'precio_final' => 323.00, 'subtotal' => 646.00],
            ['id_venta' => 15, 'id_producto' => 3, 'cantidad' => 1, 'precio_unitario' => 890.00, 'descuento' => 15.00, 'precio_final' => 756.50, 'subtotal' => 756.50],
            ['id_venta' => 15, 'id_producto' => 21, 'cantidad' => 1, 'precio_unitario' => 1100.00, 'descuento' => 15.00, 'precio_final' => 935.00, 'subtotal' => 935.00],

            // Venta 16 (Dic 8)
            ['id_venta' => 16, 'id_producto' => 24, 'cantidad' => 1, 'precio_unitario' => 400.00, 'descuento' => 0.00, 'precio_final' => 400.00, 'subtotal' => 400.00],

            // Venta 17 (Dic 10) - Sancor Salud
            ['id_venta' => 17, 'id_producto' => 7, 'cantidad' => 1, 'precio_unitario' => 600.00, 'descuento' => 50.00, 'precio_final' => 300.00, 'subtotal' => 300.00],
            ['id_venta' => 17, 'id_producto' => 8, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 25.00, 'precio_final' => 750.00, 'subtotal' => 750.00],

            // Venta 18 (Dic 10) - Sancor Salud
            ['id_venta' => 18, 'id_producto' => 8, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 25.00, 'precio_final' => 750.00, 'subtotal' => 750.00],

            // Venta 19 (Dic 10) - Sancor ANULADA
            ['id_venta' => 19, 'id_producto' => 7, 'cantidad' => 1, 'precio_unitario' => 600.00, 'descuento' => 50.00, 'precio_final' => 300.00, 'subtotal' => 300.00],
            ['id_venta' => 19, 'id_producto' => 10, 'cantidad' => 1, 'precio_unitario' => 40.00, 'descuento' => 0.00, 'precio_final' => 40.00, 'subtotal' => 40.00],
            ['id_venta' => 19, 'id_producto' => 8, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 25.00, 'precio_final' => 750.00, 'subtotal' => 750.00],

            // Venta 20 (Dic 10) - IASEP
            ['id_venta' => 20, 'id_producto' => 7, 'cantidad' => 1, 'precio_unitario' => 600.00, 'descuento' => 30.00, 'precio_final' => 420.00, 'subtotal' => 420.00],
            ['id_venta' => 20, 'id_producto' => 8, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 50.00, 'precio_final' => 500.00, 'subtotal' => 500.00],

            // Venta 21 (Dic 10)
            ['id_venta' => 21, 'id_producto' => 8, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 0.00, 'precio_final' => 1000.00, 'subtotal' => 1000.00],

            // Venta 22 (Dic 11) - IASEP
            ['id_venta' => 22, 'id_producto' => 10, 'cantidad' => 1, 'precio_unitario' => 40.00, 'descuento' => 0.00, 'precio_final' => 40.00, 'subtotal' => 40.00],
            ['id_venta' => 22, 'id_producto' => 8, 'cantidad' => 2, 'precio_unitario' => 1000.00, 'descuento' => 50.00, 'precio_final' => 500.00, 'subtotal' => 1000.00],

            // Venta 23 (Dic 14)
            ['id_venta' => 23, 'id_producto' => 18, 'cantidad' => 1, 'precio_unitario' => 3800.00, 'descuento' => 0.00, 'precio_final' => 3800.00, 'subtotal' => 3800.00],

            // Venta 24 (Dic 18) - Swiss Medical
            ['id_venta' => 24, 'id_producto' => 27, 'cantidad' => 1, 'precio_unitario' => 1100.00, 'descuento' => 15.00, 'precio_final' => 935.00, 'subtotal' => 935.00],

            // Venta 25 (Ene 5) - Medicus
            ['id_venta' => 25, 'id_producto' => 19, 'cantidad' => 1, 'precio_unitario' => 700.00, 'descuento' => 15.00, 'precio_final' => 595.00, 'subtotal' => 595.00],

            // Venta 26 (Ene 8)
            ['id_venta' => 26, 'id_producto' => 1, 'cantidad' => 1, 'precio_unitario' => 520.00, 'descuento' => 0.00, 'precio_final' => 520.00, 'subtotal' => 520.00],

            // Venta 27 (Ene 12) - PAMI
            ['id_venta' => 27, 'id_producto' => 5, 'cantidad' => 5, 'precio_unitario' => 620.00, 'descuento' => 20.00, 'precio_final' => 496.00, 'subtotal' => 2480.00],
            ['id_venta' => 27, 'id_producto' => 4, 'cantidad' => 3, 'precio_unitario' => 750.00, 'descuento' => 20.00, 'precio_final' => 600.00, 'subtotal' => 1800.00],

            // Venta 28 (Ene 15) ANULADA
            ['id_venta' => 28, 'id_producto' => 29, 'cantidad' => 1, 'precio_unitario' => 900.00, 'descuento' => 0.00, 'precio_final' => 900.00, 'subtotal' => 900.00],

            // Venta 29 (Ene 18) - Salud Integral
            ['id_venta' => 29, 'id_producto' => 2, 'cantidad' => 2, 'precio_unitario' => 380.00, 'descuento' => 15.00, 'precio_final' => 323.00, 'subtotal' => 646.00],
            ['id_venta' => 29, 'id_producto' => 21, 'cantidad' => 1, 'precio_unitario' => 1100.00, 'descuento' => 15.00, 'precio_final' => 935.00, 'subtotal' => 935.00],

            // Venta 30 (Ene 22)
            ['id_venta' => 30, 'id_producto' => 30, 'cantidad' => 1, 'precio_unitario' => 1100.00, 'descuento' => 0.00, 'precio_final' => 1100.00, 'subtotal' => 1100.00],

            // Venta 31 (Ene 25) - OSECAC
            ['id_venta' => 31, 'id_producto' => 12, 'cantidad' => 2, 'precio_unitario' => 750.00, 'descuento' => 25.00, 'precio_final' => 562.50, 'subtotal' => 1125.00],
            ['id_venta' => 31, 'id_producto' => 13, 'cantidad' => 3, 'precio_unitario' => 290.00, 'descuento' => 25.00, 'precio_final' => 217.50, 'subtotal' => 652.50],
            ['id_venta' => 31, 'id_producto' => 6, 'cantidad' => 1, 'precio_unitario' => 1000.00, 'descuento' => 0.00, 'precio_final' => 1000.00, 'subtotal' => 1000.00],

            // Venta 32 (Feb 2)
            ['id_venta' => 32, 'id_producto' => 24, 'cantidad' => 1, 'precio_unitario' => 400.00, 'descuento' => 0.00, 'precio_final' => 400.00, 'subtotal' => 400.00],

            // Venta 33 (Feb 5) - Galeno
            ['id_venta' => 33, 'id_producto' => 5, 'cantidad' => 2, 'precio_unitario' => 620.00, 'descuento' => 20.00, 'precio_final' => 496.00, 'subtotal' => 992.00],
            ['id_venta' => 33, 'id_producto' => 3, 'cantidad' => 1, 'precio_unitario' => 890.00, 'descuento' => 20.00, 'precio_final' => 712.00, 'subtotal' => 712.00],

            // Venta 34 (Feb 8) - Federada
            ['id_venta' => 34, 'id_producto' => 7, 'cantidad' => 2, 'precio_unitario' => 600.00, 'descuento' => 15.00, 'precio_final' => 510.00, 'subtotal' => 1020.00],
            ['id_venta' => 34, 'id_producto' => 25, 'cantidad' => 1, 'precio_unitario' => 480.00, 'descuento' => 15.00, 'precio_final' => 408.00, 'subtotal' => 408.00],

            // Venta 35 (Feb 12)
            ['id_venta' => 35, 'id_producto' => 10, 'cantidad' => 1, 'precio_unitario' => 40.00, 'descuento' => 0.00, 'precio_final' => 40.00, 'subtotal' => 40.00],
            ['id_venta' => 35, 'id_producto' => 11, 'cantidad' => 1, 'precio_unitario' => 420.00, 'descuento' => 0.00, 'precio_final' => 420.00, 'subtotal' => 420.00],

            // Venta 36 (Feb 15)
            ['id_venta' => 36, 'id_producto' => 18, 'cantidad' => 1, 'precio_unitario' => 8500.00, 'descuento' => 0.00, 'precio_final' => 8500.00, 'subtotal' => 8500.00],

            // Venta 37 (Feb 20) - Union Personal
            ['id_venta' => 37, 'id_producto' => 21, 'cantidad' => 1, 'precio_unitario' => 900.00, 'descuento' => 15.00, 'precio_final' => 765.00, 'subtotal' => 765.00],

            // Venta 38 (Mar 3)
            ['id_venta' => 38, 'id_producto' => 28, 'cantidad' => 1, 'precio_unitario' => 1400.00, 'descuento' => 0.00, 'precio_final' => 1400.00, 'subtotal' => 1400.00],

            // Venta 39 (Mar 6) - UOCRA
            ['id_venta' => 39, 'id_producto' => 26, 'cantidad' => 2, 'precio_unitario' => 3000.00, 'descuento' => 25.00, 'precio_final' => 2250.00, 'subtotal' => 4500.00],
            ['id_venta' => 39, 'id_producto' => 19, 'cantidad' => 1, 'precio_unitario' => 700.00, 'descuento' => 25.00, 'precio_final' => 525.00, 'subtotal' => 525.00],

            // Venta 40 (Mar 10)
            ['id_venta' => 40, 'id_producto' => 29, 'cantidad' => 1, 'precio_unitario' => 950.00, 'descuento' => 0.00, 'precio_final' => 950.00, 'subtotal' => 950.00],
        ]);

        // ============================================================
        // RELACIONES PRODUCTOS - OBRAS SOCIALES
        // ============================================================
        DB::table('productos_obras_sociales')->insert([
            // IASEP - descuentos
            ['id_producto' => 1, 'id_obra_social' => 1, 'descuento' => 30.00],
            ['id_producto' => 2, 'id_obra_social' => 1, 'descuento' => 30.00],
            ['id_producto' => 4, 'id_obra_social' => 1, 'descuento' => 40.00],
            ['id_producto' => 5, 'id_obra_social' => 1, 'descuento' => 30.00],
            ['id_producto' => 7, 'id_obra_social' => 1, 'descuento' => 50.00],
            ['id_producto' => 8, 'id_obra_social' => 1, 'descuento' => 50.00],

            // Sancor Salud
            ['id_producto' => 3, 'id_obra_social' => 2, 'descuento' => 15.00],
            ['id_producto' => 4, 'id_obra_social' => 2, 'descuento' => 15.00],
            ['id_producto' => 7, 'id_obra_social' => 2, 'descuento' => 50.00],
            ['id_producto' => 8, 'id_obra_social' => 2, 'descuento' => 25.00],
            ['id_producto' => 9, 'id_obra_social' => 2, 'descuento' => 15.00],
            ['id_producto' => 15, 'id_obra_social' => 2, 'descuento' => 10.00],

            // UOCRA
            ['id_producto' => 1, 'id_obra_social' => 3, 'descuento' => 25.00],
            ['id_producto' => 4, 'id_obra_social' => 3, 'descuento' => 25.00],
            ['id_producto' => 7, 'id_obra_social' => 3, 'descuento' => 25.00],
            ['id_producto' => 19, 'id_obra_social' => 3, 'descuento' => 20.00],

            // Galeno ART
            ['id_producto' => 3, 'id_obra_social' => 4, 'descuento' => 20.00],
            ['id_producto' => 5, 'id_obra_social' => 4, 'descuento' => 20.00],
            ['id_producto' => 7, 'id_obra_social' => 4, 'descuento' => 20.00],

            // Swiss Medical
            ['id_producto' => 6, 'id_obra_social' => 5, 'descuento' => 15.00],
            ['id_producto' => 8, 'id_obra_social' => 5, 'descuento' => 10.00],
            ['id_producto' => 23, 'id_obra_social' => 5, 'descuento' => 20.00],

            // OSDE
            ['id_producto' => 1, 'id_obra_social' => 6, 'descuento' => 10.00],
            ['id_producto' => 6, 'id_obra_social' => 6, 'descuento' => 10.00],
            ['id_producto' => 7, 'id_obra_social' => 6, 'descuento' => 10.00],
            ['id_producto' => 21, 'id_obra_social' => 6, 'descuento' => 10.00],

            // Unión Personal
            ['id_producto' => 4, 'id_obra_social' => 7, 'descuento' => 15.00],
            ['id_producto' => 8, 'id_obra_social' => 7, 'descuento' => 15.00],
            ['id_producto' => 19, 'id_obra_social' => 7, 'descuento' => 15.00],

            // Medicus
            ['id_producto' => 2, 'id_obra_social' => 8, 'descuento' => 15.00],
            ['id_producto' => 19, 'id_obra_social' => 8, 'descuento' => 15.00],
            ['id_producto' => 24, 'id_obra_social' => 8, 'descuento' => 10.00],

            // OSPACA
            ['id_producto' => 1, 'id_obra_social' => 9, 'descuento' => 15.00],
            ['id_producto' => 2, 'id_obra_social' => 9, 'descuento' => 15.00],
            ['id_producto' => 3, 'id_obra_social' => 9, 'descuento' => 15.00],
            ['id_producto' => 21, 'id_obra_social' => 9, 'descuento' => 15.00],

            // PAMI
            ['id_producto' => 1, 'id_obra_social' => 10, 'descuento' => 20.00],
            ['id_producto' => 2, 'id_obra_social' => 10, 'descuento' => 20.00],
            ['id_producto' => 4, 'id_obra_social' => 10, 'descuento' => 25.00],
            ['id_producto' => 5, 'id_obra_social' => 10, 'descuento' => 20.00],
            ['id_producto' => 22, 'id_obra_social' => 10, 'descuento' => 25.00],

            // OSECAC
            ['id_producto' => 6, 'id_obra_social' => 11, 'descuento' => 25.00],
            ['id_producto' => 12, 'id_obra_social' => 11, 'descuento' => 25.00],
            ['id_producto' => 13, 'id_obra_social' => 11, 'descuento' => 25.00],

            // OSPE
            ['id_producto' => 5, 'id_obra_social' => 12, 'descuento' => 20.00],
            ['id_producto' => 7, 'id_obra_social' => 12, 'descuento' => 20.00],

            // Salud Integral
            ['id_producto' => 2, 'id_obra_social' => 13, 'descuento' => 15.00],
            ['id_producto' => 21, 'id_obra_social' => 13, 'descuento' => 15.00],

            // Federada Salud
            ['id_producto' => 7, 'id_obra_social' => 14, 'descuento' => 15.00],
            ['id_producto' => 16, 'id_obra_social' => 14, 'descuento' => 10.00],
            ['id_producto' => 25, 'id_obra_social' => 14, 'descuento' => 15.00],
        ]);
    }
}
