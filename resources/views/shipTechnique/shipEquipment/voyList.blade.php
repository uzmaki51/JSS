<select id="voy" name="voy" class="form-control chosen-select" style="height: 25px">
    @foreach($voyInfos as $voyInfo)
        <option value="{{$voyInfo['id']}}"
                @if (isset($voy) && $voyInfo['id'] == $voy) selected @endif>
            {{$voyInfo['Voy_No']}} | {{$voyInfo['CP_No']}}
        </option>
    @endforeach
</select>