<div class="driver-help-button" x-on:click="Livewire.emit('driverjs::open-highlight', {highlight: '{{$id}}' })">
    @php
        $color = match($iconColor) {
            'primary' => 'text-primary-600',
            'danger' => 'text-danger-600',
            'success' => 'text-success-600',
            'warning' => 'text-warning-600',
            default => 'text-gray-950',
        }
    @endphp
    {{ svg($icon, "h-4 w-4 $color") }}
</div>
