<?php

namespace InvitationCodes;

use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class InvitationCodeController extends Controller
{
    public function generate()
    {
        if (request()->isMethod('post') && request('amount')) {
            $this->generateInvitationCodes(request('amount'));
        }

        return view('InvitationCodes::generate', [
            'available' => DB::table('invitation_codes')->where('used_by', 0)->get(),
            'used' => DB::table('invitation_codes')->where('used_by', '<>', 0)->get()
        ]);
    }

    protected function generateInvitationCodes($amount)
    {
        $codes = [];

        for ($i = 0; $i < $amount; $i++) {
            $codes[] = [
                'code' => md5(time().rand()),
                'generated_at' => Carbon::now(),
            ];
        }

        DB::table('invitation_codes')->insert($codes);
    }
}
