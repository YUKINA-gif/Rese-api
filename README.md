# Rese API

ある企業のグループ会社による飲食店予約サービスのAPIです

# Prerequisites

* PHP 7.4.15
* Laravel 8.4
* MySQL 8.0

# Installing

PHP,Composer(Laravel),MySQL

## Mac

### PHP
Macの場合はPHPが初めから入っているのでインストールする必要はありません。

### Composer

Composerのインストールは [こちら](https://getcomposer.org/download/)

Manual Downloadから2.0.11のバージョンのリンクをクリック。

「ダウンロード」フォルダに「composer.phar」というファイルがダウンロードされます。

続いてターミナルを起動後、Downloadディレクトリに移動し以下のコマンドを実行します。

```
$ cd Downloads
$ sudo mv composer.phar /usr/local/bin/composer
$ chmod a+x /usr/local/bin/composer
$ composer -V
```

バージョンが返ってくればインストール成功です。

### MySQL
パッケージマネージャーのHomebrewをインストールします。
下記コマンドを入力します。
```
$ /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

インストール後下記のコマンドを入力して下さい。
```
$ brew install mysql
$ brew services start mysql
$ mysql --version
```
バージョンが返ってくれば成功です。

次にMySQLの設定を行います。
以下のコマンドを入力して下さい。
```
$ mysql_secure_installation
```

コマンドを実行すると最初にVALIDATE PASSWORD PLUGINという強固なパスワード設定を助けるプラグインを使用するかどうかを質問されます。
今回は何も入力せずEnterキーを押して次に進みます。

rootユーザー(管理者)のパスワードを設定します

New password - 新しいパスワードを設定

Re-enter new password - 同じパスワードを入力

入力中は何も表示されませんがそのまま入力してください。

パスワードの設定が終わると複数の質問が続きますが、今回はEnterキーを押して設定をスキップします。

All done!と表示されれば終了です。

ログインは下記コマンドを入力で可能です。
```
$ mysql -u root -p 
```
パスワードが聞かれるので、先ほど設定したパスワードを入力して下さい。

「mysql>」と表示されて入力待ちとなったらログイン完了です。
## Windows

### PHP
PHPのインストールは[こちら](https://windows.php.net/download#php-7.4)

PHP 7.4 (7.4.20)のZipをクリックし、インストールします。

フォルダを右クリックで「すべて展開」を押し展開します。

フォルダ名を「php-7.4.20-nts-Win32-vc15-x64」のような名前から「php」に変更します。

その後[コントロールパネル]→[システムとセキュリティ]→[システム]→[システムの詳細設定]→[環境変数]に移動し以下の操作を行います。

ユーザー環境変数のPathを選択し編集を押します。
参照を押し、先ほどのphpフォルダを探しOKを押します。

コマンドプロンプトを立ち上げて下記のコマンドを実行します。

```
$ php -v
```

バージョンが返ってくれば成功です。

### Composer
Composerのインストールは [こちら](https://getcomposer.org/download/)

Windows Installerに「Composer-Setup.exe」というリンクがあるのでインストーラをダウンロードしてください。

ダウンロードしたインストーラを起動します。

Installation Options - 起動すると画面に「Developer mode」というチェックボックスが表示された画面が現れるのでOFFのまま次に進みます。

Settings Check - デフォルトのまま次へ進みます。

PHP Configuration Error - デフォルトのまま次へ進みます。

Proxy Settings - デフォルトのまま次へ進みます。

Ready to Install - Installを押します。
完了したらNextを押し、その後Finishを押して完了です。

コマンドプロンプトを立ち上げて下記のコマンドを実行します。

```
$ composer -v
```

バージョンが返ってくれば成功です。

### MySQL

MySQLのインストールは[こちら](https://dev.mysql.com/downloads/windows/installer/8.0.html)

上から2つ目のWindows (x86, 32-bit), MSI Installer(メガが大きいほう)のDownloadボタンをクリックします。

ログインまたは登録を求められますが、ページ下記の「No thanks, just start my download.」をクリック。

インストーラーのダウンロードが完了したらダウンロードしたファイルを開いてインストーラーを起動します。

Choosing a Setup Type - Developer Defaultを選択

Check Requirements - Next>をクリック

One or more product requirements have not been satisified - Yesをクリック

Installation - Executeをクリック。全てにチェックがつけばNext>をクリック。

Product Configuration - Next>をクリック。

Group Replication - 「Standalone MySQL Server / Classic MySQL Replication」が選択されていることを確認し、Next>をクリック。

Type and Networking - 
Config Typeで「Development Computer」が選択されているか確認。Next>をクリック。

Authentication Method - 「Use Strong Password Encryption for Authentication 」が選択されていることを確認し、Next>をクリック。

Accounts and Roles - MySQL Root PasswordとRepeat PasswordにMySQLの管理者用パスワードを入力し、Next>をクリック。
MySQL User Accountsは空のまま。

Windows Service - Next>をクリック。

Apply Configuration - Excuteをクリックし、全て緑色のチェックマークが付いたらFinishをクリック。

Product Configuration - Next>をクリック。

MySQL Server Configuration - Finishをクリック。

Product Configuration - Next>をクリック。

Connect To Server - Password欄に先ほど設定したMySQLの管理者用パスワードを入力し、Checkをクリック。

All connections succeeded.と表示されたらNext>をクリック。

Apply Configuration - Excuteをクリックし、全て緑色のチェックマークが付いたらFinishをクリック。

Product Configuration - Next>をクリック。

Installation Complete - Finishをクリック。

左下のスタートメニューから「MySQL Command Line Client」を開き「Enter password」に「MySQL Root Password」で指定したパスワードを入力します。「mysql>」と表示されて入力待ちとなったらログイン完了です。

# Database Preparation
開発用のデータベースを用意します。

MySQLにログインします。
下記コマンドを入力し、データベースを作成しておきます。
```
$ CREATE DATABASE rese;
```

# API Data Edit
コマンドライン(Windows)またはターミナル(Mac)を開きます。
リポジトリをコピーします。
```
$ git clone https://github.com/YUKINA-gif/Rese-api.git
```
vendorディレクトリがないので入れます。
```
$ composer update
```
.envファイルがないので作成します。

ディレクトリ内の.env.exampleの名前を.envに変更します。
.env内を下記のように編集します。
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rese
DB_USERNAME=root
DB_PASSWORD=MySQLログイン用パスワード
```
キーを発行するため下記コマンドを入力します。
```
$ php artisan key:generate
```

データベースにデータを用意し、店舗情報を用意します。
```
$ php artisan migrate

$ php artisan db:seed --class=StoreSeeder
```

