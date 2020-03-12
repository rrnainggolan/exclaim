<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\ExpenseClaim;

class ExpenseClaimApproved extends Notification
{
    use Queueable;

    public $expenseClaim;
    public $target;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ExpenseClaim $expenseClaim, $target)
    {
        $this->expenseClaim = $expenseClaim;
        $this->target = $target;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
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
        if($this->target == 'user') {
            $message->subject('Your expense claim has been approved')
            ->greeting('Hello')
            ->line('Your expense claim has been approved.')
            ->action('Click here to view the request', route('expense-claims.show', $this->expenseClaim->id))
            ->line('Thank you for using our application!');
        } else {
            $expenseClaimCode = 'EXP-'.str_pad($this->expenseClaim->id, 6, '0', STR_PAD_LEFT);

            $message->subject('Expense claim has been approved')
            ->greeting('Hello')
            ->line('Expense Claim with code: '.$expenseClaimCode.' has been approved.')
            ->line('This request still needs 1 more approval to complete.')
            ->action('Click here to view the request', route('expense-claims.show', $this->expenseClaim->id))
            ->line('Thank you for using our application!');
        }
        
        return $message;
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
