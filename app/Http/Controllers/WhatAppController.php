<?php

namespace App\Http\Controllers;

use App\CommandHandler;
use App\Service\UserService;
use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;

class WhatAppController extends Controller
{
    public function __invoke(Request $request, UserService $userService, CommandHandler $commandHandler,): string
    {
        // Check we have the info we need posted to us
        abort_unless($request->has('From') && $request->has('Body'), 403, 'error');

        $user = $userService->getUserById($request->post('From'));
        $commandResponse = $commandHandler->handle($user, $request->post('Body'));
        $messagingResponse = new MessagingResponse();
        $messagingResponse->message($commandResponse);
        return (string)$messagingResponse;
    }
}
