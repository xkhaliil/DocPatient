@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input text-slate-900 placeholder:text-slate-400']) }}>
