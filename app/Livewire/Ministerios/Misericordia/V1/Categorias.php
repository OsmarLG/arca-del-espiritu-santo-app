<?php

namespace App\Livewire\Ministerios\Misericordia\V1;

use App\Models\Categoria;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Categorias extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];

    public array $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'nombre', 'label' => 'Nombre de Categoría', 'class' => 'text-black dark:text-white'],
        ['key' => 'count_products_column', 'label' => 'Productos', 'class' => 'text-black dark:text-white'],

    ];

    public bool $createModal = false;
    public bool $editModal = false;
    public bool $confirmDeleteModal = false;

    public ?int $editing_id = null;
    public string $nombre = '';
    public ?int $delete_id = null;

    public bool $continuarCreando = false;

    public function openCreateModal()
    {
        $this->reset(['nombre']);
        $this->createModal = true;
    }

    public function create()
    {
        $this->validate(['nombre' => 'required|string|unique:categorias,nombre']);
        Categoria::create(['nombre' => $this->nombre]);

        if (!$this->continuarCreando) {
            $this->reset(['createModal', 'nombre']);
        } else {
            $this->reset(['nombre']);
        }

        $this->success('Categoría creada con éxito.');
    }

    public function openEditModal($id)
    {
        $categoria = Categoria::findOrFail($id);
        $this->editing_id = $id;
        $this->nombre = $categoria->nombre;
        $this->editModal = true;
    }

    public function update()
    {
        $this->validate([
            'nombre' => 'required|string|unique:categorias,nombre,' . $this->editing_id,
        ]);

        Categoria::findOrFail($this->editing_id)->update(['nombre' => $this->nombre]);

        $this->reset(['editModal', 'editing_id', 'nombre']);
        $this->success('Categoría actualizada con éxito.');
    }

    public function confirmDelete($id)
    {
        $this->delete_id = $id;
        $this->confirmDeleteModal = true;
    }

    public function delete()
    {
        Categoria::findOrFail($this->delete_id)->delete();
        $this->reset(['confirmDeleteModal', 'delete_id']);
        $this->success('Categoría eliminada.');
    }

    public function render()
    {
        $categorias = Categoria::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);

        return view('livewire.ministerios.misericordia.v1.categorias', [
            'headers' => $this->headers,
            'sortBy' => $this->sortBy,
            'categorias' => $categorias,
        ]);
    }
}
