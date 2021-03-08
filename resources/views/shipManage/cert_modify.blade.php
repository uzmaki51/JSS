<form class="form-horizontal" action="updateCertType" id="certUpdateForm" method="POST">
    <input type="text" class="hidden" name="id" value="{{$info['id']}}">
    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
    <input type="text" class="hidden" name="cert" value="">
    <input type="submit" class="hidden" id="submit_btn">
    <div class="form-group">
        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('CertManage.RefNo') }}</label>
        <div class="col-md-7"><input type="text" name="CertNo" class="form-control" value="{{$info['CertNo']}}"></div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('CertManage.CertName_Cn') }}</label>
        <div class="col-md-7"><input type="text" name="CertName_Cn" class="form-control" value="{{$info['CertName_Cn']}}"></div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('CertManage.CertName_En') }}</label>
        <div class="col-md-7"><input type="text" name="CertName_En" class="form-control" value="{{$info['CertName_En']}}"></div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('CertManage.Kind') }}</label>
        <div class="col-md-7">
            <select name="CertKind" class="form-control chosen-select">
                <option value="{{ transShipManager('captions.nationality') }}" @if($info['CertKind'] == transShipManager('captions.nationality')) selected @endif>{{ transShipManager('captions.nationality') }}</option>
                <option value="{{ transShipManager('captions.shiplevel') }}" @if($info['CertKind'] == transShipManager('captions.shiplevel')) selected @endif>{{ transShipManager('captions.shiplevel') }}</option>
                <option value="{{ transShipManager('captions.insurance') }}" @if($info['CertKind'] == transShipManager('captions.insurance')) selected @endif>{{ transShipManager('captions.insurance') }}</option>
                <option value="{{ transShipManager('captions.safetyequip') }}" @if($info['CertKind'] == transShipManager('captions.safetyequip')) selected @endif>{{ transShipManager('captions.safetyequip') }}</option>
            </select>
        </div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('CertManage.Description') }}</label>
        <div class="col-md-7"><textarea name="Details" class="form-control">{{$info['Details']}}</textarea></div>
    </div>
</form>
