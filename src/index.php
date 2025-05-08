<?php

namespace App;

function search(array $docs, string $search): array
{
    $result = [];

    $allText = implode(' ', array_column($docs, 'text'));
    preg_match_all('/\w+/', $allText, $matches);
    $allWords = array_unique($matches[0]);
    $allDocs = array_column($docs, 'id');
    $countDocs = count($docs);

    $invertedIndex = array_fill_keys($allWords, []);
    $tf = array_fill_keys($allDocs, []);
    $idf = [];
    $tfidf = [];

    foreach ($docs as $doc) {
        ['id' => $id, 'text' => $text] = $doc;

        preg_match_all('/\w+/', $search, $searchWords);
        preg_match_all('/\w+/', $text, $textWords);

        $rankArray = [];
        $tfWords = array_fill_keys(array_unique($textWords[0]), 0);

        foreach ($textWords[0] as $textWord) {
            (in_array($id, $invertedIndex[$textWord])) ? : $invertedIndex[$textWord][] = $id;
            $tfWords[$textWord]++;
        }

        $textWordsCount = count($textWords[0]);

        foreach ($tfWords as $word => $count) {
            $tf[$id][$word] = $count / $textWordsCount;
        }

        foreach ($searchWords[0] as $searchWord) {
            $count = count(array_filter($textWords[0], fn($word) => $word == $searchWord));
            $rankArray[] = $count;
        }

        $rank = min($rankArray);
        $wordCount = array_sum($rankArray);

        if (($rank > 0) || ($wordCount > 0)) {
            $result[$id] = $rank;
        }
    }

    foreach ($invertedIndex as $word => $docs) {
        $idf[$word] = log10($countDocs / count($docs));
    }

    foreach ($tf as $doc => $words) {
        foreach ($words as $word => $index) {
            $tfidf[$doc][$word] = $index * $idf[$word];
        }
    }

    uasort($result, fn($a, $b) => $b <=> $a);
    return array_keys($result);
}