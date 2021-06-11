<?php
extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, 'reg');
if (!isset($reg_c)) {
    echo "Error command";
    exit;
}
ini_set('max_execution_time',300);
set_time_limit (600000);
$title='Обработка файлов в каталоге';
$title1='Администрирование и конфигурирование 1С:Предприятие 8.1 ::: Урок {NOMERUROKA}'; // Заголовок файла
$file_name_start='helpwinxp-'; // Начальное название файла
$startUrokNum=1; // Начальный номер урока
$page_body_text='';
$fileprefix='';
$filelinks='';
// Открыть заведомо существующий каталог и начать считывать его содержимое
if (!isset($reg_dir)) {$reg_dir='data';$filedir='data/';} else {$filedir=$reg_dir.'/';}
$fileArr=array(); // Массив с названиями файлов
include('include/filelist-functions.php');
//*********************************************************************
// {COMMAND} 1 Вывести в браузер список файлов и записать его в файл filelist.txt
// {FORM INPUT} text|dir|data Название каталога:
// {COMMAND} 1a Записать список файлов в текстовый файл filelist.txt
// {FORM INPUT} text|dir|data Название каталога:
// {COMMAND} 1b Вывести в браузер файлы в теге IMG
// {FORM INPUT} text|dir|data Название каталога:
// {COMMAND} 1c Вывести в браузер файлы в теге IMG (Для записи в текст HTML)
// {FORM INPUT} text|dir|data Название каталога:
// {COMMAND} 2 Вывести в браузер ссылки на файлы в виде страницы HTML
// {FORM INPUT} text|dir|data Название каталога:
// {COMMAND} 3 Вывести в браузер список файлов и контрольную сумму MD5
// {FORM INPUT} text|dir|data Название каталога:
// {COMMAND} 4 Вывести в браузер список файлов и контрольную сумму MD5 и записать в таблицу MySQL
// {FORM INPUT} text|dir|data Название каталога:
//*********************************************************************
if (($reg_c=='1') || ($reg_c=='2') || ($reg_c=='3') || ($reg_c=='4') || ($reg_c=='1a') || ($reg_c=='1b') || ($reg_c=='1c'))
	{ObrabotkaCommand($reg_c,$reg_dir);}
