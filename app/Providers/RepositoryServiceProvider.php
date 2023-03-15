<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\HotelRoom\HotelRoomRepository;
use App\Repositories\HotelRoom\HotelRoomTypeRepository;
use App\Repositories\BookingOrder\BookingOrderRepository;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;
use App\Repositories\HotelRoom\HotelRoomTypeRepositoryInterface;
use App\Repositories\BookingOrder\BookingOrderRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        AuthRepositoryInterface::class => AuthRepository::class,
        HotelRoomRepositoryInterface::class => HotelRoomRepository::class,
        HotelRoomTypeRepositoryInterface::class => HotelRoomTypeRepository::class,
        BookingOrderRepositoryInterface::class => BookingOrderRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
