<?php
$options = [
    [
        "animal" => "lion",
        "likes" => ["food" => "bones", "other" => "people"]
    ],
    [
        "animal" => "cat",
        "likes" => ["food" => "bones", "other" => "sleeping"]
    ],
    [
        "animal" => "dog",
        "likes" => ["food" => "meat", "other" => "playing"]
    ]
];

$sample =  [
    "animal" => "dog",
    "likes" => ["food" => "meat", "other" => "playing"]
];

echo '<pre>';
print_r(array_diff_assoc($options, $sample));
echo '</pre>';
