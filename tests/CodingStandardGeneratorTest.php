<?php

namespace PaqtCom\CodingStandards\Tests;

use PaqtCom\CodingStandards\CodingStandardGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class CodingStandardGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_render_all_codestyle_files(): void
    {
        $tempFolder = sys_get_temp_dir() . '/' . uniqid('coding-standard');
        mkdir($tempFolder);
        try {
            CodingStandardGenerator::renderAll($tempFolder, ['src']);
            $this->assertFileExists($tempFolder . '/.php-cs-fixer.php');
            $this->assertFileExists($tempFolder . '/phpstan.neon');
            $this->assertFileExists($tempFolder . '/phpmd.xml');
            $this->assertFileExists($tempFolder . '/phpcs.xml');
        } finally {
            system('rm -rf ' . escapeshellarg($tempFolder));
        }
    }

    /** @test */
    public function it_can_patch_phpstan_paths(): void
    {
        $tempFolder = sys_get_temp_dir() . '/' . uniqid('coding-standard');
        mkdir($tempFolder);
        file_put_contents(
            $tempFolder . '/phpstan.neon',
            file_get_contents(__DIR__ . '/../fixtures/phpstan/level-1-example.neon')
        );
        try {
            CodingStandardGenerator::renderAll($tempFolder, ['src', 'tests']);
            $this->assertFileExists($tempFolder . '/phpstan.neon');
            $actual = Yaml::parseFile($tempFolder . '/phpstan.neon');
            $expected = Yaml::parseFile(__DIR__ . '/../fixtures/phpstan/level-1-example.expected.neon');
            $this->assertEquals($expected, $actual);
        } finally {
            system('rm -rf ' . escapeshellarg($tempFolder));
        }
    }
}
