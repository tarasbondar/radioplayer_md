<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Throwable
     */
    public function create($name, $email, $password)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $name ?: $email,
                'role' => User::ROLE_USER,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }


    public function changePassword(User $user, string $password): bool
    {
        DB::beginTransaction();

        try {
            $user->password = Hash::make($password);

            if (!$this->userRepository->save($user))
                throw new Exception('Can not update user name');

            // TODO: send verification mail

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return true;

    }

    /**
     * @throws Throwable
     */
    public function changeName(User $user, $newName): bool
    {
        DB::beginTransaction();

        try {
            $user->name = $newName;

            if (!$this->userRepository->save($user))
                throw new Exception('Can not update user name');

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return true;
    }


    public function getUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getUser(int $id){
        return $this->userRepository->getUser($id);
    }

    /**
     * @throws Throwable
     */
    public function sendConfirmEmail(User $user): bool
    {
        // implementation
    }
}
