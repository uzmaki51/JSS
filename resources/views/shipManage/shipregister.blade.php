@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <style>
            .custom-td-report-text{
                width: 25%;
            }
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>船舶规范</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            @if(isset($shipinfo))
                                修改
                            @else
                                登记
                            @endif
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6">
                    <div class="col-md-8 alert alert-block alert-success center" @if(is_null($status)) style="display: none @endif">
                        <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                        <strong id="msg-content"> @if($status == 'success') 保存成功。 @else {{ $status }} @endif</strong>
                    </div>
                </div>
                <div class="col-sm-3">
                    <h5 style="float: right"><a href="shipinfo"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">

                <div id="item-manage-dialog" class="hide">
                </div><!-- #dialog-message -->
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="{{ $tabName == '#general' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#general" onclick="ShowTabPage('#general')">
                                    {{ transShipManager('tabMenu.General') }}
                                </a>
                            </li>

                            <li class="{{ $tabName == '#hull' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#hull" onclick="ShowTabPage('#hull')">
                                    {{ transShipManager('tabMenu.Hull/Cargo') }}
                                </a>
                            </li>
                            <li class="{{ $tabName == '#machiery' ? 'machiery' : '' }}">
                                <a data-toggle="tab" href="#machiery" onclick="ShowTabPage('#machiery')">
                                    {{ transShipManager('tabMenu.Machinery') }}
                                </a>
                            </li>
                            <!--li class="{{ $tabName == '#saftey' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#safety" onclick="ShowTabPage('#safety')">
                                    {{ transShipManager('tabMenu.MSMC') }}
                                </a>
                            </li>
                            <li class="{{ $tabName == '#photo' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#photo" onclick="ShowTabPage('#photo')">
                                    {{ transShipManager('tabMenu.Photo') }}
                                </a>
                            </li-->
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="general" class="tab-pane {{ $tabName == '#general' ? 'active' : '' }}">
                            @if($tabName == '#general')
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                        <div id="hull" class="tab-pane {{ $tabName == '#hull' ? 'active' : '' }}">
                            @if($tabName == '#hull')
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                        <div id="machiery" class="tab-pane {{ $tabName == '#machiery' ? 'active' : '' }}">
                            @if($tabName == '#machiery')
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                        <div id="safety" class="tab-pane {{ $tabName == '#saftey' ? 'active' : '' }}">
                            @if($tabName == '#safety')
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                        <div id="photo" class="tab-pane {{ $tabName == '#saftey' ? 'photo' : '' }}">
                            @if($tabName == '#photo')
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                    </div>
                </div><!-- /span -->

                <div class="vspace-xs-12"></div>
            </div>
            <a href="#modify-dialog" role="button" class="hidden" data-toggle="modal" id="dialog-show-btn"></a>
            <div id="modify-dialog" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" id="item-modify-dialog">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable-photo.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ajaxfileupload.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.colorbox-min.js') }}"></script>

    <script type="text/javascript">

        var token = '{!! csrf_token() !!}';
        var shipId = '{!! $shipInfo['id'] !!}';
        var activeTabName = '{{ $tabName }}';
        var preTabName = activeTabName;

        //editables on first profile page
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary editable-submit"><i class="icon-ok icon-white"></i></button>';


        function ShowTabPage(tabName) {
            if(shipId.length < 1) {
                $('#msg-content').html("  现需要保存一般信息(Ship's Particulars)。  ");
                $('.alert').show();
                return;
            }

            if(preTabName == '#general' && tabName != '#general') {
                bootbox.confirm({
                    message: "需要保存内容吗吗?",
                    buttons: {
                        confirm: {
                            label: '是',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: '不是',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result) {
                            $('input[name=_tabName]').val(preTabName);
                            $('#validation-form').submit();
                        }

                        $('#general').html('');
                        $('#hull').html('');
                        $('#machiery').html('');
                        $('#safety').html('');
                        $('#photo').html('');
                        $.post("shipDataTabPage", {'_token':token, 'shipId':shipId, 'tabName':tabName}, function(data) {
                            switch (tabName) {
                                case '#general':
                                    $('#general').html(data);
                                    $('.chosen-select').chosen();
                                    $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                                        $(this).prev().focus();
                                    });
                                    break;
                                case '#hull':
                                    $('#hull').html(data);
                                    break;
                                case '#machiery':
                                    $('#machiery').html(data);
                                    break;
                                case '#safety':
                                    $('#safety').html(data);
                                    break;
                                case '#photo':
                                    $('#photo').html(data);
                                    bindUploadPhoto();
                                    break;
                            }
                        });
                    }
                });
            } else {
                $('#general').html('');
                $('#hull').html('');
                $('#machiery').html('');
                $('#safety').html('');
                $('#photo').html('');
                $.post("shipDataTabPage", {'_token':token, 'shipId':shipId, 'tabName':tabName}, function(data) {
                    switch (tabName) {
                        case '#general':
                            $('#general').html(data);
                            $('.chosen-select').chosen();
                            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                                $(this).prev().focus();
                            });
                            break;
                        case '#hull':
                            $('#hull').html(data);
                            break;
                        case '#machiery':
                            $('#machiery').html(data);
                            break;
                        case '#safety':
                            $('#safety').html(data);
                            break;
                        case '#photo':
                            $('#photo').html(data);
                            bindUploadPhoto();
                            break;
                    }
                });
            }

            preTabName = tabName;

        }

        function editPostion() {
            var obj = $(this).closest('tr').children();
            var priority = obj.eq(3).text();
            var dutyId = obj.eq(1).text();
            var codeId = obj.eq(2).text();

            var selectHtml = '<select class="form-control">';
            for (var i = 1; i < maxPriority + 1; i++) {
                selectHtml += '<option value="' + i + '"';
                if (priority == i) selectHtml += ' selected ';
                selectHtml += '>' + i + '</option>';
            }
            selectHtml += '</select>';
            obj.eq(3).html(selectHtml);

            selectHtml = '<select class="form-control chosen-select">';
            for (var i = 0; i < positionList.length; i++) {
                var pos = positionList[i];
                selectHtml += '<option value="' + pos.id + '"';
                if (pos.id == dutyId) selectHtml += ' selected ';
                selectHtml += '>' + pos.duty + '</option>';
            }
            selectHtml += '</select>';
            obj.eq(4).html(selectHtml);

            selectHtml = '<select class="form-control chosen-select" value="' + codeId + '">';
            for (var i = 0; i < codeList.length; i++) {
                var code = codeList[i];
                selectHtml += '<option value="' + code.id + '"';
                if (code.id == codeId) selectHtml += ' selected ';
                selectHtml += '>' + code.regCode + '</option>';
            }
            selectHtml += '</select>';
            obj.eq(5).html(selectHtml);
            obj.eq(6).html('<input type="number" class="form-control" value="' + obj.eq(6).text() + '">');
            obj.eq(7).find('.row_edit').hide();
            obj.eq(7).find('.row_apply').show();

            $('.chosen-select').chosen();
        }

        function cancelPositionEdit() {
            var obj = $(this).closest('tr').children();
            var dutyId = obj.eq(1).text();
            var codeId = obj.eq(2).text();

            obj.eq(3).html(obj.eq(3).find('select').val());
            var htmlText = '';
            for (var i = 0; i < positionList.length; i++) {
                var pos = positionList[i];
                if (pos.id == dutyId) {
                    htmlText = pos.duty;
                    break;
                }
            }
            obj.eq(4).html(htmlText);
            htmlText = '';
            for (var i = 0; i < codeList.length; i++) {
                var code = codeList[i];
                if (code.id == codeId) {
                    htmlText = code.regCode;
                    break;
                }
            }
            obj.eq(5).html(htmlText);
            obj.eq(6).html(obj.eq(6).find('input').val());
            obj.eq(7).find('.row_edit').show();
            obj.eq(7).find('.row_apply').hide();
        }

        function savePositionEdit() {
            var obj = $(this).closest('tr').children();
            var posId = obj.eq(0).text();
            var personCount = obj.eq(6).find('input').val() * 1;
            if (personCount < 1) {
                $.gritter.add({
                    title: '错误',
                    text: '必要输入数字。',
                    class_name: 'gritter-error'
                });
                return;
            }
            $.post('saveShipSafetyData',
                    {
                        '_token': token,
                        'shipId': shipId,
                        'id': posId,
                        'Priority': obj.eq(3).find('select').val(),
                        'DutyID': obj.eq(4).find('select').val(),
                        'STCWRegCodeID': obj.eq(5).find('select').val(),
                        'PersonNum': obj.eq(6).find('input').val(),
                    }, function (data) {
                        var returnCode = parseInt(data);
                        if (returnCode > 0) {
                            var dutyId = obj.eq(4).find('select').val();
                            var codeId = obj.eq(5).find('select').val();
                            obj.eq(1).html(dutyId);
                            obj.eq(2).html(codeId);
                            obj.eq(3).html(obj.eq(3).find('select').val());
                            var htmlText = '';
                            for (var i = 0; i < positionList.length; i++) {
                                var pos = positionList[i];
                                if (pos.id == dutyId) {
                                    htmlText = pos.duty;
                                    break;
                                }
                            }
                            obj.eq(4).html(htmlText);
                            htmlText = '';
                            for (var i = 0; i < codeList.length; i++) {
                                var code = codeList[i];
                                if (code.id == codeId) {
                                    htmlText = code.regCode;
                                    break;
                                }
                            }
                            obj.eq(5).html(htmlText);
                            obj.eq(6).html(obj.eq(6).find('input').val());
                            obj.eq(7).find('.row_edit').show();
                            obj.eq(7).find('.row_apply').hide();
                        } else {
                            var dutyId = obj.eq(4).find('select').val();
                            var posName = '';
                            for (var i = 0; i < positionList.length; i++) {
                                var pos = positionList[i];
                                if (pos.id == dutyId) {
                                    posName = pos.duty;
                                    break;
                                }
                            }
                            $.gritter.add({
                                title: '错误',
                                text: posName + ' 职务已经登记了。',
                                class_name: 'gritter-error'
                            });
                        }
                    });
        }

        function addPostion() {
            var obj = $(this).closest('tr').children();
            var dutyId = obj.eq(4).find('select').val();
            if (dutyId == '0') {
                $.gritter.add({
                    title: '错误',
                    text: '请选择上船职务。',
                    class_name: 'gritter-error'
                });
                return;
            }
            var codeId = obj.eq(5).find('select').val();
            if (codeId == '0') {
                $.gritter.add({
                    title: '错误',
                    text: '请选择STCW规定代码。',
                    class_name: 'gritter-error'
                });
                return;
            }
            var personCount = obj.eq(6).find('input').val() * 1;
            if (personCount < 1) {
                $.gritter.add({
                    title: '错误',
                    text: '必要输入数字。',
                    class_name: 'gritter-error'
                });
                return;
            }

            $.post('saveShipSafetyData',
                    {
                        '_token': token,
                        'shipId': shipId,
                        'Priority': obj.eq(3).find('select').val(),
                        'DutyID': obj.eq(4).find('select').val(),
                        'STCWRegCodeID': obj.eq(5).find('select').val(),
                        'PersonNum': obj.eq(6).find('input').val(),
                    }, function (data) {
                        var returnCode = parseInt(data);
                        if (returnCode > 0) {
                            obj.eq(0).text(returnCode);
                            var dutyId = obj.eq(4).find('select').val();
                            var codeId = obj.eq(5).find('select').val();
                            obj.eq(1).html(dutyId);
                            obj.eq(2).html(codeId);
                            obj.eq(3).html(obj.eq(3).find('select').val());
                            var htmlText = '';
                            for (var i = 0; i < positionList.length; i++) {
                                var pos = positionList[i];
                                if (pos.id == dutyId) {
                                    htmlText = pos.duty;
                                    break;
                                }
                            }
                            obj.eq(4).html(htmlText);
                            htmlText = '';
                            for (var i = 0; i < codeList.length; i++) {
                                var code = codeList[i];
                                if (code.id == codeId) {
                                    htmlText = code.regCode;
                                    break;
                                }
                            }
                            obj.eq(5).html(htmlText);
                            obj.eq(6).html(obj.eq(6).find('input').val());
                            var btnHtml = '<div class="row_edit">' +
                                    '<a class="blue duty_edit"><i class="icon-edit bigger-130"></i></a>' +
                                    '<a class="red duty_delete" style="margin-left:10px"><i class="icon-trash bigger-130"></i></a>' +
                                    '</div>' +
                                    '<div class="row_apply" style="display: none">' +
                                    '<a class="blue duty_save"><i class="icon-save bigger-130"></i></a>' +
                                    '<a class="red duty_cancel" style="margin-left:10px"><i class="icon-remove bigger-130"></i></a>' +
                                    '</div>';
                            obj.eq(7).html(btnHtml);
                            obj.eq(7).addClass("center");

                            maxPriority++;
                            var tbody = document.getElementById('pos_table');
                            var newtr = document.createElement('tr');
                            var leng = tbody.children.length;
                            if (leng < 1)
                                index = 1;
                            else
                                index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;

                            var newHtml = '<td class="hidden"></td>' + '<td class="hidden"></td>' + '<td class="hidden"></td>' +
                                    '<td class="center"><select class="form-control"';

                            for (var i = 1; i < maxPriority + 1; i++) {
                                newHtml += '<option value="' + i + '">' + i + '</option>';
                            }
                            var mm = maxPriority + 1;
                            newHtml += '<option value="' + mm + '" selected>' + mm + '</option></select></td>';

                            newHtml += '<td class="center"><select class="form-control chosen-select"><option value="0"></option>';
                            for (var i = 0; i < positionList.length; i++) {
                                var pos = positionList[i];
                                newHtml += '<option value="' + pos.id + '">' + pos.duty + '</option>';
                            }
                            newHtml += '</select></td>';

                            newHtml += '<td class="center"><select class="form-control chosen-select"><option value="0"></option>';
                            for (var i = 0; i < codeList.length; i++) {
                                var code = codeList[i];
                                newHtml += '<option value="' + code.id + '">' + code.regCode + '</option>';
                            }
                            newHtml += '</select></td>';
                            newHtml += '<td class="center"><input type="number" class="form-control"></td>';
                            newHtml += '<td class="action-buttons"><a class="blue add_duty"><i class="icon-plus bigger-130"></i></a></td>';
                            newtr.innerHTML = newHtml;
                            tbody.appendChild(newtr);

                            unbindButtonAction()
                            bindButtonAction();
                        } else {
                            var posName = '';
                            for (var i = 0; i < positionList.length; i++) {
                                var pos = positionList[i];
                                if (pos.id == dutyId) {
                                    posName = pos.duty;
                                    break;
                                }
                            }
                            $.gritter.add({
                                title: '错误',
                                text: posName + ' 职务已经登记了。',
                                class_name: 'gritter-error'
                            });
                        }
                    });
        }

        function deletePotion() {
            var obj = $(this).closest('tr').children();
            var posId = obj.eq(0).text();
            var dutyName = obj.eq(4).text();

            bootbox.confirm(dutyName + " 真要删掉吗?", function (result) {
                if (result) {
                    $.post('deleteShipSafetyData', {'_token': token, 'posId': posId}, function (result) {
                        var code = parseInt(result);
                        if (code > 0) {
                            var tbody = document.getElementById('pos_table');
                            var len = tbody.children.length;
                            var row = 0;
                            for (; row < len; row++) {
                                var tds = tbody.children[row];
                                var rowPosId = Math.floor(tds.children[0].innerText);
                                if (posId == rowPosId)
                                    break;
                            }
                            tbody.deleteRow(row);
                            $.gritter.add({
                                title: '成功',
                                text: dutyName + ' 删掉成功!',
                                class_name: 'gritter-success'
                            });
                        } else {
                            $.gritter.add({
                                title: '错误',
                                text: dutyName + ' 是已经被删掉的。',
                                class_name: 'gritter-error'
                            });
                        }
                    });
                }
            });
        }

        function bindButtonAction() {
            $('.duty_edit').click(editPostion);
            $('.duty_cancel').click(cancelPositionEdit);
            $('.duty_save').click(savePositionEdit);
            $('.duty_delete').click(deletePotion);
            $('.add_duty').click(addPostion);

            $('.chosen-select').chosen();
        }

        function unbindButtonAction() {
            $('.duty_edit').unbind('click', editPostion);
            $('.duty_cancel').unbind('click', cancelPositionEdit);
            $('.duty_save').unbind('click', savePositionEdit);
            $('.duty_delete').unbind('click', deletePotion);
            $('.add_duty').unbind('click', addPostion);
        }

        function bindUploadPhoto() {
            var colorbox_params = {
                reposition:true,
                scalePhotos:true,
                scrolling:false,
                previous:'<i class="icon-arrow-left"></i>',
                next:'<i class="icon-arrow-right"></i>',
                close:'&times;',
                current:'{current} of {total}',
                maxWidth:'100%',
                maxHeight:'100%',
                onOpen:function(){
                    document.body.style.overflow = 'hidden';
                },
                onClosed:function(){
                    document.body.style.overflow = 'auto';
                },
                onComplete:function(){
                    $.colorbox.resize();
                }
            };

            $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);

            // *** editable avatar *** //
            try {//ie8 throws some harmless exception, so let's catch it

                //it seems that editable plugin calls appendChild, and as Image doesn't have it, it causes errors on IE at unpredicted points
                //so let's have a fake appendChild for it!
                if( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ) Image.prototype.appendChild = function(el){}

                var last_gritter
                $('#shipPhoto').editable({
                    type: 'image',
                    name: 'avatar',
                    value: null,
                    image: {
                        //specify ace file input plugin's options here
                        btn_choose: '选择照片',
                        droppable: true,
                        /**
                         //this will override the default before_change that only accepts image files
                         before_change: function(files, dropped) {
								return true;
							},
                         */

                        //and a few extra ones here
                        name: 'avatar',//put the field name here as well, will be used inside the custom plugin
                        max_size: 2000000,//~2MB
                        on_error : function(code) {//on_error function will be called when the selected file has a problem
                            if(last_gritter) $.gritter.remove(last_gritter);
                            if(code == 1) {//file format error
                                last_gritter = $.gritter.add({
                                    title: '文件形式不是照片文件!',
                                    text: '文件必须是  jpg|gif|png 形式的文件!',
                                    class_name: 'gritter-error'
                                });
                            } else if(code == 2) {//file size rror
                                last_gritter = $.gritter.add({
                                    title: '照片大小太大了',
                                    text: '文件的大小不得超过2MB!',
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
                        uploadImage();
                    }
                });
            }catch(e) {
                alert(e);
            }
        }

        function uploadImage() {
            var dataArray = new Array();
            dataArray[0] = token;
            dataArray[1] = shipId;
            $.ajaxFileUpload({
                url: '{{url('shipManage/uploadShipPicture')}}',
                secureuri: false,
                fileElementId: 'avatar',
                Token : token,
                dataType: 'html',
                data:dataArray,
                success: function (data, status) {
                    if(status == 'success'){
                        $('#photo').html(data);
                        bindUploadPhoto();
                    }else{
                    }
                },
                error: function (data, status, e) {
                    console.log('error');
                    return;
                }
            });
        }

        function deleteImage(imageId) {
            bootbox.confirm("真要删掉选择的船舶照片吗?", function (result) {
                if (result) {
                    $.post('deleteShipPhotoImage', {'_token': token, 'imageId': imageId, 'shipId':shipId}, function (result) {
                        if(result) {
                            $('#photo').html(result);
                            bindUploadPhoto();
                        }
                    })
                }
            });
        }

        $(function() {
            $('ul li a[data-toggle=tab]').click(function() {
                $nowTab = $(this).attr("href");
                window.localStorage.setItem("shipTab",$nowTab);
            });

            ShowTabPage(activeTabName);
        });

    </script>
@stop