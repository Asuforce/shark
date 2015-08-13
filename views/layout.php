<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">

    <script type="text/javascript">
        const BASE_URL = "<?php echo $base_url;?>";
    </script>

    <script type="text/javascript" src="<?php echo $base_url; ?>/jquery/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>/jquery/jquery-ui.min.js"></script>
    <script src="<?php echo $base_url; ?>/jquery/badger.js"></script>
    <script src="<?php echo $base_url; ?>/bower_components/webcomponentsjs/webcomponents.js"></script>

    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-shadow/paper-shadow.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-fab/paper-fab.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/core-icons/core-icons.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-icon-button/paper-icon-button.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-slider/paper-slider.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-button/paper-button.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-tabs/paper-tabs.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-toast/paper-toast.html">

    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/style.css" />


    <title>SHARK</title>
</head>
<body>
<div class="header">
        <?php if(isset($back_link)): ?>
            <a href="<?php echo $base_url.$back_link; ?>">
                <paper-icon-button icon="chevron-left" title="chevron-left" class="back"></paper-icon-button>
            </a>
        <?php endif; ?>
        <?php if(isset($set_link)): ?>
            <a href="<?php echo $base_url.$set_link; ?>">
                <paper-icon-button icon="settings" title="settings" class="set"></paper-icon-button>
            </a>
        <?php elseif(isset($follow)): ?>
            <button id="follow_bt" data-flg="<?php echo $follow['flg']; ?>" onclick="<?php echo $follow['function']; ?>"><?php echo $follow['value']; ?></button>
        <?php elseif(isset($new_message_link)): ?>
            <a href="<?php echo $base_url.$new_message_link; ?>">
                <paper-icon-button icon="add" title="add" class="add"></paper-icon-button>
            </a>
        <?php endif; ?>
        <h1><?php if (isset($title)): echo $this->escape($title); endif; ?></h1>
</div>

<div id="main">
    <?php echo $_content; ?>
    <paper-toast class="toastAdd" id='addAction' text='add Mix' duration='1000' style="display:none;"></paper-toast>
    <paper-toast class="toastFav" id='favAction' text='add Favorite' duration='1000' style="display:none;"></paper-toast>
    <paper-toast class="toastDelete" id='favDelete' text='delete Favorite' duration='1000' style="display:none;"></paper-toast>
</div>

<?php if ($session->isAuthenticated()): ?>
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/script.js"></script>
    <?php if($chat_footer!==true): ?>
            <?php if(isset($mix_retain)): ?>
            <div class="playbar">
                <ul>
                    <li><a href="<?php echo $base_url?>/music/rec" ><img src="<?php echo $base_url;?>/img/rec.png"></a></li>
                    <li>
                        <div class="material-grid">
                            <section class="material-amber">
                                <div class="material-icon play" data-icon="play">
                                    <span class="first"></span>
                                    <span class="second"></span>
                                    <span class="third"></span>
                                </div>
                            </section>
                        </div>
                    </li>
                    <li><img id="stop" src="<?php echo $base_url;?>/img/stop.png"></li>
                    <li><a href="<?php echo $base_url?>/music/mix_save"><paper-button class="colored_w">保存</paper-button></a></li>
                </ul>
            </div>
            <?php elseif(isset($mix_rec)): ?>
                <div class="operation">
                    <ul>
                        <li><img src="<?php echo $base_url;?>/img/reset.png" class="reset"></a></li>
                        <li>
                        <div class="material-grid">
                            <section class="material-amber">
                                <div class="material-icon play" data-icon="play">
                                    <span class="first"></span>
                                    <span class="second"></span>
                                    <span class="third"></span>
                                </div>
                            </section>
                        </div>
                        </li>
                        <li>
                            <div id="recbtn"></div>
                        </li>
                        <li>
                            <a>
                                <paper-button class="colored_w">OK</paper-button>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php elseif(isset($mix_save)): ?>
            <div class="playbar">
                <ul>
                    <li>
                        <div class="material-grid">
                            <section class="material-amber">
                                <div class="material-icon play" data-icon="play">
                                    <span class="first"></span>
                                    <span class="second"></span>
                                    <span class="third"></span>
                                </div>
                            </section>
                        </div>
                    </li>
                    <li><img src="<?php echo $base_url;?>/img/stop.png"></li>
                    <li>
                        <a>
                            <paper-button class="colored_w">保存</paper-button>
                        </a>
                    </li>
                </ul>
            </div>
            <?php elseif(isset($part_save)): ?>
            <div class="playbar">
                <ul>
                    <li>
                        <div class="material-grid">
                            <section class="material-amber">
                                <div class="material-icon play" data-icon="play">
                                    <span class="first"></span>
                                    <span class="second"></span>
                                    <span class="third"></span>
                                </div>
                            </section>
                        </div>
                    </li>
                    <li><img id='stopbtn' src="<?php echo $base_url;?>/img/stop.png"></li>
                    <li>
                        <a>
                            <paper-button class="colored_w" id="submit">保存</paper-button>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        <div class="footer">
            <?php
                $request_uri = explode("/", $_SERVER['REQUEST_URI']);
                $viewName = $request_uri[1];
            ?>
            <?php $user = $session->get('user'); ?>
            <ul>
                <li>
                    <a href="/">
                        <?php if($viewName==null): ?>
                            <img src="<?php echo $base_url; ?>/img/home1.png">
                        <?php else: ?>
                            <img src="<?php echo $base_url; ?>/img/home0.png">
                        <?php endif;?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $base_url; ?>/search">
                        <?php if($viewName==="search"): ?>
                            <img src="<?php echo $base_url; ?>/img/search1.png">
                        <?php else: ?>
                            <img src="<?php echo $base_url; ?>/img/search0.png">
                        <?php endif;?>
                    </a>
                </li>
                <li><a></a></li>
                <li>
                    <a href="<?php echo $base_url; ?>/message">
                        <?php if($viewName==="message"): ?>
                            <img src="<?php echo $base_url; ?>/img/message1.png">
                        <?php else: ?>
                            <img src="<?php echo $base_url; ?>/img/message0.png">
                        <?php endif;?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $base_url; ?>/profile/<?php echo $user['user_name']; ?>">
                        <?php if($viewName==="profile"): ?>
                            <img src="<?php echo $base_url; ?>/img/profile1.png">
                        <?php else: ?>
                            <img src="<?php echo $base_url; ?>/img/profile0.png">
                        <?php endif;?>
                    </a>
                </li>
            </ul>
            <div id="MyBadge"></div>
        </div>
        <div id="music_icon">
            <a href="<?php echo $base_url; ?>/music">
                <img src="<?php echo $base_url; ?>/img/mix.png">
            </a>
        </div>
    <?php else: ?>
        <div id="chat_footer">
            <form onsubmit="message_add();return false;" method="post">
                <textarea rows="1" id="message" name="body" value=""onkeyup="resize()"></textarea>
                <input type="submit" value="送信">
            </form>
        </div>
        <div id="end"></div>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