//*********************************************************************
// {COMMAND} 5 Удаляем все файлы указанные в списке filelist.txt
//*********************************************************************
if ($reg_c=='5') {DeletaFileList();}
//*********************************************************************
// {COMMAND} 5a Удаляем все файлы указанные в списке из нужного файла
// {FORM INPUT} text|dir|data2 Название каталога где лежат файлы:
// {FORM INPUT} text|fname|filelist.txt Имя файла с названиями файлов:
//*********************************************************************
if ($reg_c=='5a')	{include('include/filelist-cmd5a.php');}
//*********************************************************************
// {COMMAND} 6 Очистка тегов HTML из файлов указанных в списке
// Все файлы отмеченные в списке filelist.txt
// Копируются текстовая часть кода HTML после тега (<h3) и заканчивая тегом (/content)
// В браузер выводятся ссылки на эти файлы в виде таблицы HTML
// {FORM INPUT} text|dir|data2 Название каталога:
// {FORM INPUT} text|file_name_start|hlp1c81- Начальное название файла:
// {FORM INPUT} text|title1|Администрирование&nbsp;и&nbsp;конфигурирование&nbsp;1С:Предприятие&nbsp;Урок&nbsp;{NOMERUROKA} Заголовок страниц:
//*********************************************************************
if ($reg_c=='6') {include('include/filelist-cmd6.php');}
//*********************************************************************
// {COMMAND} 6a Преобразование файлов TXT в файлы HTML
// {FORM INPUT} text|fname|story Начальное имя файла:
// {FORM INPUT} text|dir|data2 Каталог для записи файлов HTML:
// В браузер выводятся ссылки на эти файлы в виде таблицы HTML
//*********************************************************************
if ($reg_c=='6a') {include('include/filelist-cmd6a.php');}
//*********************************************************************
// {COMMAND} 6b Очистка тегов HTML из файлов указанных в списке
// Очистка тегов HTML из файлов указанных в списке
// Все файлы отмеченные в списке filelist.txt
// Копируются текстовая часть кода HTML после тега (<h3) и заканчивая тегом (/content)
// В браузер выводятся ссылки на эти файлы в виде таблицы HTML
// {FORM INPUT} text|dir2|data2 Каталог для записи файлов HTML:
//*********************************************************************
if ($reg_c=='6b') {include('include/filelist-cmd6b.php');}
//*********************************************************************
// {COMMAND} 7 Переименовать файлы в соответствии с сылками из filelist.lst
// Переименовать файлы в соответствии с сылками из filelist.lst
//*********************************************************************
if ($reg_c=='7') {include('include/filelist-cmd7.php');}
//*********************************************************************
// {COMMAND} 8 <b>Создание миниатюр</b> и страницы HTML со списком миниатюр
// {FORM INPUT} text|dir|data2 Каталог для записи миниатюр:
// {FORM INPUT} text|filenamehtml|index-dtops.html Имя файла HTML:
// {FORM INPUT} text|miniw|160 Ширина миниаютры:
// {FORM INPUT} text|minih|120 Высота миниатюры:
// {FORM INPUT} checkbox|naoborot|yes Изменение размера наоборот
// {FORM INPUT} text|quality|85 Качество сжатия JPEG:
// {FORM INPUT} checkbox|createhtml|yes Сделать страницу HTML
// {FORM INPUT} checkbox|createimgname|yes Вставлять названия фото из списка filelist2.txt
// {FORM INPUT} checkbox|imagegif|yes Сохранить в формате GIF
// {FORM INPUT} checkbox|createlink|yes Создавать ссылки на оригиналы
// {FORM INPUT} checkbox|linkpopup|yes Создавать ссылки в виде PopUp
// {FORM INPUT} checkbox|wintarget|yes Открывать ссылки в новом окне
// {FORM INPUT} checkbox|savetxt|yes Сохранить в текстовый файл данные о файлах
// {FORM INPUT} text|column|5 Количество колонок для HTML:
// {FORM INPUT} text|tablewidth|100% Ширина таблицы:
// Создание миниатюр и страницы HTML со списком миниатюр
// 1. Читать список файлов из файла filelist.txt
// 2. Создать каталог tnimages
// 3. Для каждого файла создать миниатюрю в файле tnimages/tnXXXXXX - где ХХХХХХ имя файла из списка
// 4. Вставить миниатюру и ссылку к файлу в шаблон index-dtops.html
// 5. Записать скрипт index-dtopsjs.js , который выводит в случайном порядке миниатюры после 11 месяца 2008 года
//*********************************************************************
if ($reg_c=='8') {
    $filename_files = __DIR__ . '/filelist.txt';
    include('include/filelist-cmd8.php');
}
//*********************************************************************
// {COMMAND} 9 Вытащить статистику из файла access.log
// Вытащить статистику из файла access.log
//*********************************************************************
if ($reg_c=='9') {include('include/filelist-cmd9.php');}
//*********************************************************************
// {COMMAND} 10 Просмотр заговолков страниц и перенос файлов в другой каталог
// {FORM INPUT} text|dir1|data2 Каталог с иходными файлами:
// {FORM INPUT} text|dir2|data2 Каталог куда нужно копировать:
//*********************************************************************
if ($reg_c=='10') {include('include/filelist-cmd10.php');}
//*********************************************************************
// {COMMAND} 11 Просмотр заговолков страниц из каталога
// {FORM INPUT} text|dir|data2 Каталог с иходными файлами:
//*********************************************************************
if ($reg_c=='11') {include('include/filelist-cmd11.php');}
//*********************************************************************
// {COMMAND} 12 <b>Просмотр заговолков</b> текстовых файлов и сортировка в алфивитном порядке
// {FORM INPUT} text|dir|text Каталог с текстовыми файлами:
// {FORM INPUT} checkbox|showrazdel|yes Показывать раздел
// {FORM INPUT} checkbox|showautor|yes Показывать автора
// {FORM INPUT} checkbox|showfname|yes Показывать имя файла
//*********************************************************************
if ($reg_c=='12') {include('include/filelist-cmd12.php');}
//*********************************************************************
// {COMMAND} 13 Загрузить ссылки из сайта <b>Yandex-market</b> Шаг1
// {FORM INPUT} text|dir|data2 Каталог для записи миниатюр:
// {FORM INPUT} text|yurl|http://market.yandex.ru/guru.xml?hid=91491&CMD=-RR=9,0,0,0-VIS=41E2-CAT_ID=160043-BPOS=380-EXC=1-PG=20-GRID=20&greed_mode=true Ссылка к странице яндекса с картинками:
// {FORM INPUT} checkbox|loadlink1|yes Загрузить последнюю ссылку
//*********************************************************************
if ($reg_c=='13') {include('include/filelist-cmd13.php');}
//*********************************************************************
// {COMMAND} 13a Загрузить ссылки из сайта <b>Yandex-market</b> повторять Шаг1
//*********************************************************************
if ($reg_c=='13a') {include('include/filelist-cmd13a.php');}
//*********************************************************************
// {COMMAND} 15 <b>Дубликаты строк</b> удалить в файле filelist.txt
//*********************************************************************
if ($reg_c=='15') {include('include/filelist-cmd15.php');}
//*********************************************************************
// {COMMAND} 14 Загрузить картинки из сайта <b>Yandex-market</b> Шаг2
// {FORM INPUT} text|dir|data2 Каталог для записи миниатюр:
// {AUTOSTART} 20000 Включить автозапуск
//*********************************************************************
if ($reg_c=='14') {include('include/filelist-cmd14.php');}
//*********************************************************************
// {COMMAND} 16 Создать каталоги и перенести файлы с фотками <b>Yandex-market</b>
// {FORM INPUT} text|dir|data2 Каталог c файлами и описаниями файлов:
// {FORM INPUT} text|dir2|data3 Каталог c файлами и описаниями файлов:
//*********************************************************************
if ($reg_c=='16') {include('include/filelist-cmd16.php');}
//*********************************************************************
// {COMMAND} 17 Сделать множество повторяющихся ссылок
//*********************************************************************
if ($reg_c=='17') {include('include/filelist-cmd17.php');}
//*********************************************************************
// {COMMAND} 18 Найти все теги h1,h2,h3
// {FORM INPUT} text|dir|data Каталог c файлами:
//*********************************************************************
if ($reg_c=='18') {include('include/filelist-cmd18.php');}
//*********************************************************************
// {COMMAND} 19 <b>Распаковать все архивы</b> в каталоги
// {FORM INPUT} text|dir|data Каталог c архивами:
//*********************************************************************
if ($reg_c=='19') {include('include/filelist-cmd19.php');}
//*********************************************************************
// {COMMAND} 20 <b>Найти фото</b> перенести в каталог и создать описание файла  
// {FORM INPUT} text|dir|data Каталог c каталогами фотами:
// {FORM INPUT} text|dir2|data Каталог c новыми фотами:
//*********************************************************************
if ($reg_c=='20') {include('include/filelist-cmd20.php');}
//*********************************************************************
// {COMMAND} 21 <b>Создание миниатюр</b> и нескольких страниц HTML со списком миниатюр
// {FORM INPUT} text|dir|data2 Каталог для записи миниатюр:
// {FORM INPUT} text|filenamehtml|index-dtop{NN}.html Имя файла HTML где обязательно {NN}:
// {FORM INPUT} text|miniw|160 Ширина миниаютры:
// {FORM INPUT} text|minih|120 Высота миниатюры:
// {FORM INPUT} text|quality|85 Качество сжатия JPEG:
// {FORM INPUT} checkbox|createimgname|yes Вставлять названия фото из списка filelist2.txt
// {FORM INPUT} checkbox|createimgsize|yes Вставлять размер оригинального файла
// {FORM INPUT} checkbox|imagegif|yes Сохранить в формате GIF
// {FORM INPUT} checkbox|createlink|yes Создавать ссылки на оригиналы
// {FORM INPUT} checkbox|linkpopup|yes Создавать ссылки в виде PopUp
// {FORM INPUT} checkbox|wintarget|yes Открывать ссылки в новом окне
// {FORM INPUT} text|column|5 Количество колонок для HTML:
// {FORM INPUT} text|tablewidth|100% Ширина таблицы:
// {FORM INPUT} text|numpiconpage|20 Количество миниатюр на одной странице:
// {FORM INPUT} text|ptitle|Фотографии Заголовок страницы :
// Создание миниатюр и страницы HTML со списком миниатюр
// 1. Читать список файлов из файла filelist.txt
// 2. Создать каталог tnimages
// 3. Для каждого файла создать миниатюрю в файле tnimages/tnXXXXXX - где ХХХХХХ имя файла из списка
// 4. Вставить миниатюру и ссылку к файлу в шаблон index-dtops.html
// 5. Записать скрипт index-dtopsjs.js , который выводит в случайном порядке миниатюры после 11 месяца 2008 года
//*********************************************************************
if ($reg_c=='21') {include('include/filelist-cmd21.php');}
//*********************************************************************
// {COMMAND} 22 <b>Upload</b> загрузить файл на сервер
// {FORM INPUT} file|userfile|userfile Выберите файл:
// {FORM INPUT} text|dir|data Каталог для загрузки:
// {FORM INPUT} hidden|MAX_FILE_SIZE|1000000 Размер файла 9Мб
//*********************************************************************
if ($reg_c=='22') {include('include/filelist-cmd22.php');}
//*********************************************************************
// {COMMAND} 23 <b>Показать названия</b> товаров из описания
// {FORM INPUT} text|dir|data Каталог для загрузки:
// {FORM INPUT} text|s|1 Номер начального файла:
// {FORM INPUT} text|e|1 Номер конечного файла:
//*********************************************************************
if ($reg_c=='23') {include('include/filelist-cmd23.php');}
//*********************************************************************
// {COMMAND} 24 <b>Склеить файлы</b> в один файл с учетом переносов
// {FORM INPUT} text|dir|data2 Каталог для сохранения:
// {FORM INPUT} text|name|fileNNN.txt Имя файла (NNN-номер):
// {FORM INPUT} text|numfiles|0 Количество файлов в одном (0-если все в один):
// {FORM INPUT} text|numaddzero|3 Количество добавляемых нулей в начале названия файла:
// {FORM INPUT} text|numstartfile|1 Начинать нумерацию файлов с:
// {FORM INPUT} checkbox|razdel|yes Добавлять разделитель перед началом нового файла
// {FORM INPUT} checkbox|startnorazdel|yes В самом начале не добавлять разделитель, а остальные добавить
// {FORM INPUT} checkbox|shufflefile|yes Записывать файлы в случайном порядке
// {FORM INPUT} checkbox|addperenos|yes Добавлять дополнительные переносы строк до и после разделителя
// {FORM INPUT} text|razdeltxt|------------- Строка разделителя:
//*********************************************************************
if ($reg_c=='24') {include('include/filelist-cmd24.php');}
//*********************************************************************
// {COMMAND} 25 <b>Преобразует все файлы</b> из одной <b>кириллической</b> кодировки в другую (k - koi8-r, w - windows-1251, i - iso8859-5)
// {FORM INPUT} text|dir|data2 Каталог для сохранения:
// {FORM INPUT} text|kod1|w Исходная кодировка (k,w,i,a,d,m):
// {FORM INPUT} text|kod2|i Выходная кодировка (k,w,i,a,d,m):
//*********************************************************************
if ($reg_c=='25') {include('include/filelist-cmd25.php');}
//*********************************************************************
// {COMMAND} 26 <b>Переместить</b> файлы в архивы RAR для создания файла .cmd
// {FORM INPUT} text|dirname1|data Каталог c каталогами:
// {FORM INPUT} text|dirname2|data2 Каталог для сохранения архивов:
//*********************************************************************
if ($reg_c=='26') {include('include/filelist-cmd26.php');}
//*********************************************************************
// {COMMAND} 27 <b>Создать ссылки из файла DEPOSIT</b> сохраненные в файле files_list.txt
//*********************************************************************
if ($reg_c=='27') {include('include/filelist-cmd27.php');}
//*********************************************************************
// {COMMAND} 28 <b>Создать ссылки из файла DEPOSIT</b> сохраненные в файле files_list.txt и картинки из каталога
//*********************************************************************
if ($reg_c=='28') {include('include/filelist-cmd28.php');}
//*********************************************************************
// {COMMAND} 29 <b>Поиск дублированных вопросов</b>
// {FORM INPUT} text|file1|text1.txt Имя файла с вопросами:
// {FORM INPUT} text|file2|text2.txt Имя выходного файла с вопросами:
//*********************************************************************
if ($reg_c=='29') {include('include/filelist-cmd29.php');}
//*********************************************************************
// {COMMAND} 30 <b>Перенос файлов в разные каталоги</b>
// {FORM INPUT} text|dir1|data2 Имя каталога c файлами:
// {FORM INPUT} text|dir2|data3 Имя каталога с новыми каталогами:
// {FORM INPUT} text|num|3 Количество символов в названии файла:
//*********************************************************************
if ($reg_c=='30') {include('include/filelist-cmd30.php');}
//*********************************************************************
// {COMMAND} 31 <b>Вытащить HTML фрагмент</b>
// {FORM INPUT} text|dir1|data2 Имя каталога c файлами:
// {FORM INPUT} text|dir2|data3 Имя каталога с новыми каталогами:
// {FORM INPUT} text|title|Заголовок Заголовок файлов: 
//*********************************************************************
if ($reg_c=='31') {include('include/filelist-cmd31.php');}
//*********************************************************************
// {COMMAND} 32 <b>Показать ссылки</b> из текстового файла filelist.txt для сохранения в формате HTML
// {FORM INPUT} checkbox|tablerows|yes Сохранить в строках таблицы
//*********************************************************************
if ($reg_c=='32') {include('include/filelist-cmd32.php');}
//*********************************************************************
// {COMMAND} 33 <b>Поиск дубликатов файлов</b> и удаление дублированых файлов со сходным MD5
// {FORM INPUT} checkbox|delfiles|yes Удалять файлы
//*********************************************************************
if ($reg_c=='33') {include('include/filelist-cmd33.php');}
//*********************************************************************
// {COMMAND} 34 <b>Создание каталогов по номерам</b> и перенос туда файлов
// {FORM INPUT} text|numstart|1 Начальный номер каталога:
// {FORM INPUT} text|numend|2 Конечный номер каталога:
// {FORM INPUT} text|numzero|3 Количество символов в названии каталога (добавление нулей):
// {FORM INPUT} text|dir|data Каталог который содержит файлы:
//*********************************************************************
if ($reg_c=='34') {include('include/filelist-cmd34.php');}
//*********************************************************************
// {COMMAND} 35 <b>Преобразовать файлы</b> в формат BASE64 из списка файлов
// {FORM INPUT} text|valuename|data Имя переменной в которую пишем файлы:
// {FORM INPUT} text|fname|file.php Имя файла для сохранения данных:
//*********************************************************************
if ($reg_c=='35') {
    $filename_files = __DIR__ . '/filelist.txt';
    include('include/filelist-cmd35.php');
}
//*********************************************************************
// {COMMAND} 36 <b>Сохранить файлы по шаблону HTML</b> шаблон хранится в файле templ.html
// {FORM INPUT} text|dirname1|data Каталог с исходными файлами:
// {FORM INPUT} text|dirname2|data2 Каталог для сохранения данных:
//*********************************************************************
if ($reg_c=='36') {include('include/filelist-cmd36.php');}
//*********************************************************************
// {COMMAND} 37 <b>Извлекать из шаблона HTML</b> исходный текст
// {FORM INPUT} text|dirname1|data Каталог с исходными файлами:
// {FORM INPUT} text|dirname2|data2 Каталог для сохранения данных:
//*********************************************************************
if ($reg_c=='37') {include('include/filelist-cmd37.php');}
//*********************************************************************
// {COMMAND} 38 <b>Преобразовать формат TXT</b> убрать переносы строк которые начинаются с маленькой буквы
// {FORM INPUT} text|dirname1|data Каталог с исходными файлами:
// {FORM INPUT} text|dirname2|data2 Каталог для сохранения данных:
//*********************************************************************
if ($reg_c=='38') {include('include/filelist-cmd38.php');}
//*********************************************************************
// {COMMAND} 39 <b>Преобразовать формат TXT</b> добавить переносы строк которые начинаются со знака табуляции
// {FORM INPUT} text|dirname1|data Каталог с исходными файлами:
// {FORM INPUT} text|dirname2|data2 Каталог для сохранения данных:
//*********************************************************************
if ($reg_c=='39') {include('include/filelist-cmd39.php');}
//*********************************************************************
// {COMMAND} 40 <b>Сделать миниатюры 2 в 1</b> первая миниатюра сверху вторая снизу
// {FORM INPUT} text|dirname1|data Каталог с исходными каталогами:
// {FORM INPUT} text|dirname2|data2 Каталог для сохранения данных:
//*********************************************************************
if ($reg_c=='40') {include('include/filelist-cmd40.php');}
//*********************************************************************
// {COMMAND} 41 <b>Вывести в браузер каталог с каталогами</b> и сохранить в filelist.txt
// {FORM INPUT} text|dirname1|data Каталог с исходными каталогами:
//*********************************************************************
if ($reg_c=='41') {include('include/filelist-cmd41.php');}
//*********************************************************************
// {COMMAND} 42 Проверить ссылки <b>Depositfiles и Hotfile</b> и сохранить в filelinks.txt
// {FORM INPUT} text|dirname1|data Каталог с файлами depo.txt и hot.txt:
// {FORM INPUT} text|dirname2|data Каталог c файлами которые записаны на серверах:
// {FORM INPUT} text|dirname3|data Каталог c картинками:
//*********************************************************************
if ($reg_c=='42') {include('include/filelist-cmd42.php');}


