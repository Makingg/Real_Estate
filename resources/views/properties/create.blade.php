<x-app-layout title="Create Listing | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>

        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">Listing Module</div>
                    <h1 class="tt-title tt-title-medium">Create property listing</h1>
                    <p class="tt-lead">Add a new property to the system and prepare it for review or publication.</p>
                </div>

                @if ($errors->any())
                    <div class="tt-alert tt-alert-error">{{ $errors->first() }}</div>
                @endif

                <section class="tt-panel">
                    <form method="POST" action="{{ route('properties.store') }}">
                        @csrf
                        @include('properties._form')
                    </form>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
