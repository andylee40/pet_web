<?php
//連線資料庫
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

//更新時間1:財政部稅籍
$updateday="select max(日期) as day from `tax`";
$resultday = mysqli_query($conn, $updateday);
while($rowday = mysqli_fetch_assoc($resultday)) 
    {  
    $arrayday[] = $rowday;
    };
$check=$arrayday[0]['day'];


//更新時間2:寵物食品申報網
$food="select if(max(新增時間)>max(移除時間),STR_TO_DATE(max(新增時間),'%Y%m%d'),STR_TO_DATE(max(移除時間),'%Y%m%d')) as day2 from `pet_food_different`";
$resultfood = mysqli_query($conn, $food);
while($rowfood = mysqli_fetch_assoc($resultfood)) 
    {  
    $arrayfood[] = $rowfood;
    };
$updatefood=$arrayfood[0]['day2'];

//更新時間3:寵物業管理系統
$manage="select if(max(新增時間)>max(移除時間),max(新增時間),max(移除時間)) as day3 from `pet_manage`";
$resultmanage = mysqli_query($conn, $manage);
while($rowmanage = mysqli_fetch_assoc($resultmanage)) 
    {  
    $arraymanage[] = $rowmanage;
    };
$updatemanage=$arraymanage[0]['day3'];

$conn->close();
?>
    
    
<!DOCTYPE html> <!--這個用來改NAV-->
<html>
    <head>
        <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
        <title>寵物產業雲</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <style>
    @font-face{
            font-family:ts;
            src: url('fonts/TaipeiSansTCBeta-Regular.ttf');
                }
    /*body{
        background-image: url(image/banner1.jpg);
         background-position: center 46%;
         background-size:155% 145%;}*/
     body{
        background-image: url(image/banner3.jpg);
         background-position: center -3%;
         background-size:130% 155%;}
    iframe{
    width: 100%; 
    height: 450px;
    margin:0% 0 0% 0;
    border:none;
          }
    
    .xy{
    width: 80%; 
    height: 500px;
    margin:5% 10% 5% 10%;
    border:none;
          }
    .area{
        border:7px WhiteSmoke solid;
         }
    .banner{
        text-align:left;
        font-size:50px;
        background-color: black;
        -webkit-background-clip: text;
        -moz-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: rgba(255,255,255,0.5) 0px 3px 3px;
        }
    /*.title{
        text-align:center;
        font-size:40px;
        background-color:LightGrey;
        text-shadow: 0px 5px 0px #b2a98f,
                     0px 11px 10px rgba(0,0,0,0.15),
                     0px 13px 2px rgba(0,0,0,0.1),
                     0px 5px 5px rgba(0,0,0,0.1);
    }*/
     .title{
            text-align:center;
            font-size:40px;
            background-color:#FFA500;
            color:white;
            text-shadow: 
                         0px 5px 5px rgba(0,0,0,0.15),
                         0px 7px 2px rgba(0,0,0,0.1),
                         0px 5px 5px rgba(0,0,0,0.1);
        }
    .data{
        float:right;
        margin-top:15px;
        font-size:15px;
    }
    .bord{
         border:0px red solid;
         width:20%;
         margin:0px auto;}
    .foot{
        text-align:left;
        font-size:20px;
        }
   #chartdata {
          max-height: 400px;
          overflow: auto;
        }

    #chartdata table {
      width: 80%;
    }
    .update{font-size:15px;}
    </style>
    <body>
            <div class="container-fluid">
                <div class="row" >
                    <div class="col banner">寵物產業雲</div>
                </div>
                <div class="row"><span class="title">寵物產業資訊<span class="data">財政部稅籍<br>更新時間：<?=$check;?></span></div>
                <div class="row">
                    <div class="col-12  col-lg-4 col-xl-4 area">
                        <iframe name="myiframe"  src="財政部稅籍/tax_piechart2.php"></iframe>
                    </div>
                   <div class="col-12 col-lg-8 col-xl-8 area">
                        <iframe  name="myiframe"  src="財政部稅籍/tax_linechart5.php"></iframe>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-7 col-xl-7 area">
                        <iframe name="myiframe"  src="財政部稅籍/tax_linechart3.php" ></iframe>
                    </div>
                     <div class="col-12 col-lg-5 col-xl-5 area">
                        <iframe name="myiframe"  src="財政部稅籍/tax_table.php" ></iframe>
                    </div>
                </div>
                <div class="row"><span class="title">寵物食品資訊<span class="data">寵物食品申報網<br>更新時間：<?=$updatefood;?></span></div>
                <div class="row" >
                    <div class="col-12 col-lg-4 col-xl-4 area">
                        <iframe name="myiframe"  src="寵物食品申報網/food_piechart2.php" ></iframe>
                    </div>
                    <div class="col-12 col-lg-8 col-xl-8 area">
                        <iframe name="myiframe"  src="寵物食品申報網/food_linechart.php"></iframe>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-9 col-xl-9 area">
                        <iframe name="myiframe"  src="寵物食品申報網/food_linechart3.php"></iframe>
                    </div>
                     <div class="col-12 col-lg-3 col-xl-3 area">
                        <iframe name="myiframe"  src="寵物食品申報網/food_linechart2.php" ></iframe>
                    </div>
                </div>
        
                <div class="row"><span class="title">寵物服務資訊<span class="data">寵物業管理系統<br>更新時間：<?=$updatemanage;?></span></div>
                <div class="row">
                    <div class="col-12 col-lg-6 col-xl-6 area">
                        <iframe name="myiframe"  src="寵物業管理系統/manage_piechart.php" ></iframe>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-6 area">
                        <iframe name="myiframe"  src="寵物業管理系統/manage_linechart.php"></iframe>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-12 col-xl-12 area">
                        <iframe name="myiframe"  src="寵物業管理系統/manage_linechart2.php"></iframe>
                    </div>
                </div> 
            </div>
        <footer>
          <div class='bord'>
               <div class='foot'>
                    <p style="margin-top:10px;">電話：</p>
                    <p>傳真：</p>
                    <p>行政院農業委員會版權所有 © Copyrights</p>
               </div>
           </div>
        </footer>
    </body>
</html>