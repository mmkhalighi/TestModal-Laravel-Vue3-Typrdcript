<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('User/Settings');
    }

    public function updatePassword(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
        ], [
            'current_password.current_password' =>
                'رمز عبور فعلی صحیح نیست.',
            'password.min' =>
                'رمز عبور جدید باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' =>
                'تکرار رمز عبور با رمز جدید مطابقت ندارد.',
            'password.different' =>
                'رمز عبور جدید باید با رمز فعلی متفاوت باشد.',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with(
            'success',
            'رمز عبور شما با موفقیت تغییر کرد.'
        );
    }
}
