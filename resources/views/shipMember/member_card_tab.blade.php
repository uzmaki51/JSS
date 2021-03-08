<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
        <form role="form" method="POST" enctype="multipart/form-data" action="updateMemberCardData">
            <input class="hidden" name="_token" value="{{csrf_token()}}">
            <input class="hidden" name="memberId" value="{{$info['id']}}">
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <div class="space-4"></div>
                <div class="col-md-9" style="padding: 0">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td colspan="10" class="center">
                                <h5 style="float: left">{{transShipMember("RegCard.Socio-political")}}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="center" style="width: 15%;">{{transShipMember("RegCard.Party")}}</td>
                            <td class="center" style="width: 15%;">{{transShipMember("RegCard.Party No")}}</td>
                            <td class="center" style="width: 15%;">{{transShipMember("RegCard.Membership standing")}}</td>
                            <td class="center" style="width: 15%;">{{transShipMember("RegCard.Employment Date")}}</td>
                            <td class="center" style="width: 10%;">{{transShipMember("RegCard.class origin")}}</td>
                            <td class="center" style="width: 10%;">{{transShipMember("RegCard.Sociality")}}</td>
                            <td class="center">{{transShipMember("RegCard.Citizenship card No")}}</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control chosen-select" name="isParty" style="width: 50%">
                                        <option value="0" @if(!isset($card['isParty'])) selected @endif>&nbsp;</option>
                                        <option value="1" @if(isset($card['isParty']) && $card['isParty'] == '1') selected @endif>로동당</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="partyNo" value="{{$card['partyNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control date-picker" style="text-align: center"
                                               type="text" data-date-format="yyyy/mm/dd"
                                               name="partyDate"
                                               value="{{$card['partyDate']}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control date-picker" style="text-align: center"
                                               type="text" data-date-format="yyyy/mm/dd"
                                               name="entryDate"
                                               value="{{$card['entryDate']}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            </span>
                                    </div>
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="fromOrigin">
                                        <option value="">&nbsp;</option>
                                        <option value="로동" @if($card['fromOrigin'] == '로동')) selected @endif>로동</option>
                                        <option value="군인" @if($card['fromOrigin'] == '군인')) selected @endif>군인</option>
                                        <option value="사무" @if($card['fromOrigin'] == '사무')) selected @endif>사무</option>
                                        <option value="농민" @if($card['fromOrigin'] == '농민')) selected @endif>농민</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="currOrigin">
                                        <option value="">&nbsp;</option>
                                        <option value="로동" @if($card['currOrigin'] == '로동')) selected @endif>로동</option>
                                        <option value="군인" @if($card['currOrigin'] == '군인')) selected @endif>군인</option>
                                        <option value="사무" @if($card['currOrigin'] == '사무')) selected @endif>사무</option>
                                        <option value="농민" @if($card['currOrigin'] == '농민')) selected @endif>농민</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="cardNum" value="{{$card['cardNum']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3" style="padding-right: 0">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <td colspan="10" class="center">
                                <h5 style="float: left">{{transShipMember("RegCard.Tall")}}, {{transShipMember("RegCard.bloodtype")}} ...</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="center">{{transShipMember("RegCard.Weight")}}</td>
                            <td class="center">{{transShipMember("RegCard.Tall")}}</td>
                            <td class="center" style="width: 70px;">{{transShipMember("RegCard.bloodtype")}}</td>
                            <td class="center">{{transShipMember("RegCard.Sheets size")}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="Weight" value="{{$info['Weight']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="text" name="Height" value="{{$info['Height']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="BloodType">
                                    <option value="A" @if($info['BloodType'] == 'A')) selected @endif>A{{transShipMember("captions.bloodType")}}</option>
                                    <option value="B" @if($info['BloodType'] == 'B')) selected @endif>B{{transShipMember("captions.bloodType")}}</option>
                                    <option value="O" @if($info['BloodType'] == 'O')) selected @endif>O{{transShipMember("captions.bloodType")}}</option>
                                    <option value="AB" @if($info['BloodType'] == 'AB')) selected @endif>AB{{transShipMember("captions.bloodType")}}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="ShoeNo" value="{{$info['ShoeNo']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="space-2"></div>
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td colspan="10" class="center">
                            <h5 style="float: left">{{transShipMember("RegCard.Career")}}</h5>
                            <a class="btn btn-sm btn-primary" style="float: right;width :80px;" href="javascript:newCareerRow()"><i class="icon-plus-sign-alt"></i>추가</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="center" style="width:10%">{{transShipMember("RegCard.From")}}</td>
                        <td class="center" style="width:10%">{{transShipMember("RegCard.To")}}</td>
                        <td class="center">{{transShipMember("RegCard.Workshop & Duty")}}</td>
                        <td class="center">{{transShipMember("RegCard.Workshop address")}}</td>
                        <td class="center">{{transShipMember("RegCard.Place of residence")}}</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody id="career_table">
                    <?php $index = 0; ?>
                    @foreach($careerList as $career)
                        <tr>
                            <td class="hidden">{{$index}}</td>
                            <td class="hidden">
                                <input type="text" name="career_{{$index}}" value="{{$index}}">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="fromDate_{{$index}}"
                                           value="{{$career['fromDate']}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            </span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="toDate_{{$index}}"
                                           value="{{$career['toDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="prePosition_{{$index}}" style="width: 100%;text-align: center"
                                       value="{{$career['prePosition']}}">
                            </td>
                            <td>
                                <input type="text" name="prePosPlace_{{$index}}" style="width: 100%;text-align: center"
                                       value="{{$career['prePosPlace']}}">
                            </td>
                            <td>
                                <input type="text" name="address_{{$index}}" style="width: 100%;text-align: center"
                                       value="{{$career['address']}}">
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="red" href="javascript:onCareerDelete({{$index}})">
                                        <i class="icon-trash bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $index++ ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="space-2"></div>
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td colspan="10" class="center">
                            <h5 style="float: left">{{transShipMember("RegCard.Family relatinShip")}}</h5>
                            <a class="btn btn-sm btn-primary" style="float: right; width :80px;" href="javascript:newFamilyRow()"><i class="icon-plus-sign-alt"></i>추가</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="center" style="width:5%">{{transShipMember("RegCard.No")}}</td>
                        <td class="center" style="width:10%">{{transShipMember("RegCard.Relation")}}</td>
                        <td class="center" style="width:8%">{{transShipMember("RegCard.Name")}}</td>
                        <td class="center" style="width:3%">{{transShipMember("RegCard.Sex")}}</td>
                        <td class="center" style="width: 10%;">{{transShipMember("RegCard.Birthday")}}</td>
                        <td class="center" style="width: 10%;">{{transShipMember("RegCard.Party")}}</td>
                        <td class="center">{{transShipMember("RegCard.BirthAddress")}}</td>
                        <td class="center">{{transShipMember("RegCard.WorkShop & Duty")}}</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody id="family_table">
                    <?php $index = 1; ?>
                    @foreach($familyList as $family)
                        <tr>
                            <td class="center">{{$index}}</td>
                            <td class="hidden">
                                <input type="text" name="family_{{$index}}" value="{{$index}}">
                            </td>
                            <td>
                                <input type="text" name="relation_{{$index}}" value="{{$family['relation']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="text" name="name_{{$index}}" value="{{$family['name']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="sex_{{$index}}">
                                    <option value="男" @if($family['sex'] == '男')) selected @endif>{{transShipMember("captions.male")}}</option>
                                    <option value="女" @if($family['sex'] == '女')) selected @endif>{{transShipMember("captions.female")}}</option>
                                </select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="birthday_{{$index}}"
                                           value="{{$family['birthday']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="isParty_{{$index}}">
                                    <option value="" @if(!isset($family['isParty'])) selected @endif>&nbsp;</option>
                                    <option value="!!" @if(isset($family['isParty'])) selected @endif>!!</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="birthPlace_{{$index}}"
                                       value="{{$family['birthPlace']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="text" name="position_{{$index}}"
                                       value="{{$family['position']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="red" href="javascript:onFamilyDelete({{$index}})">
                                        <i class="icon-trash bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $index++ ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-1 col-md-offset-5">
                    <button class="btn btn-inverse btn-sm" type="submit" style="width: 80px">
                        <i class="icon-save"></i>{{transShipMember('captions.register')}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>