<table class="table table-striped table-bordered table-hover" id="mark_table">
    <thead>
    <tr>
        <th colspan="2">
            <h5 style="float: left">各题分数 ({{$examCode}})</h5>
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
        <tr><td colspan="2" data-old="-1">没有资料。</td></tr>
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
</table>*****{{round($avg / ($index + 1), 2)}}
