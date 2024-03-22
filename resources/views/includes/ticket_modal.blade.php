{{-- {{ dd(json_decode($newTickets)) }} --}}
{{-- NOT USED NOW --}}


<x-bladewind::button onclick="showModal('form-mode-ajax')" class="p-3 bg-gray-800">
   Buy Ticket
</x-bladewind.button>

<x-bladewind::modal
    backdrop_can_close="false"
    name="form-mode-ajax"
    center_action_buttons="true"
    ok_button_action="saveProfileAjax()"
    ok_button_label="Pay Now"
    close_after_action="false">

    @php
        // Serialize the tickets into a query string
        $serializedTickets = http_build_query(['newTickets' => json_decode($newTickets)]);
    @endphp


    <button
            wire:click="generateNewTickets(2)"
            class="flex items-center justify-center text-xs font-medium h-8 px-2 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
        >Get Tickets
    </button>

    <form method="GET" action="{{ url('payment') . '?' . $serializedTickets }}" class="profile-form-ajax">
        @csrf
        <b>Select your tickets</b>
        <div class="grid grid-cols-2 gap-4 justify-center mt-6 text-black">
            <x-bladewind.input numeric="true"  required="true"  name="no_of_tickets"
            label="How many tickets?" error_message="You must select at least 1 ticket!" />

            <input type="hidden" name="amount" value="10">

            <a href="{{ url('payment') . '?' . $serializedTickets . '&amount=10&no_of_tickets=' . request('no_of_tickets') }}"
                class="p-6 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                    {{ __('PAY NOW') }}
            </a>
        </div>
    </form>

    <input id="noOfTicketsInput" name="noOfTickets" value="">
    <button wire:click.prevent="updateNewTickets(document.getElementById('noOfTicketsInput').value)">Get Tickets</button>
    <div class="tickets-list mt-4">
        @if (isset($newTickets))
            @foreach (json_decode($newTickets) as $ticket)
                <div class="ticket mt-4">
                    @foreach ($ticket->numbers as $row)
                        <div class="row flex gap-1 mt-2">
                            @foreach ($row as $cell)
                                <div class="column w-8 h-8 flex justify-center items-center">
                                    @if (is_object($cell) && property_exists($cell, 'value'))
                                        <span class="w-full h-full text-xs p-2 unchecked">{{ $cell->value }}</span>
                                    @elseif (is_int($cell) && $cell == 0)
                                        <span class="cell w-full h-full text-xs p-2 unchecked"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    <x-bladewind::processing
        name="profile-updating"
        message="Updating your profile." />

    <x-bladewind::process-complete
        name="profile-update-yes"
        process_completed_as="passed"
        button_label="Done"
        button_action="hideModal('form-mode-ajax')"
        message="Profile updated successfully." />

</x-bladewind::modal>

@push('scripts')
    <script>
        // Clear the input field after updating the tickets
        Livewire.on('ticketsUpdated', function () {
            document.getElementById('noOfTicketsInput').value = '';
        });


        saveProfileAjax = () => {
            if(validateForm('.profile-form-ajax')){
                // show process indicator while you make your ajax call
                alert('validate success');
                unhide('.profile-updating');
                hide('.profile-form-ajax');
                hideModalActionButtons('form-mode-ajax');
                // make the call
                makeAjaxCall(serialize('.profile-form-ajax'));
            } else {
                return false;
            }
        }

        makeAjaxCall = (formData) => {
            // this is a dummy function but your real function
            // will make a call and post all the data
            setTimeout(() => {
                // do these when your ajax call is done saving your data
                hide('.profile-updating');
                unhide('.profile-update-yes')
            }, 1000);
        }
    </script>
@endpush
