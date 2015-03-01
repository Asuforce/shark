<?php
    $this->setLayoutVar('title', 'Profile');
    if($person){
        $this->setLayoutVar('set_link', "/profile/{$profile['user_name']}/edit");
    }else{
        $follow = array();
        $follow = array("flg"=> "0","value" => "Follow","function"=> "followCheck()"); //まだフォローされていない
        if($follow_check!==false){
            $follow = array("flg"=> "1","value" => "Unfollow","function"=> "followCheck()"); //既にフォローされている
        }
        $this->setLayoutVar('follow', $follow);
    }
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/profile.css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js/profile.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/menu.js"></script>

<!--自己紹介部分-->
<div id="fixing">
    <div class="center_img">
        <img src="<?php echo $profile['pro_image']; ?>">
    </div>
    <div id="follow">
        <p>Follow</p>
        <a href="<?php echo $base_url; ?>/profile/<?php echo $profile['user_name']; ?>/follow">
            <paper-button class="follow">
                <h5><?php echo $profile['follow_count']; ?></h5>
            </paper-button>
        </a>
    </div>
    <div id="follower">
        <p>Follower</p>
        <a href="<?php echo $base_url; ?>/profile/<?php echo $profile['user_name']; ?>/follower">
            <paper-button class="follow">
                <h5><?php echo $profile['follower_count']; ?></h5>
            </paper-button>
        </a>
    </div>
    <div id="introduction">
        <p><?php echo $profile['introduction']; ?></p>
    </div>
</div>
<!--ナビ-->
<div class="nav" z="1">
    <paper-tabs selected="0">
        <paper-tab onclick="getProfileMaterial()">Part</paper-tab>
        <paper-tab onclick="getProfileRecord()">Mix</paper-tab>
        <paper-tab onclick="getProfileFavorit()">Favorite</pager-tab>
    </paper-tabs>
</div>

<ul class="accordion">
    <li>
        <div id="contents"></div>
    </li>
</ul>
