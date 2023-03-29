@props(['disabled' => false, 'errors', ])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => ' ']) !!}>

@error($attributes['name'])
<small class="text-red-600"></small>
@enderror