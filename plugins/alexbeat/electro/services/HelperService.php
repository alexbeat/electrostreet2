<?php

namespace Alexbeat\Electro\Services;
use Carbon\Carbon;

class HelperService
{
    public function __construct()
    {
    }

    public function getRussianPluralForm($n, $form1, $form2, $form5)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) {
            return $form5;
        }
        if ($n1 > 1 && $n1 < 5) {
            return $form2;
        }
        if ($n1 == 1) {
            return $form1;
        }
        return $form5;
    }

    public function getFormattedDate($date_string) {
        $date = Carbon::parse($date_string);
        return $date->locale('ru')->translatedFormat('d F Y');
    }

    static function formatVideoUrl($url) {
        // Преобразуем YouTube ссылки
        if (strpos($url, 'youtube.com') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $queryVars);
            $videoId = $queryVars['v'];
            return 'https://www.youtube.com/embed/' . $videoId;
        }

        if (strpos($url, 'youtu.be') !== false) {
            $videoId = substr(parse_url($url, PHP_URL_PATH), 1);
            return 'https://www.youtube.com/embed/' . $videoId;
        }

        // Преобразуем RuTube ссылки
        if (strpos($url, 'rutube.ru/video/') !== false) {
            $videoId = basename(parse_url($url, PHP_URL_PATH));
            return 'https://rutube.ru/play/embed/' . $videoId;
        }

        return $url; // Возвращаем оригинальную ссылку, если не удалось преобразовать
    }

    public static function briefphone($value)
    {
        $s = str_replace(array('(', ')', ' ', '-',), '', $value);
        if (substr($s, 0, 1) == '8') {
            $s = '+7' . substr($s, 1);
        }
        return $s;
    }

    public static function formatPrice($value, $forceZero = false)
    {
        if (!$value && !$forceZero) return;

        return number_format($value, 0, '.', ' ') .  ' ₽';
    }

    static function toPascalCase($string) {
        // Разбиваем строку на части, используя дефис в качестве разделителя
        $words = explode('-', $string);

        // Преобразуем каждое слово, начиная с заглавной буквы
        $pascalCaseWords = array_map('ucfirst', $words);

        // Соединяем все части обратно в строку
        return implode('', $pascalCaseWords);
    }

}
