<?php

namespace kiwi\core;

class Controller {
    
    public ?string $viewTemplate = null;

    public ?string $view = null;

    public ?string $viewTemplateOnBlock = null;

    public ?string $viewOnBlock = null;

    public bool $autoRender = false;

    public function filterBefore () : void {}

    public function filterAfter () : void {}
}