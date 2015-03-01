//画像を正方形に生成し、submitする
$(function($) {
    $('#submit').on('click', function(evt) {
         evt.preventDefault();
        var $form = $('#profileForm');

        // FormData オブジェクトを作成
        var formData = $form.serialize();

        cropC = cropContainerEyecandy;

        setTimeout(
            function(){
                imgY1 = "";
                imgX1 = "";
                if(cropC.img.length !== undefined){
                    imgY1 = Math.abs(parseInt(cropC.img.css('top')));
                    imgX1 = Math.abs( parseInt( cropC.img.css('left')));
                }
                var cropData = {
                    imgUrl:cropC.imgUrl,
                    imgInitW:cropC.imgInitW,
                    imgInitH:cropC.imgInitH,
                    imgW:cropC.imgW,
                    imgH:cropC.imgH,
                    imgY1: imgY1,
                    imgX1: imgX1,
                    cropH:cropC.objH,
                    cropW:cropC.objW
                };
                cropData = $.param(cropData);
                data = formData+"&"+cropData;

                $.ajax({
                  url: $form.attr('action'),
                  method: $form.attr('method'),
                  dataType: 'json',
                  data: data
                }).done(function( errors ) {
                    length = Object.keys(errors).length;
                    if(length !== 0){
                        for(var key in errors){
                            for(var i=0; i<errors[key].length; i++){
                                if(i>0){
                                    $("."+key).append("<br>");
                                }
                                $("."+key).append(errors[key][i]);
                            }
                        }
                    }else{
                        location.href= BASE_URL+'/';
                    }
                });
            },300);
    });
});