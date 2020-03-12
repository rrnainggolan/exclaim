<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\ExpenseClaim;
use Carbon\Carbon;

class ExpenseClaimRequested extends Notification
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
        //return ['mail', TelegramChannel::class];
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage;
        $message->subject('New Expense Claim Requested')
            ->greeting('Hello!')
            ->line('New Expense Claim submitted by: ' . $this->expenseClaim->user->name)
            ->line(
                'For Period: '.Carbon::parse($this->expenseClaim->start_date)->format('j M, Y').' to '
                .Carbon::parse($this->expenseClaim->end_date)->format('j M, Y')
            )
            ->action('Click here to view the request', route('expense-claims.show', $this->expenseClaim->id));

        return $message;
    }

    // public function toTelegram($transaction)
    // {
    //     $msg = "Reprint executed for:\n";
    //     $msg .= "Thank you!";

    //     return TelegramMessage::create()
    //         ->to('-1001091789483')
    //         ->content($msg);
    // }

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
