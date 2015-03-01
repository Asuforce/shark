var material;
var context = new webkitAudioContext(); // 全体を統括

var compressor = context.createDynamicsCompressor(); // 音の調整をするやつ
var masterGainNode = context.createGain();　// 全体のボリューム調整のため

/*全体の曲をまとめる*/
window.onload = function(){
    var url = document.getElementById('temp_path').value;

    material = new Audio();
    var source = context.createMediaElementSource(material);
    console.log(url);
    material.src = url;

    var gainNode = context.createGain();
    source.connect(gainNode);
    gainNode.connect(compressor);
    compressor.connect(masterGainNode);
    masterGainNode.connect(context.destination);
};

$(function() {
    $('#submit').on('click', function(evt) {
        evt.preventDefault();
        var $form = $('#partForm');

        // FormData オブジェクトを作成
        var formData = $form.serialize();

        setTimeout(
            function(){
                $.ajax({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    dataType: 'json',
                    data: formData
                }).done(function( errors ) {
                    $(".error").empty();
                    if(errors !== null){
                        for(var key in errors){
                            for(var i=0; i<errors[key].length; i++){
                                if(i>0){
                                    $("."+key).append("<br>");
                                }
                                $("."+key).append(errors[key][i]);
                            }
                        }
                    }else{
                        location.href= BASE_URL+'/music';
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                });
        },300);
    });

    // 再生、一時停止ボタン
    $(document).on('click', '.material-grid', function() {
        if(!$('.material-grid').hasClass('pause_bt')) {
            console.log('audio play');
            material.play();
            $('.material-grid').addClass('pause_bt');
        } else {
            console.log('audio pause');
            material.pause();
            $('.material-grid').removeClass('pause_bt');
        }
    });

    $(document).on('click', '#stopbtn', function() {
        console.log('audio stop');
        material.pause();
        material.currentTime = 0;
    });
});