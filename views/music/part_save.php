<?php $this->setLayoutVar('title', 'Part Save') ?>
<?php $this->setLayoutVar('part_save', true) ?>
<?php $this->setLayoutVar('back_link', "/music/rec") ?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/part_save.css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js/part_save.js"></script>

<paper-shadow class="mix" z="1">
    <img src="<?php echo $base_url;?>/img/note.png">
</paper-shadow>

<form action='<?php echo $base_url; ?>/music/part_save' method='post' id='partForm' accept-charset='utf-8'>
    <div id='rec'>
        <img src='<?php echo $base_url; ?>/img/minirec.png'>
    </div>
    <div id="part">
        <p>パート</p>
        <select name="part">
            <option value="" selected>選択する</option>
            <option value="1">Vocal</option>
            <option value="2">Guiter</option>
            <option value="3">Bass</option>
            <option value="4">Drum</option>
            <option value="5">Other</option>
        </select>
        <div class="error part"></div>
    </div>
    <div id="comment">
        <p>コメント</p>
        <textarea name="comment" rows="4" cols="40"></textarea>
        <div class="error comment"></div>
        <div class="error data"></div>
    </div>
    <input type='hidden' id='temp_path' value="<?php echo $_SESSION['temp_path']; ?>" >
</form>
