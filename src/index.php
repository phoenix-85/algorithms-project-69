<?php

namespace App;

function search(array $docs, string $search): array
{
    $result = [];

    foreach ($docs as $doc) {
        ['id' => $id, 'text' => $text] = $doc;
        $words = explode(' ', $text);
        if (array_search($search, $words)) {
            $result[] = $id;
        }
    }

    return $result;
}