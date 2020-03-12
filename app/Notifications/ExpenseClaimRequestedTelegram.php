<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use App\ExpenseClaim;
use Carbon\Carbon;

class ExpenseClaimRequestedTelegram extends Notification
{
    use Queueable;

    public $expenseClaim;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ExpenseClaim $expenseClaim)
    {
        $this->expenseClaim = $expenseClaim;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toTelegram($transaction)
    {
        $url = route('expense-claims.show', $this->expenseClaim->id);
        $msg = "New Expense Claim submitted by: ".$this->expenseClaim->user->name."\n";
        $msg .= "For Period: ".Carbon::parse($this->expenseClaim->start_date)->format('j M, Y')." to "
        .Carbon::parse($this->expenseClaim->end_date)->format('j M, Y')."\n";

        return TelegramMessage::create()
            ->to('-1001091789483')
            ->content($msg)
            ->button('View Request', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
