<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\ActivityLog;
use App\Notifications\BirthdayReminder;

class SendBirthdayReminders extends Command
{
    protected $signature = 'contacts:send-birthday-reminders';
    protected $description = 'Send birthday reminders for contacts with birthdays today';

    public function handle()
    {
        $today = now()->format('m-d');
        $contacts = Contact::whereNotNull('birthday')->get()->filter(function ($c) use ($today) {
            return optional($c->birthday) && now()->parse($c->birthday)->format('m-d') === $today;
        });

        foreach ($contacts as $contact) {
            $user = $contact->user;
            if ($user && $user->email) {
                // Send notification (requires mail configured)
                try {
                    $user->notify(new BirthdayReminder($contact));
                } catch (\Exception $e) {
                    // swallow mail errors; still record activity
                }

                ActivityLog::create([
                    'user_id' => $user->id,
                    'subject_type' => Contact::class,
                    'subject_id' => $contact->id,
                    'action' => 'birthday_reminder_sent',
                    'changes' => null,
                ]);
            }
        }

        $this->info('Birthday reminder job completed.');
        return 0;
    }
}
