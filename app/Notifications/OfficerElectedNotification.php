<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Officer;
use App\Models\User;

class OfficerElectedNotification extends Notification
{
    use Queueable;

    public $officer;
    public $officerUser;
    public $isNewElection;

    /**
     * Create a new notification instance.
     */
    public function __construct(Officer $officer, User $officerUser, bool $isNewElection = true)
    {
        $this->officer = $officer;
        $this->officerUser = $officerUser;
        $this->isNewElection = $isNewElection;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $positionName = $this->officer->formatted_position;
        $userId = $this->officerUser->user_id;
        $effectiveFrom = $this->officer->effective_from->format('M d, Y');
        $effectiveTo = $this->officer->effective_to->format('M d, Y');

        $greeting = $this->isNewElection
            ? "Congratulations! You have been elected as {$positionName}"
            : "You have been selected as {$positionName}";

        return (new MailMessage)
            ->subject('Officer Election Notification - TSJAODTC')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($greeting)
            ->line('We are pleased to inform you of your election/selection to the following position:')
            ->line('**Position:** ' . $positionName)
            ->line('**Term:** ' . $effectiveFrom . ' to ' . $effectiveTo)
            ->line('---')
            ->line('**Account Access Information:**')
            ->line('You now have access to a dedicated officer account with extended privileges for your position.')
            ->line('**Your Officer User ID:** ' . $userId)
            ->line('**Password:** Please use the same password as your operator account.')
            ->line('---')
            ->line('**How to Login:**')
            ->line('• Login to the system using your **Officer User ID**: **' . $userId . '**')
            ->line('• Use your **existing operator password** (same password for both accounts)')
            ->line('• You do NOT need to use email to login - use the User ID above')
            ->line('---')
            ->line('**Important Notes:**')
            ->line('• You now have **two user accounts** to access the system:')
            ->line('  1. Your **Operator Account** (your original User ID) - for operator-related functions')
            ->line('  2. Your **Officer Account** (' . $userId . ') - for ' . strtolower($positionName) . ' duties')
            ->line('• Both accounts use the **same password** for your convenience')
            ->line('• Your officer account provides access to additional features based on your position')
            ->action('Login to Dashboard', url('/dashboard'))
            ->line('Thank you for your service to TSJAODTC!')
            ->line('If you have any questions or need assistance, please contact the administrator.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'officer_id' => $this->officer->id,
            'position' => $this->officer->position,
            'officer_user_id' => $this->officerUser->user_id,
            'effective_from' => $this->officer->effective_from->format('Y-m-d'),
            'effective_to' => $this->officer->effective_to->format('Y-m-d'),
            'is_new_election' => $this->isNewElection,
        ];
    }
}
