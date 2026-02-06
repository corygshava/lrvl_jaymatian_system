<x-layouts.mainlayout>
    @auth
        @php
            header('Location: ./dashboard');
        @endphp
    @else
        @php
            header('Location: ./login');
        @endphp
    @endauth
</x-layouts.mainlayout>
