<?php

function list_dir(string $path)
{
    return array_values(
        array_diff(
            scandir($path),
            array('..', '.')
        )
    );
}

function list_dir_desc(string $path)
{
    $list = list_dir($path);
    rsort($list);
    return $list;
}

function read_body_json(): array
{
    $inputJSON = file_get_contents('php://input');
    return json_decode($inputJSON, true);
}
