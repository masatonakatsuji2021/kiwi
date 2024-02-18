## # 動作設計

### : Containerの新規インストール、アップグレード、ダウングレードの基本ロジック

- 同じContainer名のContainer(既存Container)が存在しない場合 (新規インストール)
    - 展開データをバージョンフォルダー /containers/{container名}/versions/{展開データContainerのバージョン番号} にコピー
    - バージョンフォルダーデータを /containers/{Container名}/app にコピー (以後展開データContainer)
    - 展開データContainer に repository, temps, writables ディレクトリを作成
    - 展開データContainerのMigrateクラスのupgradeメソッドを初期バージョン番号->最新バージョン番号まで順に実行
        例: バージョンが1.0.3 の場合、 順に Migrate1.0.0 -> Migrate1.0.1 -> Migrate1.0.2 -> Migrate1.0.3 を実行
- 同じContainer名のContainer(既存Container)が存在する場合
    - 既存Containerのバージョン番号 < 展開データのバージョン番号 (アップグレード)
        - 既存Container /Containers/{container名}/app がバージョンフォルダーデータとして存在しない場合
            - 既存Containerをバージョンフォルダー /containers/{container名}/versions/{既存Containerのバージョン番号} にコピー
        - 展開データContainer がバージョンフォルダーデータとして存在しない場合
            - 展開データをバージョンフォルダー /containers/{container名}/versions/{展開データContainerのバージョン番号} にコピー
        - 展開データバージョンフォルダーデータを /Containers/{Container名}/app_tmp にコピー
        - 既存データのパスを /Containers/{Container名}/app_delete に変更
        - 展開データContainerのパスを /containers/{container名}/app に変更
        - 既存データを削除
        - 展開データContainerのMigrateクラスのupgrateメソッドを既存Containerバージョン番号の次番号 -> 最新バージョン番号まで順に実行
            例: 1.0.5 -> 1.0.9 の場合、 順に Migrate1.0.6 -> Migrate1.0.7 -> Migrate1.0.8 -> Migrate1.0.9 を実行
    - 既存Containerのバージョン番号 > 展開データのバージョン番号 (ダウングレード)
        - 既存ContainerのMigrateクラスのdowngrateメソッドを既存Containerバージョン番号 -> 展開データContainerバージョン番号の次まで順に実行
            例: 1.0.9 -> 1.0.5 の場合、 順に Migrate1.0.9 -> Migrate1.0.8 -> Migrate1.0.7 -> Migrate1.0.6 を実行
        - 既存Container /Containers/{container名}/app がバージョンフォルダーデータとして存在しない場合
            - 既存Containerをバージョンフォルダー /containers/{container名}/versions/{既存Containerのバージョン番号} にコピー
        - 展開データContainer がバージョンフォルダーデータとして存在しない場合
            - 展開データをバージョンフォルダー /containers/{container名}/versions/{展開データContainerのバージョン番号} にコピー
        - 展開データバージョンフォルダーデータを /Containers/{Container名}/app_tmp にコピー
        - 既存データのパスを /Containers/{Container名}/app_delete に変更
        - 展開データのパスを /containers/{container名}/app に変更
        - 既存データを削除

### : ローカルzipファイルからContainerをインストールまたはアップデート

- zipファイルをテンポラリに展開 (以後展開データとする)
- 展開データにkiwiContainer.jsonが存在しない場合
    - エラー出力して終了
- 展開データのkiwiContainer.jsonのフォーマットが正常でない場合
    - エラー出力して終了
- 上記インストール、アップグレード、ダウングレードの基本ロジックのフローと同様
- 展開データをテンポラリから削除
- 完了

### : Containerを選択したバージョンに戻す (ロールバック/ロールフォワード)

- 既存Container < 移行バージョン の場合 (アップグレード)
    - 移行バージョンContainer /containers/{Container名}/versions/{移行バージョン番号} を /Containers/{Container名}/app_tmp にコピー
    - 既存Containerのパスを /Containers/{Container名}_delete に変更
    - 移行バージョンContainerのパスを /Containers/{Container名} に変更
    - 既存Containerを削除
    - 移行バージョンContainerのMigrateクラスのupgradeメソッドを現行バージョンの次番号 -> 前バージョン番号まで順に実行
        例: 1.0.5 -> 1.0.9 の場合、 順に Migrate1.0.6 -> Migrate1.0.7 -> Migrate1.0.8 -> Migrate1.0.9 を実行
    - 完了
- 既存Container = 移行バージョン の場合
    - 何もせずに終了
- 既存Container > 移行バージョン の場合 (ダウングレード)
    - 既存Container /containers/{Container名}/app のMigrateクラスのdowngrateメソッドを既存Containerのバージョン番号 -> 移行バージョン番号の次まで順に実行
        例: 1.0.9 -> 1.0.5 の場合、 順に Migrate1.0.9 -> Migrate1.0.8 -> Migrate1.0.7 -> Migrate1.0.6 を実行
    - 移行バージョンContainer /containers/{Container名}/versions/{移行バージョン番号} を /Containers/{Container名}/app_tmp にコピー
    - 既存Containerのパスを /Containers/{Container名}_delete に変更
    - 移行バージョンContainerのパスを /Containers/{Container名} に変更
    - 既存Containerを削除
    - 完了

### : Containerをアンインストール

- 対象ContainerのMigrateクラスのdowngradeメソッドを最大バージョン番号 -> 初期バージョン番号まで順に実行
    例: 対象Containerバージョンが1.0.4、，初期バージョンが1.0.0 の場合、1.0.4 -> 1.0.3 -> 1.0.2 -> 1.0.1 -> 1.0.0 を実行
- 対象Containerを削除

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
- /containers/{Container名}/appに初期ソースを設置
- /containers/{Container名}/app/kiwiBlock.json をJSON作成
- /containers/{Container名}/app/kiwiRelease.json に初期バージョンの履歴追加