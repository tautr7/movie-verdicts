<?php
// config/env.php

/**
 * Loads environment variables from a specified .env file.
 *
 * @param string $file The path to the .env file.
 * @throws \Exception If the file cannot be read or parsed.
 * @return void
 */
function loadEnv($file = __DIR__ . '/../.env'): void
{
    if (!file_exists($file)) {
        throw new Exception("The .env file does not exist.");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Check for comments and skip them
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse the line and set the environment variable
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Load the environment variables
loadEnv();