<div class="space-6"></div>
<div>
<div class="col-md-6" style="padding:0px; padding-left:5px">
    <select class="col-md-2" id="" onchange="changeManageTable($(this))">
        <option value="0">{{transBusinessManage("captions.all")}}</option>
        <option value="1">{{transBusinessManage("captions.complete")}}</option>
        <option value="2">{{transBusinessManage("captions.nocomplete")}}</option>
    </select>
</div>
<div style="padding-right:5px; float:right;">
    <button class="btn btn-primary btn-sm" id="btn-add-tema" style="border-radius: 3px;margin-right: 10px;width :80px">
        <i class="icon-plus-sign-alt"></i>{{transBusinessManage("captions.add")}}
    </button>
</div>
</div>
<div class="space-6" style="width:100%"></div>

<div style="text-align: right">
    <a href="#" id="btn-show-tema" class="hidden"> </a>
    <div id="dialog-add-modify-tema" class="hide" >
        <form class="form-horizontal">
            <input type="hidden" id="sub_plan_Id" value="0">
            <div class="form-group" style="margin-bottom: 5px">
                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.schedulename")}}:</label>
                <div class="col-md-8">
                    <select id="plan-name" class="form-control" style="width: 100%;" value="">
                        @foreach($main_plans as $plan)
                            <option value="{{$plan->id}}">{{$plan->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
			<div class="form-group">
				<label class="col-md-3 control-label no-padding-right"></label>
				<div class="col-md-8" style="color:#989898">
				    @if(count($main_plans))
					<span id="main_plan_date">{{transBusinessManage("captions.scheduleperiod")}}: {{convert_date($main_plans[0]->startDate)}}&nbsp;~&nbsp;{{convert_date($main_plans[0]->endDate)}}</span>
					@else
					<span>{{transBusinessManage("captions.msg_no_main_schedule")}}</span>
					@endif
				</div>
			</div>
            <div class="form-group">
                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.taskname")}}:</label>
                <div class="col-md-8"><input type="text" id="task-name" class="form-control"
                                             style="width: 100%" value=""></div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.explain")}}:</label>
                <div class="col-md-8" ><textarea id="task-desc" class="form-control"
                                                 style="width: 100%"></textarea></div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.task_startdate")}}:</label>
                <div class="col-md-8">
                    <div class="input-group" style="width: 80%">
                        <input id="task-start-date" class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd">
                        <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.task_completedate")}}:</label>
                <div class="col-md-8">
                    <div class="input-group" style="width: 80%">
                        <input id="task-end-date" class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd">
                        <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                    </div>
                </div>
            </div>
            {{-- 색갈 --}}
            <div class="form-group">
                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.color")}}:</label>
                <div class="col-md-8">
                    <div class="btn-color-group">
                        <label class="btn btn-custom-danger active">
                            <input type="radio" class="btn-color" value="F9B6AC" style="visibility: hidden;">
                        </label>
                        <label class="btn btn-custom-blue">
                            <input type="radio" class="btn-color" value="BDDEF9" style="visibility: hidden;">
                        </label>
                        <label class="btn btn-custom-green">
                            <input type="radio" class="btn-color" value="BDF9C4" style="visibility: hidden;">
                        </label>
                        <label class="btn btn-custom-pink">
                            <input type="radio" class="btn-color" value="F9BDE9" style="visibility: hidden;">
                        </label>
                        <label class="btn btn-custom-yellow">
                            <input type="radio" class="btn-color" value="F0F5AA" style="visibility: hidden;">
                        </label>
                    </div>
                </div>
            </div>

            <p id="plan_message_out" style="text-align: center; color: red; "></p>
        </form>
    </div><!-- #dialog-message -->
</div>

<div class="row" style="margin: 0px;">
    <div style="overflow-y: scroll; width: 100%">
        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
            <thead class="table-head">
            <tr class="black br-hblue">
                <th class="center" style="width: 5%;">{{transBusinessManage("captions.no")}}</th>
                <th class="center" style="width: 10%;">{{transBusinessManage("captions.schedulename")}}</th>
                <th class="center" style="width: 10%;">{{transBusinessManage("captions.task")}}</th>
                <th class="center" style="width: 7%;">{{transBusinessManage("captions.startdate")}}</th>
                <th class="center" style="width: 7%;">{{transBusinessManage("captions.completedate")}}</th>
                <th class="" style="width: 50%;">{{transBusinessManage("captions.explain")}}</th>
                <th class="center" style="width: 3%;">{{transBusinessManage("captions.color")}}</th>
                <th style="width: 5%;"></th>
            </tr>
            </thead>
        </table>
    </div>
    <div id="PlanManageDiv" style="overflow-x:hidden; overflow-y: auto; width:100%; height:58vh; border-bottom: 1px solid #eee">
        @include('business.plan.plan_manage_table', with(['sub_plan_list'=>$sub_plan_list]))
    </div>
</div>

<script>

    $(function(){
        // 주보...
        $('.btn-color').on('click', function(){
            $('.btn-color-group .btn').removeClass('active');
            $(this).closest('label').addClass('active');
        });

        $('#btn-show-tema' ).on('click', function(e) {

            e.preventDefault();

            var dialog = $( "#dialog-add-modify-tema" ).removeClass('hide').dialog({
                modal: true,
                title: "添加 任务",
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class" : "btn btn-xs",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "保存",
                        "class" : "btn btn-primary btn-xs",
                        click: function() {
                            var planId = $('#sub_plan_Id').val();
                            var newPlan = $("#plan-name").val();
                            var newName = $("#task-name").val();
                            var newDesc = document.getElementById("task-desc").value;
                            var newStart = $("#task-start-date").val();
                            var newEnd = $("#task-end-date").val();
                            var btnColor = $('.btn-color-group .active').find('input').val();
                            var err_flag=0;
                            if (newPlan == null ) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "管理者还没有设置了计划。";
                            }
                            if (newName.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入任务的名称。";
                            }
                            if (newDesc.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入关于任务的说明。";
                            }
                            if (newStart.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入任务的开始时间。";
                            }
                            if (newEnd.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入任务的完成时间。";
                            }
                            var startdate = new Date(newStart);
                            var enddate = new Date(newEnd);
                            var curdate = new Date();
                            curdate = new Date(curdate.toDateString());
                            if ( startdate > enddate )
                            {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "错误输入任务的执行期间。";
                            }
                            if ( enddate < curdate )
                            {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "任务完成日期已经过了。" ;
                            }
                            if ( err_flag ==1 ){ return; }

                            $.post("addSubTask", {
                                '_token': token, 'planId':planId, 'plan': newPlan,
                                'name': newName, 'desc': newDesc,
                                'start': newStart, 'end': newEnd, 'btn_color' : btnColor
                            }, function ( data ) {
                                var returnCode = parseInt( data['err'] );
                                if ( returnCode == 1 ) {
                                    window.location.reload();
                                } else if (returnCode == -1) {
                                    document.getElementById("plan_message_out").innerHTML = "设置的任务项目已经存在。";
                                    return;
                                } else if (returnCode == -2) {
                                    var plan = data['plan'];
                                    document.getElementById("plan_message_out").innerHTML = "错误设置日期时间设置。<br/> 这计划要从" +
                                        plan['startDate'] + "到" + plan['endDate'] + "完成。" ;
                                    return;
                                } else if (returnCode == -4) {
                                    document.getElementById("plan_message_out").innerHTML = "你选择的任务项目不存在。";
                                    return;
                                }
                            });

                        }
                    }
                ]
            });
            document.getElementById("plan_message_out").innerHTML = "";
        });

        $('#btn-add-tema').on('click', function () {
            $('#sub_plan_Id').val('0');
            $('#plan-name').val('');
            $('#task-name').val('');
            $('#task-start-date').val('');
            $('#task-end-date').val('');
            $('#task-desc').val('');
            $('#btn-show-tema').click();
        });

        $('.plan-edit-btn').on('click', function () {
            var row = $(this).closest('tr').children();
            $('#sub_plan_Id').val(row.eq(0).data('id'));
            $('#plan-name').val(row.eq(1).data('main'));
            $('#task-name').val(row.eq(2).text());
            $('#task-start-date').val(row.eq(3).text());
            $('#task-end-date').val(row.eq(4).text());
            $('#task-desc').val(row.eq(5).text());

            var color = row.eq(6).data('color');
            var className = 'btn-custom-danger';
            if(color == 'F9B6AC') {     // danger
                className = 'btn-custom-danger';
            } else if(color == 'BDDEF9') { // blue
                className = 'btn-custom-blue';
            } else if(color == 'BDF9C4') { // green
                className = 'btn-custom-green';
            } else if(color == 'F9BDE9') { // pink
                className = 'btn-custom-pink';
            } else if(color == 'F0F5AA') { // yellow
                className = 'btn-custom-yellow';
            }

            var colorObj = $('.btn-color-group').children();
            for(var i=0;i<colorObj.length;i++){
                var colorRow = colorObj.eq(0);
                colorRow.find('input').removeAttr('checked');
                colorRow.removeClass('active');
            }

            $('.btn-color-group').find('.' + className).addClass('active');

            $('#btn-show-tema').click();

        });

        $('.plan-del-btn').on('click', function () {
            var row = $(this).closest('tr').children();
            var trObj = $(this).closest('tr');

            var taskName = row.eq(2).text();
            var taskId = row.eq(0).data('id');

            bootbox.confirm('[' + taskName + ']  ' + "  真要删除任务吗？", function (result) {
                if (result) {
                    $.post('deleteSubTask', {'_token': token, 'taskId': taskId}, function (data) {
                        if (data['state'] == 1) {
                            trObj.remove();
                            $.gritter.add({
                                title: '成功',
                                text: data['msg'],
                                class_name: 'gritter-success'
                            });
                        } else if (data['state'] < 0) {
                            $.gritter.add({
                                title: '错误',
                                text: data['msg'],
                                class_name: 'gritter-error'
                            });
                        }
                    });
                }
            });
        });
    });

    function changeManageTable(that) {
        var value = that.val();
        $.post('changeMangeTable', {'_token': token, 'type': value}, function(data) {
            $('#PlanManageDiv').html(data);
        })
    }
</script>

