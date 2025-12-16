<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>กราฟระดับน้ำตาลในเลือด</title>
</head>

<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>

<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="//www.amcharts.com/lib/3/plugins/responsive/responsive.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />


<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<link rel="stylesheet" href="{{URL::asset('css/stylecss_test.css')}}">

<!-- blood page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>


 <style>
  body{
    background-color: #f3f6fb;
    padding: 0;
    margin: 0;
    font-family: 'Kanit', sans-serif;
    /* font-size: 15pt; */
  }
  .chartdiv {
	width: 100%;
	height: 500px;
	}
  .chart2div {
	width		: 100%;
	height		: 300px;
	font-size	: 11px;
  }

	g.amcharts-category-axis tspan {
	cursor: pointer;
	}

	g.amcharts-category-axis text.amcharts-axis-label tspan:hover,
	g.amcharts-graph-label-only text tspan {
	text-decoration: underline;
	fill: red;
	}

	text.amcharts-axis-title {
	font-size: 13px;
	}

  button {
 /* display: flex; */
 flex-direction: row;
 justify-content: center;
 align-items: center;
 padding: 6px 20px;
 border-radius: 10px;
 border: 1px solid transparent;
 color: #FFFFFF;
 background-color: #FA8072;
 font-size: 16px;
 letter-spacing: 1px;
 transition: all 0.15s linear;
}

button:hover {
 background-color: rgba(29, 201, 160, 0.08);
 border-color: #FA8072;
 color: #FA8072;
 transform: translateY(-5px) scale(1.05);
}

button:active {
 background-color: transparent;
 border-color: #FA8072;
 color: #FA8072;
 transform: translateY(5px) scale(0.95);
}

button:disabled {
 background-color: rgba(255, 255, 255, 0.16);
 color: #8E8E93;
 border-color: #8E8E93;
}

</style>
<script type="text/javascript">
var data_blood_level =[
          @foreach ($graphdata as $graphdatas)
		  {"เวลา": "{{date('Y-m-d H:i', strtotime($graphdatas->datetime));}}",
		  "ระดับน้ำตาล": "{{$graphdatas->blood_sugar}}",
      "ช่วงการวัด" : "{{$graphdatas->time_of_day}}",
      "มื้อ" : "{{$graphdatas->meal}}"},

          @endforeach
        ];
        
console.log(data_blood_level);

var colors = [  @foreach ($graphdata as $graphdatas)
             <?php 
             $a=array();

             switch ($graphdatas->meal) {
              case "4":
                if($graphdatas->meal=='4'&& $graphdatas->blood_sugar>120){
                  $color = "rgb(250, 128, 114)";
                }else{
                  $color = "rgba(64, 224, 208)";
                }  
                break;
              default:
              if(($graphdatas->time_of_day =='1' && $graphdatas->blood_sugar>95)||($graphdatas->time_of_day== '3' && $graphdatas->blood_sugar>140)||($graphdatas->time_of_day=='4' && $graphdatas->blood_sugar>120)){
                $color = "rgb(250, 128, 114)";
              }else{
                $color = "rgba(64, 224, 208)";
              }  
            }            
                   echo $a[] = '"'.$color.'"'.',';
              // echo json_encode(array_push($a,$color));
              ?>
              @endforeach  ]
console.log(colors);

var bullet = ["diamond","square","xError", "round", "triangleUp"];
var bullet_des = ["-","ก่อนอาหาร", "ก่อนนอน", "หลังอาหาร 1 ชม.", "หลังอาหาร 2 ชม."];
var bullet_meal = ["-","เช้า","กลางวัน", "เย็น", "ก่อนนอน"];

