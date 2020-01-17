<?php


namespace App\Controllers;
use App\Models\Job;
use Respect\Validation as v;

class JobsController extends BaseController
{
    public function getAddJobAction($request) {
        //Verify if isn't empty
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $jobValidator = v::key('title', v::stringType()->notEmpty())
                ->key('description', v::stringType()->notEmpty());

            var_dump($jobValidator->validate($postData));

            /*$job = new Job(); //Instance from Job Model
            //Fill data
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            //Save it in database
            $job->save();*/
        }

        //Return a render
        return $this->renderHTML('addJob.twig');

    }
}