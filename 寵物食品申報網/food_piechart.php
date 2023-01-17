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
$sql="SELECT 產品種類 as name,count(*) as value FROM pet_food2  where 產品種類 !=".'""'." and 移除時間 is null group by 產品種類";
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

            var chart = am4core.create("chartdiv", am4charts.PieChart3D);
            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

            chart.data = <?=$data;?>;
            
            var title = chart.titles.create();
            title.text = "全台寵物食品市場占比";
            title.fontSize = 25;
            title.marginTop = 15;
            title.align="left";
            title.marginBottom = 50;


            chart.innerRadius = am4core.percent(40);
            chart.depth = 120;

            chart.legend = new am4charts.Legend();
            chart.legend.position = "bottom";
            chart.legend.marginTop = "40";

            var series = chart.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "value";
            series.dataFields.depthValue = "value";
            series.dataFields.category = "name";
            series.slices.template.cornerRadius = 5;
            series.colors.step = 3;
        </script>
            