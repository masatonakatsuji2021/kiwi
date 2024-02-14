# New PHP Framework/CMS - Kiwi

2024.02.11 まだ構想中......

### - kiwiとは？

Blockという単位でアプリを開発または管理・運用可能なWebシステムおよびフレームワークの総称  

### - 開発言語

開発言語をPHP (7.4以降) を想定

ただし概念的なものは他の言語にも応用は可能なのでそちらにも拡張対応する予定  
候補としてはTypeScript(Node.js)やPython, Ruby等

## # 主な用語

### - kiwiCore

kiwiを構成するコアライブラリのこと  
kiwiCloud上ではBlockの1つ(.core)として存在する  
**これがないとkiwiFwやkiwiCloudも成立しない**

### - kiwiFw

kiwiCoreを基にしたフレームワーク構成
blocksディレクトリにblockという単位でアプリを小分けで設置でき、連携も可能

### - kiwiCloud

kiwiFwをベースにインストーラーやユーザー管理機能などの最低限必要な機能をまとめたWebシステム
いわばBlockをインストールすることでどんなコンテンツ管理システム(CMS)にもなれるようなもの

Blockのインストールやアップグレード、Block内データのインポートやエクスポート等の
操作をもGUIで簡単に操作できるようにしている  
要はSE・PGレベルではない人でも運用・管理を可能にするのが目的

### - Block

kiwiでアプリを構成する単位で、kiwiではBlockごとに独立したアプリを開発できる  
またそれらを1つに集めたり部分的に設置・分離することが容易

Blockはディレクトリで完全に分離されているため(一部書換可能リソースデータ等を除いて)
- 1 直接ディレクトリごと設置をする、
- 2 incubatorコマンドからインストールコマンドを実行、
- 3 kiwiCloudから GUIでインストールの操作をする等
の3方法がある。

1の方法はアップデートやダウングレードに対応しないので別途対応が必要 
よって2と3の方法が安全

### - incubator

kiwiFwにあるincubatorコマンドはkiwiのBlockの作成やインストール、インポート、エクスポート、  
またはControllerの作成等を行えるコマンド
kiwiがインストールされた直下から``php /root/incubator``で実行できる

なお``php /root/shell`` コマンドも存在するが  
こちらは各Blockごとでのコンソール実行用パッチなどを用意して実行するためのもの

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

## # kiwiFw ディレクトリ構成

