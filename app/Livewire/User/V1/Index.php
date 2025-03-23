<?php

namespace App\Livewire\User\V1;

use App\Models\EstadoCivil;
use App\Models\Genero;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination, Toast;

    // Búsqueda y modales
    public $search = '';
    public bool $create_user_modal = false;
    public bool $edit_user_modal = false;

    // Campos para crear/editar
    public ?int $editing_user_id = null;
    public string $userName = '';
    public string $userEmail = '';
    public string $userUsername = '';
    public string $userPassword = '';
    public string $userPassword_confirmation = '';
    public ?string $numero_telefono = null;
    public ?string $direccion = null;
    public ?int $genero_id = null;
    public ?int $estado_civil_id = null;
    public ?string $profesion = null;
    public ?string $fecha_nacimiento = null;
    public ?string $fecha_conversion = null;
    public ?int $invitador_id = null;

    // Roles y Permisos disponibles (para selects/checkboxes)
    public array $availableRoles = [];
    public array $availablePermissions = [];

    // Roles y Permisos seleccionados en el form
    public array $selectedRoles = [];
    public array $selectedPermissions = [];

    public bool $continuarCreando = false;

    // Cabeceras de la tabla
    public array $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'avatar', 'label' => 'Avatar', 'class' => 'w-1'],
        ['key' => 'name', 'label' => 'Nombre del Usuario', 'class' => 'text-black dark:text-white'],
        ['key' => 'username', 'label' => 'Username', 'class' => 'text-black dark:text-white'],
        ['key' => 'status', 'label' => 'Estado', 'class' => 'text-black dark:text-white'],
        ['key' => 'email', 'label' => 'Email', 'class' => 'text-black dark:text-white'],
        ['key' => 'numero_telefono', 'label' => 'Numero de Telefono', 'class' => 'text-black dark:text-white'],
        ['key' => 'direccion', 'label' => 'Direccion', 'class' => 'text-black dark:text-white'],
        ['key' => 'genero_id', 'label' => 'Genero', 'class' => 'text-black dark:text-white'],
        // ['key' => 'estado_civil_id', 'label' => 'Estado Civil', 'class' => 'text-black dark:text-white'],
        // ['key' => 'profesion', 'label' => 'Profesion', 'class' => 'text-black dark:text-white'],
        // ['key' => 'fecha_nacimiento', 'label' => 'Fecha de Nacimiento', 'class' => 'text-black dark:text-white'],
        ['key' => 'invitador_id', 'label' => 'Invitador', 'class' => 'text-black dark:text-white'],
        ['key' => 'roles', 'label' => 'Roles', 'class' => 'text-black dark:text-white'],
    ];
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    /**
     * Revisa permisos al montar el componente
     */
    public function mount()
    {
        // Verifica que el usuario tenga permiso de ver usuarios
        if (!auth()->user() || !auth()->user()->hasPermissionTo('view_any_user')) {
            abort(403);
        }

        // Carga todos los roles y permisos disponibles (Spatie)
        // Obtiene todos los roles
        $roles = Role::pluck('name', 'id'); // [1 => 'admin', 2 => 'master', ...]

        // Mapea a algo como: [ ['id' => 1, 'name' => 'admin'], ... ]
        $this->availableRoles = collect($roles)->map(function ($roleName, $roleId) {
            return [
                'id'   => $roleId,
                'name' => $roleName
            ];
        })->values()->toArray();

        // Igual con permisos, si quieres la misma forma:
        $permissions = Permission::pluck('name', 'id');
        $this->availablePermissions = collect($permissions)->map(function ($permName, $permId) {
            return [
                'id'   => $permId,
                'name' => $permName
            ];
        })->values()->toArray();
    }

    /**
     * Abre el modal de crear usuario (limpia variables)
     */
    public function openCreateModal()
    {
        $this->reset([
            'userName',
            'userEmail',
            'userUsername',
            'userPassword',
            'numero_telefono',
            'direccion',
            'userPassword_confirmation',
            'selectedRoles',
            'selectedPermissions',
            'genero_id',
            'estado_civil_id',
            'profesion',
            'fecha_conversion',
            'fecha_nacimiento',
            'invitador_id',
        ]);
        $this->create_user_modal = true;
    }

    /**
     * Crea un nuevo usuario con roles y/o permisos
     */
    public function createUser()
    {
        $this->validate([
            'userName'     => 'required|string',
            'userUsername'    => 'required|string|unique:users,username',
            'userEmail'    => 'required|email|unique:users,email',
            'userPassword' => 'required|min:6|confirmed',
            'numero_telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'genero_id' => 'nullable|exists:generos,id',
            'estado_civil_id' => 'nullable|exists:estados_civiles,id',
            'profesion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|string',
            'fecha_conversion' => 'nullable|string',
            'invitador_id' => 'nullable|exists:users,id',
            // 'userPassword_confirmation' => 'required_with:userPassword|same:userPassword|min:6'
        ]);

        // Creamos el usuario
        $user = User::create([
            'name'     => $this->userName,
            'username'    => $this->userUsername,
            'email'    => $this->userEmail,
            'password' => Hash::make($this->userPassword),
            'numero_telefono' => $this->numero_telefono,
            'direccion' => $this->direccion,
            'genero_id' => $this->genero_id,
            'estado_civil_id' => $this->estado_civil_id,
            'profesion' => $this->profesion,
            'fecha_nacimiento' => $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->format('Y-m-d') : null,
            'fecha_conversion' => $this->fecha_conversion ? Carbon::parse($this->fecha_conversion)->format('Y-m-d') : null,
            'invitador_id' => $this->invitador_id,
        ]);

        // Asignar roles (con syncRoles)
        if (!empty($this->selectedRoles)) {
            // Obtenemos los nombres de esos roles
            $rolesNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
            $user->syncRoles($rolesNames);
        }

        // Asignar permisos (con syncPermissions)
        if (!empty($this->selectedPermissions)) {
            $permissionsNames = Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();
            $user->syncPermissions($permissionsNames);
        }

        // Permisos predeterminados
        $defaultPermissions = [
            'view_menu_profile',
            'view_menu_dashboard',
            'view_any_notifications',
            'view_notifications',
            'mark_as_read_notifications',
            'mark_as_unread_notifications',
        ];

        // Asignar los permisos si no los tiene
        foreach ($defaultPermissions as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
            }
        }

        if (!$this->continuarCreando) {
            $this->reset([
                'create_user_modal',
                'userName',
                'userUsername',
                'userEmail',
                'numero_telefono',
                'direccion',
                'userPassword',
                'userPassword_confirmation',
                'selectedRoles',
                'selectedPermissions',
                'genero_id',
                'estado_civil_id',
                'profesion',
                'fecha_nacimiento',
                'fecha_conversion',
                'invitador_id',
            ]);
        } else {
            $this->reset([
                'userName',
                'userUsername',
                'userEmail',
                'numero_telefono',
                'direccion',
                'userPassword',
                'userPassword_confirmation',
                'selectedRoles',
                'selectedPermissions',
                'genero_id',
                'estado_civil_id',
                'profesion',
                'fecha_nacimiento',
                'fecha_conversion',
                'invitador_id'
            ]);
        }

        $this->success('Usuario creado con éxito!');
    }

    /**
     * Abre el modal de edición de un usuario existente
     */
    public function editUser(int $userId)
    {
        $user = User::findOrFail($userId);

        $this->editing_user_id = $user->id;
        $this->userName  = $user->name;
        $this->userUsername = $user->username;
        $this->userEmail = $user->email;
        $this->userPassword = ''; // vacío, solo se setea si se cambia
        $this->numero_telefono = $user->numero_telefono ?? null;
        $this->direccion = $user->direccion ?? null;
        $this->genero_id = $user->genero_id ?? null;
        $this->estado_civil_id = $user->estado_civil_id ?? null;
        $this->profesion = $user->profesion ?? null;
        $this->fecha_nacimiento = $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : null;
        $this->fecha_conversion = $user->fecha_conversion ? $user->fecha_conversion->format('Y-m-d') : null;
        $this->invitador_id = $user->invitador_id ?? null;

        // Obtenemos roles y permisos actuales del usuario en IDs
        $this->selectedRoles = $user->roles()->pluck('id')->toArray();
        $this->selectedPermissions = $user->permissions()->pluck('id')->toArray();

        $this->edit_user_modal = true;
    }

    /**
     * Actualiza datos del usuario
     */
    public function updateUser()
    {
        $this->validate([
            'userName'     => 'required|string',
            'userUsername'    => 'required|string|unique:users,username,' . $this->editing_user_id,
            'userEmail'    => 'required|email|unique:users,email,' . $this->editing_user_id,
            'userPassword' => 'nullable|min:6|confirmed',
            'numero_telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'genero_id' => 'nullable|exists:generos,id',
            'estado_civil_id' => 'nullable|exists:estados_civiles,id',
            'profesion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|string',
            'fecha_conversion' => 'nullable|string',
            'invitador_id' => 'nullable|exists:users,id',
            // 'userPassword_confirmation' => 'required_with:userPassword|same:userPassword|min:6'
        ]);

        $user = User::findOrFail($this->editing_user_id);

        $data = [
            'name'  => $this->userName,
            'username' => $this->userUsername,
            'email' => $this->userEmail,
            'numero_telefono' => $this->numero_telefono,
            'direccion' => $this->direccion,
            'genero_id' => $this->genero_id,
            'estado_civil_id' => $this->estado_civil_id,
            'profesion' => $this->profesion,
            'fecha_nacimiento' => $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->format('Y-m-d') : null,
            'fecha_conversion' => $this->fecha_conversion ? Carbon::parse($this->fecha_conversion)->format('Y-m-d') : null,
            'invitador_id' => $this->invitador_id,
        ];

        // Si hay password, se actualiza
        if (!empty($this->userPassword)) {
            $data['password'] = Hash::make($this->userPassword);
        }

        $user->update($data);

        // Sincronizar roles y permisos
        $rolesNames = Role::whereIn('id', $this->selectedRoles)->pluck('name')->toArray();
        $user->syncRoles($rolesNames);

        $permissionsNames = Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();
        $user->syncPermissions($permissionsNames);

        $this->reset([
            'edit_user_modal',
            'editing_user_id',
            'userName',
            'userUsername',
            'userEmail',
            'userPassword',
            'userPassword_confirmation',
            'numero_telefono',
            'direccion',
            'genero_id',
            'estado_civil_id',
            'profesion',
            'fecha_nacimiento',
            'fecha_conversion',
            'invitador_id',
            'selectedRoles',
            'selectedPermissions'
        ]);

        $this->toast(
            type: 'success',
            title: 'Actualizado',
            description: 'Usuario Actualizado Con Éxito',
            icon: 'o-information-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000,
        );
    }

    /**
     * Elimina un usuario
     */
    public function deleteUser(int $userId)
    {
        $user = User::findOrFail($userId);

        // Evitar eliminar a usuario master, o a ti mismo, según tu lógica
        if ($user->id === auth()->id()) {
            $this->error('No se puede eliminar a ti mismo');
            return;
        }
        if ($user->hasRole('master')) {
            $this->error('No se puede eliminar a un usuario master');
            return;
        }

        $user->delete();

        $this->toast(
            type: 'success',
            title: 'Eliminado',
            description: 'Usuario Eliminado Con Éxito',
            icon: 'o-information-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000,
        );
    }

    /**
     * Redirecciona a la vista "show" (detalle) del usuario
     */
    public function viewUser(string $userId)
    {
        return redirect()->route('users.show', $userId);
    }

    public function toggleStatus(int $userId)
    {
        $user = User::findOrFail($userId);
        $user->status = !$user->status;
        $user->save();

        $this->success('Estado actualizado correctamente');
    }

    /**
     * Renderiza la vista con la lista de usuarios
     */
    public function render()
    {
        if (auth()->user()->hasRole(['master'])) {
            $users = User::with('roles', 'permissions')
                ->where('name', 'like', '%' . $this->search . '%')
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'creyente');
                })
                ->orderBy(...array_values($this->sortBy))
                ->paginate(10);
        } else {
            $users = User::with('roles', 'permissions')
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'master');
                })
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'creyente');
                })
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy(...array_values($this->sortBy))
                ->paginate(10);
        }

        $allUsers = User::all();

        $generos = Genero::all();
        $estados_civiles = EstadoCivil::all();

        return view('livewire.user.v1.index', [
            'headers' => $this->headers,
            'sortBy'  => $this->sortBy,
            'users'   => $users,
            'allUsers' => $allUsers,
            'generos' => $generos,
            'estados_civiles' => $estados_civiles,
        ]);
    }
}
