Справка по API функциям

В API запрос передаются
method - имя метода
args - массив аргументов, передаваемых в метод


1.
Инфа о пользователе
method - user_info
args[login] - имя пользователя
пример запроса:
http://192.168.1.85/reposit-catalog/api/get.php?method=user_info&args[login]=alexeykotelevskiy

2.
Возвращает список коммитов репозитория (сейчас возвращает 30 последних коммитов, скоро допилю, как метод events_list)
method - commits_list
args[login] - имя пользователя
args[repository_name] - имя репозитория
пример запроса:
http://192.168.1.85/reposit-catalog/api/get.php?method=commits_list&args[login]=alexeykotelevskiy&args[repository_name]=reposit-catalog

3.
Возвращает инфу по конкретному коммиту
method - commit_info
args[login] - имя пользователя
args[repository_name] - имя репозитория
args[sha] - хэш коммита
Пример запроса:
http://192.168.1.85/reposit-catalog/api/get.php?method=commit_info&args[login]=alexeykotelevskiy&args[repository_name]=reposit-catalog&args[sha]=7a19f08a257980b7d22c546f84ccdcaafc1b6be3

4.
Список события пользователя
method - events_list
args[login] - имя пользователя
args[limit] - количество возвращаемых событий (опционально)
args[id_from] - от какой новости начинать вывод (опционально)
args[compare] - при заданном id_from, имеет два значения old и new. при old запрос вернет события, произошедшие раньше события id_from. При new вернет события, которые наступили позже события id_from(опционально)
Пример запроса:
http://192.168.1.85/reposit-catalog/api/get.php?method=events_list&args[login]=alexeykotelevskiy&args[limit]=5&args[id_from]=6629502381

