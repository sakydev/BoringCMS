<?php

function boringPath(string $path): string {
    $packagePath = dirname(__DIR__);

    if (!$path) {
        return $packagePath;
    }

    return sprintf('%s/%s', $packagePath, $path);
}
