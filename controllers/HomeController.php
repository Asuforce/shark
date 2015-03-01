<?php
class HomeController extends Controller
{
    // ログインしていなければ実行出来ないアクションを設定
    protected $auth_actions = array('index', 'material', 'reord', 'favorite', 'favoriteManage');

    // ホームのトップページアクション
    public function index(){
        return $this->render(array(
            '_token'    => $this->generateCsrfToken('home'),
        ));
     }

     // 素材一覧の表示アクション
    public function material() {
        $user = $this->session->get('user');
        $base_url = $this->request->getBaseUrl();

        // フォローからidを取得
        $follows = $this->db_manager->get('Home')->fetchAllFollowList($user['user_id']);

        // 素材一覧を取得
        $materials = $this->db_manager->get('Home')->fetchAllMaterialList($follows);

        header('Content-type: application/json');

        if($materials === null) {
            echo json_encode(0);
        } else {
            // htmlを作成
            $results = $this->db_manager->get('Home')->timeLineCreate($materials, $base_url, $user['user_id']);
            echo json_encode($results);
        }
    }

    // レコード一覧の表示のアクション
    public function record() {
        $user = $this->session->get('user');
        $base_url = $this->request->getBaseUrl();

        // フォローからidを取得
        $follows = $this->db_manager->get('Home')->fetchAllFollowList($user['user_id']);

        // レコード一覧を取得
        $records = $this->db_manager->get('Home')->fetchAllRecordlList($follows);

        header('Content-type: application/json');

        if ($records == null) {
            echo json_encode(0);
        } else {
            // htmlを作成
            $results = $this->db_manager->get('Home')->timeLineCreate($records, $base_url, $user['user_id']);

            echo json_encode($results);
        }
    }

    // お気に入り一覧の表示アクション
    public function favorite() {
        $user = $this->session->get('user');
        $base_url = $this->request->getBaseUrl();

        // フォローからidを取得
        $follows = $this->db_manager->get('Home')->fetchAllFollowList($user['user_id']);

        // お気に入り一覧を取得
        $favorites = $this->db_manager->get('Home')->fetchAllFavoriteList($follows);
        // 連想配列のキーを変更
        $favorites = $this->db_manager->get('Home')->keyNameChange($favorites);
        // 日付昇順でソート
        $favorites = $this->db_manager->get('Home')->sortDate($favorites);
        //画像をhtml用に変換
        $favorites = $this->db_manager->get('Profile')->convertAllImg($favorites);

        header('Content-type: application/json');

        if ($favorites != null) {
            // htmlを作成
            $results = $this->db_manager->get('Home')->timeLineCreate($favorites, $base_url, $user['user_id']);
            echo json_encode($results);
        } else {
            echo json_encode(0);
        }
    }

    // お気に入り追加アクション
    public function favoriteManage() {
        $user = $this->session->get('user');

        if($this->request->isPost()){
            $type = $this->request->getPost('type'); //素材の場合(0) レコードの場合(1)
            $fav_check = $this->request->getPost('fav_check'); //お気に入り未(0) お気に入り済(1)
            $content_id = $this->request->getPost('id'); //material_idまたはrecord_id
        }

        $this->db_manager->get('Home')->typeCheck($type,$fav_check,$content_id,$user['user_id']);
        header('Content-type: application/json');
        echo json_encode(true);
        exit();
    }
}
