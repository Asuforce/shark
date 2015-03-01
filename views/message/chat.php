<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<?php $this->setLayoutVar('title', $conversation['name']) ?>
<?php $this->setLayoutVar('back_link', "/message") ?>
<?php $this->setLayoutVar('chat_footer', true) ?>

<script type="text/javascript" src="<?php echo $base_url; ?>/js/message_chat.js"></script>

<div id="chat_space"></div>

