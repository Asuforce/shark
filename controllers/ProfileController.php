<?php
class ProfileController extends Controller
{
    //ログインしていなければ実行出来ないアクションを設定
    protected $auth_actions = array('index','edit');

    //プロフィールのページアクション
    public function index($params){
        $person = false;
        $follow_check = false;
        $user = $this->session->get('user');
        //ログインしているユーザと同じか判定。同じの場合、設定アイコンを表示
        if($params['user_name'] === $user['user_name']){
            $person = true;
        }else{
            $profile = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);
            $follow_record = $this->db_manager->get('Profile')->followCheck($user['user_id'],$profile['user_id']);
            $follow_check = $follow_record;
        }

        $profile = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);

        if(!$profile){
            $this->forward404();
        }
        //画像がない場合ははじく
        if($profile['pro_image']){
        $profile['pro_image'] = $this->db_manager->get('Profile')->convertImg($profile['pro_image']);
        }

        return $this->render(array(
            'person' => $person,
            'follow_check' => $follow_check,
            'profile' => $profile,
            '_token'    => $this->generateCsrfToken('profile'),
        ));
    }

    //プロフィール情報の編集アクション
    public function edit(){
        $user = $this->session->get('user');
        $profile = $this->db_manager->get('Profile')->fetchByUserName($user['user_name']);

        if(!$profile){
            $this->forward404();
        }

        if($this->request->isPost()){
            $user_name = $this->request->getPost('user_name');
            $name = $this->request->getPost('name');
            $mail_address = $this->request->getPost('mail_address');
            $sex = $this->request->getPost('sex');
            $password = $this->request->getPost('password');
            $introduction = $this->request->getPost('introduction');

            $imgUrl = $this->request->getPost('imgUrl');
            $imgInitW = $this->request->getPost('imgInitW');
            $imgInitH = $this->request->getPost('imgInitH');
            $imgW = $this->request->getPost('imgW');
            $imgH = $this->request->getPost('imgH');
            $imgY1 = $this->request->getPost('imgY1');
            $imgX1 = $this->request->getPost('imgX1');
            $cropW = $this->request->getPost('cropW');
            $cropH = $this->request->getPost('cropH');

            if($imgY1!==""&&$imgX1!==""){
                $pro_image = $this->db_manager->get('Profile')->cropImg($imgUrl, $imgInitW, $imgInitH,$imgW, $imgH, $imgY1,$imgX1,$cropW,$cropH);
            }else{
                $pro_image = "";
            }

            $errors = array();

            //バリデーション(文字入力)チェック
            $errors = $this->db_manager->get('User')->addValidate($user_name,$name,$mail_address,$sex,$password,$introduction);

            if (count($errors) === 0) {
                $this->db_manager->get('Profile')->update($user['user_id'],$user_name,$name,$mail_address,$sex,$password,$introduction,$pro_image);

                $user = $this->db_manager->get('User')->fetchByUserName($user_name);

                $this->session->set('user', $user);

                $base_url = $this->request->getBaseUrl();

                header('Content-type: application/json');
                echo json_encode(array('errors' => 0, 'url' => $base_url.'/profile/'.$user_name));
                exit();
            }else{
                header('Content-type: application/json');
                echo json_encode(array('errors' => $errors));
                exit();
            }
        }
        return $this->render(array(
            'user' =>$user,
            'profile' => $profile,
            'errors'   => $errors,
            '_token'    => $this->generateCsrfToken('user/add'),
        ));
    }

    public function getConvertImg() {
        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
        $filename = $_FILES["img"]["tmp_name"];
        list($width, $height) = getimagesize($filename);
        $img = $this->db_manager->get('Profile')->convertImg(file_get_contents($_FILES["img"]["tmp_name"]));
        $response = array(
            "status" => 'success',
            "url" => $img,
            "width" => $width,
            "height" => $height
        );
        print json_encode($response);
    }


    // 素材一覧の表示アクション
    public function profileMaterial($params) {
        $user = $this->session->get('user');
        $profile = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);
        $base_url = $this->request->getBaseUrl();

        // 素材一覧を取得
        $materials = $this->db_manager->get('Profile')->fetchAllMaterialList($profile);
        $materials = $this->db_manager->get('Profile')->convertAllImg($materials);

        $results = $this->db_manager->get('Home')->timeLineCreate($materials,$base_url,$user['user_id']);

        header('Content-type: application/json');
        echo json_encode($results);
    }

    // レコード一覧の表示のアクション
    public function profileRecord($params) {
        $user = $this->session->get('user');
        $profile = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);
        $base_url = $this->request->getBaseUrl();

        // レコード一覧を取得
        $records = $this->db_manager->get('Profile')->fetchAllRecordlList($profile);
        $records = $this->db_manager->get('Profile')->convertAllImg($records);

        $results = $this->db_manager->get('Home')->timeLineCreate($records, $base_url, $user['user_id']);

        header('Content-type: application/json');
        echo json_encode($results);
    }

    // お気に入り一覧の表示アクション
    public function profileFavorite($params) {
        $user = $this->session->get('user');
        $profile = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);
        $base_url = $this->request->getBaseUrl();

        // お気に入り一覧を取得
        $favorits = $this->db_manager->get('Profile')->fetchAllFavoriteList($profile['user_id']);
        // 連想配列のキーを変更
        $favorits = $this->db_manager->get('Home')->keyNameChange($favorits);
        // 日付昇順でソート
        $favorits = $this->db_manager->get('Home')->sortDate($favorits);
        //画像をhtml用に変換
        $favorits = $this->db_manager->get('Profile')->convertAllImg($favorits);

        $results = $this->db_manager->get('Home')->timeLineCreate($favorits, $base_url,$user['user_id']);

        header('Content-type: application/json');
        echo json_encode($results);
    }

    //フォローしているユーザ情報取得
    public function follow($params) {
        if($params['user_name']){
            $user = $this->db_manager->get('User')->fetchByUserName($params['user_name']);
            $follows = $this->db_manager->get('Profile')->fetchAllFollow($user['user_id']);
            $follows = $this->db_manager->get('Profile')->convertAllImg($follows);
        }
        return $this->render(array(
            'user_name' => $params['user_name'],
            'follows' => $follows,
            '_token'    => $this->generateCsrfToken('user/add'),
        ));
    }

    //フォローされているユーザ情報取得
    public function follower($params) {
        if($params['user_name']){
            $user = $this->db_manager->get('User')->fetchByUserName($params['user_name']);
            $followers = $this->db_manager->get('Profile')->fetchAllFollower($user['user_id']);
            $followers = $this->db_manager->get('Profile')->convertAllImg($followers);
        }
        return $this->render(array(
            'user_name' => $params['user_name'],
            'followers' => $followers,
            '_token'    => $this->generateCsrfToken('user/add'),
        ));
    }

    //フォロー追加アクション
    public function followManage($params) {
        $user = $this->session->get('user');

        $user = $this->db_manager->get('Profile')->fetchByUserId($user['user_id']);

        $follow_user = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);
        if($this->request->isPost()){
            $flg = $this->request->getPost('flg'); //フォロー追加要求は0、フォロー解除は1
            $this->db_manager->get('Profile')->followUser($flg,$user,$follow_user);
        }
        header('Content-type: application/json');
        echo json_encode(true);
    }
}
