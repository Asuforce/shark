<?php
    $this->setLayoutVar('title', 'New Message');
    $this->setLayoutVar('back_link', "/message");
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/newConversation.css" />

<div id="contents">
<?php foreach ($follows as $follow): ?>
    <div class='timeline'>
        <a href="<?php echo $base_url."/message/conversation/".$follow['user_name'];?>">
            <h4><?php echo $follow['name']; ?></h4>
            <img src="<?php echo $follow['pro_image']; ?>">
            <p><?php echo $follow['user_name']; ?></p>
        </a>
    </div>
<?php endforeach; ?>
</div>