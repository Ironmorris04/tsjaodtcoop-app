<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Unit;
use Carbon\Carbon;

class UnitRegistrationExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $unit;
    protected $daysUntilExpiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
        $expiryDate = Carbon::parse($unit->registration_expiry)->startOfDay();
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
        $expiryDate = Carbon::parse($this->unit->registration_expiry)->format('F d, Y');

        $daysMessage = $this->daysUntilExpiry > 0
            ? 'Expires in ' . $this->daysUntilExpiry . ' days'
            : 'Expired ' . abs($this->daysUntilExpiry) . ' days ago';

        return (new MailMessage)
            ->subject('Unit Registration Expiration Notice - ' . $this->unit->plate_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that a unit registration under your management is expiring soon.')
            ->line('**Plate Number:** ' . $this->unit->plate_number)
            ->line('**Unit Type:** ' . $this->unit->unit_type)
            ->line('**Make/Model:** ' . $this->unit->make_model)
            ->line('**Registration Expiry Date:** ' . $expiryDate)
            ->line('**Status:** ' . $daysMessage)
            ->line('Please ensure the unit registration is renewed before it expires to maintain compliance.')
            ->line('To view unit details, please log in to your dashboard.')
            ->line('Thank you for keeping your records up to date.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'unit_id' => $this->unit->id,
            'plate_number' => $this->unit->plate_number,
            'unit_type' => $this->unit->unit_type,
            'registration_expiry' => $this->unit->registration_expiry,
            'days_until_expiry' => $this->daysUntilExpiry,
        ];
    }
}
