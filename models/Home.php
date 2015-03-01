<?php
class Home extends Model
{
    // フォローを取得
    public function fetchAllFollowList($user_id) {
        $sql = "SELECT follow_id FROM follow WHERE user_id = :user_id";

        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    // 素材一覧を取得
    public function fetchAllMaterialList($follows) {

        $sql = "SELECT user.name, user.user_name, material.material_comment, profile.pro_image, instrument.instrument_image, material.material_id
                FROM user
                INNER JOIN (material INNER JOIN instrument ON material.instrument_id = instrument.instrument_id) ON user.user_id = material.user_id
                INNER JOIN profile ON user.user_id = profile.user_id
                WHERE user.user_id = ";

        $count = count($follows);   // フォローをカウント

        if ($count !== 0) {         // フォロー1以上の場合
            for ($i=0; $i < $count-1; $i++) {
                $follow = $follows[$i];
                $sql = $sql.$follow['follow_id']." OR user.user_id = ";
            }
            $sql = $sql.$follows[$count-1]['follow_id']." ORDER BY material.submit_date DESC";
            $result = $this->fetchAll($sql,array());

            return $result;
        } else { // フォローが0の場合
            return null;
        }
    }

    // レコード一覧を取得
    public function fetchAllRecordlList($follows) {

        $sql = "SELECT user.name, user.user_name, record_data.record_name, profile.pro_image, record_data.record_id
                FROM user
                INNER JOIN record_data ON user.user_id = record_data.user_id
                INNER JOIN profile ON user.user_id = profile.user_id
                WHERE user.user_id = ";

        $count = count($follows);   //フォローをカウント

        if ($count !== 0) {         // フォロー1以上の場合
            for ($i=0; $i < $count-1; $i++) {
                $follow = $follows[$i];
                $sql = $sql.$follow['follow_id']." OR user.user_id = ";
            }
            $sql = $sql.$follows[$count-1]['follow_id']." ORDER BY record_date DESC";
            $result = $this->fetchAll($sql,array());

            return $result;
        } else {                    // フォローが0の場合
            return null;
        }
    }

    // お気に入りリストを取得
    public function fetchAllFavoriteList($follows) {

        // お気に入り素材を一覧用クエリ
        $materials_sql = "SELECT fav_mate_date, material.material_comment, user.name, user.user_name, profile.pro_image, instrument.instrument_image, material.material_id
                          FROM fav_material
                          INNER JOIN material ON fav_material.material_id = material.material_id
                          INNER JOIN user ON material.user_id = user.user_id
                          INNER JOIN profile ON material.user_id = profile.user_id
                          INNER JOIN instrument ON material.instrument_id = instrument.instrument_id
                          WHERE fav_material.user_id = ";

        // お気に入りレコードを一覧用クエリ
        $records_sql = "SELECT fav_record.fav_reco_date, record_data.record_name, user.name, user.user_name, profile.pro_image, record_data.record_id
                        FROM fav_record
                        INNER JOIN record_data ON fav_record.record_id = record_data.record_id
                        INNER JOIN user ON record_data.user_id = user.user_id
                        INNER JOIN profile ON record_data.user_id = profile.user_id
                        WHERE fav_record.user_id = ";

        $count = count($follows);   // フォローをカウント

        if ($count !== 0) {         // フォローが1以上の場合
            for ($i=0; $i < $count-1; $i++) {
                $follow = $follows[$i];
                $materials_sql = $materials_sql.$follow['follow_id']." OR user.user_id = ";
                $records_sql = $records_sql.$follow['follow_id']." OR user.user_id = ";
            }
            $materials_sql = $materials_sql.$follows[$count-1]['follow_id']." ORDER BY fav_mate_date DESC";
            $records_sql = $records_sql.$follows[$count-1]['follow_id']." ORDER BY fav_reco_date DESC";

            $fav_materials = $this->fetchAll($materials_sql, array());
            $fav_records = $this->fetchAll($records_sql, array());
        }

        // 場合分け
        if(isset($fav_materials[0]['material_id'])) {                   // fav_materialsに値が存在するか確認
            if (isset($fav_records[0]['record_id'])) {                  // fav_recordsに値が存在するか確認
                $result = array_merge($fav_materials, $fav_records);    // 両方存在する場合はマージ
            } else {
                $result = $fav_materials;                               // 素材のみ存在した場合
            }
        } elseif (isset($fav_records[0]['record_id'])) {                // レコードのみ存在した場合
            $result = $fav_records;
        } else return null;                                             // 両方存在しない場合

        return $result;
    }

    // 連想配列のキー名を変更
    public function keyNameChange($arrays) {
        for ($i=0; $i < count($arrays); $i++) {
            // commentとnameのキーをtitleに変更
            $title_m = $arrays[$i]['material_comment'];
            $title_r = $arrays[$i]['record_name'];

            if (isset($title_m)) {
                $arrays[$i] += array('title' => $title_m);
                unset($arrays[$i]['material_comment']);
            } elseif (isset($title_r)) {
                $arrays[$i] += array('title' => $title_r);
                unset($arrays[$i]['record_name']);
            }

            // それぞれの日付カラムのキーをsubmit_dateに変更
            $date_m = $arrays[$i]['fav_mate_date'];
            $date_r = $arrays[$i]['fav_reco_date'];

            if (isset($date_m)) {
                $arrays[$i] += array('submit_date' => $date_m);
                unset($arrays[$i]['fav_mate_date']);
            } elseif (isset($date_r)) {
                $arrays[$i] += array('submit_date' => $date_r);
                unset($arrays[$i]['fav_reco_date']);
            }
        }
        return $arrays;
    }

    // 日付昇順ソート
    public function sortDate($arrays) {
        foreach($arrays as $key => $val) {
            $upd[$key] = $val["submit_date"];
        }
        array_multisort($upd, SORT_DESC, $arrays);

        return $arrays;
    }

   // 素材かレコードかを判定
    public function typeCheck($type,$fav_check,$content_id,$user_id) {
        switch ($type) {
            //素材の場合
            case '0':
                $this->favoriteMaterial($fav_check, $content_id, $user_id);
                break;

            //レコードのお気に入り追加
            case '1':
                $this->favoriteRecord($fav_check, $content_id, $user_id);
                break;
        }
    }
    // お気に入り素材をinsert delete
    public function favoriteMaterial($fav_check, $content_id, $user_id){
        switch ($fav_check) {
            case '0'://お気に入りされていない場合
                $sql = "INSERT INTO fav_material(user_id, material_id, fav_mate_date)
                        VALUES (:user_id, :material_id, now())";

                $this->execute($sql, array(
                    ':user_id' => $user_id,
                    ':material_id' => $content_id
                    ));

                //通知テーブル用にmaterial_idからuser_idを取得
                $info_sql = "SELECT user_id FROM material WHERE material_id = :material_id";
                $other = $this->fetch($info_sql, array(':material_id' => $content_id));
                $this->insertInformation($other['user_id'],$user_id,2);
            break;

            case '1'://お気に入りされている場合
                $sql = "DELETE FROM fav_material
                        WHERE user_id = :user_id AND material_id = :material_id";
                $this->execute($sql, array(
                    ':user_id' => $user_id,
                    ':material_id' => $content_id
                ));
             break;
        }
    }

    // お気に入りレコードをinsert delete
    public function favoriteRecord($fav_check, $content_id, $user_id) {
        if ($fav_check === "0"){
        //お気に入りされていない場合
             $sql = "INSERT INTO fav_record(user_id, record_id, fav_reco_date)
                    VALUES (:user_id, :record_id, now())";

            $this->execute($sql, array(
                ':user_id' => $user_id,
                ':record_id' => $content_id
                ));

            //通知テーブル用にrecord_idからuser_idを取得
            $info_sql = "SELECT user_id FROM record_data WHERE record_data.record_id = :record_id";
            $other = $this->fetch($info_sql, array(':record_id' => $content_id));

            $this->insertInformation($other['user_id'],$user_id,3);

        } else{
        //お気に入りされている場合
            $sql = "DELETE FROM fav_record
                    WHERE user_id = :user_id AND record_id = :record_id";

            $this->execute($sql, array(
                ':user_id' => $user_id,
                ':record_id' => $content_id
                ));
        }
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

    //既にお気に入りに登録されているか判定
    public function favoriteCheck($array, $user_id) {
        if (isset($array['material_id'])){         // 素材の場合
            $sql = "SELECT COUNT(user_id) as count FROM fav_material WHERE user_id = :user_id AND material_id = :material_id";

             $result = $this->fetch($sql, array(
                ':user_id' => $user_id,
                ':material_id' => $array['material_id']
                ));
        }else{                                     // レコードの場合
            $sql = "SELECT COUNT(user_id) as count FROM fav_record WHERE user_id = :user_id AND record_id = :record_id";

            $result = $this->fetch($sql, array(
                ':user_id' => $user_id,
                ':record_id' => $array['record_id']
                ));
        }
        return $result['count'];
    }

    //TimeLine Create
    public function timeLineCreate($contents, $base_url, $user_id) {
        $results = array();
        for ($i=0; $i < count($contents); $i++) {
            $content = $contents[$i];

            // 既にお気に入りに登録されているかを判定
            $flg = $this->favoriteCheck($content, $user_id);
            $flg = ($flg>0) ? 1 : 0;

            // 素材かレコードかを判定 素材0 レコード1
            $type = "";
            if(isset($content['material_id'])){
                $type = 0;
            }elseif(isset($content['record_id'])){
                $type = 1;
            }

            $title = "";
            switch ($content) {
                case $content['material_comment']!==null:
                    $title = $content['material_comment'];
                    break;

                case $content['record_name']!==null:
                    $title = $content['record_name'];
                    break;

                case $content['title']!==null:
                    $title = $content['title'];
                    break;
            }

            if($type) $content_id = $content['record_id'];  // レコードの場合
            else $content_id = $content['material_id'];     // 素材の場合
            array_push($results, "
                <div class='timeline' data-type=".$type." data-id=".$content_id." data-name=".$content['user_name'].">
                    <span>
                        <a href='".$base_url."/profile/".$content['user_name']."'>
                            <img src=".$content['pro_image'].">
                            <h4>".$content['name']."</h4>
                            <h6>".$content['user_name']."</h6>
                        </a>
                        <p>".$title."</p>
                        <div class='ins'>
                            <img src=".$content['instrument_image'].">
                        </div>
                        <div class='star'>
                            <img id = ".$i." src='".$base_url."/img/star".$flg.".png' data-flg = ".$flg."
                            data-type =".$type." data-id = ".$content_id.">
                        </div>
                    </span>
                    <ul>
                        <div class='scrubber'>
                            <img src='".$base_url."/img/volume.png'>
                            <paper-slider class='slider' value='50' min='0' max='100'></paper-slider>
                        </div>
                        <paper-button data-type=".$type." data-id=".$content_id." data-name=".$content['user_name']." class='colored_red addBtn' onclick="."document.querySelector('#addAction').show()".">Add</paper-button>
                    </ul>
                </div>
                "
            );
        }
        return $results;
    }
}