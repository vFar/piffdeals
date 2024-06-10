<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EncryptExistingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypt:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt existing user data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $users = User::all();

            foreach ($users as $user) {
                try {
                    $user->email = Crypt::encryptString($user->email);
                    $user->name = Crypt::encryptString($user->name);
                    $user->status = Crypt::encryptString($user->status);
                    $user->save();
                    $this->info("Encrypted user ID: {$user->id}");
                } catch (\Exception $e) {
                    $this->error("Encryption error for user ID: {$user->id}, message: {$e->getMessage()}");
                }
            }
        });

        $this->info('All users have been encrypted successfully.');
    }
}
