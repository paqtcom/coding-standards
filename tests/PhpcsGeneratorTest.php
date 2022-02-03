<?php

namespace PaqtCom\CodingStandards\Tests;

use PaqtCom\CodingStandards\PhpcsGenerator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PhpcsGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_modify_the_files_list_of_the_default_codestyle(): void
    {
        $contents = file_get_contents(__DIR__ . '/../phpcs.xml');
        if (false === $contents) {
            throw new RuntimeException('Could not load phpcs.xml');
        }
        $actual = PhpcsGenerator::modifyFiles($contents, ['app', 'modules']);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../fixtures/phpcs/expected-modified-phpcs.xml',
            $actual
        );
    }
}
