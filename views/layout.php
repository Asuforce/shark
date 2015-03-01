<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />

    <script type="text/javascript">
        const BASE_URL = "<?php echo $base_url;?>";
    </script>

    <script type="text/javascript" src="<?php echo $base_url; ?>/jquery/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>/jquery/jquery-ui.min.js"></script>
    <!--<script type="text/javascript" src="<?php echo $base_url; ?>/jquery/jquery.ui.touch-punch.min.js"></script>-->

    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/animsition.min.css">

    <!--ページ移動-->
    <script src="<?php echo $base_url; ?>/jquery/jquery.animsition.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/nav.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/nav_left.js"></script>

    <script src="<?php echo $base_url; ?>/bower_components/webcomponentsjs/webcomponents.js"></script>
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/core-drag-drop/core-drag-drop.html">

    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-shadow/paper-shadow.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-fab/paper-fab.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/core-icons/core-icons.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-icon-button/paper-icon-button.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-slider/paper-slider.html">
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-button/paper-button.html">

    <!--通知アイコンのプラグイン(jqueryとcss)-->
    <script src="<?php echo $base_url; ?>/jquery/badger.js"></script>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/badger.css">

    <!--nav部分の動き-->
    <link rel="import" href="<?php echo $base_url; ?>/bower_components/paper-tabs/paper-tabs.html">
    <title>SHARK</title>
</head>
<body>
<paper-shadow class="header" z="1">
    <div class="animsition_left">
        <?php if(isset($back_link)): ?>
            <a href="<?php echo $base_url.$back_link; ?>" class="animsition-link" data-animsition-in="fade-in-up">
                <paper-icon-button icon="chevron-left" title="chevron-left" class="back"></paper-icon-button>
            </a>
        <?php endif; ?>
        <?php if(isset($set_link)): ?>
            <a href="<?php echo $base_url.$set_link; ?>" class="animsition-link" data-animsition-in="fade-in-up">
                <paper-icon-button icon="settings" title="settings" class="set"></paper-icon-button>
            </a>
        <?php elseif(isset($follow)): ?>
            <button id="follow_bt" data-flg="<?php echo $follow['flg']; ?>" onclick="<?php echo $follow['function']; ?>"><?php echo $follow['value']; ?></button>
        <?php elseif(isset($new_message_link)): ?>
            <a href="<?php echo $base_url.$new_message_link; ?>" class="animsition-link" data-animsition-in="fade-in-up">
                <paper-icon-button icon="add" title="add" class="add"></paper-icon-button>
            </a>
        <?php endif; ?>
        <h1><?php if (isset($title)): echo $this->escape($title); endif; ?></h1>
    </div>
</paper-shadow>

<div class="animsition">
    <div id="main">
        <?php echo $_content; ?>
    </div>
</div>

<?php if ($session->isAuthenticated()): ?>
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/script.js"></script>
    <?php if($chat_footer!==true): ?>
            <?php if(isset($mix_retain)): ?>
            <paper-shadow class="playbar" z="1">
                <ul>
                    <li><a href="<?php echo $base_url?>/music/rec"  class="animsition-link" data-animsition-in="fade-in-up"><img src="<?php echo $base_url;?>/img/rec.png"></a></li>
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
                    <li><a href="<?php echo $base_url?>/music/mix_save" class="animsition-link" data-animsition-in="fade-in-up"><paper-button raised class="colored_w">保存</paper-button></a></li>
                </ul>
            </paper-shadow>
            <?php elseif(isset($mix_rec)): ?>
                <paper-shadow class="operation" z="1">
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
                            <!--<img src="<?php echo $base_url;?>/img/recbtn.png" class="recbtn">-->
                            <div id="recbtn"></div>
                        </li>
                        <li>
                            <a class="animsition-link" data-animsition-in="fade-in-up">
                                <paper-button raised class="colored_w">OK</paper-button>
                            </a>
                        </li>
                    </ul>
                </paper-shadow>
            <?php elseif(isset($mix_save)): ?>
            <paper-shadow class="playbar" z="1">
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
                        <a class="animsition-link" data-animsition-in="fade-in-up">
                            <paper-button raised class="colored_w">保存</paper-button>
                        </a>
                    </li>
                </ul>
            </paper-shadow>
            <?php elseif(isset($part_save)): ?>
            <paper-shadow class="playbar" z="1">
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
                        <a class="animsition-link" data-animsition-in="fade-in-up">
                            <paper-button raised class="colored_w" id="submit">保存</paper-button>
                        </a>
                    </li>
                </ul>
            </paper-shadow>
            <?php endif; ?>
        <paper-shadow class="footer" z="1">
            <?php $user = $session->get('user'); ?>
            <ul>
                <li>
                    <a href="<?php echo $base_url; ?>" class="animsition-link" data-animsition-in="fade-in-up">
                        <img src="<?php echo $base_url; ?>/img/home0.png">
                    </a>
                </li>
                <li>
                    <a href="<?php echo $base_url; ?>/search" class="animsition-link" data-animsition-in="fade-in-up">
                        <img src="<?php echo $base_url; ?>/img/search0.png">
                    </a>
                </li>
                <li><a></a></li>
                <li>
                    <a href="<?php echo $base_url; ?>/message">
                        <img id="drop" src="<?php echo $base_url; ?>/img/message0.png">
                    </a>
                </li>
                <li>
                    <a href="<?php echo $base_url; ?>/profile/<?php echo $user['user_name']; ?>" class="animsition-link" data-animsition-in="fade-in-up">
                        <img src="<?php echo $base_url; ?>/img/profile0.png">
                    </a>
                </li>
            </ul>
            <div id="MyBadge"></div>
        </paper-shadow>
        <div id="music_icon">
            <a href="<?php echo $base_url; ?>/music" class="animsition-link" data-animsition-in="fade-in-up">
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
