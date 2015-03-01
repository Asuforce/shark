<?php
class User extends Model
{
    // 新規登録の情報をDBにINSERTさせる
    public function insert($user_name,$name,$mail_address,$sex,$password,$introduction,$pro_image) {
        $password = $this->hashPassword($user_name, $password);
        $now = new DateTime();

        $user_sql = "INSERT INTO user(user_name,password,name,mail_address,created)
                VALUES(:user_name, :password,:name,:mail_address, :created)
        ";

        $user_stmt = $this->execute($user_sql, array(
            ':user_name'  => $user_name,
            ':name' => $name,
            ':password'   => $password,
            ':mail_address' => $mail_address,
            ':created' => $now->format('Y-m-d H:i:s'),
        ));

        $user = $this->insertId($user_name);

        // conf/my.conf 1M→16M
        $profile_sql = "INSERT INTO profile(user_id,sex,introduction, follow_count,follower_count,pro_image)
                VALUES(:user_id, :sex,:introduction, :follow_count,:follower_count,:pro_image)
        ";

        $stmt = $this->execute($profile_sql, array(
            ':user_id'  => $user['user_id'],
            ':sex'   => $sex,
            ':introduction' => $introduction,
            ':follow_count' => 0,
            ':follower_count' => 0,
            ':pro_image' => $pro_image,
        ));
    }

    // ハッシュ化の処理
    public function hashPassword($user_name, $password) {
        $salt = $this->get_sha256($user_name);
        return $this->get_sha256($salt . $password);
    }

    // 文字列から SHA256 のハッシュ値を取得
    public function get_sha256($target) {
      return hash('sha256', $target);
    }

    // 新規登録の文字入力チェック
    public function addValidate($user_name,$name,$mail_address,$sex,$password,$introduction) {
        $errors = array();

        // バリデーション項目
        // ・ユーザ名
        // ・名前
        // ・メールアドレス
        // ・性別
        // ・パスワード
        // ・自己紹介文

        // ユーザ名が4文字以上16文字以内かどうか
        if((mb_strlen($user_name)<4)||(mb_strlen($user_name)>16)){
            $errors['user_name'][] =  "ユーザ名は4文字以上16文字以下にしてください";
        }
        $user = $this->fetchByUserName($user_name);

        if($user){
            $errors['user_name'][] = "このIDは既に使用されています";
        }

        // 名前が1文字以上12文字以下かどうか
        if((mb_strlen($name)>16)||(mb_strlen($name)<1)){
           $errors['name'][] = "名前は1文字以上12文字以下にしてください";
        }

        // メールアドレスが256文字以下かどうか
        if(mb_strlen($mail_address)>256){
            $errors['mail_address'][] = "メールアドレスは256文字以下にしてください";
        }

        // メールアドレス の妥当性チェック
        if(!(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\._-]+)+$/", $mail_address))){
            $errors['mail_address'][] = "メールアドレスが正しく入力されていません";
        }

        // 性別(男が0、女が1)
        if(($sex!=='0')&&($sex!=='1')){
            $errors['sex'][] = "性別を正しく入力してください";
        }

        // passwordが8文字以上32文字以内かどうか
        if((strlen($password)<8)||(strlen($password)>32)){
            $errors['password'][] =  "パスワードの文字数は8文字以上32文字以下にしてください";
        }

        // passwordが半角英数字のみになっているかどうか
        if(!(preg_match("/^[a-zA-Z0-9]+$/", $password))){
            $errors['password'][] =  "半角英数字のみを入力してください";
        }

        // メッセージが100文字以下かどうか
        if(mb_strlen($message)>100){
             $errors['introduction'][] = "メッセージは100文字以下にしてください";
        }

        return $errors;
    }

    // ログインの文字入力チェック
    public function loginValidate($user_name,$password) {
        $errors = array();

        if (!strlen($user_name)) {
            $errors['user_name'][] = 'ユーザIDを入力してください';
        }

        if (!strlen($password)) {
            $errors['password'][] = 'パスワードを入力してください';
        }

        if (count($errors) === 0) {
            $errors = array();
            $user = $this->fetchByUserName($user_name);

            if (!$user || ($user['password'] !== $this->hashPassword($user_name, $password))) {
                $errors['wrong'][] = 'ユーザIDかパスワードが間違っています';
            }
        }
        return $errors;
    }

    // ユーザ名からユーザのID取得
    public function insertId($user_name) {
        $sql = "SELECT user_id FROM user WHERE user_name = :user_name";

        return $this->fetch($sql, array(':user_name' => $user_name));
    }

    // ユーザ名からユーザ情報取得
    public function fetchByUserName($user_name) {
        $sql = "SELECT user_id, user_name, password, name, mail_address, created FROM user WHERE user_name = :user_name";

        return $this->fetch($sql, array(':user_name' => $user_name));
    }
    // ユーザIDからユーザ情報取得
    public function fetchByUserId($user_id) {
        $sql = "SELECT user_id, user_name, password, name, mail_address, created FROM user WHERE user_id = :user_id";

        return $this->fetch($sql, array(':user_id' => $user_id));
    }
}