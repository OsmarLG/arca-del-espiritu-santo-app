<div class="p-6 bg-base-100 shadow rounded-lg">
    {{ $path = implode(' / ', array_map('ucfirst', explode('/', request()->path()))) }}

    <x-header title="Creyentes" subtitle="Gestión de Creyentes">
        <x-slot:middle class="!justify-end">
            <x-input icon="o-bolt" wire:model.live="search" placeholder="Buscar..." />
        </x-slot:middle>
        <x-slot:actions>
            <?php $filters = [
                [
                    'id' => 1,
                    'name' => 'Ascendente',
                ],
                [
                    'id' => 2,
                    'name' => 'Descendente',
                ],
            ]; ?>
            {{-- <x-select label="Ordenar" icon="o-funnel" :options="$filters" inline /> --}}
            <x-button icon="o-plus" class="btn-primary" wire:click="openCreateModal" />
        </x-slot:actions>
    </x-header>

    {{-- Tabla --}}
    <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" striped with-pagination>
        @scope('header_name', $header)
            {{ $header['label'] }} <x-icon name="s-question-mark-circle" />
        @endscope

        @scope('cell_avatar', $user)
            @if ($user->avatar)
                <x-avatar :image="asset('storage/' . $user->avatar)" class="!w-10" />
            @else
                <?php
                $words = explode(' ', $user->name);
                
                $initials = '';
                foreach ($words as $word) {
                    $initials .= strtoupper($word[0]);
                }
                ?>
                <x-avatar placeholder="{{ $initials }}" class="!w-10" />
            @endif
        @endscope

        {{-- Sobrescribe la celda del nombre del User --}}
        @scope('cell_name', $user)
            <x-badge :value="$user->name" class="badge-primary" />
        @endscope

        @scope('cell_numero_telefono', $user)
            {{ $user->numero_telefono ?? '-' }}
        @endscope

        @scope('cell_direccion', $user)
            {{ $user->direccion ?? '-' }}
        @endscope

        @scope('cell_genero_id', $user)
            {{ $user->genero->nombre ?? '-' }}
        @endscope

        @scope('cell_edad', $user)
            {{ $user->edad ?? '-' }}
        @endscope

        @scope('cell_estado_civil_id', $user)
            {{ $user->estado_civil->nombre ?? '-' }}
        @endscope

        @scope('cell_invitador_id', $user)
            {{ $user->invitador->name ?? '-' }}
        @endscope

        @scope('cell_viene_otra_iglesia', $user)
            {{ $user->viene_otra_iglesia ? 'Sí' : 'No' }}
        @endscope

        @scope('cell_bautizado', $user)
            {{ $user->bautizado ? 'Sí' : 'No' }}
        @endscope

        @scope('cell_profesion', $user)
            {{ $user->profesion ?? '-' }}
        @endscope

        @scope('cell_edad', $user)
            {{ $user->edad ?? '-' }}
        @endscope

        @scope('cell_fecha_conversion', $user)
            {{ $user->fecha_conversion->format('d-m-Y') ?? '-' }}
        @endscope

        @scope('cell_viene_otra_iglesia', $user)
            {{ $user->viene_otra_iglesia ? 'Sí' : 'No' }}
        @endscope

        @scope('cell_bautizado', $user)
            {{ $user->bautizado ? 'Sí' : 'No' }}
        @endscope

        @scope('cell_numero_telefono', $user)
            @if ($user->numero_telefono)
                <div class="flex flex-col gap-1">
                    <a href="tel:{{ $user->numero_telefono }}" class="text-blue-500 hover:underline" target="_blank">
                        📞 {{ $user->numero_telefono }}
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->numero_telefono) }}"
                        class="text-green-500 hover:underline" target="_blank">
                        💬 WhatsApp
                    </a>
                </div>
            @else
                <span class="text-gray-500">-</span>
            @endif
        @endscope

        {{-- Sobrescribe las acciones --}}
        @scope('actions', $user)
            <div class="flex gap-2">
                <x-button icon="o-camera" wire:click="openUpdateAvatarModal({{ $user->id }})" spinner class="btn-sm" />
                <x-button icon="o-eye" wire:click="viewCreyente({{ $user->id }})" spinner class="btn-sm" />
                <x-button icon="o-pencil" wire:click="editCreyente({{ $user->id }})" spinner class="btn-sm" />
                <x-button icon="o-trash" wire:click="deleteCreyente({{ $user->id }})" spinner class="btn-sm" />
            </div>
        @endscope

        <x-slot:empty>
            <x-icon name="o-cube" label="Vacio." />
        </x-slot:empty>
    </x-table>

    {{-- Modal para crear creyentes --}}
    <x-modal wire:model="create_creyente_modal" title="Crear Creyente" subtitle="Añade un nuevo Creyente" separator>
        <x-form wire:submit.prevent="createCreyente">
            <x-input label="Nombre Completo" placeholder="Nombre" icon="o-user" hint="Nombre Completo"
                wire:model="creyenteName" required />
            <x-input label="Numero de Telefono" wire:model="numero_telefono" placeholder="Numero de Telefono"
                icon="o-phone" hint="Numero de Telefono" />
            <x-input label="Direccion" wire:model="direccion" placeholder="Direccion" icon="o-home" hint="Direccion" />
            <x-checkbox label="Viene de otra iglesia" wire:model="viene_otra_iglesia" hint="Viene de otra iglesia" />
            <x-checkbox label="Bautizado" wire:model="bautizado" hint="Bautizado" />
            {{-- {{ dd($generos, $estados_civiles, $allUsers) }} --}}
            <x-choices label="Genero" wire:model="genero_id" placeholder="Genero" icon="o-user" hint="Genero"
                :options="$generos?->map(fn($genero) => ['id' => $genero->id, 'name' => $genero->nombre]) ?? []" single />
            <x-choices label="Estado Civil" wire:model="estado_civil_id" placeholder="Estado Civil" icon="o-user"
                hint="Estado Civil" :options="$estados_civiles?->map(fn($estado) => ['id' => $estado->id, 'name' => $estado->nombre]) ??
                    []" single />
            <x-input label="Profesion" wire:model="profesion" placeholder="Profesion" icon="o-user" hint="Profesion" />
            <x-datetime label="Fecha de Nacimiento" wire:model="fecha_nacimiento" placeholder="Fecha de Nacimiento"
                icon="o-calendar" hint="Fecha de Nacimiento" />
            <x-datetime label="Fecha de Conversion" wire:model="fecha_conversion" placeholder="Fecha de Conversion"
                icon="o-calendar" hint="Fecha de Conversion" />
            <x-choices label="Invitador" wire:model="invitador_id" placeholder="Invitador" icon="o-user"
                :options="$allUsers->map(
                    fn($user) => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                        'initials' => collect(explode(' ', $user->name))
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->join(''),
                    ],
                )" single />

            <x-slot:actions>
                <x-checkbox label="Continuar" wire:model="continuarCreando" hint="Continuar creando" />
                <x-button label="Cancelar" @click="$wire.create_creyente_modal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- Modal para editar creyentes --}}
    <x-modal wire:model="edit_creyente_modal" title="Editar Creyente" subtitle="Actualiza un Creyente existente"
        separator>
        <x-form wire:submit.prevent="updateCreyente">
            <x-input label="Nombre Completo" placeholder="Nombre" icon="o-user" hint="Nombre Completo"
                wire:model="creyenteName" required />
            <x-input label="Numero de Telefono" wire:model="numero_telefono" placeholder="Numero de Telefono"
                icon="o-phone" hint="Numero de Telefono" />
            <x-input label="Direccion" wire:model="direccion" placeholder="Direccion" icon="o-home"
                hint="Direccion" />
            <x-checkbox label="Viene de otra iglesia" wire:model="viene_otra_iglesia" hint="Viene de otra iglesia" />
            <x-checkbox label="Bautizado" wire:model="bautizado" hint="Bautizado" />
            <x-choices label="Genero" wire:model="genero_id" placeholder="Genero" icon="o-user" hint="Genero"
                :options="$generos?->map(fn($genero) => ['id' => $genero->id, 'name' => $genero->nombre]) ?? []" single />
            <x-choices label="Estado Civil" wire:model="estado_civil_id" placeholder="Estado Civil" icon="o-user"
                hint="Estado Civil" :options="$estados_civiles?->map(fn($estado) => ['id' => $estado->id, 'name' => $estado->nombre]) ??
                    []" single />
            <x-input label="Profesion" wire:model="profesion" placeholder="Profesion" icon="o-user"
                hint="Profesion" />
            <x-datetime label="Fecha de Nacimiento" wire:model="fecha_nacimiento" placeholder="Fecha de Nacimiento"
                icon="o-calendar" hint="Fecha de Nacimiento" />
            <x-datetime label="Fecha de Conversion" wire:model="fecha_conversion" placeholder="Fecha de Conversion"
                icon="o-calendar" hint="Fecha de Conversion" />
            <x-choices label="Invitador" wire:model="invitador_id" placeholder="Tú Invitador" icon="o-user"
                :options="$allUsers->map(
                    fn($user) => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                        'initials' => collect(explode(' ', $user->name))
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->join(''),
                    ],
                )" single hint="Tú Invitador" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.edit_creyente_modal = false" />
                <x-button label="Actualizar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="update_avatar_modal" title="Actualizar Avatar" subtitle="Selecciona la nueva imagen"
        separator>
        <x-form wire:submit.prevent="saveAvatar">
            <div class="flex justify-center">
                <x-file wire:model="newAvatar" accept="image/*" crop-after-change>
                    <div class="w-40 h-40 rounded-full bg-black flex items-center justify-center">
                        <img src="{{ $newAvatar ? $newAvatar->temporaryUrl() : ($selected_user_id ? asset('storage/' . \App\Models\User::find($selected_user_id)?->avatar) : asset('storage/user.png')) }}"
                            class="w-full h-full object-cover rounded-full" />
                    </div>
                </x-file>
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.update_avatar_modal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