Block内部の構造詳細は[ここに記載](#block_structur)

```
/blocks                                 <= インストール済Block領域
    /.core                              <= コアライブラリ用Block (kiwiCore)
        /src
        /autoload.php                   
        /kiwiBlock.json
    /main                               <= メインアプリ用Block
    /module1                            <= 他アプリBlock
    /module2                            <= 他アプリBlock
    /module3                            <= 他アプリBlock
    ...
/backups                               <= バックアップ用Block領域
    /1.0.0
        ...
    /1.0.1
        ...
    ......
/repositories                           <= Block別リモートリポジトリ接続情報
    /.core
        /RepositoryAccess.php
    /main
        /RepositoryAccess.php
    /module1
        /RepositoryAccess.php
        /token
    ....
/root                                   <= ルートディレクトリ
    /shell
        /commander                      <= コンソール実行用コマンド
        /incubator                      <= Block管理、ソース管理用コマンド
    /web
        /index.php                      <= Web公開用php
        /.htaccess
/temporaries                            <= テンポラリ領域
    ....    
/writables                              <= 書換可能リソースデータ領域(Block別の管理)
    /.main      
        /logs
            /log20240210.log
            ...
        ...
    /module1
    /module2
    /module3
/Config.php                              <= Configクラス
/routes.env                              <= Block別経路探索情報用env
```

<a id="block_structure"></a>

## # Blockディレクトリ構成

```
/controllers                        <= Controllerクラス用ディレクトリ
    /MainController.php
    ...
/extensions                         <= Extensionクラス用ディレクトリ
    /TestExtension.php
    ...
/migrates                           <= Migrateクラス用ディレクトリ
    /Migrate100.php         
    /Migrate101.php
    /Migrate102.php
    ...
/resources                          <= Blockリソースデータ領域(書換不可)
    /common
        /img
            /sample.png
            ...
        /css
            /style.css
            ...
        ...
    ...
/vendor                             <= 任意のcomposerパッケージディレクトリ
/views                              <= View用ディレクトリ
    /main
        /index.view
        ...
/viewTemplates                      <= ViewTemplate用ディレクトリ
    /default.view
    ...
/viewparts                          <= ViewPart用ディレクトリ
    /sample.view
    ....
/BlockConfig.php                    <= Configクラス
/kiwiBlock.json
```

## # Class Object

```
kiwi\core\Kiwi
    L static fileSearch($path) : Array                  <= 最深層ディレクトリ探索
    L static allDelete($path) : boolean                 <= ディレクトリ一括削除
    L static versionOnInteger($version) : integer       <= バージョン番号変換 (文字列 -> 整数)
    L static versionOnString($version) : string         <= バージョン番号変換 (数値 -> 文字列)
    L static loadEnv($filePath) : array                 <= envファイル読込
    L static saveEnv($filePath, $envData) : boolean     <= envファイル保存
kiwi\core\Config
    L static domains : Array<string>                   <= 許可ドメインリスト
    L static basicAuthority : Array                    <= ベーシック認証情報
    L static consolePasswordHash : string              <= コンソール実行時パスワードハッシュ
    L static makeCmdPasswordHash : string              <= Makeコマンド実行時パスワードハッシュ
    L static before() : void                           <= リクエスト受信時コールバック関数
kiwi\core\BlockConfig : Config
    L static routes : Array                            <= Web用経路探索
    L static routeShells : Array                       <= コンソール用経路探索
    L static resources : Array                         <= リソース領域情報
    L static handleRoute() : void                      <= 経路探索時ハンドリング
    L static handleRouteShell() : void                 <= コンソール経路探索時ハンドリング
    L static handleInstall() : void                    <= インストール時実行用ハンドリング
    L static handleUninstall() : void                  <= アンインストール時実行用ハンドリング
    L static handleImportBefore() : void               <= 
    L static handleImportAfter() : void                <=
    L static handleExportBefore() : void               <= 
    L static handleExportAfter() : void                <=
    L static handleDataDeleteBefore() : void           <= 
    L static handleDataDeleteAfter() : void            <=
kiwi\core\Controller
    L viewTemplate : string = null              <= 使用Template名
    L view : string = null                      <= 使用View名
    L blockOnViewTemplate : string = null       <= 別Block使用時のblock名
    L blockOnView : string = null               <= 別Block使用時のblock名
    L viewPartOnBlock : string = null           <= 別Block使用時のblock名
    L autoRender : boolean                      <= レンダリング有効/無効
    L handleBefore() : void                     <= action実行前の共通イベント
    L handleAfter() : void                      <= action実行後の共通イベント
    L handleDrawn() : void                      <= レンダリング後の共通イベント
kiwi\core\ExceptionController : Controller
    L handleBefore($exception) : void           <= action実行前の共通イベント
    L handleAfter($exception) : void            <= 
    L handleDrawn($exception) : void            <= 
kiwi\core\Extension
    L static load($extensionName) : Array<Extension>             <= 該当Extensionクラスリスト取得
    L static excute($extensionName, $methodName) : Array<Any>   <= 該当Extensionクラス、指定メソッド実行(レスポンスリスト取得)]
    L static loadOnBlock($blockName,$extensionName) : Array<Extension>             <= 指定Block, 該当Extensionクラスリスト取得
    L static excuteOnBlock($blockName, $extensionName, $methodName) : Array<Any>   <= 指定Block, 該当Extensionクラス、指定メソッド実
kiwi\core\Block
    L static locals : Array<LocalBlock>                     <= インストール済Blockリスト取得
    L static enableLocals : Array<LocalBlock>               <= インストール済・有効化Blockリスト取得
    L static local($blockName) : LocalBlock                 <= 指定Block名のインストール済Block取得
    L static remote($url) : RemoteBlock                     <= 指定リポジトリの最新版RemoteBlock取得
    L static localInstall($zipFilePath) : boolean           <= 指定zipファイルパスのBlockインストール(ローカルインストール)
    L static create($blockOption) : boolean                 <= BLockの作成
kiwi\core\BlockInfo
    L name : string                                         <= Block名
    L title : string                                        <= Blockタイトル
    L version : integer                                     <= バージョン番号
    L modifiedDate : date                                   <= 更新日
    L repository : BlockRepository                          <= リポジトリ情報
    L description: string                                   <= 概要文
    L author : string                                       <= 開発者
    L email : string                                        <= Email
    L homeUrl : string                                      <= 公式ページURL
    L icon : string                                         <= アイコン画像DataUrl
    L dependencies : BlockDependent                         <= 依存Block情報
kiwi\core\BlockDependent
    L name : string                                         <= 依存Block名
    L version : integer                                     <= パッケージ番号
    L type : BlockDependentType                             <= パッケージ依存関係 (master: 関係なし, under: 以下, ecual: 同等, over: 以上)
kiwi\core\BlockRepository
    L type : BlockRepositoryType                            <= リポジトリ種類(GitHub, BitBucket, GitLab, Original)
    l user : string                                         <= ユーザー名
    L repository : string                                   <= リポジトリ名
    L private : boolean                                     <= プライベートリポジトリフラグ
    L token : string                                        <= リポジトリトークン
kiwi\core\LocalBlock : BlockInfo
    L path : string                                         <= Blockディレクトリパス
    L url : string                                          <= Blockのルーティング先URL
    L status : boolean                                      <= 有効/無効
    L import($importFilePath) : boolean                     <= 指定zipファイルパスのインポート実行
    L export() : boolean                                    <= エクスポート実行
    L uninstall() : boolean                                 <= アンインストール実行
kiwi\core\RemoteBlock : BlockInfo
    L upgrade() : boolean                                               <= インストール/アップグレード実行 (リモートインストール)
kiwi\core\BlockEvent
    L static composerAutoload() : void                      <= composerパッケージ使用時のautoload実行用
kiwi\core\Migrate
    L upgrade() : void                                                  <= アップグレード実行用イベント
    L downgrade() : void                                                <= ダウングレード実行用イベント
kiwi\core\Resource
    L static block($blockName = null) : void                            <= カレントBlock設定
    L static lists() : Array<ResourceFile / ResourceDir>                <= リソース用ディレクトリ直下のリソースファイル・ディレクトリ一覧取得
    L static existsFile($path) : boolean                                <= 指定パスのファイル存在可否
    L static existsDir($path) : boolean                                 <= 指定パスのディレクトリ存在可否
    L static get($path) : ResourceFile / ResourceDir                    <= 指定パスのリソースファイル・ディレクトリ情報取得
kiwi\core\ResourceWritable : Resource
    L static mkdir($path) : boolean                                     <= 指定パスのディレクトリ作成
    L static remove($path) : boolean                                    <= 指定パスのファイル・ディレクトリ削除
    L static rename($beforePath, $afterPath) : boolean                  <= 指定パスへのファイル・ディレクトリのパス変更
    L static save($path, $contents) : boolean                           <= 指定パスでのファイルの保存
    L static copy($inputPath, $outputPath) : boolean                    <= ファイル・ディレクトリのコピー
kiwi\core\ResourceDir
    L name : string                                                     <= ディレクトリ名
    L createDate : date                                                 <= 作成日時
    L modifiedDate : date                                               <= 更新日時
    L fullPath : string                                                 <= ディレクトリのフルパス
    L lists() : Array<ResourceFile / ResourceDir>                       <= 内部リソースファイル・ディレクトリ一覧取得
    L mkdir($directoryName) : boolean                                   <= 指定ディレクトリ名のディレクトリ作成
    L rmdir() : boolean                                                 <= ディレクトリの削除
    L rename($directoryName) : boolean                                  <= 指定ディレクトリ名へ変更
    L save($fileName, $contents) : boolean                              <= 指定ファイル名でのファイル保存
    L copy($outputPath) : boolean                                       <= 指定パスへのディレクトリのコピー
kiwi\core\ResourceFile
    L name : string                                                     <= ファイル名
    L size : integer                                                    <= ファイルサイズ
    L mimeType : string                                                 <= MimeType取得
    L createDate : date                                                 <= 作成日時
    L modifiedDate : date                                               <= 更新日時
    L raw() : string                                                    <= ファイルデータ取得
    L unlink() : boolean                                                <= ファイルの削除
    L rename($fileName)                                                 <= 指定ファイル名へ変更
    L save($contents) : boolean                                         <= ファイルの上書き保存
    L copy($outputPath) : boolean                                       <= 指定パスへのファイルのコピー
kiwi\core\Rendering
    L static view($viewName) : string                                   <= Viewファイル取得
    L static viewOnBlock($blockName, $viewName) : string                <= 指定Block名のViewファイル取得
    L static viewPart($viewPartName) : string                           <= viewPartファイル取得
    L static viewPartOnBlock($blockName, $viewPartName) : string        <= 指定Block名のViewPartファイル取得
    L static template($templateName) : string                           <= templateファイル取得
    L static templateOnBlock($blockName, $templateName) : string        <= 指定Block名のtemplateファイル取得
kiwi\core\Request
    L static method() : string
    L static get() : Array<Any>
    L static post() : Array<Any>
    L static put() : Array<Any>
    L static delete() : Array<Any>
    L static headers() : Array<Any>
kiwi\core\Routes
    L static route : RouteResult
    L static on($url) : RouteResult
    L static route($url, $params) : string
kiwi\core\RouteResult
    L successed : boolean
    L block : string
    L action : string
    L url : string
    L aregments : Array<Any>
    L exception : Exception
kiwi\core\RouteWebResult : RouteResult
    L controller : string
    L url : string
kiwi\core\RouteShellResult : RouteResult
    L shell : string
    L command : string

```

## # kiwiBlock.json の構成

```json
{
    "appHash": "1234*******************************",
    "name": "module1",
    "title": "Module_01",
    "version": "1.0.2",
    "repository": "https://www.aaaaaaaa.com/bbbbb/ccccc/{version}",
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

## # namespaceについて

### : /Config.php

```php
namespace kiwi;
```

### : /.main/Config.php

```php
namespace kiwi\main;
```

### : /module1/Config.php

```php
namespace kiwi\module1;
```

## # コードサンプル

### : Config

```php
namespace kiwi;

use kiwi\core\Config as BaseConfig;
use kiwi\core\Routes;

class Config extends BaseConfig {
    public array $routes = [
        "/" => Routes::route("main", "index"),
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


### : Controller

```php
namespace kiwi\main;

use kiwi\core\Controller as BaseController;

class Controller extends BaseController {

    public string $template = "default";
    public string $autoRender = true;

    public function filterBefore() {
        echo "filter before text...";
    }
}
```

```php
namespace kiwi\main;

class MainController extends Controller {

    public function index() {

    }
}
```

### : Extension

別Blockにて下記のように指定

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

Controller側などで下記のように使用すると  
あとは受動的に利用できる

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

## # Blockのzipデータ構成

内容はBlock内のデータ構成と同じ

```
/controllers
    /MainControler.php
    ..
/extensions
/migrates
/views
/viewTemplates
/viewParts
/resources
/Config.php
/kiwiBlock.json
```

## # Blockエクスポート構成

```
/writables
    /test.txt
    ....
```

## # 動作設計

### : ローカルzipファイルからBlockをインストール (ローカルインストール)

- zipファイルをテンポラリに展開 (以後展開データとする)
- 展開データにkiwiBlock.jsonが存在しない場合
    - エラー出力して終了
- 展開データのkiwiBlock.jsonのフォーマットが正常でない場合
    - エラー出力して終了
- 同じBlock名のBlock(既存Block)が存在しない場合 (新規インストール)
    - 展開データを /blocks/{block名} にコピー (以後展開データBlock)
    - 展開データBlockのMigrateクラスのupgradeメソッドを初期バージョン番号->最新バージョン番号まで順に実行
        例: バージョンが1.0.3 の場合、 順に Migrate1.0.0 -> Migrate1.0.1 -> Migrate1.0.2 -> Migrate1.0.3 を実行
    - 展開データをテンポラリから削除
    - 完了
- 同じBlock名のBlock(既存Block)が存在する場合
    - 既存Blockのバージョン番号 < 展開データのバージョン番号 (アップグレード)
        - 展開データを /blocks/{block名}_tmp にコピー  (以後展開データBlock)
        - 既存Blockのパスを /backups/{block名} に変更
        - 展開データBlockのパスを /blocks/{block名} に変更
        - 展開データBlockのMigrateクラスのupgrateメソッドを既存Blockバージョン番号の次番号 -> 最新バージョン番号まで順に実行
            例: 1.0.5 -> 1.0.9 の場合、 順に Migrate1.0.6 -> Migrate1.0.7 -> Migrate1.0.8 -> Migrate1.0.9 を実行
        - 展開データをテンポラリから削除
        - 完了
    - 既存Blockのバージョン番号 > 展開データのバージョン番号 (ダウングレード)
        - 展開データを /blocks/{block名}_tmp にコピー  (以後展開データBlock)
        - 既存BlockのMigrateクラスのdowngrateメソッドを既存Blockバージョン番号 -> 展開データBlockバージョン番号の次まで順に実行
            例: 1.0.9 -> 1.0.5 の場合、 順に Migrate1.0.9 -> Migrate1.0.8 -> Migrate1.0.7 -> Migrate1.0.6 を実行
        - 既存Blockのパスを /backups/{block名} に変更
        - 展開データBlockのパスを /blocks/{block名} に変更
        - 展開データをテンポラリから削除
        - 完了

### : Blockを選択したバージョンに戻す (ロールバック)

- 現行バージョン > 前バージョン の場合 (ダウングレード)
    - 現行バージョンBlockのMigrateクラスのdowngrateメソッドを現行バージョン番号 -> 前バージョン番号の次まで順に実行
        例: 1.0.9 -> 1.0.5 の場合、 順に Migrate1.0.9 -> Migrate1.0.8 -> Migrate1.0.7 -> Migrate1.0.6 を実行
    - 前バージョンBlockを /blocks/{block名}_tmp にコピー
    - 前バージョンBlockのパスを /blocks/{block名} に変更
    - 現行バージョンBlockのパスを /blocks/{block名}_delete に変更
    - 現行バージョンBlockを削除
    - 完了
- 現行バージョン = 前バージョン の場合
    - 何もせずに終了
- 現行バージョン < 前バージョン の場合 (アップグレード)
   - 前バージョンBlockを /blocks/{block名}_tmp にコピー
    - 現行バージョンBLockのパスを /blocks/{block名}_delete に変更
    - 前バージョンBlockのパスを /blocks/{block名} に変更
    - 現行バージョンBlockを削除
    - 前バージョンBlockのMigrateクラスのupgradeメソッドを現行バージョンの次番号 -> 前バージョン番号まで順に実行
        例: 1.0.5 -> 1.0.9 の場合、 順に Migrate1.0.6 -> Migrate1.0.7 -> Migrate1.0.8 -> Migrate1.0.9 を実行
    - 完了

### : Blockをアンインストール

- 対象BlockのMigrateクラスのupgradeメソッドを最大バージョン番号 -> 初期バージョン番号まで順に実行
    例: 対象Blockバージョンが1.0.4、，初期バージョンが1.0.0 の場合、1.0.4 -> 1.0.3 -> 1.0.2 -> 1.0.1 -> 1.0.0 を実行
- 対象Blockを削除
- /writables/{対象Block名}のデータをディレクトリごと削除
- 完了

### : リモートリポジトリからBlockをインストール (リモートインストール)

- リモートリポジトリからBlockのzipデータをダウンロード
- 以後ローカルインポートと作業は同様

### : Blockのデータをインポート

- インポートzipファイルをテンポラリに展開 (以後インポートデータ)
- Block内にImportクラスとbeforeメソッドが存在する場合
    - Importクラスのbeforeメソッドを実行
- インポートデータを書換可能データ領域 /writables/{block名} にコピー
- Block内にImportクラスとafterメソッドが存在する場合
    - Importクラスのafterメソッドを実行
- インポートデータをテンポラリから削除

### : Blockのデータをエクスポート

- Block内にExportクラスとbeforeメソッドが存在する場合
    - Exportクラスのbeforeメソッドを実行
- 書換可能リソースデータ領域 /writables/{block名} をzip圧縮
- Block内にExportクラスとbeforeメソッドが存在する場合
    - Exportクラスのbeforeメソッドを実行

## # incubatorコマンド

root/shell ディレクトリから実施可能

```
php incubator block lists                                   <= インストール済Blockの一覧表示
php incubator block remote {repository}                     <= リモートリポジトリ指定でのBlock情報の表示
php incubator block remote-install {repository}             <= リモートリポジトリ指定でのBlockのインストール (リモートインストール)
php incubator block local-install {blockFilePath}           <= 指定ファイルパスからのBlockインストール (ローカルインストール)
php incubator block check {blockName}                       <= Blockのバージョン状態比較
php incubator block upgrade {blockName} {?version}          <= Blockのアップグレード
php incubator block uninstall {blockName}                   <= Blockのアンインストール
php incubator block import {importFilePath}                 <= Blockのインポート
php incubator block export {exportFilePath}                 <= Blockのエクスポート
php incubator block create                                  <= ブロックの作成
php incubator controller create                             <= Controllerの作成
....
php incubator upgrade                                       <= kiwiCoreのアップグレード
php incubator version                                       <= kiwiCore, kiwiFwのバージョン
```