## # 各種コアライブラリのクラスオブジェクト

- kiwi\core\Kiwi
- kiwi\core\WebStartor
- kiwi\core\configs\Config
- kiwi\core\configs\ProjectConfig
- kiwi\core\configs\ContainerConfig
- kiwi\core\configs\Handling
- kiwi\core\controllers\Controller
- kiwi\core\controllers\ExceptionController
- kiwi\core\shells\Shell
- kiwi\core\shells\ExceptionShell
- kiwi\core\extensions\Extension
- kiwi\core\extensions\ExtensionStatic
- kiwi\core\containers\Container
- kiwi\core\containers\ContainerInfo
- kiwi\core\containers\ContainerRepository
- kiwi\core\containers\ContainerRepositoryOnGitService
- kiwi\core\containers\ContainerRepositoryOnGitHub
- kiwi\core\containers\ContainerRepositoryOnBitBucket
- kiwi\core\containers\ContainerRepositoryOnGitLab
- kiwi\core\containers\LocalContainer
- kiwi\core\containers\VersionContainer
- kiwi\core\containers\RemoteContainer
- kiwi\core\containers\ContainerDevelopment
- kiwi\core\migrations\Migration
- kiwi\core\migrations\MigrationStatic
- kiwi\core\resources\Resource
- kiwi\core\resources\ResourceDir
- kiwi\core\resources\ResourceFile
- kiwi\core\resources\Writables
- kiwi\core\resources\Temporary
- kiwi\core\renders\Render
- kiwi\core\renders\View
- kiwi\core\renders\ViewTemplate
- kiwi\core\renders\ViewPart
- kiwi\core\requests\Request
- kiwi\core\requests\Get
- kiwi\core\requests\Post
- kiwi\core\requests\Put
- kiwi\core\requests\Delete
- kiwi\core\requests\Option
- kiwi\core\requests\Header
- kiwi\core\routes\Routes
- kiwi\core\routes\RouteResult
- kiwi\core\routes\RouteWebResult
- kiwi\core\routes\RouteShellResult
- kiwi\core\consoles\Incubator
- kiwi\core\consoles\Commander


必要なクラス及びっクラスメソッド、メンバ変数を列挙

- kiwi\core\Kiwi    <= Kiwi共通クラス
    - static fileSearch(string $path) : Array                           <= 最深層ディレクトリ探索
    - static copy(string $targetPath, string $copyPath, bool $deleteCopy = false) : bool          <= ディレクトリ内一括コピー
    - static delete(string $targetPath) : bool                          <= ディレクトリ内一括削除
    - static versionOnInteger(string $version) : int                    <= バージョン番号変換 (文字列 -> 整数)
    - static versionOnString(int $version) : string                     <= バージョン番号変換 (数値 -> 文字列)
    - static loadEnv(string $filePath) : array                          <= envファイル読込
    - static saveEnv(string $filePath, array $envData) : boolean        <= envファイル保存
    - static upFirst(string $text) : string                             <= 先頭を大文字変換
    - static downFirst(string $text) : string                           <= 先頭を小文字変換
    - static composerAutoload() : void                                  <= composerパッケージ使用時のautoload実行用
- kiwi\core\Config
    - static domains : Array<string>                   <= 許可ドメインリスト
    - static basicAuthority : Array                    <= ベーシック認証情報
- kiwi\core\ProjectConfig : Config
    - static containers: array                         <= Container経路探索
    - static containerVersions : array                 <= 適用Containerバージョン情報
    - static useCommander : bool                       <= commanderコマンド有効/無効
    - static useIncubator : bool                       <= incubatorコマンド有効/無効
- kiwi\core\ContainerConfig : Config
    - static routes : Array                            <= Web用経路探索
    - static routeShells : Array                       <= コンソール用経路探索
    - static resources : Array                         <= リソース領域設定
    - static writables : Array                         <= 書換可能データ領域設定
