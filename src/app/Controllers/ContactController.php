<?php
namespace App\Controllers;

use App\Models\Message;
use Laminas\Diactoros\Response\RedirectResponse;

class ContactController extends BaseController {
    public function indexAction() {
        return $this->renderHTML('contact/index.twig');
    }

    public function sendAction($request) {
        $postData = $request->getParsedBody();

        $message = new Message();
        $message->name = $postData['name'];
        $message->mail = $postData['mail'];
        $message->message = $postData['message'];
        $message->email_sent = false;
        $message->save();

        return new RedirectResponse('/contact');
    }
}