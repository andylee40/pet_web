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


$sql="SELECT 產品種類,
sum(if(產品來源='分裝',1,0)) 分裝,
sum(if(產品來源='輸入',1,0)) 輸入,
sum(if(產品來源='製造、加工',1,0)) 製造、加工,
sum(if(產品來源='委託代工廠製造',1,0)) 委託代工廠製造
from pet_food3
where 產品種類 !=".'""'." and 業者名稱 !=".'""'."  and 狀態 !=".'"下架"'."
GROUP BY 產品種類";
$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {
    $row['分裝']=intval($row['分裝']);
    $row['輸入']=intval($row['輸入']);
    $row['製造、加工']=intval($row['製造、加工']);
    $row['委託代工廠製造']=intval($row['委託代工廠製造']);
    $array[] = $row;
    };
$data=json_encode($array, JSON_UNESCAPED_UNICODE);
//print_r($data);
?>

<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>
<!--script src="//cdn.amcharts.com/lib/4/themes/kelly.js"></script-->
<script src="//www.amcharts.com/lib/4/themes/material.js"></script>
<div id="chartdiv"></div>
<script>
am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_material);

//am4core.useTheme(am4themes_kelly);

var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.data = <?=$data;?>;
chart.legend = new am4charts.Legend();

chart.colors.list = [
  am4core.color("#642A02"),
  am4core.color("#EEB3A3"),
  am4core.color("#F3553C"),
  am4core.color("#FFB53C"),
];



var title = chart.titles.create();
title.text = "全台寵物食品產品來源分佈";
title.fontSize = 25;
title.align="left";
title.marginBottom = 50;


var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "產品種類";
categoryAxis.title.text = "";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;

var label = categoryAxis.renderer.labels.template;
label.wrap = true;
label.maxWidth = 100;


var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "數量佔比(%)";
valueAxis.calculateTotals = true;
valueAxis.min = 0;
valueAxis.max = 100;
valueAxis.strictMinMax = true;
valueAxis.renderer.labels.template.adapter.add("text", function(text) {return text + "%";});

var series1 = chart.series.push(new am4charts.ColumnSeries());
series1.dataFields.valueY = "分裝";
series1.dataFields.valueYShow = "totalPercent";
series1.dataFields.categoryX = "產品種類";
series1.name = "分裝";
series1.tooltipText = "{name}: [bold]{valueY}[/]";
series1.stacked = true;
            
var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "輸入";
series2.dataFields.valueYShow = "totalPercent";
series2.dataFields.categoryX = "產品種類";
series2.name = "輸入";
series2.tooltipText = "{name}: [bold]{valueY}[/]";
series2.stacked = true;
            
var series3 = chart.series.push(new am4charts.ColumnSeries());
series3.dataFields.valueY = "製造、加工";
series3.dataFields.valueYShow = "totalPercent";
series3.dataFields.categoryX = "產品種類";
series3.name = "製造、加工";
series3.tooltipText = "{name}: [bold]{valueY}[/]";
series3.stacked = true;
            
var series4 = chart.series.push(new am4charts.ColumnSeries());
series4.dataFields.valueY = "委託代工廠製造";
series4.dataFields.valueYShow = "totalPercent";
series4.dataFields.categoryX = "產品種類";
series4.name = "委託代工廠製造";
series4.tooltipText = "{name}: [bold]{valueY}[/]";
series4.stacked = true;

          

chart.cursor = new am4charts.XYCursor();
</script>

 

           