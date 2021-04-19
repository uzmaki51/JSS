@extends('layout.header')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="page-year-view">
                <div class="page-header">
                    <div class="col-md-6">
                        <h5><b>收入及支出</b></h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group" style="width:80%; display: inline-table;"  >
                            <input type="text" placeholder="Ref No" class="form-control" style="padding: 8px;">
                    <span class="input-group-btn">
														<button type="button" class="btn btn-default btn-sm" id="btn-search" >
                                                            <i class="icon-search" ></i>
                                                        </button>
													</span>
                        </div>

                        <div style="float:right">
                            <button class="btn btn-primary btn-sm" id="btn-add-view" data-year="" data-ship="">
                                <i class="icon-plus" ></i> 追 加
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- add field --}}
                    <div class="add-movement-box" style="display:none; padding:12px 12px 42px 12px;margin-bottom:12px;background-color:#fcf8e3; border: 1px solid #bce8f1;border-radius: 3px;">
                        <table id="" class="table table-bordered table-hover " style="margin-bottom: 0px;">
                            <thead >
                            <tr>
                                <th>船舶名称</th>
                                <th>航次</th>
                                <th>付款的航次</th>
                                <th width="30%">说明</th>
                                <th>AC项目</th>
                                <th>位置</th>
                                <th width="10%">数量</th>
                                <th>货币</th>
                                <th>Account</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>
                                    <select>
                                        <option>ANNIN</option>
                                    </select>
                                </td>
                                <td><select>
                                        <option>ANNIN</option>
                                    </select></td>
                                <td><select>
                                        <option>ANNIN</option>
                                    </select></td>
                                <td><input type="text" style="width:100%;;"></td>
                                <td></td>
                                <td><select>
                                        <option>ANNIN</option>
                                    </select></td>
                                <td><input type="text" style="width:100%"></td>
                                <td><select>
                                        <option>ANNIN</option>
                                    </select></td>
                                <td><select>
                                        <option>ANNIN</option>
                                    </select></td>
                            </tr>
                            </tbody>
                        </table><table id="" class="table table-bordered table-hover " style="margin-bottom: 8px;">
                            <thead >
                            <tr>
                                <th>Pay_Mthd</th>
                                <th>Appl_Date</th>
                                <th>Rcpt_Date</th>
                                <th>Ref No</th>
                                <th>Cmplt</th>
                                <th>Recipt</th>
                                <th width="30%">概要</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td><select>
                                        <option>ANNIN</option>
                                    </select></td>
                                <td><input type="date" style="width:100%"></td>
                                <td><input type="date" style="width:100%"></td>
                                <td><input type="text" style="width:100%"></td>
                                <td><input type="checkbox" style="width:100%"></td>
                                <td><input type="checkbox" style="width:100%"></td>
                                <td><textarea name="" id="" cols="" rows="1" style="width:100%;"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="float: right;">
                            <button class="btn btn-sm btn-primary" id="btn-add-movement">添加</button>
                            <button class="btn btn-sm btn-primary" id="btn-close">关闭</button>
                        </div>
                    </div>

                    {{-- sub field of oil and items --}}
                    <div class="sub-movement-box" style="margin-bottom:12px;background-color:#fcf8e3; border: 1px solid #bce8f1;border-radius: 3px;">
                        <div style="padding: 12px;background-color: #d9edf7;"><h5 class="smaller" style="margin: 0;">原油及配件材料</h5></div>
                        <div style="padding:12px;">
                            <table id="" class="table table-bordered table-hover " style="margin-bottom: 0px;">
                                <thead >
                                <tr>
                                    <th>供给日期</th>
                                    <th>地点</th>
                                    <th>AC项目</th>
                                    <th width="30%">说明</th>
                                    <th>Part No</th>
                                    <th>QTY</th>
                                    <th width="10%">单位</th>
                                    <th>价格</th>
                                    <th>数量</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="date">
                                    </td>
                                    <td><select>
                                            <option>ANNIN</option>
                                        </select></td>
                                    <td><select>
                                            <option>ANNIN</option>
                                        </select></td>
                                    <td><input type="text" style="width:100%;;"></td>
                                    <td><input type="text" style="width:100%;;"></td>
                                    <td><input type="text" style="width:100%;;"></td>
                                    <td><select>
                                            <option>ANNIN</option>
                                        </select></td>
                                    <td><input type="text" style="width:100%;;"></td>
                                    <td><input type="text" style="width:100%;;"></td>
                                </tr>
                                <tr>
                                    <td style="background-color: #f7f7f7;"><b>概要</b></td>
                                    <td colspan="11">
                                        <textarea name="" id=""  rows="2" style="width:100%;"></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="table-responsive" id="user_list_table">
                            <table  id="user-table" class="table table-striped table-bordered table-hover" >
                                <thead>
                                <tr>
                                    <td>对象</td>
                                    <td>船舶名称</td>
                                    <td>航次</td>
                                    <td>付款的航次</td>
                                    <td>说明</td>
                                    <td>AC项目</td>
                                    <td>位置</td>
                                    <td>航海距离</td>
                                    <td>数量</td>
                                    <td>货币</td>
                                    <td>Account</td>
                                    <td>Pay_Mtdd</td>
                                    <td>Appl_Date</td>
                                    <td>Rcpt_Date</td>
                                    <td>Ref No</td>
                                    <td>Cmplt</td>
                                    <td>Recipt</td>
                                    <td width="30%">概要</td>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        jQuery(function(e){

// pagination
            var oTable1 = $('#user-table').dataTable( {
                "aoColumns": [ {"bSortable": false }, {"bSortable": false },  {"bSortable": false },  {"bSortable": false },  {"bSortable": false }, {"bSortable": false }, {"bSortable": false },  {"bSortable": false },  {"bSortable": false },  {"bSortable": false },  {"bSortable": false },  {"bSortable": false },
                    {"bSortable": false },  {"bSortable": false }, {"bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }],
                "iDisplayLength":20
            });

            $('#btn-add-view').on('click',function(){
                $('.add-movement-box').fadeIn();
                $(this).toggle();
            });
            $('#btn-close').on('click',function(){
                $('.add-movement-box').fadeOut();
                $('#btn-add-view').toggle();
            });


        });
    </script>

@stop
