<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdministratorSeeder extends Seeder
{
    public function run()
    {
        $password = config('app.env') === 'production' ? 'BronzeBan8ana' : 'password';
        $member = new Member([
            'currency_id' => 4,
            'sovereign_state_id' => 237,
            'date_of_birth' => '1980-01-01',
            'email' => 'admin@'.config('app.domain'),
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10),
            'api_token' => Str::random(80),
        ]);
        $member->save();

        $administrator = new Administrator;
        $administrator->member_id = $member->id;
        $administrator->save();
    }
}
