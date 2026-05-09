<x-app-layout title="Register | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('welcome') }}" class="tt-brand">TirahanTech</a>
            <div class="tt-actions">
                <a href="{{ route('welcome') }}" class="tt-btn">Welcome</a>
                <a href="{{ route('login') }}" class="tt-btn">Log in</a>
            </div>
        </header>

        <main class="tt-main">
            <div class="tt-main-grid tt-main-grid--auth">
                <section class="tt-copy">
                    <div class="tt-eyebrow">Create your access</div>
                    <div>
                        <h1 class="tt-title tt-title-medium">Create your client account and start browsing properties.</h1>
                        <p class="tt-lead">Register as a client to search homes, schedule viewings, and submit offers.</p>
                    </div>

                    <div class="tt-grid">
                        <article class="tt-card">
                            <h3 class="tt-card-title">Client account</h3>
                            <p class="tt-card-copy">Search homes, schedule viewings, send inquiries, and submit purchase offers.</p>
                        </article>
                    </div>
                </section>

                <section class="tt-panel">
                    <div class="tt-label">Registration</div>
                    <h2 class="tt-section-title">Create account</h2>
                    <p class="tt-sublead">Your account will open directly into the dashboard after sign-up.</p>

                    @if ($errors->any())
                        <div class="tt-alert tt-alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}" class="tt-form-grid">
                        @csrf

                        <div class="tt-field tt-span-2">
                            <label for="name">Full name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="tt-input" placeholder="Juan Dela Cruz">
                        </div>

                        <div class="tt-field tt-span-2">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="tt-input" placeholder="you@example.com">
                        </div>

                        <div class="tt-field">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="tt-input" placeholder="+63 912 345 6789">
                        </div>

                        <div class="tt-field tt-span-2">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3" class="tt-textarea" placeholder="City, province, street">{{ old('address') }}</textarea>
                        </div>

                        <div class="tt-field">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" required class="tt-input" placeholder="At least 8 characters">
                        </div>

                        <div class="tt-field">
                            <label for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="tt-input" placeholder="Repeat your password">
                        </div>

                        <div class="tt-span-2">
                            <button type="submit" class="tt-btn tt-btn-primary tt-btn-submit">Create account</button>
                        </div>
                    </form>

                    <p class="tt-footer-link">
                        Already registered?
                        <a href="{{ route('login') }}">Log in here</a>
                    </p>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
