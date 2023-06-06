<?php
include_once "required.php";
include "files.php";

if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
} else {
    $offset = 0;
}

if (isset($_GET['count'])) {
    $count = intval($_GET['count']);
} else {
    $count = 10;
}

$addImageSizes = function ($name) {
    list($width, $height) = getimagesize("images/2050/" . $name);
    return array(
        "name" => $name,
        "height" => $height
    );
};

$files = list_dir_desc("images/2050/");

$files = array_slice($files, $offset, $count);

$files = array_map($addImageSizes, $files);

echo json_encode($files, JSON_THROW_ON_ERROR);
