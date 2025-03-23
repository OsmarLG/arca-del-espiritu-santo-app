<?php

namespace App\Livewire\Creyentes\V1;

use App\Models\EstadoCivil;
use App\Models\Genero;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast, WithFileUploads;

    // Búsqueda y modales
    public $search = '';
    public bool $create_creyente_modal = false;
    public bool $edit_creyente_modal = false;
    public bool $update_avatar_modal = false;

    // Campos para crear/editar
    public ?int $editing_creyente_id = null;
    public ?int $selected_user_id = null;
    public string $creyenteName = '';
    public ?string $numero_telefono = null;
    public ?string $direccion = null;
    public ?int $genero_id = null;
    public ?int $estado_civil_id = null;
    public ?string $profesion = null;
    public ?string $fecha_nacimiento = null;
    public ?string $fecha_conversion = null;
    public ?int $invitador_id = null;
    public ?bool $viene_otra_iglesia = null;
    public ?bool $bautizado = null;
    public $avatar;
    public $newAvatar;
    public bool $continuarCreando = false;

    // Cabeceras de la tabla
    public array $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'avatar', 'label' => 'Avatar', 'class' => 'w-1'],
        ['key' => 'name', 'label' => 'Nombre del Creyente', 'class' => 'text-black dark:text-white'],
        ['key' => 'numero_telefono', 'label' => 'Numero de Telefono', 'class' => 'text-black dark:text-white'],
        ['key' => 'direccion', 'label' => 'Direccion', 'class' => 'text-black dark:text-white'],
        ['key' => 'genero_id', 'label' => 'Genero', 'class' => 'text-black dark:text-white'],
        ['key' => 'estado_civil_id', 'label' => 'Estado Civil', 'class' => 'text-black dark:text-white'],
        ['key' => 'profesion', 'label' => 'Profesion', 'class' => 'text-black dark:text-white'],
        ['key' => 'edad', 'label' => 'Edad', 'class' => 'text-black dark:text-white'],
        ['key' => 'viene_otra_iglesia', 'label' => 'Viene de otra iglesia', 'class' => 'text-black dark:text-white'],
        ['key' => 'bautizado', 'label' => 'Bautizado', 'class' => 'text-black dark:text-white'],
        ['key' => 'fecha_conversion', 'label' => 'Fecha de Conversion', 'class' => 'text-black dark:text-white'],
        ['key' => 'invitador_id', 'label' => 'Invitador', 'class' => 'text-black dark:text-white'],
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
    }

    /**
     * Abre el modal de crear usuario (limpia variables)
     */
    public function openCreateModal()
    {
        $this->reset([
            'creyenteName',
            'numero_telefono',
            'direccion',
            'genero_id',
            'estado_civil_id',
            'profesion',
            'viene_otra_iglesia',
            'bautizado',
            'avatar',
            'newAvatar',
            'fecha_conversion',
            'fecha_nacimiento',
            'invitador_id',
        ]);
        $this->create_creyente_modal = true;
    }

    /**
     * Crea un nuevo usuario con roles y/o permisos
     */
    public function createCreyente()
    {
        $this->validate([
            'creyenteName'    => 'required|string|unique:users,name',
            'numero_telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'genero_id' => 'nullable|exists:generos,id',
            'estado_civil_id' => 'nullable|exists:estados_civiles,id',
            'profesion' => 'nullable|string',
            'newAvatar' => 'nullable|image|max:1024',
            'viene_otra_iglesia' => 'nullable|boolean',
            'bautizado' => 'nullable|boolean',
            'fecha_nacimiento' => 'nullable|string',
            'fecha_conversion' => 'nullable|string',
            'invitador_id' => 'nullable|exists:users,id',
            // 'userPassword_confirmation' => 'required_with:userPassword|same:userPassword|min:6'
        ]);

        // Creamos el usuario
        $user = User::create([
            'name'     => $this->creyenteName,
            'username'    => strtolower($this->creyenteName),
            'email'    => strtolower($this->creyenteName) . '@arca-del-espiritu-santo.com',
            'numero_telefono' => $this->numero_telefono,
            'direccion' => $this->direccion,
            'genero_id' => $this->genero_id,
            'estado_civil_id' => $this->estado_civil_id,
            'profesion' => $this->profesion,
            'viene_otra_iglesia' => $this->viene_otra_iglesia,
            'bautizado' => $this->bautizado,
            'fecha_nacimiento' => $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->format('Y-m-d') : null,
            'fecha_conversion' => $this->fecha_conversion ? Carbon::parse($this->fecha_conversion)->format('Y-m-d') : null,
            'invitador_id' => $this->invitador_id,
        ]);

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

        $user->assignRole('creyente');

        if (!$this->continuarCreando) {
            $this->reset([
                'create_creyente_modal',
                'creyenteName',
                'numero_telefono',
                'direccion',
                'genero_id',
                'estado_civil_id',
                'profesion',
                'fecha_nacimiento',
                'fecha_conversion',
                'invitador_id',
                'viene_otra_iglesia',
                'bautizado',
            ]);
        } else {
            $this->reset([
                'creyenteName',
                'numero_telefono',
                'direccion',
                'genero_id',
                'estado_civil_id',
                'profesion',
                'viene_otra_iglesia',
                'bautizado',
                'fecha_nacimiento',
                'fecha_conversion',
                'invitador_id'
            ]);
        }

        $this->success('Creyente creado con éxito!');
    }

    /**
     * Abre el modal de edición de un usuario existente
     */
    public function editCreyente(int $userId)
    {
        $user = User::findOrFail($userId);

        $this->editing_creyente_id = $user->id;
        $this->creyenteName = $user->name;
        $this->numero_telefono = $user->numero_telefono ?? null;
        $this->direccion = $user->direccion ?? null;
        $this->genero_id = $user->genero_id ?? null;
        $this->estado_civil_id = $user->estado_civil_id ?? null;
        $this->profesion = $user->profesion ?? null;
        $this->viene_otra_iglesia = $user->viene_otra_iglesia ?? null;
        $this->bautizado = $user->bautizado ?? null;
        $this->fecha_nacimiento = $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : null;
        $this->fecha_conversion = $user->fecha_conversion ? $user->fecha_conversion->format('Y-m-d') : null;
        $this->invitador_id = $user->invitador_id ?? null;

        $this->edit_creyente_modal = true;
    }

    /**
     * Actualiza datos del usuario
     */
    public function updateCreyente()
    {
        $this->validate([
            'creyenteName'    => 'required|string',
            'numero_telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'genero_id' => 'nullable|exists:generos,id',
            'estado_civil_id' => 'nullable|exists:estados_civiles,id',
            'profesion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|string',
            'fecha_conversion' => 'nullable|string',
            'invitador_id' => 'nullable|exists:users,id',
            'viene_otra_iglesia' => 'nullable|boolean',
            'bautizado' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($this->editing_creyente_id);

        $data = [
            'name'  => $this->creyenteName,
            'username' => strtolower($this->creyenteName),
            'email' => strtolower($this->creyenteName) . '@arca-del-espiritu-santo.com',
            'numero_telefono' => $this->numero_telefono,
            'direccion' => $this->direccion,
            'genero_id' => $this->genero_id,
            'estado_civil_id' => $this->estado_civil_id,
            'profesion' => $this->profesion,
            'viene_otra_iglesia' => $this->viene_otra_iglesia,
            'bautizado' => $this->bautizado,
            'fecha_nacimiento' => $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->format('Y-m-d') : null,
            'fecha_conversion' => $this->fecha_conversion ? Carbon::parse($this->fecha_conversion)->format('Y-m-d') : null,
            'invitador_id' => $this->invitador_id,
        ];

        // Si hay password, se actualiza
        if (!empty($this->userPassword)) {
            $data['password'] = Hash::make($this->userPassword);
        }

        $user->update($data);

        $this->reset([
            'edit_creyente_modal',
            'editing_creyente_id',
            'creyenteName',
            'numero_telefono',
            'direccion',
            'genero_id',
            'estado_civil_id',
            'profesion',
            'viene_otra_iglesia',
            'bautizado',
            'fecha_nacimiento',
            'fecha_conversion',
            'invitador_id',
        ]);

        $this->toast(
            type: 'success',
            title: 'Actualizado',
            description: 'Creyente Actualizado Con Éxito',
            icon: 'o-information-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000,
        );
    }

    /**
     * Elimina un usuario
     */
    public function deleteCreyente(int $userId)
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
    public function viewCreyente(string $userId)
    {
        return redirect()->route('users.show', $userId);
    }

    public function openUpdateAvatarModal(int $userId)
    {
        $this->selected_user_id = $userId;
        $this->reset('newAvatar');
        $this->update_avatar_modal = true;
    }

    public function saveAvatar()
    {
        $this->validate([
            'newAvatar' => 'image|max:1024',
        ]);

        $user = User::findOrFail($this->selected_user_id);

        $path = $this->newAvatar->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        $this->reset(['newAvatar', 'update_avatar_modal', 'selected_user_id']);

        $this->toast(
            type: 'success',
            title: 'Avatar Actualizado',
            description: 'El avatar del creyente fue actualizado con éxito',
            icon: 'o-check-circle',
            css: 'alert-success text-white text-sm',
            timeout: 3000,
        );
    }

    public function closeUpdateAvatarModal()
    {
        $this->reset(['newAvatar', 'update_avatar_modal', 'selected_user_id']);
    }

    /**
     * Renderiza la vista con la lista de usuarios
     */
    public function render()
    {
        if (auth()->user()->hasRole(['master'])) {
            $users = User::with('roles', 'permissions')
                ->where('name', 'like', '%' . $this->search . '%')
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'creyente');
                })
                ->whereRaw('(
                    SELECT COUNT(*) 
                    FROM model_has_roles 
                    WHERE model_has_roles.model_id = users.id 
                    AND model_has_roles.model_type = ?
                ) = 1', [User::class])                ->orderBy(...array_values($this->sortBy))
                ->paginate(10);
        } else {
            $users = User::with('roles', 'permissions')
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'master');
                })
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'creyente');
                })
                ->whereRaw('(
                    SELECT COUNT(*) 
                    FROM model_has_roles 
                    WHERE model_has_roles.model_id = users.id 
                    AND model_has_roles.model_type = ?
                ) = 1', [User::class])                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy(...array_values($this->sortBy))
                ->paginate(10);
        }

        $allUsers = User::all();

        $generos = Genero::all();
        $estados_civiles = EstadoCivil::all();

        return view('livewire.creyentes.v1.index', [
            'headers' => $this->headers,
            'sortBy'  => $this->sortBy,
            'users'   => $users,
            'allUsers' => $allUsers,
            'generos' => $generos,
            'estados_civiles' => $estados_civiles,
        ]);
    }
}
