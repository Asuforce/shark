<?php
class Music extends Model
{
    public function checkValidate($part, $comment, $temp_path) {
        $errors = array();

        if (mb_strlen($part) === 0) {
            $errors['part'][] = "パートを選択してください";
        }
        if (mb_strlen($comment)>100) {
            $errors['comment'][] = "コメントは100文字以下にしてください";
        }
        if (mb_strlen($temp_path) === 0) {
            $errors['data'][] = "正しく録音されていません";
        }

        return $errors;
    }

    public function insert($user_id, $instrument_id, $comment) {
        $now = new DateTime();
        $sql ="INSERT INTO material(user_id, instrument_id, submit_date, material_comment)
                VALUES (:user_id, :instrument_id, :now, :comment)";

        $this->execute($sql, array(
            ':user_id' => $user_id,
            ':instrument_id' => $instrument_id,
            ':now' => $now->format('Y-m-d H:i:s'),
            ':comment' => $comment,
            ));
    }

    // 一時ファイルを作成
    public function temp_upload($user, $base_url, $wav_data) {
        $rand_str = $this->makeRandStr();
        $str = $user['user_name'].$rand_str;
        $filename = hash('sha256', $str).'.wav';
        $_SESSION['temp_path'] = $base_url.'/temp/'.$filename;
        $fullpath = mb_substr(dirname(__FILE__), 0, mb_strpos(dirname(__FILE__), '/shark')).$_SESSION['temp_path'];

        $data = substr($wav_data, strpos($wav_data, ",") + 1);
        $decodedData = base64_decode($data);
        $fp = fopen($fullpath , 'wb');
        fwrite($fp, $decodedData);
        fclose($fp);
    }

    // ランダムな16文字を作成
    function makeRandStr() {
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < 8; ++$i) {
            $str .= $chars[mt_rand(0, 61)];
        }
        return $str;
    }

    public function upload($user, $base_url, $temp_path) {
        $sql ="SELECT material_id FROM material WHERE user_id = :user_id ORDER BY submit_date DESC LIMIT 1";
        $material_id = $this->fetch($sql, array(':user_id' => $user['user_id']));

        $path = mb_substr(dirname(__FILE__), 0, mb_strpos(dirname(__FILE__), '/shark'));

        $temp_path = $path.$_SESSION['temp_path'];

        $filename = $user['user_name'].'_'.$material_id['material_id'].'.wav'; // ファイル名
        $new_path = $path.$base_url.'/materials/'.$filename;

        rename($temp_path, $new_path);

        $sql = "UPDATE  material SET material_path = :material_path WHERE material_id = :material_id";
        $this->execute($sql, array(
            ':material_path' => $new_path,
            ':material_id' => $material_id['material_id'],
            ));
    }

    //recordデータからMaterialデータを取得する
    public function getMaterialData($record_id) {
        $sql = "SELECT user.name, user.user_name, material.material_comment,
        profile.pro_image, instrument.instrument_image, material.material_id
        FROM record
        INNER JOIN material ON material.material_id = record.material_id
        INNER JOIN user ON material.user_id = user.user_id
        INNER JOIN profile ON material.user_id = profile.user_id
        INNER JOIN instrument ON material.instrument_id = instrument.instrument_id
        WHERE record.record_id = :record_id";

        return $this->fetchAll($sql,array(':record_id' => $record_id));
    }

    //Mix保存時のバリデーション
    public function mixSaveValidate($title, $comment, $mixes) {
        $errors = array();
        //title1文字以上50文字以内
        if((mb_strlen($title)<1)||(mb_strlen($title)>50)){
            $errors['title'][] =  "タイトルは1文字以上50文字以下にしてください";
        }
        //コメント100文字以内
        if(mb_strlen($comment)>100){
             $errors['comment'][] = "メッセージは100文字以下にしてください";
        }

        //mixの要素があるか
        if(count($mixes) === 0){
            $errors['mix'][] = "mixがありません";
        }

        return $errors;
    }

    //mixデータをinsert
    public function insertMix($mixes,$title,$comment,$user_id) {
        //record_data 新規に作成
        $select_sql = "SELECT MAX(record_id) AS max FROM record_data";
        $result = $this->fetch($select_sql,array());
        $record_id = $result['max']+1;

        $sql = "INSERT INTO record_data(record_id,user_id,record_name, record_date,record_comment)
                VALUES(:record_id,:user_id, :record_name, :record_date, :record_comment)";

        $now = new DateTime();
        $this->execute($sql, array(
            ':record_id' => $record_id,
            ':user_id' => $user_id,
            ':record_name' => $title,
            ':record_date' => $now->format('Y-m-d H:i:s'),
            ':record_comment' => $comment
            ));

        //recordテーブルにinsert
        foreach ($mixes as $mix) {
            $sql = "INSERT INTO record(record_id,material_id)
                VALUES(:record_id,:material_id)";
            $this->execute($sql, array(
                ':record_id' => $record_id,
                ':material_id' => $mix['id']
                ));
        }
    }

}