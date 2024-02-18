<?php

namespace kiwi\core;

use DateTime;

class BlockInfo {
    public string $name;
    public string $version;
    public int $versionNumber;
    public DateTime $modifiedDate;
    public string $author;
    public string $description;
    public string $email;
    public string $homeUrl;
    public $icon;
}

class LocalBlock extends BlockInfo {

    public function installOrUpgrade($version = null) : bool {

        return true;
    }

    public function import($importZipFilePath) : bool {

        return true;
    }

    public function export($exportZipFilePath) : bool {

        return true;
    }

    public function detaDelete() : bool {

        return true;
    }

    public function uninstall() : bool {

        return true;
    }
}

class RemoteBlock extends BlockInfo {

    public function download($version = null) : bool {
        
        return true;
    }
}

class Block {

    public static function locals() : array {

        return [];
    }

    public static function local($blockName) : ?LocalBlock {

        return null;
    }

    public static function remote($repository) : ?RemoteBlock {

        return null;
    }

    public static function backupSet(string $blockZipFile) : bool {

        return true;
    }
}