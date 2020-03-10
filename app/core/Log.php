<?php


class Log
{
    static function writeLog($text)
    {
        fwrite(fopen('log.txt', 'a'), PHP_EOL . date('Y.m.d H:i:s') . " $text " . PHP_EOL);
    }
}