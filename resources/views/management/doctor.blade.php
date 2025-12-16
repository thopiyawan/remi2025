@extends('layouts.app')
@section('script')
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="{{URL::asset('js/pie.js')}}"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<!-- <link rel="stylesheet" href="{{URL::asset('css/stylecss_test.css')}}"> -->
<!-- edit -->
<link rel="stylesheet" href="<?php echo asset('css/redesign.css')?>" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">  
<script src="<?php echo asset('js/redesign.js')?>"></script>
<style type="text/css">
#chartdiv {
  width: 100%;
  height: 400px;
}					
td {
    padding: 7px;
}

.btn-info {
    color: #fff;
    background-color: #FFBBCC;
    border-color: #FFBBCC;
}
</style>

<script>
					         var weight_status1 = {{$weight_status[0]}};
					         var weight_status2 = {{$weight_status[1]}};
					         var weight_status3 = {{$weight_status[2]}};
					         var weight_status4 = {{$weight_status[3]}};
					         var weight_status0 = {{$weight_status[4]}};

					
			          var chart = AmCharts.makeChart( "chartdiv", {
					  "type": "pie",
					  "legend":{
						   	"position":"right",
						    "marginRight":100,
						    "autoMargins":false,
							labels: {
								font: {
									size: 14,
									family:'Kanit',
								}
							}
						  },
					  "titles": [ {
					    "text": "กราฟสรุปภาวะแทรกซ้อนและสัญญาณน้ำหนัก",
					    "size": 16
					  } ],
					  "dataProvider": [ {
					    "country": "ภาวะแทรกซ้อน",
					    "visits": weight_status4
					  }, {
					    "country": "น้ำหนักเกินเกณฑ์",
					    "visits": weight_status3
					  }, {
					    "country": "น้ำหนักน้อยกว่าเกณฑ์",
					    "visits": weight_status2
					  }, {
					    "country": "น้ำหนักปกติ",
					    "visits": weight_status1
					  }, {
					    "country": "ไม่มีการบันทึก",
					    "visits": weight_status0
					  }],
					  "valueField": "visits",
					  "titleField": "country",
					  "startEffect": "elastic",
					  "startDuration": 2,
					  "labelRadius": 15,
					  "innerRadius": "50%",
					  "depth3D": 10,
					  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
					  "angle": 15,
					  "export": {
					    "enabled": true
					  }
					} );
</script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
			<h4><BUTTON href="" class="btn fa-input updatePriceButton" data-toggle="modal" data-target="#supplierComments">  <img src= "{{ Session::get('qr_code')}}" width="30" height="30" ></BUTTON>
			 คุณหมอ {{ Session::get('doctor_name') }}</h4>
			<!--  <img src= "{{ Session::get('qr_code')}}" width="200" height="200" > -->
	        <!-- 					<br><br> -->
			<div id="chartdiv"></div>
			<table class="" cellpadding="1" cellspacing="1" id="">
				<thead>
					<tr class="table-header">
						<th width="5%"><center>Case No</center></th>
			            <th width="15%">เลขประจำตัว (HN)</th>
			            <th width="15%">ชื่อ</th>
			            <th width="10%">อายุครรภ์</th>
			            <th width="12%">กำหนดการคลอด</th>
			            <th width="10%">น้ำหนักล่าสุด</th>
			            <th width="20%">สถานะการใช้งานครั้งล่าสุด</th>
						<th width="18%"><center>สัญญานน้ำหนัก</center></th>
			            <th width="10%"></th>
						<th width="10%"></th>
					</tr>
				</thead>
				<tbody id="">
					<?php $i = 1; ?>
					@foreach($datas as $user)
					<tr>
						<td align="center">{{ $i++ }}</td>
						<td>{{ $user->hospital_num }}</td>
						<td>{{ $user->user_name }}</td>
						<td>{{ $user->preg_week }}</td>
						<td>{{ $user->due_date }}</td>
						<td>{{ $user->user_weight }}</td>
						<td>{{ $user->created_at }}</td>
						<td align="center">
							@if ($user->weight_status == 1)
								<span class="dot_1"></span>
							@elseif ($user->weight_status == 2)
								<span class="dot_2"></span>
							@elseif ($user->weight_status == 3)
								<span class="dot_3"></span>
							@elseif ($user->weight_status == 4)
								<span class="dot_4"></span>
							@else
								<span class="dot_0"></span>
							@endif
						</td>
						
						<td><a href="info/{{ $user->user_id }}" class="btn btn-info" role="button">เรียกดูข้อมูล</a></td>

						<td><form action="{{ route('delete_user',[$user->user_id]) }}" method="POST">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
							<button  class="btn btn-danger" type="submit" onclick="return confirm('คุณต้องการลบคนไข้ ใช่หรือไม่?')" ><i class="fa fa-trash"></i></button>
						</form></td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<div class="d-flex justify-content-center">
		    <!-- {{$datas->appends(Request::all())->links()}} -->
			<!-- {{ $datas->links() }} -->
			<!-- {{$datas->withQueryString()->links();}} -->
			<!-- {!! $datas->appends(Request::except('page'))->render() !!} -->
			<!-- {{$datas->appends(request()->input())->links();}} -->
			<!-- {!! $datas->links('pagination::default') !!} -->
			<!-- {!! $datas->render() !!} -->
            </div>
			<!-- {{ $datas->appends($_GET)->links() }} -->
			<p class="text-sm leading-5 text-gray-700">
            Showing
            <span class="font-medium">{{ $datas->firstItem() }}</span>
            to
            <span class="font-medium">{{ $datas->lastItem() }}</span>
            of
            <span class="font-medium">{{ $datas->total() }}</span>
            results
</p>
        </div>
    </div>
</div>
  <!-- Trigger the modal with a button -->
  <!-- Modal -->
  <div class="modal fade" id="supplierComments" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">สแกน</h4>
        </div>
        <CENTER>  <img src= "{{ Session::get('qr_code')}}" width="200" height="200" > </CENTER>
         
      </div>
      
    </div>
  </div>

@endsection