var chart1 = AmCharts.makeChart("chart1div", {
    "type": "serial",
    "marginTop":0,
    "marginRight": 80,
    "dataProvider": [
		@foreach ($graphdata as $graphdatas)
   		  { 
          "date": "{{date('Y-m-d H:i', strtotime($graphdatas->datetime));}}",
          "glucose_level": "{{$graphdatas->blood_sugar}}",
          "bullet": bullet["{{$graphdatas->time_of_day}}"], 
          "bullet_des": bullet_des["{{$graphdatas->time_of_day}}"],
          "bullet_meal": bullet_meal["{{$graphdatas->time_of_day}}"],
          "color":(("{{$graphdatas->meal}}"=='4'&& "{{$graphdatas->blood_sugar}}">120)|| (("{{$graphdatas->time_of_day}}" =='1' && "{{$graphdatas->blood_sugar>95}}")||("{{$graphdatas->time_of_day}}"== '3' && "{{$graphdatas->blood_sugar>140}}")||("{{$graphdatas->time_of_day}}"=='4' && "{{$graphdatas->blood_sugar>120}}"))) ? '#FF3131' : (("{{$graphdatas->blood_sugar}}"<60) ? '#FADA5E' : '#50C878'),
          "status":(("{{$graphdatas->meal}}"=='4'&& "{{$graphdatas->blood_sugar}}">120)|| (("{{$graphdatas->time_of_day}}" =='1' && "{{$graphdatas->blood_sugar>95}}")||("{{$graphdatas->time_of_day}}"== '3' && "{{$graphdatas->blood_sugar>140}}")||("{{$graphdatas->time_of_day}}"=='4' && "{{$graphdatas->blood_sugar>120}}"))) ? "เกินเกณฑ์" : (("{{$graphdatas->blood_sugar}}"<60) ? "ต่ำกว่าเกณฑ์" : "ปกติ")
        }, 
    @endforeach ],
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left",
        "guides": [{
            "dashLength": 6,
            "inside": true,
            "label": "140 หลัง 1 ชม.",
            "lineAlpha": 1,
            "value": 140
        },
        {
            "dashLength": 6,
            "inside": true,
            "label": "120 หลัง 2 ชม.",
            "lineAlpha": 1,
            "value": 120
        },
        {
            "dashLength": 6,
            "inside": true,
            "label": "95 ก่อนอาหาร",
            "lineAlpha": 1,
            "value": 95
        }],
    }],
    "graphs": [{
        "id":"g1",
        "balloonText": "</b><span style='font-size:14px;'>[[bullet_des]]</span></b><br>[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
        "bullet": "round",
        "fillColors": "#6BCB77",
        "valueField": "glucose_level",
        "bulletSize": 15,
        "lineColor": "#D3D3D3",
        "lineThickness": 2,
        // "negativeBase": 100, 
        // "type": "smoothedLine",
        "visibleInLegend": false,
        "bulletField": "bullet",
        "bulletSizeField": "bulletSize",
        "fillColorsField": "fillColors",
        "colorField":"color"
        
        
		    // "drop": true,
        // "adjustBorderColor": false,
    }],
    "chartScrollbar": {
        "graph":"g1",
        "gridAlpha":0,
        "color":"#888888",
        "scrollbarHeight":20,
        "backgroundAlpha":0,
        "selectedBackgroundAlpha":0.1,
        "selectedBackgroundColor":"#888888",
        "graphFillAlpha":0,
        "autoGridCount":false,
        "selectedGraphFillAlpha":0,
        "graphLineAlpha":0.2,
        "graphLineColor":"#c2c2c2",
        "selectedGraphLineColor":"#888888",
        "selectedGraphLineAlpha":15
    },
    "chartCursor": {
        "categoryBalloonDateFormat": "YYYY-MM-DD JJ:NN",
        "cursorAlpha": 0,
        "valueLineEnabled":true,
        "valueLineBalloonEnabled":true,
        "valueLineAlpha":0.5,
        "fullWidth":true
    },
    "dataDateFormat": "YYYY-MM-DD JJ:NN",
    "categoryField": "date",
    "categoryAxis": {
        "minPeriod": "mm",
        "parseDates": true,
        "minorGridAlpha": 0.1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true,
        "fileName": "บันทึกน้ำตาลในเลือด"+moment().format("DD-MM-YYYY"),
        "menuReviver": function(item,li) {
          if (item.format == "XLSX") {
            item.name = moment().format("DD-MM-YYYY");
          }
          return li;
        },
        "exportFields": [
            "date",
            "glucose_level",
            "bullet_des",
            "bullet_meal",
            "status"
        ],
        "columnNames": {
            "date": "วันที่บันทึก",
            "glucose_level": "ค่าระดับน้ำตาล",
            "bullet_meal": "มื้ออาหาร",
            "bullet_des": "ช่วงการวัด",
            "status": "สถานะการประเมิน"
        }
    },
});

chart1.addListener("rendered", zoomChart);
if(chart1.zoomChart){
	chart1.zoomChart();
}

// function zoomChart(){
//     chart1.zoomToIndexes(Math.round(chart1.dataProvider.length * 0.4), Math.round(chart1.dataProvider.length *0.55));
// }
function zoomChart(){
  chart1.zoom(moment(new Date(), "YYYY-MM-DDTHH:mm:ss.SSS[Z]").subtract(2,'d'),moment(new Date(), "YYYY-MM-DDTHH:mm:ss.SSS[Z]"));
}

