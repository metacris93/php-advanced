<?php
namespace App\Controllers;

use App\Models\Job;
use App\Services\JobService;
use Respect\Validation\Validator as v;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;

class JobsController extends BaseController {
  /**
   * @var App\Services\JobService
  */
  private $jobService;

  public function __construct(JobService $jobService)
  {
      parent::__construct();
      $this->jobService = $jobService;
  }

  public function indexAction() {
      $jobs = Job::all();
      return $this->renderHTML('jobs/index.twig', compact('jobs'));
  }

    public function getAddJobAction(ServerRequest $request) {
      $responseMessage = null;
      $fullPath = '';
      $path = '';
      if ($request->getMethod() == 'POST') {
          $postData = $request->getParsedBody();
          $jobValidator = v::key('title', v::stringType()->notEmpty())
            ->key('description', v::stringType()->notEmpty());
          try {
            $jobValidator->assert($postData);
            $postData = $request->getParsedBody();
            $files = $request->getUploadedFiles();
            $logo = $files['logo'];
            $uuid = guidv4();
            if($logo->getError() == UPLOAD_ERR_OK) {
              $ext = get_last_substring('.', $logo->getClientFilename());
              $path = "photos/$uuid.$ext";
              $fullPath = storage_path($path);
              $logo->moveTo($fullPath);
            }
            $job = new Job();
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            $job->image = $path;
            $job->uuid = $uuid;
            $job->months = $postData['months'];
            $job->save();
            $responseMessage = 'Saved';
          } catch (\Exception $e) {
            $responseMessage = $e->getMessage();
          }
      }
      return $this->renderHTML('addJob.twig', [
          'responseMessage' =>$responseMessage
      ]);
    }

    public function deleteAction(ServerRequest $request) {
        $this->jobService->deleteJob($request->getAttribute('id'));
        return new RedirectResponse('/jobs');
    }
}