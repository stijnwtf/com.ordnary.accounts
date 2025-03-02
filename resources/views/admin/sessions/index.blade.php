@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Sessies
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-key mr-1.5 text-gray-400"></i>
                    Beheer actieve gebruikerssessies in het SSO platform
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('sessions.destroy-expired') }}" method="POST" onsubmit="return confirm('Weet je zeker dat je alle verlopen sessies wilt verwijderen?');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-broom mr-2"></i>
                    Verlopen sessies opruimen
                </button>
            </form>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-100 shadow-sm animate-fadeIn">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Filters
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Filter sessies op gebruiker of client
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('sessions.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
                <div class="w-full sm:w-1/3">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Gebruiker
                    </label>
                    <select id="user_id" name="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-numblio-blue focus:border-numblio-blue sm:text-sm rounded-md">
                        <option value="">Alle gebruikers</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-1/3">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Client
                    </label>
                    <select id="client_id" name="client_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-numblio-blue focus:border-numblio-blue sm:text-sm rounded-md">
                        <option value="">Alle clients</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->client_id }}" {{ request('client_id') == $client->client_id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-1/3">
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                        Sortering
                    </label>
                    <select id="sort_order" name="sort_order" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-numblio-blue focus:border-numblio-blue sm:text-sm rounded-md">
                        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Oudste eerst</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                        <i class="fas fa-filter mr-2"></i>
                        Filteren
                    </button>
                    @if(request('user_id') || request('client_id') || request('sort_order'))
                    <a href="{{ route('sessions.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Sessions Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Actieve sessies
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Overzicht van alle actieve gebruikerssessies
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">
                        Totaal: <span class="font-medium">{{ $sessions->total() }}</span> sessies
                    </span>
                </div>
            </div>
        </div>

        @if($sessions->count() > 0)
        <form action="{{ route('sessions.bulk-destroy') }}" method="POST" id="bulk-form">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 text-numblio-blue focus:ring-numblio-blue border-gray-300 rounded">
                                    <label for="select-all" class="sr-only">Selecteer alle</label>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gebruiker
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Token
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Verloopt op
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acties</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sessions as $session)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox" name="session_ids[]" value="{{ $session->id }}" class="session-checkbox h-4 w-4 text-numblio-blue focus:ring-numblio-blue border-gray-300 rounded">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium">{{ $session->user ? substr($session->user->name, 0, 1) : '?' }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $session->user ? $session->user->name : 'Onbekend' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $session->user ? $session->user->email : 'Onbekend' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $session->client ? $session->client->name : 'Onbekend' }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $session->client_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 font-mono">{{ substr($session->token, 0, 15) }}...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $session->expires_at ? $session->expires_at->format('d-m-Y') : 'Onbekend' }}</div>
                                <div class="text-xs text-gray-500">{{ $session->expires_at ? $session->expires_at->format('H:i') : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!$session->expires_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Onbekend
                                </span>
                                @elseif($session->expires_at->isPast())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Verlopen
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Actief
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('sessions.show', $session) }}" class="text-numblio-blue hover:text-numblio-lightblue mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('sessions.destroy', $session) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze sessie wilt beëindigen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6 flex justify-between items-center">
                <div>
                    <button type="submit" id="bulk-delete-button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 opacity-50 cursor-not-allowed" disabled>
                        <i class="fas fa-trash mr-2"></i>
                        Geselecteerde sessies beëindigen
                    </button>
                </div>
                <div>
                    {{ $sessions->withQueryString()->links() }}
                </div>
            </div>
        </form>
        @else
        <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
            <p>Geen actieve sessies gevonden.</p>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const sessionCheckboxes = document.querySelectorAll('.session-checkbox');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');
        const bulkForm = document.getElementById('bulk-form');

        // Functie om de bulk delete knop te updaten
        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.session-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
                bulkDeleteButton.disabled = false;
                bulkDeleteButton.innerHTML = `<i class="fas fa-trash mr-2"></i> ${checkedCount} geselecteerde sessie(s) beëindigen`;
            } else {
                bulkDeleteButton.classList.add('opacity-50', 'cursor-not-allowed');
                bulkDeleteButton.disabled = true;
                bulkDeleteButton.innerHTML = `<i class="fas fa-trash mr-2"></i> Geselecteerde sessies beëindigen`;
            }
        }

        // Selecteer alle checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                sessionCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateBulkDeleteButton();
            });
        }

        // Individuele checkboxes
        sessionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update "select all" checkbox
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // Check if all checkboxes are checked
                    const allChecked = Array.from(sessionCheckboxes).every(c => c.checked);
                    selectAllCheckbox.checked = allChecked;
                }
                updateBulkDeleteButton();
            });
        });

        // Bulk delete form submit
        if (bulkForm) {
            bulkForm.addEventListener('submit', function(e) {
                const checkedCount = document.querySelectorAll('.session-checkbox:checked').length;
                if (checkedCount === 0) {
                    e.preventDefault();
                    return false;
                }
                
                return confirm(`Weet je zeker dat je ${checkedCount} sessie(s) wilt beëindigen?`);
            });
        }

        // Initial update
        updateBulkDeleteButton();
    });
</script>
@endsection 