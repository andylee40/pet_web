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
//$sql="SELECT 產品種類 as name,count(*) as value FROM pet_food  where 產品種類 !="" group by 產品種類";
//$sql="SELECT 產品種類 as name,count(*) as value FROM pet_food2  where 產品種類 !=".'""'." and 移除時間 is null group by 產品種類";
$sql="SELECT 產品種類 as name,count(*) as value FROM pet_food3  where 產品種類 !=".'""'."and 業者名稱 !=".'""'." and 狀態 !=".'"下架"'." group by 產品種類";
$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {  
    $row['value']=intval($row['value']);
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

           var chart = am4core.create("chartdiv", am4charts.PieChart);
            

            chart.data = <?=$data;?>;
            
            var title = chart.titles.create();
            title.text = "全台寵物食品申報情況";
            title.fontSize = 25;
            title.marginBottom = 0;
            title.marginTop = 15;
            title.align="left";

            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "value";
            pieSeries.dataFields.category = "name";
            pieSeries.labels.template.disabled = true;
            pieSeries.ticks.template.disabled = true;
            
            pieSeries.colors.list = [
              am4core.color("#845EC2"),
              am4core.color("#D65DB1"),
              am4core.color("#FF6F91"),
              am4core.color("#FF9671"),
              am4core.color("#FFC75F"),
              am4core.color("#F9F871"),
            ];
            chart.legend = new am4charts.Legend();
            chart.legend.position = "right";

            chart.innerRadius = am4core.percent(60);

            var label = pieSeries.createChild(am4core.Label);
            label.text = "{values.value.sum}筆";
            label.horizontalCenter = "middle";
            label.verticalCenter = "middle";
            label.fontSize = 40;
        </script>
            