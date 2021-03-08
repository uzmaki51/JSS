@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>기초자료입력</b>
                        <small id="parent_Item">
                            <i class="icon-double-angle-right"></i>
                            AC자료관리
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    @if(!$isHolder)
                        <div id="main_type_modify">
                            <form class="form-horizontal" action="addACType" method="POST" id="form_type">
                                <input type="text" class="hidden" name="AC_type_id" value="">
                                <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center">종류</th>
                                        <th class="center">종류(영문)</th>
                                        <th class="center">수입 및 지출</th>
                                        <th class="center" style="width:120px">설명</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><input type="text" name="AC_Item_Cn" class="form-control" value=""></td>
                                        <td><input type="text" name="AC_Item_En" class="form-control" value=""></td>
                                        <td>
                                            <select name="C_D" class="form-control">
                                                <option value=""></option>
                                                <option value="Credit">Credit</option>
                                                <option value="Debit">Debit</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="AC_description" class="form-control" value=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div style="padding-right: 20px;padding-bottom:10px;text-align: right">
                                    <button type="submit" class="btn btn-sm btn-info no-radius" style="width: 80px"><i class="icon-plus-sign-alt"></i>추가</button>
                                </div>                        </form>
                        </div>
                    @endif
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="lighter smaller col-md-11">AC</h4>
                            <div class="widget-toolbar action-buttons col-md-2">
                            </div>                        </div>
                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th style="width: 10%;" class="center">No</th>
                                        <th style="width: 20%;" class="center">종류</th>
                                        <th style="width: 20%;" class="center">종류(영문)</th>
                                        <th style="width: 10% !important;" class="center">수입지출</th>
                                        <th class="center">설명</th>
                                        @if(!$isHolder)
                                            <th class="center" style="width:65px"></th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody id="AC_Item">
									<?php $index = 1; ?>
                                    @foreach($ACList as $Item)
                                        <tr>
                                            <td class="center">{{$index}}</td>
                                            <td class="hidden">{{$Item['id']}}</td>
                                            <td>{{$Item['AC_Item_Cn']}}</td>
                                            <td>{{$Item['AC_Item_En']}}</td>
                                            <td>{{$Item['C_D']}}</td>
                                            <td><a class="simple_text hide-option" style="width:100px;padding-top:4px;cursor: pointer" title="{{$Item['AC_description']}}">{{$Item['AC_description']}}</a></td>
                                            @if(!$isHolder)
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="#parent_Item" class="blue type_edit">
                                                            <i class="icon-edit bigger-130"></i>
                                                        </a>

                                                        <a class="red type_del">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
										<?php $index++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6 modify_dialog input" id="children_item">
                        <div id="sub_type_modify">
                            <form class="form-horizontal" action="addACDetail" method="POST" id="form_device">
                                <input type="hidden" name="AC_Item_Id" value="">
                                <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">

                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center">종류</th>
                                        <th class="center">항목</th>
                                        <th class="center">Abb</th>
                                        <th class="center">참고번호</th>
                                        <th class="center">설명</th>
                                        <th class="center">Order</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <input type="hidden" name="AC_Item_Detail_Id">
                                        <td style="white-space: nowrap" id="AC_Item_name"></td>
                                        <td><input type="text" name="AC_Item_Detail_Cn" class="form-control" value=""></td>
                                        <td><input type="text" name="AC_Item_Detail_Abb" class="form-control" value=""></td>
                                        <td><input type="text" name="AC_Item_Detail_Referance" class="form-control" value=""></td>
                                        <td><input type="text" name="AC_Detail_Item_Description" class="form-control" value=""></td>
                                        <td><input type="text" name="Order_No" class="form-control" value=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div style="padding-right: 20px;padding-bottom:10px;text-align: right">
                                    <button type="submit" class="btn btn-sm btn-primary save_sub_btn">추가</button>
                                </div>
                            </form>
                        </div>
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4 class="lighter smaller col-md-4">AC Detail</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-striped table-bordered table-hover" id="equip_table">
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th class="center">No</th>
                                            <th class="center">종류</th>
                                            <th class="center">항목</th>
                                            <th class="center">Abb</th>
                                            <th class="center">참고번호</th>
                                            <th class="center">설명</th>
                                            <th class="center">Order</th>
                                            <th class="center" style="width:65px"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="AC_Detail">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>

        var token = '{!! csrf_token() !!}';

        $(function() {

            $('.type_edit').on('click',function(){
                var obj = $(this).closest('tr').children();
                $('[name=AC_type_id]').val(obj.eq(1).text());
                $('[name=AC_Item_Cn]').val(obj.eq(2).text());
                $('[name=AC_Item_En]').val(obj.eq(3).text());
                $('[name=C_D]').val(obj.eq(4).text());
                $('[name=AC_description]').val(obj.eq(5).text());
                $('.AC_Item_save').text('보관');
            });

            $('.type_del').on('click',function(){
                var tr = $(this).closest('tr');
                var obj = tr.children();
                var typeId = obj.eq(1).text();
                var typeName = obj.eq(2).text();
                bootbox.confirm(typeName + "를 삭제하겠습니까?", function (result) {
                    if (result) {
                        //확인단추를 눌렀을 때의 처리
                        $.post('deleteACType', {'_token':token, 'typeId':typeId}, function (result) {
                            tr.fadeOut();
                        });
                    }
                });
            });

            $('#AC_Item td').on('click', function(){
                $('#AC_Item tr').removeClass('table-row-selected');
                $('#children_item').show();
                var tr = $(this).parent();
                var typeId = tr.find('td').eq(1).text();
                $.post('loadACDetail', {'_token':token, 'typeId':typeId}, function (result) {
                    var temp = result.split('*****');
                    $('#AC_Detail').html(temp[0]);
                    $('#AC_Item_name').text(temp[1]);
                    tr.addClass('table-row-selected');
                    $('input[name="AC_Item_Id"]').val(tr.find('td').eq(1).text());
                    bindSubTypeEvent();
                });
            });
        })

        function bindSubTypeEvent() {
            $('.edit_sub_type').on('click',function(){
                var obj = $(this).closest('tr').children();
                $('[name="AC_Item_Detail_Id"]').val(obj.eq(1).text());
                $('[name="AC_Item_Detail_Cn"]').val(obj.eq(3).text());
                $('[name="AC_Item_Detail_Abb"]').val(obj.eq(4).text());
                $('[name="AC_Item_Detail_Referance"]').val(obj.eq(5).text());
                $('[name="AC_Detail_Item_Description"]').val(obj.eq(6).text());
                $('[name="Order_No"]').val(obj.eq(7).text());
                $('.save_sub_btn').text('보관');
            });

            $('.del_sub_type').on('click',function(){
                var tr = $(this).closest('tr');
                var typeId = $('input[name="AC_Item_Detail_Id"]').val();
                bootbox.confirm("정말 삭제하겠습니까?", function (result) {
                    if (result) {
                        //확인단추를 눌렀을 때의 처리
                        $.post('deleteACDetail', {'_token':token, 'typeId': typeId}, function (result) {
                            tr.fadeOut();
                        });
                    }
                });
            });

        }
    </script>
@endsection
