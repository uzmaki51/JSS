<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-9">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="6">
                            <h5 style="float: left">{{transShipMember("RealAblility.Career")}}</h5>
                            <a class="btn btn-sm btn-primary" style="float: right;width :80px;" href="javascript:newExamingRow()"><i class="icon-plus-sign-alt"></i>추가</a>
                        </th>
                    </tr>
                    <tr>
                        <th class="center" style="width:20%;">{{transShipMember("RealAblility.Ref No")}}</th>
                        <th class="center" style="width:20%">{{transShipMember("RealAblility.Date")}}</th>
                        <th class="center" style="width:20%;">{{transShipMember("RealAblility.Place")}}</th>
                        <th class="center" style="width:20%">{{transShipMember("RealAblility.Subject")}}</th>
                        <th class="center" style="width:10%;">{{transShipMember("RealAblility.Grades")}}</th>
                        <th style="text-align: center;"></th>
                    </tr>
                </thead>
                <tbody id="examing_table">
                    <?php $index = -1; ?>
                    @foreach($examingList as $exam)
                        <?php $index++; ?>
                        <tr>
                            <input type="hidden" id="examId_{{$index}}" value="{{$exam['id']}}">
                            <td class="center">
                                <input type="text" name="examCode_{{$index}}" class="form-control" data-old="{{$exam['ExamCode']}}"
                                       value="{{$exam['ExamCode']}}" style="width:100%;text-align: center" disabled>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" name="examDate_{{$index}}" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           value="{{$exam['ExamDate']}}" data-old="{{$exam['ExamDate']}}" disabled>
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="examPlace_{{$index}}" class="form-control"
                                       style="width:100%;text-align: center" value="{{$exam['Place']}}" data-old="{{$exam['Place']}}" disabled>
                            </td>
                            <td>
                                <select class="form-control" name="examSubject_{{$index}}" data-old="{{$exam['Subject']}}" disabled>
                                    <option value="정치" @if($exam['Subject'] == '정치') selected @endif>정치</option>
                                    <option value="영어" @if($exam['Subject'] == '영어') selected @endif>영어</option>
                                    <option value="전공" @if($exam['Subject'] == '전공') selected @endif>전공</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control" name="examMarks_{{$index}}"
                                       value="{{$exam['Marks']}}" data-old="{{$exam['Marks']}}" style="width:100%;text-align: center" disabled>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons" id="{{$exam['id']}}" name="{{$index}}">
                                    <a class="blue" onclick="javascript:editMemberExaming(this)">
                                        <i class="icon-edit bigger-110"></i>
                                    </a>
                                    <a class="red" onclick="javascript:deleteMemberExaming(this)">
                                        <i class="icon-trash bigger-110"></i>
                                    </a>
                                    <a class="blue" onclick="javascript:selectMemberExaming(this)">
                                        <i class="icon-bar-chart bigger-110"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="row" style="height:270px;overflow-y: auto;margin-right: 0">
                <div class="col-md-11" style="margin-left: 7%;">
                    <table class="table table-striped table-bordered table-hover" id="mark_table">
                        <thead>
                        <tr>
                            <th colspan="2">
                                <h5 style="float: left">{{transShipMember("RealAblility.Marks")}} @if(count($examingList) > 0)({{$examingList[0]['ExamCode']}})@endif</h5>
                                <div class="action-buttons" style="float: right;padding-top: 10px">
                                    <a class="blue" href="javascript:newExamingMarkRow()">
                                        <i class="icon-edit bigger-130"></i>
                                    </a>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <input type="hidden" id="examId" value="{{$examId}}">
                        <tbody id="subMark_table">
                        <?php $avg = 0; $index = -2;?>
                        @if(count($subList) == 0)
                            <tr><td colspan="2" data-old="-1">{{transShipMember("RealAblility.No Data")}}.</td></tr>
                        @else
                            <?php $index = -1; ?>
                            @foreach($subList as $mark)
                                <?php $index++; ?>
                                <tr>
                                    <input type="hidden" id="subMarksId_{{$index}}" value="{{$mark['id']}}">
                                    <td>
                                        <input type="text" id="subMarks_{{$index}}" class="form-control" disabled
                                               value="{{$mark['SubMarks']}}" data-old="{{$mark['SubMarks']}}" style="width:100%">
                                    </td>
                                    <td class="center" style="width: 60px">
                                        <div class="action-buttons" name="{{$index}}" id="{{$mark['id']}}">
                                            <a class="blue" onclick="javascript:editSubMarkRow(this)">
                                                <i class="icon-edit bigger-110"></i>
                                            </a>
                                            <a class="red" onclick="javascript:deleteSubMarkRow(this)">
                                                <i class="icon-trash bigger-110"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php $avg += $mark['SubMarks']; ?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="space-2"></div>
                <div class="col-md-6 col-md-offset-3 center" style="background: #D6E8ED;border: 1px solid #ddd">
                    <label id="eval_mark">transShipMember('captions.averageScore') : {{round($avg / ($index + 1), 2)}}</label>
                </div>
            </div>
        </div>
    </div>
</div>