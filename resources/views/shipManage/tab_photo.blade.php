<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>
<div class="row">
    <div class="col-md-12">
        <div class="row-fluid">
            <ul class="ace-thumbnails">
            @foreach($imageList as $image)
                <li>
                    <a href="/uploads/ship/{{$image['path']}}" data-rel="colorbox">
                        <img style="width:250px" src="/uploads/ship/{{$image['path']}}" />
                    </a>
                    <div class="tools tools-bottom">
                        <a href="javascript:deleteImage({{$image['id']}})">
                            <i class="icon-remove red"></i>
                        </a>
                    </div>
                </li>
            @endforeach
                <li>
                    <div class="center" style="width:160px;height:180px">
                        <span class="profile-picture">
                            <img id="shipPhoto" class="editable img-responsive" alt="船舶照片" />
                        </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

