@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-year-view">
                <div class="page-header">
                    <div class="col-md-6">
                        <h5><b>船舶修理</b></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="repair_list_table">
                            <table id="repair_info_table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="center" style="width: 15%">船舶名称</th>
                                    <th class="center" style="width:10%">航次号码</th>
                                    <th class="center" style="width: 10%">从</th>
                                    <th class="center" style="width: 10%">到</th>
                                    <th class="center" style="width: 20%">地点</th>
                                    <th class="center" style="width: 10%">区分</th>
                                    <th class="center" style="width: 15%">领导</th>
                                    <th class="center" style="width: 10%">金额</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($RepairInfos as $RepairInfo)
                                    <tr class="center">
                                        <td class="center">{{$RepairInfo['shipName_Cn']}}</td>
                                        <td class="center">{{$RepairInfo['Voy_No']}}</td>
                                        <td class="center">{{$RepairInfo['FromDate']}}</td>
                                        <td class="center">{{$RepairInfo['ToDate']}}</td>
                                        <td class="center">{{$RepairInfo['Place']}}</td>
                                        <td class="center">{{$RepairInfo['RepairKind']}}</td>
                                        <td class="center">{{$RepairInfo['D_Officer']}}</td>
                                        <td class="center">{{$RepairInfo['Amount']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(function() {
        $("#repair_info_table").dataTable( {
            "aoColumns": [null, null, null, null, null, null, null, null],
            "iDisplayLength":10
        });

    });
</script>

@endsection

