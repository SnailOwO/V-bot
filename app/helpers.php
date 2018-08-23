<?php
if (!function_exists('customResponse')) {
    function customResponse($errors,$data = array(),$code = 200) {
        return response()->json([
            'msg' => is_array($errors) ? current($errors) : $errors,   //验证规则虽然支持取出所有错误，但是目前没遇到要显示所有错误的地方，所以暂时空着。
            'data' => $data
        ],$code);
    }
}

if (!function_exists('failResponse')) {
    function failResponse($errors,$code = 422) {
        return response()->json([
            'msg' => is_array($errors) ? current($errors) : $errors,   //验证规则虽然支持取出所有错误，但是目前没遇到要显示所有错误的地方，所以暂时空着。
        ],$code);
    }
}

if (!function_exists('customValidate')) {
    function customValidate($validate_data,$rules,$isAll = false) {
        $validator = \Validator::make($validate_data, $rules);
        if ($validator->fails()) {
            $errors = $isAll ? $validator->errors()->all() : $validator->errors()->first(); 
            return $errors;
        }
        return false;
    }
}

if(!function_exists('ts')){
    function ts($code,$lang='zh'){
        $lang = empty($lang)?'zh':$lang;
        $code = preg_replace('/[^0-9a-zA-z.-_ ]/', '', $code);
        $trans = trans($code,[],'',$lang);
        if(empty($trans)||$trans == $code){
            $trans = ucwords(preg_replace('/([0-9a-zA-z-_ ]*[.])*/', '', $code));
        }
        return $trans;
    }
}

if(!function_exists('changeWhereAry')){   //仅仅用于生成where数组,其他情况还需自己调用orwhere whereIn
    function changeWhereAry($whereAry,$structAry) {
        $ary = array();
        if(!empty($whereAry)) {
            foreach($whereAry as $key => $val) {
                if(isset($structAry[$key])) {
                    switch($structAry[$key]) {
                        case 'like':
                            if(is_array($val)) {
                                foreach($val as $k => $v) {
                                    array_push($ary,[$key,$structAry[$key],'%'. $v .'%']);  
                                }
                            } else {
                                array_push($ary,[$key,$structAry[$key],'%'. $val .'%']); 
                            }
                            break;
                        case '>':
                        case '<':
                        case '>=':
                        case '<=':
                        case '!=':
                        case '<>':
                        case '=':
                            if(is_array($val)) {
                                foreach($val as $k => $v) {
                                    array_push($ary,[$key,$structAry[$key],$v]); 
                                }
                            } else {
                                array_push($ary,[$key,$structAry[$key],$val]); 
                            }
                           break;
                        case 'bw':
                            if(is_array($val)) {   // between 暂不支持 array
                                break;
                            } 
                            $val = array_filter($val);   //过滤空字符
                            if(count($val) == 1) {   //防止多传
                                array_push($ary,[$key,current($val)]); 
                            }
                            if(count($val) == 2) {   //防止多传
                                array_push($ary,[$key,'>=',current($val)]); 
                                array_push($ary,[$key,'<=',end($val)]);
                            }
                            break;
                    }
                }
            }
            
        }
        return $ary;
    }
}


