<?php

namespace App;

function search(array $docs, string $search): array
{
    $result = [];

    foreach ($docs as $doc) {
        ['id' => $id, 'text' => $text] = $doc;

        preg_match_all('/\w+/', $text, $matches);

        if (in_array($search, $matches[0])) {
            $result[] = $id;
        }
    }
    return $result;
}