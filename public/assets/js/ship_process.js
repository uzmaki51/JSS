/**
 * Created by ChoeMunBong on 2017/5/11.
 */



$(function () {
    $('.date-picker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    }).next().on(ace.click_event, function () {
        $(this).prev().focus();
    });

    $('.chosen-select').chosen();
    // setInterval(function () {
    //     checkNewDecideReport();
    //     checkNewRecvDecideReport();
    // }, 200000);
    //
    // setInterval(function () {
    //     checkPersonSchedule();
    // }, 60000);

	$( ".hide-option" ).tooltip({
		hide: {
			effect: "slideDown",
			delay: 250
		}
	});

});

function checkNewDecideReport() {

    $.get('/decision/checkWillDecideDoc', {}, function (data) {
        var returnCode = parseInt(data);
        if(returnCode > 0) {
            $.gritter.add({
                title: '通报',
                text: '新的批准文件(' + returnCode + '个）收到了。',
                class_name: 'gritter-error'
            });

            myAlarm.play();

        }
    })
}

function checkNewRecvDecideReport() {

    $.get('/decision/checkRecvDecideDoc', {}, function (data) {
        var returnCode = parseInt(data);
        if(returnCode > 0) {
            $.gritter.add({
                title: '通报',
                text: returnCode + '개의 결재문서가 수신됐습니다.',
                class_name: 'gritter-error'
            });
            myAlarm.play();
        }
    })
}

function checkPersonSchedule() {
    $.get('/business/checkPersonSchedule', {}, function (data) {
        for(var i=0; i<data.length;i++){
            var schedule = data[i];
            $.toast.add({
                title: '通报',
                text: schedule.startTime.substr(0,5) + '에 ' + schedule.title + ' 이 있습니다.',
                class_name: 'gritter-success'
            });
            myAlarm.play();
        }
    })
}

function formatNumber(value) {
    var parts = value.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    if (parts[1] != undefined) {
        parts[1] = parts[1].replace(/0+$/g,''); // remove 0 digit from back-end
    }

    var str = parts[0] + '';
    return str;
}

//$("input[type=number]").attr("step","0.01");
$("input[name=orderNum]").attr("step","1");

//해당배렬자료를 해당url에로 submit하는 함수
function submitData(url, data, method, target) {
    if( url && data ){
        //data can be string of parameters or array/object
        data = typeof data == 'string' ? data : jQuery.param(data);
        //split params into form inputs
        var inputs = '';
        jQuery.each(data.split('&'), function(){
            var pair = this.split('=');
            inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />';
        });

        //send request
        jQuery('<form action="'+ url +'" '+target+' method="'+ (method||'post') +'">'+inputs+'</form>')
            .appendTo('body').submit().remove();
    };
}