<?php
$servername = "localhost";
$username = "帳號";
$password = "密碼";
$dbname = "pet";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
    {
      die("Connection failed: " . $conn->connect_error);
    }
$conn -> set_charset("utf8");


$sql="SELECT 特定寵物種類,
sum(if(經營業務項目 LIKE '%繁殖%',1,0)) 繁殖,
sum(if(經營業務項目 LIKE '%買賣%',1,0)) 買賣,
sum(if(經營業務項目 LIKE '%寄養%',1,0)) 寄養
from pet_manage2
where 特定寵物種類 !=".'""'." 
GROUP BY 特定寵物種類
order by 繁殖 desc";
$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {  
    $row['繁殖']=intval($row['繁殖']);
    $row['買賣']=intval($row['買賣']);
    $row['寄養']=intval($row['寄養']);
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
am4core.useTheme(am4themes_kelly);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

var title = chart.titles.create();
title.text = "全台寵物服務種類分佈";
title.fontSize = 25;
title.align="left";
title.marginBottom = 50;
// Add data
chart.data = <?=$data;?>;

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "特定寵物種類";
categoryAxis.title.text = "";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;

var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "業者數量";

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "繁殖";
series.dataFields.categoryX = "特定寵物種類";
series.name = "繁殖";
series.tooltipText = "{name}: [bold]{valueY}[/]";
// This has no effect
// series.stacked = true;

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "買賣";
series2.dataFields.categoryX = "特定寵物種類";
series2.name = "買賣";
series2.tooltipText = "{name}: [bold]{valueY}[/]";
// Do not try to stack on top of previous series
// series2.stacked = true;

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "寄養";
series2.dataFields.categoryX = "特定寵物種類";
series2.name = "寄養";
series2.tooltipText = "{name}: [bold]{valueY}[/]";
// Do not try to stack on top of previous series
// series2.stacked = true;


// Add cursor
chart.cursor = new am4charts.XYCursor();

// Add legend
chart.legend = new am4charts.Legend();
chart.legend.marginTop = "20";
</script>
            