-kiwi\core\Handling
    - static request() : void                    <= リクエスト受信時ハンドリング
    - static route() : void                      <= 経路探索時ハンドリング
    - static routeShell() : void                 <= コンソール経路探索時ハンドリング
    - static install() : void                    <= インストール時実行用ハンドリング
    - static upgrade() : void                    <= アップグレード実行用ハンドリング
    - static downgrade() : void                  <= ダウングレード用ハンドリング
    - static uninstall() : void                  <= アンインストール時実行用ハンドリング
    - static importBefore() : void               <= インポート直前ハンドリング
    - static importAfter() : void                <= インポート直後ハンドリング
    - static exportBefore() : void               <= エクスポート直前ハンドリング
    - static exportAfter() : void                <= エクスポート直後ハンドリング
    - static dataDeleteBefore() : void           <= データ削除直前ハンドリング
    - static dataDeleteAfter() : void            <= データ削除直後ハンドリング
    - static resource() : void                   <= リソースデータアクセス時ハンドリング
    - static writable() : void                   <= 書込可能データアクセス時ハンドリング
- kiwi\core\Controller  <= Controllerクラス
    - viewTemplate : string = null              <= 使用Template名
    - view : string = null                      <= 使用View名
    - ContainerOnViewTemplate : string = null       <= 別Container使用時のContainer名
    - ContainerOnView : string = null               <= 別Container使用時のContainer名
    - viewPartOnContainer : string = null           <= 別Container使用時のContainer名
    - autoRender : boolean                      <= レンダリング有効/無効
    - handleBefore() : void                     <= action実行前ハンドリング
    - handleAfter() : void                      <= action実行後ハンドリング
    - handleDrawn() : void                      <= レンダリング後ハンドリング
- kiwi\core\ExceptionController : Controller    <= エラー判定用Controller
    - exception : Exception                     <= Exceptionクラス
    - handle() : void                           <= エラー用アクション
- kiwi\core\Shell
    - handleBefore() : void
    - handleAfter() : void
- kiwi\core\ExceptionShell
    - exception : Exception
    - handle() : void                           <= エラー用アクション
- kiwi\core\Extension
    - run() : void
- kiwi\core\ExtensionStatic
    - static load(string $extName) : Array<Extension>                                           <= 該当Extensionクラスリスト取得
    - static excute(string $extName, string $methodName = "run") : Array<Any>                           <= 該当Extensionクラス、指定メソッド実行
    - static loadOnContainer(string $ContainerName, string $extName) : Array<Extension>                 <= 指定Container, 該当Extensionクラスリスト取得
    - static excuteOnContainer(string $ContainerName, string $extName, string $methodName = "run") : Array<Any> <= 指定Container, 該当Extensionクラス、指定メソッド実行
- kiwi\core\Container   <= Container操作クラス
    - static getConfig(string $containerName) : ContainerConfig                         <= ContainerConfigクラス取得
    - static locals(array $options = null) : Array<LocalContainer>                      <= インストール済Containerリスト取得
    - static local(string $ContainerName) : LocalContainer                              <= 指定Container名のインストール済Container取得
    - static remote(ContainerRepository $containerRepository) : RemoteContainer         <= 指定リポジトリ情報での最新版RemoteContainer取得
    - static versions(string $containerName) : array<VersionContainer>                  <= 指定Containerのバージョンデータリスト取得
    - static getVersion(string $ContainerName, string $version) : VersionContainer      <= 指定Container, バージョン番号のバージョンデータContainerの取得
    - static setVersion(string, $containerName, string $containerFilePath) : boolean    <= 指定Containerファイルをバージョンデータへセット
- kiwi\core\ContainerInfo
    - name : string                                         <= Container名
    - title : string                                        <= Containerタイトル
    - version : string                                      <= バージョン番号
    - modifiedDate : date                                   <= 更新日
    - repository : ContainerRepository                      <= リポジトリ情報
    - description: string                                   <= 概要文
    - author : string                                       <= 開発者
    - email : string                                        <= Email
    - homeUrl : string                                      <= 公式ページURL
    - icon : string                                         <= アイコン画像DataUrl
    - kiwiContainer : KiwiContainer                         <= kiwiContainer.jsonデータ情報
    - kiwiRelease : array                                   <= kiwiRelease.jsonデータ情報
- kiwi\core\ContainerRepository
    - static type : ContainerRepositoryGitType                     <= リポジトリ種類(GitHub, BitBucket, GitLab)
    - static releaseUrl : string                                   <= 公開URL
    - static versionUrl : string                                   <= バージョン取得URL
