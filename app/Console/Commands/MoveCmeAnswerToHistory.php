<?php

namespace App\Console\Commands;

use App\Models\Cme;
use App\Repositories\CmeRepository;
use App\Models\CmeAnswer;
use Illuminate\Console\Command;

class MoveCmeAnswerToHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:cme_answer_into_history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moving cme answers table data into CME histories and CME history details';

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
        $members = CmeAnswer::groupBy('member_id')->get();
        if(count($members)>0){
            $cmeRepository = new CmeRepository();
            $cmes = Cme::get();
            foreach ($members as $member) {
                foreach ($cmes as $cme) {
                    $cmeAnswers = CmeAnswer::where(['cme_id'=>$cme->id,'member_id'=>$member->member_id])->get();
                    if(count($cmeAnswers)>0){
                        $history = $this->calculateHistory($cmeAnswers, $cme);
                        if(!empty($history['cmeHistory']) AND !empty($history['cmeHistoryDetails'])){
                            $cmeRepository->createOrUpdateHistory($history);
                        }
                    }
                }
            }
            echo "Data moved successfully";
        }
    }

    public function calculateHistory($cmeAnswers, $cme)
    {
        $cmeHistoryDetails = [];
        $data = [];
        $correctAnswer = 0;
        $memberId = null;
        $attemptNumber = 1;
        $totalQuestions = count($cmeAnswers);
        if($totalQuestions>0){
            foreach ($cmeAnswers as $answer) {
                $cmeHistoryDetails[] = [
                    'question_bank_id'  => $answer->question_id,
                    'correct_answer'    => $answer->questionBank->correct_option,
                    'member_answer'     => $answer->answer,
                    'created_at'        => $answer->created_at,
                ];
                if((strcasecmp($answer->answer, $answer->questionBank->correct_option)==0)){
                    $correctAnswer++;
                }
                $memberId = $answer->member_id;
                $attemptNumber = $answer->attempt_number;
            }

            $data['cmeHistoryDetails'] = $cmeHistoryDetails;
            $earnPercent = round(($correctAnswer/$totalQuestions)*100,2);
            $isPass = $earnPercent >= $cme->passing_criteria ? 1 : 0;
            $map_type = null ;
            $map_type_id = null ;
            if($cme->cmeMap){
                $map_type = $cme->cmeMap->map_type;
                $map_type_id = $cme->cmeMap->map_type_id;
            }
            $data['cmeHistory'] = [
                "member_id"         => $memberId,
                "cme_id"            => $cme->id,
                "type"              => $map_type,
                "type_id"           => $map_type_id,
                "result_in_percent" => $earnPercent ?? 0,
                "earned_points"     => $cme->points ?? 0,
                "earned_coins"      => $cme->coins ?? 0,
                "earned_coins_type" => $cme->coins_type,
                "is_passed"         => $isPass,
                "total_questions"   => $totalQuestions,
                "correct_answers"   => $correctAnswer,
                "attempt_number"    => $cmeAnswers[0]->attempt_number,
                "created_at"        => $cmeAnswers[0]->created_at,
            ];
        }
        return $data;
    }
}
