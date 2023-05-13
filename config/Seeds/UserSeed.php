<?php

use Cake\Auth\DefaultPasswordHasher;
use Migrations\AbstractSeed;

/**
 * User seed.
 */
class UserSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => (new DefaultPasswordHasher())->hash('password')
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
