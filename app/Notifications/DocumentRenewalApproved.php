<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DocumentRenewal;

class DocumentRenewalApproved extends Notification
{
    use Queueable;

    public $renewal;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocumentRenewal $renewal)
    {
        $this->renewal = $renewal;
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
        $documentType = $this->renewal->formatted_type;
        $entityName = $this->renewal->entity_name;
        $newExpiry = $this->renewal->new_expiry_date->format('M d, Y');

        return (new MailMessage)
            ->subject('Document Renewal Approved')
            ->greeting('Hello ' . $notifiable->full_name . ',')
            ->line('Your document renewal request has been approved!')
            ->line('**Document Type:** ' . $documentType)
            ->line('**Entity:** ' . $entityName)
            ->line('**New Expiry Date:** ' . $newExpiry)
            ->action('View Dashboard', url('/dashboard'))
            ->line('The new expiry date has been applied to your record.')
            ->line('Thank you for keeping your documents up to date!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'renewal_id' => $this->renewal->id,
            'document_type' => $this->renewal->document_type,
            'entity_name' => $this->renewal->entity_name,
            'new_expiry_date' => $this->renewal->new_expiry_date->format('Y-m-d'),
        ];
    }
}
