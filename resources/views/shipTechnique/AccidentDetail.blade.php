@extends('layout.header')

@section('content')

    <div class="main-content">
        <script src="/KindEditor/kindeditor.js"></script>
        <script src="/KindEditor/lang/zh_CN.js"></script>
        <script src="/KindEditor/plugins/code/prettify.js"></script>
        <div class="page-content">
            <div class="page-header">
                <h4 class="center"><b>船舶事故记录</b></h4>
            </div>
            <div class="col-md-12">
                <form role="form" method="POST" action="updateAccident" id="accident-add-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="text" class="hidden" name="id" value="{{$accident['id']}}">
                    <table class="table table-bordered">
                        <tbody class="center">
                        <tr>
                            <td class="td-title" width="10%">船舶名称<span class="require">*</span></td>
                            <td>
                                @if($readonly)
                                    <label class="form-control">{{$accident['shipName_Cn']}}</label>
                                @else
                                    <select id="ShipId" name="ShipId">
                                        @foreach($shipList as $ship)
                                            <option value="{{$ship['RegNo']}}" @if($ship['shipName_Cn']==$accident['shipName_Cn']) selected @endif>
                                                {{$ship['shipName_Cn']}} @if(!empty($ship['name']))| {{$ship['name']}} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                            <td class="td-title" width="10%">航次号码<span class="require">*</span></td>
                            <td width="10%">
                                @if($readonly)
                                    <label class="form-control">{{$accident['Voy_No']}}</label>
                                @else
                                    <div id="voy">
                                        <select name="VoyId" class="form-control">
                                            @foreach($cps as $cp)
                                                <option value="{{$cp['CP_No']}}"
                                                        @if(isset($accident) && ($accident['VoyId']==$cp['CP_No'])) selected @endif>
                                                    {{$cp['Voy_No']}} | {{$cp['CP_No']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </td>
                            <td class="td-title" width="10%">日期<span class="require">*</span></td>
                            <td>
                                @if($readonly)
                                    <label class="form-control">{{convert_date($accident['AccidentDate'])}}</label>
                                @else
                                    <div class="input-group" >
                                        <input class="form-control date-picker" name="AccidentDate"
                                               type="text" data-date-format="yyyy/mm/dd"
                                               value="@if(isset($accident)){{convert_date($accident['AccidentDate'])}}@endif">
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="td-title" width="10%">港口名称<span class="require">*</span></td>
                            <td>
                                @if($readonly)
                                    <label class="form-control">{{$accident['Port_Cn']}}</label>
                                @else
                                    <select id="PortId" name="PortId">
                                        @foreach($portnames as $portname)
                                            <option value="{{$portname['id']}}" @if ($portname['Port_Cn']==$accident['Port_Cn']) selected @endif>
                                                {{$portname['Port_Cn']}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">类型<span class="require">*</span></td>
                            <td>
                                @if($readonly)
                                    <label class="form-control">
                                        {{ g_enum('AccidentTypeData')[$accident['AccidentKind']][0] }}
                                    </label>
                                @else
                                    <select name="AccidentKind" class="form-control">
                                        @foreach(g_enum('AccidentTypeData') as $key => $item)
                                            <option value="{{ $key }}" {{ $key == $accident['AccidentKind'] ? 'selected' : '' }}>{{ $item[0] }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                            <td class="td-title">地点</td>
                            <td colspan="5">
                                @if($readonly)
                                    <label class="form-control">{{$accident['Place']}}</label>
                                @else
                                    <input type="text" name="Place" class="form-control"
                                           value="@if(isset($accident)){{$accident['Place']}}@endif">
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <table class="table table-bordered">
                        <tbody class="center">
                        <tr>
                            <td class="td-title" width="10%">事故内容<span class="require">*</span></td>
                            <td>
                                @if($readonly)
                                    {{$accident['Content']}}
                                @else
                                    <input type="text" name="Content" class="form-control" style="width:100%"
                                           value="@if(isset($accident)) {{$accident['Content']}} @endif">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">详细内容</td>
                            <td class="custom-td-dec-text">
                                <textarea name="Details" class="form-control" style="height:210px">
                                    @if(isset($accident)) {{$accident['Details']}} @endif
                                </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">附件</td>
                            <td colspan="5" style="text-align: left">
                                <input type="file" name="attachFile" id="attachFile"
                                       style="display: none;">

                                <input type="hidden" id="AddFileServerPath" name="AddFileServerPath">
                                @if($readonly)

                                @else
                                    <button type="button" id="openFile">
                                        <i class="icon-folder-open-alt"></i>
                                        选择文件
                                    </button>
                                @endif
                                <a href="/fileDownload?type=repaire&path={{$accident['AddFileName']}}" style="font-size: 14px">{{ $accident['AddFileServerPath'] }}</a>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    @if($readonly==0)
                        <div style="text-align: center">
                            <button type="submit" class="btn btn-inverse btn-sm" style="width: 80px"><i class="icon-save"></i>登记</button>
                        </div>
                    @endif
                </form>
             </div>
        </div>
    </div>

<script type="text/javascript">
    var token='<?php echo csrf_token()?>';
    $(function () {
        editor = KindEditor.create('textarea[name="Details"]', {
            cssPath: '/KindEditor/plugins/code/prettify.css',
            newlineTag: 'br',
            allowPreviewEmoticons: false,
            allowImageUpload: false,
            @if($readonly)readonlyMode:true,@endif                                                                                                            {{--읽기전용--}}
            items: ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'link']
        });

        $("#accident-add-form").validate({
            rules: {
                ShipId : 'required',
                VoyId : 'required',
                AccidentDate : 'required',
                PortId : 'required',
                AccidentKind : 'required',
                Content : 'required',
            },
            messages: {
                ShipId : "请选择船舶名称。",
                VoyId : "请选择航次号码。",
                AccidentDate : "请输入船舶事故的日期。",
                PortId : "请输入船舶事故的港口名称。",
                AccidentKind : "请选择船舶事故的类型。",
                Content : "请输入船舶事故的内容。",
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

