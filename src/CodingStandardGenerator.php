<?php

namespace PaqtCom\CodingStandards;

use RuntimeException;

class CodingStandardGenerator
{
    public static function renderAll(string $rootFolder, array $paths): void
    {
        self::updatePhpCsFixerConfig($rootFolder);
        self::updatePhpmdConfig($rootFolder);
        self::updatePhpcsConfig($rootFolder, $paths);
        self::updatePhpstanConfig($rootFolder, $paths);
    }

    public static function updatePhpstanConfig(string $rootFolder, array $paths)
    {
        if (file_exists($rootFolder . '/phpstan.neon')) {
            $contents = file_get_contents($rootFolder . '/phpstan.neon');
            if ($contents === false) {
                throw new RuntimeException('"' . $rootFolder . '/phpstan.neon" could not be loaded!');
            }
            self::renderFile($rootFolder . '/phpstan.neon', PhpstanGenerator::rebuild($contents, $paths));
        } else {
            self::renderFile($rootFolder . '/phpstan.neon', self::renderPhpstanConfig($paths));
        }
    }

    public static function updatePhpCsFixerConfig(string $rootFolder): void
    {
        if (file_exists($rootFolder . '/.php_cs')) {
            unlink($rootFolder . '/.php_cs');
        }
        if (file_exists($rootFolder . '/.php_cs.cache')) {
            unlink($rootFolder . '/.php_cs.cache');
        }
        self::renderFile($rootFolder . '/.php-cs-fixer.php', self::renderPhpCsFixerConfig());
    }

    public static function updatePhpmdConfig(string $rootFolder): void
    {
        self::renderFile($rootFolder . '/phpmd.xml', self::renderPhpmdConfig());
    }

    public static function updatePhpcsConfig(string $rootFolder, array $paths): void
    {
        self::renderFile($rootFolder . '/phpcs.xml', self::renderPhpcsXml($paths));
    }

    public static function renderPhpCsFixerConfig(): string
    {
        return self::loadDataFile('.php-cs-fixer.php');
    }

    public static function renderPhpmdConfig(): string
    {
        return self::loadDataFile('phpmd.xml');
    }

    public static function renderPhpstanConfig(array $paths): string
    {
        $phpstan = self::loadDataFile('phpstan.neon');

        return PhpstanGenerator::rebuild($phpstan, $paths);
    }

    public static function renderPhpcsXml(array $files): string
    {
        $phpcs = self::loadDataFile('phpcs.xml');

        return PhpcsGenerator::modifyFiles($phpcs, $files);
    }

    private static function renderFile(string $file, string $contents): void
    {
        if (false === file_put_contents($file, $contents)) {
            throw new RuntimeException('Could not create file ' . $file);
        }
    }

    private static function loadDataFile(string $file): string
    {
        $contents = file_get_contents(__DIR__ . '/../' . $file);
        if (!$contents) {
            throw new RuntimeException('Could not load data file ' . $file);
        }

        return $contents;
    }
}
