<select name="search_voy_number" id="search_voy_number" class="form-control" style="text-align:center;">
    <option value=""></option>
    @foreach($cps as $cp)
        <option value="{{$cp['CP_No']}}">{{$cp['Voy_No']}} | {{$cp['CP_No']}}</option>
    @endforeach
</select>
