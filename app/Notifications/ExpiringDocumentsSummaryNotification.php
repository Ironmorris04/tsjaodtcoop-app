<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiringDocumentsSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $documents;
    protected $recipientEmail;
    protected $isCooperative;
    protected $recipientName;

    /**
     * @param \Illuminate\Support\Collection|array $documents
     * @param string|null $recipientEmail
     * @param bool $isCooperative
     * @param string $recipientName
     */
    public function __construct($documents, ?string $recipientEmail = null, bool $isCooperative = false, string $recipientName = 'Recipient')
    {
        $this->documents = $documents;
        $this->recipientEmail = $recipientEmail;
        $this->isCooperative = $isCooperative;
        $this->recipientName = $recipientName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = new MailMessage;

        $mail->subject($this->isCooperative
            ? 'Expiring Cooperative Documents'
            : 'Your Expiring Documents');

        $mail->markdown('emails.expiring-documents', [
            'documents' => $this->documents,
            'recipientName' => $this->recipientName,
        ]);

        return $mail;
    }
}
