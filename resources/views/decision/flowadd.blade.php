@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-6">
                    <h4><b>{{transDecideManage("title.EnvironmentSetting")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>{{transDecideManage("title.ApproveFlow")}}
                            <i class="icon-double-angle-right"></i>@if(!isset($flowId))追加 @else 修改 @endif
                            <div id="flowId" style="display: none">@if(isset($flowId)) {{$flowId}} @else 0 @endif</div>
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6">
                    <h5 style="float: right"><a href="javascript:history.back()"><strong>{{transDecideManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="widget-box">
                        <div class="widget-header header-color-blue2">
                            <h4 class="lighter smaller col-sm-4">{{transDecideManage("captions.createProcess")}}</h4>
                        </div>
                        <div class="widget-body" style="background: url(/assets/img/breadcrumbs.png) repeat">
                            <div class="widget-main padding-8">
                                <div class="row">
                                    <div class="space-4"></div>
                                    <div class="col-sm-6">
                                        <div class="col-md-3" style="padding-top:5px;text-align: right;width:22%">{{transDecideManage("captions.userList")}}:</div>
                                        <div class="col-md-8 tree" style="border:1px solid #eee; height:330px">
                                            {!! $result !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">{{transDecideManage("captions.approveProcessName")}}</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="flowname" value ="@if(isset($flowinfo)) {{$flowinfo['flow_name']}} @endif" id="flowname" style="width: 100%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-2"></div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button class="btn btn-xs btn-white" id="add_decide"  onclick="OnAddUsers('decide')">
                                                {{transDecideManage("captions.addAuthor")}}
                                                    <i class="icon-arrow-right icon-on-right"></i>
                                                </button>
                                                <div class="space-2"></div>
                                                <button class="btn btn-xs btn-white" id="del_decide"  onclick="OnRemoveUsers('decide')">
                                                    <i class="icon-trash bigger-50"></i>
                                                    {{transDecideManage("captions.delete")}}
                                                </button>
                                            </div>
                                            <div class="col-sm-7">
                                                <select class="flow_userlist" id="decide" multiple="multiple" style="width: 100%; height: 150px">
                                                    <?php
                                                    if(isset($flowinfo))
                                                    {
                                                        $idList = explode(',', $flowinfo['decideUsers']);
                                                        $nameList = explode(',', $flowinfo['decideUserlist']);
                                                        $nameIndex = 0;
                                                        foreach($idList as $decId) {
                                                            if(empty($decId))
                                                                continue;
                                                    ?>
                                                    <option value="{{$decId}}">{{$nameList[$nameIndex]}}</option>
                                                    <?php
                                                            $nameIndex++;
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-1">
                                                <button class="btn btn-xs btn-white" id="up_decide"  onclick="OnMoveUpUsers('decide')">
                                                    <i class="icon-caret-up"></i>
                                                </button>
                                                <div class="space-2"></div>
                                                <button class="btn btn-xs btn-white" id="down_decide"  onclick="OnMoveDownUsers('decide')">
                                                    <i class="icon-caret-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button class="btn btn-xs btn-white" id="add_recv"  onclick="OnAddUsers('recv')">
                                                {{transDecideManage("captions.addReceiver")}}
                                                    <i class="icon-arrow-right icon-on-right"></i>
                                                </button>
                                                <div class="space-2"></div>
                                                <button class="btn btn-xs btn-white" id="del_recv"  onclick="OnRemoveUsers('recv')">
                                                    <i class="icon-trash bigger-50"></i>
                                                    {{transDecideManage("captions.delete")}}
                                                </button>
                                            </div>
                                            <div class="col-sm-7">
                                                <select class="flow_userlist" id="recv" multiple="multiple" style="width: 100%; height: 150px">
                                                    <?php
                                                        if(isset($flowinfo))
                                                        {
                                                            $idList = explode(',', $flowinfo['recvUsers']);
                                                            $nameList = explode(',', $flowinfo['recvUserlist']);
                                                            $nameIndex = 0;
                                                            foreach($idList as $rcvId) {
                                                                if(empty($rcvId))
                                                                    continue;
                                                    ?>
                                                                <option value="{{$rcvId}}">{{$nameList[$nameIndex]}}</option>
                                                    <?php
                                                                $nameIndex++;
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-1">
                                                <button class="btn btn-xs btn-white" id="up_recv"  onclick="OnMoveUpUsers('recv')">
                                                    <i class="icon-caret-up"></i>
                                                </button>
                                                <div class="space-2"></div>
                                                <button class="btn btn-xs btn-white" id="down_recv"  onclick="OnMoveDownUsers('recv')">
                                                    <i class="icon-caret-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="space-6"></div>
                                            <div class="col-sm-6 col-lg-offset-6"/>
                                            <button class="btn btn-sm" id="id-btn-canceldialog"  onclick="onCancel()">
                                                <i class="icon-undo"></i>
                                                {{transDecideManage("captions.cancel")}}
                                            </button>
                                            <button class="btn btn-info btn-sm" id="id-btn-adddialog"  onclick="OnAddflow()">
                                                <i class="icon-ok"></i>
                                                {{transDecideManage("captions.confirm")}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div><!-- /.main-content -->

    <script src="{{asset('/assets/js/fuelux/data/fuelux.tree-sampledata.js')}}"></script>
    <script src="{{asset('/assets/js/fuelux/fuelux.tree.custom.min.js')}}"></script>

    <script type="text/javascript">
        var cindex = 0;
        var findex = 0;
        var token = '<?php echo csrf_token() ?>';

        function onCancel() {
            history.back();
        }

        function OnAddflow(){
            var tmpItem = document.getElementById('flowname');
            var flow_name = new String;
            flow_name = tmpItem.value;
            flow_name = flow_name.trim();

            if(flow_name.length == 0)
            {
                alert('请输入批准流程的名称。');
                return;
            }

            var listitem = document.getElementById('decide');
            var oplist = listitem.getElementsByTagName('option');
            var decideIds = new String;
            if(oplist.length == 0)
            {
                alert('请输入批准人。');
                return;
            } else {
                for( var i = 0; i < oplist.length; i++)
                {
                    decideIds = decideIds.length < 1 ? oplist[i].getAttribute('value') : decideIds + "," + oplist[i].getAttribute('value');
                }
            }

            listitem = document.getElementById('recv');
            oplist = listitem.getElementsByTagName('option');
            var recvIds = new String;
            if(oplist.length > 0)
            {
                for( var i = 0; i < oplist.length; i++)
                {
                    recvIds = recvIds + "," + oplist[i].getAttribute('value');
                }
                recvIds += ',';
            }

            var flowId = document.getElementById('flowId').innerText;
            var flow_info = flowId;
            flow_info +='_'+flow_name;
            flow_info +='_'+decideIds;
            flow_info +='_'+recvIds;

            var flowinfo=flow_info.toString();

            $.post("flowsave", {_token:token, flowinfo:flow_info}, function(data) {

                if(data['result'] == 'success')
                {
                    alert('保存成功！');
                    window.location.href = 'flowmanage?menuId=19&submenu=23';
                }else if(data['result']=='repetition')
                {
                    alert('已经存在同名的批准流程。请重新输入批准流程。');
                }
            });
        }

        function OnAddUsers(id){
            //선택된 리용자들을 검색
            var checkitems = document.getElementsByClassName('chkUser');
            var userlist = new Array();
            for (var i = 0; i < checkitems.length; i++) {
                if(checkitems[i].checked == true)
                {
                    var user = new Array();
                    user['id'] = checkitems[i].parentNode.id;
                    user['realname'] = checkitems[i].nextElementSibling.innerText;
                    userlist.push(user);
                }

                checkitems[i].checked = false;
            }
            //선택된 리용자들을 목록에 추가
            var listitem = document.getElementById(id);
            for( var i = 0; i < userlist.length; i++)
            {
                if( existsOfListView(id, userlist[i]['id'])) continue;

                var node = document.createElement('option');
                node.setAttribute('value', userlist[i]['id']);

                var textNode = document.createTextNode(userlist[i]['realname']);
                node.appendChild(textNode);

                listitem.appendChild(node);
            }
        }

        function existsOfListView(list_id, val)
        {
            var listitem = document.getElementById(list_id);
            var oplist = listitem.getElementsByTagName('option');
            for( var i = 0; i < oplist.length; i++)
            {
                if( oplist[i].getAttribute('value') == val) return true;
            }
            return false;
        }

        function OnRemoveUsers(id){
            var listitem = document.getElementById(id);
            var oplist = listitem.getElementsByTagName('option');
            var selItemlist= new Array();
            for( var i = 0; i < oplist.length; i++)
            {
                if(oplist[i].selected == true) selItemlist.push(oplist[i]);
            }

            var n = selItemlist.length;
            for( var i = 0; i < n; i++)
            {
                listitem.removeChild(selItemlist[i]);
            }
        }

        function OnMoveUpUsers(id){
            var listitem = document.getElementById(id);
            var oplist = listitem.getElementsByTagName('option');
            var selItemlist= new Array();
            for( var i = 0; i < oplist.length; i++)
            {
                if(oplist[i].selected == true)
                {
                    var selitem = new Array();
                    selitem['pos'] = i;
                    selitem['item'] = oplist[i];

                    selItemlist.push(selitem);
                }
            }

            if(selItemlist[0]['pos'] == 0) return;

            var n = selItemlist.length;
            for( var i = 0; i < n; i++)
            {
                var cur_item = selItemlist[i]['item'];
                var prev_item = selItemlist[i]['item'].previousElementSibling;
                var val = prev_item.getAttribute('value');
                var txt = prev_item.innerHTML;;

                prev_item.setAttribute('value', cur_item.getAttribute('value'));
                prev_item.innerHTML = cur_item.innerHTML;
                prev_item.selected = true;
                cur_item.setAttribute('value', val);
                cur_item.innerHTML = txt;
                cur_item.selected= false;
            }

        }

        function OnMoveDownUsers(id){
            var listitem = document.getElementById(id);
            var oplist = listitem.getElementsByTagName('option');
            var selItemlist= new Array();
            for( var i = oplist.length-1; i>=0; i--)
            {
                if(oplist[i].selected == true)
                {
                    var selitem = new Array();
                    selitem['pos'] = i;
                    selitem['item'] = oplist[i];

                    selItemlist.push(selitem);
                }
            }

            if(selItemlist[0]['pos'] == oplist.length-1) return;

            var n = selItemlist.length;
            for( var i = 0; i < n; i++)
            {
                var cur_item = selItemlist[i]['item'];
                var next_item = selItemlist[i]['item'].nextElementSibling;
                var val = next_item.getAttribute('value');
                var txt = next_item.innerHTML;;

                next_item.setAttribute('value', cur_item.getAttribute('value'));
                next_item.innerHTML = cur_item.innerHTML;
                next_item.selected = true;
                cur_item.setAttribute('value', val);
                cur_item.innerHTML = txt;
                cur_item.selected= false;
            }
        }

        $(document).ready(function () {
            $("#tree").treeview({//나무보기구조를 구축한다.
                toggle: function () {
                }
            });
        });


    </script>
@stop
