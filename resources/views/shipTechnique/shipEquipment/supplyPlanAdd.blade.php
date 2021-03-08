@extends('layout.sidebar')

@section('content')
    <script src="/assets/js/fuelux/fuelux.spinner.min.js"></script>
    <div class="main-content">
        <div class="page-content">
            <div class="page-year-view">
                <div class="page-header">
                    <div class="col-md-3">
                        <h5><b>공급계획등록</b></h5>

                    </div>
                    <div class="col-md-offset-6 col-md-3">
                        <a href="javascript: location.href='supplyplan?menuId=80'" style="float: right;font-size: 20px"><strong>이전페지</strong></a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-11">
                        <button type="button" id="sendBtn" class="btn btn-primary" style="float: right"><i
                                    class="icon-save"></i>등록
                        </button>
                    </div>

                    <div class="col-md-12">
                        <div class="space-2"></div>
                        <form role="form" method="POST"
                              action="{{url('shipTechnique/addSupplyPlan')}}"
                              enctype="multipart/form-data" id="Planform">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="submit" style="display: none">

                            <div class="col-md-12">
                                <table id="tbl_app" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <th class="center">년도</th>
                                        <th class="center">월</th>
                                        <th class="center">배이름</th>
                                        <th class="center">부문</th>
                                        <th class="center">계획내용</th>
                                        <th class="center" style="width: 80px">수량</th>
                                        <th class="center">비고</th>
                                    </thead>
                                    <tbody>
                                        <td class="center">
                                            <select class="form-control" id="year" name="year">
                                                @for($year=2015; $year<intval(date('Y'))+4; $year++)
                                                <option value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td class="center">
                                            <select class="form-control" name="month" id="month">
                                                @for($month=1;$month<13; $month++)
                                                    <option value="{{$month}}">{{$month}}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td class="center">
                                            <select class="form-control" id="shipName" name="shipName">
                                                <option value=""></option>
                                                @foreach($shipInfos as $shipInfo)
                                                    <option value="{{$shipInfo['RegNo']}}">{{$shipInfo['shipName_Cn']}}</option>
                                                    @endforeach
                                            </select>
                                        </td>
                                        <td class="center">
                                            <select class="form-control" id="dept" name="dept">
                                                <option value=""></option>
                                                @foreach($deptInfos as $deptInfo)
                                                    <option value="{{$deptInfo['id']}}">{{$deptInfo['Dept_Cn']}}</option>
                                                    @endforeach
                                            </select>
                                        </td>
                                        <td class="center">
                                            <input type="text" id="PlanContent" name="PlanContent">
                                        </td>
                                        <td class="center">
                                            <input type="text" class="input-mini spinner-input form-control" id="PlanAmount" name="PlanAmount">
                                        </td>
                                        <td class="center">
                                            <textarea name="PlanRemark" id="PlanRemark" class="form-control">

                                            </textarea>
                                        </td>
                                    </tbody>
                                </table>

                            </div>

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        var token = '<?php echo csrf_token() ?>';
        $(function () {
            $('#year').css({height:'50px',width:'100%'});
            $('#month').css({height:'50px',width:'100%'});
            $('#shipName').css({height:'50px',width:'100%'});
            $('#dept').css({height:'50px',width:'100%'});
            $('#PlanContent').css({height:'50px',width:'100%'});
            $('#PlanAmount').css({height:'50px',width:'100%'});
        });
        $('#PlanAmount').ace_spinner({value:0,min:0,max:10000,step:10, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
                .on('change', function(){
                    //alert(this.value)
                });
        $('#sendBtn').click(function(){
            $('#Planform').submit();
        });
    </script>

@endsection

