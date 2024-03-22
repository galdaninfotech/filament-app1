<div>
    <!-- <div id="users-list"></div> -->

<div class="col">
                <p id="local-player-name" class="player-name"></p>
                <div id="local-player" class="player"></div>
            </div>

    <div class="containerz flex items-start my-2">
        <!-- Streams -->
        <div class="row video-group">

            <div class="w-100"></div>
            <div class="col">
                <div id="remote-playerlist"></div>
            </div>
        </div>

        <form id="join-form" name="join-form" class="">
            <!-- UI Controls -->
            <div class="flex items-center justify-center space-x-1 text-xs">
                @if ($user->id == 1)
                    <button id="host-join" type="submit" class="rounded p-2 bg-gray-800 text-green-100 didsabled:cursor-not-allowed disabled:opacity-50">Host</button>
                @endif
                @if ($user->id > 1)
                    <button id="audience-join" type="submit" class="rounded p-2 bg-gray-800 text-green-100 didsabled:cursor-not-allowed disabled:opacity-50">Video</button>
                @endif

                <button id="mic-btn" type="button" class="w-0 h-0" disabled> </button>
                <button id="video-btn" type="button" class="w-0 h-0" disabled> </button>
            </div>
        </form>
    </div>


     <style>

.player {
    width: 200px;
    height: 280px;
    position: absolute;
    right: 0;
    top: 0;
    padding: 20px;
}

</style>


</div>
@push('scripts')
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
@endpush
