<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function assignIndex(){
        $user = User::all();
        $roles = Role::all();

        // Ambil semua permission dan kelompokkan berdasarkan prefix (modul)
        // Contoh: 'user.create' → 'user' => ['create', 'read', ...]
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0]; // Ambil prefix modul
        })->map(function ($groupedPermissions) {
            // Ambil hanya action-nya, misalnya dari 'user.create' → 'create'
            $actions = $groupedPermissions->map(function ($permission) {
                return explode('.', $permission->name)[1];
            });

            // Tentukan urutan preferensi
            $preferredOrder = ['create', 'read', 'update', 'delete'];

            // Urutkan berdasarkan urutan preferensi
            return collect($preferredOrder)->filter(function ($action) use ($actions) {
                return $actions->contains($action);
            })->values();
        });


        return view('user-assign',[
            'users' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function assignPermission(Request $request, $id_user) {
        $validate = $request->validate([
            'permissions' => 'array',
        ]);

        try {
            // Mencari user berdasarkan ID
            $user = User::findOrFail($id_user);

            // Menyinkronkan permissions dengan user
            $user->syncPermissions($request->permissions);

            return redirect()->back()->with([
                'notifikasi' => 'Permission berhasil ditambahkan ke user',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {  // Menangkap exception
            // Menangani error jika terjadi
            return redirect()->back()->with([
                'notifikasi' => 'Permission gagal ditambahkan ke user: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function assignRoles(Request $request, $id_user) {
        $validate = $request->validate([
            'roles' => 'array',
        ]);

        try {
            // Mencari user berdasarkan ID
            $user = User::findOrFail($id_user);

            // Menyinkronkan role dengan user
            $user->syncRoles($request->roles);

            return redirect()->back()->with([
                'notifikasi' => 'Role berhasil ditambahkan ke user',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {  // Menangkap exception
            // Menangani error jika terjadi
            return redirect()->back()->with([
                'notifikasi' => 'Role gagal ditambahkan ke user: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

}
