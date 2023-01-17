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
/*$sql="SELECT if(count(*) is null,'寄養','寄養') as  name,count(*) as value FROM `pet_manage` WHERE 經營業務項目 LIKE '%寄養%' and 移除時間 is null
union all
SELECT if(count(*) is null,'繁殖','繁殖') as  name,count(*) as value FROM `pet_manage` WHERE 經營業務項目 LIKE '%繁殖%' and 移除時間 is null
union all
SELECT if(count(*) is null,'買賣','買賣') as  name,count(*) as value FROM `pet_manage` WHERE 經營業務項目 LIKE '%買賣%' and 移除時間 is null";*/

$sql="SELECT if(count(*) is null,'寄養','寄養') as  name,count(*) as value FROM `pet_manage2` WHERE 經營業務項目 LIKE '%寄養%' 
union all
SELECT if(count(*) is null,'繁殖','繁殖') as  name,count(*) as value FROM `pet_manage2` WHERE 經營業務項目 LIKE '%繁殖%' 
union all
SELECT if(count(*) is null,'買賣','買賣') as  name,count(*) as value FROM `pet_manage2` WHERE 經營業務項目 LIKE '%買賣%'";
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
            title.text = "全台寵物服務現況";
            title.fontSize = 25;
            title.align="left";
            title.marginBottom = 50;
            title.marginTop = 15;

            chart.innerRadius = am4core.percent(40);
            chart.depth = 120;

            chart.legend = new am4charts.Legend();
            chart.legend.position = "bottom";
            chart.legend.marginTop = "50";


            var series = chart.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "value";
            series.dataFields.depthValue = "value";
            series.dataFields.category = "name";
            series.slices.template.cornerRadius = 10;

            series.colors.list = [
              am4core.color("#028C6A"),
              am4core.color("#EECB27"),
              am4core.color("#E13239"),
            ];
        </script>
            