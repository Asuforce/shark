$(document).ready(function(){
    getProfileMaterial();
});

function getProfileMaterial(){
    $.ajax({
        type: 'post',
        url: location.pathname+'/profileMaterial',
    }).done(function(contents){
        if(contents.length !== 0){
            $("#contents").empty();
            createContents(contents);
        }
    });
}

function getProfileRecord(){
    $.ajax({
        type: 'post',
        url: location.pathname+'/profileRecord',
    }).done(function(contents){
        $("#contents").empty();
        if(contents.length !== 0){
            createContents(contents);
        }
    });
}

function getProfileFavorit(){
    $.ajax({
        type: 'post',
        url: location.pathname+'/profileFavorite',
    }).done(function(contents){
        $("#contents").empty();
        if(contents.length !== 0){
            createContents(contents);
        }
    });
}

var follow_flg = true; //フラグを立てて誤作動を防ぐ
function followCheck(){
    if(follow_flg === true){
        follow_flg = false;
        var flg = $("#follow_bt").data("flg");
        $.ajax({
            type: 'post',
            url: location.pathname+'/followManage',
            dataType: 'json',
            data: {flg:flg},
            cache: false
        }).done(function(data){
            follow_flg = true;
            if(flg===0){
                //フォロー追加
                $("#follow_bt").data("flg",1);
                $("#follow_bt").text("フォロー済");
            }else if(flg===1){
                //フォロー削除
                $("#follow_bt").data("flg",0);
                $("#follow_bt").text("フォロー");
            }
        }).fail(function(data){
            console.log('error : changeFavorite');
        });
    }
}
