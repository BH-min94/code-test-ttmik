<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ip_table extends Model  {

    use HasFactory;

    protected $table = 'ip_table';

    /* - */
    protected function getCountryCode($ip) {
        return $this->select('code')
            ->whereRaw('INET_ATON(`to_ip`) <= INET_ATON(?) AND INET_ATON(`end_ip`) >= INET_ATON(?)', [$ip, $ip])
            ->first();
    }
}
