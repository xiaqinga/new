<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $fields = [
        'name' => '',
        'email' => '',
        'is_super' => 0,
        'roles' => [],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = User::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = User::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('email', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = User::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('email', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = $data['recordsTotal'];
                $data['data'] = User::
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $data['rolesAll'] = Role::all()->toArray();
        return view('admin.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'name'=>'required|unique:admin_users|max:255',
            'email'=>'required|unique:admin_users|max:255',
            'is_super' => 'int'
        ]);
        foreach (array_keys($this->fields) as $field) {
            $user->$field = $request->get($field);
        }
        if ($request->get('password') != '' && $request->get('repassword') != '' && $request->get('password') == $request->get('repassword')) {
            $user->password = bcrypt($request->get('password'));
        } else {
            return redirect()->back()->withErrors('密码或确认密码不能为空！');
        }
        unset($user->roles);
        $user->save();
        if (is_array($request->get('roles'))) {
            $user->attachRoles($request->get('roles'));
        }
        return redirect('/admin/user')->withSuccess('添加成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find((int)$id);
        if (!$user) return redirect('/admin/user')->withErrors("找不到该用户!");
        $roles = [];
        if ($user->roles) {
            foreach ($user->roles as $v) {
                $roles[] = $v->id;
            }
        }
        $user->roles = $roles;
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $user->$field);
        }
        $data['rolesAll'] = Role::all()->toArray();
        $data['id'] = (int)$id;
        return view('admin.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|unique:admin_users,name,'.$request->get('id').'|max:255',
            'email'=>'required|unique:admin_users,email,'.$request->get('id').'|max:255',
            'is_super' => 'int'
        ]);
        $user = User::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            $user->$field = $request->get($field);
        }
        if ($request->get('password') != '' || $request->get('repassword') != '') {
            if ($request->get('password') != '' && $request->get('repassword') != '' && $request->get('password') == $request->get('repassword')) {
                $user->password = bcrypt($request->get('password'));
            } else {
                return redirect()->back()->withErrors('修改密码时,密码或确认密码不能为空！');
            }
        }
        unset($user->roles);
        $user->save();

        $user->detachRoles();
        $user->attachRoles($request->get('roles',[]));
        return redirect('/admin/user')->withSuccess('添加成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = User::find((int)$id);
        if ($tag && $tag->id != 1) {
            $tag->delete();
        } else {
            return redirect()->back()
                ->withErrors("删除失败");
        }

        return redirect()->back()
            ->withSuccess("删除成功");
    }
}
