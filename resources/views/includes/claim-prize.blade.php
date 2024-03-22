
{{-- @dd($tickets) --}}
<button
    x-data
    @click="$dispatch('open-modal', { name: 'claim' })"
    wire:click="updateTicketSelected({{ $ticket->id }})"
    class="bg-gray-800 text-white active:bg-pink-600 font-bold text-xs px-2 py-1 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
>
    {{ __('Claim Prize') }}
</button>


 {{-- Modal Dialog --}}
 <x-modal name="claim" title="Claims" ticketId="">
    <x-slot:body>
        <section class="px-8 bg-white dark:bg-gray-900">
            <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                <x-validation-errors class="mb-4" />
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ session('status') }}
                    </div>
                @endif
                <form wire:submit="claimPrize()" x-on:received-claim.window="isShowing = false" class="flex flex-col items-center justify-center">
                    @csrf
                    <select
                        id="select-prize"
                        name="prize"
                        wire:model="prizeSelected"
                        wire:loading.remove
                        class="block mt-1 w-full rounded-md"
                        required
                        >
                        <option value=""> Select Prize </option>
                        @foreach ($remainingPrizes as $prize)
                            <option value="{{ $prize->game_prize_id }}" @selected(old($prize->prize_name) == $prize->prize_name)>
                                {{ $prize->prize_name }}
                            </option>
                        @endforeach
                    </select>

                    <div wire:loading.remove class="flex items-center justify-end mt-4">
                        <x-button class="ms-4"> {{ __('Claim Prize') }} </x-button>
                    </div>
                    <div class="hidden mt-4" wire:loading>
                        <svg aria-hidden="true" class="inline w-10 h-10 text-gray-400 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </form>
            </div>
        </section>
    </x-slot>
</x-modal>

{{-- end Modal Dialog --}}