function getFirstDayOfWeek(d) {
  const date = new Date(d);
  const day = date.getDay(); // 👉️ get day of week
  const diff = date.getDate() - day + (day === 0 ? -6 : 1);
  return new Date(date.setDate(diff));
}
  var a =  [@foreach ($blood_sugar as $bs)
   		  { 
          "date": "{{$bs->datetime}}",
          "status_lable": "{{$bs->status_lable}}",
          "week": moment(getFirstDayOfWeek(new Date("{{$bs->datetime}}"))).format('D/MMM/YYYY')+' to '+moment(new Date(getFirstDayOfWeek("{{$bs->datetime}}").setDate(getFirstDayOfWeek("{{$bs->datetime}}").getDate() + 6))).format('D/MMM/YYYY'),        
        },  
    @endforeach ]

  const res = a.reduce((acc, obj) => {
  const existingIndex = acc.findIndex(
    el => el.status_lable === obj.status_lable && el.week === obj.week
  )
  if (existingIndex > -1) {
    acc[existingIndex].count += 1
  } else {
    acc.push({
      status_lable: obj.status_lable,
      week: obj.week ,
      count: 1
    })
  }
  return acc
}, [])
console.log(res);

var arr=[];  

for (var i =1; i < res.length; ++ i) {
const data2 = res.map(item => {
  let predefined = {};
  
  if (item.week === res[i]['week']) predefined.week = item.week;
  if (item.week === res[i]['week']){
    // predefined.week = item.week
    if (item.status_lable  === 'LOWต่ำกว่าเกณฑ์') predefined.LOW = item.count;
    if (item.status_lable  === 'NORMALปกติ') predefined.NORMAL = item.count;
    if (item.status_lable  === 'HIGHเกินเกณฑ์') predefined.HIGH = item.count;
    
  }

  arr.push(predefined);
  
   
})}
console.log(arr);
let resarr = {};
arr.forEach(a => resarr[a.week] = {...resarr[a.week], ...a});
resarr = Object.values(resarr);
const result = resarr.filter(element => {
  if (Object.keys(element).length !== 0) {
    return true;
  }

  return false;
});
console.log(result);



var chart3 = AmCharts.makeChart("chart2div", {
  "type": "serial",
	"theme": "none",
    "legend": {
      "autoMargins": false,
      "horizontalGap": 10,
      "useGraphSettings": true,
      "markerSize": 10

    },
    "dataProvider": result,
    "valueAxes": [{
        "stackType": "regular",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "labelsEnabled": false,
        "position": "left"
    }],
    "chartScrollbar": {
        "graph":"g1",
        "gridAlpha":0,
        "color":"#888888",
        "scrollbarHeight":20,
        "backgroundAlpha":0,
        "selectedBackgroundAlpha":0.1,
        "selectedBackgroundColor":"#888888",
        "graphFillAlpha":0,
        "autoGridCount":false,
        "selectedGraphFillAlpha":0,
        "graphLineAlpha":0.2,
        "graphLineColor":"#c2c2c2",
        "selectedGraphLineColor":"#888888",
        "selectedGraphLineAlpha":15
    },
    "graphs": [
      
      {
        
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.1,
        "title": "LOW",
        "type": "column",
		    "color": "#000000",
        "fillColors": "#FADA5E",
        "valueField": "LOW",
    }, 
    {
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.1,
        "title": "NORMAL",
        "type": "column",
        "color": "#000000",
        "fillColors": "#50C878",
        "valueField": "NORMAL"
    }, {
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.1,
        "title": "HIGH",
        "type": "column",
        "color": "#000000",
        "fillColors": "#FF3131",
        "valueField": "HIGH"
    }
  ],
    "categoryField": "week",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "position": "left"
    },
    "export": {
        "enabled": true,
        "fileName": "สรุปค่าระดับน้ำตาลในเลือดรายสัปดาห์"+moment().format("DD-MM-YYYY"),
        "exportFields": [
            "week",
            "LOW",
            "HIGH",
            "NORMAL"
        ],
        "columnNames": {
            "week": "สัปดาห์",
            "LOW": "ต่ำกว่าเกณฑ์",
            "NORMAL": "ปกติ",
            "HIGH": "เกินเกณฑ์",
        },
        "menuReviver": function(item,li) {
          if (item.format == "XLSX") {
            item.name = moment().format("DD-MM-YYYY");
          }
          return li;
        }

    },

});


