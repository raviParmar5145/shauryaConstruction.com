<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');

        if ($request->get('keyword') != "") {
            $orders = $orders->where('users.name', 'like', '%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email', 'like', '%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id', 'like', '%'.$request->keyword.'%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.list', compact('orders'));

    }
    

    public function detail($orderID) {
        $order = Order::select('orders.*','countries.name as countryName')
            ->where('orders.id',$orderID)
            ->leftJoin('countries','countries.id', 'orders.country_id')
            ->first();
        
        $orderItems = OrderItems::where('order_id',$orderID)->get();
        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function changeOrderStatus(Request $request, $id){
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipping_date = $request->shipping_date;
        $order->save();

        $message = 'Order status updated successfully!';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' =>$message
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderID) {

        orderEmail($orderID,$request->userType);
        $message = 'Order email sent successfully!';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' =>$message
        ]);
    }
}
