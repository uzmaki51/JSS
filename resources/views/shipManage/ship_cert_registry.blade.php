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
            <div class="inner-wrap col-md-12">
                <div class="row mb-4">
                    <div class="col-lg-6 d-flex">
                        <label class="custom-label d-inline-block" style="padding: 6px;">船名</label>
                        <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px;">
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
                            <button class="btn btn-info btn-sm search-btn" onclick="addCertItem()"><i class="icon-plus"></i>添加</button>
                            <button class="btn btn-warning btn-sm excel-btn d-none"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                            <a href="#modal-wizard" class="only-modal-show d-none" role="button" data-toggle="modal"></a>
                            @if(!$isHolder)
                                <button class="btn btn-sm btn-primary" id="submit">
                                    <i class="icon-save"></i>
                                    保存
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="shipCertList" method="post" id="certList-form" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" value="{{ $shipId }}" name="ship_id">
                        <table class="custom-table-striped">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="d-none"></th>
                                <th class="center" style="width:60px;word-break: break-all;">{!! transShipManager('shipCertlist.No') !!}</th>
                                <th class="center" style="width:60px;word-break: break-all;">{{ transShipManager('shipCertlist.Code') }}</th>
                                <th class="center" style="width:280px;word-break: break-all;">{{ transShipManager('shipCertlist.name of certificates') }}</th>
                                <th class="center" style="width:120px;word-break: break-all;">{{ transShipManager('shipCertlist.issue_date') }}</th>
                                <th class="center" style="width:120px;word-break: break-all;">{{ transShipManager('shipCertlist.expire_date') }}</th>
                                <th class="center" style="width:120px;word-break: break-all;">{!! transShipManager('shipCertlist.due_endorse') !!}</th>
                                <th class="center" style="width:80px;word-break: break-all;">{{ transShipManager('shipCertlist.issuer') }}</th>
                                <th class="center" style="width:40px;word-break: break-all;"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15"></th>
                                <th class="center" style="width:200px;word-break: break-all;">{{ transShipManager('shipCertlist.remark') }}</th>
                            </tr>
                            </thead>
                            <tbody id="cert_list" v-cloak>
                            <tr v-for="(item, array_index) in cert_array">
                                <td class="d-none"><input type="hidden" name="id[]" v-model="item.id"></td>
                                <td class="center no-wrap" v-bind:data-action="array_index">@{{ item.order_no }}</td>
                                <td class="center no-wrap" v-bind:data-code="array_index">@{{ item.code }}</td>
                                <td>
                                    <div class="custom-select-wrapper" v-bind:data-index="array_index" v-bind:cert-index="item.cert_id" @click="certTypeChange">
                                        <div class="custom-select" style="color:#12539b">
                                            <input type="hidden"  name="cert_id[]" v-model="item.cert_id" v-bind:data-main-value="array_index"/>
                                            <div class="custom-select__trigger"><span class="" v-bind:cer_name="array_index">@{{ item.cert_name }}</span>
                                                <div class="arrow"></div>
                                            </div>
                                            <div class="custom-options">
                                                <div class="custom-options-scroll">
                                                    <span v-for="(certItem, index) in certTypeList" v-bind:class="[item.cert_id == certItem.id ? 'custom-option  selected' : 'custom-option ']" @click="setCertInfo(array_index, certItem.id)">@{{ certItem.name }}</span>
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
                                <td class="center"><vuejs-datepicker :value="item.issue_date" name="issue_date[]" :readonly='false' :format="customFormatter" input-class="form-control text-center" :language="zh"></vuejs-datepicker></td>
                                <td class="center"><vuejs-datepicker :value="item.expire_date" name="expire_date[]" :format="customFormatter" input-class="form-control text-center" :language="zh"></vuejs-datepicker></td>
                                <td class="center"><input class="form-control text-center" type="text" v-model="item.due_endorse" name="due_endorse[]"></td>
                                <td class="center">
                                    <select class="form-control text-center" v-model="item.issuer" name="issuer[]">
                                        <option v-for="(issuer, index) in issuer_type" v-bind:value="index">@{{ issuer }}</option>
                                    </select>
                                <td class="text-center">
                                    <label v-bind:for="array_index"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15" style="cursor: pointer;"></label>
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
                        <div class="modal-dialog dynamic-list">
                            <div class="modal-content" style="border: 0;">
                                <div class="modal-header no-padding" data-target="#modal-step-contents">
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
                                            <div class="col-md-12" style="min-height: 300px; max-height: 300px; overflow-y:auto">
                                            <table class="table-bordered rank-table">
                                                <thead>
                                                <tr style="background-color: #c9dfff;height:18px;">
                                                    <td class="center td-header no-padding" style="width:25%">Order No</td>
                                                    <td class="center td-header no-padding" style="width:26%">Code</td>
                                                    <td class="center td-header no-padding" style="width:40%">Name</td>
                                                    <td class="center td-header no-padding" style="width: 10%;"></td>
                                                </tr>
                                                </thead>
                                                <tbody id="rank-table">
                                                <tr class="rank-tr" v-for="(typeItem, index) in list">
                                                    <td class="d-none">
                                                        <input type="hidden" name="cert_id[]" v-model="typeItem.id">
                                                    </td>
                                                    <td class="no-padding center">
                                                        <input type="text" @change="addNewRow(this)" class="form-control" name="order_no[]" v-model="typeItem.order_no" style="width: 100%;text-align: center">
                                                    </td>
                                                    <td class="no-padding">
                                                        <input type="text" @change="addNewRow(this)" class="form-control" name="code[]" v-model="typeItem.code" style="width: 100%;text-align: center">
                                                    </td>
                                                    <td class="no-padding center">
                                                        <input type="text" @change="addNewRow(this)" class="form-control" name="name[]" v-model="typeItem.name" style="width: 100%;text-align: center">
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
                        $(event.target).addClass('open');
                        if($(event.target).siblings(".custom-options").hasClass('open')) {
                            $(event.target).removeClass('open');
                            $(event.target).siblings(".custom-options").removeClass('open');
                        } else {
                            $(event.target).addClass('open');
                            $(event.target).siblings(".custom-options").addClass('open');
                        }
                    },
                    setCertInfo: function(array_index, cert) {
                        setCertInfo(cert, array_index);
                        $(".custom-select__trigger").removeClass('open');
                        $(".custom-options").removeClass('open');
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

                        var result = confirm('Are you really delete it?');

                        if(result) {
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
                        } else {
                            return false;
                        }
                    },
                    ajaxFormSubmit() {
                        let form = $('#shipCertForm').serialize();
                        $.post('shipCertType', form).done(function (data) {
                            Object.assign(certTypeObj.list, data);
                            certListObj.certTypeList = data;
                            shipCertTypeList = data;
                            $('.close').click();
                        });
                    },
                    addNewRow(e) {
                        if($.isEmptyObject(certTypeObj.list[certTypeObj.list.length - 1]) && certTypeObj.list.length > 0)
                            return false;
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
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] == certId) {
                    certListObj.cert_array[index]['order_no'] = value['order_no'];
                    certListObj.cert_array[index]['cert_id'] = certId;
                    certListObj.cert_array[index]['code'] = value['code'];
                    certListObj.cert_array[index]['cert_name'] = value['name'];
                    certListObj.$forceUpdate();
                }
            });
        }

        $('#select-ship').on('change', function() {
            getShipInfo($(this).val());
        });

        $('#submit').on('click', function() {
            $('#certList-form').submit();
        });

        $(document).mouseup(function(e) {
            var container = $(".custom-options");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $(".custom-options").removeClass('open');
                $(".custom-options").siblings('.custom-select__trigger').removeClass('open')
            }
        });

    </script>
@endsection