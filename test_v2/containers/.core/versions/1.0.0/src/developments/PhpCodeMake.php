<?php

namespace kiwi\core\developments;

class PhpCodeMake {

    public static function createReturnArray(array $array, int $indent = 0, bool $container = false) : string {

        $str = "";
        if (!$container) {
            $str = "<?php\n";
            $str .= "\nreturn [\n";
        }

        $indent++;

        $indentStr = "";
        for($i = 0 ; $i < $indent ; $i++){
            $indentStr .= "    ";
        }

        foreach ($array as $key => $value) {
            $str .= $indentStr;
            if (array_values($array) !== $array) {
                $str .= "\"" . $key . "\" => ";
            }

            if (is_array($value)) {
                $subStr = self::createReturnArray($value, $indent, true);
                $str .= "[\n" . $subStr;
                $str .= $indentStr;
                $str .= "],\n";
            }
            else {
                if (is_string($value)) {
                    $str .= "\"" . $value . "\",\n";
                }
                else if (is_int($value)) {
                    $str .= $value . ",\n";
                }
                else if (is_bool($value)) {
                    $str .=  ($value)? 'true':'false';
                    $str .= ",\n";
                }
            }
        }

        if (!$container){
            $str .= "];\n";
        }
        return $str;
    }
}