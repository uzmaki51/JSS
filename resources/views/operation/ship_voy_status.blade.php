@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4>
                        <b>배동태</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            배동태상태관리
                        </small>
                    </h4>
                </div>

            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 input-group">
                            <span class="input-icon">
                            <input placeholder="상태이름" type="text" class="form-control" id="search_keyword" value="{{$status}}">
                            <i class="icon-search nav-search-icon"></i>
                            </span>
                        <span>
                                <button class="btn btn-sm btn-info no-radius" style="width: 80px" id="search-btn"><i class="icon-search" ></i>
                                    검색
                                </button>
                            </span>
                    </div>
                    @if(!$isHolder)
                        <div>
                            <button class="btn btn-sm btn-info no-radius" style="float:right; width :80px;" id="btn-add-status">
                                <i class="icon-plus" ></i>추가
                            </button>
                        </div>
                    @endif
                </div>
                <div class="row">
                    {{-- modify field --}}
                    <div class="space-4"></div>
                    <div class="add-status-box" style="display:none; padding:12px 12px 42px 12px;margin-bottom:12px;background-color:#fcf8e3; border: 1px solid #bce8f1;border-radius: 3px;">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="statusId" value="">
                        <table class="arc-std-table table table-bordered table-hover " style="margin-bottom: 8px;">
                            <tbody>
                            <tr>
                                <td style="background-color: #f7f7f7;">Voy_Status</td>
                                <td ><input type="text" name="Voy_Status" value=""></td>
                                <td style="background-color: #f7f7f7;">설명</td>
                                <td colspan="2"><input type="text" name="Descript" value=""></td>
                                <td style="background-color: #f7f7f7;">Description</td>
                                <td colspan="2"><input type="text" name="Descript_En" value=""></td>
                            </tr>
                            <tr>
                                <td style="background-color: #f7f7f7;">배동태항목</td>
                                <td><input type="text" name="Status_Name" value=""></td>
                                <td style="background-color: #f7f7f7;">경제일수관련</td>
                                <td>
                                    <select class="form-control chosen-select" name="Related_Economy">
                                        <option value="0">무효</option>
                                        @foreach($economy as $status)
                                            <option value="{{$status['id']}}">{{$status['Event']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="background-color: #f7f7f7;">비경제일수관련</td>
                                <td>
                                    <select class="form-control chosen-select" name="Related_UnEconomy">
                                        <option value="0">무효</option>
                                        @foreach($uneconomy as $status)
                                            <option value="{{$status['id']}}">{{$status['Event']}}</option>
                                        @endforeach
                                    </select>

                                </td>
                                <td style="background-color: #f7f7f7;">기타관련</td>
                                <td>
                                    <select class="form-control chosen-select" name="Related_Other">
                                        <option value="0">무효</option>
                                        @foreach($other as $status)
                                            <option value="{{$status['id']}}">{{$status['Event']}}</option>
                                        @endforeach
                                    </select>

                                </td>
                            </tr>
                            {{--@endforeach--}}
                            </tbody>
                        </table>
                        <div style="float: right;">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-save">수정</button>
                            <input class="btn btn-sm btn-info" style="width:60px" id="btn-close" value="닫기">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <table class="arc-std-table table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <td class="center">No</td>
                            <td class="center">Voy_status</td>
                            <td class="center">배동태항목</td>
                            <td class="center">설명</td>
                            <td class="center">Description</td>
                            <td class="center">경제일수관련</td>
                            <td class="center">비경제일수관련</td>
                            <td class="center">기타관련</td>
                            @if(!$isHolder)
                                <td style="width:60px"></td>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="status-table">
						<?php $index = ($list->currentPage() - 1) * 15 + 1; ?>
                        @foreach($list as $item)
                            <tr>
                                <td class="center">{{$index++}}</td>
                                <td class="center" data-id="{{$item['id']}}">{{ $item['Voy_Status'] }}</td>
                                <td class="center">{{$item['Status_Name']}}</td>
                                <td class="center">{{$item['Descript']}}</td>
                                <td class="center">{{$item['Descript_En']}}</td>
                                <td class="center" data-id="{{$item['Related_Economy']}}">{{$item['economyEvent']['Event']}}</td>
                                <td class="center" data-id="{{$item['Related_UnEconomy']}}">{{$item['uneconomyEvent']['Event']}}</td>
                                <td class="center" data-id="{{$item['Related_Other']}}">{{$item['otherEvent']['Event']}}</td>
                                @if(!$isHolder)
                                    <td class="action-buttons">
                                        <a href="javascript:void(0);" class="blue modify_btn"><i class="icon-edit bigger-130"></i></a>
                                        <a href="javascript:void(0);" class="red trash_btn"><i class="icon-trash bigger-130"></i></a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $list->render() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(function(e){

            var token = '<?php echo csrf_token() ?>';

            $('#search-btn').on('click', function(){
                var keyword = $('#search_keyword').val();
                var param = '?status=' + keyword;
                if(keyword.length < 1)
                    param = '';
                location.href = 'voyStatusManage' + param;
            });

            $('#btn-add-status').on('click', function(){
                $("[name=statusId]").val('');
                $("[name=Voy_Status]").val('');
                $("[name=Status_Name]").val('');
                $("[name=Descript]").val('');
                $("[name=Descript_En]").val('');
                $("[name=Related_Economy]").val('');
                $("[name=Related_UnEconomy]").val('');
                $("[name=Related_Other]").val('');
                $('.add-status-box').fadeIn();
            });

            /**/
            $('.modify_btn').on('click', function(){
                // replace content
                var obj = $(this).closest('tr').children();
                $(this).closest('tr').addClass('selectedRow');

                $("[name=statusId]").val(obj.eq(1).data('id'));
                $("[name=Voy_Status]").val(obj.eq(1).html());
                $("[name=Status_Name]").val(obj.eq(2).html());
                $("[name=Descript]").val(obj.eq(3).html());
                $("[name=Descript_En]").val(obj.eq(4).html());
                $("[name=Related_Economy]").chosen('destroy');
                $("[name=Related_UnEconomy]").chosen('destroy');
                $("[name=Related_Other]").chosen('destroy');
                $("[name=Related_Economy]").val(obj.eq(5).data('id'));
                $("[name=Related_UnEconomy]").val(obj.eq(6).data('id'));
                $("[name=Related_Other]").val(obj.eq(7).data('id'));
                $("[name=Related_Economy]").chosen();
                $("[name=Related_UnEconomy]").chosen();
                $("[name=Related_Other]").chosen();

                $('.add-status-box').fadeIn();
            });

            // update row data
            $('#btn-save').on('click', function(){
                // save in db
                var statusId = $("[name=statusId]").val();
                var statusName = $("[name=Voy_Status]").val();
                if(statusName.length < 1) {
                    $.gritter.add({
                        title: '오유',
                        text: '[Voy_Status]' + ' 마당을 반드시 입력하여야 합니다!',
                        class_name: 'gritter-error '
                    });
                    return;
                }
                $.post("updateVoyStatus", {
                    '_token': token,
                    "statusId":$("[name=statusId]").val(),
                    "Voy_Status":$("[name=Voy_Status]").val(),
                    "Status_Name":$("[name=Status_Name]").val(),
                    "Descript":$("[name=Descript]").val(),
                    "Descript_En":$("[name=Descript_En]").val(),
                    "Related_Economy":$("[name=Related_Economy]").val(),
                    "Related_UnEconomy":$("[name=Related_UnEconomy]").val(),
                    "Related_Other":$("[name=Related_Other]").val()
                }, function (data) {

                    var result = jQuery.parseJSON(data);

                    if (result.status == 'success') {
                        if(statusId == '') {
                            var tbody = document.getElementById('status-table');
                            var newtr = document.createElement('tr');
                            var leng = tbody.children.length;
                            if(leng < 1)
                                index = 1;
                            else
                                index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
                            var newHtml = '<td class="center">' + index + '</td>' +
                                '<td class="center" data-id="' + result.statusId + '">' + $("[name=Voy_Status]").val() + '</td>' +
                                '<td class="center">' + $("[name=Status_Name]").val() + '</td>' +
                                '<td class="center">' + $("[name=Descript]").val() + '</td>' +
                                '<td class="center">' + $("[name=Descript_En]").val() + '</td>' +
                                '<td class="center">' + $("[name=Related_Economy]").val() + '</td>' +
                                '<td class="center">' + $("[name=Related_UnEconomy]").val() + '</td>' +
                                '<td class="center">' + $("[name=Related_Other]").val() + '</td>' +
                                '<td class="action-buttons"><a href="javascript:void(0);" class="blue modify_btn"><i class="icon-edit bigger-130"></i></a>' +
                                '<a href="javascript:void(0);" class="red trash_btn"><i class="icon-trash bigger-130"></i></a></td>';
                            newtr.innerHTML = newHtml;
                            tbody.appendChild(newtr);
                        } else {
                            var selObj = $('.selectedRow').children();

                            selObj.eq(1).text($("[name=Voy_Status]").val());
                            selObj.eq(2).text($("[name=Status_Name]").val());
                            selObj.eq(3).text($("[name=Descript]").val());
                            selObj.eq(4).text($("[name=Descript_En]").val());
                            var selVal = $("[name=Related_Economy]").val();
                            if(selVal != '0')
                                selObj.eq(5).text($("[name=Related_Economy][selected=selected]").text());
                            else
                                selObj.eq(5).text('');
                            selVal = $("[name=Related_UnEconomy]").val();
                            if(selVal != '0')
                                selObj.eq(6).text($("[name=Related_UnEconomy][selected=selected]").text());
                            else
                                selObj.eq(6).text('');

                            selVal = $("[name=Related_Other]").val();
                            if(selVal != '0')
                                selObj.eq(7).text($("[name=Related_Other][selected=selected]").text());
                            else
                                selObj.eq(7).text('');

                            selObj.removeClass('selectedRow');
                        }
                        $('.add-status-box').fadeOut();
                        window.location.reload(true);
                    } else {
                        $.gritter.add({
                            title: '오유',
                            text: '['+ statusName + ']' + ' 상태이름이 중복되였습니다.',
                            class_name: 'gritter-error '
                        });
                    }
                });

            });
            // remove a row
            $('.trash_btn').on('click', function(){

                var obj = $(this).closest('tr').children();
                var statusId = obj.eq(1).data('id');
                var statusName = obj.eq(1).html();
                bootbox.confirm('[' + statusName + '] 상태를 삭제하겠습니까?', function(result) {
                    if(result) {
                        // save in db
                        $.post("removeVoyStatus", {'_token': token, 'statusId': statusId}, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                var tableBody = document.getElementById('status-table');
                                var rows = tableBody.children;
                                var len = rows.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var td = rows[row].children[1];
                                    var selId = td.getAttribute('data-id');
                                    if (selId == statusId)
                                        break;
                                }
                                tableBody.deleteRow(row);
                            } else {
                                $.gritter.add({
                                    title: '오유',
                                    text: '['+ statusName + ']' + ' 상태는 이미 삭제되였습니다.',
                                    class_name: 'gritter-error '
                                });
                            }
                        });
                    }
                });
            });


            $('#btn-close').on('click',function(){
                $('.add-status-box').fadeOut();
            });


        });
    </script>

@stop
