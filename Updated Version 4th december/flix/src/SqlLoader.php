<?php
class SqlLoader
{
    public static function load($filename): bool|string
    {
        $file_path = __DIR__ . '/../sql/' . $filename;
        if (file_exists(filename: $file_path)) {
            return file_get_contents(filename: $file_path);
        }
        throw new Exception(message: "SQL file not found: " . $filename);
    }
}