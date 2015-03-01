<?php $this->setLayoutVar('title', 'Follow') ?>
<?php $this->setLayoutVar('back_link', "/profile/{$user_name}") ?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/follow.css" />

<div class="animsition">
    <div id="circle">
        <p>Follow</p>
        <h7><?php echo count($follows); ?></h7>
    </div>
    <paper-shadow class="fixing" z="1"></paper-shadow>
    <div id="follow_user">
    <?php foreach ($follows as $follow): ?>
        <div class='userList'>
            <a href="<?php echo $base_url; ?>/profile/<?php echo $follow['user_name']?>">
                <img src="<?php echo $follow['pro_image']; ?>">
                <h4><?php echo $follow['name']; ?></h4>
                <h6><?php echo $follow['user_name']; ?></h6>
            </a>
        </div>
    <?php endforeach; ?>
    </div>
</div>