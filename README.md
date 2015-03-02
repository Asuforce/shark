#SHARK -SHare And Remix-
**「音楽を通じて人と人をつなげる」**  
音楽を通じて人々が繋がる場の提供を考えた私達は、離れた場所にいる人とでも繋がることのできる"WEB サービス"を開発しました。
音楽活動となるとほとんどの場合、楽器や音を合わせて音楽を奏でることが多いので、WEB 上で自由に音を組み合わせることの出来るコンテンツをつくりたいと考えました。
開発をした WEB アフリケーションは「SHARK」と言います。
名前の由来は SHare And Remix を略したもので、コンセプトである、シェアすること によって「音楽で人と人を繋げる」ことを意味しています。

[公式PV - Youtube](https://www.youtube.com/watch?v=X3IHm__hiAk)

###テストについて
1. ローカルサーバのドキュメントルートにリポジトリをクローンしてください。
2. RD*内のSharkApplication.phpのconfigure()内をMySQLデータベースの環境に合わせてください。
3. MySQLデータベースにDB名"shark"を作成してください。
4. RD内のshark.sqlを"shark"インポートしてください。
5. ローカルサーバーを起動し"GoogleChrome"を立ち上げ"http://localhost/shark/web"にアクセスしてください。
6. ユーザー名"root", パスワード"asdf1234"でサイトにログインすることができます。

*リポジトリのルートディレクトリ

###動作ブラウザ
Google Chrome40

###開発環境
Mac OSX Yosemite
php 5.5.10
mysql
Apache 2.2.26

###主な更新履歴
* ver0_7の主な変更点
    - 録音機能を追加
    - D&D機能を追加
    - cssを修正

* ver0_6の主な変更点
    - crop機能を追加
    - MDを導入

* ver0_5の主な変更点
    - ミュージックMVCを実装

* ver0_4の主な変更点
    - プロフィール情報(画像、名前)の追加
* ホームのタイムライン表示
    - お気に入りボタンを実装
    - 通知機能の導入
    - 画像を正方形にリサイズして保存

* ver0_3の主な変更点
    - メッセージ機能の作成
    - プロフィール編集の作成
    - ホーム画面表示

* ver0_2の主な変更点
    - ログイン機能の実装、新規登録の実装、SHAで暗号化
    - Application.php 189行目　アクション実行ではなく、画面遷移に変更  
        変更前：$this->runAction($controller, $action);  
        変更後：$this->response->setHttpHeader('Location', $action);

* ver0_1の主な変更点
    - フレームワークの実装
