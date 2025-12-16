

<!DOCTYPE html>
<html>
<head>
  <title>บันทึกอาหาร</title>

</head>
<meta charset="utf-8">
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<!-- <link rel="stylesheet" href="css/stylecss_pploy.css" /> -->
<link rel="stylesheet" href="{{URL::asset('css/stylecss_pploy.css')}}">
<link href="https://fonts.googleapis.com/css?family=Abril+Fatface" rel="stylesheet">

<style type="text/css">
ol {
  list-style: none;
  counter-reset: my-awesome-counter;
/*  display: flex;
  flex-wrap: wrap;*/
  margin: 0;
  padding: 0;
  text-align: left;
}

  
h2{
  -webkit-margin-before: 0px;
    -webkit-margin-after: 0px;
}
</style>

<body>

  <div class="content card">
       <h1>บันทึกอาหาร</h1>

  <table>
<!--     <thead>
        <tr>
            <th>วันที่</th>
            <th>อาหารเช้า</th>
             <th>อาหารกลางวัน</th>
            <th>อาหารเย็น</th>
             <th>มื้อว่างรอบเช้า</th>
            <th>มื้อว่างรอบบ่าย</th>
        </tr>
    </thead> -->
    <tbody>
          @foreach ($record as $records)
          <tr>
              <td><h2>{{ date('d', strtotime($records->created_at))}}</h2>
                {{ date('m-Y', strtotime($records->created_at))}}
              </td>

              <?php  
              if ($records->breakfast == 'NULL'){
                $breakfast = 'ไม่มีการบันทึก';
              }else{
                $breakfast = $records->breakfast ;
              }
              if ($records->lunch == 'NULL'){
                $lunch = 'ไม่มีการบันทึก';
              }else{
                $lunch = $records->lunch;
              }
              if ($records->dinner == 'NULL'){
                $din = 'ไม่มีการบันทึก';
              }else{
                $din = $records->dinner;
              }
              if ($records->dessert_lu == 'NULL'){
                $de_lu = 'ไม่มีการบันทึก';
              }else{
                $de_lu = $records->dessert_lu;
              }
              if ($records->dessert_din == 'NULL'){
                $de_din = 'ไม่มีการบันทึก';
              }else{
                $de_din= $records->dessert_din;
              }
              ?>
               <td>
                <ol>
                  <li><img class="food" src="{{URL::asset('img_web/sunrise.png')}}" /></li>
                  <li><img class="food" src="{{URL::asset('img_web/sunrise.png')}}" /></li>
                  <li><img class="food" src="{{URL::asset('img_web/sunrise (2).png')}}" /></li>
                  <li><img class="food" src="{{URL::asset('img_web/sunrise (2).png')}}" /></li>
                  <li><img class="food" src="{{URL::asset('img_web/sunrise (3).png')}}" /></li>
                    </ol>
                <td>
                <ol>
                  <li><img class="food" src="{{URL::asset('img_web/diet.png')}}" /> {{$breakfast}}</li>
                  <li><img class="food" src="{{URL::asset('img_web/orange.png')}}" /> {{$de_lu}}</li>
                  <li><img class="food" src="{{URL::asset('img_web/diet.png')}}" /> {{$lunch}}</li>
                  <li><img class="food" src="{{URL::asset('img_web/orange.png')}}" /> {{$de_din}}</li>
                  <li><img class="food" src="{{URL::asset('img_web/diet.png')}}" /> {{$din}}</li>
                </ol>
                
              </td>
          </tr>
         @endforeach
   </tbody>
</table></div></div>
<img class="foot" src="{{URL::asset('css/bg_forest.png')}}" />
</body>
</html>




