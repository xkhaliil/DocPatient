<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-secondary focus-ring']) }}>
    {{ $slot }}
</button>
