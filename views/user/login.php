<?php $this->setLayoutVar('title', 'Login') ?>

<link rel="stylesheet"type="text/css" href="<?php echo $base_url; ?>/css/login.css">
<script type="text/javascript" src="<?php echo $base_url;?>/js/login.js"></script>

<?php if (isset($errors) && count($errors) > 0): ?>
<?php echo $this->render('errors', array('errors' => $errors)) ?>
<?php endif; ?>

<div id="logo">
    <img src="<?php echo $base_url; ?>/img/logo.png">
</div>
<form action='<?php echo $base_url; ?>/login' method='post' id='loginForm'>
    <!--ID入力-->
    <div id="id">
        <h2>ID</h2>
        <input type='text' name='user_name' value='' />
        <div class="error user_name"></div>
    </div>
    <!--PASS入力-->
    <div id="pass">
        <h2>PASS</h2>
        <input type='password' name='password' value='' />
        <div class="error password"></div>
        <div class="error wrong"></div>
    </div>
    <!--login入力-->
    <div id="login">
        <paper-button raised class="colored_red" id="submit">ログイン</paper-button>
    </div>
</form>
<!--新規登録-->
<div id="new_account">
    <a href="<?php echo $base_url; ?>/termsOfService" class="animsition-link" data-animsition-in="fade-in-up"><h3>新規登録はこちら</h3></a>
</div>
