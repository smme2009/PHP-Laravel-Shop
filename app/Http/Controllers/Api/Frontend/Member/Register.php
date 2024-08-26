<?php

namespace App\Http\Controllers\Api\Frontend\Member;

use App\Http\Controllers\Controller;

use App\Http\Service\Frontend\Member\Register as SrcRegister;

/**
 * 會員註冊
 */
class Register extends Controller
{
    public function __construct(
        private SrcRegister $srcRegister,
    ) {
    }

    /**
     * 註冊會員
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerMember()
    {
        $memberData = [
            'account' => request()->get('account'),
            'password' => request()->get('password'),
            'name' => request()->get('name'),
            'phone' => request()->get('phone'),
        ];

        $result = $this->srcRegister->validateData($memberData);

        if (!$result->status) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
                ->get();

            return $response;
        }

        $isAdd = $this->srcRegister->registerMember($memberData);

        if (!$isAdd) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('註冊會員失敗')
                ->get();
        }

        $response = $this->toolResponseJson()
            ->setMessage('成功註冊會員')
            ->get();

        return $response;
    }
}