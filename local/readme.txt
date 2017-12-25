Справка по API функциям

В API запрос передаются
method - имя метода
args - массив аргументов, передаваемых в метод


1.
Список коммитов в репозитории
method - commits_list
args[id] - id - репозитория
пример запроса:
/reposit-catalog/api/get.php?method=commits_lists&args[id]=123


2.
Список репозиториев
method - rep_list
args[group] - группировать по дисциплинам (Опционально Y - если нужно группировать) (Лучше не использовать)
args[type] - тип репозитория (опционально, по умалчанию оба) значения: edu - учебные, individual - индивидуальные
Если тип не задан или individual, то дополнительно необходимо передать один из следующих параметров
    args[user_id] - id пользователя в система
    args[user_email] - почта пользователя

пример запроса:
http://reposit-catalog.ru/reposit-catalog/api/get.php?method=rep_list&args[user_email]=aa@mail.ru

3.
Список измененных файлов
method - commit_info_files_list
args[id] - id репозитория
возвращает коммиты и измененные файлы по ним (A - файл добавлен, M - файл изменен, D - файл удален)
/reposit-catalog/api/get.php?method=commit_info_files_list&args[id]=2

4.
Список файлов с количеством добавленных и удаленных строк
method - commit_info_lines_list
args[id] - id репозитория
По каждому коммиту за прошедший месяц возвращает список измененных файлов и количество добавленных и удаленных строк в каждом
/reposit-catalog/api/get.php?method=commit_info_lines_list&args[id]=1

5. Информация о репозитории
method - rep_info
args[id] - id репозитория
возвращает информацию о репощитории
/reposit-catalog/api/get.php?method=rep_info&args[id]=3

6. Детальная информация о репозитории
method - detail_rep_info
args[id] - id репозитория
args[period] - период в днях
args[mask] - маска для файлов (опционально. Расширения должны быть заданычерез запятую и пробел)

Возвращает детальную информацию о репозитории за определенный период, по файлам с определенной маской

http://192.168.1.85/reposit-catalog/api/get.php?method=detail_rep_info&args[id]=1&args[period]=30&args[mask]=sass,%20css

7. Получить список масок
method - mask_list

http://192.168.1.85/reposit-catalog/api/get.php?method=mask_list