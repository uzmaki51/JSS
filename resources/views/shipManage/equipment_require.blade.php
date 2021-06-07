
<div id="equipment-require-list" v-cloak>
    <div class="row">
        <div class="col-lg-4">
            <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名: </label>
            <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px; max-width: 100px;" @change="onChangeShip">
                @foreach($shipList as $ship)
                    <option value="{{ $ship['IMO_No'] }}"
                        {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                    </option>
                @endforeach
            </select>
            <select class="text-center" name="year_list" @change="onChangeYear" v-model="activeYear">
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}年</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <div class="text-center" style="margin-top: 6px;">
                <strong style="font-size: 16px; padding-top: 6px;">
                    <span id="search_info">{{ $shipName }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font-bold">@{{ activeYear }}年必修备件表</span>
                </strong>
            </div>
        </div>
        <div class="col-lg-5">
            <select class="custom-select" v-model="activeStatus" @change="onChangeYear">
                <option value="0">全部</option>
                <option value="1">缺件</option>
            </select>
            <div class="btn-group f-right">
                <button class="btn btn-primary btn-sm search-btn" @click="addRow"><i class="icon-plus"></i>添加</button>
                <button class="btn btn-sm btn-success" @click="submitForm"><i class="icon-save"></i>保存</button>
                <button class="btn btn-warning btn-sm excel-btn"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                <a href="#modal-wizard" class="only-modal-show d-none" role="button" data-toggle="modal"></a>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 4px;">
        <div class="col-lg-12 head-fix-div common-list">
            <form action="shipReqEquipmentList" method="post" id="equipment-require-form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" value="{{ $shipId }}" name="shipId">
                <table class="table-striped">
                    <thead class="">
                        <th class="d-none"></th>
                        <th class="text-center">No</th>
                        <th class="text-center" style="width: 90px;">部门</th>
                        <th class="text-center style-header" style="width: 300px;">项目</th>
                        <th class="text-center style-header">必备量</th>
                        <th class="text-center style-header" style="width: 60px;">单位</th>
                        <th class="text-center style-header">库存量</th>
                        <th class="text-center style-header">状态</th>
                        <th class="text-center style-header" style="width: 200px;">备注</th>
                        <th class="text-center style-header"></th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in list" :class="index % 2 == 0 ? 'even' : 'odd'">
                            <td class="center no-wrap">@{{ index + 1 }}<input type="hidden" name="id[]" v-model="item.id"></td>
                            <td class="center no-wrap">
                                <select class="form-control text-center" v-model="item.place" name="place[]">
                                    <option value="1">主机(M/E)</option>
                                    <option value="2">辅机(A/E)</option>
                                    <option value="3">锅炉(BLR)</option>
                                    <option value="4">机械</option>
                                </select>
                            </td>
                            <td>
                                <div class="dynamic-select-wrapper" v-bind:data-index="index" v-bind:cert-index="item.item" @click="certTypeChange">
                                    <div class="dynamic-select" style="color:#12539b">
                                        <input type="hidden"  name="item[]" v-model="item.item" v-bind:data-main-value="index"/>
                                        <div class="dynamic-select__trigger dynamic-arrow">@{{ item.cert_name }}</div>
                                        <div class="dynamic-options" style="margin-top: -17px;">
                                            <div class="dynamic-options-scroll">
                                                <span v-for="(certItem, certIndex) in certTypeList" v-bind:class="[item.item == certItem.id ? 'dynamic-option  selected' : 'dynamic-option ']" @click="setCertInfo(index, certItem.id)">@{{ certItem.name }}</span>
                                            </div>
                                            <div>
                                            <span class="edit-list-btn" id="edit-list-btn" @click="openShipCertList(index)">
                                                <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px;">
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <my-currency-input v-model="item.inventory_vol" class="form-control text-center" name="inventory_vol[]" v-bind:prefix="''" v-bind:fixednumber="2" v-bind:index="index"></my-currency-input>
                            </td>
                            <td>
                                <input class="form-control text-left" type="text" v-model="item.unit" name="unit[]">
                            </td>
                            <td>
                                <my-currency-input v-model="item.require_vol" class="form-control text-center" name="require_vol[]" v-bind:prefix="''" v-bind:fixednumber="2" v-bind:index="index"></my-currency-input>
                            </td>
                            <td class="center no-wrap">
                                <select class="form-control text-center" v-model="item.status" name="status[]">
                                    <option value="1">新品</option>
                                    <option value="2">二手</option>
                                </select>
                            </td>

                            <td><input class="form-control text-left" type="text" v-model="item.remark" name="remark[]"></td>
                            
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a class="red" @click="deleteCertItem(item.id, index)">
                                        <i class="icon-trash" style="color: red!important;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>



	<?php
	echo '<script>';
    echo 'var PlaceType = ' . json_encode(g_enum('PlaceType')) . ';';
    echo 'var VarietyType = ' . json_encode(g_enum('VarietyType')) . ';';
    echo 'var UnitData = ' . json_encode(g_enum('UnitData')) . ';';
	echo '</script>';
	?>
    <script>
        var equipRequireObj = null;
        var itemListObj = null;
        var $__this = null;
        var shipCertTypeList = [];
        var equipRequireObjTmp = [];
        var certIdList = [];
        var certIdListTmp = [];
        var shipCertTypeList = [];
        var IS_FILE_KEEP = '{!! IS_FILE_KEEP !!}';
        var IS_FILE_DELETE = '{!! IS_FILE_DELETE !!}';
        var IS_FILE_UPDATE = '{!! IS_FILE_UPDATE !!}';
        var shipId = '{!! $shipId !!}';
        var activeYear = '{!! $activeYear !!}';
        var isChangeStatus = false;
        var initLoad = true;
        var activeId = 0;

        var submitted = false;
        if(isChangeStatus == false)
            submitted = false;

        function initRequire() {
            // Create Vue Obj
            equipRequireObj = new Vue({
                el: '#equipment-require-list',
                data: {
                    list: [],

                    itemList: [],
                    certListTmp: [],
                    certTypeList: [],
                    cert_array: [],

                    placeList: PlaceType,
                    varietyList: VarietyType,
                    unitList: UnitData,

                    shipId:         shipId,
                    activeYear:     activeYear,
                    placeType:      0,
                    activeType:     0,
                    activeStatus:   0,
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
                    setCertInfo: function(index, cert) {
                        var values = $("input[name='item[]']")
                            .map(function(){return parseInt($(this).val());}).get();

                        isChangeStatus = true;
                        setCertInfo(cert, index);
                        $(".dynamic-select__trigger").removeClass('open');
                        $(".dynamic-options").removeClass('open');
                    },
                    customFormatter(date) {
                        return moment(date).format('YYYY-MM-DD');
                    },
                    dateModify(e, index, type) {
                        $(e.target).on("change", function() {
                            equipRequireObj.list[index][type] = $(this).val();
                        });
                    },
                    customInput() {
                        return 'form-control';
                    },
                    onFileChange(e) {
                        let index = e.target.getAttribute('data-index');
                        equipRequireObj.itemList[index]['is_update'] = IS_FILE_UPDATE;
                        equipRequireObj.itemList[index]['file_name'] = 'updated';
                        isChangeStatus = true;
                        this.$forceUpdate();
                    },
                    openShipCertList(index) {
                        activeId = index;
                        // Object.assign(itemListObj.list, shipCertTypeList);
                        // itemListObj.list.push([]);
                        $('.only-modal-show').click();
                    },
                    onChangeShip: function(e) {
                        location.href = '/shipManage/equipment?id=' + $_this.shipId + '&type=require';
                    },
                    onChangeYear: function(e) {
                        var confirmationMessage = 'It looks like you have been editing something. '
                                + 'If you leave before saving, your changes will be lost.';
                        let currentObj = JSON.parse(JSON.stringify($__this.list));
                        if(JSON.stringify(equipRequireObjTmp) != JSON.stringify(currentObj))
                            isChangeStatus = true;
                        else
                            isChangeStatus = false;

                        if (!submitted && isChangeStatus) {
                            bootbox.confirm(confirmationMessage, function (result) {
                                if (!result) {
                                    return;
                                }
                                else {
                                    getRequireInitInfo();
                                }
                            });
                        } else {
                            getRequireInitInfo();
                        }
                    },
                    getImage: function(file_name) {
                        if(file_name != '' && file_name != undefined)
                            return '/assets/images/document.png';
                        else
                            return '/assets/images/paper-clip.png';
                    },
                    conditionSearch() {
                        getRequireInitInfo();
                    },
                    getToday: function(symbol) {
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + symbol + mm + symbol + dd;

                        return today;
                    },
                    submitForm: function() {
                        submitted = true;
                        $('#equipment-require-form').submit();
                    },
                    addRow: function() {
                        let length = $__this.list.length;
                        if(length == 0) {
                            this.list.push([]);
                            this.list[length].place = 1;
                            if(itemListObj.list.length > 0)
                                this.list[length].item = itemListObj.list[0].id;
                            else {
                                this.list[length].item = 0;
                            }
                            console.log(this.list[length].item)
                            setCertInfo(this.list[length].item, length);
                            this.list[length].require_vol = '';
                            this.list[length].inventory_vol = '';
                            this.list[length].unit = '';
                            this.list[length].status = 1;
                            this.list[length].remark = '';
                        } else {
                            this.list.push([]);
                            this.list[length].place = this.list[length - 1].place;
                            this.list[length].item = this.list[length-1].item;
                            console.log(this.list[length-1].item)
                            setCertInfo(this.list[length].item, length);
                            this.list[length].require_vol = this.list[length-1].require_vol;
                            this.list[length].inventory_vol = this.list[length-1].inventory_vol;
                            this.list[length].unit = this.list[length-1].unit;
                            this.list[length].status = this.list[length-1].status;
                            this.list[length].remark = this.list[length-1].remark;
                        }
                    },
                    deleteCertItem(id, index) {
                        __alertAudio();
                        bootbox.confirm("Are you sure you want to delete?", function (result) {
                            if (result) {
                                if (id != undefined) {
                                    $.ajax({
                                        url: BASE_URL + 'ajax/shipManage/equipment/require/delete',
                                        type: 'post',
                                        data: {
                                            id: id,
                                        },
                                        success: function (data, status, xhr) {
                                            $__this.list.splice(index, 1);
                                            equipRequireObjTmp = JSON.parse(JSON.stringify($__this.list))
                                        }
                                    })
                                } else {
                                    this.list.splice(index, 1);
                                    equipRequireObjTmp = JSON.parse(JSON.stringify($__this.list));
                                }
                            }
                        });
                    }

                },
                updated() {
                        $('.date-picker').datepicker({
                            autoclose: true,
                        }).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                    }
            });
            
            itemListObj = new Vue({
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
                                isChangeStatus = true;
                                $.ajax({
                                    url: BASE_URL + 'ajax/shipManage/equipment/require/type/delete',
                                    type: 'post',
                                    data: {
                                        id: index
                                    },
                                    success: function(data) {
                                        itemListObj.list = Object.assign([], [], data);

                                        equipRequireObjTmp = JSON.parse(JSON.stringify($__this.list));
                                    }
                                })
                            }});
                    },
                    ajaxFormSubmit() {
                        let form = $('#shipEquipForm').serialize();
                        $.post('saveShipReqEquipmentType', form).done(function (data) {
                            let result = data;

                            itemListObj.list = Object.assign([], [], result);
                            $__this.certTypeList = Object.assign([], [], result);
                            shipCertTypeList = Object.assign([], [], result);
                            itemListObj.list.forEach(function(value) {
                                if(value.id == $__this.list[activeId].item) {
                                    $__this.list[activeId].cert_name = value.name;
                                    console.log(value.name)
                                }
                            })

                            $__this.$forceUpdate();
                            equipRequireObjTmp = JSON.parse(JSON.stringify($__this.list));
                            $('.close').click();
                        });
                    },
                    addNewRow(e) {
                        isChangeStatus = true;
                        itemListObj.list.push([]);
                    }
                }
            });

            $__this = equipRequireObj;
            getRequireInitInfo();

        }

        function getRequireInitInfo() {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/equipment/require/list',
                type: 'post',
                data: {
                    shipId: $__this.shipId,
                    year: $__this.activeYear,
                    checkLack: $__this.activeStatus,
                },
                success: function(data, status, xhr) {
                    $__this.list = data;
                    getRequireType();
                }
            })
        }

        function getRequireType() {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/equipment/require/type/list',
                type: 'post',
                success: function(data, status, xhr) {

                    $__this.cert_array = Object.assign([], [], data);
                    itemListObj.list = Object.assign([], [], data);
                    $__this.certTypeList = Object.assign([], [], data);
                    shipCertTypeList = Object.assign([], [], data);
                    equipRequireObj.itemList  = Object.assign([], [], data);

                    certIdList = [];
                    $__this.list.forEach(function(value, index) {
                        certIdList.push(value['item']);
                        setCertInfo(value['item'], index);
                    });

                    equipRequireObjTmp = JSON.parse(JSON.stringify($__this.list));

                }
            })
        }

        function addCertItem() {
            let reportLen = equipRequireObj.itemList.length;
            let newCertId = 0;
            if(reportLen == 0) {
                reportLen = 0;
                newCertId = 0;
            } else {
                newCertId = equipRequireObj.itemList[reportLen - 1]['item'];
            }

            newCertId = getNearCertId(newCertId);

            if(shipCertTypeList.length <= reportLen && reportLen > 0)
                return false;

            if(newCertId == '') {
                newCertId = getNearCertId(0);
            }

            equipRequireObj.itemList.push([]);
            equipRequireObj.itemList[reportLen]['item']  = newCertId;
            equipRequireObj.itemList[reportLen]['is_tmp']  = 1;
            setCertInfo(newCertId, reportLen);
            equipRequireObj.itemList[reportLen]['issue_date']  = $($('[name^=issue_date]')[reportLen - 1]).val();
            equipRequireObj.itemList[reportLen]['expire_date']  = $($('[name^=expire_date]')[reportLen - 1]).val();
            equipRequireObj.itemList[reportLen]['due_endorse']  = $($('[name^=due_endorse]')[reportLen - 1]).val();
            equipRequireObj.itemList[reportLen]['issuer']  = 1;
            $($('[name=item]')[reportLen - 1]).focus();
            certIdList.push(equipRequireObj.itemList[reportLen]['item']);

            $('[date-issue=' + reportLen + ']').datepicker({
                autoclose: true,
            }).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });

            isChangeStatus = true;
        }

        function getNearCertId(item) {
            var values = $("input[name='item[]']")
                .map(function(){return parseInt($(this).val());}).get();
            let tmp = 0;
            tmp = item;
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] - tmp > 0 && !values.includes(value['id'])) {
                    if(value['id'] - item <= value['id'] - tmp)
                        tmp = value['id'];
                }
            });

            return tmp == item ? 0 : tmp;
        }

        function setCertInfo(certId, index = 0) {
            let status = 0;
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] == certId) {
                    equipRequireObj.list[index]['item'] = certId;
                    equipRequireObj.list[index]['cert_name'] = value['name'];
                    equipRequireObj.$forceUpdate();
                    status ++;
                }
            });
        }

        $('#select-ship').on('change', function() {
            location.href = "/shipManage/shipCertList?id=" + $(this).val()
        });

        $(document).mouseup(function(e) {
            var container = $(".dynamic-options-scroll");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $(".dynamic-options").removeClass('open');
                $(".dynamic-options").siblings('.dynamic-select__trigger').removeClass('open')
            }
        });

        $(".ui-draggable").draggable({
            helper: 'move',
            cursor: 'move',
            tolerance: 'fit',
            revert: "invalid",
            revert: false
        });
    </script>