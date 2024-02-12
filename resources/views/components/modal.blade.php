@props(['name', 'title'])

<div x-data="{ 
        isShowing : false , 
        name : '{{ $name }}',
    }" 
    x-show="isShowing"
    x-on:open-modal.window="isShowing = ($event.detail.name === name)" 
    x-on:close-modal.window="isShowing = false"
    x-on:keydown.escape.window="isShowing = false"
    x-transition.duration
    style="display:none;" 
    class="fixed z-50 inset-0 h-screen flex items-center justify-center">
    
    {{-- Gray Background --}}
    <!-- <div x-on:click="isShowing = false" class="fixed inset-0 bg-gray-500 opacity-40"></div> -->

    {{-- Modal Body --}}
    <div x-on:click.outside="isShowing = false" class="w-full z-9999 sm:max-w-md mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex justify-between">
            <div class="text-xl text-gray-800">{{ $title }}</div>
            <button x-on:click="$dispatch('close-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="p-4">
            {{ $body }}
        </div>
    </div>
</div>