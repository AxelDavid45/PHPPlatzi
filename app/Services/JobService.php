<?php

namespace App\Services;


use App\Models\Job;

class JobService
{
    public function deleteJob($id) {
        //Find the job with id x
        $job = Job::find($id);
        if(!$job) {
            throw new \Exception();
        }
        //Delete physically from DB
        $job->delete();
    }
}