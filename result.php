<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/main.css" />
    <title>Результат анализа</title>
</head>

<body>
    <div class="content-container">
        <h1>Результат анализа</h1>

        <?php
        if (isset($_POST['data']) && $_POST['data']) 
        {
            echo '<div class="src_text">' . htmlspecialchars($_POST['data']) . '</div>'; 
            test_it($_POST['data']); 
        } else
        {
            echo '<div class="src_error">Нет текста для анализа</div>'; 
        }

        function test_it($text)
        {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto'); 

            $cifra = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            $punctuation = array('.', ',', ';', ':', '!', '?', '-', '(', ')', '"', "'");
            $cifra_amount = 0;
            $letter_count = 0;
            $uppercase_count = 0;
            $lowercase_count = 0;
            $punctuation_count = 0;
            $word = '';
            $words = array();

            for ($i = 0; $i < mb_strlen($text); $i++) {
                $char = mb_substr($text, $i, 1);

                if (in_array($char, $cifra)) {
                    $cifra_amount++;
                } elseif (preg_match('/[А-Яа-яA-Za-z]/u', $char)) { 
                    $letter_count++;
                    if (mb_strtoupper($char) === $char) {
                        $uppercase_count++;
                    } else {
                        $lowercase_count++;
                    }
                } elseif (in_array($char, $punctuation)) {
                    $punctuation_count++;
                }

                if ($char == ' ' || $i == mb_strlen($text) - 1) {
                    if ($word) {
                        $word = mb_strtolower($word, 'UTF-8'); // Приводим слово к нижнему регистру
                        if (isset($words[$word])) {
                            $words[$word]++;
                        } else {
                            $words[$word] = 1;
                        }
                    }
                    $word = '';
                } else {
                    $word .= $char;
                }
            }

            echo '<table class="analysis_table">';
            echo '<tr><th>Параметр</th><th>Значение</th></tr>';
            echo '<tr><td>Количество символов</td><td>' . mb_strlen($text) . '</td></tr>';
            echo '<tr><td>Количество букв</td><td>' . $letter_count . '</td></tr>';
            echo '<tr><td>Количество строчных букв</td><td>' . $lowercase_count . '</td></tr>';
            echo '<tr><td>Количество заглавных букв</td><td>' . $uppercase_count . '</td></tr>';
            echo '<tr><td>Количество знаков препинания</td><td>' . $punctuation_count . '</td></tr>';
            echo '<tr><td>Количество цифр</td><td>' . $cifra_amount . '</td></tr>';
            echo '<tr><td>Количество слов</td><td>' . count($words) . '</td></tr>';
            echo '</table>';

            ksort($words); // Сортировка массива $words по ключам в порядке возрастания
            echo '<h2>Список всех слов и их количество вхождений:</h2>';
            echo '<table class="analysis_table">';
            echo '<tr><th>Слово</th><th>Количество вхождений</th></tr>';
            foreach ($words as $word => $count) {
                echo '<tr><td>' . htmlspecialchars($word) . '</td><td>' . $count . '</td></tr>';
            }
            echo '</table>';

            $symbols = test_symbs($text);
            echo '<h2>Вхождения каждого символа:</h2>';
            echo '<table class="analysis_table">';
            echo '<tr><th>Символ</th><th>Количество вхождений</th></tr>';
            foreach ($symbols as $symbol => $count) {
                echo '<tr><td>' . htmlspecialchars($symbol) . '</td><td>' . $count . '</td></tr>';
            }
            echo '</table>';
        }

        function test_symbs($text)
        {
            $symbs = array(); 
            $l_text = mb_strtolower($text, 'UTF-8'); // переводим текст в нижний регистр

            for ($i = 0; $i < mb_strlen($l_text); $i++) {
                $char = mb_substr($l_text, $i, 1);
                if (isset($symbs[$char])) {
                    $symbs[$char]++;
                } else {
                    $symbs[$char] = 1;
                }
            }
            return $symbs; // массив с числом вхождений символов в тексте
        }
        ?>

        <a href="index.html" class="back_link">Другой анализ</a>
    </div>
</body>

</html>