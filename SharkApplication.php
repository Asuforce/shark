<?php
class SharkApplication extends Application
{
    //ログインのアクションを設定
    protected $login_action = array('user', 'login');

    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    protected function registerRoutes()
    {
        return array(
            '/'
                => array('controller' => 'home', 'action' => 'index'),

            '/login'
                => array('controller' => 'user', 'action' => 'login'),
            '/add'
                => array('controller' => 'user', 'action' => 'add'),

            '/termsOfService'
                => array('controller' => 'user', 'action' => 'termsOfService'),


            '/logout'
                => array('controller' => 'user', 'action' => 'logout'),

            '/home'
                => array('controller' => 'home', 'action' => 'index'),
            '/home/:action'
                => array('controller' => 'home'),

            '/search'
                => array('controller' => 'search', 'action' => 'index'),
            '/search/:action'
                => array('controller' => 'search'),

            '/message'
                => array('controller' => 'message', 'action' => 'index'),
            '/message/:action'
                => array('controller' => 'message'),
            '/message/conversation/:user_name'
                => array('controller' => 'message','action' => 'chat'),
            '/message/conversation/:user_name/:action'
                => array('controller' => 'message'),

            '/profile/getConvertImg'
                => array('controller' => 'profile', 'action' => 'getConvertImg'),
            '/profile/:user_name'
                => array('controller' => 'profile', 'action' => 'index'),
            '/profile/:user_name/:action'
                => array('controller' => 'profile'),

            '/music'
                => array('controller' => 'music', 'action' => 'index'),
            '/music/:action'
                => array('controller' => 'music'),
        );
    }

    protected function configure()
    {
        $this->db_manager->connect('master', array(
            'dsn'      => 'mysql:dbname=shark;host=localhost',
            'user'     => 'root',
            'password' => 'root',
        ));
    }
}
