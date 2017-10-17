Справка по API функциям

В API запрос передаются
method - имя метода
args - массив аргументов, передаваемых в метод


1.
Список коммитов в репозитории
method - commits_list
args[id] - id - репозитория
пример запроса:
http://192.168.1.85/reposit-catalog/api/get.php?method=commits_lists&args[id]=123
