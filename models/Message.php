<?php
class Message extends Model
{

    // 会話一覧取得
    public function fetchByConversation($user){

    // 外部結合を行い、会話ID、ユーザID、未読数、ユーザ名を取得する
        $sql = "SELECT conversation.conversation_id,conversation.user_id,conversation.count,user.user_name,user.name,profile.pro_image
                FROM conversation
                INNER JOIN user ON conversation.user_id = user.user_id
                INNER JOIN profile ON conversation.user_id = profile.user_id
                WHERE conversation.user_id != :user_id AND conversation.conversation_id
                IN (SELECT conversation_id FROM conversation WHERE conversation.user_id = :user_id);";

        return $this->fetchAll($sql, array(':user_id' => $user['user_id']));

    }
    // 通知一覧取得
    public function fetchByInformation($user) {

        //内部結合を行い、相手のID、ステータス
        $sql = "SELECT other_id,info_status
                FROM information
                WHERE information.user_id = :user_id";

        return $this->fetchAll($sql, array(':user_id' => $user['user_id']));

    }
    // チャットのユーザ名取得
    public function fetchByConversationName($user_id,$conversation_id) {
    //内部結合を行い、会話ID、ユーザID、未読数、ユーザ名を取得する
        $sql = "SELECT conversation.user_id,user.user_name,user.name
                FROM conversation
                INNER JOIN user ON conversation.user_id = user.user_id
                WHERE conversation.user_id != :user_id AND conversation.conversation_id = :conversation_id";

        return $this->fetch($sql, array(':user_id' => $user_id,':conversation_id' => $conversation_id));
    }

    //ユーザIDからフォローしているかを確認
    public function fetchAuthFollow($user_id,$follow_id) {
        $sql = "SELECT user_id FROM follow
        WHERE (user_id = :user_id AND follow_id = :follow_id)
        OR (user_id = :follow_id AND follow_id = :user_id)";
        return $this->fetch($sql,array(':user_id' => $user_id,':follow_id' => $follow_id));
    }

    //チャットを閲覧する権限があるか
    public function fetchExistConversation($user_id,$other_id) {
        $sql = " SELECT conv1.conversation_id,conv1.user_id, conv2.user_id as other_id, conv1.count
                FROM conversation conv1
                INNER JOIN conversation conv2 on conv1.user_id<> conv2.user_id
                AND conv1.conversation_id=conv2.conversation_id
                WHERE conv1.user_id = :user_id AND conv2.user_id = :other_id";

        return $this->fetch($sql,array(
            ':user_id' => $user_id,
            'other_id' => $other_id,
            ));
    }

    //会話テーブルを新しく作成する
    public function newConversationCreate($user_id,$other_id) {
        $select_sql = "SELECT MAX(conversation_id) AS max FROM conversation";

        $result = $this->fetch($select_sql,array());

        $conversation_id = $result['max']+1;

        $sql = "INSERT INTO conversation(conversation_id,user_id,count)
                VALUES(:conversation_id,:user_id,0),
                (:conversation_id,:other_id,0)";

        $this->execute($sql, array(
            ':conversation_id' => $conversation_id,
            ':user_id' => $user_id,
            ':other_id' => $other_id,
            ));

        return $conversation_id;
    }

    // 新しく発言された時に実行されるメソッド
    public function add($user_id,$conversation_id,$body) {
        $now = new DateTime();

    // メッセージテーブルにレコードを追加
        $add_sql = "INSERT INTO message(conversation_id,user_id,body,is_read,created)
                    VALUES(:conversation_id,:user_id,:body,0,:created)";

        $user_stmt = $this->execute($add_sql, array(
            ':conversation_id'  => $conversation_id,
            ':user_id'   => $user_id,
            ':body' => $body,
            ':created' => $now->format('Y-m-d H:i:s'),
            ));

    // 先ほどの未読数をカウントし、会話テーブルに反映させる
        $no_read_sql = "UPDATE conversation AS conv SET count= (
                        SELECT COUNT(message_id) FROM message AS mes
                        WHERE mes.conversation_id=:conversation_id AND mes.user_id=:user_id AND mes.is_read=0
                        )WHERE conv.conversation_id = :conversation_id AND conv.user_id!=:user_id";

