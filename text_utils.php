<?php

function extract_images(string $html) : array {
    preg_match_all('/<img(\s+\w+="[\D\d]+?")+\s*>/ui', $html, $matches);
    return $matches[0];
}

function correct_reductions(string $text) : string
{
    $pattern = '/\b(и)\s*(т)[.\s]*(д|п)\b[.\s]*/ui';
    $text = preg_replace($pattern, '$1 $2.$3. ', $text);
    return $text;
}

function tables_pointer(string $html) {
    $tables = [];

    $html = preg_replace_callback(
        '/<table(\s+\w+="[\D\d]+?")*\s*>([\D\d]*?)<\/table>/ui',
        function ($matches) use (&$tables) {
            $attributes = $matches[1];
            $content = $matches[2];

            // Проверяем наличие хотя-бы одной ячейки с данными, и берем её содержимое
            $has_cell = preg_match('/<td(?:\s+\w+="[\D\d]+?")*\s*>([\D\d]*?)<\/td>/ui', $content, $first_cell);
            $first_cell_data = (1 === $has_cell) ? $first_cell[0] : '';

            // Проверяем наличие атрибута id, и если его нет, задаем
            $has_id = preg_match('/id\s*=\s*"([\D\d]+?)"/ui', $attributes, $match_id);
            $id = '';
            if (0 === $has_id || empty($match_id[1])) {
                $id = uniqid('', true);
                $attributes = "id=\"$id\"" . $attributes;
            }
            else {
                $id = $match_id[1];
            }

            $tables[] = ["id" => $id, "first_cell_data" => $first_cell_data];

            return "<table $attributes>$content</table>";
        },
        $html
    );

    return [$tables, $html];
}