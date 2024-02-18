<?php

namespace kiwi\core;

use kiwi\core\Controller;
use kiwi\core\Routes;

class Rendering {

    public static ?Controller $controllerDelegate = null;
    private static $_dataBuffer = [];

    public static function getView(string $viewPath = null) : string {
        ob_start();
        self::view($viewPath);
        return ob_get_clean();
    }

    public static function view(string $viewPath = null) : void {

        if(!$viewPath) {
            $container = Routes::$route -> container;
            if (isset(self::$controllerDelegate -> viewOnContainer)) {
                $container = self::$controllerDelegate -> viewOnContainer;
            }
            if (isset(self::$controllerDelegate -> view)) {
                $viewPath = KIWI_ROOT_CONTAINER . "/" . $container . "/app/views/" . self::$controllerDelegate -> view . ".view";
            }
            else{
                $viewPath = KIWI_ROOT_CONTAINER . "/" . $container . "/app/views/" . Routes::$route -> controller . "/" . Routes::$route -> action . ".view";
            }        
        }

        if (!file_exists($viewPath)) {
            echo "[View Error] View file not found.";
            return;
        }

        require $viewPath;
    }

    public static function getViewTemplate(string $templatePath = null) : string {
        ob_start();
        self::viewPart($templatePath);
        return ob_get_clean();
    }

    public static function viewTemplate(string $templatePath = null) : void {
        if (!$templatePath) {
            $container = Routes::$route -> container;
            if (isset(self::$controllerDelegate -> viewTemplateOnContainer)) {
                $container = self::$controllerDelegate -> viewTemplateOnContainer;
            }
            $templatePath = KIWI_ROOT_CONTAINER . "/" . $container . "/app/viewTemplates/" . self::$controllerDelegate -> viewTemplate . ".view";
        }

        if (!file_exists($templatePath)) {
            echo "[View Error] ViewTemplate file not found.";
            return;
        }

        require $templatePath;
    }

    public static function getViewPart(string $viewPartPath) : string {
        ob_start();
        self::getViewPart($viewPartPath);
        return ob_get_clean();
    }

    public static function viewPart(string $viewPartPath) : void {

        $container = Routes::$route -> container;
        if (isset(self::$controllerDelegate -> viewPartOnContainer)) {
            $container = self::$controllerDelegate -> viewPartOnContainer;
        }
        $viewPartPath = KIWI_ROOT_CONTAINER . "/" . $container . "/app/viewParts/" . $viewPartPath . ".view";
  
        if (!file_exists($viewPartPath)) {
            echo "[View Error] ViwePart file not found.";
            return;
        }

        require $viewPartPath;
    }

    public static function set(string $name, $value) : void {
        self::$_dataBuffer[$name] = $value;
    }

    public static function get(string $name) {
        if (isset(self::$_dataBuffer[$name])) {
            return self::$_dataBuffer[$name];
        }
    }

}