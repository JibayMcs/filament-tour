<div class="flex items-center">
    @if(isset($icon))
        <x-filament::icon
            icon="{{$icon}}"
            @class([
                'h-5 w-5',
                match ($iconColor) {
                    'gray' => 'text-gray-600 ring-gray-600/10 dark:text-gray-400 dark:ring-gray-400/20',
                    default => 'text-custom-600 ring-custom-600/10 dark:text-custom-400 dark:ring-custom-400/30',
                },
            ])

            @style([
                \Filament\Support\get_color_css_variables(
                    $iconColor,
                    shades: [
                        50,
                        300,
                        400,
                        ...$icon ? [500] : [],
                        600,
                        700,
                    ]
                ) => $iconColor !== 'gray',
                'margin-right:10px'
            ])
        />
    @endif
    {{$title}}
</div>
