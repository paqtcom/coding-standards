<?php

namespace PaqtCom\CodingStandards\Tests;

use PaqtCom\CodingStandards\PhpstanGenerator;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class PhpstanGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_modify_phpstan_with_other_paths(): void
    {
        $contents = file_get_contents(__DIR__ . '/../phpstan.neon');
        if (false === $contents) {
            throw new RuntimeException('Could not load phpstan.neon');
        }
        $actual = Yaml::parse(PhpstanGenerator::rebuild($contents, ['app', 'config']));
        $expected = Yaml::parseFile(__DIR__ . '/../fixtures/phpstan/expected-modified-phpstan.neon');
        $this->assertEquals($expected, $actual);
    }
}
