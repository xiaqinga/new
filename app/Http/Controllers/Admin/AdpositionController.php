<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Adposition;
use Config;

class AdpositionController extends Controller
{
    protected $fields = [
        'name' => '',
        'display_name' => '',
        'description' => '',
        'perms' => [],
    ];
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
            $data['recordsTotal'] = Adposition::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Adposition::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Adposition::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = $data['recordsTotal'];
                $data['data'] = Adposition::
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
                $types=Config::get('constants.types');
                foreach ($data['data'] as $item) {
                    $item['indexClassId']=$types[$item['indexClassId']];
                }
            }

            return response()->json($data);
        }
        $data['types']=Config::get('constants.types');
        return view('admin.adposition.index',$data);
    }

    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $data['types']=Config::get('constants.types');
        $data['otherImgPath']=Config::get('constants.otherImgPath');
        $data['otherImgUrl']=Config::get('constants.otherImgUrl');
        $data['resSer']=Config::get('constants.resSer');
        return view('admin.adposition.create',$data);
    }
}
