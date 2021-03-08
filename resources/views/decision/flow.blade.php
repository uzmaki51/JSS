@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transDecideManage("title.EnvironmentSetting")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>{{transDecideManage("title.ApproveFlow")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-4 form-group" style="padding:0">
                        <label class="col-md-2 control-label" style="padding:5px">{{transDecideManage("captions.processName")}}:</label>
                        <div class="col-md-9" style="padding:0">
                            <input type="text" class="form-control"
                                   id="search_flow_name" value="@if(isset($flow)){{$flow}}@endif" style="width:100%">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-primary no-radius" type="button" onclick="filterBySearchItems()" style="width: 80px">
                                <i class="icon-search"></i>
                                {{transDecideManage("captions.search")}}
                            </button>
                        </span>
                    </div>
                    <div class="col-sm-2" style="float:right;text-align: right">
                        <a href="{{ url('decision/flowadd') }}" class="btn btn-primary btn-sm" style="width: 80px"><i class="icon-plus-sign-alt"></i>{{transDecideManage("captions.add")}}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive" id="flow_list_table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width: 50px">{{transDecideManage("captions.no")}}</th>
                                <th class="center" style="width: 30%">{{transDecideManage("captions.approveProcessName")}}</th>
                                <th class="center">{{transDecideManage("captions.authorizer")}}</th>
                                <th class="center">{{transDecideManage("captions.receiver")}}</th>
                                <th class="center" style="width: 70px"></th>
                            </tr>
                            </thead>
                            <tbody id="flow-table">
                            @if (count($list) > 0)
                                <?php $index = ($list->currentPage() - 1) * $list->perPage() + 1;?>
                                @foreach ($list as $flowinfo)
                                    <tr>
                                        <td data-id="{{$flowinfo['id']}}">{{$index++}}</td>
                                        <td>{{  $flowinfo['title'] }}</td>
                                        <td>{{ $flowinfo['decideUserlist'] }}</td>
                                        <td>{{ $flowinfo['recvUserlist'] }}</td>
                                        <td class="action-buttons">
                                            <a href="flowadd?flowId={{$flowinfo['id']}}">
                                                <i class="blue icon-edit bigger-130"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="red del-btn">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>
        var pageNum = 0;
        var token = '<?php echo csrf_token() ?>';

        $(function () {

            $('.del-btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var flowId = obj.eq(0).data('id');
                var flowName = obj.eq(1).eq(0).html();

               bootbox.confirm('[ ' + flowName + ' ] 要删除批准流程吗?', function (result) {
                   if (result) {
                       //확인단추를 눌렀을 때의 처리
                       $.post('flowDelelte', {'_token':token, 'flow_id':flowId}, function (data) {
                           var result = jQuery.parseJSON(data);
                           if (result.status == 'success') {
                               var tbody = document.getElementById('flow-table');
                               var len = tbody.children.length;
                               var index = obj.eq(0).html();

                               var selRow = -1;
                               var rowIndex = 0;
                               for (var i = 0; i < len; i++) {
                                   var tds = tbody.children[i];
                                   var selIndex = tds.children[0].innerText;
                                   if (selIndex == index) {
                                       selRow = i;
                                       continue;
                                   }
                                   tbody.children[i].children[0].innerText = rowIndex + 1;
                                   rowIndex++;
                               }
                               if (selRow > -1)
                                   tbody.deleteRow(selRow);
                           } else {
                               $.gritter.add({
                                   title: '错误',
                                   text: result.status,
                                   class_name: 'gritter-error'
                               });
                           }
                       });
                   }
               });

           });
        });

        function filterBySearchItems() {
            var flow_name = $("#search_flow_name").val();

            var param = '';
            if(flow_name.length > 0)
                param = '?flow=' + flow_name;

            location.href = 'flowmanage' + param;
        }

    </script>
@stop
