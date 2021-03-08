@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <script src="/KindEditor/kindeditor.js"></script>
        <script src="/KindEditor/lang/zh_CN.js"></script>
        <script src="/KindEditor/plugins/code/prettify.js"></script>
        <div class="page-content">
            <div class="page-header">
                <h4 class="center"><b>배수리관련기록</b></h4>
            </div>
            <div class="col-md-12">
                <form role="form" method="POST" action="updateRepair" id="correct-add-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="text" class="hidden" name="id" value="{{$recovery['id']}}">
                    <div class="col-md-12">
                        <div class="table-responsive" id="repair_list_table">
                            <table class="table table-bordered">
                                <tbody class="center">
                                    <tr>
                                        <td class="td-title">배이름 <span class="require">*</span></td>
                                        <td style="width: 15%">
                                            @if($readonly)
                                                <label class="form-control">{{$recovery['shipName_Cn']}}</label>
                                            @else
                                                <select name="ShipId" class="form-control">
                                                    @foreach($shipList as $ship)
                                                        <option value="{{$ship['RegNo']}}" @if ($ship['shipName_Cn']==$recovery['shipName_Cn']) selected @endif>
                                                            {{$ship['shipName_Cn']}} @if(!empty($ship['name'])) | {{$ship['name']}} @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                        <td class="td-title">항차번호 <span class="require">*</span></td>
                                        <td id="Voy" style="width: 15%">
                                            @if($readonly)
                                                <label class="form-control">{{$recovery['Voy_No']}}</label>
                                            @else
                                                <select name="VoyId"  class="form-control">
                                                    @foreach($cps as $cp)
                                                        <option value="{{$cp['CP_No']}}" @if(isset($recovery) && ($recovery['VoyId']==$cp['CP_No'])) selected @endif>
                                                            {{$cp['Voy_No']}} | {{$cp['CP_No']}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                        <td class="td-title">부터<span class="require">*</span></td>
                                        <td style="width:15%">
                                            @if($readonly)
                                                <label class="form-control">{{convert_date($recovery['FromDate'])}}</label>
                                            @else
                                                <div class="input-group" >
                                                    <input class="form-control date-picker" name="FromDate"
                                                           type="text" data-date-format="yyyy/mm/dd"
                                                           value="{{convert_date($recovery['FromDate'])}}">
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="td-title">까지<span class="require">*</span></td>
                                        <td style="width:15%">
                                            @if($readonly)
                                                <label class="form-control">{{convert_date($recovery['ToDate'])}}</label>
                                            @else
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="ToDate"
                                                           type="text" data-date-format="yyyy/mm/dd"
                                                           value="{{convert_date($recovery['ToDate'])}}">
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">장소 <span class="require">*</span></td>
                                        <td>
                                            @if($readonly)
                                                <label class="form-control">{{$recovery['Place']}}</label>
                                            @else
                                                <input type="text" name="Place" class="form-control" value="@if(isset($recovery)){{$recovery['Place']}} @endif">
                                            @endif
                                        </td>
                                        <td class="td-title">구분 <span class="require">*</span></td>
                                        <td>
                                            @if($readonly)
                                                <label class="form-control">
                                                @if($recovery['RepairKind'] == 1)
                                                    정기
                                                @elseif($recovery['RepairKind'] == 2)
                                                    돌발
                                                @elseif($recovery['RepairKind'] == 3)
                                                    자체
                                                @endif
                                                </label>
                                            @else
                                                <select name="RepairKind" class="form-control">
                                                    <option value="1" @if(isset($recovery) && ($recovery['RepairKind']==1)) selected @endif>정기</option>
                                                    <option value="2" @if(isset($recovery) && ($recovery['RepairKind']==2)) selected @endif>돌발</option>
                                                    <option value="3" @if(isset($recovery) && ($recovery['RepairKind']==3)) selected @endif>자체</option>
                                                </select>
                                            @endif
                                        </td>
                                        <td class="td-title">지도성원</td>
                                        <td>
                                            @if($readonly)
                                                <label class="form-control">{{$recovery['D_Officer']}}</label>
                                            @else
                                                <input type="text" name="D_Officer" class="form-control" value="@if(isset($recovery)){{$recovery['D_Officer']}}@endif">
                                            @endif
                                        </td>
                                        <td class="td-title">금액 <span class="require"></span></td>
                                        <td>
                                            @if($readonly)
                                                <label class="form-control">{{$recovery['Amount']}}</label>
                                            @else
                                                <input type="number" name="Amount" class="form-control" value="@if(isset($recovery)){{$recovery['Amount']}}@endif">
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                             </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="td-title">수리내용 <span class="require">*</span></td>
                                        <td>
                                            @if($readonly)
                                                {{$recovery['Content']}}
                                            @else
                                                <input type="text" name="Content" class="form-control" style="width:100%"
                                                       value="@if(isset($recovery)) {{$recovery['Content']}} @endif">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">상세내용</td>
                                        <td>
                                            <textarea name="Detail" class="form-control" style="height:210px">
                                                @if(isset($recovery)) {{$recovery['Detail']}} @endif
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">첨부화일</td>
                                        <td>
                                            <input type="file" name="attachFile" id="attachFile"
                                                   style="display: none;">
                                            <input type="hidden" id="AddFileServerPath" name="AddFileServerPath">
                                            @if($readonly)

                                            @else
                                                <button type="button" id="openFile">
                                                    <i class="icon-folder-open-alt"></i>
                                                    파일선택
                                                </button>
                                            @endif
                                            <a href="/fileDownload?type=repaire&path={{$recovery['AddFileServerPath']}}" style="font-size: 14px">{{$recovery['AddFileServerPath']}}</a>
                                        </td>
                                    </tr>

                                </tbody>
                             </table>
                            @if($readonly==0)
                                <div style="text-align: center">
                                    <button type="submit" class="btn btn-inverse btn-sm" style="width: 80px">
                                        <i class="icon-save"></i>
                                        등록
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
             </div>
        </div>
    </div>

<script type="text/javascript">
    var token='<?php echo csrf_token()?>';
    $(function () {
        editor = KindEditor.create('textarea[name="Detail"]', {
            cssPath: '/KindEditor/plugins/code/prettify.css',
            newlineTag: 'br',
            allowPreviewEmoticons: false,
            allowImageUpload: false,
            @if($readonly)readonlyMode:true,@endif                                                                                                            {{--읽기전용--}}
            designMode:'on',
            items: ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'link']
        });

        $("#correct-add-form").validate({
            rules: {
                ShipId : "required",
                VoyId: "required",
                FromDate: "required",
                ToDate: "required",
                Place: "required",
                RepairKind: "required",
                Content: "required",
            },
            messages: {
                ShipId : "배이름을 선택하십시오",
                VoyId: "항차번호를 선택하십시오.",
                FromDate: "배수리날자를 입력하십시오.",
                ToDate: "배수리날자를 입력하십시오.",
                Place: "배수리 장소를 입력하십시오.",
                RepairKind: "배수리종류를 선택하십시오.",
                Content: "배수리 내용을 입력하십시오.",
            }
        });

        $('#openFile').click(function () {
            $('#attachFile').trigger('click');
        });

        $('#attachFile').change(function () {
            document.getElementById('AddFileName').innerText = $(this).val();
            $('#AddFileServerPath').val($(this).val());
        });

        $('[name=ShipId]').change(function(){
            var shipId=$(this).val();

            $.post('getVoyListSearch', {_token:token,shipId:shipId}, function(data){
                $('#Voy').html(data);
            });
        });
    });



</script>

@endsection

