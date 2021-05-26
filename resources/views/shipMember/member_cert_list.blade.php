<<<<<<< Updated upstream
<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>

@extends('layout.'.$header)

=======
@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

>>>>>>> Stashed changes
@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/vue.css') }}" rel="stylesheet"/>
@endsection
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream

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
=======
            </div>
            <div class="row col-md-12" style="margin-bottom: 4px;">
                <div class="col-md-6">
                    <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                    <select class="custom-select d-inline-block" id="select-ship" style="max-width: 100px;">
                        <option value="" selected></option>
                        @foreach($shipList as $ship)
                            <option value="{{ $ship['IMO_No'] }}" data-name="{{$ship['shipName_En']}}">{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}</option>
                        @endforeach
                    </select>
                    <strong class="f-right" style="font-size: 16px; padding-top: 6px;align-content: flex-end;display: flex;"><span id="ship_name" class="list-header"></span> CREW CERTIFICATES LIST</strong>
                </div>
                <div class="col-md-6">
                    <div class="f-right">
                        <label class="font-bold">提前:</label>
                        <!--input type="number" min="0" step="1" class="text-center" style="width: 60px;" name="expire_date" id="expire-date" value="0"-->
                        <select id="expire-date" style="width: 60px;">
                            <option value="0" selected>All</option>
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="180">180</option>
                        </select>
                        <label>天</label>
                        <!--button class="btn btn-report-search btn-sm search-btn" onclick="" id="btn-search"><i class="icon-search"></i>搜索</button-->
                        <!--a class="btn btn-sm btn-danger refresh-btn-over" type="button" onclick="javascript:refresh()">
                            <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">恢复
                        </a-->
                        <button class="btn btn-warning btn-sm excel-btn" onclick=""><i class="icon-table"></i>{{ trans('common.label.excel') }}</button>
                    </div>
                </div>
            </div>
            <div class="" style="margin-top:8px;">
                <div id="item-manage-dialog" class="hide"></div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div>
                    <table id="table-shipmember-list" class="custom-table-striped">
                        <thead>
                        <tr class="black br-hblue" style="height:45px;">
                            <th class="text-center style-header" style="width: 3%;"><span>No</span></th>
                            <th class="text-center style-header" style="width: 8%;"><span>Name</span></th>
                            <th class="text-center style-header" style="width: 7%;"><span>Rank</span></th>
                            <th class="text-center style-header" style="width: 2%;"><span>DOC No</span></th>
                            <th class="text-center style-header" style="width: 15%;"><span>Type of certificates</span></th>
                            <th class="text-center style-header" style="width: 7%;"><span>Certificates No.</span></th>
                            <th class="text-center style-header" style="width: 6%;"><span>Issued Date</span></th>
                            <th class="text-center style-header" style="width: 6%;"><span>Expire Date</span></th>
                            <th class="text-center style-header" style="width: 6%;"><span>Issued by</span></th>
                        </tr>
                        </thead>
                        <tbody class="" id="list-body">
                        </tbody>
                    </table>
                </div>
                <div id="test">
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
<<<<<<< Updated upstream

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
=======
>>>>>>> Stashed changes

	<?php
	echo '<script>';
	echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
	echo '</script>';
	?>
    <script>
<<<<<<< Updated upstream
        var certListObj = null;
        var shipCertTypeList = [];

=======
        var certList = new Array();
        var cIndex = 0;
        
        @foreach($security as $type)
            var cert = new Object();
            cert.value = '{{$type["title"]}}';
            certList[cIndex] = cert;
            cIndex++;
        @endforeach

        var token = '{!! csrf_token() !!}';
        var shipName = '';
>>>>>>> Stashed changes
        $(function () {
            // Initialize
            initialize();
        });

<<<<<<< Updated upstream
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
=======
        var listTable = null;
        function initTable() {
            listTable = $('#table-shipmember-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/shipMember/cert/list',
                    type: 'POST',
                    data: {'type' : 'crew'},
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                }
            });

            certListObj.ship_id = '{!! $shipId !!}';
            getShipInfo(certListObj.ship_id, certListObj.expire_date);
=======
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }

        function doSearch() {
            if (shipName == "") return;
            if (listTable == null) initTable();
            $('#ship_name').html('"' + shipName + '"');
            listTable.column(2).search($("#select-ship" ).val(), false, false);
            listTable.column(3).search($("#expire-date").val(), false, false).draw();
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
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
=======
                else
                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }

            tab_text=tab_text+"</table>";
            //tab_text='<table border="2px" style="text-align:center;vertical-align:middle;"><tr><th class="text-center sorting_disabled" style="width: 78px;text-align:center;vertical-align:center;" rowspan="1" colspan="1"><span>No</span></th></tr><tr style="width: 78px;text-align:center;vertical-align:middle;"><td class="text-center sorting_disabled" rowspan="2" style="">你好</td></tr></table>';
            tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            //document.getElementById('test').innerHTML = tab_text;
            var filename = 'CREW CERTIFICATES LIST(' + shipName + ')';
            exportExcel(tab_text, filename, 'CREW CERTIFICATES LIST');
            return 0;
        }

        /*
        function refresh() {
            $('#expire-date').val('0');
            doSearch();
        }

        $('#btn-search').on('click', function() {
            doSearch();
        });
        */
        
    </script>

@endsection
>>>>>>> Stashed changes
