<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                
                <div x-data="{ name: $persist('') }">
                    <button @click="name = 'Link'">Set name to Link</button>
                    <button @click="name = 'Zelda'">Set name to Zelda</button>
                    <div x-text='`Selected name: ${name}`'></div>
                </div>

                <livewire:numberz />
                <livewire:tickets />
            </div>
        </div>
    </div>
</x-app-layout>
