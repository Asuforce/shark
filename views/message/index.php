<?php
    $this->setLayoutVar('title', 'Notification');
    $this->setLayoutVar('new_message_link', "/message/newConversation");
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/notification.css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js/message.js"></script>


<paper-shadow class="nav" z="1">
    <paper-tabs class="bottom_fit" selected="0">
        <paper-tab onclick="getInformation()">Notice</paper-tab>
        <paper-tab onclick="getConversation()">Message</paper-tab>
    </paper-tabs>
</paper-shadow>

<div id="contents"></div>


