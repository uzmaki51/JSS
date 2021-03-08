@if($type == 'voy')
    <option value="">&nbsp;</option>
    @foreach($cpInfos as $cp)
        <option value="{{ $cp['id'] }}">{{ $cp['Voy_No'] }} | {{ $cp['CP_No'] }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($equipInfos as $equipInfo)
        <option value="{{ $equipInfo->id }}">{{ $equipInfo->Euipment_Cn }}({{ $equipInfo->GroupOfEuipment_Cn }})|{{ $equipInfo->Euipment_En }}|{{ $equipInfo->Type }}|{{ $equipInfo->SN }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($equipInfos as $equipInfo)
        <option value="{{ $equipInfo->id }}">{{ $equipInfo->Label }}|{{ $equipInfo->Type }}|{{ $equipInfo->SN }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($parts as $part)
        <option value="{{ $part->id }}">{{ $part->PartName_Cn }}|{{ $part->PartName_En }}|{{ $part->PartNo }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($parts as $part)
        <option value="{{ $part->id }}">{{ $part->PartNo }}</option>
    @endforeach
@elseif($type == 'equipment')
    <option value="">&nbsp;</option>
    @foreach($equipInfos as $equipInfo)
        <option value="{{ $equipInfo->id }}">{{ $equipInfo->Euipment_Cn }}({{ $equipInfo->GroupOfEuipment_Cn }})|{{ $equipInfo->Euipment_En }}|{{ $equipInfo->Type }}|{{ $equipInfo->SN }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($equipInfos as $equipInfo)
        <option value="{{ $equipInfo->id }}">{{ $equipInfo->Label }}|{{ $equipInfo->Type }}|{{ $equipInfo->SN }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($parts as $part)
        <option value="{{ $part->id }}">{{ $part->PartName_Cn }}|{{ $part->PartName_En }}|{{ $part->PartNo }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($parts as $part)
        <option value="{{ $part->id }}">{{ $part->PartNo }}</option>
    @endforeach
@elseif($type == 'part')
    <option value="">&nbsp;</option>
    @foreach($parts as $part)
        <option value="{{ $part->id }}">{{ $part->PartName_Cn }}|{{ $part->PartName_En }}|{{ $part->PartNo }}</option>
    @endforeach
    *****
    <option value="">&nbsp;</option>
    @foreach($parts as $part)
        <option value="{{ $part->id }}">{{ $part->PartNo }}</option>
    @endforeach
@endif