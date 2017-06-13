<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use Config;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
  if ($request->ajax()) {
        $data         = array();
        $data['draw'] = $request->get('draw');
        $start        = $request->get('start', 0);
        $length       = $request->get('length', 10);
        $order        = $request->get('order');
        $columns      = $request->get('columns');
        $Customer     = new Customer();
        $where        = [];
        if ($fromSide = $request->get('fromSide')) {
            $where[] = ['fromSide', '=', $fromSide];
        }

        if ($makerLevel = $request->get('makerLevel')) {
            $where[] = ['makerLevel', '=', 1];
        }


        $status=$request->get('status');
        if (isset($status)){
            $where[] = ['status', '=', $status];
        }


        if ($number = $request->get('number')) {
            $data['recordsFiltered'] = $Customer->where($where)
                ->Where(function ($query) use ($number) {
                    $query->orwhere('id', '=', $number)
                        ->orwhere('mobilePhone', '=', $number);
                })->count();
            $data['data']            = $Customer->where($where)->Where(function ($query) use ($number) {
                $query->orwhere('id', '=', $number)
                    ->orwhere('mobilePhone', '=', $number);
            })->skip($start)->take($length)
                ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                ->get();

        } else {
            $data['recordsFiltered'] = $Customer->where($where)->count();
            $data['data']            = Customer::with('SilverScore')->where($where)->skip($start)->take($length)
                ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                ->get();

        }
        $fromSides = Config::get('constants.fromSides');
        foreach ($data['data'] as $item) {
          $item->accumulaSilverScore =$item->SilverScore->accumulaSilverScore;
          $item->fromSide=$fromSides[$item->fromSide];
          $item->parentId=$item->parentId?Customer::find($item->parentId)->alias:'无';
         }

         return response()->json($data);
       }
        $data['types'] = Config::get('constants.types');

        return view('admin.customer.index', $data);
    }

    /**    禁用/开启用户
     * @param $id
     *
     * @return mixed
     *
     */
    public function destroy($id)
    {

        $customer = Customer::find((int)$id);

        $customer->status=$customer->status==0?1:0;
        $customer->save();
        return redirect()->back()
            ->withSuccess("操作成功");
    }

    public  function show($id){
        $customer = Customer::find((int)$id);


        return view('admin.customer.show');

    }
}
