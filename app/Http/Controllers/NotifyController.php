<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationEmail;
use Illuminate\Support\Facades\Http;

class NotifyController extends Controller
{
    public function store(Request $request)
    {
        if (!empty($request->website)) {
            abort(403, 'Access Denied.');
        }
        // Verify Google reCAPTCHA v3
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = env('GOOGLE_RECAPTCHA_V3_SECRET');
        $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);
        \Log::info('reCAPTCHA response', ['response' => $recaptcha->json()]);
        if (!$recaptcha->json('success') || $recaptcha->json('score') < 0.7) {
            return redirect()->back()->with('error', 'reCAPTCHA verification failed. Please try again.')->withInput();
        }
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:api_subscribers,email',
            ]);

            NotificationEmail::create([
                'email' => $validated['email'],
            ]);

            return redirect()->back()->with('success', 'Thank you! You will be notified.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('email')) {
                $failedRules = $e->validator->failed();
                if (isset($failedRules['email']['Unique'])) {
                    return redirect()->back()->with('error', 'You have already been subscribed.')->withInput();
                }
            }
            throw $e;
        }
    }
}
