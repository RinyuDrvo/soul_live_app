<?php
function h($var){//htmlでエスケープ処理をする関数
    if(is_array($var)){
        return array_map('h',$var);
    }else{
        return htmlspecialchars($var,ENT_QUOTES,'UTF-8');
    }
}
?>