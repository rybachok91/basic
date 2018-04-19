<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<?php
$this->registerJsFile('https://www.gstatic.com/charts/loader.js');
$this->registerJs(
      "google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Название');
      data.addColumn('number', 'Количество');
      data.addRows([
        ['Mushrooms', 4],
        ['Onions', 1],
        ['Olives', 1], 
        ['Zucchini', 1],
        ['Pepperoni', 2]
      ]);

      // Set chart options
      var options = {'title':'Сколько пиццы съели',
                     'width':500,
                     'height':300};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }"
);
?>


<div class="site-index">
    <div class="jumbotron">
        <!--Div that will hold the pie chart-->
        <div id="chart_div" style="width:400; height:300"></div>
    </div>
</div>
