<?php

namespace kiwi\core;

class Controller {
    
    public string $viewTemplate;

    public string $view;

    public string $viewTemplateOnBlock;

    public string $viewOnBlock;

    public string $viewPartOnBlock;

    public bool $autoRender = false;

    public function handleBefore() : void {}

    public function handleAfter() : void {}

    public function handleDrawn() : void {}
}