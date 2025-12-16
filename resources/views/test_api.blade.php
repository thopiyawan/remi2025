<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grouped Stack Bar Chart with AmCharts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.11/flatpickr.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.11/flatpickr.min.js"></script>
    
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    
</head>
<body>
    
    <h3>สรุปค่าระดับน้ำตาลในเลือด</h3>
    <label> <h4>เลือกวันที่ </h4>
    <input type="text" id="dateRange" placeholder="Select Date Range"> </label> 
    <div id="barchart" style="width: 100%; height: 500px;"></div>
   


    <style>
        body {
            font-family: 'Kanit', sans-serif;
            text-align: center;
            margin: 20px;
        }

        h1 {
            margin-bottom: 10px;
        }

        #dateRange {
            width: 200px;
            padding: 8px;
        }

        canvas {
            max-width: 800px;
            margin-top: 20px;
        }


        
    </style>
</body>
</html>

    <script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
      function filteredcateg(item,categ,group) {

        var category = filteredData.filter(function(item) {
            var category = item.category;
            return category == categ;
        });
        var res = category.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
        }, {})

        return res;
        
    }

    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
            sum += parseFloat( obj[el] );
            }
        }
        return sum;
    }
    // Sample data
    var data = [
        @foreach ($graphbar as $graphbars)
   		{ 
          "date": "{{$graphbars['date']}}",
          "category": "{{$graphbars['category']}}",
          "status": "{{$graphbars['status']}}", 
        }, 
       @endforeach
    ];
    console.log(data);
    // Initialize date range picker
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            // When dates are selected, update the chart
            updateChart(selectedDates);
        }
    });

    // Initialize the chart
    var chart = am4core.create("barchart", am4charts.XYChart);

    function filteredcateg(item,categ,group) {
        var category = data.filter(function(item) {
            var category = item.category;
            return category == categ;
        });
        var res = category.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
        }, {})
        return res;
    }

    function sum( obj ) {
            var sum = 0;
            for( var el in obj ) {
                if( obj.hasOwnProperty( el ) ) {
                sum += parseFloat( obj[el] );
                }
            }
            return sum;
    }

    for (var i = 0; i < data.length; i++) {
            var item = data[i];
            var categ = item['category'];
            res1 = filteredcateg(item, categ ='เช้า-ก่อน');
            res2 = filteredcateg(item, categ ='เช้า-หลัง');
            res3 = filteredcateg(item, categ ='กลางวัน-ก่อน');
            res4 = filteredcateg(item, categ ='กลางวัน-หลัง');
            res5 = filteredcateg(item, categ ='เย็น-ก่อน');
            res6 = filteredcateg(item, categ ='เย็น-หลัง');
            res7 = filteredcateg(item, categ ='รวม');
            var summed_res1 = sum(res1);
            var summed_res2 = sum(res2);
            var summed_res3 = sum(res3);
            var summed_res4 = sum(res4);
            var summed_res5 = sum(res5);
            var summed_res6 = sum(res6);
            var sum_total = summed_res1+summed_res2+summed_res3+summed_res4+summed_res5+summed_res6;
            var cate_sum = data.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
            }, {})
            console.log(cate_sum);
            chart.data = [{
                "category": "เช้า-ก่อน",
                "group1": ((res1['low']/summed_res1)*100).toFixed(2),
                "group2": ((res1['normal']/summed_res1)*100).toFixed(2),
                "group3": ((res1['high']/summed_res1)*100).toFixed(2),

            },
            {
                "category": "เช้า-หลัง",
                "group1": ((res2['low']/summed_res2)*100).toFixed(2) ,
                "group2": ((res2['normal']/summed_res2)*100).toFixed(2),
                "group3": ((res2['high']/summed_res2)*100).toFixed(2),
 
            },
            {
                "category": "กลางวัน-ก่อน",
                "group1": ((res3['low']/summed_res3)*100).toFixed(2) ,
                "group2": ((res3['normal']/summed_res3)*100).toFixed(2),
                "group3": ((res3['high']/summed_res3)*100).toFixed(2),
     
            },
            {
                "category": "กลางวัน-หลัง",
                "group1": ((res4['low']/summed_res4)*100).toFixed(2) ,
                "group2": ((res4['normal']/summed_res4)*100).toFixed(2),
                "group3": ((res4['high']/summed_res4)*100).toFixed(2),
            },
            {
                "category": "เย็น-ก่อน",
                "group1": ((res5['low']/summed_res5)*100).toFixed(2) ,
                "group2": ((res5['normal']/summed_res5)*100).toFixed(2) ,
                "group3": ((res5['high']/summed_res5)*100).toFixed(2),
            },
            {
                "category": "เย็น-หลัง",
                "group1": ((res6['low']/summed_res6)*100).toFixed(2) ,
                "group2": ((res6['normal']/summed_res6)*100).toFixed(2),
                "group3": ((res6['high']/summed_res6)*100).toFixed(2),
            },
            {
                "category": "รวม",
                "group1": ((cate_sum['low']/sum_total)*100).toFixed(2) ,
                "group2": ((cate_sum['normal']/sum_total)*100).toFixed(2),
                "group3": ((cate_sum['high']/sum_total)*100).toFixed(2),
            },
            
            
        ]; };

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "category";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.line.strokeOpacity = 1;
    categoryAxis.renderer.minGridDistance = 30;
    categoryAxis.renderer.cellStartLocation = 0.2;
    categoryAxis.renderer.cellEndLocation = 0.8;

    categoryAxis.renderer.inside = true;
    categoryAxis.renderer.labels.template.valign = "top";
    categoryAxis.renderer.labels.template.fontSize = 13;

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.grid.template.disabled = true;
        valueAxis.renderer.labels.template.disabled = true;
    chart.colors.list = [
            am4core.color("#ffde7b"),
            am4core.color("#1DC9A0"),
            am4core.color("#ff5162"),
            ];
           
    var createSeries = function(field, name, stacked) {
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = field;
        series.dataFields.categoryX = "category";
        series.name = name;
        series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/] %";
        series.stacked = stacked;
        series.columns.template.width = am4core.percent(95);

        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
        labelBullet.label.text = "{valueY} %";
        labelBullet.label.fill = am4core.color("#fff");
        labelBullet.locationY = 0.5;
        labelBullet.label.hideOversized = true;

        return series;
    };

    var group1Series = createSeries("group1", "low", true);
    var group2Series = createSeries("group2", "normal", true);
    var group3Series = createSeries("group3", "high", true);

    // Add legend
    chart.legend = new am4charts.Legend();

    // Update function to handle date range selection
    function updateChart(selectedDates) {
        var startDate = new Date(selectedDates[0]);
        var endDate = new Date(selectedDates[1]);

        var filteredData = data.filter(function(item) {
            var date = new Date(item.date);
            return date >= startDate && date <= endDate;
        });
    
    function filteredcateg(item,categ,group) {
        var category = filteredData.filter(function(item) {
            var category = item.category;
            return category == categ;
        });
        var res = category.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
        }, {})
        return res;
    }
    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
            sum += parseFloat( obj[el] );
            }
        }
        return sum;
    }
        var newData = [];
        for (var i = 0; i < filteredData.length; i++) {
            var item = filteredData[i];
            var categ = item['category'];
            
           
            res1 = filteredcateg(item, categ ='เช้า-ก่อน');
            res2 = filteredcateg(item, categ ='เช้า-หลัง');
            res3 = filteredcateg(item, categ ='กลางวัน-ก่อน');
            res4 = filteredcateg(item, categ ='กลางวัน-หลัง');
            res5 = filteredcateg(item, categ ='เย็น-ก่อน');
            res6 = filteredcateg(item, categ ='เย็น-หลัง');
            res7 = filteredcateg(item, categ ='รวม');
           
            var summed_res1 = sum(res1);
            var summed_res2 = sum(res2);
            var summed_res3 = sum(res3);
            var summed_res4 = sum(res4);
            var summed_res5 = sum(res5);
            var summed_res6 = sum(res6);
            var sum_total = summed_res1+summed_res2+summed_res3+summed_res4+summed_res5+summed_res6;
            // var sum_total_g2 = sum(res6);
            // var sum_total_g3 = sum(res6);

            var cate_sum = filteredData.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
            }, {})
            console.log(cate_sum);
                
 

            newData.push({
                "category": "เช้า-ก่อน",
                "group1": ((res1['low']/summed_res1)*100).toFixed(2),
                "group2": ((res1['normal']/summed_res1)*100).toFixed(2),
                "group3": ((res1['high']/summed_res1)*100).toFixed(2),

            },
            {
                "category": "เช้า-หลัง",
                "group1": ((res2['low']/summed_res2)*100).toFixed(2) ,
                "group2": ((res2['normal']/summed_res2)*100).toFixed(2),
                "group3": ((res2['high']/summed_res2)*100).toFixed(2),
 
            },
            {
                "category": "กลางวัน-ก่อน",
                "group1": ((res3['low']/summed_res3)*100).toFixed(2) ,
                "group2": ((res3['normal']/summed_res3)*100).toFixed(2),
                "group3": ((res3['high']/summed_res3)*100).toFixed(2),
     
            },
            {
                "category": "กลางวัน-หลัง",
                "group1": ((res4['low']/summed_res4)*100).toFixed(2) ,
                "group2": ((res4['normal']/summed_res4)*100).toFixed(2),
                "group3": ((res4['high']/summed_res4)*100).toFixed(2),
            },
            {
                "category": "เย็น-ก่อน",
                "group1": ((res5['low']/summed_res5)*100).toFixed(2) ,
                "group2": ((res5['normal']/summed_res5)*100).toFixed(2) ,
                "group3": ((res5['high']/summed_res5)*100).toFixed(2),
            },
            {
                "category": "เย็น-หลัง",
                "group1": ((res6['low']/summed_res6)*100).toFixed(2) ,
                "group2": ((res6['normal']/summed_res6)*100).toFixed(2),
                "group3": ((res6['high']/summed_res6)*100).toFixed(2),
            },
            {
                "category": "รวม",
                "group1": ((cate_sum['low']/sum_total)*100).toFixed(2) ,
                "group2": ((cate_sum['normal']/sum_total)*100).toFixed(2),
                "group3": ((cate_sum['high']/sum_total)*100).toFixed(2),
            },
            
            );
        }
        chart.data = newData;
    }
});

    </script>
</body>
</html>
