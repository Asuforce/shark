<?php $this->setLayoutVar('title', 'Follower') ?>
<?php $this->setLayoutVar('back_link', "/profile/{$user_name}") ?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/follow.css" />

<div class="animsition">
    <div id="circle">
        <p>フォロー</p>
        <h7><?php echo count($followers); ?></h7>
    </div>
    <paper-shadow class="fixing" z="1"></paper-shadow>
    <div id="follow_user">
    <?php foreach ($followers as $follower): ?>
        <div class='user_list'>
            <a href="<?php echo $base_url; ?>/profile/<?php echo $follower['user_name']?>">
                <h4><?php echo $follower['name']; ?></h4>
                <img src="<?php echo $follower['pro_image']; ?>">
                <p><?php echo $follower['user_name']; ?></p>
            </a>
        </div>
    <?php endforeach; ?>
    </div>
</div>