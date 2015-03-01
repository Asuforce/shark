
var record_now = 0;//現在取得しているレコードの中で最も古いIDを保持する変数

//htmlロード後、最初に実行される処理
$(document).ready(function(){
    message_init();
    message_load();
    setInterval('message_load()', 5000);
});

/*初期レコード一覧表示用ajax*/
function message_init(){
    $.ajax({
        type: 'post',
        url: location.pathname+'/init',
        success: function( message ){
            if(message.length !== 0){
                create_balloon(message.chat_records);
                record_now = message.page;
                scTarget();
            }
        }
    });
}

/*送信ボタンが押されたらメッセージをサーバへ渡す*/
function message_add(){
    if($("#message").val().length > 200){
        alert('文字が長すぎます');
    }else if($("#message").val().length<1){
        alert('文字が短すぎます');
    }else{
        var data = {body:$("#message").val().replace(/\n/g,"<BR>")};
        $.ajax({
            type: 'post',
            url: location.pathname+'/add',
            data: data,
            success: function(message){
                $("#chat_space").append(message[0]);
                $("#message").val("");
                scTarget();
            }
        });
    }
}

/*取得した以前のレコードより前のレコードを30件表示*/
function message_pre(){
    var data = {page:record_now};
    if(data.page){
        $.ajax({
            type: 'post',
            url: location.pathname+'/pre',
            data: data,
            success: function( message ){
                pre_height = $(document).height();
                if(message.length!==0){
                    create_balloon(message.chat_records);
                    after_height = $(document).height();
                    record_now = message.page;
                    pre_Target(after_height-pre_height);
                }
            }
        });
    }
}

/*引数の位置までスクロールを移動させる*/
function pre_Target(target){
    var pos = target;
    $("html, body").animate({
        scrollTop:pos
    }, 0, "swing");
    return false;
}

/*最上部までスクロールされた場合に実行する*/
$(window).scroll(function() {
    if ($(window).scrollTop() === 0){
        message_pre();
    }
});

/*画面を最下部へ移動させる*/
function scTarget(){
    var pos = $("#end").offset().top;
    $("html, body").animate({
        scrollTop:pos
    }, 0, "swing"); //swingで0が良さそう
    return false;
}

/*textareaを自動で調節する関数*/
function resize(){
    areaoj=document.getElementById("message");
    end = document.getElementById("end");
    tval = areaoj.value;//テキストエリアの文字取得

    //改行文字の数を取得
    num = tval.match(/\n|\r\n/g);
    //改行文字の数に合せて高さを変更
    if (num !== null){
        if(num.length<3) {
            areaoj.style.overflow = 'hidden';
            len = num.length+1;
            areaoj.style.height = len*15+"px";
            end.style.height = 50+(len*15)+"px";
            scTarget();
        }else{
            areaoj.style.overflow = 'auto';
        }
    }
    else {
        areaoj.style.height = 15+"px";
        end.style.height = 50+"px";
        return;
    }
}

/*受け取った配列から吹き出しを生成する関数*/
function create_balloon(message){
    for ( var i = 0; i < message.length; i++ ) {
        $("#chat_space").prepend(message[i]);
    }
}

/*常にローディングして、誰かが発言してないか確認する*/
function message_load(){
    $.ajax({
        type: 'post',
        url: location.pathname+'/load',
        success: function( message ){
            if(message.length !== 0){
                for ( var i = 0; i < message.length; i++ ) {
                    $("#chat_space").append(message[i]);
                }
                scTarget();
            }
        }
    });
}
