<x-app-layout title="Welcome | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('welcome') }}" class="tt-brand">TirahanTech</a>
            <div class="tt-actions">
                <a href="{{ route('login') }}" class="tt-btn">Log in</a>
                <a href="{{ route('register') }}" class="tt-btn tt-btn-primary">Register</a>
            </div>
        </header>

        <main class="tt-main">
            <div class="tt-main-grid tt-main-grid--welcome">
                <section class="tt-copy">
                    <div class="tt-eyebrow">Real estate operations for admins, agents, and buyers</div>
                    <div>
                        <h1 class="tt-title">Welcome home to a cleaner property workflow.</h1>
                        <p class="tt-lead">Browse listings, connect with agents, manage viewings, and move offers through one role-based platform built for TirahanTech.</p>
                    </div>

                    <div class="tt-actions">
                        <a href="{{ route('register') }}" class="tt-btn tt-btn-primary">Create an account</a>
                        <a href="{{ route('login') }}" class="tt-btn">Already have an account?</a>
                    </div>

                    <div class="tt-grid tt-grid-3">
                        <article class="tt-card">
                            <h3 class="tt-card-title">Agent</h3>
                            <p class="tt-card-copy">Publish listings, respond to buyers, and manage inquiries and schedules.</p>
                        </article>
                        <article class="tt-card">
                            <h3 class="tt-card-title">Client</h3>
                            <p class="tt-card-copy">Search homes, submit offers, and keep every conversation in one place.</p>
                        </article>
                    </div>
                </section>

                <aside class="tt-panel">
                    <div class="tt-label">Platform Snapshot</div>
                    <h2 class="tt-section-title">Everything starts here.</h2>
                    <div class="tt-stack">
                        <section class="tt-card">
                            <h3 class="tt-card-title">Public browsing for guests</h3>
                            <p class="tt-card-copy">Visitors can explore approved listings before deciding to sign in.</p>
                        </section>
                        <section class="tt-card">
                            <h3 class="tt-card-title">Instant dashboard access</h3>
                            <p class="tt-card-copy">After login or sign-up, users go straight into the system with the right permissions.</p>
                        </section>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</x-app-layout>
