@extends('layout.sidebar')


@section('content')

    <div class="main-content">
        <style>
            .ace-file-multiple .file-label .file-name [class*="icon-"]{
                position: inherit;
                display:block;
                text-align:center;
                height:auto;
                line-height: 100px;
                width:auto;
                font-size: 64px;
                color:#d5d5d5;
                margin:4px 0;
                background-color:transparent
            }
            .ace-file-multiple .file-label:before{
                margin : 12px;
            }

            .hide-option {
                padding-left: 10px;
                padding-right: 15px;
            }

            .profile-picture {
                height: 160px;
            }

            .profile-picture.sign-picture {
                width: 100%;
                height: 40px;
            }

            #avatar, #sign{
                width: 100%;
                height: 100%;
            }
            .sign-picture .ace-file-multiple .file-label:before{
                margin : 0px !important;
                font-size: 13px !important;
            }
            .sign-picture .icon-cloud-upload {
                font-size: 15px !important;
                line-height: 0px !important;
            }
             .chosen-drop{
                 width: 350px !important;
             }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4>
                        <b>{{transShipMember("title.Register Folder")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            @if(isset($info))
                                {{transShipMember('captions.registerInfoChange')}}
                            @else
                                {{transShipMember('captions.registerMember')}}
                            @endif
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6">
                    <h5 style="float: right; color: #1565C0;"><a href="javascript: history.back()"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <form action="updateMemberMainInfo" role="form" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div style="text-align: right">
                            @if(isset($info))
                                <input class="hidden" name="memberId" value="{{$info['id']}}">
                            @endif
                            <input class="hidden" name="_token" value="{{csrf_token()}}">
                            <button class="btn btn-inverse btn-sm" type="submit" style="width: 80px">
                                <i class="icon-save"></i>{{transShipMember('captions.register')}}
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="space-4"></div>
                        <div class="col-md-10 no-padding">
                            <table class="table table-bordered table-striped">
                            <tbody>
                                <tr style="height: 35px">
                                    <td class="center" style="width:10%">{{transShipMember("registerShipMember.Passport No")}}</td>
                                    <td style="width: 15%;">
                                        <input type="text" name="crewNum" class="form-control" value="@if(isset($info)){{$info['crewNum']}}@endif">
                                    </td>
                                    <td class="center" style="width:10%">{{transShipMember("registerShipMember.Name")}}</td>
                                    <td style="width:15%">
                                        <input type="text" name="realname" class="form-control" value="@if(isset($info)){{$info['realname']}}@endif">
                                    </td>
                                    <td class="center" style="width:10%">{{transShipMember("registerShipMember.Surname")}}</td>
                                    <td style="width:15%">
                                        <input type="text" name="Surname" class="form-control" value="@if(isset($info)){{$info['Surname']}}@endif">
                                    </td>
                                    <td class="center" style="width:10%">{{transShipMember("registerShipMember.Given Name")}}</td>
                                    <td style="width:15%">
                                        <input type="text" name="GivenName" class="form-control" value="@if(isset($info)){{$info['GivenName']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="center">{{transShipMember("registerShipMember.Sex")}}</td>
                                    <td style="width:70px">
                                        <select name="Sex" class="form-control chosen-select">
                                            <option value="0" @if(isset($info) && ($info['Sex'] == 0)) selected @endif>{{transShipMember('captions.male')}}</option>
                                            <option value="1" @if(isset($info) && ($info['Sex'] == 1)) selected @endif>{{transShipMember('captions.female')}}</option>
                                        </select>
                                    </td>
                                    <td class="center">{{transShipMember("registerShipMember.Birthday")}}</td>
                                    <td style="width:150px">
                                        <div class="input-group" style="width:100%">
                                            <input class="form-control date-picker" name="birthday" type="text" data-date-format="yyyy/mm/dd"
                                                   value="@if(isset($info)){{$info['birthday']}}@endif">
                                            <span class="input-group-addon">
                                                <i class="icon-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="center">{{transShipMember("registerShipMember.Birthplace")}}</td>
                                    <td colspan="3">
                                        <input type="text" name="BirthPlace" class="form-control" value="@if(isset($info)){{$info['BirthPlace']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="center">{{transShipMember("registerShipMember.Address")}}</td>
                                    <td colspan="3">
                                        <input type="text" name="address" class="form-control" value="@if(isset($info)){{$info['address']}}@endif">
                                    </td>
                                    <td class="center">{{transShipMember("registerShipMember.HomePhone")}}</td>
                                    <td>
                                        <input type="text" name="tel" class="form-control" value="@if(isset($info)){{$info['tel']}}@endif">
                                    </td>
                                    <td class="center">{{transShipMember("registerShipMember.MobilePhone")}}</td>
                                    <td>
                                        <input type="text" name="phone" class="form-control" value="@if(isset($info)){{$info['phone']}}@endif">
                                    </td>
                                </tr>
                                <tr style="height: 35px">
                                    <td class="center">{{transShipMember("registerShipMember.Reg Date")}}</td>
                                    <td>
                                        <div class="input-group" style="width:100%">
                                            <input class="form-control date-picker" name="RegDate" type="text" data-date-format="yyyy/mm/dd"
                                                   value="@if(isset($info)){{$info['RegDate']}}@endif">
                                            <span class="input-group-addon">
                                                <i class="icon-calendar bigger-110"></i>
                                            </span>
                                        </div>

                                    </td>
                                    <td class="center">{{transShipMember("registerShipMember.Dismissal date")}}</td>
                                    <td>
                                        <div class="input-group" style="width:100%">
                                            <input class="form-control date-picker" name="DelDate" type="text" data-date-format="yyyy/mm/dd"
                                                   value="@if(isset($info)){{$info['DelDate']}}@endif">
                                                <span class="input-group-addon">
                                                    <i class="icon-calendar bigger-110"></i>
                                                </span>
                                        </div>
                                    </td>
                                    <td class="center">{{transShipMember("registerShipMember.Reg State")}}</td>
                                    <td>
                                        <select name="RegStatus" class="form-control chosen-select">
                                            <option value="1" @if(isset($info) && ($info['RegStatus'] == 1)) selected @endif>{{transShipMember('captions.register')}}</option>
                                            <option value="0" @if(isset($info) && ($info['RegStatus'] == 0)) selected @endif>{{transShipMember('captions.dismiss')}}</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="col-md-2" style="border:1px solid #ddd;height:200px;padding:0;float:right;width:16%">
                            <span class="profile-picture">
                                <img id="avatar" class="editable img-responsive" src="@if(isset($info) && !empty($info['crewPhoto'])) /uploads/crewPhoto/{{$info['crewPhoto']}} @endif" alt="선원사진">
                            </span>
                            <span class="profile-picture sign-picture">
                                <input  multiple="" type="file" id="stamp" name="stamp" style="display: none"/>
                                <img id="sign" class="editable img-responsive" style="display: none; cursor: pointer;"
                                     src="@if(isset($info) && !empty($info['signPhoto'])) /uploads/signPhoto/{{$info['signPhoto']}} @endif" alt="수표그림">
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            @if(isset($info))
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="memberTab">
                            <li class="active">
                                <a data-toggle="tab" href="#main_data">
                                    {{transShipMember("title.Register Data")}}
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#capacity_data">
                                    {{transShipMember("title.Capacity Data")}}
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#training_data">
                                    {{transShipMember("title.Train Register")}}
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#examing_data">
                                    {{transShipMember("title.Real ablility")}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="main_data" class="tab-pane active">
                            @include('shipMember.member_main_tab', with(['info'=>$info, 'shipList'=>$shipList, 'pos'=>$posList, 'ksList'=>$ksList, 'historyList'=>$historyList, 'typeList'=>$typeList]))
                        </div>
                        <div id="capacity_data" class="tab-pane">
                            @include('shipMember.member_capacity_tab', with(['memberId'=>$memberId, 'capacity'=>$capacity, 'capacity_career'=>$capacity_career, 'schoolList'=>$schoolList, 'capacityList'=>$capacityList]))
                        </div>
                        <div id="training_data" class="tab-pane">
                            @include('shipMember.member_training_tab', with(['memberId'=>$memberId, 'security'=>$security, 'training'=>$training]))
                        </div>
                        <div id="examing_data" class="tab-pane">
                            @include('shipMember.member_examing_tab', with(['examingList'=>$examingList, 'subList'=>$subList, 'codeList'=>$codeList]))
                        </div>
                        <p id="err_message_out" class="error-message"></p>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>

    <script>
        var token = '{!! csrf_token() !!}';
        var memberId = '@if(isset($info)){{$info['id']}}@endif';
        <?php $index = 0; ?>
        var posList = new Array();
        @foreach($posList as $pos)
            var shipPos = new Object();
            shipPos.id = '{{$pos['id']}}';
            shipPos.text = '{{$pos['Duty']}}';
            posList[{{$index++}}] = shipPos;
        @endforeach

        <?php $index = 0; ?>
        var typeList = new Array();
        @foreach($typeList as $type)
            var shipType = new Object();
            shipType.id = '{{$type['id']}}';
            shipType.text = '{{$type['ShipType_Cn']}}';
            typeList[{{$index++}}] = shipType;
        @endforeach

@if(isset($info))
        <?php $index = 0; ?>
        var capacityList = new Array();
        @foreach($capacityList as $capacity)
            var capacityType = new Object();
            capacityType.value = '{{$capacity['id']}}';
            capacityType.text = '{{$capacity['Capacity']}}';
            capacityList[{{$index++}}] = capacityType;
        @endforeach
@endif
        var state = '{{$state}}';

        $(function () {

            $.fn.editable.defaults.mode = 'inline';
            $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
            $.fn.editableform.buttons = '';


            if(state == 'error') {
                $.gritter.add({
                    title: '错误',
                    text: '登记号重复了!',
                    class_name: 'gritter-error'
                });
            }

            $('.input-file').ace_file_input({
                no_file:'选择文件 ...',
                btn_choose:'选择',
                btn_change:'修改',
                droppable:false,
                onchange:null,
                thumbnail:false
            });

            $('#stamp').ace_file_input({
                style: 'well',
                btn_choose: '选择照片...',
                btn_change: null,
                no_icon: 'icon-cloud-upload',
                droppable: true,
                thumbnail: 'small',//large | fit
                preview_error: function (filename, error_code) {
                }
            }).on('change', function () {
            });

            if($('#sign').attr('src') != '') {
                $('#sign').show();
                $('.profile-picture.sign-picture .ace-file-input.ace-file-multiple').hide();
            } else {
                $('#sign').hide();
                $('.profile-picture.sign-picture .ace-file-input.ace-file-multiple').show();
            }

            $('#sign').on('click', function(){
                $('#sign').hide();
                $('.profile-picture.sign-picture .ace-file-input.ace-file-multiple').show();
            });

            try {//ie8 throws some harmless exception, so let's catch it

                //it seems that editable plugin calls appendChild, and as Image doesn't have it, it causes errors on IE at unpredicted points
                //so let's have a fake appendChild for it!
                if( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ) Image.prototype.appendChild = function(el){}

                var last_gritter
                $('#avatar').editable({
                    type: 'image',
                    name: 'avatar',
                    value: null,
                    image: {
                        //specify ace file input plugin's options here
                        btn_choose: '选择文件。',
                        droppable: true,
                        /**
                         //this will override the default before_change that only accepts image files
                         before_change: function(files, dropped) {
								return true;
							},
                         */

                        //and a few extra ones here
                        name: 'avatar',//put the field name here as well, will be used inside the custom plugin
                        max_size: 1100000,//~1000Kb
                        on_error : function(code) {//on_error function will be called when the selected file has a problem
                            if(last_gritter) $.gritter.remove(last_gritter);
                            if(code == 1) {//file format error
                                last_gritter = $.gritter.add({
                                    title: '不是照片。',
                                    text: '文件必须是 jpg|gif|png 的照片形式。',
                                    class_name: 'gritter-error'
                                });
                            } else if(code == 2) {//file size rror
                                last_gritter = $.gritter.add({
                                    title: '文件大小错误!',
                                    text: '大小不得超过1M以上。',
                                    class_name: 'gritter-error'
                                });
                            }
                            else {//other error
                            }
                        },
                        on_success : function() {
                            $.gritter.removeAll();
                        }
                    },
                    url: function(params) {
                        // ***UPDATE AVATAR HERE*** //
                        //You can replace the contents of this function with examples/profile-avatar-update.js for actual upload


                        var deferred = new $.Deferred

                        //if value is empty, means no valid files were selected
                        //but it may still be submitted by the plugin, because "" (empty string) is different from previous non-empty value whatever it was
                        //so we return just here to prevent problems
                        var value = $('#avatar').next().find('input[type=hidden]:eq(0)').val();
                        if(!value || value.length == 0) {
                            deferred.resolve();
                            return deferred.promise();
                        }


                        //dummy upload
                        setTimeout(function(){
                            if("FileReader" in window) {
                                //for browsers that have a thumbnail of selected image
                                var thumb = $('#avatar').next().find('img').data('thumb');
                                if(thumb) $('#avatar').get(0).src = thumb;
                            }

                            deferred.resolve({'status':'OK'});

                            if(last_gritter) $.gritter.remove(last_gritter);
                            last_gritter = $.gritter.add({
                                title: 'Avatar Updated!',
                                text: 'Uploading to server can be easily implemented. A working example is included with the template.',
                                class_name: 'gritter-info gritter-center'
                            });

                        } , parseInt(Math.random() * 800 + 800))

                        return deferred.promise();
                    },

                    success: function(response, newValue) {
                    }
                });
            }catch(e) {}

            $('ul li a[data-toggle=tab]').click(function(){
                $nowTab = $(this).attr("href");
                window.localStorage.setItem("shipMemberTab",$nowTab);
            });

            $initTab = window.localStorage.getItem("shipMemberTab");
            if ($initTab != null) {
                $('ul li a[data-toggle=tab]').each(function(){
                    $href = $(this).attr("href");
                    $(this).parent("li").prop("class","");
                    $($href).prop("class", "tab-pane");
                    if($initTab == $href) {
                        $($initTab).prop("class", "tab-pane active");
                        $(this).parent("li").prop("class","active");
                    }
                });
            }
        });

        function newrow() {
            var tbody = document.getElementById('history_table');
            var newtr = document.createElement('tr');
            var leng = tbody.children.length;
            if(leng < 1)
                index = 0;
            else
                index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
            var htmlStr = '<td class="hidden">'+ index +'</td>' +
                '<td class="hidden"><input type="text" name="index_'+ index + '" value="'+ index + '"></td>' +
                '<td>' +
                '<div class="input-group">' +
                '<input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" name="FromDate_' + index + '">' +
                '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                '<td>' +
                '<div class="input-group">' +
                '<input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" name="ToDate_' + index + '">' +
                '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                '<td><input type="text" name="Ship_' + index + '" style="width: 100%"></td>' +
                '<td><select class="form-control chosen-select" name="DutyID_' + index +'">';
                for(var i=0;i<posList.length; i++) {
                    var shipPos = posList[i];
                    console.log(shipPos);
                    htmlStr += '<option value="'+ shipPos.id + '">' + shipPos.text + '</option>';
                }
                htmlStr += '</select></td>';
                htmlStr += '<td><select class="form-control chosen-select" name="ShipType_' + index + '">';
                for(var i=0;i<typeList.length; i++) {
                    var shipType = typeList[i];
                    htmlStr += '<option value="'+ shipType.id + '">' + shipType.text + '</option>';
                }
                htmlStr += '</select></td>';
                htmlStr += '<td>' +
                    '<input type="text" name="GrossTonage_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="Power_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="SailArea_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="Remarks_' + index + '" style="width: 100%"></td>' +
                    '<td><div class="action-buttons">' +
                    '<a class="red" href="javascript:onDelete('+ index + ')">' +
                    '<i class="icon-trash bigger-130"></i></a></div></td>';

            newtr.innerHTML = htmlStr;
            newtr.id = 'history' + index;
            tbody.appendChild(newtr);
            setDatePicker();

			$('[name=DutyID_' + index+']').chosen();
			$('[name=ShipType_' + index+']').chosen();
        }

        function onDelete(id) {
            var tbody = document.getElementById('history_table');
            var len = tbody.children.length;
            var row = 0;
            for (; row < len; row++) {
                var tds = tbody.children[row];
                var rowId = Math.floor(tds.children[0].innerText);
                if(rowId == id)
                    break;
            }
            tbody.deleteRow(row);
        }

        function newCareerRow() {
            var tbody = document.getElementById('career_table');
            var newtr = document.createElement('tr');
            var leng = tbody.children.length;
            if(leng < 1)
                index = 0;
            else
                index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
            var htmlStr = '<td class="hidden">' + index + '</td>' +
                    '<td class="hidden"><input type="text" name="career_' + index +'" value="' + index + '"></td>' +
                    '<td><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd"' +
                    'name="fromDate_' + index + '"><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                    '<td><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd"' +
                    'name="toDate_' + index + '"><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                    '<td><input type="text" name="prePosition_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="prePosPlace_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="address_' + index + '" style="width: 100%"></td>' +
                    '<td><div class="action-buttons"><a class="red" href="javascript:onCareerDelete(' + index + ')">' +
                    '<i class="icon-trash bigger-130"></i></a></div></td>';
            newtr.innerHTML = htmlStr;
            tbody.appendChild(newtr);
            setDatePicker();
        }

        function newCapacityRow() {
            var tbody = document.getElementById('capacity_table');
            var newtr = document.createElement('tr');
            var leng = tbody.children.length;
            if(leng < 1)
                index = 0;
            else
                index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
            var htmlStr = '<td class="hidden">' + index + '</td>' +
                    '<td class="hidden"><input type="text" name="capacity_' + index + '" value="'+ index +'"></td>' +
                    '<td><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd"' +
                    'name="RegDate_' + index + '"><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                    '<td><select class="form-control chosen-select" name="CapacityID_' + index + '"><option value="0"></option>';
            for(var i=0;i<capacityList.length; i++) {
                var capacity = capacityList[i];
                htmlStr += '<option value="' + capacity.value + '">' + capacity.text + '</option>';
            }
            htmlStr += '</select></td>' +
                    '<td class="center"><input type="checkbox" name="GOC_' + index + '" style="width: 16px;height:16px"></td>' +
                    '<td><input type="text" name="RegReason_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="Remarks_Career_' + index + '" style="width: 100%"></td>' +
                    '<td><div class="action-buttons"><a class="red" href="javascript:onCapacityDelete(' + index + ')"><i class="icon-trash bigger-130"></i></a></div></td>';

            newtr.innerHTML = htmlStr;
            tbody.appendChild(newtr);
            setDatePicker();

			$('[name=CapacityID_' + index + ']').chosen();
        }

        function newSchoolRow() {
            var tbody = document.getElementById('school_table');
            var newtr = document.createElement('tr');
            var leng = tbody.children.length;
            if(leng < 1)
                index = 0;
            else
                index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
            var htmlStr = '<td class="hidden">' + index + '</td>' +
                    '<td class="hidden"><input type="text" name="school_' + index + '" value="' + index + '"></td>' +
                    '<td><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd"' +
                    'name="FromDate_' + index + '"><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                    '<td><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd"' +
                    'name="ToDate_' + index + '"><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                    '<td><input type="text" name="SchoolName_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="Major_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="Grade_' + index + '" style="width: 100%"></td>' +
                    '<td><input type="text" name="TechQualification_' + index + '"style="width: 100%"></td>' +
                    '<td><input type="file" class="input-file" name="school_goc_' + index + '"/></td>' +
                    '<td></td>' +
                    '<td><input type="text" name="Remarks_' + index + '" style="width: 100%"></td>' +
                    '<td><div class="action-buttons"><a class="red" href="javascript:onSchoolDelete(' + index + ')"><i class="icon-trash bigger-130"></i></a></div></td>';

            newtr.innerHTML = htmlStr;
            tbody.appendChild(newtr);
            setDatePicker();

            $('.input-file').ace_file_input({
                no_file:'选择文件 ...',
                btn_choose:'选择',
                btn_change:'修改',
                droppable:false,
                onchange:null,
                thumbnail:false
            });
        }

        function onCareerDelete(id) {
            var tbody = document.getElementById('career_table');
            var len = tbody.children.length;
            var row = 0;
            for (; row < len; row++) {
                var tds = tbody.children[row];
                var rowId = Math.floor(tds.children[0].innerText);
                if(rowId == id)
                    break;
            }
            tbody.deleteRow(row);
        }

        function onCapacityDelete(id) {
            var tbody = document.getElementById('capacity_table');
            var len = tbody.children.length;
            var row = 0;
            for (; row < len; row++) {
                var tds = tbody.children[row];
                var rowId = Math.floor(tds.children[0].innerText);
                if(rowId == id)
                    break;
            }
            tbody.deleteRow(row);
        }

        function onSchoolDelete(id) {
            var tbody = document.getElementById('school_table');
            var len = tbody.children.length;
            var row = 0;
            for (; row < len; row++) {
                var tds = tbody.children[row];
                var rowId = Math.floor(tds.children[0].innerText);
                if(rowId == id)
                    break;
            }
            tbody.deleteRow(row);
        }

        function setDatePicker() {
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        @if(isset($info))
        var memberId = {{$memberId}};
        var save = '<a class="red" onclick="javascript:saveMemberExaming(this)"><i class="icon-save bigger-110"></i></a>';
        var cancel = '<a class="red" onclick="javascript:cancelMemberExaming(this)"><i class="icon-remove bigger-110"></i></a>';
        var edit = '<a class="blue" onclick="javascript:editMemberExaming(this)"><i class="icon-edit bigger-110"></i></a>';
        var trash = '<a class="red" onclick="javascript:deleteMemberExaming(this)"><i class="icon-trash bigger-110"></i></a>';
        var subMark = '<a class="blue" onclick="javascript:selectMemberExaming(this)"><i class="icon-bar-chart bigger-110"></i></a>';
        function newExamingRow() {
            var tbody = document.getElementById('examing_table');
            var newtr = document.createElement('tr');
            for(index = 0; ; index++) {
                if($('[name="examCode_' + index + '"').attr('type') == undefined) break;
            }
            if(index > 0 && $('[name="examCode_' + (index - 1) + '"').data('old') == undefined) return;
            var htmlStr = '<input type="hidden" id="examId_' + index + '" value="">\
                <td class="center"><input type="text" name="examCode_' + index + '" class="form-control" style="width:100%"></td>\
                <td><div class="input-group"><input class="form-control date-picker" name="examDate_' + index + '" type="text" data-date-format="yyyy/mm/dd">\
                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>\
                <td><input type="text" name="examPlace_' + index + '" class="form-controlstyle="width:100%"></td>\
                <td><select class="form-control" name="examSubject_' + index + '">\
                    <option value=""></option><option value="英文">英文</option><option value="">专业</option>\
                    </select></td>\
                <td><input class="form-control" name="examMarks_' + index + '" style="width:100%"></td>\
                <td><div class="action-buttons" name="' + index + '">' + save + ' ' + trash + '</div></td>';

            newtr.innerHTML = htmlStr;
            tbody.appendChild(newtr);
            setDatePicker();
        }

        function editMemberExaming(that) {
            var parent = that.parentNode;
            var index = parent.getAttribute('name');
            $('[name="examCode_' + index + '"').removeAttr('disabled');
            $('[name="examDate_' + index + '"').removeAttr('disabled');
            $('[name="examPlace_' + index + '"').removeAttr('disabled');
            $('[name="examSubject_' + index + '"').removeAttr('disabled');
            $('[name="examMarks_' + index + '"').removeAttr('disabled');
            parent.innerHTML = save + ' ' + cancel;
        }

        function saveMemberExaming(that) {
            var parent = that.parentNode;
            var index = parent.getAttribute('name');
            var examCode = $('[name="examCode_' + index + '"').val();
            var examDate = $('[name="examDate_' + index + '"').val();
            var examPlace = $('[name="examPlace_' + index + '"').val();
            var examSubject = $('[name="examSubject_' + index + '"').val();
            var examMarks = $('[name="examMarks_' + index + '"').val();
            if(examCode == '' || examDate == '' || examPlace == '' || examMarks == '') {
                $.gritter.add({
                    title: '错误',
                    text: '此资料该请输和保存',
                    class_name: 'gritter-error'
                });
                return;
            }
            var examId = $('#examId_' + index).val();
            $.post('registerMemberExamingData', {
                '_token':token,
                'examId': examId,
                'memberId': memberId,
                'examCode': examCode,
                'examDate': examDate,
                'examPlace': examPlace,
                'examSubject': examSubject,
                'examMarks': examMarks
            }, function(data) {
                if(data > 0) {
                    parent.id = data;
                    $('#examId_' + index).val(data);
                    $('[name="examCode_' + index + '"').attr('disabled', 'disabled').attr('data-old', examCode);
                    $('[name="examDate_' + index + '"').attr('disabled', 'disabled').attr('data-old', examDate);
                    $('[name="examPlace_' + index + '"').attr('disabled', 'disabled').attr('data-old', examPlace);
                    $('[name="examSubject_' + index + '"').attr('disabled', 'disabled').attr('data-old', examSubject);
                    $('[name="examMarks_' + index + '"').attr('disabled', 'disabled').attr('data-old', examMarks);
                    parent.innerHTML = edit + ' ' + trash + ' ' + subMark;
                } else {
                    $.gritter.add({
                        title: '错误',
                        text: '参考号码跟科目重复了。',
                        class_name: 'gritter-error'
                    });
                }
            });
        }

        function cancelMemberExaming(that) {
            var parent = that.parentNode;
            var index = parent.getAttribute('name');
            $('[name="examCode_' + index + '"').attr('disabled', 'disabled').val($('[name="examCode_' + index + '"').data('old'));
            $('[name="examDate_' + index + '"').attr('disabled', 'disabled').val($('[name="examDate_' + index + '"').data('old'));
            $('[name="examPlace_' + index + '"').attr('disabled', 'disabled').val($('[name="examPlace_' + index + '"').data('old'));
            $('[name="examSubject_' + index + '"').attr('disabled', 'disabled').val($('[name="examSubject_' + index + '"').data('old'));
            $('[name="examMarks_' + index + '"').attr('disabled', 'disabled').val($('[name="examMarks_' + index + '"').data('old'));
            parent.innerHTML = edit + ' ' + trash + ' ' + subMark;
        }

        function deleteMemberExaming(that) {
            bootbox.confirm("真要删掉吗?", function (result) {
                if (result) {
                    //确认단추를 눌렀을 때의 처리
                    var parent = that.parentNode;
                    var index = parent.getAttribute('name');
                    var examId = $('#examId_' + index).val();
                    var current_examId = $('#examId').val();
                    $.post('deleteMemberExamingData', {
                        '_token':token,
                        'examId': examId,
                        'current': current_examId,
                        'memberId': memberId
                    }, function(data) {
                        if(data != 'success') {
                            temp = data.split('*****');
                            $('#mark_table').html(temp[0]);
                            $('#eval_mark').text('平均成绩 : ' + temp[1]);
                        }
                        var parent = that.parentNode;
                        var td = parent.parentNode;
                        var tr = td.parentNode;
                        var row = tr.sectionRowIndex;
                        tr.parentNode.deleteRow(row);
                    });
                }
            });
        }

        function selectMemberExaming(that) {
            var id = that.parentNode.id;
            $.post('showMemberExamSubMarks', {
                '_token':token,
                'examId':id
            }, function(data) {
                if(data) {
                    temp = data.split('*****');
                    $('#mark_table').html(temp[0]);
                    $('#eval_mark').text('平均成绩 : ' + temp[1]);
                    $('#examId').val(id);
                }
            });
        }

        function newExamingMarkRow() {
            if($('#examId').val() == undefined || $('#examId').val() == '') {
                $.gritter.add({
                    title: '错误',
                    text: '请选择',
                    class_name: 'gritter-error'
                });
                return;
            }
            var tbody = document.getElementById('subMark_table');
            var newtr = document.createElement('tr');
            for(index = 0; ; index++) {
                if($('#subMarks_' + index).attr('type') == undefined) break;
            }
            if(index > 0 && $('#subMarks_' + (index - 1)).data('old') == undefined) return;
            var htmlStr = '<input type="hidden" id="subMarksId_' + index + '" value="">\
                    <td><input type="text" id="subMarks_' + index + '" class="form-control" value="" style="width:100%"></td>\
                    <td class="center" style="width: 60px">\
                    <div class="action-buttons" name="' + index + '" id="">\
                    <a class="red" onclick="javascript:saveSubMarkRow(this)">\
                    <i class="icon-save bigger-110"></i></a>\
                    <a class="red" onclick="javascript:deleteSubMarkRow(this)">\
                    <i class="icon-trash bigger-110"></i></a></div></td>';

            newtr.innerHTML = htmlStr;
            if($('#subMark_table td[data-old="-1"]').text() == "没有资料。") tbody.innerHTML = '';
            tbody.appendChild(newtr);
        }

        var sub_save = '<a class="red" onclick="javascript:saveSubMarkRow(this)"><i class="icon-save bigger-110"></i></a>';
        var sub_edit = '<a class="blue" onclick="javascript:editSubMarkRow(this)"><i class="icon-edit bigger-110"></i></a>';
        var sub_cancel = '<a class="red" onclick="javascript:cancelSubMarkRow(this)"><i class="icon-remove bigger-110"></i></a>';
        var sub_trash = '<a class="red" onclick="javascript:deleteSubMarkRow(this)"><i class="icon-trash bigger-110"></i></a>';

        function saveSubMarkRow(that) {
            var parent = that.parentNode;
            var index = parent.getAttribute('name');
            var subMark = $('#subMarks_' + index).val();
            if(subMark == '') {
                $.gritter.add({
                    title: '错误',
                    text: '请填写成绩。',
                    class_name: 'gritter-error'
                });
                return;
            }
            var id = $('#subMarksId_' + index).val();
            var examId = $('#examId').val();
            $.post('saveExamSubMarks', {
                '_token': token,
                'id': id,
                'examId': examId,
                'subMarks': subMark,
            }, function(data) {
                if(data) {
                    $('#eval_mark').text('平均成绩 : ' + data);
                    $('#subMarks_' + index).attr('disabled', 'disabled').attr('data-old', subMark);
                    parent.innerHTML = sub_edit + ' ' + sub_trash;
                }
            });
        }

        function editSubMarkRow(that) {
            var parent = that.parentNode;
            var index = parent.getAttribute('name');
            $('#subMarks_' + index).removeAttr('disabled');
            parent.innerHTML = sub_save + ' ' + sub_cancel;
        }

        function cancelSubMarkRow(that) {
            var parent = that.parentNode;
            var index = parent.getAttribute('name');
            $('#subMarks_' + index).attr('disabled', 'disabled').val($('#subMarks_' + index).data('old'));
            parent.innerHTML = sub_edit + ' ' + sub_trash;
        }

        function deleteSubMarkRow(that) {
            bootbox.confirm("真要删掉吗?", function (result) {
                if (result) {
                    var parent = that.parentNode;
                    var index = parent.getAttribute('name');
                    var id = $('#subMarksId_' + index).val();
                    var examId = $('#examId').val();
                    $.post('deleteExamSubMarks', {
                        '_token': token,
                        'id': id,
                        'examId': examId
                    }, function(data) {
                        $('#eval_mark').text('平均成绩 : ' + data);
                        var parent = that.parentNode;
                        var td = parent.parentNode;
                        var tr = td.parentNode;
                        var row = tr.sectionRowIndex;
                        var tbody = tr.parentNode;
                        tbody.deleteRow(row);
                        console.log(tbody.parentNode.rows.length);
                        if(tbody.parentNode.rows.length == 1) {
                            var newtr = document.createElement('tr');
                            newtr.innerHTML = '<tr><td colspan="2" data-old="-1">没有资料。</td></tr>';
                            tbody.appendChild(newtr);
                        }
                    });
                }
            });
        }
        @endif

    </script>

@endsection
