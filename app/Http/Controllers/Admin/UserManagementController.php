<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * عرض جميع الطلاب والسائقين
     */
   public function index(): View
{
    $users = User::whereHas('role', function ($q) {
        $q->whereIn('name', ['student', 'driver']);
    })->get();

    $roles = Role::whereIn('name', ['student', 'driver'])->get();

    return view('admin.users.index', compact('users', 'roles'));
}


    /**
     * صفحة تعديل المستخدم
     */
    public function edit(User $user): View
    {
        $roles = Role::whereIn('name', ['student', 'driver'])->get();

        return view('admin.users.edit', compact('user', 'roles'));
         //في حال وجود صفحه لتعديل المستخدمين عدل الاسم فقط
    }

    /**
     * تحديث بيانات المستخدم (طالب / سائق)
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'role_id'  => ['required', 'exists:roles,id'],
            'status'   => ['required', 'in:pending,approved,rejected'],
        ]);

        $user->update([
            'username' => $request->username,
            'role_id'  => $request->role_id,
            'status'   => $request->status,
        ]);

        return redirect()->back()->with('success', 'تم تحديث المستخدم بنجاح');
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('success', 'تم حذف المستخدم');
    }
    public function create(): View
{
    return view('admin.users.create');
    // لو اسم الصفحة مختلف عدله
}


public function store(Request $request): RedirectResponse
{
    $request->validate([
        'username' => ['required', 'string', 'max:255', 'unique:users,username'],
        'password' => ['required', 'min:8'],
        'role_id'  => ['required', 'exists:roles,id'],
        'status'   => ['required', 'in:pending,approved,rejected'],
    ]);

    User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role_id'  => $request->role_id,
        'status'   => $request->status,
    ]);

    return redirect()->back()->with('success', 'تمت الإضافة بنجاح');
}



}