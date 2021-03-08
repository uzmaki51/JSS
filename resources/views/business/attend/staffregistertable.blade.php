<table id="staff_attendancelist_table"
       class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>{{transBusinessManage("captions.no")}}</th>
        <th>{{transBusinessManage("captions.name")}}</th>
        <th>{{transBusinessManage("captions.enterstate")}}</th>
        <th>{{transBusinessManage("captions.registertime")}}</th>
        <th>{{transBusinessManage("captions.memo")}}</th>
    </tr>
    </thead>
    <tbody>
    <?php $index = 1;?>
    @foreach($attendancestafflist as $attendancestaff)
        <tr id="{{$attendancestaff->id}}">
            <td>{{$index++}}</td>
            <td>{{$attendancestaff->staffname}}</td>
            <td>
                @if($attendancestaff->statusid==2)
                    <select class="form-control" value="{{$attendancestaff->status}}"
                            oldvalue="{{$attendancestaff->status}}">
                        @foreach($attendancetype as $type)
                            <option value="{{$type->id}}"
                                    @if($type->id==$attendancestaff->statusid) selected @endif>{!! $type->name !!}</option>
                        @endforeach
                    </select>
                @else
                    {{$attendancestaff->status}}
                @endif
            </td>
            <td>{{$attendancestaff->registeredtime}}</td>
            <td>
                @if ($attendancestaff->memo == null)
                    <input type="text" placeholder="请填写留下便条。" style="width:100%;"
                           value="" oldvalue="">
                @else
                    {{$attendancestaff->memo}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
