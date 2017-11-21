<?php
namespace Api\Model;
use Think\Model;
class UserController extends Model {
    protected $connection = array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => 'root',
        'db_host'  => 'localhost',
        'db_port'  => '3306',
        'db_name'  => 'cmpoints',
        'db_charset' =>    'utf8',
        'db_prefix' => 'cp_'
    );
}