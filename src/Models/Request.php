<?php

namespace JOOservices\XClient\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JOOservices\XLogger\Models\Interfaces\LoggerEntityInterface;

/**
 * @property string $ip
 * @property array $context
 * @property string $message
 * @property string $level
 * @property string $status
 */
class Request extends Model
{
    use GeneratesUuid;
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'uuid',
        'ip',
        'url',
        'payload',
        'response',
        'status',
        'message'
    ];

    protected $casts = [
        'uuid' => 'string',
        'ip' => 'string',
        'url' => 'string',
        'payload' => 'array',
        'response' => 'array',
        'status' => 'int',
        'message' => 'string'
    ];
}
