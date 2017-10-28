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
args[group] - группировать по дисциплинам (Опционально Y - если нужно группировать)
args[type] - тип репозитория (опционально, по умалчанию оба) значения: edu - учебные, individual - индивидуальные
пример запроса:
/reposit-catalog/api/get.php?method=rep_list&args[group]=Y

3.
Список измененных файлов
method - commit_info_files_list
args[id] - id репозитория
возвращает коммиты и измененные файлы по ним (A - файл добавлен, M - файл изменен, D - файл удален)
/reposit-catalog/api/get.php?method=commit_info_files_list&args[id]=2