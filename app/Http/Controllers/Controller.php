<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $get_url = 'https://ecommerce.biente.shop/demo1/index.php?route=api/test/index';
    public $end_point = 'https://ecommerce.biente.shop/demo1/index.php?route=api/test/add';

    public function test()
    {

    }

    public function getData()
    {
        $users = User::all()->toArray();

        if($users){
            return redirect()->back()->with('users', $users);
        }

        $response = Http::get($this->get_url);
        $json = $response->json();

        foreach ($json as $value) {
            foreach ($value as $key => $value1) {
                $product = new Product();
                $user = new User();

                $product->id = $key;
                $user->externalId = $key;

                $user->userId = $value1['userId'];
                $user->save();

                $product->title = $value1['productMainInfos'][0]['title'];
                $product->description = $value1['productMainInfos'][0]['description'];

                $product->stockCount = $value1['stockCount'];
                $product->status = $value1['status'];

                $product->save();
            }
        }

        return view('ui', ['users' => User::all()]);
    }

    public function sendData()
    {
        $data = array();
        $index = 0;

        $users = User::where('userId', 129423874)
            ->get();

        foreach ($users as $user) {
            foreach ($user->getProducts()->where('stockCount', '>', 0)->where('status', '1')->get() as $product) {
                $data[$index]['externalId'] = $product->id;
                $data[$index]['title'] = $product->title;
                $data[$index++]['description'] = $product->description;
            }
        }

        $json = json_encode($data);

        $client = new Client();

        $request = $client->request('POST', $this->end_point, [ 'json' => $json]);

        return redirect()->back()->with('users', User::all());
    }

    public function ui()
    {
        $users = User::all();

        if($users){
            return view('ui', ['users' => $users]);
        }

        else{
            return view('ui');
        }
    }
}
