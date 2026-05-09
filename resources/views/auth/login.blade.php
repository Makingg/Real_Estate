<x-app-layout title="Log In | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('welcome') }}" class="tt-brand">TirahanTech</a>
            <div class="tt-actions">
                <a href="{{ route('welcome') }}" class="tt-btn">Welcome</a>
                <a href="{{ route('register') }}" class="tt-btn tt-btn-primary">Register</a>
            </div>
        </header>

        <main class="tt-main">
            <div class="tt-main-grid tt-main-grid--auth">
                <section class="tt-copy">
                    <div class="tt-eyebrow tt-eyebrow-cyan">Welcome back</div>
                    <div>
                        <h1 class="tt-title tt-title-medium">Log back in and keep every deal moving.</h1>
                        <p class="tt-lead">Access your role-based workspace to manage listings, answer inquiries, schedule viewings, and review transaction activity from one place.</p>
                    </div>

                    <div class="tt-grid tt-grid-2">
                        <article class="tt-card">
                            <h3 class="tt-card-title">Admins</h3>
                            <p class="tt-card-copy">Review platform activity, monitor users, and keep listings and transactions under control.</p>
                        </article>
                        <article class="tt-card">
                            <h3 class="tt-card-title">Agents and clients</h3>
                            <p class="tt-card-copy">Pick up where you left off with your inquiries, offers, schedules, and property updates.</p>
                        </article>
                    </div>
                </section>

                <section class="tt-panel">
                    <div class="tt-label">Account Access</div>
                    <h2 class="tt-section-title">Sign in</h2>
                    <p class="tt-sublead">Use your TirahanTech account credentials to continue to the dashboard.</p>

                    @if ($errors->any())
                        <div class="tt-alert tt-alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="tt-alert tt-alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="tt-form">
                        @csrf

                        <div class="tt-field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="tt-input" placeholder="you@example.com">
                        </div>

                        <div class="tt-field">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" required class="tt-input" placeholder="Enter your password">
                        </div>

                        <label class="tt-checkbox-row">
                            <input type="checkbox" name="remember" value="1" class="tt-checkbox">
                            Keep me signed in
                        </label>

                        <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Log in</button>
                    </form>

                    <p class="tt-footer-link">
                        Need an account?
                        <a href="{{ route('register') }}">Register here</a>
                    </p>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
