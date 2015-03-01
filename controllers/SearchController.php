<?php
class SearchController extends Controller
{
    //ログインしていなければ実行出来ないアクションを設定
    protected $auth_actions = array('index');

    //検索する前の画面に対するアクション
    public function index(){
        return $this->render(array(
            '_token'    => $this->generateCsrfToken('search'),
        ));
    }

    //検索結果後のアクション
    public function result(){
        $searchs = $this->request->getGet('r');
        $status = $this->request->getGet('status');
        $base_url = $this->request->getBaseUrl();
        $user = $this->session->get('user');
        $user_id = $user['user_id'];

        header('Content-type: application/json');
        if(isset($searchs)&&$searchs!==""&&isset($status)&&$status!==""){
            $searchs = mb_convert_kana($searchs, 's', 'UTF-8');   //全角スペースを半角に変換
            $searchs = preg_split("/[\s]+/",$searchs);
            $results = array();
            switch ($status) {
                //素材
                case "0":
                    $results = $this->db_manager->get('Search')->searchMaterial($searchs);
                    $results = $this->db_manager->get('Home')->timeLineCreate($results, $base_url, $user_id);//htmlを生成
                    break;
                case "1":
                    //レコード
                    $results = $this->db_manager->get('Search')->searchRecord($searchs);
                    $results = $this->db_manager->get('Home')->timeLineCreate($results,  $base_url, $user_id);//htmlを生成
                    break;
                //ユーザ検索
                case "2":
                    $results = $this->db_manager->get('Search')->searchUser($searchs);
                    $results = $this->db_manager->get('Search')->userCreate($results,$base_url);//htmlを生成
                    break;
                default:
                    break;
            }
            echo json_encode($results);
            exit();
        }
        echo json_encode(null);
        exit();
    }

}
