<div class="px-2 py-1">
    @if(auth()->check())
        <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
            {{ auth()->user()->username ?? auth()->user()->first_name . ' ' . auth()->user()->last_name }}
        </div>
    @endif
</div> 