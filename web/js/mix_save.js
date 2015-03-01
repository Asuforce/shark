$(function($) {
    $('#submit').on('click', function(evt) {
         evt.preventDefault();
        var $form = $('#mixForm');

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
                    if(errors !== null){
                        for(var key in errors){
                            $("."+key).empty();
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
});