@props(['messages' => []])

@if ($messages)
    <ul class="mt-2 text-sm text-danger fw-medium">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
