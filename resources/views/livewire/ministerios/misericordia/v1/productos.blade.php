<div class="p-6 bg-base-100 shadow rounded-lg">
    {{ $path = implode(' / ', array_map('ucfirst', explode('/', request()->path()))) }}

    <x-header title="Productos" subtitle="Gestión de productos donados">
        <x-slot:middle class="!justify-end">
            <x-input wire:model.live="search" placeholder="Buscar..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" wire:click="openCreateModal" />
        </x-slot:actions>
    </x-header>

    <x-table :headers="$headers" :rows="$productos" :sort-by="$sortBy" striped with-pagination>
        @scope('cell_imagen', $producto)
            @if ($producto->imagen)
                <x-avatar :image=" asset('storage/' . $producto->imagen)" class="!w-10 rounded-sm" />
            @else
                <x-avatar :image=" asset('storage/picture.png')" class="!w-10 rounded-sm" />
            @endif
        @endscope

        @scope('cell_nombre', $producto)
            {{ $producto->nombre }}
        @endscope

        @scope('cell_descripcion', $producto)
            {{ $producto->descripcion }}
        @endscope

        @scope('cell_stock', $producto)
            {{ $producto->stock }}
        @endscope

        @scope('cell_categoria_id', $producto)
            {{ $producto->categoria->nombre ?? '-' }}
        @endscope

        @scope('actions', $producto)
            <div class="flex gap-2">
                <x-button icon="o-camera" wire:click="openUpdateFotoModal({{ $producto->id }})" class="btn-sm" />
                <x-button icon="o-pencil" wire:click="openEditModal({{ $producto->id }})" class="btn-sm" />
                <x-button icon="o-trash" wire:click="confirmDelete({{ $producto->id }})" class="btn-sm" />
            </div>
        @endscope

        <x-slot:empty>
            <x-icon name="o-cube" label="Sin productos aún." />
        </x-slot:empty>
    </x-table>

    <x-modal wire:model="createModal" title="Nuevo Producto" subtitle="Agregar un producto">
        <x-form wire:submit.prevent="create">
            <x-input label="Nombre" wire:model="nombre" required />
            <x-textarea label="Descripción" wire:model="descripcion" />
            <x-input label="Stock" type="number" wire:model="stock" />
            <x-choices label="Categoría" wire:model="categoria_id" searcheable :options="$categorias->map(fn($cat) => ['id' => $cat->id, 'name' => $cat->nombre])" single required />
            <x-slot:actions>
                <x-checkbox label="Continuar" wire:model="continuarCreando" hint="Continuar creando" />
                <x-button label="Cancelar" @click="$wire.createModal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="editModal" title="Editar Producto" subtitle="Modificar producto existente">
        <x-form wire:submit.prevent="update">
            <x-input label="Nombre" wire:model="nombre" required />
            <x-textarea label="Descripción" wire:model="descripcion" />
            <x-input label="Stock" type="number" wire:model="stock" />
            <x-choices label="Categoría" wire:model="categoria_id" searcheable :options="$categorias->map(fn($cat) => ['id' => $cat->id, 'name' => $cat->nombre])" single required />
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.editModal = false" />
                <x-button label="Actualizar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="confirmDeleteModal" title="Eliminar Producto" subtitle="¿Deseas eliminar este producto?">
        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.confirmDeleteModal = false" />
            <x-button label="Sí, Eliminar" class="btn-danger" wire:click="delete" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="updateFotoModal" title="Actualizar Foto del Producto" subtitle="Selecciona la nueva imagen">
        <x-form wire:submit.prevent="saveFoto">
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <div class="flex flex-col items-center">
                    <label class="mb-2 text-sm font-semibold text-center">Desde Galería</label>
                    <x-file wire:model="newFotoGallery" id="gallery-upload-producto" accept="image/*" crop-after-change>
                        <div class="w-40 h-40 bg-black flex items-center justify-center rounded-xl">
                            <img src="{{ $newFotoGallery ? $newFotoGallery->temporaryUrl() : ($selected_product_id ? asset('storage/' . \App\Models\Product::find($selected_product_id)?->foto) : asset('storage/picture.png')) }}" class="w-full h-full object-cover rounded-xl" />
                        </div>
                    </x-file>
                </div>
                <div class="flex flex-col items-center">
                    <label class="mb-2 text-sm font-semibold text-center">Tomar Foto</label>
                    <x-file wire:model="newFotoCamera" id="camera-upload-producto" accept="image/*" capture="picture" crop-after-change>
                        <div class="w-40 h-40 bg-black flex items-center justify-center rounded-xl">
                            <img src="{{ $newFotoCamera ? $newFotoCamera->temporaryUrl() : ($selected_product_id ? asset('storage/' . \App\Models\Product::find($selected_product_id)?->foto) : asset('storage/picture.png')) }}" class="w-full h-full object-cover rounded-xl" />
                        </div>
                    </x-file>
                </div>
            </div>
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.updateFotoModal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
