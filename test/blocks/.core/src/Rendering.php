<?php

namespace kiwi\core;

use kiwi\core\Controller;
use kiwi\core\Routes;

class Rendering {

    public static ?Controller $controllerDelegate = null;

    public static function getView(string $viewPath = null) : string {
        ob_start();
        self::view($viewPath);
        return ob_get_clean();
    }

    public static function view(string $viewPath = null) : void {

        if(!$viewPath) {
            $block = Routes::$route -> block;
            if (isset(self::$controllerDelegate -> viewOnBlock)) {
                $block = self::$controllerDelegate -> viewOnBlock;
            }
            if (isset(self::$controllerDelegate -> view)) {
                $viewPath = KIWI_ROOTDIR . "/blocks/" . $block . "/views/" . self::$controllerDelegate -> view . ".view";
            }
            else{
                $viewPath = KIWI_ROOTDIR . "/blocks/" . $block . "/views/" . Routes::$route -> controller . "/" . Routes::$route -> action . ".view";
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
            $block = Routes::$route -> block;
            if (isset(self::$controllerDelegate -> viewTemplateOnBlock)) {
                $block = self::$controllerDelegate -> viewTemplateOnBlock;
            }
            $templatePath = KIWI_ROOTDIR . "/blocks/" . $block . "/viewTemplates/" . self::$controllerDelegate -> viewTemplate . ".view";
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
        if (!$viewPartPath) {
            $block = Routes::$route -> block;
            if (isset(self::$controllerDelegate -> viewPartOnBlock)) {
                $block = self::$controllerDelegate -> viewPartOnBlock;
            }
            $viewPartPath = KIWI_ROOTDIR . "/blocks/" . $block . "/viewParts/" . self::$controllerDelegate -> viewPartOnBlock . ".view";
        }

        if (!file_exists($viewPartPath)) {
            echo "[View Error] ViwePart file not found.";
            return;
        }

        require $viewPartPath;
    }

    public static function setData(string $name, $value) : void {
        $$name = $value;
    }

}