<?php

function extract_images(string $html) : array {
    preg_match_all('/<img\b[^>]+>/ui', $html, $matches);
    return $matches[0];
}

function correct_reductions(string $text) : string
{
    $pattern = '/\b(и)\s*(т)[\.\s]*(д|п)\b[\.\s]*/ui';
    $text = preg_replace($pattern, '$1 $2.$3. ', $text);
    return $text;
}

function tables_pointer(string $html) : array {
    $tables = [];

    $html = preg_replace_callback(
        '/<table\b([^>]+)>([\D\d]*?)<\/table>/sui',
        function ($matches) use (&$tables) {
            $attributes = $matches[1];
            $content = $matches[2];

            // Проверяем наличие хотя-бы одной ячейки с данными, и берем её содержимое
            $has_cell = preg_match('/<td\b[^>]+>([\D\d]*?)<\/td>/sui', $content, $first_cell);
            $first_cell_data = (1 === $has_cell) ? $first_cell[0] : '';

            // Проверяем наличие атрибута id, и если его нет, задаем
            $has_id = preg_match('/id\s*=\s*"([^"]+)"/ui', $attributes, $match_id);
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
    foreach ($matches[1] as $style) {
        //var_dump($matches);
        if (!isset($found_styles[$style])) {
            $found_styles[$style] = 0;
        }
        $found_styles[$style] += 1;
    }

    // Создаем классы для каждого из повторяющихся стилей
    $repeated_styles = [];
    foreach ($found_styles as $style => $count) {
        if ($count > 1) {
            $repeated_styles[$style] = uniqid('style');
        }
    }

    $html = preg_replace_callback(
        '/<(\w+)\s+(\w+\s*=\s*"[\d\D]+?"\s*)>/ui',
        function ($matches) use ($found_styles, $repeated_styles) {
            $element = $matches[1];
            $attributes = $matches[2];
            
            $has_style = preg_match('/\bstyle\s*=\s*"([\D\d]+?)"/ui', $attributes, $match_attr);
            if (1 === $has_style && $found_styles[$match_attr[1]] > 1) {
                // Удаляем атрибут style
                $attributes = preg_replace('/\bstyle\s*=\s*"([\D\d]+?)"/ui', '', $attributes);

                // Добавляем новый класс или создаем атрибут класс, вписывая в него новый стиль
                $style_class_name = $repeated_styles[$match_attr[1]];
                $class = '';
                $has_class = preg_match('/\bclass\s*=\s*"([^"]+)"+?/ui', $attributes, $match_class);
                if (1 == $has_class) {
                    $other_classes = $match_class[1];
                    $class = "class=\"$style_class_name $other_classes\"";
                }
                else {
                    $class = "class=\"$style_class_name\"";
                }
                return "<$element $class>";
            }

            return "<$element $attributes>";
        },
        $html
    );

    return [$repeated_styles, $html];
}