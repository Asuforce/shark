<?php
class UserController extends Controller
{
    // ログインしていなければ実行出来ないアクションを設定
    protected $auth_actions = array('logout');

    // ログインアクション
    public function login(){
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }

        if ($this->request->isPost()) {
            $user_name = $this->request->getPost('user_name');
            $password = $this->request->getPost('password');

            // バリデーション(文字入力)チェック
            $errors = $this->db_manager->get('User')->loginValidate($user_name,$password);

            if (count($errors) === 0) {
                $this->session->setAuthenticated(true);
                $user = $this->db_manager->get('User')->fetchByUserName($user_name);

                $this->session->set('user', $user);

                $base_url = $this->request->getBaseUrl();

                header('Content-type: application/json');
                echo json_encode(array('errors' => 0, 'url' => $base_url));
                exit();
            } else {
                header('Content-type: application/json');
                echo json_encode(array('errors' => $errors));
                exit();
            }
        }
        return $this->render(array(
            'errors'   => $errors,
            '_token'    => $this->generateCsrfToken('/login'),
        ));
    }

    //利用規約画面
    public function termsOfService() {
        return $this->render(array(
            '_token'    => $this->generateCsrfToken('user/termsOfService'),
        ));
        exit();
    }

    // ログアウトアクション
    public function logout()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);

        return $this->redirect('/login');
    }

    // 新規登録アクション
    public function add(){
        //既にログインしているかチェック
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }

        // POSTされたかチェック
        if ($this->request->isPost()) {
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
                $pro_image = file_get_contents(dirname(__FILE__)."/img/freeicon.jpg");
            }

            $base_url = $this->request->getBaseUrl();

            $errors = array();

            //バリデーション(文字入力)チェック
            $errors = $this->db_manager->get('User')->addValidate($user_name,$name,$mail_address,$sex,$password,$introduction);

            if (count($errors) === 0) {
                $this->db_manager->get('User')->insert($user_name,$name,$mail_address,$sex,$password,$introduction,$pro_image);

                $this->session->setAuthenticated(true);

                $user = $this->db_manager->get('User')->fetchByUserName($user_name);

                $this->session->set('user', $user);

                header('Content-type: application/json');
                echo json_encode(null);
                exit();
            }else{
                header('Content-type: application/json');
                echo json_encode($errors);
                exit();
            }
        }

        return $this->render(array(
            'user_name' =>$user_name,
            'name' =>$name,
            'mail_address' =>$mail_address,
            'sex' =>$sex,
            'introduction' =>$introduction,
            'errors'   => $errors,
            '_token'    => $this->generateCsrfToken('user/add'),
        ));
    }
}
