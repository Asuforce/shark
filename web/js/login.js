$(function($) {
    $('#submit').on('click', function(evt) {
        evt.preventDefault();
        var $form = $('#loginForm');

        // FormData オブジェクトを作成
        var formData = $form.serialize();

        setTimeout(
            function(){
                $.ajax({
                  url: $form.attr('action'),
                  method: $form.attr('method'),
                  dataType: 'json',
                  data: formData
                }).done(function( res ) {
                    $(".error").empty();
                    if(res.errors !== 0){
                        for(var key in res.errors){
                            error = res.errors[key];
                            for(var i=0; i<error.length; i++){
                                if(i>0){
                                    $("."+key).append("<br>");
                                }
                                $("."+key).append(error[i]);
                            }
                        }
                    }else{
                        location.href= res.url;
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                });
            },200);
    });
});