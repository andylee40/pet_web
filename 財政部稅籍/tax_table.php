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

$now = new DateTime();
$time=$now->format('Y/m/d'); 
$lastmmonth=date('Y/m/01',strtotime("-1 month",time()));

//$sql="SELECT * FROM tax_country";
$year=date("y");
$month=date("m");
$lastmonth=intval(date("m"))-1;
/*$sql="SELECT 名稱,
sum(if(新增時間 is not null,1,0)) 增加,
sum(if(移除時間 is not null,1,0)) 減少,
sum(if(新增時間 is not null,1,0))-sum(if(移除時間 is not null,1,0)) 漲跌
from tax_day
where MONTH(新增時間)=$month or MONTH(移除時間)=$month
GROUP BY 名稱";*/
/*$sql="SELECT (case when 名稱 like '寵物批發' then '寵物批發'
         	 when 名稱 like '寵物服裝及其飾品配件批發' then '服裝及其飾品配件批發'
        	when 名稱 like '寵物殯葬' then '寵物殯葬'
        	when 名稱 like '寵物照顧及訓練' then '照顧及訓練'
        	when 名稱 like '寵物用品批發' then '用品批發'
        	when 名稱 like '寵物用品零售' then '用品零售'
        	when 名稱 like '寵物零售' then '寵物零售'
        	when 名稱 like '寵物飼品零售' then '飼品零售'
        end) 名稱,
sum(if(新增時間 is not null and MONTH(新增時間)=$month,1,0)) 增加,
sum(if(移除時間 is not null and MONTH(移除時間)=$month,1,0)) 減少,
sum(if(新增時間 is not null and MONTH(新增時間)=$month,1,0))-sum(if(移除時間 is not null and MONTH(移除時間)=$month,1,0)) 漲跌,
(sum(if(新增時間 is not null and MONTH(新增時間)=$month,1,0))-sum(if(新增時間 is not null and MONTH(新增時間)=$lastmonth,1,0)))/sum(if(新增時間 is not null and MONTH(新增時間)=$lastmonth,1,0))*100  增加百分比,
(sum(if(移除時間 is not null and MONTH(移除時間)=$month,1,0))-sum(if(移除時間 is not null and MONTH(移除時間)=$lastmonth,1,0)))/sum(if(移除時間 is not null and MONTH(移除時間)=$lastmonth,1,0))*100  減少百分比
from tax_day
GROUP BY 名稱";*/
$sql="SELECT (case when 類別 like '寵物批發' then '寵物批發'
         	 when 類別 like '寵物服裝及其飾品配件批發' then '服裝及其飾品配件批發'
        	when 類別 like '寵物殯葬' then '寵物殯葬'
        	when 類別 like '寵物照顧及訓練' then '照顧及訓練'
        	when 類別 like '寵物用品批發' then '用品批發'
        	when 類別 like '寵物用品零售' then '用品零售'
        	when 類別 like '寵物零售' then '寵物零售'
        	when 類別 like '寵物飼品零售' then '飼品零售'
        end) 名稱,
sum(if(新增時間 is not null and MONTH(新增時間)=$month,1,0)) 增加,
sum(if(移除時間 is not null and MONTH(移除時間)=$month,1,0)) 減少,
sum(if(新增時間 is not null and MONTH(新增時間)=$month,1,0))-sum(if(移除時間 is not null and MONTH(移除時間)=$month,1,0)) 漲跌,
concat(round((sum(if(新增時間 is not null and MONTH(新增時間)=$month,1,0))-sum(if(新增時間 is not null and MONTH(新增時間)=$lastmonth,1,0)))/sum(if(新增時間 is not null and MONTH(新增時間)=$lastmonth,1,0))*100,0),'%')  增加百分比,
concat(round((sum(if(移除時間 is not null and MONTH(移除時間)=$month,1,0))-sum(if(移除時間 is not null and MONTH(移除時間)=$lastmonth,1,0)))/sum(if(移除時間 is not null and MONTH(移除時間)=$lastmonth,1,0))*100,0),'%')  減少百分比
from tax_different
GROUP BY 類別";

//echo $sql;

$result = mysqli_query($conn, $sql);
$conn->close();
while($row = mysqli_fetch_assoc($result)) 
    {  
    $row['增加']=intval($row['增加']);
    $row['減少']=intval($row['減少']);
    #$row['增加百分比']=intval($row['增加百分比']);
    #$row['減少百分比']=intval($row['減少百分比']);
    $array[] = $row;
    };
$data=json_encode($array, JSON_UNESCAPED_UNICODE);
//print_r($data);
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<style>
    #chartdata{
              
              max-height: 500px;
              overflow: auto;
            }
    #chartdata table {
                      height:80px;
                      width: 90%;
                     }
    #title{
        font-size:25px;
        margin:20px 0 10px 0;
        }
    #DataTables_Table_0_length,#DataTables_Table_0_filter,#DataTables_Table_0_info,#DataTables_Table_0_paginate{display:none;}
    tbody .sorting_1{text-align:left;
             background-image: url(../image/banner4.jpg);
             background-position: center 10%;
             background-size:cover;
            }
    tbody td{text-align:center;
             background-image: url(../image/banner4.jpg);
             background-position: center 10%;
             background-size:cover;
            }
    .note{font-size:12px;
         text-align:right;}
</style>
<div id="title">全台寵物產業近期異動狀況</div>
<div style="margin-top:20px;" id="chartdata"></div>
<script>
// Themes begin
am4core.useTheme(am4themes_animated);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);
 
// Add chart's data into a table
chart.data = <?=$data;?>;
chart.events.on("datavalidated", function(ev) {
  chart.exporting.getHTML("html", {
    addColumnNames: true
  }, false).then(function(html) {
    var div = document.getElementById("chartdata");
    div.innerHTML = html;
    $("#chartdata table").DataTable();
  });
});
</script>
<div class="note" style="margin-top:0px;">資料採用時間段 : <?=$lastmmonth;?>  ~  <?=$time;?></div>
<!--div class="note" >增加 : 當月份增加筆數 ;<span> 增加百分比 : (當月份增加筆數-上月份增加筆數) /上月份增加筆數</span></div>
<div class="note">減少 : 當月份減少筆數 ;<span> 減少百分比 : (當月份減少筆數-上月份減少筆數) /上月份減少筆數</span></div-->
