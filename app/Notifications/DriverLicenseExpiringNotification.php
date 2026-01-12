<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Driver;
use Carbon\Carbon;

class DriverLicenseExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $driver;
    protected $daysUntilExpiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
        $expiryDate = Carbon::parse($driver->license_expiry)->startOfDay();
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
        $expiryDate = Carbon::parse($this->driver->license_expiry)->format('F d, Y');

        $daysMessage = $this->daysUntilExpiry > 0
            ? 'Expires in ' . $this->daysUntilExpiry . ' days'
            : 'Expired ' . abs($this->daysUntilExpiry) . ' days ago';

        return (new MailMessage)
            ->subject('Driver License Expiration Notice - ' . $this->driver->first_name . ' ' . $this->driver->last_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that a driver\'s license under your management is expiring soon.')
            ->line('**Driver Name:** ' . $this->driver->first_name . ' ' . $this->driver->last_name)
            ->line('**License Number:** ' . $this->driver->license_number)
            ->line('**License Type:** ' . $this->driver->license_type)
            ->line('**Expiry Date:** ' . $expiryDate)
            ->line('**Status:** ' . $daysMessage)
            ->line('Please ensure the driver renews their license before it expires and an updated copy is uploaded to the system to maintain compliance.')
            ->line('To view driver details, please log in to your dashboard.')
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
            'driver_id' => $this->driver->id,
            'driver_name' => $this->driver->first_name . ' ' . $this->driver->last_name,
            'license_number' => $this->driver->license_number,
            'license_expiry' => $this->driver->license_expiry,
            'days_until_expiry' => $this->daysUntilExpiry,
        ];
    }
}
