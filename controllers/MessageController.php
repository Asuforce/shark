<?php
class MessageController extends Controller
{
    //ログインしていなければ実行出来ないアクションを設定
    protected $auth_actions = array('index','information','conversation','chat');

    //メッセージ画面のアクション
    public function index() {
        $user = $this->session->get('user');
        $conversations = $this->db_manager->get('Message')->fetchByConversation($user);

        return $this->render(array(
            'user' => $user,
            'conversations' => $conversations,
        ),'index');
    }

    public function information() {
        $user = $this->session->get('user');
        $informations = $this->db_manager->get('Message')->fetchByInformation($user);

        $results = array();
        foreach ($informations as $information){
            $other = $this->db_manager->get('User')->fetchByUserId($information['other_id']);
            $information['other_name'] = $other['name'];
            array_push($results,$this->db_manager->get('Message')->informationCreate($information));
        }
        $this->db_manager->get('Message')->updateInformation($user['user_id']);

        header('Content-type: application/json');
        echo json_encode($results);
    }

    public function conversation() {
        $user = $this->session->get('user');
        $conversations = $this->db_manager->get('Message')->fetchByConversation($user);

        $results = array();
        $base_url = $this->request->getBaseUrl();

        foreach ($conversations as $conversation){
            $conversation['pro_image'] = $this->db_manager->get('Profile')->convertImg($conversation['pro_image']);
            array_push($results,$this->db_manager->get('Message')->conversationCreate($base_url,$conversation));
        }

        header('Content-type: application/json');
        echo json_encode($results);
    }

    //新規の会話作成
    public function newConversation(){
        $user = $this->session->get('user');
        $follows = $this->db_manager->get('Profile')->fetchAllFollow($user['user_id']);
        $follows = $this->db_manager->get('Profile')->convertAllImg($follows);
        return $this->render(array(
            'follows' => $follows,
            '_token'    => $this->generateCsrfToken('user/add'),
        ));
    }

    //チャット一覧を表示させる
    public function chat($params) {
        $user = $this->session->get('user');
        $other = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);

        $user_id = $user['user_id'];
        $other_id = $other['user_id'];

        //フォローしているか
        $auth = $this->db_manager->get('Message')->fetchAuthFollow($user_id,$other_id);
        if(!$auth){
            $this->forward404();
        }

        //会話レコードが存在しているか
        $exitst = $this->db_manager->get('Message')->fetchExistConversation($user_id,$other_id);
        if(!$exitst){
            //存在しなければ、insert
            $conversation_id = $this->db_manager->get('Message')->newConversationCreate($user_id,$other_id);
        }else{
            $conversation_id = $exitst['conversation_id'];
        }

        $conversation = $this->db_manager->get('Message')->fetchByConversationName($user_id,$conversation_id);

        return $this->render(array(
            'conversation' => $conversation,
            '_token' => $this->generateCsrfToken('message/conversation'),
        ));
    }

    //チャット表示の初期に呼ばれるアクション
    public function init($params) {
        if($this->request->isPost()){
            $user = $this->session->get('user');
            $other = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);

            $user_id = $user['user_id'];
            $other_id = $other['user_id'];

            $exitst = $this->db_manager->get('Message')->fetchExistConversation($user_id,$other_id);
            $conversation_id = $exitst['conversation_id'];

            $messages = $this->db_manager->get('Message')->init($user['user_id'],$conversation_id);

            $page = $messages[count($messages)-1]['message_id'];

            $chat_records = $this->db_manager->get('Message')->balloonCreate($user['user_id'],$messages);

            $results = array('chat_records'=>$chat_records,'page'=> $page);

            header('Content-type: application/json');
            echo json_encode($results);
        }
    }

    //チャットの発言を追加させるアクション
    public function add($params) {
        if($this->request->isPost()){
            $user = $this->session->get('user');
            $other = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);

            $user_id = $user['user_id'];
            $other_id = $other['user_id'];

            $exitst = $this->db_manager->get('Message')->fetchExistConversation($user_id,$other_id);
            $conversation_id = $exitst['conversation_id'];

            $body = $this->request->getPost('body');

            $this->db_manager->get('Message')->add($user['user_id'],$conversation_id,$body);
            $messages = array(array('user_id'=> $user['user_id'],'body'=>$body));

            $chat_records = $this->db_manager->get('Message')->balloonCreate($user['user_id'],$messages);

            header('Content-type: application/json');
            echo json_encode($chat_records);
        }
    }

    //現在表示されているレコードより、以前のレコードを30件表示する
    public function pre($params) {
         if($this->request->isPost()){
            $user = $this->session->get('user');
            $other = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);

            $user_id = $user['user_id'];
            $other_id = $other['user_id'];

            $exitst = $this->db_manager->get('Message')->fetchExistConversation($user_id,$other_id);
            $conversation_id = $exitst['conversation_id'];

            $page = $this->request->getPost('page');

            $messages = $this->db_manager->get('Message')->pre($conversation_id, $page);
            $page = $messages[count($messages)-1]['message_id'];
            $chat_records = $this->db_manager->get('Message')->balloonCreate($user['user_id'],$messages);
            $results = array('chat_records'=>$chat_records,'page'=> $page);

            header('Content-type: application/json');
            echo json_encode($results);
         }
    }

    //他の人が発言をしていないか常に確認するアクション
    public function load($params) {
        if($this->request->isPost()){
            $user = $this->session->get('user');
            $other = $this->db_manager->get('Profile')->fetchByUserName($params['user_name']);

            $user_id = $user['user_id'];
            $other_id = $other['user_id'];

            $exitst = $this->db_manager->get('Message')->fetchExistConversation($user_id,$other_id);
            $conversation_id = $exitst['conversation_id'];

            $messages = $this->db_manager->get('Message')->load($user['user_id'],$conversation_id);
            $chat_records = $this->db_manager->get('Message')->balloonCreate($user['user_id'],$messages);

            header('Content-type: application/json');
            echo json_encode($chat_records);
         }
    }

    //会話一覧で未読がある場合は未読数を表示させる
    public function conversationLoad() {
        if($this->request->isPost()){
            $user = $this->session->get('user');
            $conversations = $this->db_manager->get('Message')->fetchByConversation($user);

            $results =  array();

            foreach ($conversations as $conversation){
                $count = $this->db_manager->get('Message')->getConversationCount($conversation['conversation_id'],$conversation['user_id']);//未読数を取得
                array_push($results,array(
                'conversation_id' => $conversation['conversation_id'],
                'count' => $count
                ));
            }
            header('Content-type: application/json');
            echo json_encode($results);
            exit();
        }else{
             header('Content-type: application/json');
            echo json_encode('');
            exit();
        }
    }

    public function noReadLoad() {
        if($this->request->isPost()){
            $user = $this->session->get('user');
            $information = $this->db_manager->get('Message')->fetchNoReadInformation($user['user_id']);
            $conversation = $this->db_manager->get('Message')->fetchNoReadConversation($user['user_id']);

            $count = (int)$information['count'] + (int)$conversation['count'];
            if($count>999){
                $count = "999+";
            }
            header('Content-type: application/json');
            echo json_encode($count);
        }else{
             header('Content-type: application/json');
            echo json_encode('');
        }
    }
}
