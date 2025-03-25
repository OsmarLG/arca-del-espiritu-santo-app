<?php

namespace App\Livewire\Ministerios\Misericordia\V1;

use App\Models\Categoria;
use App\Models\Donacion;
use App\Models\Familia;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public int $totalProductos = 0;
    public int $totalCategorias = 0;
    public int $totalDonaciones = 0;

    public $donacionesRecientes = [];

    public bool $selectorModal = false;
    public bool $modalUsuario = false;
    public bool $modalFamilia = false;

    public ?int $donante_id = null;
    public array $donaciones = [];

    public function openSelectorModal()
    {
        $this->reset(['donante_id', 'donaciones', 'modalUsuario', 'modalFamilia']);
        $this->selectorModal = true;
    }

    public function seleccionarTipo($tipo)
    {
        $this->selectorModal = false;
        $this->modalUsuario = $tipo === 'user';
        $this->modalFamilia = $tipo === 'familia';
    }

    public function addProducto()
    {
        $this->donaciones[] = ['producto_id' => Product::first()->id, 'cantidad' => 1];
    }

    public function removeProducto($index)
    {
        unset($this->donaciones[$index]);
        $this->donaciones = array_values($this->donaciones);
    }

    public function submitDonacion($tipo)
    {
        $this->validate([
            'donante_id' => 'required|integer',
            'donaciones.*.producto_id' => 'required|exists:products,id',
            'donaciones.*.cantidad' => 'required|integer|min:1',
        ]);

        $donableType = $tipo === 'user' ? User::class : Familia::class;

        foreach ($this->donaciones as $item) {
            Donacion::create([
                'donable_type' => $donableType,
                'donable_id' => $this->donante_id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
            ]);

            Product::where('id', $item['producto_id'])->increment('stock', $item['cantidad']);
        }

        $this->success('Donación registrada correctamente.');

        $this->reset(['modalUsuario', 'modalFamilia', 'donante_id', 'donaciones']);
        $this->donaciones = [['producto_id' => null, 'cantidad' => 1]];
    }

    public function cancelarDonacion($donacionId)
    {
        $donacion = Donacion::findOrFail($donacionId);

        // Restar del stock solo si el producto existe
        if ($donacion->producto) {
            $donacion->producto->decrement('stock', $donacion->cantidad);
        }

        $donacion->delete();

        $this->success('Donación cancelada y stock actualizado.');
    }

    public function render()
    {
        $this->totalProductos = Product::count();
        $this->totalCategorias = Categoria::count();
        $this->totalDonaciones = Donacion::count();

        $this->donacionesRecientes = Donacion::with(['producto', 'donable'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.ministerios.misericordia.v1.index', [
            'productos' => Product::all(),
            'usuarios' => User::all(),
            'familias' => Familia::all(),
            'totalProductos' => $this->totalProductos,
            'totalCategorias' => $this->totalCategorias,
            'totalDonaciones' => $this->totalDonaciones,
            'donacionesRecientes' => $this->donacionesRecientes,
        ]);
    }
}
