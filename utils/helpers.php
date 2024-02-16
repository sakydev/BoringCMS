<?php

function boringPath(string $path): string {
    $packagePath = dirname(__DIR__);

    if (!$path) {
        return $packagePath;
    }

    return sprintf('%s/%s', $packagePath, $path);
}

function phrase(array|string $key, array $replace = [], $locale = null): string
{
    if (!strpos($key, '.')) {
        return $key;
    }

    $keys = explode('.', $key);
    $translation = trans(array_shift($keys), [], $locale);

    foreach ($keys as $segment) {
        if (!is_array($translation) || !array_key_exists($segment, $translation)) {
            return $key;
        }

        $translation = $translation[$segment];
    }

    foreach ($replace as $name => $value) {
        if (strstr($translation, ":{$name}")) {
            $translation = str_replace(":{$name}", $value, $translation);
        }
    }

    return trim($translation);
}
