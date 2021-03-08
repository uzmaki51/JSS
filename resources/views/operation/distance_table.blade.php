<table class="table table-bordered table-striped table-hover" id="distance-table">
    <tbody>
    @foreach($distanceList as $port)
    <tr>
        <td style="width:5%"><input type="checkbox"></td>
        <td style="width:19%">{{$port['DeptPort']}}</td>
        <td style="width:19%">{{$port['LPort_Cn']}}</td>
        <td style="width:19%">{{$port['ArvdPort']}}</td>
        <td style="width:19%">{{$port['DPort_Cn']}}</td>
        <td class="center" style="width:19%">{{$port['SailDistance']}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
