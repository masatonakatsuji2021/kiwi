<?php

namespace kiwi\test1;

use kiwi\core\BlockEvent as _BlockEvent;

class BlockEvent extends _BlockEvent{
    
    public function begin() {
        $this->composerAutoload();
    }
}