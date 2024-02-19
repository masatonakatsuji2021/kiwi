## # kiwiFw ディレクトリ構成

container内部の構造詳細は[ここに記載](#container_structur)

```
/containers                     <= container領域
    /.core                      <= コアライブラリ
        /versions
            /1.0.0
                /src
                    /Controller.php
                    ....
                /autoload.php.default
                /kiwiContainer.json
                /kiwiRelease.json
            /autoload.php
    /main                       <= メインアプリ用container
        /versions               <= バージョン別データ
            /1.0.0
            /1.0.1
            /1.0.2
            ...
        /repository             <= containerリポジトリ設定用 (管理対象外)
            /Repository.php     <= RepositoryクラスPHP
            /token              <= アクセストークン情報
        /temps                  <= テンポラリ用 (管理対象外)
        /writables              <= 書込可能データ用 (管理対象外)
    /module1
    /module2
    ...
/root                                   <= ルートディレクトリ
    /shell
        /commander                      <= コンソール実行用コマンド
        /incubator                      <= container管理/ソース管理用コマンド
    /web
        /index.php                      <= Web公開用php
        /.htaccess
/Config.php                             <= Configクラス
/routes.env                             <= container別経路探索情報用env
/.gitignore
```

|ディレクトリ名|概要|
|:--|:--|
|containers|kiwiのContainerごとに管理するディレクトリ|
|- {Container名}|Containerディレクトリ<br>Containerごとのソース、テンポラリ、データ管理はこの中で行う|
|- - versions|バージョン別のContainerソースファイル用ディレクトリ<br>Containerのインストール時はインストールするバージョンが生成される|
|-  - repository|Containerのリポジトリ設定用ディレクトリ<br>リモートリポジトリが存在する場合はこの中にリポジトリ接続設定用クラスや接続時に使用するアクセストークン等が入る|
|- - temps|Containerのテンポラリディレクトリ|
|- - writables|Containerの書換可能リソースファイル用ディレクトリ|
|root|各種ルートディレクトリ|
|- shell|コンソール系のルートディレクトリ|
|- web|Web用のルートディレクトリ|
|configs|設定ファイルディレクトリ|
|- Config.php|Configクラス|
|- kiwi.json|kiwi.jsonファイル|

<a id="container_structure"></a>

## # containerディレクトリ構成

```
/versions                               <= バージョン別アプリソース用ディレクトリ
    /1.0.0                              <= バージョン番号
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
        /vendor                             <= composerパッケージディレクトリ (任意)
        /views                              <= View用ディレクトリ
            /main
                /index.view
                ..
            ..
        /viewTemplate                       <= ViewTemplate用ディレクトリ
            /default.view
            ...
        /viewParts                          <= ViewPart用ディレクトリ
            /sample.view
            ...
        /resources                          <= containerリソースデータ領域 (書換不可)
            /common
                /img
                    /sample.png
                    ...
                /css
                    /style.css
                    ...
                ...
            ...
        /ContainerConfig.php                <= Configクラス
        /kiwicontainer.json                 <= kiwicontainer.json (container設定情報)
        /kiwiRelease.json                   <= kiwiRelease.json (各バージョンごとの履歴)
    /1.0.1
    /1.0.2
    ...
/repository                         <= containerリポジトリ設定用 (管理対象外)
    /Repository.php                 <= RepositoryクラスPHP
    /token                          <= アクセストークン情報
/temps                              <= テンポラリ用 (管理対象外)
    ...
/writables                          <= 書込可能データ用 (管理対象外)
    ...
```
