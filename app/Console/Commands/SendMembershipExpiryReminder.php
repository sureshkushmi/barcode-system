<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\MembershipExpiryReminderMail;

class SendMembershipExpiryReminder extends Command
{
    protected $signature = 'send:membership-expiry-reminder';
    protected $description = 'Send membership expiry reminders to users 1 month before expiry date';

    public function handle()
    {
        // Get users whose membership will expire in 1 month
        $users = User::whereNotNull('expiry_date')
                    ->whereDate('expiry_date', '=', now()->addMonth()->toDateString())
                    ->get();

        foreach ($users as $user) {
            // Send expiry reminder email
            Mail::to($user->email)->send(new MembershipExpiryReminderMail($user));
            $this->info('Expiry reminder sent to: ' . $user->email);
        }
    }

    protected function schedule(Schedule $schedule)
{
    $schedule->command('send:membership-expiry-reminder')->daily(); // Runs daily
}
}

