# test_task9
Необходимо написать класс на PHP с именем Authenticity в файле authenticity.php, который имеет публичный метод get($inn), на вход принимает ИНН физического лица (7704313718 и 7804480543) и возвращает результат в виде массива:

Объявление: public function get ($inn)
Входной параметр: $inn - ИНН физического лица (7704313718 и 7804480543)
Результат - один из массивов:
[
   'inn' => $inn
   ,'message' => 'По заданным критериям поиска сведений не найдено.'
   ,'authenticity' => true
]
или
[
   'inn' => $inn
   ,'message' => 'Наличие признака недостоверности'
   ,'authenticity' => false
];
Адрес для проверки наличия записи о недостоверности: https://pb.nalog.ru/search.html 

Проверка тестового задания:

Файл для проверки результатов тестового задания:

include_once ('authenticity.php');
$authenticity = new Authenticity();
echo 'Есть признак недостоверности:<pre>';
print_r($authenticity->get('7704313718'));
echo '</pre>';
echo 'Нет признака о недостоверности:<pre>';
print_r($authenticity->get('7804480543'));
echo '</pre>';
