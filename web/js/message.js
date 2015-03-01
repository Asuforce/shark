$(document).ready(function(){
    getInformation();
});

function getInformation() {
    clearInterval('conversationLoad()');
    $("#contents").empty();
    $.ajax({
        type: 'post',
        url: location.pathname+'/information',
        success: function( contents ){
            if(contents.length!==0){
                simpleCreateContents(contents);
            }
        }
    });
}

function getConversation() {
    setInterval('conversationLoad()', 5000);
    $("#contents").empty();
    $.ajax({
        type: 'post',
        url: location.pathname+'/conversation',
        success: function( contents ){
            if(contents.length!==0){
                simpleCreateContents(contents);
                conversationLoad();
            }
        }
    });
}

//常にローディングして未読がないか確認
function conversationLoad() {
    $.ajax({
        type: 'post',
        url: BASE_URL+'/message/conversationLoad',
        success: function( messages ){
            if(messages.length!==0){
                for ( var i = 0; i < messages.length; i++ ) {
                    message = messages[i];
                    if(0<message.count){ //要素が存在した場合のみ
                        $("#"+message.conversation_id).css('display','block');
                        count = message.count;
                        $("#"+message.conversation_id).text(count);
                    }else{
                        $("#"+message.conversation_id).css("display","none");
                    }
                }
            }
        }
    });
}