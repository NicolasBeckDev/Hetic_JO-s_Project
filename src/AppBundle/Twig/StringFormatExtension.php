<?php

namespace AppBundle\Twig;

class StringFormatExtension extends \Twig_Extension
{
    protected $unwantedLetters;
    protected $unwantedSymbols;

    public function __construct()
    {
        $this->unwantedLetters = [
            'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
        ];

        $this->unwantedSymbols = [
            '&' => ''
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('snake', array($this, 'toSnakeCase')),
            new \Twig_SimpleFilter('camel', array($this, 'toCamelCase')),
        ];
    }

    public function replaceUnwantedCharacters($string)
    {
        $str = strtr($string, $this->unwantedLetters);
        $str = strtr($str, $this->unwantedSymbols);

        return $str;
    }

    public function toSnakeCase($string)
    {
        $str = mb_strtolower(preg_replace('/\s+/', '_', $this->replaceUnwantedCharacters($string)));

        return $str;
    }

    public function toCamelCase($string)
    {
        $str = lcfirst(str_replace(' ', '_', ucwords($this->replaceUnwantedCharacters($string), ' ')));

        return $str;
    }
}