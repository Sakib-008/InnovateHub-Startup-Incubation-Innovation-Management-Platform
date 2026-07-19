<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->oldest();
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Get the other participant from the current user's perspective
    public function otherUser(): User
    {
        return auth()->id() === $this->user_one_id
            ? $this->userTwo
            : $this->userOne;
    }

    public function unreadCount(): int
    {
        return $this->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    // Find or create a conversation between two users (always store lower ID first ro prevent duplicate)
    public static function between(int $userA, int $userB): self
    {
        [$one, $two] = $userA < $userB ? [$userA, $userB] : [$userB, $userA];

        return self::firstOrCreate([
            'user_one_id' => $one,
            'user_two_id' => $two,
        ]);
    }
}