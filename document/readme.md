# New PHP Framework/CMS - Kiwi

2024.02.11 まだ構想中......

### - kiwiとは？

Containerという単位でアプリを開発または管理・運用可能なWebシステムおよびフレームワークの総称  

### - 開発言語

開発言語をPHP (7.4以降) を想定

ただし概念的なものは他の言語にも応用は可能なのでそちらにも拡張対応する予定  
候補としてはTypeScript(Node.js)やPython, Ruby等

## # 主な用語

### - kiwiCore

kiwiを構成するコアライブラリのこと  
kiwiCloud上ではcontainerの1つ(.core)として存在する  
**これがないとkiwiFwやkiwiCloudも成立しない**

### - kiwiFw

kiwiCoreを基にしたフレームワーク構成
containersディレクトリにContainerという単位でアプリを小分けで設置でき、連携も可能

### - kiwiCloud

kiwiFwをベースにインストーラーやユーザー管理機能などの最低限必要な機能をまとめたWebシステム
いわばContainerをインストールすることでどんなコンテンツ管理システム(CMS)にもなれるようなもの

Containerのインストールやアップグレード、Container内データのインポートやエクスポート等の
操作をもGUIで簡単に操作できるようにしている  
要はSE・PGレベルではない人でも運用・管理を可能にするのが目的

### - Container

kiwiでアプリを構成する単位で、kiwiではContainerごとに独立したアプリを開発できる  
またそれらを1つに集めたり部分的に設置・分離することが容易

Containerはディレクトリで完全に分離されているため(一部書換可能リソースデータ等を除いて)
- 1 直接ディレクトリごと設置をする、
- 2 incubatorコマンドからインストールコマンドを実行、
- 3 kiwiCloudから GUIでインストールの操作をする等
の3方法がある。

1の方法はアップデートやダウングレードに対応しないので別途対応が必要 
よって2と3の方法が安全

### - incubator

kiwiFwにあるincubatorコマンドはkiwiのContainerの作成やインストール、インポート、エクスポート、  
またはControllerの作成等を行えるコマンド
kiwiがインストールされた直下から``php /root/incubator``で実行できる

なお``php /root/shell`` コマンドも存在するが  
こちらは各Containerごとでのコンソール実行用パッチなどを用意して実行するためのもの

## # 何でこれ作ったの?

### 理由1. 元々フレームワークにアプリ拡張を考えている仕様がないから 

既存のWebフレームワークは基本的に単一のWebアプリを開発するのには向いているが、  
全く別の機能を追加する点に関しては開発者レベルでないとできない点が多い

例えば、アプリAに別の機能(アプリBとする)を設けるとすると
下記の工程が必要
- MVC規約FWの場合別々のディレクトリごとにファイル設置しなければならない
- データベースのテーブル追加・カラム変更・レコード追加が必要
- 同じ名前のクラス、DBテーブル・カラムが存在した場合の対処が必要

等

### 理由2. インストール/アップグレードの考慮が必要

インストール機能やアップグレード機能を一から設計しないと駄目で、  
その手順も考えないといけない

### 理由3. ユーザーアカウントの連携をどうするか?

外部のアプリとして独立しても構わないが、  
その場合同じユーザーアカウントでログインできたり連携したりなどを考える必要もあるが....
別のアカウント設けてもOKかもしれないが、  
別々でログイン状態を保たないといけない....

### 理由4. これといって類似のものが存在しないような気がする....

既にWordPressやEC-CUBE等ではプラグインという形では存在する

が、しかしどちらもあくまで補完的な意味合いでの拡張機能の位置づけなので  
プラグインをメインにアプリを展開している、というわけではなさそう

## # ディレクトリの構造

[こちらを参照](directories.md)

## # 各種コアライブラリのクラスオブジェクト

[こちらを参照](structure.md)

## # kiwiContainer.json の構成

Containerを構成するには``kiwiContainer.json``ファイルが必要

```json
{
    "name": "module1",
    "title": "Module_01",
    "version": "1.0.2",
    "repository": {
        "type": "github",
        "user": "user_name",
        "repository": "repo_test",
        "branch": "release"
    },
    "author": "yamada takao",
    "description": "Module01 Text Sample Text Sample Text Sample ...",
    "email": "test@abcdefg.com",
    "homeUrl": "https://www.xxxxxxxxx.com",
    "modifiedDate": "2024/01/01",
    "dependencies" : {
        "module2": "1.0.0^"
    },
}
```

## # kiwiRelease.json

Containerを構成するには``kiwiRelease.json``が必要

