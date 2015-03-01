<?php
    $this->setLayoutVar('title', 'Notification');
    $this->setLayoutVar('new_message_link', "/message/newConversation");
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/notification.css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js/message.js"></script>


<div class="nav">
    <paper-tabs class="bottom_fit" selected="0">
        <paper-tab onclick="getInformation()">Notice</paper-tab>
        <paper-tab onclick="getConversation()">Message</paper-tab>
    </paper-tabs>
</div>

<div id="contents"></div>


