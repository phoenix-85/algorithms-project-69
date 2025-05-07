<?php

namespace App;

function search(array $docs, string $search): array
{
    $result = [];

    foreach ($docs as $doc) {
        ['id' => $id, 'text' => $text] = $doc;

        preg_match_all('/\w+/', $text, $matches);

        $count = count(array_filter($matches[0], fn($word) => $word == $search));

        if ($count != 0) {
            $result[$id] = $count;
        }
    }

    uasort($result, fn($a, $b) => $b <=> $a);
    return array_keys($result);
}