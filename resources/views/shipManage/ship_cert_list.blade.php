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
                        <b>船舶证书记录</b>
                    </h4>
                </div>

            </div>
            <div class="inner-wrap col-md-12" id="cert_list" v-cloak>
                <div class="row mb-4">
                    <div class="col-lg-4 d-flex">
                        <label class="custom-label d-inline-block" style="padding: 6px;">船名</label>
                        <select class="custom-select d-inline-block" style="padding: 4px;" @change="changeShip">
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
                    <div class="col-lg-3"></div>
                    <div class="col-lg-5">
                        <label>提前</label>
                        <input type="text" class="text-center" style="width: 60px;" name="expire_date" v-model="expire_date">
                        <input type="hidden" class="text-center" style="width: 60px;" name="ship_id" v-model="ship_id">
                        <label>天</label>
                        <div class="btn-group f-right">
                            <button class="btn btn-report-search btn-sm search-btn" @click="doSearch()"><i class="icon-search"></i>搜索</button>
                            <a class="btn btn-sm btn-danger {{ Auth::user()->isAdmin == 1 ? '' : 'right-no-radius' }} refresh-btn-over" type="button" @click="refresh">
                                <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">更新
                            </a>
                            <button class="btn btn-warning btn-sm excel-btn" @click="onExport"><i class="icon-table"></i>{{ trans('common.label.excel') }}</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" value="{{ $shipId }}" name="ship_id">
                        <table class="custom-table-striped">
                            <thead>
                            <tr class="black br-hblue">
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
                            <tbody>
                            <tr v-for="(item, array_index) in cert_array">
                                <td class="center no-wrap">@{{ item.order_no }}</td>
                                <td class="center no-wrap">@{{ item.code }}</td>
                                <td>@{{ item.cert_name }}</td>
                                <td class="center"><span>@{{ item.issue_date }}</span></td>
                                <td class="center"><span>@{{ item.expire_date }}</span></td>
                                <td class="center"><span>@{{ item.due_endorse }}</span></td>
                                <td class="center"><span>@{{ issuer_type[item.issuer] }}</span></td>
                                <td class="text-center">
                                    <a v-bind:href="item.attachment_link" target="_blank" v-bind:class="[item.attachment_link == '' || item.attachment_link == undefined ? 'd-none' : '']"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15" style="cursor: pointer;"></a>
                                </td>
                                <td><span>@{{ item.remark }}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>

	<?php
	echo '<script>';
	echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
	echo '</script>';
	?>
    <script>
        var certListObj = null;
        var shipCertTypeList = [];

        $(function () {
            // Initialize
            initialize();
        });

        function initialize() {
            // Create Vue Obj
            certListObj = new Vue({
                el: '#cert_list',
                data: {
                    cert_array: [],
                    certTypeList: [],
                    issuer_type: IssuerTypeData,
                    expire_date: 0,
                    ship_id: 0,
                },
                methods: {
                    customFormatter(date) {
                        return moment(date).format('YYYY-MM-DD');
                    },

                    doSearch() {
                        this.getShipCertInfo();
                    },
                    changeShip(e) {
                        this.ship_id = e.target.value;console.log(e.target.value)
                        this.getShipCertInfo();
                    },
                    refresh() {
                      this.expire_date = 0;
                      this.getShipCertInfo();
                    },
                    onExport() {
                      location.href='/shipManage/shipCertExcel?id=' + this.ship_id;
                    },
                    getShipCertInfo() {
                        getShipInfo(this.ship_id, this.expire_date);
                    }
                }
            });

            certListObj.ship_id = '{!! $shipId !!}';
            getShipInfo(certListObj.ship_id, certListObj.expire_date);
        }

        function getShipInfo(ship_id, expire_date) {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/cert/list',
                type: 'post',
                data: {
                    ship_id: ship_id,
                    expire_date: expire_date
                },
                success: function(data, status, xhr) {
                    let ship_name = data['ship_name'];
                    shipCertTypeList = data['cert_type'];
                    $('#ship_name').text(ship_name);
                    certListObj.cert_array = data['ship'];
                    certListObj.certTypeList = shipCertTypeList;
                    certListObj.ship_id = data['ship_id'];
                    certListObj.cert_array.forEach(function(value, index) {
                        setCertInfo(value['cert_id'], index);
                    });
                    totalRecord = data['ship'].length;

                }
            })
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

    </script>
@endsection