<x-app-layout title="Inquiries | TirahanTech">
    <div class="tt-shell tt-page">
        <header class="tt-topbar">
            <a href="{{ route('dashboard') }}" class="tt-brand">TirahanTech</a>
            <x-app-nav />
        </header>
        <main class="tt-main">
            <div class="tt-copy" style="width: 100%;">
                <div>
                    <div class="tt-eyebrow tt-eyebrow-cyan">Inquiry Module</div>
                    <h1 class="tt-title tt-title-medium">Property inquiries</h1>
                    <p class="tt-lead">Monitor buyer questions, listing interest, and response progress.</p>
                </div>
                @if (session('status'))
                    <div class="tt-alert tt-alert-success">{{ session('status') }}</div>
                @endif
                <section class="tt-panel">
                    <div class="tt-table-wrap">
                        <table class="tt-table">
                            <thead>
                                <tr>
                                    <th>Inquiry</th>
                                    <th>Property</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inquiries as $inquiry)
                                    <tr>
                                        <td>
                                            <strong>{{ $inquiry->subject ?: 'Property inquiry' }}</strong>
                                            {{ $inquiry->message }}
                                        </td>
                                        <td>{{ $inquiry->property?->title ?? 'N/A' }}</td>
                                        <td>Client: {{ $inquiry->client?->name ?? 'N/A' }}<br>Agent: {{ $inquiry->agent?->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="tt-badge">{{ ucfirst($inquiry->status) }}</span>
                                            @if (auth()->user()->isAdmin() || auth()->id() === $inquiry->agent_id)
                                                <form method="POST" action="{{ route('inquiries.update', $inquiry) }}" class="tt-inline-form" style="margin-top: 10px;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="tt-select-sm">
                                                        @foreach (['new', 'responded', 'closed'] as $status)
                                                            <option value="{{ $status }}" @selected($inquiry->status === $status)>{{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="tt-btn tt-btn-primary">Update</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"><p class="tt-empty">No inquiries available yet.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tt-pager">{{ $inquiries->links() }}</div>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
