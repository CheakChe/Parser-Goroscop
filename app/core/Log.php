<?php


class Log
{
    public static function writeLog(string $text): void
    {
        fwrite(fopen('log.txt', 'ab'), PHP_EOL . date('Y.m.d H:i:s') . " $text " . PHP_EOL);
    }
}