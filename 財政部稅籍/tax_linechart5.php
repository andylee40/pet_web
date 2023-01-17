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
$sql="SELECT (case when 類別 like '寵物批發' then '寵物批發'
         	 when 類別 like '寵物服裝及其飾品配件批發' then '服裝及其飾品配件批發'
        	when 類別 like '寵物殯葬' then '寵物生命紀念'
        	when 類別 like '寵物照顧及訓練' then '照顧及訓練'
        	when 類別 like '寵物用品批發' then '用品批發'
        	when 類別 like '寵物用品零售' then '用品零售'
        	when 類別 like '寵物零售' then '寵物零售'
        	when 類別 like '寵物飼品零售' then '飼品零售'
        end) 名稱,
sum(if(縣市 IN('臺北市','新北市','基隆市','新竹市','桃園市','新竹縣','宜蘭縣'),1,0)) 北,
sum(if(縣市 IN('臺中市','苗栗縣','彰化縣','南投縣','雲林縣'),1,0)) 中,
sum(if(縣市 IN('高雄市','臺南市','嘉義市','嘉義縣','屏東縣','澎湖縣'),1,0)) 南,
sum(if(縣市 IN('花蓮縣','臺東縣'),1,0)) 東
from tax_sep
where 移除時間 = '' 
GROUP BY 類別";
$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {
    $row['北']=intval($row['北']);
    $row['中']=intval($row['中']);
    $row['南']=intval($row['南']);
    $row['東']=intval($row['東']);
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
  am4core.color("#E12E4B"),
  am4core.color("#F9E54E"),
  am4core.color("#F8981D"),
  am4core.color("#5BBDC8"),
];


var title = chart.titles.create();
title.text = "全台寵物產業各區域分佈狀況";
title.fontSize = 25;
title.align="left";
title.marginBottom = 50;


var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "名稱";
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
series1.dataFields.valueY = "北";
series1.dataFields.valueYShow = "totalPercent";
series1.dataFields.categoryX = "名稱";
series1.name = "北";
series1.tooltipText = "{name}: [bold]{valueY}[/]";
series1.stacked = true;
            
var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "中";
series2.dataFields.valueYShow = "totalPercent";
series2.dataFields.categoryX = "名稱";
series2.name = "中";
series2.tooltipText = "{name}: [bold]{valueY}[/]";
series2.stacked = true;
            
var series3 = chart.series.push(new am4charts.ColumnSeries());
series3.dataFields.valueY = "南";
series3.dataFields.valueYShow = "totalPercent";
series3.dataFields.categoryX = "名稱";
series3.name = "南";
series3.tooltipText = "{name}: [bold]{valueY}[/]";
series3.stacked = true;
            
var series4 = chart.series.push(new am4charts.ColumnSeries());
series4.dataFields.valueY = "東";
series4.dataFields.valueYShow = "totalPercent";
series4.dataFields.categoryX = "名稱";
series4.name = "東";
series4.tooltipText = "{name}: [bold]{valueY}[/]";
series4.stacked = true;


chart.cursor = new am4charts.XYCursor();
</script>

 

           