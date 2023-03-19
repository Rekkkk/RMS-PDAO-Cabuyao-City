<?php

namespace App\Console;

use DB;
use Carbon\Carbon;
use App\Models\AppointmentLimit;
use App\Models\AppointmentDayDisable;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
    
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            DB::table('appointment')->where('appointment_date', '<' ,date("Y-m-d"))
            ->where('appointment_status', '!=' ,'Done')
            ->update(['appointment_status' => 'Unprocess']);

     
        })->everyMinute();

        $schedule->call(function () {
            DB::table('appointment_limit_months')->where('appointment_month', '<' ,date("Y-m"))->delete();

        })->everyMinute();

        $schedule->call(function () {
            DB::table('appointment_disable_day')->where('date', '<' ,date("Y-m-d"))->delete();

        })->everyMinute();


        $schedule->call(function () {
   
            $dateToday = Carbon::now()->format('m-d-Y');

            $convertDate = Carbon::createFromFormat('m-d-Y', $dateToday)->addMonth();
      
            $newDate = $convertDate->format('Y-m');

            if(Carbon::parse(Carbon::now()->endOfMonth()->toDateString())->day <= Carbon::now()->addDays(14)->format('d')){
    
                $ifMonthExist = DB::table('appointment_limit_months')->where('appointment_month', $newDate)->first();
    
                if(!$ifMonthExist){
                    AppointmentLimit::create([
                        'appointment_month' =>  $newDate,
                        'limits' => 30
                    ]);
                }
            }
        })->everyMinute();

        $schedule->call(function () {

            $year = Carbon::now()->addYear()->format('Y');

            $dates = [
                ['date' => $year . "-01-01"],
                ['date' => $year . "-02-24"],
                ['date' => $year . "-04-06"],
                ['date' => $year . "-04-07"],
                ['date' => $year . "-04-08"],
                ['date' => $year . "-04-09"],
                ['date' => $year . "-04-22"],
                ['date' => $year . "-05-01"],
                ['date' => $year . "-06-12"],
                ['date' => $year . "-06-29"],
                ['date' => $year . "-08-21"],
                ['date' => $year . "-08-28"],
                ['date' => $year . "-11-01"],
                ['date' => $year . "-11-02"],
                ['date' => $year . "-12-08"],
                ['date' => $year . "-12-24"],
                ['date' => $year . "-12-25"],
                ['date' => $year . "-12-30"],
                ['date' => $year . "-12-31"]
            ];

            foreach($dates as $date){
                AppointmentDayDisable::create([
                    'date' => $date['date'],
                ]);
            }
   
        })->yearlyOn(11, 1, '00:00');
        
        
    }

    public function scheduleTimezone(){
        return 'Asia/Hong_Kong';
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
