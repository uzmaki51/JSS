<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered general" style="font-weight: bold">
                    <tbody>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            <span class="text-danger">Seamanbook No*</span>
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="crewNum" class="form-control d-in-block" style="width:50%" value="@if(isset($info)){{$info['crewNum']}}@endif" required minlength="11" maxlength="11">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Name in Chinese
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="realname" class="form-control" style="width:100%" value="@if(isset($info)){{$info['realname']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Name in English
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="Surname" class="form-control first-input" value="@if(isset($info)){{$info['Surname']}}@endif" style="border-right: 1px solid #cccccc!important;" placeholder="Family">
                            <input type="text" name="GivenName" class="form-control second-input" value="@if(isset($info)){{$info['GivenName']}}@endif" placeholder="Given">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Gender
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <select class="form-control" name="Sex">
                                <option value="0" @if(isset($info) && ($info['Sex'] == 0)) selected @endif>{{transShipMember('captions.male')}}</option>
                                <option value="1" @if(isset($info) && ($info['Sex'] == 1)) selected @endif>{{transShipMember('captions.female')}}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Birthday
                        </td>
                        <td class="custom-td-dec-text">
                            <div class="input-group">
                                <input class="form-control date-picker"
                                    name="birthday"
                                    type="text" data-date-format="yyyy-mm-dd"
                                    value="@if(isset($info)){{$info['birthday']}}@endif">
                                <span class="input-group-addon">
                                            <i class="icon-calendar "></i>
                                        </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            HomeAddress
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="address" class="form-control" style="width:100%" value="@if(isset($info)){{$info['address']}}@endif">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered general" style="font-weight: bold">
                    <tbody>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Nationality
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <!--select class="form-control" name="Nationality">
                                <option value="0" @if(isset($info) && ($info['Nationality'] == 0)) selected @endif>CHINESE</option>
                                <option value="1" @if(isset($info) && ($info['Nationality'] == 1)) selected @endif>BANGLADESH</option>
                                <option value="2" @if(isset($info) && ($info['Nationality'] == 2)) selected @endif>MYANMAR</option>
                            </select-->
                            
                            <div class="custom-select-wrapper">
                                <div class="custom-select">
                                    <input type="hidden"  name="Nationality" value="0"/>
                                    <div class="custom-select__trigger"><span>CHINESE</span>
                                        <div class="arrow"></div>
                                    </div>
                                    <div class="custom-options">
                                        <div class="custom-options-scroll">
                                            <span class="custom-option selected" data-value="0">CHINESE</span>
                                            <span class="custom-option" data-value="1">BANGLADESH</span>
                                            <span class="custom-option" data-value="2">MYANMAR</span>
                                        </div>
                                        <div>
                                            <span class="edit-list-btn" id="edit-list-btn" >
                                                <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            <span class="sub-title text-danger" disabled>身份证号 *</span>
                        </td>
                        <td class="custom-td-report-text" colspan="3">
                            <input type="text" name="CertNo" class="form-control d-in-block" style="width:100%" value="@if(isset($info)){{$info['CertNo']}}@endif" required minlength="18" maxlength="18">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            PhoneNumber
                        </td>
                        <td class="custom-td-report-text" colspan="3">
                            <input type="text" name="phone" class="form-control" style="width:100%" value="@if(isset($info)){{$info['phone']}}@endif" placeholder="">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            BirthPlace
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="BirthPlace" class="form-control" style="width:100%" value="@if(isset($info)){{$info['BirthPlace']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Other Contacts
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="OtherContacts" class="form-control" style="width:100%" value="@if(isset($info)){{$info['OtherContacts']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" style="text-align: left" colspan="2">
                            Bank Information
                        </td>
                        <td class="custom-td-report-text" colspan="2">
                            <input type="text" name="BankInformation" class="form-control" style="width:100%" value="@if(isset($info)){{$info['BankInformation']}}@endif">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>