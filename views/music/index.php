<?php $this->setLayoutVar('title', 'Mix') ?>
<?php $this->setLayoutVar('mix_retain', true) ?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/mix_retain.css" />

<paper-shadow class="mix" z="1">
    <img src="<?php echo $base_url;?>/img/note.png">
</paper-shadow>

<ul class="accordion">
    <div id="contents">
        <?php foreach($mixes as $mix): ?>
            <?php echo $mix['html']; ?>
        <?php endforeach; ?>
    </div>
</ul>

<script type="text/javascript">
$(function(){
    $(".timeline").css("background-color","#fff");
    $(".timeline").children("span").children("a").css("background-color","#fff");
    $(".timeline").children("ul").css("background-color","#fff");

    //Mix内の要素Delete処理
    $("#main").on("click", ".del", function(){
        audio_pause();
        ul = $(this).parent();
        timeline = ul.parent();
        type = timeline[0].dataset.type;
        id = timeline[0].dataset.id;
        name = timeline[0].dataset.name;

        key = type+"_"+name+"_"+id;

        delete playSounds[key];

        var param = {
          type : type,
          id : id
        };
        $.ajax({
          type: 'post',
          url: BASE_URL+'/music/mixDelete',
          dataType: 'json',
          data: param,
          cache: false
        }).done(function(data){
        console.log(data);
        timeline.fadeOut(400);
        setTimeout(function(){timeline.remove();} ,400);
        }).fail(function(data){
          console.log('error : changeFavorite');
        });
    });

    timeLines = $(".timeline");
    dataSounds = {}

    for (var i =0; i < timeLines.length; i++) {
        timeline = timeLines[i];
        type = timeline.dataset.type;
        id = timeline.dataset.id;
        name = timeline.dataset.name;

        key = type+"_"+name+"_"+id;
        url = BASE_URL+"/materials/"+name+"_"+id+".mp3";
        materialKey = key;
        connect(key,materialKey,url);
    }

    $(".playbar").on("click", ".material-grid", function(){
        timeLines = $(".timeline");
        playSounds = {};
        if(!$(this).hasClass("playNow")){
            for (var i =0; i < timeLines.length; i++) {
                timeline = timeLines[i];
                getStart(timeline);
            }
            audio_play();
            $(this).addClass("playNow");
        }
    });
    $(".playbar").on("click", "#stop", function(){
        if($(".material-grid").hasClass("playNow")){
            audio_pause();
            $(".material-grid").removeClass("playNow");
        }
    });
});
function getStart(timeline) {
    type = timeline.dataset.type;
    name = timeline.dataset.name;
    id = timeline.dataset.id;

    key = type+"_"+name+"_"+id;
    playSounds[key] = dataSounds[key][0];
}

</script>