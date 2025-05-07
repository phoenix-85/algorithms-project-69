<?php

namespace App;

function search(array $docs, string $search): array
{
    $result = [];
    $invertedIndex = [];

    foreach ($docs as $doc) {
        ['id' => $id, 'text' => $text] = $doc;

        preg_match_all('/\w+/', $search, $searchWords);
        preg_match_all('/\w+/', $text, $textWords);

        $rankArray = [];
        $wordCount = 0;

        foreach ($textWords[0] as $textWord) {
            $invertedIndex[$textWord][] = $id;
        }

        $invertedIndex = array_map(fn($item) => array_unique($item), $invertedIndex);

        foreach ($searchWords[0] as $searchWord) {
            $count = count(array_filter($textWords[0], fn($word) => $word == $searchWord));
            $rankArray[] = $count;
            $wordCount += $count;
        }

        $rank = min($rankArray);

        if (($rank > 0) || ($wordCount > 0)) {
            $result[$id] = $rank;
        }
    }

    uasort($result, fn($a, $b) => $b <=> $a);
    return array_keys($result);
}