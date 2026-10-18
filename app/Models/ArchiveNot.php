<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArchiveNot extends Model
{
    use HasFactory;
    protected $table    = 'archive_nots';
    protected $fillable = ['id', 'sender', 'type_id', 'title', 'reciver', 'files'];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'reciver');
    }

    public function allRead()
    {
        $notificationIds = DB::table('notifications')
            ->where('data->archiveNot_id', $this->id)
            ->pluck('id');

        $allRead = DB::table('notifications')
            ->whereIn('id', $notificationIds)
            ->whereNull('read_at')
            ->count() == 0;

        return $allRead;
    }

    public function unreadRecipients()
    {
        $notificationIds = DB::table('notifications')
            ->where('data->archiveNot_id', $this->id)
            ->pluck('id');

        $unreadRecipientIds = DB::table('notifications')
            ->whereIn('id', $notificationIds)
            ->whereNull('read_at')
            ->pluck('notifiable_id');

        return User::whereIn('id', $unreadRecipientIds)->pluck('name')->toArray();
    }
}