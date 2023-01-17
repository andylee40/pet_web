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
$sql="select 日期,
寵物批發,
寵物服裝及其飾品配件批發 as 服裝及其飾品配件批發,
寵物殯葬,
寵物照顧及訓練 as 照顧及訓練,
寵物用品批發 as 用品批發,
寵物用品零售 as 用品零售,
寵物零售,
寵物飼品零售 as 飼品零售 
FROM tax where 日期  in(SELECT max(日期) FROM tax group by  MONTH(日期))";
$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {
    $row['寵物批發']=intval($row['寵物批發']);
    $row['飼品零售']=intval($row['飼品零售']);
    $row['寵物殯葬']=intval($row['寵物殯葬']);
    $row['服裝及其飾品配件批發']=intval($row['服裝及其飾品配件批發']);
    $row['用品批發']=intval($row['用品批發']);
    $row['照顧及訓練']=intval($row['照顧及訓練']);
    $row['用品零售']=intval($row['用品零售']);
    $row['寵物零售']=intval($row['寵物零售']);
    $array[] = $row;
    };
$data=json_encode($array, JSON_UNESCAPED_UNICODE);
//print_r($data);
?>
    
<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>
<div id="chartdiv"></div>
<script>
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.data = <?=$data;?>;
chart.dateFormatter.dateFormat = "yyyy-MM";


var title = chart.titles.create();
title.text = "全台寵物產業每月異動狀況";
title.fontSize = 25;
title.align="left";
title.marginBottom = 50;

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.minGridDistance = 30;
dateAxis.baseInterval = {
  "timeUnit": "month",
  "count": 1
};
dateAxis.dateFormats.setKey("month", "yyyy-MM");



var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.LineSeries());
  series.dataFields.valueY = field;
  series.dataFields.dateX = "日期";
  series.name = name;
  series.tooltipText = "{dateX}: [b]{valueY}[/]";
  series.strokeWidth = 2;
  
  series.smoothing = "monotoneX";
  
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.stroke = am4core.color("#fff");
  bullet.circle.strokeWidth = 2;
  
  return series;
}

createSeries("寵物批發", "寵物批發");
createSeries("服裝及其飾品配件批發", "服裝及其飾品配件批發").hidden = true;
createSeries("寵物殯葬", "寵物殯葬").hidden = true;
createSeries("照顧及訓練", "照顧及訓練").hidden = true;
createSeries("用品批發", "用品批發").hidden = true;
createSeries("用品零售", "用品零售").hidden = true;
createSeries("寵物零售", "寵物零售").hidden = true;
createSeries("飼品零售", "飼品零售").hidden = true;

chart.legend = new am4charts.Legend();
chart.legend.marginTop = "20";
chart.cursor = new am4charts.XYCursor();
</script>