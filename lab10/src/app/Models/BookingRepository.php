<?php
namespace App\Models;

final class BookingRepository extends PgRepository
{
    protected string $table = 'bookings';
}