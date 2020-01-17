<?php


namespace App\Controllers;
use App\Models\Job;
use Respect\Validation\Validator as v;

class JobsController extends BaseController
{
    public function getAddJobAction($request) {
        $responseMessage = null;
        //Verify if isn't empty
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $jobValidator = v::key('title', v::stringType()->notEmpty())
                ->key('description', v::stringType()->notEmpty());

            try {
                //Try to validate
                $jobValidator->assert($postData);

                $job = new Job(); //Instance from Job Model
                //Fill data
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                //Save it in database
                $job->save();

                $responseMessage = 'Saved';

            } catch (\Exception $e) {
                $responseMessage = 'Error';
            }

        }

        //Return a render
        return $this->renderHTML('addJob.twig', [
            'message' => $responseMessage
        ]);

    }
}