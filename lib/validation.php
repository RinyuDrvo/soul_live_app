<?php
//汎用バリデーション関数
//第一引数:バリデーション対象のデータ(char) 第二引数:データの名前(char) 第3引数:文字列の長さ(int)
function validation($data,$data_name,$data_len){
    //汎用バリデーション
    if(!isset($data) || !is_string($data) || $data === ''){
        //エラーをExceptionクラスに投げる
        throw new Exception($data_name . 'が不正な値です');
    }
    //データの文字数が指定されていれば
    if($data_len !== ''){
        //文字数バリデーション
        if(strlen($data) !== $data_len){
            //エラーをErrorExceptionクラスに投げる
            throw new Exception($data_name . 'の長さが不正です');
        }
    }
}
?>