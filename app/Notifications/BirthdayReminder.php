<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BirthdayReminder extends Notification
{
    use Queueable;

    protected $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Birthday reminder: ' . $this->contact->full_name)
            ->line('Today is the birthday of ' . $this->contact->full_name)
            ->action('View Contact', route('contacts.show', $this->contact));
    }
}
