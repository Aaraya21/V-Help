<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'department' => ['nullable', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\s+]{9,20}$/'],
        ];

        // รหัสนักศึกษา: นักศึกษากรอกเองได้เฉพาะตอนที่ยังไม่มี (เมื่อบันทึกแล้วแก้ไม่ได้)
        if ($user->role === 'student' && empty($user->student_code)) {
            $rules['student_code'] = ['nullable', 'string', 'max:20', Rule::unique(User::class, 'student_code')->ignore($user->id)];
        }

        $data = $request->validate($rules, [
            'name.required'       => 'กรุณากรอกชื่อ-นามสกุล',
            'email.required'      => 'กรุณากรอกอีเมล',
            'email.unique'        => 'อีเมลนี้ถูกใช้งานแล้ว',
            'phone.regex'         => 'รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง',
            'student_code.unique' => 'รหัสนักศึกษานี้ถูกใช้งานแล้ว',
        ]);

        $user->fill($data)->save();

        return Redirect::route('profile.edit')->with('success', 'บันทึกข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