- kiwi\core\ContainerRepositoryOnGitService
    - static user : string                                         <= ユーザー名
    - static repository : string                                   <= リポジトリ名
    - static branch : string                                       <= 公開ブランチ名
    - static private : boolean                                     <= プライベートリポジトリ有効/無効
    - static token : string                                        <= リポジトリトークン
- kiwi\core\ContainerRepositoryOnGitHub : ContainerRepositoryOnGitService
    - static type : ContainerRepositoryType = GitHub               <= リポジトリ種類(=GitHub)
    - static releaseUrl : string = "*****";
    - static versionUrl : string = "*****";
- kiwi\core\ContainerRepositoryOnBitBucket : ContainerRepositoryOnGitService
    - static type : ContainerRepositoryType = BitBucket            <= リポジトリ種類(=BitBucket)
    - static releaseUrl : string = "*****";
    - static versionUrl : string = "*****";
- kiwi\core\ContainerRepositoryOnGitLab : ContainerRepositoryOnGitService
    - static type : ContainerRepositoryType = GitLab               <= リポジトリ種類(=GitLab)
    - static releaseUrl : string = "*****";
    - static versionUrl : string = "*****";
- kiwi\core\LocalContainer : ContainerInfo
    - path : string                                         <= Containerディレクトリパス
    - pathVersions : string                                  <= バージョンデータディレクトリのパス
    - pathWritables : string                                <= 書込可能データディレクトリのパス
    - pathTemps : string                                    <= テンポラリディレクトリのパス
    - pathRepositories : string                             <= リポジトリ情報ディレクトリのパス
    - url : string                                          <= Containerのルーティング先URL
    - status : boolean                                      <= 有効/無効
    - import($importFilePath) : boolean                     <= 指定zipファイルパスのインポート実行
    - export() : boolean                                    <= エクスポート実行
    - uninstall() : boolean                                 <= アンインストール実行
- kiwi\core\VersionContainer : ContainerInfo
    - path : string                                         <= バージョンデータContainerバージョンのディレクトリパス
    - change() : bool                                      <= バージョンデータContainerバージョンへの移行(アップグレード/ダウングレード実行)
    - remove() : bool                                       <= バージョンデータContainerバージョンの削除
- kiwi\core\RemoteContainer : ContainerInfo
    - versions : array<RemoteContainer>                     <= 他バージョンのRemoteContainerリスト取得
    - version(string $version = null) : RemoteContainer     <= 指定バージョンのRemoteContainer取得
    - download() : VersionContainer                         <= リモートからのダウンロード実行 -> バージョンデータへセット
- kiwi\core\ContainerDevelopment
    - static create($containerOption) : bool
    - static versionUp($versionUpOption) : bool
- kiwi\core\Migration
    - upgrade() : void                                      <= アップグレード実行用イベント
    - downgrade() : void                                    <= ダウングレード実行用イベント
- kiwi\core\MigrationStatic
    - static upgrade($startVersion, $endVersion) : bool
    - static downgrade($startVersion, $endVersion) : bool
- kiwi\core\Resource
    - static type : ResourcesType                                       <= ResourceType (Resource / Writable / Temporary)
    - static container : string                                         <= カレントContainer名
    - static exists($path) : boolean                                    <= 指定パスの存在可否
    - static isDirectory($path) : boolean                               <= 指定パスのディレクトリ判定
    - static isFile($path) : boolean                                    <= 指定パスのファイル判定
    - static lists() : Array<ResourceFile / ResourceDir>                <= リソース用ディレクトリ直下のリソースファイル・ディレクトリ一覧取得
    - static read($path) : ResourceFile / ResourceDir                   <= 指定パスのリソースファイル・ディレクトリ情報取得
- kiwi\core\Writable : Resource
    - static type : ResourceType = Writable                             <= ResourceType (= Writable)
    - static mkdir($path) : boolean                                     <= 指定パスのディレクトリ作成
    - static remove($path) : boolean                                    <= 指定パスのファイル・ディレクトリ削除
    - static rename($beforePath, $afterPath) : boolean                  <= 指定パスへのファイル・ディレクトリのパス変更
    - static save($path, $contents) : boolean                           <= 指定パスでのファイルの保存
    - static copy($inputPath, $outputPath) : boolean                    <= ファイル・ディレクトリのコピー
