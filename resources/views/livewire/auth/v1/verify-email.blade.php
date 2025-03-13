<div class="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">
    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg text-center text-gray-800 dark:text-gray-200">
        <h2 class="text-xl font-bold">Verifica tu dirección de correo</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Te hemos enviado un enlace de verificación a tu correo. Por favor, revisa tu bandeja de entrada.
        </p>

        @if (session()->has('message'))
            <div class="mt-4 p-2 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <div class="mt-4">
            <!-- Botón para reenviar verificación con Livewire -->
            <button class="px-4 py-2 rounded-md font-medium text-white bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition"
                wire:click="resendVerificationEmail">
                Reenviar Correo de Verificación
            </button>
        </div>

        <div class="mt-2">
            <!-- Botón para cerrar sesión con Livewire -->
            <button class="btn-circle btn-ghost btn-xs dark:text-gray-200 text-gray-800" wire:click="logout" tooltip-left="Cerrar Sesión">
                <x-icon name="o-power" />
            </button>
        </div>
    </div>
</div>
