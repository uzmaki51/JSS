@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <script src="/KindEditor/kindeditor.js"></script>
        <script src="/KindEditor/lang/zh_CN.js"></script>
        <script src="/KindEditor/plugins/code/prettify.js"></script>
        <div class="page-content">
            <div class="page-header">
                <h4 class="center"><b>배검사기록</b></h4>
            </div>

            <div class="col-md-12">
                <form role="form" method="POST" action="updateSurvey" id="correct-add-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="text" class="hidden" name="id" value="{{$recovery['id']}}">
                        <table id="Survey_info_table" class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="td-title" width="10%">배이름<span class="require">*</span></td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['shipName_Cn']}}</label>
                                        @else
                                            <select id="ShipId" name="ShipId">
                                                @foreach($shipList as $ship)
                                                    <option value="{{$ship['RegNo']}}"
                                                            @if($ship['shipName_Cn']==$recovery['shipName_Cn']) selected @endif>
                                                        {{$ship['shipName_Cn']}} @if(!empty($ship['name'])) | {{$ship['name']}} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td class="td-title" width="10%">항차번호<span class="require">*</span></td>
                                    <td width="10%">
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['Voy_No']}}</label>
                                        @else
                                            <div id="voy">
                                                <select name="VoyId" class="form-control">
                                                    @foreach($cps as $cp)
                                                        <option value="{{$cp['CP_No']}}"
                                                                @if(isset($recovery) && ($recovery['VoyId']==$cp['CP_No'])) selected @endif>
                                                            {{$cp['Voy_No']}} | {{$cp['CP_No']}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="td-title" width="10%">검사날자<span class="require">*</span></td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['SurveyDate']}}</label>
                                        @else
                                            <div class="input-group" >
                                                <input class="form-control date-picker" name="SurveyDate"
                                                       type="text" data-date-format="yyyy/mm/dd"
                                                       value="@if(isset($recovery)){{convert_date($recovery['SurveyDate'])}}@endif">
                                                <span class="input-group-addon">
                                                    <i class="icon-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="td-title" width="10%">장소<span class="require">*</span></td>
                                    <td width="15%">
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['Port_Cn']}}</label>
                                        @else
                                            <select class="form-control" name="PortId">
                                                @foreach($portnames as $portname)
                                                    <option value="{{$portname['id']}}" @if ($portname['Port_Cn'] == $recovery['Port_Cn']) selected @endif>
                                                        {{$portname['Port_Cn']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-title">종류<span class="require">*</span></td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['SurveyKind_Cn']}}</label>
                                        @else
                                            <select name="SurveyKindId" class="form-control">
                                                @foreach($surveykinds as $surveykind)
                                                    <option value="{{$surveykind['id']}}" @if ($surveykind['SurveyKind_Cn']==$recovery['SurveyKind_Cn']) selected @endif>
                                                        {{$surveykind['SurveyKind_Cn']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td class="td-title">검사원</td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['Surveyer']}}</label>
                                        @else
                                            <input type="text" name="Surveyer" id="Surveyer" class="form-control"
                                                   value="@if(isset($recovery)){{$recovery['Surveyer']}}@endif">
                                        @endif
                                    </td>
                                    <td class="td-title">검사기관</td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['Object']}}</label>
                                        @else
                                            <input type="text" name="Object" class="form-control"
                                                   value="@if(isset($recovery)){{$recovery['Object']}}@endif">
                                        @endif
                                    </td>
                                    <td class="td-title">검사비<span class="require">*</span></td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['Amount']}}</label>
                                        @else
                                            <input type="number" name="Amount" class="form-control" style="text-align:center;"
                                                   value="@if(isset($recovery)){{$recovery['Amount']}}@endif">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="Survey_info_table" class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="10%">검사내용<span class="require">*</span></td>
                                    <td>
                                        @if($readonly)
                                            <label class="form-control">{{$recovery['Content']}}</label>
                                        @else
                                            <input type="text" name="Content" class="form-control" style="width:100%"
                                                   value="@if(isset($recovery)) {{$recovery['Content']}} @endif">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>제기된 내용</td>
                                    <td class="custom-td-dec-text">
                                        <textarea id="Deficiency" name="Deficiency" class="form-control" style="width:100%;height:210px">
                                            @if(isset($recovery)) {{$recovery['Deficiency']}} @endif
                                        </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        첨부화일
                                    </td>
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
                                        <label id="AddFileName">@if(isset($recovery)) {{$recovery['AddFileServerPath']}} @endif</label>
                                    </td>
                                </tr>
                            </tbody>
                         </table>
                        @if($readonly==0)
                        <div style="text-align: center">
                            <button type="submit" class="btn btn-inverse btn-sm"><i class="icon-save"></i>등록</button>
                        </div>
                        @endif
                </form>
             </div>
        </div>
    </div>

<script type="text/javascript">
    var token='<?php echo csrf_token()?>';
    $(function () {
        editor = KindEditor.create('textarea[name="Deficiency"]', {
            cssPath: '/KindEditor/plugins/code/prettify.css',
            newlineTag: 'br',
            allowPreviewEmoticons: false,
            allowImageUpload: false,
            @if($readonly)readonlyMode:true,@endif                                                                                                            {{--읽기전용--}}
            items: ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'link']
        });

        $("#correct-add-form").validate({
            rules: {
                ShipId : 'required',
                VoyId : 'required',
                SurveyDate : 'required',
                PortId : 'required',
                SurveyKindId : 'required',
                Amount : 'required',
                Content : 'required',
            },
            messages: {
                ShipId : "배이름을 선택하십시오",
                VoyId : "항차번호를 선택하십시오.",
                SurveyDate : "배검사 날자를 입력하십시오.",
                PortId : "배검사 항구를 선택하십시오.",
                SurveyKindId : "배검사종류를 선택하십시오.",
                Amount : '배검사 비용을 입력하십시오.',
                Content : "배검사 내용을 입력하십시오.",
            }
        });

        $('#openFile').click(function () {
            $('#attachFile').trigger('click');
        });

        $('#attachFile').change(function () {
            document.getElementById('AddFileName').innerText = $(this).val();
            $('#AddFileServerPath').val($(this).val());
        });

        $('#ShipId').change(function(){
            var shipId=$('#ShipId').val();

            $.post('getVoyListSearch', {_token:token,shipId:shipId}, function(data){
                $('#voy').html(data);
            });
        });
    });


</script>

@endsection

