<?php
class Profile extends Model
{
    // ユーザIDからプロフィール情報取得
    public function fetchByUserId($user_id) {
        $sql = "SELECT user_id, sex, introduction, pro_image
                FROM profile WHERE user_id = :user_id";

        $result = $this->fetch($sql, array(':user_id' => $user_id));

        $follow_sql = "SELECT COUNT(follow_id) as count FROM follow WHERE user_id = :user_id";

        $follow_c = $this->fetch($follow_sql, array(':user_id' => $user_id));

        $follower_sql = "SELECT COUNT(follow_id) as count FROM follow WHERE follow_id = :follow_id";

        $follower_c = $this->fetch($follower_sql, array(':follow_id' => $user_id));

        $result['follow_count'] = $follow_c['count'];
        $result['follower_count'] = $follower_c['count'];

        return $result;
    }

    // ユーザ名からユーザ情報取得
    public function fetchByUserName($user_name) {
        $sql = "SELECT user.user_name, user.name, profile.user_id,profile.sex,profile.introduction,profile.pro_image
                FROM profile
                INNER JOIN user ON user.user_id = profile.user_id
                WHERE user.user_name = :user_name";

        $result = $this->fetch($sql, array(':user_name' => $user_name));

        $follow_sql = "SELECT COUNT(follow_id) as count FROM follow WHERE user_id = :user_id";

        $follow_c = $this->fetch($follow_sql, array(':user_id' => $result['user_id']));

        $follower_sql = "SELECT COUNT(follow_id) as count FROM follow WHERE follow_id = :follow_id";

        $follower_c = $this->fetch($follower_sql, array(':follow_id' => $result['user_id']));

        $result['follow_count'] = $follow_c['count'];
        $result['follower_count'] = $follower_c['count'];

        return $result;
    }

