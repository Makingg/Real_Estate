<?php

namespace App\Notifications;

use App\Models\Offer;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OfferRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Property $property, public Offer $selectedOffer)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Offer not selected',
            'message' => sprintf(
                'Another offer has been accepted for %s at %s. Your offer was not selected.',
                $this->property->title,
                $this->property->address,
            ),
            'property_id' => $this->property->id,
            'selected_offer_id' => $this->selectedOffer->id,
            'selected_offer_amount' => (string) $this->selectedOffer->offer_amount,
            'status' => 'rejected',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
