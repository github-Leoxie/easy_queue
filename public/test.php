<?php

  try{
    throw new \Error('bbbb');
  //    throw new \Exception('bbb');
  }catch(\Exception $ex){
    echo $ex->getMessage()."\n";
  }
