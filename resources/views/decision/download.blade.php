<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <META name=GENERATOR content="MSHTML 8.00.7601.17514">
    <META content="text/html; charset=UTF-8" http-equiv=Content-Type>
</head>
<body>
<form>
    <style>P {
            MARGIN-TOP: 1px; MARGIN-BOTTOM: 1px
        }
    </style>

    <table border=0 cellSpacing=0 cellPadding=0 width=530 height=302>
        <tr>
            <td height=65 vAlign=top width="100%">
                <table border=1 cellSpacing=0 width="100%" height=100 >
                    <div></div>
                    <tr>
                        <?php foreach ($decidedInfos as $decidedInfo): ?>
                        <td height=92 width="20%" align=middle>
                            <table style="TEXT-ALIGN: center; FONT-SIZE: 12px" border=1
                                   cellSpacing=0 width="100%" align=center>
                                <tr>
                                    <td height=15 align=middle>{{transDecideManage("captions.authorizer")}}</td>
                                </tr>
                                <tr>
                                    @if(empty($decidedInfo['agentUser']))
                                        <td height=36 align=middle>{{ $decidedInfo['pos'] }}&nbsp;{{ $decidedInfo['name'] }}</td>
                                    @else
                                        <td height=36 align=middle>{{$decidedInfo['pos']}} {{$decidedInfo['name']}}/{{$decidedInfo['agentPos']}} {{$decidedInfo['agentUser']}}</td>
                                    @endif

                                </tr>
                                <tr>
                                    <td height=42 align=middle>
                                        @if(!empty($decidedInfo['stamp']))
                                            <img src="/uploads/stamp/{{ $decidedInfo['stamp'] }}" width="100" height="35" border="0" />
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <?php endforeach; ?>
                        <td height=92 width=40 colSpan=2>&nbsp;</td>
                    </tr>
                    <div></div>
                </table>
            </td>
        </tr>

        <tr>
            <td height=6></td></tr>
        <tr>
            <td height=26 vAlign=top width="100%" >
                <table style="FONT-SIZE: 12px" border=1 cellSpacing=0 width="100%">
                    <tr>
                        <td  width="9%" height="28" align=middle>{{transDecideManage("captions.docNumber")}}</td>
                        <td  width="22%">{{$reportInfo['docNo']}}</td>

                        <td   width="9%" align="middle">{{transDecideManage("captions.draftDate")}}</td>
                        <td  width="35%">{{ $reportInfo['create_at'] }}</td>

                        <td  width="9%" align="middle">{{transDecideManage("captions.savePeriod")}}</td>
                        <td width="18%">
                            @if($reportInfo['storage'] < 13)
                                {{$reportInfo['storage']}}月
                            @else
                                {{$reportInfo['storage'] - 12}}年
                            @endif
                        </td>
                    </tr>

                    <tr height="28">
                        <td  width="9%" height="28" align="middle">{{transDecideManage("captions.departmentName")}}</td>
                        <td width="22%">{{$creator['unit']}}</td>
                        <td  width="9%" align="middle">{{transDecideManage("captions.approver")}}</td>
                        <td  width="35%">{{$creator['name']}}</td>
                        <td  width="9%" align="middle">{{transDecideManage("captions.officePosition")}}</td>
                        <td  width="18%">{{$creator['pos']}}</td>
                    </tr>

                    <?php if (!empty($recvUsersInfo)) { ?>
                    <tr height="28">
                        <td width="9%" align="center">{{transDecideManage("captions.receiver")}}</td>
                        <td  width="91%" colspan="5" >
                        <?php
                            $recvUsers = '';
                            foreach ($recvUsersInfo as $recvUser) {
                                $recvUsers .= ' '. $recvUser['name'];

                                if($recvUser['isAdmin']==1) {
                                    $recvUsers .= '(管理者)';
                                } else {
                                    $recvUsers .= '(一般使用者)';
                                }
                            }
                            echo $recvUsers;
                        ?>
                        </td>

                    </tr>
                    <?php	}
                    ?>

                    <tr height="28">
                        <td  width="9%" align="center">{{transDecideManage("captions.sendDepartment")}}</td>
                        <td  width="91%" colspan="5" >{{$reportInfo['submitUnit']}}</td>
                    </tr>

                    <tr height="28">
                        <td  width="9%" align="center" >{{transDecideManage("captions.approveName")}}</td>
                        <td  width="91%" colspan="5" >{{$reportInfo['title']}}</td>
                    </tr>

                    <tr >
                        <td colspan="6">
                            {!! $reportInfo['content'] !!}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr><td height=6></td></tr>
        <tr>
            <td height=46 vAlign=top>
                <table style="LINE-HEIGHT: 20px; FONT-SIZE: 12px" border=1 cellSpacing=0
                       width="100%" height=20>

                    <thead>
                    <tr class="custom-td-label1">
                        <td  height=22 width="9%" align="middle">{{transDecideManage("captions.no")}}</td>
                        <td  width="15%" align="middle">{{transDecideManage("captions.authorizer")}}</td>
                        <td  width="20%" align="middle">{{transDecideManage("captions.authorState")}}</td>
                        <td  width="35%" align="middle">{{transDecideManage("captions.authorDate")}}</td>
                        <td  width="20%" align="middle">{{transDecideManage("captions.authorOpinion")}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 1; ?>
                    @foreach($decidedInfos as $decider)
                        @if($decider['isDecide'] == 1)
                            <tr class="custom-td-text1">
                                <td style="text-align: center">{{$index}}</td>
                                <td style="text-align: center">
                                    @if(!empty($decider['agentUser']))
                                        {{$decider['pos']}} {{$decider['name']}} / {{$decider['agentPos']}} {{$decider['agentUser']}}
                                    @else
                                        {{$decider['name']}}
                                    @endif
                                </td>
                                <td style="text-align: center">{{$decider['state']}}</td>
                                <td style="text-align: center">{!! convert_datestr($decider['stampDate']) !!}</td>
                                <td style="text-align: center">{!! nl2br($decider['note']) !!}</td>
                            </tr>
                            <?php $index++ ?>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table></form></body></html>
<?php
