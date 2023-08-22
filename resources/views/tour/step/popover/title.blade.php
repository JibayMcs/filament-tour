<div class="flex items-center">
    @if(isset($icon))
        @php
            $color = match($iconColor) {
                'primary' => 'text-primary-600',
                'danger' => 'text-danger-600',
                'success' => 'text-success-600',
                'warning' => 'text-warning-600',
                default => 'text-gray-950',
            }
        @endphp
        {{ svg($icon, "h-6 w-6 mr-2 $color") }}
    @endif
    {{$title}}
</div>
