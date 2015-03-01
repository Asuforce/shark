<?php $this->setLayoutVar('title', 'New Account') ?>
<?php $this->setLayoutVar('back_link', "/login") ?>

<link rel="stylesheet"type="text/css" href="<?php echo $base_url; ?>/css/newaccount.css"title="top">
<link rel="stylesheet" type="text/css "href="<?php echo $base_url; ?>/css/croppic.css">
<script type="text/javascript" src="<?php echo $base_url; ?>/js/croppic.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/add.js"></script>

<?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('errors', array('errors' => $errors)) ?>
<?php endif; ?>

<form enctype='multipart/form-data' action='<?php echo $base_url; ?>/add' method='post' id="profileForm">
    <div class="center_img">
        <div id="cropContainerEyecandy"></div>
    </div>
    <div id="setting">
        <table class="set">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>
                        <input type="text" placeholder="4文字以上16文字以下" name="user_name" value="<?php echo $user_name;?>" class="hoge" style="text-align: left;">
                        <div class="error user_name"></div>
                    </td>
                </tr>
                <tr>
                    <th>名前</th>
                    <td>
                        <input type="text" placeholder="1文字以上12文字以下" name="name" value="<?php echo $name;?>" class="hoge" style="text-align: left;">
                        <div class="error name"></div>
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        <input type="text" name="mail_address" value="<?php echo $mail_address;?>" class="hoge" style="text-align: left; ">
                        <div class="error mail_address"></div>
                    </td>
                </tr>
                <tr>
                    <th>パスワード</th>
                    <td>
                        <input type="password" placeholder="8文字以上文字以下" name="password" value="" class="hoge" style="text-align: left; ">
                        <div class="error password"></div>
                    </td>
                </tr>
                <tr>
                    <th>性別</th>
                    <td>
                        <div class="sexRadio">
                            <?php if ($sex==='0'): ?>
                                <input type="radio" name="sex" id="on" value=0 checked>
                                <label for="on" class="switch-on">男</label>
                                <input type="radio" name="sex" id="off" value=1>
                                <label for="off" class="switch-off">女</label>
                            <?php elseif ($sex==='1'): ?>
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
                        <textarea name="introduction" class="hoge" style="text-align: left; "><?php echo $introduction;?></textarea>
                        <div class="error introduction"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="add">
        <paper-button raised class="colored_red" id="submit">新規登録</paper-button>
    </div>
</form>

<script>
    /*cropとuploadを同じファイルで実行*/
    var croppicContainerEyecandyOptions = {
        uploadUrl: BASE_URL+"/profile/getConvertImg",
        imgEyecandy:false,
        loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
    };
    var cropContainerEyecandy = new Croppic('cropContainerEyecandy', croppicContainerEyecandyOptions);

</script>
