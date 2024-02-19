## # 動作設計

### : Containerの新規インストール、アップグレード、ダウングレードの基本ロジック

- 同じContainer名で同じバージョンデータが存在しない場合 (新規インストール)
    - /containers/{container名}/versions/{インストールContainerバージョン番号} にコピー
    - /containers/{container名} に repository, temps, writables ディレクトリを作成
    - /configs/kiwi.json を更新
    - 展開データContainerのMigrateクラスのupgradeメソッドを初期バージョン番号->最新バージョン番号まで順に実行
        例: バージョンが1.0.3 の場合、 順に Migrate1.0.0 -> Migrate1.0.1 -> Migrate1.0.2 -> Migrate1.0.3 を実行
    - /containers/{container名}/versions/{インストールContainerバージョン番号}/ContainerConfigクラスの handleInstall メソッドを実行
- 同じContainer名で同じバージョンデータが存在する場合
    - 既存Containerのバージョン番号 < 展開データのバージョン番号 (アップグレード)
        - /configs/kiwi.json を更新
        - 展開データContainerのMigrateクラスのupgrateメソッドを既存Containerバージョン番号の次番号 -> 最新バージョン番号まで順に実行
            例: 1.0.5 -> 1.0.9 の場合、 順に Migrate1.0.6 -> Migrate1.0.7 -> Migrate1.0.8 -> Migrate1.0.9 を実行
        - /containers/{container名}/versions/{インストールContainerバージョン番号}/ContainerConfigクラスの handleInstall メソッドを実行
    - 既存Containerのバージョン番号 > 展開データのバージョン番号 (ダウングレード)
        - /configs/kiwi.json を更新
        - 既存ContainerのMigrateクラスのdowngrateメソッドを既存Containerバージョン番号 -> 展開データContainerバージョン番号の次まで順に実行
            例: 1.0.9 -> 1.0.5 の場合、 順に Migrate1.0.9 -> Migrate1.0.8 -> Migrate1.0.7 -> Migrate1.0.6 を実行
        - /containers/{container名}/versions/{インストールContainerバージョン番号}/ContainerConfigクラスの handleInstall メソッドを実行

### : ローカルzipファイルからContainerをインストールまたはアップデート

- zipファイルをテンポラリに展開 (以後展開データとする)
- 展開データにkiwiContainer.jsonが存在しない場合
    - エラー出力して終了
- 展開データのkiwiContainer.jsonのフォーマットが正常でない場合
    - エラー出力して終了
- 上記インストール、アップグレード、ダウングレードの基本ロジックのフローと同様
- 展開データをテンポラリから削除
- 完了

### : Containerをアンインストール

- /containers/{container名}のMigrateクラスのdowngradeメソッドを最大バージョン番号 -> 初期バージョン番号まで順に実行
    例: 対象Containerバージョンが1.0.4、，初期バージョンが1.0.0 の場合、1.0.4 -> 1.0.3 -> 1.0.2 -> 1.0.1 -> 1.0.0 を実行
- /containers/{container名} を削除
- kiwi.jsonを更新

### : リモートリポジトリからContainerをインストール (リモートインストール)

- リポジトリへの接続情報クラス(Repository.php及びtokenファイル)をテンポラリに保存
- リモートリポジトリからContainerのzipデータをダウンロード
- 以後ローカルインポートと作業は同様
- 展開データContainerに repository ディレクトリを作成して、テンポラリのリポジトリ接続情報ファイルをコピー
- テンポラリのリポジトリ接続情報ファイルを削除

### : Containerのデータをインポート

- インポートzipファイルをテンポラリに展開 (以後インポートデータ)
- Container内にImportクラスとbeforeメソッドが存在する場合
    - Importクラスのbeforeメソッドを実行
- インポートデータを書換可能データ領域 /writables/{Container名} にコピー
- Container内にImportクラスとafterメソッドが存在する場合
    - Importクラスのafterメソッドを実行
- インポートデータをテンポラリから削除

### : Containerのデータをエクスポート

- Container内にExportクラスとbeforeメソッドが存在する場合
    - Exportクラスのbeforeメソッドを実行
- 書換可能リソースデータ領域 /writables/{Container名} をzip圧縮
- Container内にExportクラスとbeforeメソッドが存在する場合
    - Exportクラスのbeforeメソッドを実行

### : Containerの作成

- 下記のオプションを設定
    - Container名
    - 初期バージョン番号
    - リポジトリ種類 (GitHub, BitBucket, GitLab, none)
        - * 以下 種類が none 以外の場合
            - ユーザー名
            - リポジトリ名
            - 公開ブランチ名
    - 開発者
    - 概要文
    - Email
    - 公式URL
- /containers/{Container名}/versions/{初期バージョン番号} にソースファイルを生成
- /containers/{Container名}/app/kiwiBlock.json をJSON作成
- /containers/{Container名}/app/kiwiRelease.json に初期バージョンの履歴追加
- /configs/kiwi.json を更新