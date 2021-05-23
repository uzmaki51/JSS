<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tb_users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['account', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];


	public function getRecvUser($flowType, $reportId) {
		$unit = DB::table('tb_users')
			->where('id', Auth::user()->id)
			->select('*')
			->first()->unit;

		$friends = DB::table('tb_users')
			->where('unit', $unit)
			->select('*')
			->get();

		$friendID = ',';
		if($flowType == 1) {
			foreach($friends as $key => $item)
				$friendID .= $item->id . ',';
		} else {
			$recvUser = DB::table($this->table)
				->where('isAdmin', 2)
				->first()->id;

			$friendID .= Auth::user()->id . ',';
			$friendID .= $recvUser . ',';
		}

		DB::table('tb_decision_report')
			->where('id', $reportId)
			->update([
				'recvUser'  => $friendID
			]);
	}

	public static function getSimpleUserList($unit = null, $pos = null, $realname = null, $status = null) {
		$query = static::query()->select('tb_users.*', 'tb_pos.title as posTitle', 'tb_unit.title as unitTitle')
			->leftJoin('tb_pos', 'tb_users.pos', '=', 'tb_pos.id')
			->leftJoin('tb_unit', 'tb_users.unit', '=', 'tb_unit.id');

		if(isset($unit))
			$query->where('tb_users.unit', $unit);

		if(isset($pos))
			$query->where('tb_users.pos', $pos);

		if(isset($realname))
			$query->where('tb_users.realname', 'like', '%'.$realname.'%');

		if(isset($status))
			$query->where('tb_users.status', $status);

		//$result = $query->orderBy('tb_unit.orderkey')->orderBy('tb_pos.orderNum')->paginate()->setPath('');
		$result = $query->orderBy('tb_users.pos', 'asc')->paginate()->setPath('');

		return $result;
	}

	public function isAdmin() {
		$result = $this->query()
			->select('tb_users.isAdmin')
			->where('tb_users.id', $this['id'])
			->first();

		if(empty($result)) {
			$result = new \stdClass();
			$result->isAdmin = 0;
		}

		return $result;
	}
}
