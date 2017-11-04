<div class="title">
    <h1><?php echo $arResult["repository_name"];?></h1>
</div>
<div class="detail-container">
    <div class="detail-description">
        <?php echo $arResult['repository_description'];?>
    </div>
    <h3 class="detail-statistic">Статистика за последний месяц <span>[+]</span></h3>
    <div class="detail-commits-all">
        <div>
        Коммитов за последний месяц: <?php echo count($arResult["commits_list"]);?>
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
                <td><div class="popup-title" data-title="<?php echo $arResult["files_add_for_popup"]?>"><?php echo $arResult["all_files_add"]?></div></td>
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
        google.charts.setOnLoadCallback(drawChart);

        function drawChartCommits() {
            var data = google.visualization.arrayToDataTable([
                ['Дата', 'Количество коммитов'],
                <?php echo $arResult["js_commits"]?>
            ]);

            var options = {
                title: 'Количество коммитов',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart_files'));
            chart.draw(data, options);
        }


        function drawChartFiles() {
            var data = google.visualization.arrayToDataTable([
                ['День', 'Добавлено', 'Изменено', 'Удалено'],
                <?php echo $arResult["js_files"]?>
            ]);

            var options = {
                title: 'Файлы',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart_commit'));
            chart.draw(data, options);
        }


        function drawChartLines() {
            var data = google.visualization.arrayToDataTable([
                ['День', 'Добавлено', 'Удалено'],
                <?php echo $arResult["js_lines"]?>
            ]);

            var options = {
                title: 'Строки',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart_lines'));
            chart.draw(data, options);
        }

        function drawChart() {
            drawChartCommits();
            drawChartFiles();
            drawChartLines();

        }
    </script>
    <div id="curve_chart_commit" style="width: 500px; height: 300px; display: inline-block"></div>
    <div id="curve_chart_files" style="width: 500px; height: 300px; display: inline-block"></div>
    <div id="curve_chart_lines" style="width: 500px; height: 300px; display: inline-block"></div>
</div>