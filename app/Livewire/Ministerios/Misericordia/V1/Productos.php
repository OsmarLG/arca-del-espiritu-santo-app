<?php

namespace App\Livewire\Ministerios\Misericordia\V1;

use App\Models\Categoria;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Productos extends Component
{
    use WithPagination, Toast, WithFileUploads;

    public string $search = '';
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];

    public array $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'nombre', 'label' => 'Nombre del Producto'],
        ['key' => 'descripcion', 'label' => 'Descripción'],
        ['key' => 'categoria_id', 'label' => 'Categoría'],
        ['key' => 'stock', 'label' => 'Stock'],
        ['key' => 'imagen', 'label' => 'Imagen'],
    ];

    public bool $createModal = false;
    public bool $editModal = false;
    public bool $confirmDeleteModal = false;

    public ?int $editing_id = null;
    public ?int $delete_id = null;
    public bool $continuarCreando = false;

    public string $nombre = '';
    public string $descripcion = '';
    public int $stock = 0;
    public ?int $categoria_id = null;
    public $newFotoGallery;
    public $newFotoCamera;
    public ?int $selected_product_id = null;
    public bool $updateFotoModal = false;

    public function openCreateModal()
    {
        $this->reset(['nombre', 'descripcion', 'stock', 'categoria_id', 'newFotoGallery', 'newFotoCamera']);
        $this->createModal = true;
    }

    public function create()
    {
        $this->validate([
            'nombre' => 'required|string|unique:products,nombre',
            'descripcion' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Product::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'stock' => $this->stock,
            'categoria_id' => $this->categoria_id,
        ]);

        $this->success('Producto creado con éxito.');

        if (!$this->continuarCreando) {
            $this->reset(['createModal', 'nombre', 'descripcion', 'stock', 'categoria_id']);
        } else {
            $this->reset(['nombre', 'descripcion', 'stock', 'categoria_id']);
        }
    }

    public function openEditModal($id)
    {
        $producto = Product::findOrFail($id);
        $this->editing_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->stock = $producto->stock;
        $this->categoria_id = $producto->categoria_id;
        $this->editModal = true;
    }

    public function update()
    {
        $this->validate([
            'nombre' => 'required|string|unique:products,nombre,' . $this->editing_id,
            'descripcion' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $producto = Product::findOrFail($this->editing_id);

        $producto->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'stock' => $this->stock,
            'categoria_id' => $this->categoria_id,
        ]);

        $this->reset(['editModal', 'editing_id', 'nombre', 'descripcion', 'stock', 'categoria_id']);
        $this->success('Producto actualizado con éxito.');
    }

    public function confirmDelete($id)
    {
        $this->delete_id = $id;
        $this->confirmDeleteModal = true;
    }

    public function delete()
    {
        $producto = Product::findOrFail($this->delete_id);
        if ($producto->foto) {
            Storage::disk('public')->delete($producto->foto);
        }
        $producto->delete();

        $this->reset(['confirmDeleteModal', 'delete_id']);
        $this->success('Producto eliminado.');
    }

    public function openUpdateFotoModal(int $id)
    {
        $this->selected_product_id = $id;
        $this->reset('newFotoGallery', 'newFotoCamera');
        $this->updateFotoModal = true;
    }

    public function saveFoto()
    {
        $foto = $this->newFotoGallery ?? $this->newFotoCamera;

        if (!$foto) {
            $this->error('No se seleccionó ninguna imagen.');
            return;
        }

        $this->validate([
            $this->newFotoGallery ? 'newFotoGallery' : 'newFotoCamera' => 'image',
        ]);

        $producto = Product::findOrFail($this->selected_product_id);
        $path = $foto->store('productos', 'public');

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->update(['imagen' => $path]);

        $this->reset(['newFotoGallery', 'newFotoCamera', 'updateFotoModal', 'selected_product_id']);
        $this->success('Foto actualizada correctamente.');
    }

    public function render()
    {
        $productos = Product::with(['categoria', 'donaciones'])
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);

        $categorias = Categoria::all();

        return view('livewire.ministerios.misericordia.v1.productos', [
            'headers' => $this->headers,
            'sortBy' => $this->sortBy,
            'productos' => $productos,
            'categorias' => $categorias,
        ]);
    }
}
