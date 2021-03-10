@extends('app')

@section('content')
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
									<label class="col-md-4 control-label">密码确认</label>
									<div class="col-md-8">
										<input type="password" class="form-control" name="password_confirmation">
									</div>
								</div>

								<div style="margin-right: 10px">
									<div class="form-group" style="text-align: center">
										<button type="submit"
												class="width-30 pull-right btn btn-sm btn-primary">
											<i class="icon-refresh"></i>
											更新密码
										</button>
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