</script>

<body>           
<div class="tab-pane fade" id="pills-blood" role="tabpanel" aria-labelledby="pills-blood-tab">				
				<div class="content card">
    <div style="text-align:center;"> <h4>กราฟระดับน้ำตาลในเลือด</h4></div>
    <!-- <div id="chartdiv"></div> -->
	<div id="chart1div" class="chartdiv"></div>
  <div style="text-align:center;"><h4>สรุปค่าระดับน้ำตาลรายสัปดาห์</h4></div>
  <div id="chart2div"  class="chart2div"></div>
	<!-- <div id="bloodchart" class="chartdiv"></div> -->
    <!-- <canvas id="myChart" width="400" height="400"></canvas>    -->
<div style="text-align:center;"><h4>ประวัติการบันทึก</h4></div>
<table class="" cellpadding="1" cellspacing="1" id="">
  <thead>
    <tr id="table-header">
      <th ><label>วัน</label></th>
      <th><label> เวลา</label></th>
      <th><label> มื้อ</label></th>
      <th><label> ช่วงการวัด</label></th>
      <th><label>ค่าน้ำตาล</label></th>
      <th><label>ลบ</label></th>
    </tr>
  </thead>
  <tbody id="">
    @foreach ($graphdata as $graphdatas)
    <?php 
        $temp = explode(' ',$graphdatas->datetime);
        $time = date("H:i", strtotime($temp[1]));
        $Date = date("d", strtotime($temp[0]));
        $year = date("Y", strtotime($temp[0]));  
        $meal = array("","เช้า", "กลางวัน", "เย็น", "ก่อนนอน");
        $tod = array("","ก่อน", "หลัง", "หลัง 1 ชม." , "หลัง 2 ชม.");
        $strMonth= date("n",strtotime($temp[0]));
        $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        $DateThai = $strMonthThai;

        
        switch ($graphdatas->meal) {
          case "4":
            if($graphdatas->blood_sugar>120){
              $color = "#FF0000";
            }elseif($graphdatas->blood_sugar<60){
              $color = '#FADA5E';
            }else{
              $color = "#17202A";
            }  
            break;
          default:
          if(($graphdatas->time_of_day =='1' && $graphdatas->blood_sugar>95)||($graphdatas->time_of_day== '3' && $graphdatas->blood_sugar>140)||($graphdatas->time_of_day=='4' && $graphdatas->blood_sugar>120)){
            $color = "#FF0000";
          }elseif($graphdatas->blood_sugar<60){
            $color = '#FADA5E';
          }else{
            $color = "#17202A";
          }  
        }       
    ?>
    <tr class="" style="color:{{{ $color }}}">
      <td style="display:none;"><label>{{ $temp[0] }}</label></td>
      <td><label><h3>{{ $Date  }}  {{$strMonthThai}}</h3> {{$year}}</label></td>
      <td><label>{{ $time  }} น.</label></td>
      <td><label>{{ $meal[ $graphdatas->meal]}}</label></td>
      <td><label>{{  $tod[$graphdatas->time_of_day]}}</label></td>
      <td><label><h1>{{ $graphdatas->blood_sugar}}</h1> มก./ดล.</label></td>
      <td><label>
          <form action="{{ route('delete_bs',[$graphdatas->id]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button  class="btn btn-danger" type="submit" onclick="return confirm('คุณต้องการลบ ใช่หรือไม่?')" ><i class="fa fa-trash"></i></button>
		      </form></label>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
  			
				</div>
			</div>
    <!-- LIFF SDK  -->
    <script src="https://static.line-scdn.net/liff/edge/versions/2.9.0/sdk.js"></script>
<script>

$(document).on("change", "#datepicker .created_on", function() {
  var dataVal = $(this).datepicker('getDate');//get date from datepicker
  dataVal= $.datepicker.formatDate("yy-mm-dd", dataVal);//set format date like in the rows
  //console.log(dataVal, typeof dataVal);
  if (dataVal != '') {
    $("tr:not('#table-header')").hide();//hide all rows
    //show rows with the same date selected
    $("label:contains('" +  dataVal + "')").each(function(){
      $(this).closest('tr').show();
    });
  }  
});


(function($) {
  $('.datepicker').each(function() {
    $(this).datepicker({
      setDate : new Date(),
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      onClose: function() {
        //triggerFocus();
      }
    });
  });
}(jQuery));
</script>

</body>
</html>