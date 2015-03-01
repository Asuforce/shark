<?php
    $this->setLayoutVar('title', 'New Message');
    $this->setLayoutVar('back_link', "/message");
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/newConversation.css" />

<div id="contents">
<?php foreach ($follows as $follow): ?>
    <div href="<?php echo $base_url."/message/conversation/".$follow['user_name'];?>" class='userList'>
        <a href="<?php echo $base_url."/message/conversation/".$follow['user_name'];?>">
            <img src="<?php echo $follow['pro_image']; ?>">
            <h4><?php echo $follow['name']; ?></h4>
            <h6><?php echo $follow['user_name']; ?></h6>
        </a>
    </div>
<?php endforeach; ?>
</div>