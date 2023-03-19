<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Program;
use App\Models\ProgramImage;
use App\Models\Appointment;
use App\Models\AppointmentLimit;
use App\Models\AppointmentDayDisable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $selectedProgram = Program::with('pictures')->get();
        View::share('programs', $selectedProgram);  

        $disableDates = AppointmentDayDisable::all();
        View::share('disableDate', $disableDates);  

        $appointmentDate = Appointment::all();
        View::share('appointmentDate', $appointmentDate);  

        $appointmentLimit = AppointmentLimit::all();
        View::share('appointmentLimit', $appointmentLimit);  
    
        // View::share('key', 'value');
        // Schema::defaultStringLength(191);

/* Sharing the data from the database to the view. */

   

    }
}
