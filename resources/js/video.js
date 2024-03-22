// create Agora client
var client = AgoraRTC.createClient({
    mode: "live",
    codec: "vp8"
  });
  var localTracks = {
    videoTrack: null,
    audioTrack: null
  };
  var localTrackState = {
    videoTrackEnabled: true,
    audioTrackEnabled: true
  }
  var remoteUsers = {};
  // Agora client options
  var options = {
    appid: '8fd414b1b7b94ed894dbd5ca248fba78',
    channel: 'video_channel',
    uid: null,
    token: '007eJxTYMjxmZ1XXTQz3N0wz031SvzEIovHHRHm5ydm5bwp/anuJqXAYJGWYmJokmSYZJ5kaZKaYmFpkpKUYpqcaGRikZaUaG5x7t6r1IZARgZVjZlMjAwQCOLzMpRlpqTmxydnJOblpeYwMAAARnQi8w==',
    role: "audience" // host or audience
  };

if(userId == 1){
    document.querySelector("#host-join").addEventListener("click", function(){
        options.role = "host";
    });
}

if(userId == 2){
    document.querySelector("#audience-join").addEventListener("click", function(){
        options.role = "audience";
    });
}


document.querySelector("#join-form").addEventListener("submit", async function(e){
    e.preventDefault();
    if(userId == 1)
        document.getElementById("host-join").disabled = true;

    if(userId == 2)
        document.getElementById("audience-join").disabled = true;

    try {
    //   options.appid = document.getElementById("appid").value;
    //   options.channel = document.getElementById("channel").value;
    options.appid = '8fd414b1b7b94ed894dbd5ca248fba78';
    options.channel = 'video_channel';
      await join();
    } catch (error) {
      console.error(error);
    } finally {
        if(document.getElementById("leave"))
            document.getElementById("leave").disabled = false;
    }
});

if(document.querySelector("#leave")) {
    document.querySelector("#leave").addEventListener("click", function(e){
        leave();
    });
}


async function join() {
    // create Agora client
    client.setClientRole(options.role);
    document.getElementById("mic-btn").disabled = false;
    document.getElementById("video-btn").disabled = false;
    if (options.role === "audience") {
      document.getElementById("mic-btn").disabled = true;
      document.getElementById("video-btn").disabled = true;
      // add event listener to play remote tracks when remote user publishs.
      client.on("user-published", handleUserPublished);
      client.on("user-joined", handleUserJoined);
      client.on("user-left", handleUserLeft);
    }
    // join the channel
    options.uid = await client.join(options.appid, options.channel, options.token || null);
    if (options.role === "host") {
      document.getElementById('mic-btn').disabled = false;
      document.getElementById('video-btn').disabled = false;
      client.on("user-published", handleUserPublished);
      client.on("user-joined", handleUserJoined);
      client.on("user-left", handleUserLeft);
      // create local audio and video tracks
      localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
      localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
      showMuteButton();
      // play local video track
      localTracks.videoTrack.play("local-player");
      // document.getElementById("local-player-name").innerText = 'localTrack(options.uid)';
      // publish local tracks to channel
      await client.publish(Object.values(localTracks));
      console.log("Successfully published.");
    }
}

async function leave() {
    var trackName;
    for (trackName in localTracks) {
      var track = localTracks[trackName];
      if (track) {
        track.stop();
        track.close();
        document.getElementById('mic-btn').disabled = true;
        document.getElementById('video-btn').disabled = true;
        localTracks[trackName] = undefined;
      }
    }
    // remove remote users and player views
    remoteUsers = {};
    document.getElementById("remote-playerlist").html = "";
    // leave the channel
    await client.leave();
    document.getElementById("local-player-name").innerText = "";
    document.getElementById("host-join").disabled = false;
    document.getElementById("audience-join").disabled = false;
    document.getElementById("leave").disabled = true;
    hideMuteButton();
    console.log("Client successfully left channel.");
}

async function subscribe(user, mediaType) {
    const uid = user.uid;
    // subscribe to a remote user
    await client.subscribe(user, mediaType);
    console.log("Successfully subscribed.");
    if (mediaType === 'video') {
      const player = document.createElement("div");
      player.innerHTML = `
        <div id="player-wrapper-${uid}">
          <div id="player-${uid}" class="player p-4 relative">
            <button id="leave" type="button" class="w-[20px] h-[20px] absolute top-8 right-8 z-[999] flex items-center justify-center bg-gray-800 text-green-100 text-md">x</button>
          </div>
        </div>
      `;
      document.getElementById("remote-playerlist").appendChild(player);
      user.videoTrack.play(`player-${uid}`);
    }
    if (mediaType === 'audio') {
      user.audioTrack.play();
    }
}

  // Handle user published
function handleUserPublished(user, mediaType) {
    const id = user.uid;
    remoteUsers[id] = user;
    subscribe(user, mediaType);
}

  // Handle user joined
function handleUserJoined(user, mediaType) {
    const id = user.uid;
    remoteUsers[id] = user;
    subscribe(user, mediaType);
}

  // Handle user left
function handleUserLeft(user) {
    const id = user.uid;
    delete remoteUsers[id];
    document.getElementById(`player-wrapper-${id}`).remove();
}

// Mute audio click
document.getElementById("mic-btn").addEventListener("click", function(){
    if (localTrackState.audioTrackEnabled) {
        muteAudio();
    } else {
        unmuteAudio();
    }
});


// Mute video click
document.getElementById("video-btn").addEventListener("click", function(){
    if (localTrackState.videoTrackEnabled) {
        muteVideo();
    } else {
        unmuteVideo();
    }
});

// Hide mute buttons
function hideMuteButton() {
    document.getElementById("video-btn").style.display = "none";
    document.getElementById("mic-btn").style.display = "none";
}

  // Show mute buttons
  function showMuteButton() {
    document.getElementById("video-btn").style.display = "inline-block";
    document.getElementById("mic-btn").style.display = "inline-block";
}

// Mute audio function
async function muteAudio() {
    if (!localTracks.audioTrack) return;
    await localTracks.audioTrack.setEnabled(false);
    localTrackState.audioTrackEnabled = false;
    document.getElementById("mic-btn").innerText = "Unmute Audio";
}

// Mute video function
async function muteVideo() {
    if (!localTracks.videoTrack) return;
    await localTracks.videoTrack.setEnabled(false);
    localTrackState.videoTrackEnabled = false;
    document.getElementById("video-btn").innerText = "Unmute Video";
}

// Unmute audio function
async function unmuteAudio() {
    if (!localTracks.audioTrack) return;
    await localTracks.audioTrack.setEnabled(true);
    localTrackState.audioTrackEnabled = true;
    document.getElementById("mic-btn").innerText = "Mute Audio";
}

// Unmute video function
async function unmuteVideo() {
    if (!localTracks.videoTrack) return;
    await localTracks.videoTrack.setEnabled(true);
    localTrackState.videoTrackEnabled = true;
    document.getElementById("video-btn").innerText = "Mute Video";
}
