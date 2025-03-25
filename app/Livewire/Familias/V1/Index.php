<?php

namespace App\Livewire\Familias\V1;

use App\Models\Familia;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];

    public array $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'nombre', 'label' => 'Nombre de Familia', 'class' => 'text-black dark:text-white'],
        ['key' => 'count_users_column', 'label' => 'Integrantes', 'class' => 'text-black dark:text-white'],
    ];

    public bool $createModal = false;
    public bool $editModal = false;
    public bool $confirmDeleteModal = false;

    public ?int $editing_id = null;
    public string $nombre = '';
    public array $selected_users = [];
    public ?int $delete_id = null;

    public bool $continuarCreando = false;
    public array $availableUsers = [];

    public function openCreateModal()
    {
        $this->reset(['nombre', 'selected_users']);
        $this->createModal = true;
    }

    public function create()
    {
        $this->validate([
            'nombre' => 'required|string|unique:familias,nombre',
            'selected_users' => 'array',
        ]);

        $familia = Familia::create(['nombre' => $this->nombre]);
        $familia->users()->sync($this->selected_users);

        if (!$this->continuarCreando) {
            $this->reset(['createModal', 'nombre', 'selected_users']);
        } else {
            $this->reset(['nombre', 'selected_users']);
        }

        $this->success('Familia creada con éxito.');
    }

    public function openEditModal($id)
    {
        $familia = Familia::with('users')->findOrFail($id);
        $this->editing_id = $id;
        $this->nombre = $familia->nombre;
        $this->selected_users = $familia->users->pluck('id')->toArray();
        $this->editModal = true;
    }

    public function update()
    {
        $this->validate([
            'nombre' => 'required|string|unique:familias,nombre,' . $this->editing_id,
            'selected_users' => 'array',
        ]);

        $familia = Familia::findOrFail($this->editing_id);
        $familia->update(['nombre' => $this->nombre]);
        $familia->users()->sync($this->selected_users);

        $this->reset(['editModal', 'editing_id', 'nombre', 'selected_users']);
        $this->success('Familia actualizada con éxito.');
    }

    public function confirmDelete($id)
    {
        $this->delete_id = $id;
        $this->confirmDeleteModal = true;
    }

    public function delete()
    {
        Familia::findOrFail($this->delete_id)->delete();
        $this->reset(['confirmDeleteModal', 'delete_id']);
        $this->success('Familia eliminada.');
    }

    public function render()
    {
        $familias = Familia::withCount('users')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);

        $users = User::pluck('name', 'id'); // [1 => 'admin', 2 => 'master', ...]

        // Mapea a algo como: [ ['id' => 1, 'name' => 'admin'], ... ]
        $this->availableUsers = collect($users)->map(function ($name, $id) {
            return [
                'id'   => $id,
                'name' => $name
            ];
        })->values()->toArray();

        return view('livewire.familias.v1.index', [
            'headers' => $this->headers,
            'sortBy' => $this->sortBy,
            'familias' => $familias,
            'availableUsers' => $this->availableUsers,
        ]);
    }
}