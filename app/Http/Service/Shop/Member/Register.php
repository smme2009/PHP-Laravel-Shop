<?php

namespace App\Http\Service\Shop\Member;

use Illuminate\Support\Facades\Hash;
use App\Http\Service\Service;
use App\Http\Repository\Shop\Member\Register as RepoRegister;
use App\Tool\Validation\Validation as ToolValidation;
use App\Tool\Validation\Result;

/**
 * 會員註冊
 */
class Register extends Service
{
    public function __construct(
        private RepoRegister $repoRegister,
    ) {

    }

    /**  
     * 驗證資料
     * 
     * @param array $memberData 會員資料
     * 
     * @return Result 驗證結果
     */
    public function validateData(array $memberData): Result
    {
        $modelPath = 'App\Models\Member';

        // 驗證規則
        $rule = [
            'account' => ['required', 'string', 'email', 'unique:' . $modelPath],
            'password' => ['required', 'string', 'alpha_num:ascii'],
            'checkPassword' => ['required', 'string', 'same:password'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'regex:/^09\d{8}$/', 'unique:' . $modelPath]
        ];

        $result = ToolValidation::validate($memberData, $rule);

        return $result;
    }

    /**
     * 註冊會員帳號
     * 
     * @param array $memberData 會員資料
     * 
     * @return bool
     */
    public function registerMember(array $memberData): bool
    {
        $memberData['password'] = Hash::make($memberData['password']);

        $isAdd = $this->repoRegister->addMember($memberData);

        return $isAdd;
    }
}
