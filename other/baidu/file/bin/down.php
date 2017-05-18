<?php
ini_set('max_execution_time','0');
$url=$_GET['url'];
$basedir = dirname(__FILE__);
$exePath=$basedir.'\app.exe';
$jsPath=$basedir.'\app.js';
$fileName=exec($exePath.' '.$jsPath.' '.$url , $output, $retVal);
//$fileName=exec('app  D:\eclipse.js '.$url , $output, $retVal);
$data=$fileName;
echo $data;