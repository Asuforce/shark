<?php
    $this->setLayoutVar('title', 'Edit');
    $this->setLayoutVar('back_link', "/profile/{$profile['user_name']}");
?>

<link rel="stylesheet"type="text/css" href="<?php echo $base_url; ?>/css/profile_set.css">
    <link rel="stylesheet" type="text/css "href="<?php echo $base_url; ?>/css/croppic.css">
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/croppic.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/edit.js"></script>

<?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('errors', array('errors' => $errors)) ?>
<?php endif; ?>

<form enctype='multipart/form-data' action='<?php echo $base_url; ?>/profile/<?php echo $user['user_name']; ?>/edit' method='post' id="profileForm">
    <div class="center_img">
        <div id="cropContainerEyecandy"></div>
    </div>
    <div id="setting">
        <table class="set">
            <tr>
                <th>ID</th>
                <td>
                    <input type="text" placeholder="4文字以上16文字以下" name="user_name" value="<?php echo $user['user_name'];?>" class="hoge" style="text-align: left;"/>
                    <div class="error user_name"></div>
                </td>
            </tr>
            <tr>
                <th>名前</th>
                <td>
                    <input type="text" placeholder="1文字以上12文字以下" name="name" value="<?php echo $user['name'];?>" class="hoge" style="text-align: left;"/>
                    <div class="error name"></div>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <input type="text" name="mail_address" value="<?php echo $user['mail_address'];?>" class="hoge" style="text-align: left; "/>
                    <div class="error mail_address"></div>
                </td>
            </tr>
            <tr>
                <th>パスワード</th>
                <td>
                    <input type="password" placeholder="8文字以上32文字以下" name="password" value="" class="hoge" style="text-align: left; "/>
                    <div class="error password"></div>
                </td>
            </tr>
            <tr>
                <th>性別</th>
                <td>
                    <div class="sexRadio">
                        <?php if ($profile['sex']==='0'): ?>
                            <input type="radio" name="sex" id="on" value=0 checked>
                            <label for="on" class="switch-on">男</label>
                            <input type="radio" name="sex" id="off" value=1>
                            <label for="off" class="switch-off">女</label>
                        <?php elseif ($profile['sex']==='1'): ?>
                            <input type="radio" name="sex" id="on" value=0 checked>
                            <label for="on" class="switch-on">男</label>
                            <input type="radio" name="sex" id="off" value=1>
                            <label for="off" class="switch-off">女</label>
                        <?php else: ?>
                            <input type="radio" name="sex" id="on" value=0 checked>
                            <label for="on" class="switch-on">男</label>
                            <input type="radio" name="sex" id="off" value=1>
                            <label for="off" class="switch-off">女</label>
                        <?php endif; ?>
                        <div class="error sex"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>自己紹介</th>
                <td>
                    <textarea name="introduction" class="hoge" style="text-align: left; "><?php echo $profile['introduction'];?></textarea>
                    <div class="error introduction"></div>
                </td>
            </tr>
        </table>
    </div>
</form>

<div id="save">
    <paper-button raised class="colored_red" id="submit">保存</paper-button>
</div>
<div id="logout">
    <a href="<?php echo $base_url; ?>/logout" class="animsition-link" data-animsition-in="fade-in-up"><paper-button raised class="colored_gray">ログアウト</paper-button></a>
</div>

<script>
/*cropとuploadを同じファイルで実行*/
var croppicContainerEyecandyOptions = {
    uploadUrl:BASE_URL+"/profile/getConvertImg",
    imgEyecandy:false,
    loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
};
var cropContainerEyecandy = new Croppic('cropContainerEyecandy', croppicContainerEyecandyOptions);
</script>