//*********************************************************************
// {COMMAND} view Вывести <b>список файлов</b> из каталога
//*********************************************************************
if ($reg_c=='view') {include('include/filelist-cmdview.php');}
//*********************************************************************
// {COMMAND} move Перенести файлы в новый каталог
// {FORM INPUT} text|dir|data Каталог содержащий файлы:
// {FORM INPUT} text|dir2|data2 Каталог для нового расположения файлов:
// {FORM INPUT} text|ext|.txt Расширение файлов:
//*********************************************************************
if ($reg_c=='move') {include('include/filelist-cmdmove.php');}
//*********************************************************************
// {COMMAND} delete <b>Удалить файлы</b> в каталоге 
// {FORM INPUT} text|dir|data Каталог:
// {FORM INPUT} text|ext|.txt Расширение файлов:
//*********************************************************************
if ($reg_c=='delete') {include('include/filelist-cmddelete.php');}
//*********************************************************************
// {COMMAND} img <b>Переименовать</b> все файлы с изображениями из подкаталогов в каталоге
// {FORM INPUT} text|dir|data Каталог содержащий каталоги:
//*********************************************************************
if ($reg_c=='img') {include('include/filelist-cmdimg.php');}
//*********************************************************************
// {COMMAND} dz Перекодировать все файли из формата DZ
// {FORM INPUT} text|dir|data Каталог содержащий файлы с расширением dz:
//*********************************************************************
if ($reg_c=='dz') {include('include/filelist-cmddz.php');}

echo $strHtmStartMain.$page_body_text.$strHtmEndMain;
?>