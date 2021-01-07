<!doctype html>
<?php
header("Content-Type:text/html; charset=utf-8"); 

?>
<html>

<head>
    <title>亞東戒菸小幫手</title>
    <?php
    if(isset($_GET['id'])){
        echo " <meta property='og:title'  content='戒菸狀況圖表' />";
        echo "<meta property='og:description' content='這星期的抽菸狀況'/>";   
    }
    echo "<meta property='og:image' content='http://120.96.63.55/quit/icon/v1.png'/>";
    
    ?>
    <script src="Chart.js"></script>
    <script src="utils.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    </style>
</head>

<body>
    <div id="container" style="width: 75%;" align="center" >
        <canvas id="canvas"></canvas>
    </div>
    <!--<select name="timeinterval">
    <option>Chocolate</option>
    <option>Taffy</option>
    <option>Fudge</option>
    <option>Cookie</option>
        
    </select>-->
    <script>
      $("select").change(function(){
           var interval1 = "";
            $("select option:selected").each(function(){
               interval1 = $(this).text();
            });
            $.ajax({
                url: "smokedata.php",
                type: "POST",
                data: {interval: 1},
                dataType: "json",
                success: function(data){
                    for(var i =data.length-1; i>-1;i--){
                      addData(data[i]["per"],data[i]["date"])
                    }
                   window.myBar.update();
                },
                error: function(){}
            });                 
               });
                
        var color = Chart.helpers.color;
        var barChartData = {
            labels: [],
            datasets: [{
                label: '根數',
                backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: []
            }]
        };

        window.onload = function mycanvas() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0, // it is for ignoring negative step.
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                return value;
                                }
                            }
                        }
                    }]
                },
                title: {
                    display: true,
                    text: "抽菸紀錄"
                }
            }
        });
             $.ajax({
                url: "smokedata.php?id=<?php echo $_GET['id']?>",
                type: "POST",
                data: {interval: 1,id: "1"},
                dataType: "json",
                success: function(data){
                    for(var i =data.length-1; i>-1;i--){
                      addData(data[i]["per"],data[i]["date"])
                    }
                   window.myBar.update();
                },
                error: function(){}
            });
        };

        function addData(smokecount,time) {
            if (barChartData.datasets.length > 0) {
                    barChartData.labels.push(time);
                    for (var index = 0; index < barChartData.datasets.length; ++index) {
                    //window.myBar.addData(randomScalingFactor(), index);
                    barChartData.datasets[index].data.push(smokecount);
                    }                            
            }
        }
    
       function removeData() {
            barChartData.labels.splice(-1, 1); // remove the label first

            barChartData.datasets.forEach(function(dataset, datasetIndex) {
                dataset.data.pop();
            });

            window.myBar.update();
        }
    </script>
</body>

</html>
