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
                        <b>船舶证书</b>
                    </h4>
                </div>

            </div>
            <div class="inner-wrap col-md-12">
                <div class="row mb-4">
                    <div class="col-lg-2 d-flex">
                        <label class="custom-label d-inline-block" style="padding: 6px;">船名</label>
                        <select class="custom-select d-inline-block" id="select-ship">
                            <option value="" @if(!isset($shipId)) selected @endif>全体</option>
                            @foreach($shipList as $ship)
                                <option value="{{ $ship['shipID'] }}"
                                        @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn'].' | '.$ship['shipName_En']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        @if(isset($shipName['shipName_En']))
                            <div class="col-md-12" style="font-size: 16px; padding-top: 6px;">
                                <strong>"{{$shipName['shipName_Cn']}}" CERTIFICATES</strong>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="btn-group f-right">
                            <button class="btn btn-info btn-sm search-btn" onclick="addCertItem()"><i class="icon-plus"></i>添加</button>
                            <button class="btn btn-warning btn-sm excel-btn d-none"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
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
                            <tr v-for="(item, index) in cert_array" v-bind:data-index="index">
                                <td class="d-none"><input type="hidden" name="id[]" v-model="item.id"></td>
                                <td class="center">@{{ item.order_no }}</td>
                                <td>@{{ item.code }}</td>
                                <td>
                                    <select class="form-control" name="cert_id[]" v-model="item.cert_id" @change="certTypeChange($event, index)">
                                        <option v-for="(item, index) in certTypeList" v-bind:value="item.id">@{{ item.name }}</option>
                                    </select>
                                </td>
                                <td class="center"><vuejs-datepicker :value="item.issue_date" name="issue_date[]" :readonly='false' :format="customFormatter" input-class="form-control text-center" :language="zh"></vuejs-datepicker></td>
                                <td class="center"><vuejs-datepicker :value="item.expire_date" name="expire_date[]" :format="customFormatter" input-class="form-control text-center" :language="zh"></vuejs-datepicker></td>
                                <td class="center"><input class="form-control" type="text" v-model="item.due_endorse" name="due_endorse[]"></td>
                                <td class="center">
                                    <select class="form-control text-center" v-model="item.issuer" name="issuer[]">
                                        <option v-for="(issuer, index) in issuer_type" v-bind:value="index">@{{ issuer }}</option>
                                    </select>
                                <td class="text-center">
                                    <a v-bind:href="item.attachment_link"></a>
                                    <label v-bind:for="index"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15"></label>
                                    <input type="file" name="attachment[]" v-bind:id="index" class="d-none" @change="onFileChange" v-bind:data-index="index" accept="pdf">
                                    <input type="hidden" name="is_update[]" v-bind:id="index" class="d-none" v-bind:value="item.is_update">
                                </td>
                                <td><input class="form-control text-center" type="text" v-model="item.remark" name="remark[]"></td>
                            </tr>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/zh.js"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
    <?php
    echo '<script>';
    echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
    echo '</script>';
    ?>
    <script>
        var certListObj = null;
        var certTypeList = [];
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
                data() {
                    return {
                        cert_array: [],
                        certTypeList: [],
                        zh: vdp_translation_zh.js,
                        issuer_type: IssuerTypeData
                    }

                },
                components: {
                    vuejsDatepicker
                },
                methods: {
                    certTypeChange: function(event, index) {
                        let certId = event.target.value;
                        setCertInfo(certId, index);
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
                    }
                },
                updated() {
                    $('input').removeAttr('readonly');
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
                    certListObj.cert_array = data['ship'];
                    certListObj.certTypeList = data['cert'];
                    certTypeList = data['cert'];
                    certListObj.cert_array.forEach(function(value, index) {console.log(value);
                        certListObj.cert_array[index]['is_update'] = IS_FILE_KEEP;
                        setCertInfo(value['cert_id'], index);
                    });
                    totalRecord = data['ship'].length;
                }
            })
        }

        function addCertItem() {
            if($.isEmptyObject(certListObj.cert_array[certListObj.cert_array.length - 1]))
                return false;
            certListObj.cert_array.push([]);
            // isAdd = true;
            $($('[name=cert_id]')[certListObj.cert_array.length - 1]).focus();
            totalRecord = certListObj.cert_array.length;
        }

        function setCertInfo(certId, index = 0) {
            certTypeList.forEach(function(value, key) {
                if(value['id'] == certId) {
                    certListObj.cert_array[index]['order_no'] = value['order_no'];
                    certListObj.cert_array[index]['code'] = value['code'];
                }

            });
        }

        $('#select-ship').on('change', function() {
            getShipInfo($(this).val());
        });

        $('#submit').on('click', function() {
            $('#certList-form').submit();
        })
    </script>
@endsection