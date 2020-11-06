<?php

namespace CEKW\WpPluginFramework\Core;

trait DynamicKeyResolverTrait
{
    public function resolveKeyFromClassName(string $suffix): string
    {
        $classNameParts = explode('\\', static::class);
        $className = array_pop($classNameParts);
        $camelCaseKey = str_replace($suffix, '', $className);
        $kebabCaseKey = preg_replace('%([a-z])([A-Z])%', '\1-\2', $camelCaseKey);

        print_r($camelCaseKey);

        return strtolower($kebabCaseKey);
    }
}