<div>
    <div x-data="{ open1: false }">
        <button @click="open1 = true" class="flex items-center bg-gray-800 text-white active:bg-pink-600 font-bold text-xs px-2 py-1 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button"
            >Details
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open1">
            <div
                @click.away="open1 = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="shadow-md p-5 rounded max-w-sm ml-2 bg-gray-200"
            >
            <table>
                @foreach($claims as $claim)
                    <tr class="grid grid-cols-2 border-b-1" style="border-bottom: 1px dotted #b9b9b9; padding-bottom: 4px; margin-bottom: 4px;">

                        <td>Claim ID : </td><td class="claim-id">{{ $claim->id }}</td>
                        @php
                            // dd($claim);
                            switch ($claim->game_prize_id) {
                                case '1':
                                    echo '<td>Prize : </td><td>Full House</td>';
                                    break;

                                case '2':
                                    echo '<td>Prize : </td><td>Top Line</td>';
                                    break;

                                case '3':
                                    echo '<td>Prize : </td><td>Bottom Line</td>';
                                    break;

                                case '4':
                                    echo '<td>Prize : </td><td>Bottom Line</td>';
                                    break;

                                case '5':
                                    echo '<td>Prize : </td><td>Quick Five</td>';
                                    break;

                                case '6':
                                    echo '<td>Prize : </td><td>Ticket Corner</td>';
                                    break;

                                case '7':
                                    echo '<td>Prize : </td><td>Kings Corner</td>';
                                    break;

                                case '8':
                                    echo '<td>Prize : </td><td>Queens Corner</td>';
                                    break;

                                default:
                                    echo '<td>Prize : </td><td> - </td>';
                                    break;
                            }
                        @endphp
                        <td>Status : </td><td><span
                            @if ($claim->status == "Open") class="text-green-500" @endif
                            @if ($claim->status == "Winner") class="text-green-500" @endif
                            @if ($claim->status == "Boggy") class="text-red-500" @endif
                            >{{ strtoupper($claim->status) }}</span></td>
                        <td>Remarks : </td><td><span>{{ $claim->remarks }}</span></td>
                    </tr>
                @endforeach
            </table>
            </div>
        </div>
    </div>
</div>
