var audio_context; // 録音中のデータ
var context;
var material = new Audio(); // 録音後のデータ
var wav_data;

var compressor;
var masterGainNode;

window.onload = function() {
  try {
    // webkit shim
    navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
    window.URL = window.URL || window.webkitURL;
    window.AudioContext = window.AudioContext || window.webkitAudioContext;


    audio_context = new AudioContext();
    context  = new AudioContext();

    compressor = context.createDynamicsCompressor(); // 音の調整をするやつ
    masterGainNode = context.createGain();　// 全体のボリューム調整のため

    var source = context.createMediaElementSource(material);

    var gainNode = context.createGain();
    source.connect(gainNode);
    gainNode.connect(compressor);
    compressor.connect(masterGainNode);
    masterGainNode.connect(context.destination);
  } catch (e) {
    alert(e);
  }
  navigator.getUserMedia({audio: true}, startUserMedia, function(e) {});
};

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
    $("#recbtn").addClass('stoprec');
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

function uploadAudio(wav_data){
  console.log('pass');
  var reader = new FileReader();
    reader.onload = function(event){
      var fd = new FormData();
      fd.append('data', event.target.result);
      $.ajax({
        type: 'POST',
        url: BASE_URL+'/music/temp_upload',
        data: fd,
        processData: false,
        contentType: false
      }).done(function(data) {
          location.href = BASE_URL+data.url;
      }).fail(function(data){
          console.log('update error');
      });
    };
  reader.readAsDataURL(wav_data);
}

$(function(){
  // リセットボタン
  $("#main").on("click", ".reset", function() {
    console.log('reset');
    if($('#recbtn').hasClass("recording")){
      $(this).css('zIndex', 0);
      $(this).animate({zIndex:1}, {
        duration:1000,
        step:function(now) {
          $(this).css({transform:'rotate('+(now*-360)+'deg)'});
        }, complete : function(){
          $(this).css('zIndex', 0);
        }
      });
      $("#recbtn").removeClass('recording');
      $("#recbtn").removeClass('stoprec');
      console.log('reverse');
      $('#recbtn').animate({
        'border-radius' : '50%',
        'width' : '17.5px',
        'height' : '17.5px',
        'margin-top' : '16px',
        'background-color' : '#DD6A5F'
      },400);
      stopRecording();
    }
  });

  // 再生、一時停止ボタン
  // $("#main").on('click', '.material-grid', function() {
  //   if(!$('.material-grid').hasClass('pause_bt')) {
  //     console.log('audio play');
  //     material.play();
  //     $('.material-grid').addClass('pause_bt');
  //   } else {
  //     console.log('audio pause');
  //     material.pause();
  //     $('.material-grid').removeClass('pause_bt');
  //   }
  // });

  $("#main").on('click', '.material-grid', function() {
      material.play();
  });

  // 録音、停止ボタン
  $("#main").on('click', '#recbtn', function() {
    if(!$('#recbtn').hasClass("recording")){
      console.log('recording');
      $('#recbtn').addClass('recording');
      $('#recbtn').animate({
        'border-radius' : '0',
        'width' : '8px',
        'height' : '8px',
        'margin-top' : '22px',
        'background-color' : '#fff'
      },400);
      startRecording();
    } else if (!$('#recbtn').hasClass("stoprec")) {
      console.log('stoprec');
      $('#recbtn').addClass('stoprec');
      ttmp = percent;
      stopRecording();
    } else {
      console.log('stop');
      material.pause();
      material.currentTime = 0;
    }
  });

  // okボタン
  $("#main").on("click", ".colored_w", function() {
      if(wav_data) {
        setTimeout(uploadAudio(wav_data), 400);
      }
  });
});
