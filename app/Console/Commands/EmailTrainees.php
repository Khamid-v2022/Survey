<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\SurveyMail;

use App\Models\User;
use App\Models\User_Form;

use App\Notifications\NotifyTrainee;
use Carbon\Carbon;

class EmailTrainees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:trainees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to unresponsive Trainees';


    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = Carbon::now()->subDay(env('MAIL_REMINDE_DAY'));

        $unresponsive_trainees = User_Form::where('emailled_at', '<', $limit)->where('emailled_times', '<', env('MAIL_REMINDE_TIMES'))->get();
        
        $email_success_flag = true;
        foreach($unresponsive_trainees as $item){
        
            // send Email
            $user_info = User::where('id', $item['user_id'])->first();
            $survey_url = url("/survey/{$item['unique_str']}");
            // $survey_url = route('survey', ['unique_str' => $item['unique_str']]);
            
            $details = [
                'title' => 'Coachingsupport Herinnering',
                'body' => 'Beste ' . $user_info['first_name'] . '<br/><br/>' . 'Je hebt de enquete nog niet ingevuld, gelieve dat via onderstaande link te doen:<br/>' 
                            . '<a href="' . $survey_url . '" target="_black">' . $survey_url . '</a>'
            ];
            
            try {
                Mail::to($user_info['email'])->send(new SurveyMail($details));
                
                // update emilled field
                $user_form = User_Form::where('id', $item['id'])->first();
                $user_form->emailled_at =  Carbon::now();
                $user_form->emailled_times++;
                $user_form->save();

            } catch (Exception $e) {
                if (count(Mail::failures()) > 0) {
                    $email_success_flag = false;
                }
            }

            // $item->notify(new NotifyTrainee());
        }

        return $email_success_flag;
    }
}
