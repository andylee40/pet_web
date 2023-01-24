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
$now = new DateTime();
$time=$now->format('Y-m-d'); 
//$sql="SELECT * FROM tax_country";
$time="'".$time."'";


$sql=
"
SELECT if(max(寵物照顧及訓練) is null,'照顧及訓練','照顧及訓練') as  name,sum(寵物照顧及訓練) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union all
SELECT if(max(寵物用品零售) is null,'用品零售','用品零售') as  name,sum(寵物用品零售) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union all
SELECT if(max(寵物零售) is null,'寵物零售','寵物零售') as  name,sum(寵物零售) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union ALL
SELECT if(max(寵物飼品零售) is null,'飼品零售','飼品零售') as  name,sum(寵物飼品零售) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union all
SELECT if(max(寵物用品批發) is null,'用品批發','用品批發') as  name,sum(寵物用品批發) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union ALL
SELECT if(max(寵物批發) is null,'寵物批發','寵物批發') as  name,sum(寵物批發) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union all
SELECT if(max(寵物服裝及其飾品配件批發) is null,'服裝及其飾品配件批發','服裝及其飾品配件批發') as  name,sum(寵物服裝及其飾品配件批發) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
union ALL
SELECT if(max(寵物殯葬) is null,'寵物生命紀念','寵物生命紀念') as  name,sum(寵物殯葬) as value FROM `tax` where 日期 = (select max(日期) from `tax`)
";

//echo $sql;
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) 
    {  
    $row['value']=intval($row['value']);
    $array[] = $row;
    };
$data=json_encode($array, JSON_UNESCAPED_UNICODE);
//print_r($data);
//exit();
$conn->close();

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
            title.text = "全台寵物產業現況";
             
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
            label.text = "共{values.value.sum}家";
            label.horizontalCenter = "middle";
            label.verticalCenter = "middle";
            label.fontSize = 40;
        </script>

            