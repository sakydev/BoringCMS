<?php

function boringPath(string $path): string {
    $packagePath = dirname(__DIR__);

    if (!$path) {
        return $packagePath;
    }

    return sprintf('%s/%s', $packagePath, $path);
}

function phrase($key, $replace = [], $locale = null): string
{
    $keys = explode('.', $key);
    $translation = trans(array_shift($keys), $replace, $locale);

    foreach ($keys as $segment) {
        if (!is_array($translation) || !array_key_exists($segment, $translation)) {
            return $key;
        }

        $translation = $translation[$segment];
    }

    return $translation;
}