```json
[
    {
        "version": "1.0.0",
        "modifiedDate": "2024/01/01 00:00",
        "comment": "upgrade comment text sample text sample..."
    },
    {
        "version": "1.0.1",
        "modifiedDate": "2024/01/15 00:00",
        "comment": "upgrade comment text sample text sample..."
    },
    {
        "version": "1.0.2",
        "modifiedDate": "2024/02/01 00:00",
        "comment": "upgrade comment text sample text sample..."
    },
    ...
]
```

## # namespaceについて

各ディレクトリにてnamepsaceの記述方法が異なる

/Config.php の場合

```php
namespace kiwi;
```

/containers/main/app/controllers\MainController.php の場合

```php
namespace kiwi\main\app\controller;
```

/containers/module1/app/controllers/MainController.php の場合

```php
namespace kiwi\module1\app\controllers;
```

/containers/module2/repository/Repository.php の場合

```php
namespace kiwi\module2\repository;
```

それぞれのContainerごとに名前空間が固有名詞分けされているため  
他のContainerにあるクラスやメソッドなどを利用することができる。

例えば メインアプリである 「main」にビジネスロジックの``models/TestModel``クラスを自前で設置したとする  
これを別のContainer「module1」から実行させたい場合は下記のようにuse句を使えば簡単に使用することができる

```php
namespace kc\module1\app\controllers;

use kiwi\core\Controller;
use kiwi\main\models\TestModel;

class MainController extends Controller {

    public function index () {
        $test = new TestModel();
        $test->run();
    }
}
```

なお``kiwi/core``はkiwiのコアライブラリである kiwiCore を指す。  
(ディレクトリ上は「.code」)

## # コードサンプル

/Config.php

```php
namespace kiwi;

use kiwi\core\Config as BaseConfig;
use kiwi\core\Routes;

class Config extends BaseConfig {

    public array $routes = [
        "/" => "controller=main, action=index",
    ];

    public array $resources = [
        [
            "path" => "/common",
            "headers" => [
                "name" => "kiwi system common",
            ],
            "cache" => [
                "MaxAge" => 3600 * 24,
            ],
        ],
    ];

    public array $writables = [
        [
            "path" => "/",
            "acceptMimeType" => [
                "text/css",
                "image/jpg",
                "image/png",
            ],
            "cache" => [
                "MaxAge" => 3600,
            ],
        ],
    ];


}
```


/containers/main/controllers/MainController.php

```php
namespace kiwi\main;

use kiwi\core\Controller as BaseController;
use kiwi\core\Rendering;

class MainController extends BaseController {

    public string $template = "default";
    public string $autoRender = true;

    public  function index() {

        Rendering::setData("name", "mainController sample...");
    }

}
```

### : Extensionクラス

別Containerから受動的にメソッドを実行できるクラス  
Extensionクラスを下記のように指定

```php
namespace kiwi\module01;

use kiwi\core\Extension;

class TestExtension extends Extension {

    public function run() {
        return "test extension run...";
    }

    public function run2() {
        return "test2 extension run...";
    }
}
```

別のContainerのController側などで下記のように使用するとあとは受動的に実行される

```php
namespace kiwi\main;

use kiwi\core\Extension;

class MainController extends Controller {

    public function index() {
        $testExt = Extension::use("Test");
        foreach $testExt as $t {
            echo $t->run();    
        }

        $testExt2 = Extension::execute("Test", "run2");
        foreach $testExt as $t {
            echo $t;    
        }
    }
}
```

## # Containerのzipデータ構成

内容はContainer内のappディレクトリ内データ構成と同じ

## # Containerエクスポート構成

writablesのデータ領域のみをエクスポートする

```
/container.env
/writables
    /test.txt
    ....
```

container.env の中身は下記

```
container = {container名}
version = {エクスポート時バージョン番号}
createDate = {エクスポート日時}
```

## # incubatorコマンド

root/shell ディレクトリから実施可能

```
php incubator Container lists                                   <= インストール済Containerの一覧表示
php incubator Container remote {repository}                     <= リモートリポジトリ指定でのContainer情報の表示
php incubator Container remote-install {repository}             <= リモートリポジトリ指定でのContainerのインストール (リモートインストール)
php incubator Container local-install {ContainerFilePath}       <= 指定ファイルパスからのContainerインストール (ローカルインストール)
php incubator Container check {ContainerName}                   <= Containerのバージョン状態比較
php incubator Container upgrade {ContainerName} {?version}      <= Containerのアップグレード
php incubator Container uninstall {ContainerName}               <= Containerのアンインストール
php incubator Container import {importFilePath}                 <= Containerのインポート
php incubator Container export {exportFilePath}                 <= Containerのエクスポート
php incubator Container create                                  <= ブロックの作成
php incubator controller create                                 <= Controllerの作成
....
php incubator upgrade                                           <= kiwiCoreのアップグレード
php incubator version                                           <= kiwiCore, kiwiFwのバージョン
```