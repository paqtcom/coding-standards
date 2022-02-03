<?php

namespace PaqtCom\CodingStandards;

use DOMDocument;
use DOMElement;
use RuntimeException;

class PhpcsGenerator
{
    public static function modifyFiles(string $phpcsContents, array $files): string
    {
        $domDocument = new DOMDocument('1.0');
        $domDocument->loadXML($phpcsContents);
        $elements = $domDocument->getElementsByTagName('file');
        for ($i = $elements->length; --$i >= 0;) {
            $file = $elements->item($i);
            if (null !== $file && $file->parentNode) {
                $file->parentNode->removeChild($file);
            }
        }
        $ruleset = $domDocument->documentElement;
        if (!$ruleset) {
            throw new RuntimeException('No root node found in the phpcs.xml!');
        }
        foreach ($files as $file) {
            /** @var DOMElement $fileNode */
            $fileNode = $domDocument->createElement('file', $file);
            $ruleset->appendChild($fileNode);
        }

        $result = $domDocument->saveXML();
        if (false === $result) {
            throw new RuntimeException('Could not create XML as string');
        }

        return $result;
    }
}
