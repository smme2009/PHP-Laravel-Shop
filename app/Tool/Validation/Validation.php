<?php

namespace App\Tool\Validation;

use App\Tool\Validation\Result;

/**
 * 驗證資料
 */
class Validation
{
    /**
     * 驗證資料
     * 
     * @param array $data 資料
     * @param array $rule 規則
     * 
     * @return Result
     */
    public static function validateData(array $data, array $rule)
    {
        $validator = validator($data, $rule);

        $status = !$validator->fails();
        $message = $validator->errors()->all();

        $result = new Result($status, $message);

        return $result;
    }
}
