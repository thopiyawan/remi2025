// function updateTextInput(val) {
//     // console.log(val)
//     document.getElementById('sugar-data').innerHTML=val; 
// }
// function get_scroll(){
//     // $('#sugar-data').html($('#blood_sugar').val());
//     var rangeValueop = document.getElementById("blood_sugar");
//     var rangeValue = document.getElementById("sugar-data")
//     rangeValue.innerHTML = rangeValueop.value;
// }
$(document).ready(function () {
    var i = 1;
    var length;
    var addamount = 700;

    $("#add2").click(function () {
        addamount += 700;
        // console.log('amount: ' + addamount);
        i++;
        $('#dynamic_field').append('<div class="wrap-group-selected" id="row2' + i + '"><input type="text" name="moreFields[' + i + '][food_name]" placeholder="ส่วนประกอบ"><input type="number" name="moreFields[' + i + '][portion]" placeholder="ปริมาณ"><select name="moreFields[' + i + '][unit]"><option value="1" selected>ทัพพี</option><option value="2">ช้อน</option><option value="3">ช้อนโต๊ะ</option><option value="4">ลูก</option><option value="5">ฟอง</option><option value="6">ตัว</option><option value="7">มล.</option><option value="8">ชิ้น</option><option value="9">อื่นๆ</option></select><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-txt btn_remove2">x</button></div>');
    });

    $(document).on('click', '.btn_remove2', function(){  
        addamount -= 700;
        var button_id = $(this).attr("id");
        $('#row2' + button_id ).remove();
    });

    $(document).on('click', '.btn-group', function(){  
        $('.btn-group .btn').toggleClass("inactive");
        var inactive_class = $(this).find(".inactive").attr("data-value")
        console.log(inactive_class)
        $('.section').removeClass('hidden');
        $(".section-"+inactive_class).addClass("hidden");
    });

    $(document).on('click', '.wrap-group-selected.meal .group-selected', function(){  
        $('.wrap-group-selected.meal .group-selected').removeClass('active');
        $(this).addClass("active");
        // console.log($(this).attr("value"))
        $('#meal').val($(this).attr("value"))

    });

    $(document).on('click', '.wrap-group-selected.vitamin .group-selected', function(){  
        $('.wrap-group-selected.vitamin .group-selected').removeClass('active');
        $(this).addClass("active");
        $('#vitamin').val($(this).attr("value"))
    });

    $(document).on('click', '.wrap-group-selected.time .group-selected', function(){  
        $('.wrap-group-selected.time .group-selected').removeClass('active');
        $(this).addClass("active");
        
        switch ($(this).attr("value")) {
            case "1":
                $( '.sugar-range input[type=range]' ).css({
                    "background":"linear-gradient( to right,#ffde7b 33%, #1DC9A0 33% 47.5%, #ff5162 47.5% 100% )" 
                });
                $('#data-normal').attr('class','c1');
                $('#data-height').attr('class','c1');
                break;
            case "3":
                $( '.sugar-range input[type=range]' ).css({
                    "background":"linear-gradient( to right,#ffde7b 33%, #1DC9A0 33% 70%, #ff5162 70% 100% )" 
                });
                $('#data-normal').attr('class','c3');
                $('#data-height').attr('class','c3');
                break;
            case "4":
                $( '.sugar-range input[type=range]' ).css({
                    "background":"linear-gradient( to right,#ffde7b 33%, #1DC9A0 33% 60%, #ff5162 60% 100% )" 
                });
                $('#data-normal').attr('class','c4');
                $('#data-height').attr('class','c4');
                break;
        
        }
        $("[name='time_of_day']").val($(this).attr("value"));
    });

});

