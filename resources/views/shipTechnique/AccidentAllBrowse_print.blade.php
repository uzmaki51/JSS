@if ($outMthd == 1)
    <?php $header = "layout.header-print";?>
@else
    <?php $header = "layout.excel-header";?>
@endif

@extends($header)
@section('content')
    @if($outMthd == 1)
    <div class="main-content">
        <div class="page-content">

            <div class="col-md-12">
                <div class="row">
                    <div class="space-4"></div>
    @else
        @include('layout.excel-style')
    @endif
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center" >배이름</th>
                            <th class="center">항차번호</th>
                            <th class="center">날자</th>
                            <th class="center">항명</th>
                            <th class="center">장소</th>
                            <th class="center">내용</th>
                            <th class="center">종류</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AccidentInfos as $AccidentInfo)
                            <tr class="center">
                                <td class="center">{{$AccidentInfo['shipName_Cn']}} | {{$AccidentInfo['shipName_En']}}</td>
                                <td class="center">{{$AccidentInfo['Voy_No']}} | {{$AccidentInfo['CP_No']}}</td>
                                <td class="center">{{convert_date($AccidentInfo['AccidentDate'])}}</td>
                                <td class="center">{{$AccidentInfo['Port_Cn']}}</td>
                                <td class="center">{{$AccidentInfo['Place']}}</td>
                                <td class="center"><label class="simple_text">{{$AccidentInfo['Content']}}</label></td>
                                <td class="center">
                                    @if($AccidentInfo['AccidentKind'] == 1)
                                        좌주
                                    @elseif($AccidentInfo['AccidentKind'] == 2)
                                        충돌
                                    @elseif($AccidentInfo['AccidentKind'] == 3)
                                        고장파손
                                    @elseif($AccidentInfo['AccidentKind'] == 4)
                                        분실
                                    @elseif($AccidentInfo['AccidentKind'] == 5)
                                        화물부족
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
    @if($outMthd == 1)
                </div>
            </div>
        </div>

    </div>
    @endif
@endsection

