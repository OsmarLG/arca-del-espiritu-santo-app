<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\CategoriaMisericordia;
use Illuminate\Database\Seeder;

class CategoriasMisericordiaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Comida',
            'Ropa',
            'Juguetes',
            'Artículos de higiene',
            'Calzado',
            'Libros',
            'Cobijas',
            'Medicinas',
            'Material Escolar',
        ];

        foreach ($categorias as $nombre) {
            Categoria::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
