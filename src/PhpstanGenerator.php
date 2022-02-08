<?php

namespace PaqtCom\CodingStandards;

use Symfony\Component\Yaml\Yaml;

class PhpstanGenerator
{
    public static function rebuild(string $phpstanContents, array $paths): string
    {
        $array = Yaml::parse($phpstanContents);
        $array['parameters']['paths'] = $paths;

        return Yaml::dump($array, 12);
    }
}
