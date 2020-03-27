<?php
/*
    Name: Simple html templating for PHP
    Author: Max Műller
    github: https://github.com/Hell0XD/Simple-html-templating-for-PHP
*/
class Engine {
    function __construct(string $path) {
        $this->file = file_get_contents($path);
    }

    // generate_html generates html string from given variables and file
    public function generate_html(array $variables, string $file = "") {
        // if no file given use default file from constructor
        $file ? "" : $file = $this->file;

        foreach($variables as $key => $value) {
            if (is_array($value)) {
                // get all content in block
                $block = self::get_string_between($file, "<!--{{#".$key."}}-->", "<!--{{/#".$key."}}-->");
                $tmp = "";

                // for every block defined block and generate recursively html
                foreach($value as $localVariables) $tmp .= $this->generate_html($localVariables, $block);
                
                // replace old content for new content
                $file = self::replace_string_between($file, "<!--{{#".$key."}}-->", "<!--{{/#".$key."}}-->", $tmp);

                // remove html comments
                $file = str_replace("<!--{{#".$key."}}-->", "", $file);
                $file = str_replace("<!--{{/#".$key."}}-->", "", $file);
            }else{
                // replace only attribute
                if (strpos($key, ':') !== false) {
                    // get the attribute to replace
                    $atrribute = explode(":", $key)[1];
                    
                    // get the key
                    $key = explode(":", $key)[0];

                    // isolate block to manipulation
                    $block = self::get_string_between($file, "<!--{{".$key."}}-->", "<!--{{/".$key."}}-->");
                    // replace blocks atrributes to new ones
                    $newblock = self::replace_string_between($block, $atrribute.'="', '"', $value);
                    if($newblock == $value){
                        $newblock = self::replace_string_between($block, $atrribute."='", "'", $value);
                    }
                    //var_dump($newblock);
                    // replace old block with new block
                    $file = self::replace_string_between($file, "<!--{{".$key."}}-->", "<!--{{/".$key."}}-->", $newblock);
                }else{
                    // replace old content for new content
                    $file = self::replace_string_between($file, "<!--{{".$key."}}-->", "<!--{{/".$key."}}-->", $value);
                }
                // remove html comments
                $file = str_replace("<!--{{".$key."}}-->", "", $file);
                $file = str_replace("<!--{{/".$key."}}-->", "", $file);
            }
        }
        return $file;
    }

    // get_string_between gets string between two substring
    public static function get_string_between(string $string, string $start, string $end){
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    // replace_string_between replaces string between two substrings
    public static function replace_string_between(string $string, string $start, string $end, string $replacement) {
        $pos = strpos($string, $start);
        $start = $pos === false ? 0 : $pos + strlen($start);
        $pos = strpos($string, $end, $start);
        $end = $pos === false ? strlen($string) : $pos;
        return substr_replace($string, $replacement, $start, $end - $start);
    }
}