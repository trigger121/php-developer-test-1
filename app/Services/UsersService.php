<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UsersService
{

    /**
     * This is to map an array of users against the User Model
     * @param array $users
     * @throws \Exception
     */
    public function mapAndStore(array $users)
    {
        foreach ($users as $user) {
            $this->validateUsers($user);
        }
        /** @var User[] $userModels */
        $userModels = collect($users)->mapInto(User::class);
        foreach ($userModels as $userModel) {
            $userModel->save();
        }

        return $userModels;
    }

    /**
     * @param array $user
     * @throws \Exception
     */
    public function validateUsers(array $user)
    {
        $validator = Validator::make(
            $user,
            [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'avatar' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            throw new \Exception('User Failed Validation');
        }
    }
}
