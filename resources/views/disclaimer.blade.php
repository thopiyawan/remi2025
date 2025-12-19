<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>คำสงวนสิทธิ์</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<!-- <link rel="stylesheet" href="css/stylecss_pploy.css" /> -->
<link rel="stylesheet" href="{{URL::asset('css/disc.css')}}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://static.line-scdn.net/liff/edge/versions/2.3.0/sdk.js"></script>

<style>
#sendmessagebutton {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}


#sendmessagebutton {border-radius: 4px;}

</style>
<body>           
    <div class="content card">
    <!--   <center> <h2> Disclaimer </h2></center>  -->
        <h4 align = 'left'>คำสงวนสิทธิ์</h4>
        <h6 align = 'left'>ทาง REMI จัดทำแชทบอตนี้ เพื่อให้ข้อมูลเกี่ยวกับการดูแลสุขภาพของคุณแม่ตลอดช่วงการตั้งครรภ์ คอยติดตามน้ำหนักและข้อมูลของทารกในแต่ละสัปดาห์ แนะนำด้านอาหารตามหลักโภชนาการ การออกกำลังกายในท่าต่างๆตามช่วงอายุครรภ์ และสามารถตอบคำถามที่คุณแม่อยากทราบในขณะตั้งครรภ์ได้ เพื่อช่วยให้คุณแม่ที่อยู่ในช่วงตั้งครรภ์สามารถมีผู้ดูแลใกล้ตัวได้สะดวก
 ไม่ว่าในขณะใดขณะหนึ่งก็ตาม REMI สามารถทำการแก้ไขปรับปรุงข้อมูลต่าง ๆ ที่ปรากฏแชทบอตนี้ได้</h6>
        <h4 align = 'left'>ข้อจำกัดความรับผิด</h4>
        <h6 align = 'left'>REMI ไม่มีความรับผิดในความเสียหายใด ๆ รวมตลอดถึงความเสียหายทางตรงความเสียหายทางอ้อม ความเสียหายพิเศษ ความเสียหายโดยบังเอิญ หรือความเสียหายเกี่ยวเนื่อง</h6>
        <center><button id="sendmessagebutton" >ยอมรับ</button></center>
               
        
    </div>

    <!-- LIFF SDK  -->
    <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
    <script >

      window.onload = function (e) {
        function runApp() {
                liff.getProfile().then(profile => {
                    document.getElementById("userId").value = profile.userId;
                }).catch(err => console.error(err));
            }
            liff.init({ liffId: "1656991660-Px9gqmQB" }, () => {
                if (liff.isLoggedIn()) {
                    runApp()
                } else {
                    liff.login();
                }
            }, err => console.error(err.code, error.message));
    // https://developers.line.me/ja/reference/liff/#liffopenwindow()



    document.getElementById('sendmessagebutton').addEventListener('click', function () {
        //https://developers.line.me/ja/reference/liff/#liffsendmessages()
        liff.sendMessages([{
            type: 'text',
            text: 'ยอมรับ'
        }]).then(() => {
            liff.closeWindow();
        })
        .catch(function (error) {
            window.alert("Error sending message: " + error);
        });
    });
};






</script>

</body>
</html>