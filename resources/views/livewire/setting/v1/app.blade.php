<div class="p-6 bg-base-100 shadow-lg rounded-lg space-y-6">
    <h2 class="text-2xl font-bold text-center">Configuración de la Aplicación</h2>

    <!-- Logo -->
    <div class="flex items-center space-x-4">
        <div class="relative group">
            <img src="{{ asset($app_logo) }}" class="w-24 h-24 rounded-lg shadow-lg object-cover">

            <!-- Botón de edición sobre la imagen -->
            <x-button icon="o-pencil"
                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition text-white rounded-lg"
                wire:click="openUpdateLogoModal" />
        </div>

        <div>
            <h3 class="text-xl font-semibold text-primary">{{ $app_name }}</h3>
            <p class="text-sm text-gray-500">{{ $app_description }}</p>
        </div>
    </div>

    <!-- Formulario de Configuración -->
    <form wire:submit.prevent="updateSettings" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold">Nombre de la Aplicación</label>
            <input type="text" wire:model="app_name" class="input input-bordered w-full">
        </div>
        <div>
            <label class="block text-sm font-semibold">Descripción</label>
            <textarea wire:model="app_description" class="textarea textarea-bordered w-full"></textarea>
        </div>
        <button type="submit" class="w-full md:w-[25%] bg-slate-300 dark:bg-slate-800 dark:text-white btn">Actualizar Configuración</button>
    </form>

    <!-- Modal para actualizar Logo -->
    <x-modal wire:model="update_logo_modal" title="Actualizar Logo" subtitle="Selecciona y recorta la nueva imagen" separator>
        <x-form wire:submit.prevent="saveLogo">
            <div class="flex justify-center">
                <x-file wire:model="newLogo" accept="image/png, image/jpg, image/jpeg" crop-after-change>
                    <div class="w-40 h-40 rounded-lg bg-black flex items-center justify-center">
                        <img src="{{ $newLogo ? $newLogo->temporaryUrl() : asset($app_logo) }}" 
                            class="w-full h-full object-cover rounded-lg" />
                    </div>
                </x-file>
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.update_logo_modal = false" />
                <x-button label="Restaurar Predeterminado" class="btn-warning" wire:click="resetToDefaultLogo" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
