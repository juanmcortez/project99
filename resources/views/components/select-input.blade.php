@props(['options' => [], 'selected' => null, 'placeholder' => '— Select —'])

<select {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 disabled:bg-gray-100']) }}>
    <option value="">{{ $placeholder }}</option>
    @foreach ($options as $option)
        @php
            $value = $option instanceof \BackedEnum ? $option->value : $option['value'];
            $label = $option instanceof \BackedEnum ? $option->label() : $option['label'];
        @endphp
        <option value="{{ $value }}" @selected($selected === $value)>{{ $label }}</option>
    @endforeach
</select>
