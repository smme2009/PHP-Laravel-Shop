<?php

namespace App\Http\Service\Frontend\Member;

use App\Http\Service\Service;

use Illuminate\Support\Facades\Hash;

use App\Http\Repository\Frontend\Member\Register as RepoRegister;

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
     * @return \App\Tool\Validation\Result 驗證結果
     */
    public function validateData(array $memberData)
    {
        $modelPath = 'App\Models\Member';

        // 驗證規則
        $rule = [
            'account' => ['required', 'string', 'email', 'unique:' . $modelPath],
            'password' => ['required', 'string', 'alpha_num:ascii'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string', 'regex:/^09\d{8}$/', 'unique:' . $modelPath]
        ];

        $result = $this->toolValidation()->validateData($memberData, $rule);

        return $result;
    }

    /**
     * 註冊會員帳號
     * 
     * @param array $memberData 會員資料
     * 
     * @return bool
     */
    public function registerMember(array $memberData)
    {
        $memberData['password'] = Hash::make($memberData['password']);

        $isAdd = $this->repoRegister->addMember($memberData);

        return $isAdd;
    }
}
