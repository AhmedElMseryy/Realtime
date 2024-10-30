<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserRegisteredEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    // public $myUser;

    /**
     * Create a new event instance.
     */
    public function __construct($user)
    {
        $this->message = "New user registered from event called $user->name";
        // $this->myUser = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('new_user_channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new_user_registered_custom_name';
    }


    // public function broadcastWith(): array
    // {
    //     return ['email' => $this->myUser->email];
    // }


    // public function broadcastWhen(): bool
    // {
    //     return $this->myUser->name == 'Ahmed';
    // }
}
