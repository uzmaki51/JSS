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

            <div class="col-sm-12">
                <div class="row">
                    <div class="space-4"></div>
    @else
        @include('layout.excel-style')
    @endif
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center">배이름</th>
                            <th class="center">항차번호</th>
                            <th class="center">검사날자</th>
                            <th class="center">장소</th>
                            <th class="center">종류</th>
                            <th class="center">검사내용</th>
                            <th class="center">검사비</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($SurveyInfos as $SurveyInfo)
                            <tr class="center">
                                <td class="center" data-id="{{$SurveyInfo['id']}}">{{$SurveyInfo['shipName_Cn']}} | {{$SurveyInfo['shipName_En']}}</td>
                                <td class="center">{{$SurveyInfo['Voy_No']}} | {{$SurveyInfo['CP_No']}}</td>
                                <td class="center">{{convert_date($SurveyInfo['SurveyDate'])}}</td>
                                <td class="center">{{$SurveyInfo['Port_Cn']}}</td>
                                <td class="center">{{$SurveyInfo['SurveyKind_Cn']}}</td>
                                <td><label class="simple_text">{{$SurveyInfo['Content']}}</label></td>
                                <td class="center">{{$SurveyInfo['Amount']}}</td>
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

