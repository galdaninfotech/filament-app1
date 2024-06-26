

 {{-- Modal Dialog --}}
 <x-modal name="send" title="Claim a prize" >
    <x-slot:body>
        <section class="px-8 bg-white dark:bg-gray-900">
            <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                <x-validation-errors class="mb-4" />
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                        {{ session('status') }}
                    </div>
                @endif

                <form id="send-form" wire:submit="sendTicketToFriend()" x-on:received-send.window="isShowing = false" class="flex flex-col items-center justify-center">
                    @csrf
                    <select
                        id="select-email"
                        name="email"
                        wire:model="userIdSelected"
                        wire:loading.remove
                        class="block mt-1 w-full rounded-md"
                        required
                        >
                        <option value=""> Select Friend's Email </option>

                        {{-- {{ dd($emailsForSelectInput) }} --}}
                        @foreach ($emailsForSelectInput as $email)
                            <option value="{{ $email->id }}" @selected(old($email->email) == $email->email)>
                                {{ $email->email }}
                            </option>
                        @endforeach
                    </select>

                    <div wire:loading.remove class="flex items-center justify-end mt-4">
                        <x-button class="ms-4"> {{ __('Send Now') }} </x-button>
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
    function getTicketIdForSend(button) {
        // set wire:submit="send-ticket-id" on the form element
        const ticketId = button.getAttribute('data-ticket-id');
        const formElement =  document.getElementById('send-form');
        formElement.setAttribute('wire:submit', 'sendTicketToFriend("'+ ticketId +'")');
    }


</script>
{{-- end Modal Dialog --}}
