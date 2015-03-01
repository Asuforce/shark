<?php
class Search extends Model
{
    //素材検索
    public function searchMaterial($keywords){
        //instrument.instrument_image
        $sql = "SELECT user.name, user.user_name, material.material_comment, profile.pro_image, instrument.instrument_image, material.material_id
        FROM user
        INNER JOIN material ON user.user_id = material.user_id
        INNER JOIN instrument ON material.instrument_id = instrument.instrument_id
        INNER JOIN profile ON user.user_id = profile.user_id
        WHERE material_comment LIKE ";

        $count = count($keywords);
        if($count!==0){
            for ($i=0; $i < $count-1 ; $i++) {
                $keyword = $keywords[$i];
                $sql = $sql." '%{$keyword}%' AND material_comment LIKE ";
            }
            $sql = $sql." '%{$keywords[$count-1]}%' ORDER BY material.submit_date DESC;";
            $results = $this->fetchAll($sql,array());
        }
        return $results;
    }

    //レコード検索
    public function searchRecord($keywords){
        $sql = "SELECT user.name, user.user_name, record_data.record_name, record_data.record_comment, profile.pro_image, record_data.record_id
        FROM user
        INNER JOIN record_data ON user.user_id = record_data.user_id
        INNER JOIN profile ON user.user_id = profile.user_id
        WHERE ";

        $count = count($keywords);
        if($count!==0){
            for ($i=0; $i < $count-1 ; $i++) {
                $keyword = $keywords[$i];
                $sql = $sql." record_name LIKE '%{$keyword}%' OR record_comment LIKE '%{$keyword}%' AND ";
            }
            $sql = $sql." record_name LIKE '%{$keywords[$count-1]}%' OR record_comment LIKE '%{$keywords[$count-1]}%' ORDER BY record_date DESC;";
            $results = $this->fetchAll($sql,array());
        }
        return $results;
    }

    //ユーザ検索
    public function searchUser($keywords){
        $sql = "SELECT user.name,user.user_name,profile.pro_image
        FROM user
        INNER JOIN profile ON user.user_id = profile.user_id
        WHERE";

        $count = count($keywords);
        if($count!==0){
            for ($i=0; $i < $count-1 ; $i++) {
                $keyword = $keywords[$i];
                $sql = $sql." user_name LIKE '%{$keyword}%' OR name LIKE '%{$keyword}%' ";
            }
            $sql = $sql." user_name LIKE '%{$keywords[$count-1]}%' OR name LIKE '%{$keywords[$count-1]}%'";
            $results = $this->fetchAll($sql,array());
        }
        return $results;
    }
    // お気に入り素材を判定
    public function favoriteMaterial($array){
        if ($array['fav_check']) {// お気に入りの場合場合削除
            $sql = "DELETE FROM fav_material
                    WHERE user_id = :user_id AND material_id = :material_id";

            $this->execute($sql, array(
                ':user_id' => $array['user_id'],
                ':material_id' => $array['content_id']
                ));
        } else {                                    // 新規のお気入りの場合追加
            $sql = "INSERT INTO fav_material(user_id, material_id, fav_mate_date)
                    VALUES (:user_id, :material_id, now())";

            $this->execute($sql, array(
                ':user_id' => $array['user_id'],
                ':material_id' => $array['content_id']
                ));
        }
    }

    // お気に入りレコードを判定
    public function favoriteRecord($array) {
        if ($array['fav_check']) {                  // お気に入りの場合場合削除
            $sql = "DELETE FROM fav_record
                    WHERE user_id = :user_id AND record_id = :record_id";

            $this->execute($sql, array(
                ':user_id' => $array['user_id'],
                ':record_id' => $array['content_id']
                ));
        } else {                                    // 新規のお気入りの場合追加
            $sql = "INSERT INTO fav_record(user_id, record_id, fav_reco_date)
                    VALUES (:user_id, :record_id, now())";

            $this->execute($sql, array(
                ':user_id' => $array['user_id'],
                ':record_id' => $array['content_id']
                ));
        }
    }

	// お気に入りボタンの判定
    public function favoriteCheck($array, $user_id) {
        if (isset($array['material_id'])) {         // 素材の場合
            $sql = "SELECT user_id FROM fav_material WHERE user_id = :user_id AND material_id = :material_id";

            $result = $this->fetch($sql, array(
                ':user_id' => $user_id,
                ':material_id' => $array['material_id']
                ));
        } else {                                    // レコードの場合
            $sql = "SELECT user_id FROM fav_record WHERE user_id = :user_id AND record_id = :record_id";

            $result = $this->fetch($sql, array(
                ':user_id' => $user_id,
                ':record_id' => $array['record_id']
                ));
        }
        if (isset($result['user_id'])) return '1';  // 登録されている場合
        else return '0';                            // 登録されていない場合
    }

    //ユーザ用htmlの作成
    public function userCreate($contents) {
        $results = array();
        foreach ($contents as $content) {
            array_push($results,
                "<div class='timeline'>
                <h4>".$content['name']."</h4>
                <img src=".$content['pro_image'].">
                <p>".$content['user_name']."</p>
            </div>"
            );
        }
        return $results;
    }
}
