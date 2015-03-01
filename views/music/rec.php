<?php $this->setLayoutVar('title', 'Rec') ?>
<?php $this->setLayoutVar('mix_rec', true) ?>
<?php $this->setLayoutVar('back_link', "/music") ?>

<link type="text/css" rel="stylesheet" href="<?php echo $base_url; ?>/css/rec.css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js/rec/recorder.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/rec/worker.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/rec/rec.js"></script>

<!--mixロゴ-->
<paper-shadow class="mix" z="1">
    <img src="<?php echo $base_url;?>/img/note.png">
</paper-shadow>

<div id='timer'>
    <div id='captureTimer'>
        <img class='imgrec' src='<?php echo $base_url; ?>/img/bigrec.png' value='録音' >
    </div>
</div>
