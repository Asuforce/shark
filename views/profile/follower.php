<?php $this->setLayoutVar('title', 'Follower') ?>
<?php $this->setLayoutVar('back_link', "/profile/{$user_name}") ?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/follow.css" />

<div class="animsition">
    <div id="circle">
        <p>Follower</p>
        <h7><?php echo count($followers); ?></h7>
    </div>
    <paper-shadow class="fixing" z="1"></paper-shadow>
    <div id="follow_user">
    <?php foreach ($followers as $follower): ?>
        <div class='userList'>
            <a href="<?php echo $base_url; ?>/profile/<?php echo $follower['user_name']?>">
                <img src="<?php echo $follower['pro_image']; ?>">
                <h4><?php echo $follower['name']; ?></h4>
                <h6><?php echo $follower['user_name']; ?></h6>
            </a>
        </div>
    <?php endforeach; ?>
    </div>
</div>