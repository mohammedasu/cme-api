<?php

namespace App\Services;

use App;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\CmeRepository;
use App\Exceptions\CustomErrorException;
use App\Helpers\EngagementHelper;

class CmeService
{
    public function __construct()
    {
        $this->repository           = new CmeRepository();
        $this->memberService        = new MemberService();
        $this->flyyService          = new FlyyService();
        $this->memberPointService   = new MemberPointService();
    }

    /**
     * Function to fetch all cme list
    */

    public function getAll($request)
    {
        Log::info('CmeService | getAll', $request->all());
        try {
            $where = $this->getWhere($request);
            return $this->repository->findByMultipleFields($where,true, $request);
        } catch (Exception $e){
            throw new CustomErrorException($e);
        }
    }
    
    /**
     * Function to store CME answer
    */

    public function store($request)
    {
        Log::info('CmeService | store', $request->all());
        try {
            DB::beginTransaction();
            $memberDetails = $this->memberService->findByMultipleFields($request);
            $cmeDetails = $this->repository->findByMultipleFields(['id' => $request->cme_id],false,$request);
            $memberHistory = $this->repository->findHistoryByMultipleFields(['cme_id' => $request->cme_id, 'member_id' => $request->member_id]);
            $this->repository->createOrUpdateHistory($this->calculateHistory($request, $cmeDetails));
            $this->memberPointService->createOrUpdate($cmeDetails,$request->member_id);
            if ($cmeDetails->type == 'survey' && $cmeDetails->cmeMap->map_type && $cmeDetails->cmeMap->map_type == 'dgmr_project' && $memberDetails->member_ref_no) {
                // for telecaller Engagement to user complete survey
                EngagementHelper::updateHistory($memberDetails->member_ref_no, 'survey', $request->cme_id);
                if($memberDetails->is_prime && is_null($memberHistory)){
                    $this->flyyService->sendEventToFlyy($memberDetails->member_ref_no, 'survey');
                }
            }
            $cmeDetails->forum_url = null;
            if($cmeDetails->cmeMap AND $cmeDetails->cmeMap->map_type == 'forum' AND $cmeDetails->cmeMap->forum){
                $cmeDetails->forum_url = '/forums/'.$cmeDetails->cmeMap->forum->link_name ;
            }
            DB::commit();
            return $cmeDetails;
        }
        catch (Exception $e){
            DB::rollBack();
            throw new CustomErrorException($e);
        }
    }

    /**
     * Function to calculate answer and create history request
    */
    public function calculateHistory($request, $cmeDetails)
    {
        $correctAnswer = 0;
        $totalQuestions = count($request->answers);
        foreach ($request->answers as $answer) {
            foreach ($cmeDetails->questions as $value) {
                if(($answer['question_id'] == $value->questionBank->id)){
                    $cmeHistoryDetails[] = [
                        'question_bank_id' => $answer['question_id'],
                        'correct_answer'   => $value->questionBank->correct_option,
                        'member_answer'    => $answer['answer'],
                    ];
                    if((strcasecmp($answer['answer'], $value->questionBank->correct_option)==0)){
                        $correctAnswer++;
                    }
                }
            }
        }

        $data['cmeHistoryDetails'] = $cmeHistoryDetails;
        $earnPercent = round(($correctAnswer/$totalQuestions)*100,2);
        $isPass = $earnPercent >= $cmeDetails->passing_criteria ? 1 : 0;

        $data['cmeHistory'] = [
            "member_id"         => $request->member_id,
            "cme_id"            => $request->cme_id,
            "type"              => $request->type,
            "type_id"           => $request->type_id,
            "result_in_percent" => $earnPercent ?? 0,
            "earned_points"     => $cmeDetails->points ?? 0,
            "earned_coins"      => $cmeDetails->coins ?? 0,
            "earned_coins_type" => $cmeDetails->coins_type,
            "is_passed"         => $isPass,
            "total_questions"   => $totalQuestions,
            "correct_answers"   => $correctAnswer,
        ];

        return $data;
    }

    /**
     * Function to fetch CME details
    */

    public function getDetails($request)
    {
        Log::info('CmeService | getDetails', $request->all());
        try {
            $where = $this->getWhere($request);
            $cmeDetails = $this->repository->findByMultipleFields($where,false, $request);
            if($cmeDetails){
                $cmeDetails->forum_url = null;
                if($cmeDetails->cmeMap AND $cmeDetails->cmeMap->map_type == 'forum' AND $cmeDetails->cmeMap->forum){
                    $cmeDetails->forum_url = '/forums/'.$cmeDetails->cmeMap->forum->link_name ;
                }
            }
            return $cmeDetails;
        } catch (Exception $e){
            throw new CustomErrorException($e);
        }
    }

    /**
     * Function to create where clause for cme table
    */

    public function getWhere($request)
    {
        $where = [];
        if($request->has('survey_url')) {
            $where[] = ['survey_url','=',$request->survey_url];
        }
        if($request->has('cme_id')) {
            $where[] = ['id','=',$request->cme_id];
        }
        if($request->has('type')) {
            $where[] = ['type','=',$request->type];
        }
        if($request->has('name')) {
            $where[] = ['name','like','%'. $request->name.'%'];
        }
        return $where;
    }
}
