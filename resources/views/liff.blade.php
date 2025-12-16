<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- <script src="//code.jquery.com/jquery-2.1.3.min.js"></script> -->

<link rel="stylesheet" href="{{URL::asset('css/liff.css')}}">
<!------ Include the above in your HEAD tag ---------->

<div class="container">
            <form class="form-horizontal" role="form">
                <h2>Registration</h2>
                <div class="form-group">
                    <label for="firstName" class="col-sm-3 control-label">ชื่อ นามสกุล</label>
                    <div class="col-sm-9">
                        <input type="text" id="firstName" placeholder="ชื่อ นามสกุล" class="form-control" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="age" class="col-sm-3 control-label">อายุ</label>
                    <div class="col-sm-9">
                        <input type="text" id="age" placeholder="อายุ" class="form-control" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="height" class="col-sm-3 control-label">ส่วนสูง</label>
                    <div class="col-sm-9">
                        <input type="text" id="height" placeholder="ส่วนสูง" class="form-control" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="user_Pre_weigh" class="col-sm-3 control-label">น้ำหนักปกติก่อนตั้งครรภ์</label>
                    <div class="col-sm-9">
                        <input type="text" id="preweight" placeholder="น้ำหนักปกติก่อนตั้งครรภ์" class="form-control" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="weigh" class="col-sm-3 control-label">น้ำหนักปัจจุบัน</label>
                    <div class="col-sm-9">
                        <input type="text" id="weight" placeholder="น้ำหนักปัจจุบัน" class="form-control" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">คำนวณอายุครรภ์</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="preg_week" value="preg_week" >
                                ครั้งสุดท้ายที่มีประจำเดือน
                                  <input type="date" name="preg">
                                  <input type="submit" value="ตกลง">
                                
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="preg_week" value="preg_week">กำหนดการคลอด<br>

                                  <input type="date" name="preg">
                                  <input type="submit" value="ตกลง">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phoneNumber" class="col-sm-3 control-label">เบอร์โทรศัพท์</label>
                    <div class="col-sm-9">
                        <input type="phoneNumber" id="phoneNumber" placeholder="เบอร์โทรศัพท์" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="weigh" class="col-sm-3 control-label">น้ำหนักปัจจุบัน</label>
                    <div class="col-sm-9">
                        <input type="text" id="weight" placeholder="น้ำหนักปัจจุบัน" class="form-control" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email* </label>
                    <div class="col-sm-9">
                        <input type="email" id="email" placeholder="Email" class="form-control" name= "email">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">โรงพยาบาลที่ไปฝากครรภ์</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="hospital" value="hospital" >โรงพยาบาลธรรมศาสตร์
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="hospital" value="hospital">โรงพยาบาลศิริราช
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">แพ้อาหารหรือไม่</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="history_food" value="history_food" >แพ้อาหาร

                                </label>
                                   <!--  <div class="col-sm-6"> -->
                                        <input type="text" id="food" class="form-control" name= "food" placeholder="อาหารที่แพ้">
                                    <!-- </div> -->
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="history_food" value="history_food">ไม่แพ้อาหาร
                                </label>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.form-group -->


                <div class="form-group">
                    <label class="control-label col-sm-3">ระดับการออกกำลังกาย</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-15">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="active_lifestyle" value="active_lifestyle" >เบา (วิถีชีวิตทั่วไป ไม่มีการออกกำลังกาย หรือมีการออกกำลังกายน้อย)
                                </label>
                            </div>
                            <div class="col-sm-15">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="active_lifestyle" value="active_lifestyle">กลาง (วิถีชีวิตกระฉับกระเฉง หรือ มีการออกกำลังกายสม่ำเสมอ)
                                </label>
                            </div>
                            <div class="col-sm-15">
                                <label class="radio-inline">
                                    <input name="prefixegroup" type="radio" id="active_lifestyle" value="active_lifestyle">หนัก (วิถีชีวิตมีการใช้แรงงานหนัก ออกกำลังกายหนักเป็นประจำ)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="index.php?userid=" method="POST">
                <center><input class="button" type="submit" value="ตกลง" />
                </form>
            </form> <!-- /form -->
        </div> <!-- ./container -->

<img class="foot" src="{{URL::asset('css/bg_forest.png')}}" />