        $this->execute($no_read_sql, array(
            ':conversation_id'  => $conversation_id,
            ':user_id'   => $user_id,
            ));
    }

    // chat画面が最初に呼ばれた時、最新のレコード15件を呼び出す
    public function init($user_id,$conversation_id) {
        // 最新のメッセージ内容15件をselect
        $init_sql = "SELECT message_id,conversation_id, user_id, body, is_read, created
                    FROM message
                    WHERE conversation_id = :conversation_id
                    ORDER BY message_id DESC LIMIT 15
                    ";

        // 自分以外のユーザIDで未読のものを既読に変更
        $no_record_sql = "UPDATE message SET is_read = 1
                        WHERE conversation_id = :conversation_id AND user_id != :user_id AND is_read = 0";

        $this->execute($no_record_sql, array(
            ':conversation_id' => $conversation_id,
            ':user_id' => $user_id,
            ));
        $messages = $this->fetchAll($init_sql, array(':conversation_id' => $conversation_id,));

        return $messages;
    }

    // 現在表示されている履歴より以前の履歴を30件取得する
    public function pre($conversation_id,$page){
        // 現在表示している中で最も古いメッセージIDを判別(30以下なら残りの全てを渡す。それ以外は30件)
        if($page<30){
            $record_now = 0;
            $record_num = $page-1;
        }else{
            $record_now = $page-31;
            $record_num =30;
        }

        // 以前のデータ30件取得
        $pre_sql = "SELECT message_id,user_id,body,is_read,created
                    FROM message
                    WHERE conversation_id = :conversation_id
                    ORDER BY message_id LIMIT  $record_now , $record_num";
            // 文字列に変更させないため、直接入力

        $stmt = $this->execute($pre_sql, array(
            ':conversation_id' => $conversation_id,
            ));

        $pre_messages = $stmt->fetchAll();

        // 最新のデータからクライアント側で取得させるため、ソートして調整
        if(count($pre_messages)!==0){
            foreach ($pre_messages as $key => $value){
                $key_id[$key] = $value['message_id'];
            }
            array_multisort ($key_id,SORT_DESC,$pre_messages);
        }
        return $pre_messages;
    }

    // 現在新しいレコードが入っているか入っているか、確認させる
    public function load($user_id,$conversation_id) {
        // 自分が未読のものを取得
        $no_read_sql = "SELECT message_id,user_id,body,is_read,created
                        FROM message
                        WHERE conversation_id=:conversation_id AND user_id!=:user_id AND is_read = 0";

        $no_read_stmt = $this->execute($no_read_sql, array(
            ':conversation_id' => $conversation_id,
            ':user_id' => $user_id,
            ));

        // 他人が発言したmessageの未読のものを既読に変更
        $no_read_update_sql = "UPDATE message SET is_read=1
                                WHERE conversation_id=:conversation_id AND user_id!=:user_id";

        $this->execute($no_read_update_sql, array(
            ':conversation_id' => $conversation_id,
            ':user_id' => $user_id,
            ));

        // 会話テーブルの未読数を更新
        $no_read_count_sql = "UPDATE conversation AS conv SET count= (
                            SELECT COUNT(message_id) FROM message AS mes
                            WHERE mes.conversation_id=:conversation_id AND mes.user_id !=:user_id AND mes.is_read=0
                            )WHERE conv.conversation_id = :conversation_id AND conv.user_id = :user_id";

        $this->execute($no_read_count_sql, array(
            ':conversation_id' => $conversation_id,
            ':user_id' => $user_id,
        ));

        // 他人の発言を取得
        return $no_read_stmt->fetchAll();
    }

    // チャット用のhtml生成
    public function balloonCreate($user_id,$messages) {
        $results = array();
        foreach ($messages as $message){
            $float = 'left';

            if($message['user_id']===$user_id){
                $float = 'right';
            }

            array_push($results, "
                <div class='balloon'>
                    <div class='balloon-".$float."-foot-outer'>
                        <div class='balloon-".$float."-foot-inner'></div>
                    </div>
                    <div class='balloon-".$float."-body'>
                        <p>".$message['body']."</p>
                    </div>
                    <div class='clear-float'></div>
                </div>");
        }
        return $results;
    }
    // 通知用のhtml作成
    public function informationCreate($content) {
        return
            "<div class='timeline'>
                <p>".$content['other_name'].$this->getInfoStatus($content['info_status'])."</p>
            </div>";
    }

    public function getConversationCount($conversation_id,$user_id){
        $sql = " SELECT conv.count
                FROM conversation conv
                WHERE conv.conversation_id = :conversation_id AND conv.user_id != :user_id";
        $result =  $this->fetch($sql,array(
            ':conversation_id' => $conversation_id,
            ':user_id' => $user_id,
            ));
        if($result['count'] !== '0'){
            return $result['count'];
            exit();
        }
        return '';
    }

    // 会話用のhtml作成
    public function conversationCreate($base_url,$content) {
        $count = $this->getConversationCount($content['conversation_id'],$content['user_id']);

        $result ="<div class='user'>
                    <a href=".$base_url."/message/conversation/".$content['user_name'].">
                        <div id=".$content['conversation_id']." class='noRead'>".$count."</div>
                        <img src=".$content['pro_image'].">
                        <p>".$content['name']."</p>
                    </a>
                </div>";

        return $result;
    }

    //通知情報の判定 1=フォロー　2=素材お気に入り　3=レコードお気に入り　4=素材使用
    public function getInfoStatus($status) {
        switch ($status) {
            case 1:
                $result = "さんにフォローされました";
                break;
             case 2:
               $result = "さんがあなたの素材をお気に入りに追加しました";
                break;
             case 3:
               $result = "さんがあなたのレコードをお気に入りに追加しました";
                break;
             case 4:
                $result = "さんがあなたの素材をを使用しました";
                break;
            default:
                $result = 0;
                break;
        }
        return $result;
    }

    //通知レコードにinsert
    //1=フォロー　2=素材お気に入り　3=レコードお気に入り　4=素材使用
    public function insertInformation($user_id, $other_id,$status) {
         $sql = "INSERT INTO information(user_id, other_id, read_check, info_status, info_date)
                VALUES (:user_id, :other_id, 0, :info_status, now())";

        $this->execute($sql, array(
            ':user_id' => $user_id,
            ':other_id' => $other_id,
            ':info_status' => $status
            ));
    }

    //通知レコードの未読を既読にupdate
    public function updateInformation($user_id) {
         $sql = "UPDATE information SET read_check = 1
                WHERE user_id = :user_id AND read_check = 0";

        $this->execute($sql, array(
            ':user_id' => $user_id
            ));
    }

    //未読のレコード数をカウント
    public function fetchNoReadInformation($user_id) {
        $sql = "SELECT COUNT(user_id) as count FROM information WHERE read_check = 0 AND user_id = :user_id";

        return $this->fetch($sql, array(':user_id' => $user_id));
    }

    public function fetchNoReadConversation($user_id) {
        $sql = "SELECT SUM(count) as count FROM conversation WHERE user_id = :user_id";

        return $this->fetch($sql, array(':user_id' => $user_id));
    }

}
