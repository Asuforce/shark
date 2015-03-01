window.AudioContext = window.AudioContext || window.webkitAudioContext;
var context = new webkitAudioContext(); // 全体を統括
var masterGainNode = context.createGain();　// 全体のボリューム調整のため
var compressor = context.createDynamicsCompressor(); // 音の調整をするやつ

var dataSounds = {};//ロードしたファイルを保管しておく
var playSounds = {};//現在再生している要素全体
/*全体の曲をまとめる*/
function connect(key,materialKey,url){
    var buffer_source;
    var getSound = new XMLHttpRequest();
    //load
    getSound.open("GET",url,true);
    getSound.responseType = "arraybuffer";
    getSound.onload = function(){
        context.decodeAudioData(getSound.response,function(buffer){
                var array = [];
                var data = {
                    buffer:buffer,
                    key:materialKey
                };
                array.push(data);
                if(dataSounds[key] !== undefined){
                    array = dataSounds[key];
                    array.push(data);
                }
                dataSounds[key] = array;
        });
    }
    getSound.send();
}

function audio_play() {
    console.log(playSounds);
    for (var key in playSounds) {
        var playSound = context.createBufferSource();
        var gainNode = context.createGain();
        playSound.buffer = playSounds[key].buffer;
        playSound.loop = true;
        playSound.connect(gainNode);
        gainNode.gain.value = 0.5;
        gainNode.connect(compressor);
        compressor.connect(masterGainNode);
        masterGainNode.connect(context.destination);
        playSound.start(0);
        playSounds[key].playSound = playSound;
        playSounds[key].gainNode = gainNode;
    }
}

function audio_pause() {
    for (var key in playSounds) {
        playSounds[key].playSound.stop(0);
    }
}

function messageLoad() {
    $.ajax({
        type: 'post',
        url: BASE_URL+'/message/noReadLoad',　//自分で指定が必要です。
        success: function( count ){
            if(count>0){
                $("#MyBadge").text(count);
                $("#MyBadge").css('display','block');
            }else{
                $("#MyBadge").css("display", "none");
            }
        }
    });
}

messageLoad();
setInterval("messageLoad()", 5000);


// 配列からTimeLineを生成する関数
function createContents(contents){
    for ( var i = 0; i < contents.length; i++ ) {
        $("#contents").append(contents[i]);
    }

    timeLines = $(".timeline");
    dataSounds = {}

    for (var i =0; i < timeLines.length; i++) {
        type = timeLines[i].dataset.type;
        id = timeLines[i].dataset.id;
        name = timeLines[i].dataset.name;
        key = type+"_"+name+"_"+id;

        if(type === '0'){
            url = BASE_URL+"/materials/"+name+"_"+id+".wav";
            materialKey = key;
            connect(key,materialKey,url);
        }else if(type === '1'){
            $.ajax({
                type: 'post',
                url: BASE_URL+'/music/getMaterial',
                dataType: 'json',
                data: {id:id},
                cache: false,
                async: false    //実行順序の保証
            }).done(function(data){
                for(var i=0; i< data.length; i++){
                    url = BASE_URL+"/materials/"+data[i].name+"_"+data[i].id+".wav";
                    materialKey = "0_"+data[i].name+"_"+data[i].id;
                    connect(key,materialKey,url);
                }
            }).fail(function(data){
                console.log('error : getMaterial');
            });
        }
    }

    //TimeLineドラッグ時の処理
    $(".timeline").draggable({
        helper: "clone",
        scroll: false,
        zIndex: 5,
        start: function(e,ui){
            //不要な要素を削除
            $(".ui-draggable-dragging a h4").remove();
            $(".ui-draggable-dragging a h6").remove();
            $(".ui-draggable-dragging p").remove();
            $(".ui-draggable-dragging .star").remove();
            $(".ui-draggable-dragging ul").remove();

            img = $(".ui-draggable-dragging a img");
            ins = $(".ui-draggable-dragging .ins");
            ins_img = $(".ui-draggable-dragging .ins img");
            ins_img.hide();

            //画像のサイズ変更
            $(".ui-draggable-dragging").css({
                'width' : '95%',
                'background-color' : 'transparent',
                'margin-left' : e.pageX-img.offset().left -50 +"px",
                'margin-top' : -30 +"px"
            });
            img.css({
                'width' : '80',
                'height' : '80'
            });
            ins.css({
                'width' : '30',
                'height' : '30',
                'position': 'relative',
                'margin' : '0'
            });
            ins_img.css({
                'width' : '30',
                'height' : '30',
                'position': 'relative',
                'margin' : '0'
            });
            //画像のサイズ変更

            $(".ui-draggable-dragging").animate({
                 width  : '120',
                 height : '120'
            },1,function(){ins_img.show();});
        }
    });

    //TimeLineドロップ時の処理。ドロップ場所はMusicアイコン
    $("#music_icon a img").droppable({
        accept : ".timeline" ,
        tolerance : "touch",
        drop : function(event , ui){
            timeline = ui.draggable[0];
            type = timeline.dataset.type;
            id = timeline.dataset.id;
            name = timeline.dataset.name;
            span = "<span>"+timeline.childNodes[1].innerHTML+"</span>";
            ul = timeline.childNodes[3];
            scrubber = "<div class='scrubber'>"+ul.childNodes[1].innerHTML+"</div>";
            var del = '<div class="del"><paper-icon-button icon="delete" title="delete"></paper-icon-button></div>';
            var html = "<div class='timeline' data-type="+type+" data-id="+id+" data-name="+name+">"+span+"<ul>"+scrubber+del+"</ul>"+"</div>";
            mixAdd(type,id,html);
        }
    });
}

