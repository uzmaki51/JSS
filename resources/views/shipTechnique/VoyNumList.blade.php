<select name="VoyId" class="form-control" style="width: 100%">
    @foreach($cps as $cp)
        <option value="{{$cp['CP_No']}}">{{$cp['Voy_No']}} | {{$cp['CP_No']}}</option>
    @endforeach
</select>
