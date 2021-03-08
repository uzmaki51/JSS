<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ t('Title') }}</title>



	<link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="/assets/css/font-awesome.min.css" />
	<link rel="icon" type="image/png" href="{{ cAsset('/assets/css/img/logo.png') }}" sizes="192x192">
	<!--[if IE 7]>
	<link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css" />
	<![endif]-->


	<link rel="stylesheet" href="/assets/css/ace.min.css" />
	<link rel="stylesheet" href="/assets/css/ace-rtl.min.css" />
	<link rel="stylesheet" href="/assets/css/theme.css" />

	<!--[if lte IE 8]>
	<link rel="stylesheet" href="/assets/css/ace-ie.min.css" />
	<![endif]-->

    <style>
        body {
            background-color: #c5f1ff!important;
        }
    </style>

	<!-- inline styles related to this page -->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!--[if lt IE 9]>
	<script src="/assets/js/html5shiv.js"></script>
	<script src="/assets/js/respond.min.js"></script>
	<![endif]-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	@yield('content')

</body>
</html>