function simpleCreateContents(contents){
    for ( var i = 0; i < contents.length; i++ ) {
        $("#contents").append(contents[i]);
    }
}

function mixAdd(type,id,html) {
    var param = {
        type : type,
        id : id,
        html : html
    };
    $.ajax({
        type: 'post',
        url: BASE_URL+'/music/mixAdd',
        dataType: 'json',
        data: param,
        cache: false
    }).done(function(data){
    }).fail(function(data){
        console.log('error : mixAdd');
    });
}

//お気に入り追加・削除アクション
var favorite_flg = true;
$(function(){
    $(document).on("click", ".star img", function(){
        if(favorite_flg === true){
            id = $(this).attr("id");
            favorite_flg = false;
            var flg = $(this).data("flg");
            var param = {
                type : $(this).data("type"), // 素材かレコードか(0:素材, 1:レコード)
                fav_check : $(this).data("flg"),// 既にお気に入りされているか(0:されていない, 1:されている)
                id : $(this).data("id")   // material_idかrecord_idを取得
            };
            $.ajax({
                type: 'post',
                url: BASE_URL+'/home/favoriteManage',
                dataType: 'json',
                data: param,
                cache: false
            }).done(function(data){
                //insertまたはdeleteに成功した場合のみ
                if(data === true){
                    favorite_flg = true;
                    if(flg === 0){
                        //お気に入りに追加
                        $("#"+id).data("flg",1);
                        $("#"+id).attr("src",BASE_URL+"/img/star1.png");
                    }else if(flg==1){
                        //お気に入り削除
                        $("#"+id).data("flg",0);
                        $("#"+id).attr("src",BASE_URL+"/img/star0.png");
                    }
                }
            }).fail(function(data){
                console.log('error : changeFavorite');
            });
        }
    });
});
var preSelectTimeline;//現在再生している要素

function getStartSounds(timeline,ul) {
    type = timeline[0].dataset.type;
    name = timeline[0].dataset.name;
    id = timeline[0].dataset.id;

    key = type+"_"+name+"_"+id;

    if(preSelectTimeline !== undefined){
        $(preSelectTimeline).removeClass("slidedown");
        $(preSelectTimeline).removeClass("playNow");
        $(preSelectTimeline).css("background-color","#fff");
        $(preSelectTimeline).children("span").children("a").css("background-color","#fff");
        $(preSelectTimeline).children("ul").css("background-color","#fff");
        $(preSelectTimeline).children("ul").slideUp("normal");
        $(preSelectTimeline).children("ul").children(".scrubber").hide();
        $(preSelectTimeline).children("ul").children(".del").hide();
    }

    preSelectTimeline = $(timeline);
    $(timeline).css("background-color","#bbdcd8");
    $(timeline).children("span").children("a").css("background-color","#bbdcd8");
    $(timeline).children("ul").css("background-color","#bbdcd8");
    if(playSounds.length!==0){
        audio_pause();
        playSounds = {};
    }
    if($(timeline).data("type")===0){
    //素材
        playSounds[key] = dataSounds[key][0];
    }else if($(timeline).data("type")===1){
    //レコード
        for(var i=0; i< dataSounds[key].length;i++){
            materialKey = dataSounds[key][i].key;
            playSounds[materialKey] = dataSounds[key][i];
        }
    }
}

// アコーディオン(volume)
$(function(){
    $(document).on("click", ".timeline span", function(){
        timeline = $(this).parent();
        ul = timeline.children("ul");
        timeline.toggleClass("slidedown");
        if(!timeline.hasClass("changed")){
            if(timeline.hasClass("slidedown")){
                //slideDown
                ul.slideDown("normal");
                ul.children(".scrubber").fadeIn(800);
                ul.children(".del").fadeIn(800);
                if(timeline.hasClass("ui-draggable")&&!timeline.hasClass("playNow")){
                    getStartSounds(timeline,ul);
                    audio_play();
                    timeline.addClass("playNow");
                    var ratings = document.querySelector('.slider');
                    ratings.addEventListener('core-change', function() {
                        console.log(ratings.value);
                        playSounds[key].gainNode.gain.value = ratings.value/100;
                    });
                }
            }else{
                //slideUp
                ul.children(".scrubber").fadeOut(100);
                ul.children(".del").fadeOut(100);
                ul.slideUp("normal");
                timeline.css("background-color","#fff");
                timeline.children("span").children("a").css("background-color","#fff");
                timeline.children("ul").css("background-color","#fff");
                if(timeline.hasClass("ui-draggable")){
                    preSelectTimeline = undefined;
                    timeline.removeClass("playNow");
                    audio_pause();
                    playSounds = {};
                }
            }
        }
    });
});