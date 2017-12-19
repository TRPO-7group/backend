
<?php if ($period == 30){?>
    <h3 class="detail-statistic">Статистика за последний месяц <span>[+]</span></h3>
<?php } else if ($period ==  7){?>
    <h3 class="detail-statistic">Статистика за последнюю неделю <span>[+]</span></h3>
<?php } else if ($period== 90){?>
    <h3 class="detail-statistic">Статистика за последние три месяца <span>[+]</span></h3>
<?php }?>


    <div class="detail-commits-all">
        <div>
            <?php if ($period == 30){?>
                Коммитов за последний месяц:  <?php echo count($arResult["commits_list"]);?>
            <?php } else if ($period ==  7){?>
                Коммитов за последнюю неделю:  <?php echo count($arResult["commits_list"]);?>
            <?php } else if ($period== 90){?>
                Коммитов за последние три месяца:  <?php echo count($arResult["commits_list"]);?>
            <?php }?>

        </div>
        <table border="1" cellspacing="0" cellpadding="5px">
            <tr>
                <th colspan="3">Статистика по файлам</th>
            </tr>
            <tr>
                <th>Добавлено</th>
                <th>Изменено</th>
                <th>Удалено</th>
            </tr>
            <tr>
                <td><?php echo $arResult["all_files_add"]?></td>
                <td><?php echo $arResult["all_files_modified"]?></td>
                <td><?php echo $arResult["all_files_delete"]?></td>
            </tr>
        </table>

        <table border="1" cellspacing="0" cellpadding="5px">
            <tr>
                <th colspan="3">Статистика по строкам</th>
            </tr>
            <tr>
                <th>Добавлено</th>
                <th>Удалено</th>
            </tr>
            <tr>
                <td ><?php echo $arResult["all_lines_add"]?></td>
                <td><?php echo $arResult["all_lines_delete"]?></td>
            </tr>
        </table>

    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart<?php echo $arResult["repository_id"] . "_" . $period?>);

        function drawChartCommits<?php echo $arResult["repository_id"] . "_" . $period?>() {
            var data = google.visualization.arrayToDataTable([
                ['Дата', 'Количество коммитов'],
                <?php echo $arResult["js_commits"]?>
            ]);

            var options = {
                title: 'Количество коммитов',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart_files<?php echo $arResult["repository_id"] . "_" . $period?>'));
            chart.draw(data, options);
        }


        function drawChartFiles<?php echo $arResult["repository_id"] . "_" . $period?>() {
            var data = google.visualization.arrayToDataTable([
                ['День', 'Добавлено', 'Изменено', 'Удалено'],
                <?php echo $arResult["js_files"]?>
            ]);

            var options = {
                title: 'Файлы',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart_commit<?php echo $arResult["repository_id"] . "_" . $period?>'));
            chart.draw(data, options);
        }


        function drawChartLines<?php echo $arResult["repository_id"] . "_" . $period?>() {
            var data = google.visualization.arrayToDataTable([
                ['День', 'Добавлено', 'Удалено'],
                <?php echo $arResult["js_lines"]?>
            ]);

            var options = {
                title: 'Строки',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart_lines<?php echo $arResult["repository_id"] . "_" . $period?>'));
            chart.draw(data, options);
        }

        function drawChart<?php echo $arResult["repository_id"] . "_" . $period?>() {
            drawChartCommits<?php echo $arResult["repository_id"] . "_" . $period?>();
            drawChartFiles<?php echo $arResult["repository_id"] . "_" . $period?>();
            drawChartLines<?php echo $arResult["repository_id"] . "_" . $period?>();

        }
    </script>
    <div id="curve_chart_commit<?php echo $arResult["repository_id"] . "_" . $period?>" style="width: 370px; height: 200px; display: inline-block"></div>
    <div id="curve_chart_files<?php echo $arResult["repository_id"] . "_" . $period?>" style="width: 370px; height: 200px; display: inline-block"></div>
    <div id="curve_chart_lines<?php echo $arResult["repository_id"] . "_" . $period?>" style="width: 370px; height: 200px; display: inline-block"></div>

