<?php

namespace App\Services;

use App\Models\Enquiry;
use Illuminate\Support\Facades\Mail;

class EnquiryNotifier
{
    public function notify(Enquiry $enquiry): void
    {
        $to = env('ADMIN_EMAIL', config('mail.from.address'));
        if (!$to) {
            return; // no recipient configured; noop in dev
        }
        $subject = 'New Enquiry from '.$enquiry->name;
        $body = "Name: {$enquiry->name}\nEmail: {$enquiry->email}\nPhone: {$enquiry->phone}\nMessage: {$enquiry->message}\nSource: {$enquiry->source_page}";
        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}

