<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\User;

class CheckDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa a que usuarios ya se les vencio su codigo, de acuerdo a la fecha actual';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hoy = Carbon::now()->format('Y-m-d');

        $users = User::where('estado', 'activo')->get();
        $users->map(function($user) use($hoy){
            $final = new Carbon($user->acceso->final);
            if($hoy > $final->format('Y-m-d')){
                $user->update([
                    'estado' => 'vencido'
                ]);
            }
        });
        return 0;
    }
}
