<div>
    @php use Carbon\Carbon; @endphp
    <!-- Claims Table -->
    <div class="container mx-auto divide-y divide-gray-400 divide-dotted">
        <table class="table-auto text-sm w-full">
            <thead>
                <tr class="bg-gray-400 text-white p-4">
                    <th class="py-2 text-gray-900">ID</th>
                    <th class="py-2">Prize</th>
                    <th class="py-2">Ticket</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Comment</th>
                    <th class="py-2">Claimed At</th>
                    <th class="py-2">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-700 divide-dotted divide-purple-500">
                @foreach($claimsz as $claim)
                    <tr class="">
                        <td class="py-2 text-gray-900"> {{ $claim->claim_id }} </td>
                        <td class="py-2"> {{ $claim->game_prize_id }} </td>
                        <td class="py-2"> {{ $claim->ticket_id }} </td>
                        <td class="py-2"> {{ $claim->status }} </td>
                        <td class="py-2"> {{ $claim->comment }} </td>
                        <td class="py-2"> {{ Carbon::now()->diffForHumans($claim->created_at) }} </td>
                        <td class="py-2">
                            <button 
                                x-data
                                wire:click="updateSelectedClaimWithDetails({{ $claim->claim_id }})"
                                @click="$dispatch('open-modal', { name: 'claim-modal' })"
                                class="text-black bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                            >
                                {{ __('View Claim') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- end Claims Table -->
    

    {{-- Modal Dialog --}}
    <x-modal name="claim-modal" title="Claims" claim_id="55555">
        <x-slot:body>
            <section class="px-8 bg-white dark:bg-gray-900">
                <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="claimPrize()" x-on:received-claim.window="isShowing = false">
                        @csrf
                        {{ dd($selectedClaimWithDetails) }}
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ms-4"> {{ __('Claim Prize') }} </x-button>
                        </div>
                        <div class="hidden" wire:loading> Claiming prize... </div>
                    </form>
                </div>
            </section>
        </x-slot>
    </x-modal>
    {{-- end Modal Dialog --}}
</div>
