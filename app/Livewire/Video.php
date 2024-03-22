<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Events\MakeAgoraCall;

class Video extends Component
{
    public $user;
    public $allUsers;

    public $callPlaced;
    public $client;
    public $localStream;
    public $mutedAudio;
    public $mutedVideo;
    public $userOnlineChannel;
    public $onlineUsers = [];
    public $incomingCall;
    public $incomingCaller;
    public $agoraChannel;

    public function mount() {
        $this->user = Auth::user();
        $this->allUsers = User::all();

        $this->callPlaced = false;
        $this->client = null;
        $this->localStream = null;
        $this->mutedAudio = false;
        $this->mutedVideo = false;
        $this->userOnlineChannel = null;
        $this->onlineUsers = $this->getLoggedInUsers();
        $this->incomingCall = false;
        $this->incomingCaller = "";
        $this->agoraChannel = null;
    }


    function getLoggedInUsers() {
        return DB::table(config('session.table'))
            ->distinct()
            ->select(['users.id', 'users.name', 'users.email'])
            ->whereNotNull('user_id')
            ->leftJoin('users', config('session.table') . '.user_id', '=', 'users.id')
            ->get();
    }


    protected function token(Request $request)
    {

        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->name;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        return $token;
    }

    public function callUser(Request $request)
    {

        $data['userToCall'] = $request->user_to_call;
        $data['channelName'] = $request->channel_name;
        $data['from'] = Auth::id();

        broadcast(new MakeAgoraCall($data))->toOthers();
    }

    public function render()
    {
        return view('livewire.video');
    }
}
