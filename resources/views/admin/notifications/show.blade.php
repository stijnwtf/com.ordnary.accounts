@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Notificatie Details
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-bell mr-1.5 text-gray-400"></i>
                    Details van notificatie #{{ $notification->id }}
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            @if($notification->isRead())
            <form action="{{ route('notifications.mark-as-unread', $notification) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-envelope mr-2"></i>
                    Markeer als ongelezen
                </button>
            </form>
            @else
            <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-envelope-open mr-2"></i>
                    Markeer als gelezen
                </button>
            </form>
            @endif
            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze notificatie wilt verwijderen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Verwijderen
                </button>
            </form>
            <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar overzicht
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <span class="sr-only">Sluiten</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Notification Details Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center">
                @php
                    $typeColors = [
                        'info' => 'bg-blue-100 text-blue-600',
                        'success' => 'bg-green-100 text-green-600',
                        'warning' => 'bg-yellow-100 text-yellow-600',
                        'error' => 'bg-red-100 text-red-600',
                    ];
                    $bgColor = $typeColors[$notification->type] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <div class="h-12 w-12 rounded-full {{ $bgColor }} flex items-center justify-center mr-4">
                    <i class="fas {{ $notification->icon }}"></i>
                </div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $notification->title }}
                        @if($notification->isRead())
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-check text-xs mr-1"></i> Gelezen
                        </span>
                        @else
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Ongelezen
                        </span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                        {{ $notification->created_at->format('d-m-Y H:i:s') }} ({{ $notification->created_at->diffForHumans() }})
                    </p>
                </div>
                <div class="ml-auto">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $bgColor }}">
                        {{ ucfirst($notification->type) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Notificatie ID
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $notification->id }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Titel
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $notification->title }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Bericht
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $notification->message }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Type
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }}">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Icon
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <i class="fas {{ $notification->icon }} {{ $bgColor }} p-2 rounded"></i>
                        <span class="ml-2">{{ $notification->icon }}</span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Link
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($notification->link)
                        <a href="{{ $notification->link }}" target="_blank" class="text-numblio-blue hover:text-numblio-lightblue">
                            {{ $notification->link }} <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                        @else
                        <span class="text-gray-500">Geen link</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Status
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($notification->isRead())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-check text-xs mr-1"></i> Gelezen 
                            @if(is_object($notification->read_at) && method_exists($notification->read_at, 'format'))
                                op {{ $notification->read_at->format('d-m-Y H:i:s') }}
                            @else
                                (datum onbekend)
                            @endif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Ongelezen
                        </span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Aangemaakt op
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $notification->created_at->format('d-m-Y H:i:s') }}
                        <span class="text-gray-500">({{ $notification->created_at->diffForHumans() }})</span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Laatst bijgewerkt
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $notification->updated_at->format('d-m-Y H:i:s') }}
                        <span class="text-gray-500">({{ $notification->updated_at->diffForHumans() }})</span>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                @if($notification->link)
                <a href="{{ $notification->link }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Ga naar link
                </a>
                @endif
                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze notificatie wilt verwijderen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 