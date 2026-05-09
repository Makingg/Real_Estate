<x-app-layout title="Viewings | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>
        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow">Viewing Scheduler</div>
                    <h1 class="tt-title tt-title-medium">Viewing appointments</h1>
                    <p class="tt-lead">Track scheduled property visits and confirm, complete, or cancel appointments.</p>
                </div>
                @if (session('status'))
                    <div class="tt-alert tt-alert-success">{{ session('status') }}</div>
                @endif
                <section class="tt-panel">
                    <div class="tt-table-wrap">
                        <table class="tt-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Schedule</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($viewings as $viewing)
                                    <tr>
                                        <td><strong>{{ $viewing->property?->title ?? 'N/A' }}</strong>{{ $viewing->notes ?: 'No notes provided.' }}</td>
                                        <td>{{ $viewing->scheduled_at?->format('M d, Y h:i A') }}</td>
                                        <td>Client: {{ $viewing->client?->name ?? 'N/A' }}<br>Agent: {{ $viewing->agent?->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="tt-badge">{{ ucfirst($viewing->status) }}</span>
                                            @if (auth()->user()->isAdmin() || auth()->id() === $viewing->agent_id)
                                                <form method="POST" action="{{ route('viewings.update', $viewing) }}" class="tt-form" style="margin-top: 10px;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="tt-select-sm">
                                                        @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                            <option value="{{ $status }}" @selected($viewing->status === $status)>{{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input name="notes" value="{{ $viewing->notes }}" class="tt-input-sm" placeholder="Notes">
                                                    <button type="submit" class="tt-btn tt-btn-primary">Update</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"><p class="tt-empty">No viewing requests yet.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tt-pager">{{ $viewings->links() }}</div>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
