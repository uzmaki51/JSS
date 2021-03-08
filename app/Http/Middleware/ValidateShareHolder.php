<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Auth;
use Session;

class ValidateShareHolder extends BaseVerifier {

	public function handle($request, Closure $next)
	{
		$isShareHolder = false;
		$shipList = array();
		if(Auth::user()) {
			$isShareHolder = Auth::user()->isAdmin == IS_SHAREHOLDER ? true : false;
			$shipList = explode(',', Auth::user()->shipList);
		}

		Session::put('IS_HOLDER', $isShareHolder);
		Session::put('shipList', $shipList);

		return parent::handle($request, $next);
	}

}
