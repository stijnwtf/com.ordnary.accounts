<x-filament-panels::page>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold tracking-tight md:text-3xl">
            {{ __('Numblio SSO Management') }}
        </h1>
    </div>

    <div class="mb-6">
        <p class="text-gray-500 dark:text-gray-400">
            {{ __('Welcome to the Numblio SSO management dashboard. Here you can manage your SSO clients, users, and monitor system activity.') }}
        </p>
    </div>

    @if (count($this->getWidgets()))
        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :widgets="$this->getWidgets()"
        />
    @endif
</x-filament-panels::page> 