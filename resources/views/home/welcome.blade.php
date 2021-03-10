@extends('layout.header')

@section('sidebar')

    <!-- kchs-->
    <link href="/assets/css/camera.css" rel="stylesheet">

    <div class="body">
        <div class="container">
            <div class="row-fluid">
                <div class="camera_wrap pattern_4 camera_emboss" id="camera_wrap_4">
                    <div data-src="/assets/img/bgimage1.jpg">
                        <div class="camera_caption fadeFromTop">
                            <a href="javascript:showFront()">
                            <h1>출근登记이 성과적으로 진행되였습니다.</h1>
                            </a>
                            <a href="javascript:showFront()">
                            <span>
						    즐거운 마음으로 하루사업을 시작하십시오.
                                <u>여기를 누르십시오.</u>
						    </span>
                            </a>
                        </div>
                    </div>
                    <div data-src="/assets/img/bgimage2.jpg">
                        <div class="camera_caption fadeFromRight">
                            <a href="javascript:showFront()">
                            <h1>출근登记이 성과적으로 진행되였습니다.</h1>
                            </a>
                            <a href="javascript:showFront()">
                            <span>
						    즐거운 마음으로 하루사업을 시작하십시오.
                                <u>여기를 누르십시오.</u>
						    </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <img src="/assets/img/sha.png" class="slidershadowcam" alt="">
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/jquery.js"></script>
    <script src="/assets/js/plugins.js"></script>

    <script>
        jQuery(function(){
            jQuery('#camera_wrap_4').camera({
                height: 'auto',
                loader: 'bar',
                pagination: false,
                thumbnails: false,
                hover: true,
                opacityOnGrid: false,
                fx: 'random',
                transPeriod: 1500,
                time: 7000,
                overlayer: true,
                imagePath: 'img/'
            });
        });
    </script>

    <script>
        function showFront() {
            window.location.href = 'home';
        }
    </script>
@endsection
