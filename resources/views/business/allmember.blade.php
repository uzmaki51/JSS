@extends('layout.header')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <h4>
                    <b>{{transBusinessManage("title.AllMember")}}</b>
                    <div style="float: right">
                        <a href="#modal-dialog" role="button" class="btn btn-primary btn-xs" style="float:right; width: 100px;" data-toggle="modal"><i class="icon-group"></i>{{transBusinessManage("captions.officeStructure")}}</a>
                    </div>
                </h4>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1" style="width:30px;margin-right:10px">
                        <button class="btn btn-danger btn-sm more-btn" style="padding:0">
                            <i id="showSearch" class="icon-chevron-down"></i>
                        </button>
                    </div>
                    <div class="col-sm-11">
                        <form action="showTotalMemberList" method="POST">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="col-sm-2 form-group" style="padding: 0">
                                <label style="float:left;padding:5px">{{transBusinessManage("captions.department")}}:</label>
                                <div class="col-md-8" style="padding:0">
                                    <select class="form-control" name="unit">
                                        <option value="">{{transBusinessManage("captions.all")}}</option>
                                        @foreach($units as $unit)
                                            <option value="{{$unit['id']}}" @if($unit['id'] == $unitId) selected @endif>{{ $unit['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 form-group" style="padding: 0">
                                <label style="float:left;padding:5px">{{transBusinessManage("captions.duty")}}:</label>
                                <div class="col-md-8" style="padding:0">
                                    <select class="form-control" name="pos">
                                        <option value="">{{transBusinessManage("captions.all")}}</option>
                                        @foreach($unitPos as $pos)
                                            <option value="{{$pos['id']}}" @if($pos['id'] == $posId) selected @endif>{{ $pos['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 form-group" style="padding: 0">
                                <label style="float:left;padding:5px">{{transBusinessManage("captions.shipname")}}:</label>
                                <div class="col-md-8" style="padding:0">
                                    <select class="form-control" name="ship">
                                        <option value="">{{transBusinessManage("captions.all")}}</option>
                                        @foreach($ships as $ship)
                                            <option value="{{$ship['RegNo']}}" @if($ship['RegNo'] == $shipId) selected @endif>{{ $ship['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 form-group" style="padding: 0">
                                <label style="float:left;padding:5px">{{transBusinessManage("captions.duty")}}:</label>
                                <div class="col-md-8" style="padding:0">
                                    <select class="form-control" name="duty">
                                        <option value="">{{transBusinessManage("captions.all")}}</option>
                                        @foreach($shipDuty as $duty)
                                            <option value="{{$duty['id']}}" @if($duty['id'] == $dutyId) selected @endif>{{ $duty['Duty'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 form-group" style="padding: 0">
                                <label style="float:left;padding:5px">!!:</label>
                                <div class="col-md-7" style="padding:0">
                                    <select class="form-control" name="party">
                                        <option value="">{{transBusinessManage("captions.all")}}</option>
                                        <option value="2" @if($party == 2) selected @endif>!!</option>
                                        <option value="1" @if(isset($party) && $party == 1) selected @endif>{{transBusinessManage("captions.nullity")}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style=" padding:0">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary search-btn" style="width: 80px"><i class="icon-search"></i>{{transBusinessManage("captions.search")}}</button>
                                </span>
                            </div>
                            <div id="moreSearchView" style="display: none">

                                <div class="col-sm-4 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.birthday")}}:</label>
                                    <div class="col-md-4 input-group" style="padding:0">
                                        <input name="start-date" class="form-control date-picker" type="text"
                                               data-date-format="yyyy-mm-dd" value="{{ $birthStart }}"/>
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                    <div class="col-md-1"> ~ </div>
                                    <div class="col-md-4 input-group" style="padding:0">
                                        <input name="end-date" class="form-control date-picker" type="text"
                                               data-date-format="yyyy-mm-dd" value="{{ $birthEnd }}"/>
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-2 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.name")}}:</label>
                                    <div class="col-md-8" style="padding:0">
                                        <input class="form-control" type="text" name="username" value="{{$username}}" >
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.enterdate")}}:</label>
                                    <div class="col-md-4 input-group" style="padding:0">
                                        <input name="entry-start" class="form-control date-picker" type="text"
                                               data-date-format="yyyy-mm-dd" value="{{ $entryStart }}"/>
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                    <div class="col-md-1"> ~ </div>
                                    <div class="col-md-4 input-group" style="padding:0">
                                        <input name="entry-end" class="form-control date-picker" type="text"
                                               data-date-format="yyyy-mm-dd" value="{{ $entryEnd }}"/>
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.address")}}:</label>
                                    <div class="col-md-8" style="padding:0">
                                        <input class="form-control" type="text" name="address" value="{{$address}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.handphone")}}:</label>
                                    <div class="col-md-8" style="padding:0;">
                                        <input class="form-control" type="text" name="phone" value="{{$phone}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.certnumber")}}:</label>
                                    <div class="col-md-6" style="padding:0">
                                        <input class="form-control" type="text" name="cardNum" value="{{$cardNum}}">
                                    </div>
                                </div>

                                <div class="col-sm-2 form-group" style="padding: 0">
                                    <label style="float:left;padding:5px">{{transBusinessManage("captions.status")}}:</label>
                                    <div class="col-md-8" style="padding:0">
                                        <select class="form-control" name="status">
                                            <option value="">{{transBusinessManage("captions.all")}}</option>
                                            <option value="1" @if($status == 1) selected @endif>{{transBusinessManage("captions.currentoffice")}}</option>
                                            <option value="0" @if(isset($status) && $status == 0) selected @endif>{{transBusinessManage("captions.dismiss")}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div style="overflow-y: scroll; width: 100%">
                        <table class="table table-striped table-bordered table-hover arc-std-table">
                            <thead>
                                <tr class="black br-hblue">
                                <th class="center" style="width:3%">{{transBusinessManage("captions.no")}}No</th>
                                <th class="center" style="width:10%">{{transBusinessManage("captions.departmentName")}}</th>
                                <th class="center" style="width:7%">{{transBusinessManage("captions.officeposition")}}</th>
                                <th class="center" style="width:6%">{{transBusinessManage("captions.shipname")}}</th>
                                <th class="center" style="width:6%">{{transBusinessManage("captions.duty")}}</th>
                                <th class="center" style="width:6%">{{transBusinessManage("captions.name")}}</th>
                                <th class="center" style="width:6%">{{transBusinessManage("captions.birthday")}}</th>
                                <th class="center" style="width:4%">{{transBusinessManage("captions.sex")}}</th>
                                <th class="center" style="width:5%">{{transBusinessManage("captions.month")}}</th>
                                <th class="center" style="width:20%">{{transBusinessManage("captions.address")}}</th>
                                <th class="center" style="width:9%">{{transBusinessManage("captions.handphone")}}</th>
                                <th class="center" style="width:7%">{{transBusinessManage("captions.certnumber")}}</th>
                                <th class="center" style="width:6%">{{transBusinessManage("captions.entercompanydate")}}</th>
                                <th class="center" style="width:4%">{{transBusinessManage("captions.remark")}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="overflow-x:hidden; overflow-y:auto; width:100%; height:67vh; border-bottom: 1px solid #eee">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                            <?php $index = 1;?>
                            @foreach($list as $user)
                                <tr>
                                    <td class="center" style="width:3%">{{$index++}}</td>
                                    <td class="center" style="width:10%">
                                        @if($user->memberType == 1){{$user->unitName}} @endif
                                    </td>
                                    <td class="center" style="width:7%">@if($user->memberType == 1){{$user->posName}}@endif</td>
                                    <td class="center" style="width:6%">@if($user->memberType == 2){{$user->unitName}}@endif</td>
                                    <td class="center" style="width:6%">
                                        @if(($user->memberType == 2) && !empty($user->unitName))
                                            {{$user->posName}}
                                        @elseif(($user->memberType == 2) && empty($user->unitName))
                                            {{transBusinessManage("captions.waitmember")}}
                                        @endif
                                    </td>
                                    <td class="center" style="width:6%"><a href="@if($user->memberType == 1)companyMemberInfo?uid={{$user->id}}
                                        @elseif($user->memberType == 2)/shipMember/registerShipMember?memberId={{$user->id}}
                                        @endif">{{$user->realname}}</a></td>
                                    <td class="center" style="width:6%">{{convert_date($user->birthday)}}</td>
                                    <td class="center" style="width:4%">@if($user->sex == 0){{transBusinessManage("captions.male")}} @else {{transBusinessManage("captions.female")}} @endif</td>
                                    <td class="center" style="width:5%">@if($user->isParty == 1)!! @endif</td>
                                    <td class="center" style="width:20%">{{$user->address}}</td>
                                    <td class="center" style="width:9%">{{$user->phone}}</td>
                                    <td class="center" style="width:7%">{{$user->cardNum}}</td>
                                    <td class="center" style="width:6%">{{convert_date($user->entryDate)}}</td>
                                    <td class="center" style="width:4%">@if($user->status == 0){{transBusinessManage("captions.dismiss")}} @else {{transBusinessManage("captions.register")}} @endif</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="modal-dialog" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header no-padding">
                            <div class="table-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                {{transBusinessManage("captions.company_structure")}}
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="tree" class="tree"></div>
                        </div>
                        <div class="modal-footer no-margin-top">
                            <button class="btn btn-sm btn-danger pull-left btn-confirm" data-dismiss="modal">
                                <i class="icon-check"></i>
                                {{transBusinessManage("captions.apply")}}
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            </div>
        </div>
    </div>


    <script>

        var totalList = jQuery.parseJSON('{!! $originStruct !!}');
        var menuHide = 1;

        $(function() {
            $('.search-btn').on('click', function () {
                filterBySearchItems();
            });

            $('.btn-struct').on('click', function () {
                $.post('getCompanyStructure', {'_token':token}, function (data) {

                });
            });

            $('.init-btn').on('click', function () {
                location.href = 'showTotalMemberList';
            });

            $('.more-btn').on('click', function () {
                if(menuHide == 1) {
                    $('#showSearch').removeClass('icon-chevron-down').addClass('icon-chevron-up');
                    $('#moreSearchView').fadeIn();
                } else {
                    $('#moreSearchView').fadeOut();
                    $('#showSearch').removeClass('icon-chevron-up').addClass('icon-chevron-down');
                }
                menuHide = !menuHide;
            })
        });

        function filterBySearchItems() {
            var unit = $('#unit_select').val();
            var ship = $('#ship_select').val();
            var party = $('#party_select').val();
            var status = $('#status_select').val();

            var param = '';
            if(unit.length > 0)
                param = '?unit=' + unit;
            if(ship.length > 0)
                param = param.length > 0 ? param + '&ship=' + ship : '?ship=' + ship;
            if(party.length > 0)
                param = param.length > 0 ? param + '&party=' + party : '?party=' + party;
            if(status.length > 0)
                param = param.length > 0 ? param + '&status=' + status : '?status=' + status;

            var url = 'showTotalMemberList' + (param.length > 0 ? param : '');
            location.href = url;
        }

    </script>

    <script src="{{asset('/assets/js/fuelux/data/fuelux.tree-structdata.js')}}"></script>
    <script src="{{asset('/assets/js/fuelux/fuelux.tree.member.min.js')}}"></script>

@endsection