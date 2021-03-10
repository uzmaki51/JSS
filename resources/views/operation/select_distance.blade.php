<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header no-padding">
            <div class="table-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="white">&times;</span>
                </button>
                Sail Distance Table
            </div>
        </div>
        <div class="modal-body no-padding">
            <div class="space-2"></div>
            <div class="row" style="margin: 0">
                <div class="col-md-4">
                    <div class="col-md-3" style="padding:7px 0;text-align: right">上传港口</div>
                    <div class="col-md-9">
                        <select class="form-control chosen-select" style="width:70%" id="l_port">
                            <option value=""></option>
                            @foreach($portList as $port)
                                <option value="{{$port->id}}">{{$port->Port_Cn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-3" style="padding:7px 0;text-align: right">下船港口</div>
                    <div class="col-md-9">
                        <select class="form-control chosen-select" style="width:70%" id="d_port">
                            <option value=""></option>
                            @foreach($portList as $port)
                                <option value="{{$port->id}}">{{$port->Port_Cn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button style="float:left" class="btn btn-sm btn-primary distance-filter">搜索</button>
            </div>
            <div class="space-2"></div>
            <div class="row" style="margin: 0" id="distance-div">
                <div style="overflow-y: scroll; width: 100%">
                <table class="table table-striped table-bordered table-hover no-margin-bottom">
                    <thead>
                    <tr class="black br-hblue">
                        <th style="width:5%"></th>
                        <th style="width:19%">Dept港口</th>
                        <th style="width:19%">上船港口</th>
                        <th style="width:19%">Arvd港口</th>
                        <th style="width:19%">下船港口</th>
                        <th style="width:19%">距离[mile]</th>
                    </tr>
                    </thead>
                </table>
                </div>
                <div style="overflow-x:hidden; overflow-y:auto; width:100%; height:350px; border-bottom: 1px solid #eee" id="table_data">
                    @include('operation.distance_table', ['distanceList'=>$distanceList])
                </div>
            </div>
        </div>
        <div class="modal-footer no-margin-top">
            <button class="btn btn-sm btn-danger pull-left btn-confirm" data-dismiss="modal">
                <i class="icon-check"></i>
                确认
            </button>
        </div>
    </div><!-- /.modal-content -->
</div>

<script>
    $(function () {
        $('.chosen-select').chosen();
        bindBtnClickEvent();

        $('.distance-filter').on('click', function () {
            var lPort = $('#l_port').val();
            var dPort = $('#d_port').val();
            $.get('betweenPortDistance', {'sPort':lPort, 'dPort':dPort}, function (data) {
                $('#table_data').html(data);
                bindBtnClickEvent();
            })
        })

    });
</script>