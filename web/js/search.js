function setMaterial(){
    $("#status").data("value","0");
}
function setRecord(){
	$("#status").data("value","1");
}
function setUser(){
	$("#status").data("value","2");
}

//formデータを送る
$(function($) {
    $('#submit').on('click', function(evt) {
         evt.preventDefault();
         var $form = $('#searchForm');

        var param = {
            r:$("#r").val(), //入力データ
            status: $("#status").data("value"), //検索内容
        };

        setTimeout(
            function(){
                $.ajax({
                  url: $form.attr('action'),
                  method: $form.attr('method'),
                  dataType: 'json',
                  data: param
                }).done(function( results ) {
                    $("#contents").empty();
                    if(results ===null || results.length ===0){
                        $("#contents").append("<div class='error'>"+$("#r").val()+"に一致する情報は見つかりませんでした</div>");
                    }else{
                        createContents(results);
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                });
        },250);
    });
});