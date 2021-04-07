@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
@endsection
@section('content')

    <div class="main-content">
        <style>
            .table tbody > tr > .custom-td-label1 {
                padding: 2px!important;
                border: unset!important;
                width: 120px!important;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                display: block;
            }
            
            .table tbody > tr > .custom-td-label1:after {
                content: '........................................................................................................';
                overflow: hidden;
                width: 120px;
            }

            .list-body:hover {
                background-color: #e0edff;
                cursor: pointer;
            }

        </style>
        
        <div class="page-content">
            <form id="member-form" action="updateMemberInfo" role="form" method="POST" enctype="multipart/form-data">
                <div class="page-header">
                    <div class="col-sm-3">
                        <h4><b>Crew Register</b>
                        </h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-6 f-left">
                        <label>Name: <input type="text" id="search-name"/></label>
                        <label style="margin-left:5px;">Sign On (上船): </label><input id="search-signon" style="margin-top:5px; margin-left:5px; position:absolute;" type="checkbox" onclick="" checked disabled/>
                    </div>
                    <div class="col-sm-6 f-right">
                        @if(!$isHolder)
                            <div class="btn-group f-right">
                                <a href="/shipMember/registerShipMember" class="btn btn-sm btn-primary btn-add" style="width: 80px">
                                    <i class="icon-plus"></i>{{ trans('common.label.add') }}
                                </a>
                                <button type="submit" id="btnRegister" class="btn btn-sm btn-info" style="width: 80px">
                                    <i class="icon-save"></i>{{ trans('common.label.save') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:4px;">
                    <div id="item-manage-dialog" class="hide"></div>
                    <div class="row">
                        <div class="head-fix-div" style="height:100px!important" id="crew-table">
                            <table id="table-shipmember-list">
                                <thead>
                                  <th class="text-center" style="width: 3%;"><span>No</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Family Name, Given Name</span></th>
                                    <th class="text-center" style="width: 7%;"><span>Rank</span></th>
                                    <th class="text-center" style="width: 7%;"><span>Nationality</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Chinese ID No.</span></th>
                                    <th class="text-center" style="width: 7%;"><span>Date and place of birth</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Date and place of embarkation</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Seaman's Book No and Expire Date</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Passport's No and Expire Date</span></th>
                                    <th style="width: 2%;"></th>
                                </thead>
                                <tbody class="list-body" id="list-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--div class="col-md-12">
                    <div class="row">
                        <div class="head-fix-div" id="crew-table">
                            <table>
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 2%;"><span>No</span></th>
                                    <th class="text-center" style="width: 10%;"><span>SeamanbookNo</span></th>
                                    <th class="text-center" style="width: 10%;"><span>Name in chinese</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Gender</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Birthday</span></th>
                                    <th class="text-center" style="width: 7%;"><span>Nationality</span></th>
                                    <th class="text-center" style="width: 8%;"><span>Sign On/Off</span></th>
                                    <th style="width: 2%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $index = 1; ?>
                                @if(isset($list) && count($list) > 0)
                                    @foreach ($list as $item)
                                        <tr id="test{{$index}}" class="member-item {{ $item['id'] == $info['id'] ? 'selected' : '' }}" data-index="{{ $item['id'] }}">
                                            <td class="text-center" style="width: 2%;">{{ $item['id'] }}</td>
                                            <td class="text-center" style="width: 10%;">{{ $item['crewNum'] }}</td>
                                            <td class="text-center" style="width: 10%;">{{ $item['realname'] }}</td>
                                            <td class="text-center" style="width: 10%;">{{ $item['Sex'] == 0 ? transShipMember('captions.male') : transShipMember('captions.female')}}</td>
                                            <td class="text-center" style="width: 8%;">{{ $item['birthday'] }}</td>
                                            <td class="text-center" style="width: 7%;">{{ $item['Nationality'] }}</td>
                                            <td class="text-center" style="width: 8%;">{{ $item['RegStatus'] == 0 ? 'On' : 'Off' }}</td>
                                            <td class="text-center" style="width: 2%;">
                                                <div class="action-buttons">
                                                    @if(!$isHolder)
                                                        <a class="red" href="javascript:deleteItem('{{ $item['id'] }}', '{{ $item['shipName_En'] }}')">
                                                            <i class="icon-trash"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $index ++; ?>
                                    @endforeach
                                @else
                                    <div>
                                        {{ trans('common.message.no_data') }}
                                    </div>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div style="text-align: right">
                            @if(isset($info))
                                <input class="hidden" name="memberId" value="{{$info['id']}}">
                            @endif
                            <input class="hidden" name="_token" value="{{csrf_token()}}">
                        </div>
                    </div>
                </div-->
                <div class="col-md-12">
                    <div class="row">
                        <div class="tabbable">
                            <ul class="nav nav-tabs ship-register" id="memberTab">
                                <li class="active">
                                    <a data-toggle="tab" href="#general_data">
                                        个人信息
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#capacity_data">
                                        适任证书
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#training_data">
                                        培训证书
                                    </a>
                                </li>
                                <li>
                                    <div class="alert alert-block alert-success center visuallyhidden">
                                        <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                        <strong id="msg-content"> Please register a new member.</strong>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div id="general_data" class="tab-pane active">
                                @include('shipMember.member_general_tab', with(['info'=>$info, 'shipList'=>$shipList]))
                            </div>
                            <div id="main_data" class="tab-pane">
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
            </form>
            <div id="modal-rank-list" class="modal" aria-hidden="true" style="display: none; margin-top: 15%;">
                <div class="modal-dialog dynamic-list">
                    <div class="modal-content" style="border: 0;">
                        <div class="modal-header no-padding" data-target="#modal-step-contents">
                            <div class="table-header">
                                <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                Rank List
                            </div>
                        </div>
                        <div id="modal-rank-content" class="dynamic-modal-body step-content">
                            <div class="row" style="">
                                <div class="col-md-12" style="min-height: 300px; max-height: 300px; overflow-y:auto">
                                    <table class="table-bordered rank-table">
                                        <thead>
                                        <tr style="background-color: #c9dfff;height:18px;">
                                            <td class="center td-header no-padding" style="width:15%">OrderNo</td>
                                            <td class="center td-header no-padding" style="width:30%">Rank</td>
                                            <td class="center td-header no-padding" style="width:20%">Rank abb.</td>
                                            <td class="center td-header no-padding">Description</td>
                                            <td class="center td-header no-padding"></td>
                                        </tr>
                                        </thead>
                                        <tbody id="rank-table">
                                        <tr class="rank-tr">
                                            <td class="no-padding center">
                                                <input type="text" onchange="addRank(this)" class="form-control" name="Rank_OrderNo[]"value="" style="width: 100%;text-align: center">
                                            </td>
                                            <td class="no-padding">
                                                <input type="text" onchange="addRank(this)" class="form-control" name="Rank_Name[]"value="" style="width: 100%;text-align: center">
                                            </td>
                                            <td class="no-padding center">
                                                <input type="text" onchange="addRank(this)" class="form-control" name="Rank_Abb[]"value="" style="width: 100%;text-align: center">
                                            </td>
                                            <td class="no-padding">
                                                <input type="text" onchange="addRank(this)" class="form-control" name="Rank_Description[]"value="" style="width: 100%;text-align: center">
                                            </td>
                                            <td class="no-padding center">
                                                <div class="action-buttons">
                                                    <a class="red" onClick="javascript:deleteRank(this)"><i class="icon-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="btn-group f-right mt-20 d-flex">
                                        <button type="button" class="btn btn-success small-btn ml-0" onclick="javascript:dynamicRankSubmit('rank')">
                                            <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                        </button>
                                        <div class="between-1"></div>
                                        <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modal-capacity-list" class="modal" aria-hidden="true" style="display: none; margin-top: 15%;">
                <div class="modal-dialog dynamic-list">
                    <div class="modal-content" style="border: 0;">
                        <div class="modal-header no-padding" data-target="#modal-step-contents">
                            <div class="table-header">
                                <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                Capacity List
                            </div>
                        </div>
                        <div id="modal-capacity-content" class="dynamic-modal-body step-content">
                            <div class="row" style="">
                                <div class="col-md-12" style="min-height: 300px; max-height: 300px; overflow-y:auto">
                                    <table class="table-bordered rank-table">
                                        <thead>
                                        <tr style="background-color: #c9dfff;height:18px;">
                                            <td class="center td-header no-padding" style="width:15%">OrderNo</td>
                                            <td class="center td-header no-padding" style="width:40%">Capacity</td>
                                            <td class="center td-header no-padding" style="width:20%">STCW</td>
                                            <td class="center td-header no-padding" style="width:25%">Description</td>
                                            <td class="center td-header no-padding" style="width:10%"></td>
                                        </tr>
                                        </thead>
                                        <tbody id="capacity-table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="btn-group f-right mt-20 d-flex">
                                        <button type="button" class="btn btn-success small-btn ml-0" onclick="javascript:dynamicCapacitySubmit('capacity')">
                                            <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                        </button>
                                        <div class="between-1"></div>
                                        <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modal-dynamic" class="modal" aria-hidden="true" style="display: none; margin-top: 15%;">
                <div class="modal-dialog dynamic-modal">
                    <div class="modal-content" style="border: 0;">
                        <div class="modal-header no-padding" data-target="#modal-step-contents">
                            <div class="table-header">
                                <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                Edit List Items
                            </div>
                        </div>
                        <div id="modal-body-content" class="dynamic-modal-body step-content">
                            <div class="row">
                                <label>Type each item on a separate line:</line>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <textarea id="dynamic-data" type="text" name="HullNotation" class="dynmaic-list" rows="15"></textarea>
                            </div>
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-4">
                                    <label>Default Value:</label>
                                </div>
                                <div class="col-md-8">
                                    <select id="dynamic-default" class="dynamic-default-select">
                                        <option value="0">ENGLISH</option>
                                        <option value="1">ENGLISH</option>
                                        <option value="2">ENGLISH</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="btn-group f-right mt-20 d-flex">
                                    <button type="button" class="btn btn-success small-btn ml-0" onclick="javascript:dynamicSubmit()">
                                        <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                    </button>
                                    <div class="between-1"></div>
                                    <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                </div>
                            </div>
                            <div>
                                <form role="form" method="POST" action="{{url('decision/report/submit')}}" enctype="multipart/form-data" id="report-form">
                                </form>
                            </div>
                        </div>
                        <input type="hidden" value="" id="dynamic-type"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/jsquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.rowsGroup.js') }}"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>
    <?php
	echo '<script>';
	echo 'var CurrencyLabel = ' . json_encode(g_enum('CurrencyLabel')) . ';';
	echo '</script>';
	?>
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
                    text: 'SeamanbookNo不可以重复了!',
                    class_name: 'gritter-error'
                });
            }

            $('ul li a[data-toggle=tab]').click(function(){
                $nowTab = $(this).attr("href");
                window.localStorage.setItem("shipMemberTab",$nowTab);
            });

            if (memberId != -1) {
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
            }

            try {
                if( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ) Image.prototype.appendChild = function(el){}
                var last_gritter;
                $('#avatar').editable({
                    type: 'image',
                    name: 'avatar',
                    height: '100px',
                    value: null,
                    image: {
                        //specify ace file input plugin's options here
                        btn_choose: '选择文件',
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

                if (memberId == -1) {
                    $('#avatar').click();
                }
            });

        $('.list-body').on('click', function(evt) {
            let cell = $(evt.target).closest('td');
            if(cell.index() < 9) {
                let member_id = this.firstElementChild.getAttribute('data-index');
                location.href = BASE_URL + 'shipMember/registerShipMember?memberId=' + member_id;
            }
            
        });
        /*
        $('.member-item').on('click', function() {
            //if($(this).hasClass('selected'))
            //    return;
            let member_id = $(this).attr('data-index');
            alert(member_id);
            location.href = BASE_URL + 'shipMember/registerShipMember?memberId=' + member_id;
        });
        */

        function setDatePicker() {
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        @if(isset($info))
        var memberId = '{!! $info['id'] !!}';
        @endif
        
        $(function() {
            if(memberId == -1 ) {
                $('.alert').toggleClass('visuallyhidden');
                setTimeout(function() {
                    $('.alert').toggleClass('visuallyhidden');
                }, 2000);
                $('[name=crewNum]').focus();
            }
        })

        function deleteItem(memberId) {
            bootbox.confirm("真要删除吗?", function (result) {
                if (result) {
                    $.post('deleteShipMember', {'_token':token, 'dataId':memberId}, function (result) {
                        console.log("result:");
                        console.log(result);
                        var code = parseInt(result);
                        if (code > 0) {
                            location.reload();
                        } else {

                        }
                    });
                }
            });
        }

        function initTable() {
            listTable = $('#table-shipmember-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/shipMember/search',
                    type: 'POST',
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: 'no', className: "text-center"},
                    {data: 'name', className: "text-center"},
                    {data: 'rank', className: "text-center"},
                    {data: 'nationality', className: "text-center"},
                    {data: 'cert-id', className: "text-center"},
                    {data: 'birth-and-place', className: "text-center"},
                    {data: 'date-and-embarkation', className: "text-center"},
                    {data: 'bookno-expire', className: "text-center"},
                    {data: 'passport-expire', className: "text-center"},
                    {data: null, className: "text-center"},
                ],
                rowsGroup: [0, 2, 3, 4],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    $(row).attr('data-index', data['no']);
                    $(row).attr('class', 'member-item');
                    if (index % 2 == 0) {
                        $('td', row).eq(9).attr('rowspan', '2');
                        $('td', row).eq(9).html('').append('<div class="action-buttons"><a class="red" href="javascript:deleteItem(' + data['no'] + ')"><i class="icon-trash"></i></a></div>');
                    }
                    else {
                        $('td', row).eq(9).remove();
                    }
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }
        initTable();

        function doSearch() {
            console.log("DoSearch");
            var name = $('#search-name').val();
            listTable.column(1).search(name, false, false).draw();
        }

        $('#member-form').on('keydown', function(e) {
            if (e.which == 13) {
                e.preventDefault();
            }
        })

        $('#search-name').on('keyup', function(e) {
            if (e.which == 13) {
                doSearch();
            }
        })
    </script>
    <script type="text/javascript">
        var capacityList = new Array();
        var cIndex = 0;
        var state = '{!! $state !!}';
        
        @foreach($typeList as $type)
            var capacity = new Object();
            capacity.value = '{{$type['id']}}';
            capacity.text = '{{$type['Capacity_En']}}';
            capacityList[cIndex] = capacity;
            cIndex++;
        @endforeach

        addHistory(null);
        
        function deleteHistory(e)
        {
            console.log(e);
            if ($('#history_table tr').length > 2) { //&& !$(e).closest("tr").is(":last-child")) {
                if (confirm("Are you sure to delete?")) {
                    console.log($(e).closest("tr"));
                    $(e).closest("tr").remove();
                }
            }
        }

        function addHistory(e)
        {
            if ($('#history_table tr').length <= 3)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    var newrow = '<tr><td class="no-padding"><div class="input-group"><input onfocus="addHistory(this)" class="form-control date-picker" style="width: 100%;text-align: center" type="text" data-date-format="yyyy/mm/dd"name="FromDate[]"value=""><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td><td class="no-padding"><div class="input-group"><input onfocus="addHistory(this)" class="form-control date-picker" style="width: 100%;text-align: center"type="text" data-date-format="yyyy/mm/dd"name="ToDate[]"value=""><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td><td class="no-padding"><input type="text" onfocus="addHistory(this)" class="form-control" name="ShipName[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><select name="DutyID[]" class="form-control" onfocus="addHistory(this)" style="padding:0px!important;color:#12539b!important"><option value="0">&nbsp;</option>';
                    @foreach($posList as $pos)
                    newrow = newrow + '<option value="' + '{{$pos['id']}}';
                    @if($info['DutyID_Book'] == $pos['id']) newrow = newrow + '" selected';
                    @endif
                    newrow = newrow + '">';
                    newrow = newrow + '{{$pos['Duty_En']}}' + '</option>';
                    @endforeach
                    newrow = newrow + '</select></td><td class="no-padding"><input type="text" onfocus="addHistory(this)" class="form-control" name="GT[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><select onfocus="addHistory(this)" class="form-control" name="ShipType[]"style="padding:0px!important;color:#12539b!important"><option value="0">&nbsp;</option>';
                    //'<input type="text" onfocus="addHistory(this)" class="form-control" name="ShipType[]"value="" style="width: 100%;text-align: center"></td>';
                    @foreach($typeList as $type)
                    newrow = newrow + '<option value="' + '{{$type['id']}}';
                    @if($info['ShipType'] == $type['id']) newrow = newrow + '" selected';
                    @endif
                    newrow = newrow + '">';
                    newrow = newrow + '{{$type['ShipType']}}' + '</option>';
                    @endforeach


                    newrow = newrow += '</select></td><td class="no-padding"><input type="text" onfocus="addHistory(this)" class="form-control" name="Power[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addHistory(this)" class="form-control" name="TradingArea[]"value="" style="width: 100%;text-align: center"></td><td class="center no-padding"><div class="action-buttons"><a class="red" onclick="javascript:deleteHistory(this)"><i class="icon-trash"></i></a></div></td></tr>';
                    $("#history_table").append(newrow);
                    setDatePicker();
                }
            }
        }

        function setDatePicker() {
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        var $form = $('form'),
        origForm = $form.serialize();
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
            console.log($form.serialize());
            console.log($form.serialize() !== origForm);

            if ($form.serialize() !== origForm) {
                (e || window.event).returnValue = confirmationMessage;
            }
            return confirmationMessage;
        });
    </script>

@endsection
