# Rese API

ある企業のグループ会社による飲食店予約サービスの API です

## Prerequisites

-   PHP 7.4.15
-   Laravel 8.4
-   MySQL 8.0

## Installing

PHP,Composer(Laravel),MySQL

### Mac

#### PHP

Mac の場合は PHP が初めから入っているのでインストールする必要はありません。

#### Composer

Composer のインストールは [こちら](https://getcomposer.org/download/)

Manual Download から 2.0.11 のバージョンのリンクをクリック。

「ダウンロード」フォルダに「composer.phar」というファイルがダウンロードされます。

続いてターミナルを起動後、Download ディレクトリに移動し以下のコマンドを実行します。

```
$ cd Downloads
$ sudo mv composer.phar /usr/local/bin/composer
$ chmod a+x /usr/local/bin/composer
$ composer -V
```

バージョンが返ってくればインストール成功です。

#### MySQL

パッケージマネージャーの Homebrew をインストールします。
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

次に MySQL の設定を行います。
以下のコマンドを入力して下さい。

```
$ mysql_secure_installation
```

コマンドを実行すると最初に VALIDATE PASSWORD PLUGIN という強固なパスワード設定を助けるプラグインを使用するかどうかを質問されます。
今回は何も入力せず Enter キーを押して次に進みます。

root ユーザー(管理者)のパスワードを設定します

New password - 新しいパスワードを設定

Re-enter new password - 同じパスワードを入力

入力中は何も表示されませんがそのまま入力してください。

パスワードの設定が終わると複数の質問が続きますが、今回は Enter キーを押して設定をスキップします。

All done!と表示されれば終了です。

ログインは下記コマンドを入力で可能です。

```
$ mysql -u root -p
```

パスワードが聞かれるので、先ほど設定したパスワードを入力して下さい。

「mysql>」と表示されて入力待ちとなったらログイン完了です。

### Windows

#### PHP

PHP のインストールは[こちら](https://windows.php.net/download#php-7.4)

PHP 7.4 (7.4.20)の Zip をクリックし、インストールします。

フォルダを右クリックで「すべて展開」を押し展開します。

フォルダ名を「php-7.4.20-nts-Win32-vc15-x64」のような名前から「php」に変更します。

その後[コントロールパネル]→[システムとセキュリティ]→[システム]→[システムの詳細設定]→[環境変数]に移動し以下の操作を行います。

ユーザー環境変数の Path を選択し編集を押します。
参照を押し、先ほどの php フォルダを探し OK を押します。

コマンドプロンプトを立ち上げて下記のコマンドを実行します。

```
$ php -v
```

バージョンが返ってくれば成功です。

#### Composer

Composer のインストールは [こちら](https://getcomposer.org/download/)

Windows Installer に「Composer-Setup.exe」というリンクがあるのでインストーラをダウンロードしてください。

ダウンロードしたインストーラを起動します。

Installation Options - 起動すると画面に「Developer mode」というチェックボックスが表示された画面が現れるので OFF のまま次に進みます。

Settings Check - デフォルトのまま次へ進みます。

PHP Configuration Error - デフォルトのまま次へ進みます。

Proxy Settings - デフォルトのまま次へ進みます。

Ready to Install - Install を押します。
完了したら Next を押し、その後 Finish を押して完了です。

コマンドプロンプトを立ち上げて下記のコマンドを実行します。

```
$ composer -v
```

バージョンが返ってくれば成功です。

#### MySQL

MySQL のインストールは[こちら](https://dev.mysql.com/downloads/windows/installer/8.0.html)

上から 2 つ目の Windows (x86, 32-bit), MSI Installer(メガが大きいほう)の Download ボタンをクリックします。

ログインまたは登録を求められますが、ページ下記の「No thanks, just start my download.」をクリック。

インストーラーのダウンロードが完了したらダウンロードしたファイルを開いてインストーラーを起動します。

Choosing a Setup Type - Developer Default を選択

Check Requirements - Next>をクリック

One or more product requirements have not been satisified - Yes をクリック

Installation - Execute をクリック。全てにチェックがつけば Next>をクリック。

Product Configuration - Next>をクリック。

Group Replication - 「Standalone MySQL Server / Classic MySQL Replication」が選択されていることを確認し、Next>をクリック。

Type and Networking -
Config Type で「Development Computer」が選択されているか確認。Next>をクリック。

Authentication Method - 「Use Strong Password Encryption for Authentication 」が選択されていることを確認し、Next>をクリック。

Accounts and Roles - MySQL Root Password と Repeat Password に MySQL の管理者用パスワードを入力し、Next>をクリック。
MySQL User Accounts は空のまま。

Windows Service - Next>をクリック。

Apply Configuration - Excute をクリックし、全て緑色のチェックマークが付いたら Finish をクリック。

Product Configuration - Next>をクリック。

MySQL Server Configuration - Finish をクリック。

Product Configuration - Next>をクリック。

Connect To Server - Password 欄に先ほど設定した MySQL の管理者用パスワードを入力し、Check をクリック。

All connections succeeded.と表示されたら Next>をクリック。

Apply Configuration - Excute をクリックし、全て緑色のチェックマークが付いたら Finish をクリック。

Product Configuration - Next>をクリック。

Installation Complete - Finish をクリック。

左下のスタートメニューから「MySQL Command Line Client」を開き「Enter password」に「MySQL Root Password」で指定したパスワードを入力します。「mysql>」と表示されて入力待ちとなったらログイン完了です。

## Database Preparation

開発用のデータベースを用意します。

MySQL にログインします。
下記コマンドを入力し、データベースを作成しておきます。

```
$ CREATE DATABASE rese;
```

## API Data Edit

CLIを開きます。
任意の場所でリポジトリをコピーします。

```
$ git clone https://github.com/YUKINA-gif/Rese-api.git
```

Rese-apiディレクトリに移動し、vendor ディレクトリがないので入れます。

```
$ cd Rese-api
$ composer update
```

.env ファイルがないので作成します。

Rese-apiディレクトリ直下にある.env.example の名前を.env に変更します。
.env 内を下記のように編集します。

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

Do you really wish to run this command? (yes/no)と聞かれるのでyesと入力し、Enterキーをクリック。

店舗情報を取得し、正常に動作するか確認します。

ローカルサーバーを立ち上げ、
```
$ php artisan serve
```
別のCLIを開き、APIをたたきます。
```
$ curl http://127.0.0.1:8000/api/stores/1
```
店舗情報が返ってきたら成功です。