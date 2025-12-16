<!DOCTYPE html>
<html>
<head>
  <title>บันทึกการทานวิตามิน</title>

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

      <div class="content card">
       <h1>บันทึกการทานวิตามิน</h1>
      <div>
  <table >
    <tbody>
          @foreach ($record as $records)
          <tr>
              <td><h2>{{ date('d', strtotime($records->created_at))}}</h2>
                {{ date('m-Y', strtotime($records->created_at))}}
              </td>
              <?php 
              if ($records->vitamin == '0'){
                $a = 'ไม่ได้ทาน';
              }elseif ($records->vitamin  == '1'){
                $a = 'ทาน';
              }else{
                $a = 'ไม่มีการบันทึก';
              }
              ?>
            
             <td>
                <ol>
                  <li><img class="food" src="{{URL::asset('img_web/lime.png')}}" /> {{ $a }}</li>
                </ol>
                
              </td>
          
          </tr>
         @endforeach
   </tbody>







</table></div></div>
<img class="foot" src="{{URL::asset('css/bg_forest.png')}}" />
</body>
</html>




