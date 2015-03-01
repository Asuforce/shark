<?php $this->setLayoutVar('title', $conversation['name']) ?>
<?php $this->setLayoutVar('back_link', "/message") ?>
<?php $this->setLayoutVar('chat_footer', true) ?>

<style>
/*戻るボタン*/
.header h1 { padding-right: 30px; }
.back { float: left; margin-left: 5px; height: auto; width: auto; top: 3px; color: #000000; }
</style>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/message_chat.js"></script>

<div id="chat_space"></div>

