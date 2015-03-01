$(document).ready(function(){
    getMaterial();
});

function getMaterial(){
    $.ajax({
        type: 'post',
        url: BASE_URL+'/home/material',
    }).done(function(contents){
        $("#contents").empty();
        if(contents.length!==0){
            createContents(contents);
        } else {
            $("#contents").empty();
        }
    }).fail(function(data){
        console.log('error : getMaterial');
    });
}

function getRecord(){
    $.ajax({
        type: 'post',
        url: BASE_URL+'/home/record',
    }).done(function(contents){
        $("#contents").empty();
        if(contents.length!==0){
            createContents(contents);
        } else {
            $("#contents").empty();
        }
    }).fail(function(data){
        console.log('error : getRecord');
    });
}

function getFavorite(){
    $.ajax({
        type: 'post',
        url: BASE_URL+'/home/favorite',
    }).done(function(contents){
        $("#contents").empty();
        if(contents.length!==0){
            createContents(contents);
        } else {
            $("#contents").empty();
        }
    }).fail(function(data){
        console.log('error : getFavorite');
    });
}
