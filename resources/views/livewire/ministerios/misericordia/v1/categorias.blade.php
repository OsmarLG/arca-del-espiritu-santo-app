<div class="p-6 bg-base-100 shadow rounded-lg">
    {{ $path = implode(' / ', array_map('ucfirst', explode('/', request()->path()))) }}
    
    <x-header title="Categorías" subtitle="Gestión de categorías de productos">
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live="search" placeholder="Buscar..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" wire:click="openCreateModal" />
        </x-slot:actions>
    </x-header>

    <x-table :headers="$headers" :rows="$categorias" :sort-by="$sortBy" striped with-pagination>
        @scope('cell_nombre', $categoria)
            {{ $categoria->nombre }}
        @endscope

        {{-- Sobrescribe la celda del nombre del rol --}}
        @scope('header_count_products_column', $header)
            {{ $header['label'] }}
        @endscope

        @scope('cell_count_products_column', $categoria)
            <u>{{ $categoria->productos->count() }}</u>
        @endscope

        @scope('actions', $categoria)
            <div class="flex gap-2">
                <x-button icon="o-pencil" wire:click="openEditModal({{ $categoria->id }})" class="btn-sm" />
                <x-button icon="o-trash" wire:click="confirmDelete({{ $categoria->id }})" class="btn-sm" />
            </div>
        @endscope

        <x-slot:empty>
            <x-icon name="o-cube" label="Sin categorías aún." />
        </x-slot:empty>
    </x-table>

    <x-modal wire:model="createModal" title="Nueva Categoría" subtitle="Agregar una categoría">
        <x-form wire:submit.prevent="create">
            <x-input label="Nombre" wire:model="nombre" required />
            <x-slot:actions>
                <x-checkbox label="Continuar" wire:model="continuarCreando" hint="Continuar creando" />
                <x-button label="Cancelar" @click="$wire.createModal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="editModal" title="Editar Categoría" subtitle="Modificar nombre de la categoría">
        <x-form wire:submit.prevent="update">
            <x-input label="Nombre" wire:model="nombre" required />
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.editModal = false" />
                <x-button label="Actualizar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="confirmDeleteModal" title="Eliminar" subtitle="¿Deseas eliminar esta categoría?">
        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.confirmDeleteModal = false" />
            <x-button label="Sí, Eliminar" class="btn-danger" wire:click="delete" />
        </x-slot:actions>
    </x-modal>
</div>
