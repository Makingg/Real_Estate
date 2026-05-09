<x-app-layout title="Edit Listing | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">Listing Module</div>
                    <h1 class="tt-title tt-title-medium">Edit property listing</h1>
                    <p class="tt-lead">Update listing details, amenities, and status information.</p>
                </div>

                @if ($errors->any())
                    <div class="tt-alert tt-alert-error">{{ $errors->first() }}</div>
                @endif

                <section class="tt-panel">
                    <form method="POST" action="{{ route('properties.update', $property) }}">
                        @csrf
                        @method('PUT')
                        @include('properties._form')
                    </form>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
