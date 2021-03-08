@extends('layout.sidebar')

@section('content')
    <div class="main-content">

        <style>
            .col-md-2{width: 18.6666%;}
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4><b>{{transOrgManage("title.MemberInfo")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transOrgManage("captions.detailInfo")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3" style="text-align: right">
                    <h5 style="float: right"><a href="javascript:history.back()"><strong>{{transOrgManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#home">{{transOrgManage("captions.memberinfo_permissionmanage")}}</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab"
                                   href="@if (!isset($userid)) javascript:msg() @else #profile @endif">{{transOrgManage("captions.educationprofile")}}</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" @if (!isset($userid)) href="javascript:msg()" @else href="#info" @endif>{{transOrgManage("captions.family")}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane active">
                                <div class="col-md-12" style="border: 1px solid #cccccc">
                                    <div class="row">
                                        <table id="sample-table-1" class="table table-bordered">
                                            <tbody>
                                            <tr height="50dp">
                                                <td class="custom-td-label">{{transOrgManage("captions.loginID")}}</td>
                                                <td class="custom-td-text center">
                                                    {{ $userinfo['account'] }}
                                                </td>
                                                <td class="custom-td-label">{{transOrgManage("captions.name")}}</td>
                                                <td class="custom-td-text center">
                                                    {{ $userinfo['name'] }}
                                                </td>
                                                <td class="custom-td-label">{{transOrgManage("captions.birthday")}}</td>
                                                <td class="custom-td-text center">
                                                    {{ $profile['birthday'] }}
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.sex")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if($profile['sex']==1))
                                                    transOrgManage("captions.notice")女
                                                    @else
                                                    transOrgManage("captions.notice")男
                                                    @endif
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.notice")}}!!
                                                </td>
                                                <td class="custom-td-text center">
                                                     @if ($profile['isParty']==1)
                                                     transOrgManage("captions.notice")!!
                                                     @endif
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.notice")}}!!
                                                </td>
                                                <td class="custom-td-text center">
                                                    {{  $profile['partyDate'] }}
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label">!!</td>
                                                <td class="custom-td-text center">
                                                    {{ $userinfo['partyNum'] }}
                                                </td>
                                                <td class="custom-td-label">
                                                    !!
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if ((isset($profile))&&($profile['fromOrigin']==1)) 军人
                                                    @elseif ((isset($profile))&&($profile['fromOrigin']==2)) 劳动
                                                    @elseif ((isset($profile))&&($profile['fromOrigin']==3)) !!
                                                    @elseif ((isset($profile))&&($profile['fromOrigin']==4)) !!
                                                    @endif
                                                </td>
                                                <td class="custom-td-label">
                                                    사회성분
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if ((isset($profile))&&($profile['currOrigin']==1)) !!
                                                    @elseif ((isset($profile))&&($profile['currOrigin']==2)) !
                                                    @elseif ((isset($profile))&&($profile['currOrigin']==3)) !!
                                                    @elseif ((isset($profile))&&($profile['currOrigin']==4)) !!
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.office")}}
                                                </td>
                                                <td class="custom-td-text" center>
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.officePosition")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.identificationNumber")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    {{trim($profile['authCard'])}}
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.phone")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if(isset($profile)) {{trim($profile['telNum'])}} @endif
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                    E-mail
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if(isset($profile)) {{$profile['email']}} @endif
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.address")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if(isset($profile)) {{trim($profile['address'])}} @endif
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.phone")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if(isset($profile)) {{trim($profile['homeTel'])}} @endif
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.handphoneNumber")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    @if(isset($profile)) {{trim($profile['phone'])}} @endif
                                                </td>
                                                <td rowspan="2" colspan="2">
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.identificationNumber")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    {{trim($profile['cardNum'])}}
                                                </td>
                                                <td class="custom-td-label" colspan="1">
                                                {{transOrgManage("captions.enterDate")}}
                                                </td>
                                                <td class="custom-td-text center">
                                                    {{ $profile['entryDate'] }}
                                                </td>
                                            </tr>
                                            <tr height="50dp">
                                                <td class="custom-td-label center" colspan="1">
                                                {{transOrgManage("captions.exitDate")}}
                                                </td>
                                                <td class="custom-td-text">
                                                    {{ $profile['releaseDate'] }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="profile" class="tab-pane">
                                <table class="arc-std-table table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td colspan="9" class="custom-td-label"><h4>{{transOrgManage("captions.educationHistory")}}</h4></td>
                                    </tr>
                                    <tr>
                                        <td class="center" style="width:50px">No</td>
                                        <td class="center" style="width:150px">{{transOrgManage("captions.from")}}</td>
                                        <td class="center" style="width:150px">{{transOrgManage("captions.to")}}</td>
                                        <td class="center">{{transOrgManage("captions.schoolName")}}</td>
                                        <td class="center" style="width:15%">{{transOrgManage("captions.major")}}</td>
                                        <td class="center" style="width:70px">{{transOrgManage("captions.score")}}</td>
                                        <td class="center">{{transOrgManage("captions.skill")}}</td>
                                        <td class="center">{{transOrgManage("captions.remark")}}</td>
                                    </tr>
                                    </thead>
                                    <tbody id="school-table">
                                    <?php $index = 1;?>
                                    @foreach($careers as $career)
                                        <tr>
                                            <td data-id="{{$career['id']}}">{{$index++}}</td>
                                            <td style="width:150px">{{$career['startDate']}}</td>
                                            <td style="width:150px">{{$career['endDate']}}</td>
                                            <td>{{$career['school']}}</td>
                                            <td>{{$career['special']}}</td>
                                            <td>{{$career['endMark']}}</td>
                                            <td>{{$career['capacity']}}</td>
                                            <td>{{$career['remark']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="info" class="tab-pane">
                                <table class="table table-striped table-bordered table-hover" style="margin:0">
                                    <thead>
                                    <tr>
                                        <td colspan="10" class="custom-td-label"><h4>{{transOrgManage("captions.family")}}</h4></td>
                                    </tr>
                                    <tr>
                                        <td class="custom-td-label1" style="width: 50px">{{transOrgManage("captions.no")}}</td>
                                        <td class="custom-td-label1" style="width: 120px;">{{transOrgManage("captions.relationship")}}</td>
                                        <td class="custom-td-label1">{{transOrgManage("captions.name")}}</td>
                                        <td class="custom-td-label1" style="width: 7%">{{transOrgManage("captions.sex")}}</td>
                                        <td class="custom-td-label1" style="width: 8%">!!</td>
                                        <td class="custom-td-label1" style="width: 150px">{{transOrgManage("captions.birthday_age")}}</td>
                                        <td class="custom-td-label1">{{transOrgManage("captions.officePosition")}}</td>
                                        <td class="custom-td-label1">{{transOrgManage("captions.address")}}</td>
                                        <td class="custom-td-label1">{{transOrgManage("captions.remark")}}</td>
                                    </tr>
                                    </thead>
                                    <tbody id="family-table">
                                    <?php $index = 1;?>
                                    @foreach($familys as $family)
                                        <tr>
                                            <td data-id="{{$family['id']}}">{{$index++}}</td>
                                            <td data-relation="{{$family['relation']}}">{{$family['relationName']['releaseName']}}</td>
                                            <td>{{$family['name']}}</td>
                                            <td data-sex="{{$family['sex']}}">{{$family->sexName()}}</td>
                                            <td data-party="{{$family['isParty']}}">{{$family->partyName()}}</td>
                                            <td style="width:150px">{{$family['birthday']}}</td>
                                            <td>{{$family['pos']}}</td>
                                            <td>{{$family['address']}}</td>
                                            <td>{{$family['remark']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
