<?php
namespace core\lib;

use core\lib\request\drive\Get;
use core\lib\request\drive\Json;
use core\lib\request\drive\Post;

/**
 * Trait Request
 * Auth  leo.xie
 * mail 811329263@qq.com
 * @package core\lib
 */
trait Request
{
    public static function post($key=null,$default=null){
        return (new Post())->get($key,$default);
    }

    public static function get($key=null,$default=null){
        return (new Get())->get($key,$default);
    }

    public static function json($key=null,$default=null){
        return (new Json())->get($key,$default);
    }

    public static function params($key=null,$default=null){
        $post = (new Post())->get($key,$default);
        if($post !== $default){
            return $post;
        }
        $get = (new Get())->get($key,$default);
        if($get !== $default){
            return $get;
        }
        return (new Json())->get($key,$default);
    }
}