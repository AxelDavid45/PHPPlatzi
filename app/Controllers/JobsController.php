<?php


namespace App\Controllers;
use App\Models\Job;

class JobsController
{
    public function getAddJobAction($request) {
        //Verify if isn't empty
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $job = new Job(); //Instance from Job Model
            //Fill data
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            //Save it in database
            $job->save();
        }

        require_once '../view/addJob.php';

    }
}