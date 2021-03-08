@foreach($list as $response)
    <div class="profile-activity clearfix">
        <div>
            <Strong style="color: #FB6941">{{$response['user']}} : </Strong>
            {{ $response['content'] }}
            <div class="time">
                <i class="icon-time bigger-110" style="padding-right: 10px"></i>1秒前
            </div>
        </div>
    </div>
@endforeach
