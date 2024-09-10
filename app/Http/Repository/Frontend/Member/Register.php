<?php

namespace App\Http\Repository\Frontend\Member;

use App\Models\Member as ModeMember;

/**
 * 會員註冊
 */
class Register
{
    public function __construct(
        public ModeMember $member,
    ) {

    }

    /**
     * 新增會員帳號
     * 
     * @param array $memberData 會員資料 
     * 
     * @return bool
     */
    public function addMember(array $memberData)
    {
        $this->member->account = $memberData['account'];
        $this->member->password = $memberData['password'];
        $this->member->name = $memberData['name'];
        $this->member->phone = $memberData['phone'];
        $this->member->status = 1;

        $isAdd = $this->member->save();

        return $isAdd;
    }
}
