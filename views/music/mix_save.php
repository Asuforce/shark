<?php $this->setLayoutVar('title', 'Mix Save') ?>
<?php $this->setLayoutVar('back_link', "/music") ?>
<?php $this->setLayoutVar('mix_save', "/music") ?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/mix_save.css" />
<script type="text/javascript" src="<?php echo $base_url;?>/js/mix_save.js"></script>

<!--mixロゴ-->
<paper-shadow class="mix" z="1">
    <img src="<?php echo $base_url;?>/img/note.png">
</paper-shadow>

<form action="<?php echo $base_url; ?>/music/mix_save" method="post" id="mixForm">
    <!--<div class="error mix"></div>-->
    <div id="rec">
        <img src="<?php echo $base_url;?>/img/minirec.png">
    </div>
    <!-- タイトル入力 -->
    <div id="title">
        <p>タイトル</p>
        <input name="title" type="text">
        <div class="error title"></div>
    </div>
    <!-- コメント入力 -->
    <div id="comment">
        <p>コメント</p>
        <textarea name="comment" rows="4" cols="40"></textarea>
        <div class="error comment"></div>
    </div>
</form>