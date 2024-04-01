

 {{-- Modal Dialog --}}
 <x-modal name="claim" title="Claim a prize" >
    <x-slot:body>
        <section class="px-8 bg-white dark:bg-gray-900">
            <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                <x-validation-errors class="mb-4" />
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                        {{ session('status') }}
                    </div>
                @endif

                <form id="claim-form" wire:submit="claimPrize()" x-on:received-claim.window="isShowing = false" class="flex flex-col items-center justify-center">
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

                        {{-- {{ dd($remainingPrizes) }} --}}
                        @foreach ($prizesForSelectInput as $prize)
                            <option value="{{ $prize->id }}" @selected(old($prize->name) == $prize->name)>
                                {{ $prize->name }}
                            </option>
                        @endforeach
                    </select>

                    <div wire:loading.remove class="flex items-center justify-end mt-4">
                        <x-button class="ms-4"> {{ __('Claim Now') }} </x-button>
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

<script>
    function getTicketId(button) {
        // set wire:submit="send-ticket-id" on the form element
        const ticketId = button.getAttribute('data-ticket-id');
        const formElement =  document.getElementById('claim-form');
        formElement.setAttribute('wire:submit', 'claimPrize("'+ ticketId +'")');
    }


</script>
{{-- end Modal Dialog --}}
