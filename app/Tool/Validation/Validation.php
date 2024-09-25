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
    public function validateData(array $data, array $rule): Result
    {
        $validator = validator($data, $rule);

        $status = !$validator->fails();
        $error = $validator->errors()->toArray();

        $result = new Result($status, $error);

        return $result;
    }
}
