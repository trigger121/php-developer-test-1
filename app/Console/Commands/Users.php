<?php

namespace App\Console\Commands;

use App\Services\Api\Users\Request;
use App\Services\UsersService;
use Exception;
use Illuminate\Console\Command;

class Users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zeffr:getUsers {page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows you to call a 3rd Party API to pull in user data';

    private UsersService $userService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UsersService $usersService)
    {
        parent::__construct();
        $this->userService = $usersService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        try {
            $usersRequest = new Request($this->argument('page'), 'https://reqres.in/api/users');
            $usersResponse = $usersRequest->call()->getResponse();
            $this->userService->mapAndStore($usersResponse->getData());
        } catch (Exception $e) {
            throw new Exception('Something went wrong!!');
        }

        return 0;
    }
}
