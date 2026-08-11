@if (session('status'))
    <div {{ $attributes->merge(['class' => 'mb-4 rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800']) }}>
        @if (session('status') === 'verification-link-sent')
            A new verification link has been sent to your email address.
        @else
            {{ session('status') }}
        @endif
    </div>
@endif
