<?php
$servername = "localhost";
$username = "root";
$password = "mypassword";
$dbname = "pet";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
    {
      die("Connection failed: " . $conn->connect_error);
    }
$conn -> set_charset("utf8");
//$sql="SELECT * FROM tax_country";
$month=date("m");
$sql="SELECT 產品種類,
sum(if(新增時間 is not null,1,0)) 增加,
sum(if(移除時間 is not null,1,0)) 減少
from pet_food2
where 產品種類 !='' and (MONTH(新增時間)=$month or MONTH(移除時間)=$month)
GROUP BY 產品種類";
$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {  
    $row['增加']=intval($row['增加']);
    $row['減少']=intval($row['減少']);
    $array[] = $row;
    };
$data=json_encode($array, JSON_UNESCAPED_UNICODE);
//print_r($data);
?>
<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/kelly.js"></script>
<div id="chartdiv"></div>
<script>
am4core.useTheme(am4themes_animated);
//am4core.useTheme(am4themes_kelly);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

var title = chart.titles.create();
title.text = "<?=$month;?>月寵物食品市場異動";
title.fontSize = 25;
title.align="left";
title.marginBottom = 50;
// Add data
chart.data = <?=$data;?>;

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "產品種類";
categoryAxis.title.text = "類別";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;

var label = categoryAxis.renderer.labels.template;
label.wrap = true;
label.maxWidth = 140;


var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "變動數量";

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "增加";
series.dataFields.categoryX = "產品種類";
series.name = "增加";
series.tooltipText = "{name}: [bold]{valueY}[/]";
// This has no effect
// series.stacked = true;

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "減少";
series2.dataFields.categoryX = "產品種類";
series2.name = "減少";
series2.tooltipText = "{name}: [bold]{valueY}[/]";
// Do not try to stack on top of previous series
// series2.stacked = true;



// Add cursor
chart.cursor = new am4charts.XYCursor();

// Add legend
chart.legend = new am4charts.Legend();
chart.legend.marginTop = "20";
</script>
            