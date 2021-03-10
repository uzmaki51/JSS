<form class="form-horizontal" action="updateCertInfo" id="certUpdateForm" method="POST" enctype="multipart/form-data">
    <input type="text" class="hidden" name="id" value="{{$info['id']}}">
    <input type="text" class="hidden" name="ShipName" value="{{$info['ShipName']}}">
    <input type="text" class="hidden" name="certName" value="">
    <input type="text" class="hidden" name="issuUnit" value="">
    <input type="text" class="hidden" name="expireMonth" value="">
    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
    <input type="submit" class="hidden" id="submit_btn">
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">证书名:</label>
        <div class="col-md-8">
            <select name="CertNo" class="form-control chosen-select">
                @foreach($certList as $type)
                    <option value="{{$type['CertNo']}}" @if(isset($info) && ($info['CertNo'] == $type['CertNo'])) selected @endif>
                        {{ $type['CertNo'] .' | '.$type['CertName_Cn'].' | '.$type['CertName_En'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">签发部门(中文):</label>
        <div class="col-md-8"><input type="text" name="IssuedAdmin_Cn" class="form-control" value="{{$info['IssuedAdmin_Cn']}}"></div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">签发部门(英文):</label>
        <div class="col-md-8"><input type="text" name="IssuedAdmin_En" class="form-control" value="{{$info['IssuedAdmin_En']}}"></div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">证书等级:</label>
        <div class="col-md-8">
            <select name="CertLevel" class="form-control chosen-select">
                <option value="完全证书" @if($info['CertLevel'] == '完全证书') selected @endif>完全证书</option>
                <option value="临时证书" @if($info['CertLevel'] == '临时证书') selected @endif>临时证书</option>
            </select>
        </div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">签发日期:</label>
        <div class="input-group  col-md-8">
            <input class="form-control date-picker" name="IssuedDate" type="text" data-date-format="yyyy-mm-dd" value="{{$info['IssuedDate']}}">
            <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
        </div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">有效期至:</label>
        <div class="input-group col-md-8">
            <input class="form-control date-picker" name="ExpiredDate" type="text" data-date-format="yyyy-mm-dd" value="{{$info['ExpiredDate']}}">
            <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
        </div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">备注:</label>
        <div class="col-md-8"><textarea name="Remark" class="form-control">{{$info['Remark']}}</textarea></div>
    </div>
    <div class="space-2"></div>
    <div class="form-group">
        <label class="col-md-3 control-label no-padding-right">复本:</label>
        @if(!empty($info['Scan']))
            <?php
                $temp = explode('.', $info['Scan']);
                $ext = '.'.end($temp);
                $filename = $info['CertName_Cn'].'_证书复本.'.$ext;
            ?>
            <a href="/fileDownload?type=ship-cert&path={{ $info['Scan'] }}&filename={{ $filename }}"
               class="hide-option" title="{{$info['CertName_Cn']}}_证书复本" style="float:left;padding-top:6px">
                <i class="blue icon-print bigger-200"></i>
            </a>
        @endif
        <div class="col-md-4">
            <input multiple="" type="file" id="copy-photo" name="copy-photo" style="display: none">
        </div>
    </div>
</form>