- kiwi\core\Temporary : Writable
    - static tyep : ResourceType = Temporary                            <= ResourceType (= Writable)
    - static path : string = null                                       <= 作業ディレクトリパス
    - static identifer : string = null                                  <= 作業ディレクトリ識別子
    - static make() : ResourceTemporary                                 <= 作業ディレクトリ識別子の設定(一時作業ディレクトリ作成とディレクトリパス取得)
    - static clear() : bool                                             <= 作業ディレクトリ識別子の解除(一時作業ディレクトリのデータ削除)
- kiwi\core\ResourceDir
    - name : string                                                     <= ディレクトリ名
    - createDate : date                                                 <= 作成日時
    - modifiedDate : date                                               <= 更新日時
    - fullPath : string                                                 <= ディレクトリのフルパス
    - lists() : Array<ResourceFile / ResourceDir>                       <= 内部リソースファイル・ディレクトリ一覧取得
    - mkdir($directoryName) : boolean                                   <= 指定ディレクトリ名のディレクトリ作成
    - rmdir() : boolean                                                 <= ディレクトリの削除
    - rename($directoryName) : boolean                                  <= 指定ディレクトリ名へ変更
    - save($fileName, $contents) : boolean                              <= 指定ファイル名でのファイル保存
    - copy($outputPath) : boolean                                       <= 指定パスへのディレクトリのコピー
- kiwi\core\ResourceFile
    - name : string                                                     <= ファイル名
    - size : integer                                                    <= ファイルサイズ
    - mimeType : string                                                 <= MimeType取得
    - createDate : date                                                 <= 作成日時
    - modifiedDate : date                                               <= 更新日時
    - raw() : string                                                    <= ファイルデータ取得
    - unlink() : boolean                                                <= ファイルの削除
    - rename($fileName)                                                 <= 指定ファイル名へ変更
    - save($contents) : boolean                                         <= ファイルの上書き保存
    - copy($outputPath) : boolean                                       <= 指定パスへのファイルのコピー
- kiwi\core\Render
    - static set(string $name, any $value) : void                       <= レンダリングへデータをセット
    - static get(string $name)                                          <= セットされたデータを取得
- kiwi\core\View : Render
    - static load($viewPath = null) : void                              <= Viewファイルのロード
    - static get($viewPath = null) : string                             <= Viewファイルのレスポンス取得
- kiwi\core\ViewTemplate : Render
    - static load($templateName) : void                                 <= ViewTemplateファイルのロード
    - static get(string $templatePath = null) : string                  <= ViewTemplateファイルのレスポンス取得
- kiwi\core\ViewPart : Render
    - static load(string $viewPartPath) : void                          <= ViewPartファイルのロード
    - static get(string $viewPartPath) : string                         <= ViwePartファイルのレスポンス取得
- kiwi\core\Request
    - static method : RequestMethod             <= リクエストメソッド (Get\Post\Put\Delete\Option\Other)
    - static get($name = null) : array          <= リクエストデータ取得
- kiwi\core\Get : Request
    - static method : string = Get
- kiwi\core\Post : Request
    - static method : string = Post
- kiwi\core\Put : Request
    - static method : string = Put
- kiwi\core\Delete : Request
    - static method : string = Delete
- kiwi\core\Options : Request
    - static method : string = Optionss
- kiwi\core\Header
    - static get($name = null) : array          <= リクエストヘッダ取得
- kiwi\core\Routes
    - static route : RouteResult                <= 経路探索結果
    - static on($url) : RouteResult             <= 指定URLの経路探索経過取得
- kiwi\core\RouteResponse
    - successed : boolean
    - Container : string
    - aregments : Array<Any>
    - exception : Exception
- kiwi\core\RouteResponseWeb : RouteResponse
    - action : string
    - controller : string
    - url : string
- kiwi\core\RouteResponseConsole : RouteResponse
    - shell : string
    - command : string
