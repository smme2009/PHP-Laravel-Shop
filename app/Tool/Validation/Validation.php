<?php

namespace App\Tool\Validation;

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
    public static function validate(array $data, array $rule): Result
    {
        $validator = validator($data, $rule);

        $status = !$validator->fails();
        $errorList = $validator->errors()->toArray();

        return new Result($status, $errorList);
    }
}
