<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/vue.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
@endsection


@section('content')
    <div class="main-content">
        <style>
            .filter_row {
                background-color: #45f7ef;
            }
            .chosen-drop {
                width : 350px !important;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>船舶证书记录</b>
                    </h4>
                </div>

            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-6 d-flex">
                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名: </label>
                        <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px; max-width: 100px;">
                            @foreach($shipList as $ship)
                                <option value="{{ $ship['id'] }}"
                                        {{ isset($shipId) && $shipId == $ship['id'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                </option>
                            @endforeach
                        </select>
                        @if(isset($shipName['shipName_En']))
                            <div class="col-md-12" style="font-size: 16px; padding-top: 6px;">
                                <strong>"<span id="ship_name">{{ $shipName['shipName_En'] }}</span>" CERTIFICATES</strong>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="btn-group f-right">
                            <button class="btn btn-primary btn-sm search-btn" onclick="addCertItem()"><i class="icon-plus"></i>添加</button>
                            <button class="btn btn-warning btn-sm excel-btn d-none"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                            <a href="#modal-wizard" class="only-modal-show d-none" role="button" data-toggle="modal"></a>
                            @if(!$isHolder)
                                <button class="btn btn-sm btn-warning" id="submit">
                                    <i class="icon-save"></i>保存
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 4px;">
                    <div class="col-lg-12">
                        <form action="shipCertList" method="post" id="certList-form" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" value="{{ $shipId }}" name="ship_id">
                        <table>
                            <thead class="">
                                <th class="d-none"></th>
                                <th class="text-center style-header" style="width:60px;word-break: break-all;">{!! transShipManager('shipCertlist.No') !!}</th>
                                <th class="text-center style-header" style="width:60px;word-break: break-all;">{{ transShipManager('shipCertlist.Code') }}</th>
                                <th class="text-center style-header" style="width:280px;word-break: break-all;">{{ transShipManager('shipCertlist.name of certificates') }}</th>
                                <th class="text-center style-header" style="width:120px;word-break: break-all;">{{ transShipManager('shipCertlist.issue_date') }}</th>
                                <th class="text-center style-header" style="width:120px;word-break: break-all;">{{ transShipManager('shipCertlist.expire_date') }}</th>
                                <th class="text-center style-header" style="width:120px;word-break: break-all;">{!! transShipManager('shipCertlist.due_endorse') !!}</th>
                                <th class="text-center style-header" style="width:80px;word-break: break-all;">{{ transShipManager('shipCertlist.issuer') }}</th>
                                <th class="text-center style-header" style="width:40px;word-break: break-all;"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15"></th>
                                <th class="text-center style-header" style="width:200px;word-break: break-all;">{{ transShipManager('shipCertlist.remark') }}</th>
                            </thead>
                            <tbody id="cert_list" v-cloak>
                            <tr v-for="(item, array_index) in cert_array">
                                <td class="d-none"><input type="hidden" name="id[]" v-model="item.id"></td>
                                <td class="center no-wrap" v-bind:data-action="array_index">@{{ item.order_no }}</td>
                                <td class="center no-wrap" v-bind:data-code="array_index">@{{ item.code }}</td>
                                <td>
                                    <div class="dynamic-select-wrapper" v-bind:data-index="array_index" v-bind:cert-index="item.cert_id" @click="certTypeChange">
                                        <div class="dynamic-select" style="color:#12539b">
                                            <input type="hidden"  name="cert_id[]" v-model="item.cert_id" v-bind:data-main-value="array_index"/>
                                            <div class="dynamic-select__trigger dynamic-arrow">@{{ item.cert_name }}</div>
                                            <div class="dynamic-options" style="margin-top: -17px;">
                                                <div class="dynamic-options-scroll">
                                                    <span v-for="(certItem, index) in certTypeList" v-bind:class="[item.cert_id == certItem.id ? 'dynamic-option  selected' : 'dynamic-option ']" @click="setCertInfo(array_index, certItem.id)">@{{ certItem.name }}</span>
                                                </div>
                                                <div>
                                                    <span class="edit-list-btn" id="edit-list-btn" @click="openShipCertList">
                                                        <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px;">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="center"><vuejs-datepicker :value="item.issue_date" name="issue_date[]" :readonly='false' :format="customFormatter" input-class="form-control text-center white-bg" :language="zh"></vuejs-datepicker></td>
                                <td class="center"><vuejs-datepicker :value="item.expire_date" name="expire_date[]" :format="customFormatter" input-class="form-control text-center white-bg" :language="zh"></vuejs-datepicker></td>
                                <td class="center"><input class="form-control text-center" type="text" v-model="item.due_endorse" name="due_endorse[]"></td>
                                <td class="center">
                                    <select class="form-control text-center" v-model="item.issuer" name="issuer[]">
                                        <option v-for="(issuer, index) in issuer_type" v-bind:value="index">@{{ issuer }}</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <label v-bind:for="array_index"><img v-bind:src="getImage(item.file_name)" width="15" height="15" style="cursor: pointer;" v-bind:title="item.file_name"></label>
                                    <input type="file" name="attachment[]" v-bind:id="array_index" class="d-none" @change="onFileChange" v-bind:data-index="array_index" accept=".pdf">
                                    <input type="hidden" name="is_update[]" v-bind:id="array_index" class="d-none" v-bind:value="item.is_update">
                                </td>
                                <td><input class="form-control text-center" type="text" v-model="item.remark" name="remark[]"></td>
                            </tr>
                            </tbody>
                        </table>
                        </form>
                    </div>

                    <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none; margin-top: 15%;">
                        <div class="dynamic-modal-dialog">
                            <div class="dynamic-modal-content" style="border: 0;">
                                <div class="dynamic-modal-header" data-target="#modal-step-contents">
                                    <div class="table-header">
                                        <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                            <span class="white">&times;</span>
                                        </button>
                                        船舶证书种类登记
                                    </div>
                                </div>
                                <div id="modal-cert-type" class="dynamic-modal-body step-content">
                                    <div class="row" style="">
                                        <form action="shipCertType" method="post" id="shipCertForm">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="head-fix-div col-md-12" style="height:300px;">
                                            <table class="table-bordered rank-table">
                                                <thead>
                                                <tr class="rank-tr" style="background-color: #c9dfff;height:18px;">
                                                    <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:10%">OrderNo</th>
                                                    <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:50%">Code</th>
                                                    <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:15%">Name</th>
                                                    <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;"></th>
                                                </tr>
                                                </thead>
                                                <tbody id="rank-table">
                                                <tr class="no-padding center" v-for="(typeItem, index) in list">
                                                    <td class="d-none">
                                                        <input type="hidden" name="cert_id[]" v-model="typeItem.id">
                                                    </td>
                                                    <td class="no-padding center">
                                                        <input type="text" @focus="addNewRow(this)" class="form-control" name="order_no[]" v-model="typeItem.order_no" style="width: 100%;text-align: center">
                                                    </td>
                                                    <td class="no-padding">
                                                        <input type="text" @focus="addNewRow(this)" class="form-control" name="code[]" v-model="typeItem.code" style="width: 100%;text-align: center">
                                                    </td>
                                                    <td class="no-padding center">
                                                        <input type="text" @focus="addNewRow(this)" class="form-control" name="name[]" v-model="typeItem.name" style="width: 100%;text-align: center">
                                                    </td>
                                                    <td class="no-padding center">
                                                        <div class="action-buttons">
                                                            <a class="red" @click="deleteShipCert(typeItem.id)"><i class="icon-trash"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        </form>
                                        <div class="row">
                                            <div class="btn-group f-right mt-20 d-flex">
                                                <button type="button" class="btn btn-success small-btn ml-0" @click="ajaxFormSubmit">
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
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/zh.js"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>

    <?php
    echo '<script>';
    echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
    echo '</script>';
    ?>
    <script>
        var certListObj = null;
        var certTypeObj = null;
        var shipCertTypeList = [];
        var totalRecord = 0;
        var initLoad = true;
        var IS_FILE_KEEP = '{!! IS_FILE_KEEP !!}';
        var IS_FILE_DELETE = '{!! IS_FILE_DELETE !!}';
        var IS_FILE_UPDATE = '{!! IS_FILE_UPDATE !!}';

        $(function () {
            // Initialize
            initialize();
        });

        function initialize() {
            let ship_id = '{!! $shipId !!}';

            // Create Vue Obj
            certListObj = new Vue({
                el: '#cert_list',
                data: {
                    cert_array: [],
                    certTypeList: [],
                    zh: vdp_translation_zh.js,
                    issuer_type: IssuerTypeData
                },
                components: {
                    vuejsDatepicker
                },
                methods: {
                    certTypeChange: function(event) {
                        let hasClass = $(event.target).hasClass('open');
                        if($(event.target).hasClass('open')) {
                            $(event.target).removeClass('open');
                            $(event.target).siblings(".dynamic-options").removeClass('open');
                        } else {
                            $(event.target).addClass('open');
                            $(event.target).siblings(".dynamic-options").addClass('open');
                        }
                    },
                    setCertInfo: function(array_index, cert) {
                        setCertInfo(cert, array_index);
                        $(".dynamic-select__trigger").removeClass('open');
                        $(".dynamic-options").removeClass('open');
                    },
                    customFormatter(date) {
                        return moment(date).format('YYYY-MM-DD');
                    },
                    customInput() {
                      return 'form-control';
                    },
                    onFileChange(e) {
                        let index = e.target.getAttribute('data-index');
                        certListObj.cert_array[index]['is_update'] = IS_FILE_UPDATE;
                        this.$forceUpdate();
                    },
                    openShipCertList(e) {
                        $('.only-modal-show').click();
                    },
                    getImage: function(file_name) {
                        if(file_name != '' && file_name != undefined)
                            return '/assets/images/document.png';
                        else
                            return '/assets/images/paper-clip.png';

                    },

                },
                updated() {

                }
            });

            certTypeObj = new Vue({
                el: '#modal-cert-type',
                data() {
                    return {
                        list: [],
                    }
                },
                methods: {
                    deleteShipCert(index) {
                        if(index == undefined || index == '')
                            return false;

                        bootbox.confirm("Are you sure you want to delete?", function (result) {
                            if (result) {
                            $.ajax({
                                url: BASE_URL + 'ajax/shipManage/cert/delete',
                                type: 'post',
                                data: {
                                    id: index
                                },
                                success: function(data) {
                                    certTypeObj.list = data;
                                    certTypeObj.list.push([]);
                                }
                            })
                        }});
                    },
                    ajaxFormSubmit() {
                        let form = $('#shipCertForm').serialize();
                        $.post('shipCertType', form).done(function (data) {
                            Object.assign(certTypeObj.list, data);
                            certListObj.certTypeList = data;
                            shipCertTypeList = data;
                            getShipInfo(ship_id);
                            $('.close').click();
                        });
                    },
                    addNewRow(e) {
                        //if($.isEmptyObject(certTypeObj.list[certTypeObj.list.length - 1]) && certTypeObj.list.length > 0)
                        //    return false;
                        certTypeObj.list.push([]);
                    }
                }
            });

            getShipInfo(ship_id);
        }

        function getShipInfo(ship_id) {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/cert/list',
                type: 'post',
                data: {
                    ship_id: ship_id
                },
                success: function(data, status, xhr) {
                    let result = data;
                    let ship_id = data['ship_id'];
                    let ship_name = data['ship_name'];

                    shipCertTypeList = data['cert_type'];

                    $('[name=ship_id]').val(ship_id);
                    $('#ship_name').text(ship_name);
                    certListObj.cert_array = data['ship'];
                    certListObj.certTypeList = shipCertTypeList;

                    Object.assign(certTypeObj.list, shipCertTypeList);
                    certTypeObj.list.push([]);
                    console.log(certListObj.certTypeList.length);

                    certListObj.cert_array.forEach(function(value, index) {
                        certListObj.cert_array[index]['is_update'] = IS_FILE_KEEP;
                        setCertInfo(value['cert_id'], index);
                    });
                    totalRecord = data['ship'].length;

                }
            })
        }

        function addCertItem() {
            if($.isEmptyObject(certListObj.cert_array[certListObj.cert_array.length - 1]) && certListObj.cert_array.length > 0)
                return false;
            certListObj.cert_array.push([]);

            $($('[name=cert_id]')[certListObj.cert_array.length - 1]).focus();
            totalRecord = certListObj.cert_array.length;
        }

        function setCertInfo(certId, index = 0) {
            let status = 0;
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] == certId) {
                    certListObj.cert_array[index]['order_no'] = value['order_no'];
                    certListObj.cert_array[index]['cert_id'] = certId;
                    certListObj.cert_array[index]['code'] = value['code'];
                    certListObj.cert_array[index]['cert_name'] = value['name'];
                    certListObj.$forceUpdate();
                    status ++;
                }

                // if(status == 1) {
                //     certListObj.cert_array[index]['order_no'] = shipCertTypeList[0]['order_no'];
                //     certListObj.cert_array[index]['cert_id'] = shipCertTypeList[0]['id'];
                //     certListObj.cert_array[index]['code'] = shipCertTypeList[0]['code'];
                //     certListObj.cert_array[index]['cert_name'] = shipCertTypeList[0]['name'];
                // }
            });
        }

        $('#select-ship').on('change', function() {
            getShipInfo($(this).val());
        });

        $('#submit').on('click', function() {
            $('#certList-form').submit();
        });

        $(document).mouseup(function(e) {
            var container = $(".dynamic-options-scroll");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $(".dynamic-options").removeClass('open');
                $(".dynamic-options").siblings('.dynamic-select__trigger').removeClass('open')
            }
        });
    </script>
@endsection