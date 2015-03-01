var audio_context; // 録音中のデータ
var context = new webkitAudioContext(); // 全体を統括
var material = new Audio(); // 録音後のデータ

var compressor = context.createDynamicsCompressor(); // 音の調整をするやつ
var masterGainNode = context.createGain();　// 全体のボリューム調整のため

 window.requestFileSystem = window.requestFileSystem || window.webkitRequestFileSystem;

window.requestAnimationFrame = (function() {
    return window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    window.msRequestAnimationFrame ||
    window.oRequestAnimationFrame ||
    function(f) { return window.setTimeout(f, 1000 / 60); };
}());

window.cancelAnimationFrame = (function() {
    return window.cancelAnimationFrame ||
    window.cancelRequestAnimationFrame ||
    window.webkitCancelAnimationFrame ||
    window.webkitCancelRequestAnimationFrame ||
    window.mozCancelAnimationFrame ||
    window.mozCancelRequestAnimationFrame ||
    window.msCancelAnimationFrame ||
    window.msCancelRequestAnimationFrame ||
    window.oCancelAnimationFrame ||
    window.oCancelRequestAnimationFrame ||
    function(id) { window.clearTimeout(id); };
}());

var now = window.performance && (
    performance.now || performance.mozNow || performance.msNow ||
    performance.oNow || performance.webkitNow
    );

window.getTime = function() {
    return (now && now.call(performance)) ||
    (new Date().getTime());
};

var recorder;
var input;

var timeMax = 15000; // 15秒
var percent = 0;

var aTimer = null;
var aTimeSum = 0;
var aTimeOld = 0;

var bt =  true;
var moni = true;
var ttmp;

function startUserMedia(stream) {
  input = audio_context.createMediaStreamSource(stream);
  recorder = new Recorder(input);
}

// 録音開始
function startRecording(button) {
  bt = false;
  aTimer = requestAnimationFrame(timerLoop);
  timerStart();
  recorder && recorder.record();

}

// 録音終了
function stopRecording(button) {
  bt = true;
  timerStop();

  recorder && recorder.stop();
  recorder && recorder.exportWAV(function(blob) {});

  timerReset();
  $('#captureTimer').css('height', ttmp + '%');
  recorder && recorder.clear();
}

// タイマーを進行させる
var timerUpdate = function() {
  percent = Math.floor((aTimeSum / timeMax) * 100);
  if (percent == 100) {
      ttmp = 0;
      stopRecording();
  } else if (percent !== 0) {
      percent = 100 - percent;
      $('#captureTimer').css('height', percent + '%');
  }
};

// タイマーをループさせる
var timerLoop = function() {
  var now = getTime();
  aTimeSum += (now - aTimeOld);
  aTimeOld = now;

  timerUpdate();
  if (aTimer) {
    aTimer = requestAnimationFrame(timerLoop);
  }
};

// タイマー開始
var timerStart = function() {
  aTimeOld = getTime();
  timerLoop();
};

// タイマー停止
var timerStop = function() {
  if (aTimer) {
    cancelAnimationFrame(aTimer);
    aTimer = null;
  }
};

// タイマーリセット
var timerReset = function() {
  timerStop();
  aTimeSum = 0;
  percent = 0;
};

function audio_play() {
  material.play();
  console.log('play');
}

function audio_pause() {
  material.pause();
}

function putWaveData(blob) {
  window.requestFileSystem(TEMPORARY, 3000000, function(fileSystem){
        // ファイル新規作成（上書き）
        fileSystem.root.getFile('shark_temp.wav', {create: true, exclusive: false}, function(fileEntry){
            // ファイル書き込み
            fileEntry.createWriter(function(fileWriter){
                console.log(blob);
                fileWriter.write(blob);
                // ファイル書き込み成功イベント
                fileWriter.onwriteend = function(e){
                    console.log("file writting success");
                };
                // ファイル書き込み失敗イベント
                fileWriter.onerror = function(e){
                    console.log("file writting error");
                };
            });
            document.getElementById('toMaterial').href = 'mix_save?url='+ fileEntry.toURL();
        }, function(error){
            console.log("error.code=" + error.code);
        });
    });
}

$(function(){
    $(document).on("click", ".accordion p", function(){
        $(this).next("ul").slideToggle();
        $(this).children("span").toggleClass("open");
    });

    $(document).on("click", ".accordion dt", function(){
        $(this).next("dd").slideToggle();
        $(this).next("dd").siblings("dd").slideUp();
        $(this).toggleClass("open");
        $(this).siblings("dt").removeClass("open");
    });

    //Mix内の要素Delete処理
    $(document).on("click", ".del", function(){
      ul = $(this).parent();
      timeline = ul.parent();
      var param = {
          type : timeline[0].dataset.type,
          id : timeline[0].dataset.id
      };
      $.ajax({
          type: 'post',
          url: BASE_URL+'/music/mixDelete',
          dataType: 'json',
          data: param,
          cache: false
      }).done(function(data){
        console.log(data);
        timeline.fadeOut(400);
        setTimeout(function(){timeline.remove();} ,400);
      }).fail(function(data){
          console.log('error : changeFavorite');
      });
    });
});
