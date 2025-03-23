<div class="p-6 bg-base-100 shadow rounded-lg">
    {{ $path = implode(' / ', array_map('ucfirst', explode('/', request()->path()))) }}

    <x-header title="Usuarios" subtitle="Gestión de Usuarios">
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

        @scope('cell_status', $user)
            <x-button icon="{{ $user->status ? 'o-check-circle' : 'o-x-circle' }}"
                class="{{ $user->status ? 'btn-info' : 'btn-danger' }}" wire:click="toggleStatus({{ $user->id }})">
            </x-button>
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

        @scope('cell_roles', $user)
            <div class="flex flex-wrap gap-1">
                @foreach ($user->roles as $role)
                    <x-badge :value="$role->name" class="bg-red-200 text-black dark:bg-slate-800 dark:text-white" />
                @endforeach
            </div>
        @endscope

        {{-- Sobrescribe las acciones --}}
        @scope('actions', $user)
            <div class="flex gap-2">
                <x-button icon="o-eye" wire:click="viewUser({{ $user->id }})" spinner class="btn-sm" />
                <x-button icon="o-pencil" wire:click="editUser({{ $user->id }})" spinner class="btn-sm" />
                <x-button icon="o-trash" wire:click="deleteUser({{ $user->id }})" spinner class="btn-sm" />
            </div>
        @endscope

        <x-slot:empty>
            <x-icon name="o-cube" label="Vacio." />
        </x-slot:empty>
    </x-table>

    {{-- Modal para crear roles --}}
    <x-modal wire:model="create_user_modal" title="Crear Usuario" subtitle="Añade un nuevo Usuario" separator>
        <x-form wire:submit.prevent="createUser">
            <x-input label="Nombre Completo" placeholder="Tú nombre" icon="o-user" hint="Tú Nombre Completo"
                wire:model="userName" required />
            <x-input label="Username" wire:model="userUsername" required placeholder="Tú Nombre de Usuario"
                icon="o-user" hint="Tú Usuario" />
            <x-input label="Email" wire:model="userEmail" type="email" required placeholder="Tú Correo"
                icon="o-envelope" hint="Tú Nombre de Correo" />
            <x-password label="Contraseña" wire:model="userPassword" type="password" placeholder="Contraseña segura"
                hint="Ingresa tu Contraseña" clearable required />
            <x-password label="Confirmar Contraseña" wire:model="userPassword_confirmation" type="password"
                placeholder="Contraseña segura" hint="Confirma tu Contraseña" clearable required />
            <x-input label="Numero de Telefono" wire:model="numero_telefono" placeholder="Tú Numero de Telefono"
                icon="o-phone" hint="Tú Numero de Telefono" />
            <x-input label="Direccion" wire:model="direccion" placeholder="Tú Direccion" icon="o-home"
                hint="Tú Direccion" />
            {{-- {{ dd($generos, $estados_civiles, $allUsers) }} --}}
            <x-choices label="Genero" wire:model="genero_id" placeholder="Tú Genero" icon="o-user" hint="Tú Genero"
                :options="$generos?->map(fn($genero) => ['id' => $genero->id, 'name' => $genero->nombre]) ?? []" single />
            <x-choices label="Estado Civil" wire:model="estado_civil_id" placeholder="Tú Estado Civil" icon="o-user"
                hint="Tú Estado Civil" :options="$estados_civiles?->map(fn($estado) => ['id' => $estado->id, 'name' => $estado->nombre]) ??
                    []" single />
            <x-input label="Profesion" wire:model="profesion" placeholder="Tú Profesion" icon="o-user"
                hint="Tú Profesion" />
            <x-datetime label="Fecha de Nacimiento" wire:model="fecha_nacimiento" placeholder="Tú Fecha de Nacimiento"
                icon="o-calendar" hint="Tú Fecha de Nacimiento" />
            <x-datetime label="Fecha de Conversion" wire:model="fecha_conversion" placeholder="Tú Fecha de Conversion"
                icon="o-calendar" hint="Tú Fecha de Conversion" />
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

            <div class="mb-4">
                <x-choices label="Roles" allow-all wire:model="selectedRoles" :options="$availableRoles" />
            </div>

            <div class="mb-4">
                <x-choices label="Permisos" allow-all wire:model="selectedPermissions" :options="$availablePermissions" />
            </div>

            <x-slot:actions>
                <x-checkbox label="Continuar" wire:model="continuarCreando" hint="Continuar creando" />
                <x-button label="Cancelar" @click="$wire.create_user_modal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- Modal para editar usuarios --}}
    <x-modal wire:model="edit_user_modal" title="Editar Usuario" subtitle="Actualiza un Usuario existente" separator>
        <x-form wire:submit.prevent="updateUser">
            <x-input label="Nombre Completo" placeholder="Tú nombre" icon="o-user" hint="Tú Nombre Completo"
                wire:model="userName" required /> <x-input label="Username" wire:model="userUsername" required
                placeholder="Tú Nombre de Usuario" icon="o-user" hint="Tú Usuario" />
            <x-input label="Email" wire:model="userEmail" type="email" required placeholder="Tú Correo"
                icon="o-envelope" hint="Tú Nombre de Correo" />
            <x-password label="Contraseña" wire:model="userPassword" type="password" placeholder="Contraseña segura"
                hint="Ingresa tu Contraseña" clearable />
            <x-password label="Confirmar Contraseña" wire:model="userPassword_confirmation" type="password"
                placeholder="Contraseña segura" hint="Confirma tu Contraseña" clearable />
            <x-input label="Numero de Telefono" wire:model="numero_telefono" placeholder="Tú Numero de Telefono"
                icon="o-phone" hint="Tú Numero de Telefono" />
            <x-input label="Direccion" wire:model="direccion" placeholder="Tú Direccion" icon="o-home"
                hint="Tú Direccion" />
            <x-choices label="Genero" wire:model="genero_id" placeholder="Tú Genero" icon="o-user"
                hint="Tú Genero" :options="$generos?->map(fn($genero) => ['id' => $genero->id, 'name' => $genero->nombre]) ?? []" single />
            <x-choices label="Estado Civil" wire:model="estado_civil_id" placeholder="Tú Estado Civil"
                icon="o-user" hint="Tú Estado Civil" :options="$estados_civiles?->map(fn($estado) => ['id' => $estado->id, 'name' => $estado->nombre]) ?? []" single />
            <x-input label="Profesion" wire:model="profesion" placeholder="Tú Profesion" icon="o-user"
                hint="Tú Profesion" />
            <x-datetime label="Fecha de Nacimiento" wire:model="fecha_nacimiento"
                placeholder="Tú Fecha de Nacimiento" icon="o-calendar" hint="Tú Fecha de Nacimiento" />
            <x-datetime label="Fecha de Conversion" wire:model="fecha_conversion"
                placeholder="Tú Fecha de Conversion" icon="o-calendar" hint="Tú Fecha de Conversion" />
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

            <div class="mb-4">
                <x-choices label="Roles" allow-all wire:model="selectedRoles" :options="$availableRoles" />
            </div>

            <div class="mb-4">
                <x-choices label="Permisos" allow-all wire:model="selectedPermissions" :options="$availablePermissions" />
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.edit_user_modal = false" />
                <x-button label="Actualizar" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
