@php
    $user = auth()->user();

    $links = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'roles' => ['admin', 'agent', 'client']],
        ['label' => 'Users', 'route' => 'users.index', 'roles' => ['admin']],
        ['label' => 'Listings', 'route' => 'properties.index', 'roles' => ['admin', 'agent', 'client']],
        ['label' => 'Create Listing', 'route' => 'properties.create', 'roles' => ['admin', 'agent']],
        ['label' => 'Inquiries', 'route' => 'inquiries.index', 'roles' => ['admin', 'agent', 'client']],
        ['label' => 'Viewings', 'route' => 'viewings.index', 'roles' => ['admin', 'agent', 'client']],
        ['label' => 'Offers', 'route' => 'offers.index', 'roles' => ['admin', 'agent', 'client']],
    ];
@endphp

<nav class="tt-nav">
    @foreach ($links as $link)
        @if (in_array($user->role, $link['roles'], true))
            <a href="{{ route($link['route']) }}" class="tt-nav-link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                {{ $link['label'] }}
            </a>
        @endif
    @endforeach
</nav>
