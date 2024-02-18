<?php

namespace kiwi\core;

class Controller {
    
    public string $viewTemplate;

    public string $view;

    public string $viewOnContainer;

    public string $viewTemplateOnContainer;
    
    public string $viewPartOnContainer;

    public bool $autoRender = false;

    public function handleBefore() : void {}

    public function handleAfter() : void {}

    public function handleDrawn() : void {}
}