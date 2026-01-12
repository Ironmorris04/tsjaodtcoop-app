<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DocumentRenewal;

class DocumentRenewalRejected extends Notification
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
        $rejectionReason = $this->renewal->rejection_reason;
        $requestedExpiry = $this->renewal->new_expiry_date->format('M d, Y');

        return (new MailMessage)
            ->subject('Document Renewal Rejected')
            ->error()
            ->greeting('Hello ' . $notifiable->full_name . ',')
            ->line('Unfortunately, your document renewal request has been rejected.')
            ->line('**Document Type:** ' . $documentType)
            ->line('**Entity:** ' . $entityName)
            ->line('**Requested Expiry Date:** ' . $requestedExpiry)
            ->line('**Reason for Rejection:** ' . $rejectionReason)
            ->action('View Dashboard', url('/dashboard'))
            ->line('Please review the rejection reason and submit a new renewal request if needed.')
            ->line('If you have any questions, please contact the administrator.');
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
            'requested_expiry_date' => $this->renewal->new_expiry_date->format('Y-m-d'),
            'rejection_reason' => $this->renewal->rejection_reason,
        ];
    }
}
