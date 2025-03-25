<div class="p-6 bg-base-100 shadow rounded-lg">
    {{ $path = implode(' / ', array_map('ucfirst', explode('/', request()->path()))) }}

    <x-header title="Dashboard Ministerio de Misericordia" subtitle="Estadísticas y donaciones" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat title="Total Productos" :value="$totalProductos" icon="o-cube" />
        <x-stat title="Total Categorías" :value="$totalCategorias" icon="o-rectangle-group" />
        <x-stat title="Total Donaciones" :value="$totalDonaciones" icon="o-hand-raised" />
    </div>

    <div class="flex justify-end mb-4">
        <x-button icon="o-plus" label="Nueva Donación" class="btn-primary" wire:click="openSelectorModal" />
    </div>

    <x-card title="Últimas Donaciones">
        <ul class="divide-y divide-gray-300">
            @forelse ($donacionesRecientes as $donacion)
                <li class="py-2 flex justify-between items-center">
                    <div>
                        <strong>{{ $donacion->producto->nombre ?? 'Producto eliminado' }}</strong>
                        <span class="text-gray-500 text-sm ml-2">x{{ $donacion->cantidad }}</span><br>
                        <span class="text-xs text-gray-500">
                            Donado por: {{ $donacion->donable?->name ?? $donacion->donable?->nombre ?? 'Desconocido' }}
                            ({{ class_basename($donacion->donable_type) }})
                        </span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $donacion->created_at->diffForHumans() }}</span>
                    <x-button icon="o-x-circle" class="btn-xs btn-error ml-4" spinner wire:click="cancelarDonacion({{ $donacion->id }})" title="Cancelar Donación" />
                </li>
            @empty
                <li class="text-gray-500 py-2">No hay donaciones recientes.</li>
            @endforelse
        </ul>
    </x-card>

    {{-- Modal de selección --}}
    <x-modal wire:model="selectorModal" title="Tipo de Donante" subtitle="Selecciona cómo se hará la donación">
        <div class="flex flex-col gap-4">
            <x-button label="Donar como Usuario" class="btn-primary" wire:click="seleccionarTipo('user')" />
            <x-button label="Donar como Familia" class="btn-secondary" wire:click="seleccionarTipo('familia')" />
        </div>
    </x-modal>

    {{-- Modal Usuario --}}
    <x-modal wire:model="modalUsuario" title="Registrar Donación de Usuario">
        <x-form wire:submit.prevent="submitDonacion('user')">
            <x-choices label="Usuario" wire:model="donante_id"
                :options="$usuarios->map(fn($u) => ['id' => $u->id, 'name' => $u->name])"
                single searcheable icon="o-users"  required />

            @foreach ($donaciones as $index => $item)
                <div class="grid grid-cols-12 gap-2 items-end">
                    <div class="col-span-7">
                        <x-choices label="Producto" wire:model="donaciones.{{ $index }}.producto_id"
                        :options="collect([['id' => '', 'name' => '-- Selecciona un producto --']])->concat(
                            $productos->map(fn($p) => ['id' => $p->id, 'name' => $p->nombre])
                        )" single searcheable required />
                    </div>
                    <div class="col-span-3">
                        <x-input type="number" label="Cantidad" wire:model="donaciones.{{ $index }}.cantidad" min="1" />
                    </div>
                    <div class="col-span-2">
                        <x-button icon="o-trash" class="btn-sm btn-error"
                            wire:click="removeProducto({{ $index }})" />
                    </div>
                </div>
            @endforeach

            <x-button icon="o-plus" label="Agregar producto" class="btn-sm btn-outline" wire:click="addProducto" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.modalUsuario = false" />
                <x-button label="Guardar Donación" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- Modal Familia --}}
    <x-modal wire:model="modalFamilia" title="Registrar Donación de Familia">
        <x-form wire:submit.prevent="submitDonacion('familia')">
            <x-choices label="Familia" wire:model="donante_id"
                :options="$familias->map(fn($f) => ['id' => $f->id, 'name' => $f->nombre])" single searcheable icon="o-users" required />

            @foreach ($donaciones as $index => $item)
                <div class="grid grid-cols-12 gap-2 items-end">
                    <div class="col-span-7">
                        <x-choices label="Producto" wire:model="donaciones.{{ $index }}.producto_id"
                        :options="collect([['id' => '', 'name' => '-- Selecciona un producto --']])->concat(
                            $productos->map(fn($p) => ['id' => $p->id, 'name' => $p->nombre])
                        )" single required />
                    </div>
                    <div class="col-span-3">
                        <x-input type="number" label="Cantidad" wire:model="donaciones.{{ $index }}.cantidad" min="1" />
                    </div>
                    <div class="col-span-2">
                        <x-button icon="o-trash" class="btn-sm btn-error"
                            wire:click="removeProducto({{ $index }})" />
                    </div>
                </div>
            @endforeach

            <x-button icon="o-plus" label="Agregar producto" class="btn-sm btn-outline" wire:click="addProducto" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.modalFamilia = false" />
                <x-button label="Guardar Donación" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
