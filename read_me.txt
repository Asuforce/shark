前提事項確認 ver0_7
php 5.5.10
mysql 
Apache 2.2.26

このフレームワークは「パーフェクトPHP」といった、参考書を基に作成されています。

参考書との主な変更点は2つ

・モデル名の定義が
変更前：UserRepository.php
変更後：User.php
といった形に簡略化してあります。

・コントローラのアクション定義も
変更前：public function loginAction()
変更後：public function login()
といった形に変更されています。

ver0_7の主な変更点
・録音機能を追加
・D&D機能を追加
・cssを修正

ver0_6の主な変更点
・crop機能を追加
・MDを導入

ver0_5の主な変更点
・ミュージックMVCを実装

ver0_4の主な変更点
・プロフィール情報(画像、名前)の追加
・ホームのタイムライン表示
・お気に入りボタンを実装
・通知機能の導入
・画像を正方形にリサイズして保存

ver0_3の主な変更点
・メッセージ機能の作成
・プロフィール編集の作成
・ホーム画面表示

ver0_2の主な変更点
・ログイン機能の実装、新規登録の実装、SHAで暗号化
・Application.php 189行目　アクション実行ではなく、画面遷移に変更
　変更前：$this->runAction($controller, $action);
　変更後：$this->response->setHttpHeader('Location', $action);

ver0_1の主な変更点
・フレームワークの実装