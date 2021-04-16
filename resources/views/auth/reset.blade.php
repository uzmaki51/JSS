@extends('app')

@section('content')
<body style="background:#55a4f4">
<div class="container-fluid">
	<div class="row">
		<div class="space-30"></div>
		<div class="space-30"></div>
		<div class="space-30"></div>
		<div class="space-30"></div>
		<div class="space-30"></div>
		<div class="space-30"></div>
		<div class="space-30"></div>

		<div class="position-relative">
			<div class="login-container">
				<div class="panel panel-default">
					<div class="panel-heading">变更密码</div>
					<div class="panel-body">
						@if (!empty($state) && ($state == 'error'))
							<div class="alert alert-danger">
								<ul>
									<li>错误 : {{ $msg }}</li>
								</ul>
							</div>
						@endif

						<form class="form-horizontal" role="form" method="POST" action="{{ url('/home/resetPassword') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="col-md-11">
								<div class="form-group">
									<label class="col-md-4 control-label">旧密码</label>
									<div class="col-md-8">
										<input type="password" class="form-control" name="old_passwd" value="{{ old('old_passwd') }}">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">新密码</label>
									<div class="col-md-8">
										<input type="password" class="form-control" name="password">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">确认密码</label>
									<div class="col-md-8">
										<input type="password" class="form-control" name="password_confirmation">
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div style="text-align:right;">
									<div class="btn-group f-right">
										<button type="submit" class="btn btn-sm btn-info" style="width: 80px">
											<i class="icon-refresh"></i>更新密码
										</button>
										<a href="/home" class="btn btn-sm btn-primary btn-add" style="width: 80px">
											<i class="icon-remove"></i>{{ trans('common.label.cancel') }}
										</a>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
	<script>
		$(function() {
			$("#validation-form").validate({
				rules: {
					old_passwd : "required",
					password: "required",
					password_confirmation: "required",
				},
				messages: {
					old_passwd: "이전의 암호를 입력하십시오.",
					password: "새 암호를 입력하십시오.",
					password_confirmation: "암호确认을 입력하십시오.",
				}
			});
		});
	</script>
@endsection
