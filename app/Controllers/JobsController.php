<?php


namespace App\Controllers;
use App\Models\Job;

class JobsController
{
    public function getAddJobAction() {
        //Verify if isn't empty
        if (!empty($_POST)) {
            $job = new Job(); //Instance from Job Model
            //Fill data
            $job->title = $_POST['title'];
            $job->description = $_POST['description'];
            //Save it in database
            $job->save();
        }

        require_once '../view/addJob.php';

    }
}