    // ユーザ、プロフィール情報をDBにUPDATEさせる
    public function update($user_id,$user_name,$name,$mail_address,$sex,$password,$introduction,$pro_image) {
        $password = $this->hashPassword($user_name, $password);

        $user_sql = "UPDATE user
                    SET user_name = :user_name, password = :password, name = :name, mail_address = :mail_address
                    WHERE user_id = :user_id
        ";

        $user_stmt = $this->execute($user_sql, array(
            ':user_name'  => $user_name,
            ':password'   => $password,
            ':name'  => $name,
            ':mail_address' => $mail_address,
            ':user_id' => $user_id,
        ));

        if($pro_image!==""){
            $profile_sql = "UPDATE profile
                            SET sex = :sex,introduction = :introduction, pro_image = :pro_image
                            WHERE user_id = :user_id
            ";

            $stmt = $this->execute($profile_sql, array(
                ':user_id'  => $user_id,
                ':sex'   => $sex,
                ':introduction' => $introduction,
                ':pro_image' => $pro_image,
            ));
        }else{
            $profile_sql = "UPDATE profile SET sex = :sex,introduction = :introduction WHERE user_id = :user_id";

            $stmt = $this->execute($profile_sql, array(
                ':user_id'  => $user_id,
                ':sex'   => $sex,
                ':introduction' => $introduction
            ));
        }
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

    // 素材一覧を取得
    public function fetchAllMaterialList($user) {
        $sql = "SELECT user.user_name,user.name,material.material_id,material.material_comment, profile.pro_image, instrument.instrument_image
                FROM user
                INNER JOIN (material INNER JOIN instrument ON material.instrument_id = instrument.instrument_id) ON user.user_id = material.user_id
                INNER JOIN profile ON user.user_id = profile.user_id
                WHERE user.user_id = :user_id
                ORDER BY material.submit_date DESC;";

        return $this->fetchAll($sql,array(':user_id' => $user['user_id']));
    }

    // レコード一覧を取得
    public function fetchAllRecordlList($user) {
        $sql = "SELECT record_data.record_id,user.name,user.user_name, record_data.record_name, profile.pro_image
                FROM user
                INNER JOIN record_data ON user.user_id = record_data.user_id
                INNER JOIN profile ON user.user_id = profile.user_id
                WHERE user.user_id = :user_id";

        return $this->fetchAll($sql,array(':user_id' => $user['user_id']));
    }

    // お気に入りリストを取得
    public function fetchAllFavoriteList($user_id) {
        $materials_sql = "SELECT fav_mate_date,material.material_id,material.material_comment, user.name,user.user_name, profile.pro_image, instrument.instrument_image
                        FROM fav_material
                        INNER JOIN material ON fav_material.material_id = material.material_id
                        INNER JOIN user ON material.user_id = user.user_id
                        INNER JOIN profile ON material.user_id = profile.user_id
                        INNER JOIN instrument ON material.instrument_id = instrument.instrument_id
                        WHERE fav_material.user_id = :user_id
                        ORDER BY fav_mate_date DESC
                        ";
        $records_sql = "SELECT fav_record.fav_reco_date,record_data.record_id, record_data.record_name, user.name,user.user_name, profile.pro_image
                        FROM fav_record
                        INNER JOIN record_data ON fav_record.record_id = record_data.record_id
                        INNER JOIN user ON record_data.user_id = user.user_id
                        INNER JOIN profile ON record_data.user_id = profile.user_id
                        WHERE fav_record.user_id = :user_id
                        ORDER BY fav_reco_date DESC
                        ";
        $fav_materials = $this->fetchAll($materials_sql, array(':user_id' => $user_id));
        $fav_records = $this->fetchAll($records_sql, array(':user_id' => $user_id));

        return array_merge($fav_materials, $fav_records);
    }

    //ユーザIDからフォロー一覧を取得
    public function fetchAllFollow($user_id) {
        $sql = "SELECT user.user_name,user.name, profile.pro_image
                FROM follow
                INNER JOIN user ON follow.follow_id = user.user_id
                INNER JOIN profile ON follow.follow_id = profile.user_id
                WHERE follow.user_id = :user_id;";

        return $this->fetchAll($sql,array(':user_id' => $user_id));
    }

    //ユーザIDからフォロワー一覧を取得
    public function fetchAllFollower($user_id) {
        $sql = "SELECT user.user_name,user.name, profile.introduction, profile.pro_image
                FROM follow
                INNER JOIN user ON follow.user_id = user.user_id
                INNER JOIN profile ON follow.user_id = profile.user_id
                WHERE follow.follow_id = :user_id;";

        return $this->fetchAll($sql,array(':user_id' => $user_id));
    }

    // フォローチェック
    public function followCheck($user_id,$follow_id) {
        $sql = "SELECT user_id FROM follow WHERE user_id = :user_id AND follow_id = :follow_id";

        return $this->fetch($sql, array(
            ':user_id' => $user_id,
            ':follow_id' => $follow_id
            ));
    }

    //フォローを判定
    public function followUser($flg, $user, $follow){
        if($flg === "0") {
            //フォローされていない場合
            $insert_sql = "INSERT INTO follow(user_id, follow_id, follow_date)
                    VALUES (:user_id, :follow_id, now())";

            $this->execute($insert_sql, array(
                ':user_id' => $user['user_id'],
                ':follow_id' => $follow['user_id']
                ));

            //通知テーブルにレコード追加
            $this->insertInformation($follow['user_id'],$user['user_id'],1);

            //プロフィールテーブルのフォローカウント、フォロワーカウントを変更
            $follow_update_sql = "UPDATE profile
                    SET follow_count = :follow_count
                    WHERE user_id = :user_id
            ";

            $this->execute($follow_update_sql, array(
                ':follow_count'   => $user['follow_count']+1,
                ':user_id' => $user['user_id'],
            ));

            $follower_update_sql = "UPDATE profile
                    SET follower_count = :follower_count
                    WHERE user_id = :user_id
            ";

            $this->execute($follower_update_sql, array(
                ':follower_count'  => $follow['follower_count']+1,
                ':user_id' => $follow['user_id'],
            ));

        } else{
            //フォローされている場合
            $delete_sql = "DELETE FROM follow
                    WHERE user_id = :user_id AND follow_id = :follow_id";

            $this->execute($delete_sql, array(
                ':user_id' => $user['user_id'],
                ':follow_id' => $follow['user_id']
                ));

            //プロフィールテーブルのフォローカウント、フォロワーカウントを変更
            $follow_update_sql = "UPDATE profile
                    SET follow_count = :follow_count
                    WHERE user_id = :user_id
            ";

            $this->execute($follow_update_sql, array(
                ':follow_count'   => $user['follow_count']-1,
                ':user_id' => $user['user_id'],
            ));

            $follower_update_sql = "UPDATE profile
                    SET follower_count = :follower_count
                    WHERE user_id = :user_id
            ";

            $this->execute($follower_update_sql, array(
                ':follower_count'  => $follow['follower_count']-1,
                ':user_id' => $follow['user_id'],
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

    //画像を正方形にcropping
    public function cropImg($imgUrl, $imgInitW, $imgInitH,$imgW, $imgH, $imgY1,$imgX1,$cropW,$cropH) {
    //画像データを正方形にリサイズします
        $what = getimagesize($imgUrl);
        switch(strtolower($what['mime']))
        {
            case 'image/png':
                $img_r = imagecreatefrompng($imgUrl);
                $source_image = imagecreatefrompng($imgUrl);
                $type = '.png';
                break;
            case 'image/jpeg':
                $img_r = imagecreatefromjpeg($imgUrl);
                $source_image = imagecreatefromjpeg($imgUrl);
                $type = '.jpeg';
                break;
            case 'image/gif':
                $img_r = imagecreatefromgif($imgUrl);
                $source_image = imagecreatefromgif($imgUrl);
                $type = '.gif';
                break;
            default: die('image type not supported');
        }

        $resizedImage = imagecreatetruecolor($imgW, $imgH);
        imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW,
                    $imgH, $imgInitW, $imgInitH);

        $dest_image = imagecreatetruecolor($cropW, $cropH);
        imagecopyresampled($dest_image, $resizedImage, 0, 0, $imgX1, $imgY1, $cropW,
                    $cropH, $cropW, $cropH);

        ob_start();
        imagejpeg($dest_image);
        $update_img = ob_get_contents();
        ob_end_clean();

        return $update_img;
    }
}