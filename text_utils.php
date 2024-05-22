<?php

function extract_images(string $html) : array {
    preg_match_all('/<img(\s+\w+="[\D\d]+?")+\s*>/sui', $html, $matches);
    return $matches[0];
}

function correct_reductions(string $text) : string
{
    $pattern = '/\b(и)\s*(т)[.\s]*(д|п)\b[.\s]*/ui';
    $text = preg_replace($pattern, '$1 $2.$3. ', $text);
    return $text;
}

function tables_pointer(string $html) : array {
    $tables = [];

    $html = preg_replace_callback(
        '/<table(\s+\w+="[\D\d]+?")*\s*>([\D\d]*?)<\/table>/sui',
        function ($matches) use (&$tables) {
            $attributes = $matches[1];
            $content = $matches[2];

            // Проверяем наличие хотя-бы одной ячейки с данными, и берем её содержимое
            $has_cell = preg_match('/<td(?:\s+\w+="[\D\d]+?")*\s*>([\D\d]*?)<\/td>/sui', $content, $first_cell);
            $first_cell_data = (1 === $has_cell) ? $first_cell[0] : '';

            // Проверяем наличие атрибута id, и если его нет, задаем
            $has_id = preg_match('/id\s*=\s*"([\D\d]+?)"/sui', $attributes, $match_id);
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

function simplify_repeated_styles(string $html) {
    $found_styles = [];
    // Находим все стили и считаем кол-во их повторений
    preg_match_all('/\bstyle\s*=\s*"([\D\d]+?)"/', $html, $matches);
    foreach ($matches[0] as $style) {
        if (!isset($found_styles[$style[1]])) {
            $found_styles[$style[1]] = 0;
        }
        $found_styles[$style[1]] += 1;
    }

    // Создаем классы для каждого из повторяющихся стилей
    $repeated_styles = [];
    foreach ($found_styles as $style => $count) {
        if ($count > 1) {
            $repeated_styles[$style] = 'style' . uniqid('', true);
        }
    }

    $html = preg_replace_callback(
        '/<(\w+)(\s+\w+="[\D\d]+?")*\s*>/sui',
        function ($matches) use ($found_styles, $repeated_styles) {
            $element = $matches[1];
            $attributes = $matches[2];
            
            $has_style = preg_match('/\bstyle\s*=\s*"([\D\d]+?)"/', $attributes, $match_attr);
            if (1 === $has_style && $found_styles[$match_attr[1]] > 1) {
                $class_name = $repeated_styles[$match_attr[1]];

                
            }

            return $matches[0];
        },
        $html
    );

    return $found_styles;
}