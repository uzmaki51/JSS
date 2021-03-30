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

            .member-item:hover {
                background-color: #e0edff;
            }
        </style>
        
        <div class="page-content">
            <form action="updateMemberInfo" role="form" method="POST" enctype="multipart/form-data">
                <div class="page-header">
                    <div class="col-sm-3">
                        <h4><b>Crew Register</b>
                        </h4>
                    </div>
                    <div class="col-sm-5"></div>
                    <div class="col-sm-4">
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

                <div class="col-md-12">
                    <div id="item-manage-dialog" class="hide"></div>
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
                                            <td class="text-center" style="width: 7%;">@if(isset($item['Nationality'])){{ g_enum('NationalityData')[$item['Nationality']]}}@endif</td>
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

                    <!--div class="row">
                        <div class="col-md-10 no-padding">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr style="height: 35px">
                                        <td class="center" style="width:10%">{{transShipMember("registerShipMember.Passport No")}}</td>
                                        <td style="width: 15%;">
                                            <input type="text" name="crewNum" class="form-control" value="@if(isset($info)){{$info['crewNum']}}@endif" required>
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
                    </div-->
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="tabbable">
                            <ul class="nav nav-tabs ship-register" id="memberTab">
                                <li class="active">
                                    <a data-toggle="tab" href="#general_data">
                                        General
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#main_data">
                                        Boarding Profile
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#capacity_data">
                                        Capacity & SchoolingCareer
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#training_data">
                                        Training
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="">
                                        Wage
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
                
                var row = $(".member-item.selected");
                $('#crew-table').scrollTop(row.position().top - row.height());
                //$('#crew-table').scrollTop($("tr").find("[data-index='" + memberId + "']").position().top);
            }
        });

        $('.member-item').on('click', function() {
            if($(this).hasClass('selected'))
                return;

            let member_id = $(this).attr('data-index');
            location.href = BASE_URL + 'shipMember/registerShipMember?memberId=' + member_id;
        });

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

        function deleteItem(memberId, shipName) {
            bootbox.confirm(shipName + "的船舶规范真要删除吗?", function (result) {
                if (result) {
                    $.post('deleteShipMember', {'_token':token, 'dataId':memberId}, function (result) {
                        var code = parseInt(result);
                        if (code > 0) {
                            location.reload();
                        } else {

                        }
                    });
                }
            });
        }

        document.querySelector('.custom-select-wrapper').addEventListener('click', function() {
            this.querySelector('.custom-select').classList.toggle('open');
        })

        for (const option of document.querySelectorAll(".custom-option")) {
            option.addEventListener('click', function() {
                if (!this.classList.contains('selected')) {
                    this.parentNode.querySelector('.custom-option.selected').classList.remove('selected');
                    this.classList.add('selected');
                    this.closest('.custom-select').querySelector('.custom-select__trigger span').textContent = this.textContent;
                    this.closest('.custom-select').firstElementChild.value  = this.getAttribute('data-value');
                }
            })
        }

        /*
        $("body").click(function(e) {
            $("#dropMenu").css("visibility", ( e.target.id === "optionButton" ? "visible" : "hidden" ));
        });
        */
    </script>

@endsection
