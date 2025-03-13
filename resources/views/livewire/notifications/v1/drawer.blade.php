<div class="relative">
    <x-button label="Notificaciones ({{ count($notifications) }})" icon="o-bell" class="btn-ghost btn-sm"
        wire:click="toggleDrawer" />

    @if ($showDrawer)
        <div class="absolute top-12 right-0 w-80 bg-white dark:bg-slate-900 shadow-lg rounded-lg p-4"
            wire:click.away="$set('showDrawer', false)">
            <h2 class="text-lg font-bold">Notificaciones</h2>

            @if (!$notifications->isEmpty())
                <button class="text-blue-500 text-sm underline" wire:click="markAllAsRead">
                    Marcar todas como leídas
                </button>
            @endif

            <div class="max-h-96 overflow-y-auto">
                @if ($notifications->isEmpty())
                    <p class="text-gray-500">No tienes notificaciones.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($notifications as $notification)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <strong>{{ $notification->data['title'] ?? 'Notificación' }}</strong>
                                    <p class="text-sm text-gray-600">{{ $notification->created_at->diffForHumans() }}</p>
                                    <p class="text-sm">{{ $notification->data['details'] ?? '' }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 text-sm"
                                        wire:click="viewNotification('{{ $notification->id }}')">Ver</button>
                                    <button class="text-green-500 text-sm"
                                        wire:click="markAsRead('{{ $notification->id }}')">✓</button>
                                    <button class="text-red-500 text-sm"
                                        wire:click="deleteNotification('{{ $notification->id }}')">✗</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif
</div>
