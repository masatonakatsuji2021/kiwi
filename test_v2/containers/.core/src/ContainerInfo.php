<?php

namespace kiwi\core;

use DateTime;

class ContainerInfo {
    // Container名
    public string $name;
    
    // Containerタイトル
    public string $title;
    
    // バージョン番号
    public string $version;

    // 更新日
    public DateTime $modifiedDate;

    // リポジトリ情報
    public ContainerRepository $repository;

    // 概要文
    public string $description;

    // 開発者
    public string $author;

    // Email
    public string $email;

    // 公式ページURL
    public string $homeUrl;

    // アイコン画像DataUrl
    public string $icon;

    // kiwiContainer.jsonデータ情報
    public array $kiwiContainer;

    // kiwiRelease.jsonデータ情報
    public array $kiwiRelease;
}