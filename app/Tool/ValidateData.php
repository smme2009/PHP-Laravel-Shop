<?php

namespace App\Tool;

/**
 * 驗證資料
 */
class validateData
{
    /**
     * 驗證資料
     * 
     * @param array $data 資料
     * @param array $rule 規則
     * 
     * @return array
     */
    public static function validateData(array $data, array $rule): array
    {
        $validator = validator($data, $rule);

        $result = [
            'status' => !$validator->fails(),
            'errorMessage' => $validator->errors()->all(),
        ];

        return $result;
    }
}
