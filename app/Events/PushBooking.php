<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PushBooking implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $bookings;
    public function __construct($bookings)
    {
        $this->bookings = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'phone' => $booking->customer->phone,
                'customer_name' => $booking->customer->name,
                'customer_email' => $booking->customer->email,
                'service_id' => $booking->id_service,
                'service_name'=>$booking->service->name,
                'service_slug'=>$booking->service->slug,
                'service_discount'=>$booking->service->price,
                'service_price'=>$booking->service->compare_price,
                'time' => $booking->time,
                'end_time' => $booking->end_time,
                'status' => $booking->status,
            ];
        });
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('bookings');
    }
}
