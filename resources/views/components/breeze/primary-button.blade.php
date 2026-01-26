<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary focus-ring']) }}>
    {{ $slot }}
</button>
