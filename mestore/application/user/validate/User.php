<?php
namespace app\user\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
	    'tell'   => 'require|length:11|unique:user,tell'
	];
	protected $message = [
	    'tell.require' => '手机号码必须填写',
	    'tell.length'  => '手机号长度必须为11位',
	    'tell.unique'  => '手机号已经被绑定'
	];

	

}