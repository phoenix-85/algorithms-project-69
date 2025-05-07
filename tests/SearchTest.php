<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use function App\search;

class SearchTest extends TestCase
{
    public function testSearch(): void
    {
        $actual1 = search([], 'abcd');
        $actual2 = search([
            ['id' => 'doc1', 'text' => 'gonna shoot aw'],
            ['id' => 'doc2', 'text' => 'love peace no shooter']
        ],
            'shoot');
        $this->assertEquals([], $actual1);
        $this->assertEquals(['doc1'], $actual2);
    }
}