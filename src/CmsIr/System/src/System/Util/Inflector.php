<?php

namespace CmsIr\System\Util;

//use Doctrine\Common\Util\Inflector as DoctrineInflector;
//extends DoctrineInflector
class Inflector
{
    static $normalizationTable = array('ą' => 'a', 'ś' => 's', 'ł' => 'l', 'ę' => 'e', 'ó' => 'o', 'ż' => 'z', 'ź' => 'z', 'ć' => 'c', 'ń' => 'n', 'Ą' => 'A', 'Ś' => 'S', 'Ł' => 'L', 'Ę' => 'E', 'Ó' => 'O', 'Ż' => 'Z', 'Ź' => 'Z', 'Ć' => 'C', 'Ń' => 'N');

    public static function slugify($word, $separator = '-', $maxLength = 0)
    {
        if (empty($word))
        {
            return '';
        }

        $word = strtr($word, self::$normalizationTable);

        // transliterate latin characters to basic ascii
        if (function_exists('iconv')) {
            $word = iconv('utf-8', 'us-ascii//TRANSLIT', $word);
        }

        $word = preg_replace(array('/[^A-Za-z0-9_+& \/\.\-]/', '/[_+& \/\.\-]+/'), array('', $separator), $word);

        if ($maxLength > 0) {
            $word = trim($word);
            $word = substr($word, 0, $maxLength);
        }

        $word = strtolower($word);

        /**
         * @todo tableize method, or get it from Doctrines Inflector
         */
        return str_replace("_", $separator, self::tableize($word));
    }

    public static function unslugify($word)
    {
        /**
         * @todo classify method, or get it from Doctrines Inflector
         */
        return str_replace("-", "_", self::classify($word));
    }

    public static function slugifyAssoc(array $assoc, $separator = '-', $maxLength = 0)
    {
        $words = array();

        foreach($assoc as $key => $value)
        {
            $words[] = self::slugifyKeyValue($key, $value, $separator);
        }

        $word = implode($separator, $words);
        return self::slugify($word);
    }

    public static function assocToString(array $data)
    {
        $parts = array();

        foreach($data as $key => $value)
        {
            $parts[] = sprintf('%s => %s', $key, (string) $value);
        }

        return implode(', ', $parts);
    }
    
    public static function slugifyKeyValue($key, $originalValue = null, $separator = '-')
    {
        if (is_null($originalValue))
        {
            $convertedValue = 'null';

        } elseif (is_array($originalValue))
        {
            $words = array();

            foreach($originalValue as $key => $currentValue)
            {
                $words[] = self::slugifyKeyValue($key, $currentValue, $separator);
            }

            $convertedValue = implode($separator, $words);

        } else
        {
            $convertedValue = (string) $originalValue;
        }

        $stringValue = ((string) $key) . $separator . $convertedValue;
        return $stringValue;
    }

    public static function sanitizeFilename($name, $ext = "", $separator = '-', $withRandomPart = true, $maxLength = 0)
    {
        $name = strtr($name, self::$normalizationTable);

        // transliterate latin characters to basic ascii
        if (function_exists('iconv')) {
            $name = iconv('utf-8', 'us-ascii//TRANSLIT', $name);
        }

        // in case that name was wrongly passed with extension get only filename
        $name = pathinfo($name, PATHINFO_FILENAME);

        if (empty($ext)) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
        }

        $name = trim($name);

        if ($maxLength > 0) {
            $name = substr($name, 0, $maxLength);
        }

        // in case that filename have separators or weird chars
        // first remove all characters that aren't alphanumeric or separators
        // then replace unnecesary separators with default one
        $name = preg_replace(array('/[^A-Za-z0-9_+& \/\.\-]/', '/[_+& \/\.\-]+/'), array('', $separator), $name);

        $name = strtolower($name);

        $randomPart = "";

        if ($withRandomPart) {
            // generate random string based on file name hash
            $randomPart = $separator . substr(sha1($name . mt_rand(11111, 99999)), 0, 8);
        }

        $extensionPart = "";

        if (!empty($ext)) {
            $extensionPart = '.' . strtolower($ext);
        }

        return $name . $randomPart . $extensionPart;
    }
    
    /**
     * Convert word in to the format for a Doctrine table name. Converts 'ModelName' to 'model_name'
     *
     * @param  string $word  Word to tableize
     * @return string $word  Tableized word
     */
    public static function tableize($word)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $word));
    }

    /**
     * Convert a word in to the format for a Doctrine class name. Converts 'table_name' to 'TableName'
     *
     * @param string  $word  Word to classify
     * @return string $word  Classified word
     */
    public static function classify($word)
    {
        return str_replace(" ", "", ucwords(strtr($word, "_-", "  ")));
    }

    /**
     * Camelize a word. This uses the classify() method and turns the first character to lowercase
     *
     * @param string $word
     * @return string $word
     */
    public static function camelize($word)
    {
        return lcfirst(self::classify($word));
    }
    
}
