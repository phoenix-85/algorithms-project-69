<?php

namespace App;

function search(array $docs, string $search): array
{
    $result = [];

    foreach ($docs as $doc) {
        ['id' => $id, 'text' => $text] = $doc;

        preg_match_all('/\w+/', $search, $searchWords);
        preg_match_all('/\w+/', $text, $textWords);

        $rankArray = [];
        $wordCount = 0;

        foreach ($searchWords[0] as $searchWord) {
            $rankArray[] = count(array_filter($textWords[0], fn($word) => $word == $searchWord));
        }

        $rank = min($rankArray);

        foreach ($searchWords[0] as $searchWord) {
            $wordCount += count(array_filter($textWords[0], fn($word) => $word == $searchWord));
        }

        if (($rank > 0) || ($wordCount > 0)) {
            $result[$id] = $rank;
        }
    }

    uasort($result, fn($a, $b) => $b <=> $a);
    return array_keys($result);
}