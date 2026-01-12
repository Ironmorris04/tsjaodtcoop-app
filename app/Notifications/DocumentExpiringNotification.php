<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Document;
use Carbon\Carbon;

class DocumentExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $daysUntilExpiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
        $expiryDate = Carbon::parse($document->expiry_date)->startOfDay();
        $now = Carbon::now()->startOfDay();

        $this->daysUntilExpiry = (int) $now->diffInDays($expiryDate, false);
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
        $expiryDate = Carbon::parse($this->document->expiry_date)->format('F d, Y');

        $daysMessage = $this->daysUntilExpiry > 0
            ? 'Expires in ' . $this->daysUntilExpiry . ' days'
            : 'Expired ' . abs($this->daysUntilExpiry) . ' days ago';

        return (new MailMessage)
            ->subject('Document Expiration Notice - ' . $this->document->document_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that your document is expiring soon.')
            ->line('**Document Type:** ' . $this->document->document_type)
            ->line('**Document Name:** ' . $this->document->document_name)
            ->line('**Document Number:** ' . ($this->document->document_number ?? 'N/A'))
            ->line('**Expiry Date:** ' . $expiryDate)
            ->line('**Status:** ' . $daysMessage)
            ->line('Please take action to renew this document before it expires to avoid any disruptions.')
            ->line('To view details, please log in to your dashboard.')
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type,
            'document_name' => $this->document->document_name,
            'expiry_date' => $this->document->expiry_date,
            'days_until_expiry' => $this->daysUntilExpiry,
        ];
    }
}
