<?php
class MusicController extends Controller
{
    protected $auth_actions = array('index', 'mix');

    //mix保持画面
    public function index() {
        $mixes = ($this->session->get('mix')!==null) ? $this->session->get('mix') : array();
        return $this->render(array(
            'mixes' => $mixes,
            '_token'    => $this->generateCsrfToken('music'),
        ));
    }

    //mix保存画面
    public function mix_save(){
        if($this->request->isPost()){
            $title = $this->request->getPost('title');
            $comment = $this->request->getPost('comment');
            $mixes = ($this->session->get('mix')!==null) ? $this->session->get('mix') : array();
            $user = $this->session->get('user');

            //バリデーション
            $errors = $this->db_manager->get('Music')->mixSaveValidate($title,$comment,$mixes);

            header('Content-type: application/json');
            if(count($errors)!==0){
                //エラー
                echo json_encode($errors);
                exit();
            }else{
                //成功
                $this->db_manager->get('Music')->insertMix($mixes,$title,$comment,$user['user_id']);
                echo json_encode(null);
                exit();
            }
        }
        return $this->render(array(
            '_token'    => $this->generateCsrfToken('music'),
        ));
    }

    //rec画面
    public function rec() {
        return $this->render(array(
            '_token'    => $this->generateCsrfToken('music'),
        ));
    }

    //一時保存
    public function temp_upload() {
        if ($this->request->isPost()) {
            $user = $this->session->get('user');
            $base_url = $this->request->getBaseUrl();
            $wav_data = $this->request->getPost('data');
            $this->db_manager->get('Music')->temp_upload($user, $base_url, $wav_data);
            header('Content-type: application/json');
            echo json_encode(array('url' => '/music/part_save'));
            exit();
        }
    }

    //入力画面
    public function part_save() {
        if ($this->request->isPost()) {
            $base_url = $this->request->getBaseUrl();
            $user = $this->session->get('user');
            $part = $this->request->getPost('part');
            $comment= $this->request->getPost('comment');
            $temp_path = $_SESSION['temp_path'];

            $errors = array();
            $errors = $this->db_manager->get('Music')->checkValidate($part, $comment, $temp_path);

            if (count($errors) === 0) {
                $this->db_manager->get('Music')->insert($user['user_id'], $part, $comment);
                $this->db_manager->get('Music')->upload($user, $base_url, $temp_path);
                header('Content-type: application/json');
                echo json_encode(null);
                exit();
            } else {
                header('Content-type: application/json');
                echo json_encode(array('errors' => $errors));
                exit();
            }
        }
        return $this->render(array(
            // 'temp_path' => $temp_path,
            '_token'    => $this->generateCsrfToken('music'),
        ));
    }

    //mixを追加
    public function mixAdd() {
        $user = $this->session->get('user');
        $base_url = $this->request->getBaseUrl();
        $mix = ($this->session->get('mix')!==null) ? $this->session->get('mix') : array();
        if($this->request->isPost()){
            $type = $this->request->getPost('type');
            $id = $this->request->getPost('id');
            $html = $this->request->getPost('html');
            if($type === '1'){
                $materials = $this->db_manager->get('Music')->getMaterialData($id);
                $materials = $this->db_manager->get('Profile')->convertAllImg($materials);
                $timelines = $this->db_manager->get('Home')->timeLineCreate($materials, $base_url, $user['user_id']);
                for($i=0; $i<count($materials); $i++){
                    $material = $materials[$i];
                    $timeline = $timelines[$i];
                    $mix[] = array(
                        'type' => '0',
                        'id' => $material['material_id'],
                        'html' => $timeline
                    );
                }
            }elseif($type === '0'){
                $mix[] = array(
                    'type' => '0',
                    'id' => $id,
                    'html' => $html
                );
            }
            $this->session->set('mix', $mix);
        }
        header('Content-type: application/json');
        echo json_encode($materials);
        exit();
    }

    //mixを削除
    public function mixDelete() {
        $mixes = $this->session->get('mix');
        if($this->request->isPost()){
            $type = $this->request->getPost('type');
            $id = $this->request->getPost('id');
            for($i=0; $i<count($mixes); $i++) {
                $mix = $mixes[$i];
                if($mix['type']===$type&&$mix['id']===$id){
                    array_splice($mixes, $i,1);//セッションデータ削除
                    break;
                }
            }
            $this->session->set('mix', $mixes);
        }
        header('Content-type: application/json');
        echo json_encode($mixes);
        exit();
    }

    public function getMaterial() {
        $results = array();
        if($this->request->isPost()){
            $id = $this->request->getPost("id");
            $materials = $this->db_manager->get('Music')->getMaterialData($id);
            $materials = $this->db_manager->get('Profile')->convertAllImg($materials);

            foreach ($materials as $material) {
                $results[] = array(
                    'id' => $material['material_id'],
                    'name' => $material['user_name']
                );
            }
        }
        header('Content-type: application/json');
        echo json_encode($results);
        exit();
    }
}