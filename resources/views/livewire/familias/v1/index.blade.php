<div class="p-6 bg-base-100 shadow rounded-lg">
    {{ $path = implode(' / ', array_map('ucfirst', explode('/', request()->path()))) }}

    <x-header title="Familias" subtitle="Gestión de familias registradas">
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live="search" placeholder="Buscar..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" wire:click="openCreateModal" />
        </x-slot:actions>
    </x-header>

    <x-table :headers="$headers" :rows="$familias" :sort-by="$sortBy" striped with-pagination>
        @scope('cell_nombre', $familia)
            {{ $familia->nombre }}
        @endscope

        @scope('header_count_users_column', $header)
            {{ $header['label'] }}
        @endscope

        @scope('cell_count_users_column', $familia)
            <u>{{ $familia->users_count }}</u>
        @endscope

        @scope('actions', $familia)
            <div class="flex gap-2">
                <x-button icon="o-pencil" wire:click="openEditModal({{ $familia->id }})" class="btn-sm" />
                <x-button icon="o-trash" wire:click="confirmDelete({{ $familia->id }})" class="btn-sm" />
            </div>
        @endscope

        <x-slot:empty>
            <x-icon name="o-user-group" label="Sin familias aún." />
        </x-slot:empty>
    </x-table>

    <x-modal wire:model="createModal" title="Nueva Familia" subtitle="Registrar una nueva familia" separator>
        <x-form wire:submit.prevent="create">
            <div class="mb-4">
                <x-input label="Nombre" wire:model="nombre" required />
            </div>
            <div class="mb-4">
                <x-choices allow-all label="Usuarios" wire:model="selected_users" :options="$availableUsers" multiple search icon="o-users" />
            </div>
            <x-slot:actions>
                <x-checkbox label="Continuar" wire:model="continuarCreando" hint="Continuar creando" />
                <x-button label="Cancelar" @click="$wire.createModal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="editModal" title="Editar Familia" subtitle="Actualizar datos de la familia" separator>
        <x-form wire:submit.prevent="update">
            <x-input label="Nombre" wire:model="nombre" required />
            <div class="mb-4">
                <x-choices allow-all label="Usuarios" wire:model="selected_users" :options="$availableUsers" multiple search icon="o-users" />
            </div>
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.editModal = false" />
                <x-button label="Actualizar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="confirmDeleteModal" title="Eliminar Familia" subtitle="¿Deseas eliminar esta familia?">
        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.confirmDeleteModal = false" />
            <x-button label="Sí, Eliminar" class="btn-danger" wire:click="delete" />
        </x-slot:actions>
    </x-modal>
</div>
