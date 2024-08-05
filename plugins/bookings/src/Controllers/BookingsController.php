<?php

namespace Leo\Bookings\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Leo\Bookings\Models\Bookings;
use Leo\Customers\Models\Customers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\PushBooking;
use Illuminate\Support\Facades\Auth;
use Leo\Services\Models\ServiceBills;
use Leo\Services\Models\ServiceBillsDetails;

class BookingController extends Controller
{
    // List all bookings
    public function index()
    {
        $bookings = Bookings::with(['user', 'customer', 'service'])->get();
        return Inertia::render('Bookings/Index', ['bookings' => $bookings]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'time' => 'required|date_format:Y-m-d H:i:s',
            'id_service' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $customer = Customers::where('email', $request->email)->first();
        if ($customer) {
            $id_customer = $customer->id;
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $id_customer = Customers::insertGetId($data);
        }

        $booking = Bookings::create([
            'id_user' => $request->user()->id ?? null,
            'id_customer' => $id_customer,
            'id_service' => $request->id_service,
            'time' => $request->time,
            'end_time' => Carbon::parse($request->time)->addHour(),
        ]);
        $bookings = Bookings::with(['user', 'customer', 'service'])->where('status', 0)->orderBy('id', 'desc')->get();
        broadcast(new PushBooking($bookings));
        return response()->json(['check' => true], 200);
    }

    public function show($id)
    {
        $booking = Bookings::with(['user', 'customer', 'service'])->findOrFail($id);
        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'time' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'id_service' => 'sometimes|required|exists:services,id',
        ]);
        $booking = Bookings::findOrFail($id);
        if ($request->has('time')) {
            $booking->time = $request->time;
            $booking->end_time = Carbon::parse($request->time)->addHour();
        }
        if ($request->has('id_service')) {
            $booking->id_service = $request->id_service;
        }
        if ($request->has('status')) {
            $data['status'] = $request->status;
            $data['id_user'] = $request->id_user;
            $data['updated_at'] = now();
            Bookings::where('id', $id)->update($data);
        } else {
            $booking->save();
        }
        $bookings = Bookings::with(['customer', 'user', 'service'])->where('status', 0)
            ->get();
        $bookings = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'phone' => $booking->customer->phone,
                'customer_name' => $booking->customer->name,
                'customer_email' => $booking->customer->email,
                'service_id' => $booking->id_service,
                'service_name' => $booking->service->name,
                'service_slug' => $booking->service->slug,
                'service_discount' => $booking->service->price,
                'service_price' => $booking->service->compare_price,
                'time' => $booking->time,
                'end_time' => $booking->end_time,
                'status' => $booking->status,
            ];
        });
        return response()->json(['check' => true, 'bookings' => $bookings]);
    }


    public function destroy($id)
    {
        $booking = Bookings::findOrFail($id);
        $booking->delete();

        return response()->json(null, 204);
    }

    public function api_home(Request $request)
    {
        $bookings = Bookings::with(['customer', 'user', 'service'])->where('status', 0)->get();

        $bookings = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'id_user' => $booking->id_user,
                'phone' => $booking->customer->phone,
                'customer_name' => $booking->customer->name,
                'customer_email' => $booking->customer->email,
                'service_id' => $booking->id_service,
                'service_name' => $booking->service->name,
                'service_slug' => $booking->service->slug,
                'service_discount' => $booking->service->price,
                'service_price' => $booking->service->compare_price,
                'time' => $booking->time,
                'end_time' => $booking->end_time,
                'status' => $booking->status,
            ];
        });
        return response()->json($bookings);
    }
    public function api_nhan_vien(Request $request)
    {
        $id_nhan_vien = Auth::user()->id;
        $result = Bookings::where('id_user', $id_nhan_vien)->where('status', 1)->get();
        $bookings = $result->map(function ($booking) {
            return [
                'id' => $booking->id,
                'id_user' => $booking->id_user,
                'phone' => $booking->customer->phone,
                'customer_name' => $booking->customer->name,
                'customer_email' => $booking->customer->email,
                'service_id' => $booking->id_service,
                'service_name' => $booking->service->name,
                'service_slug' => $booking->service->slug,
                'service_discount' => $booking->service->price,
                'service_price' => $booking->service->compare_price,
                'time' => $booking->time,
                'end_time' => $booking->end_time,
                'status' => $booking->status,
            ];
        });
        return response()->json($bookings);
    }

    public function api_cancelbooking_nhan_vien($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $id_nhan_vien = Auth::user()->id;
        Bookings::where('id_user', $id_nhan_vien)
            ->where('id', $id)
            ->update(['status' => 3, 'note' => $request->note, 'updated_at' => now()]);
        $result = Bookings::where('id_user', $id_nhan_vien)->where('status', 1)->get();
        $bookings = $result->map(function ($booking) {
            return [
                'id' => $booking->id,
                'id_user' => $booking->id_user,
                'phone' => $booking->customer->phone,
                'customer_name' => $booking->customer->name,
                'customer_email' => $booking->customer->email,
                'service_id' => $booking->id_service,
                'service_name' => $booking->service->name,
                'service_slug' => $booking->service->slug,
                'service_discount' => $booking->service->price,
                'service_price' => $booking->service->compare_price,
                'time' => $booking->time,
                'end_time' => $booking->end_time,
                'status' => $booking->status,
            ];
        });
        return response()->json(['check' => true, 'bookings' => $bookings]);
    }

    public function api_submitbooking_nhan_vien($id, Request $request)
    {
        $id_nhan_vien = Auth::user()->id;
        Bookings::where('id_user', $id_nhan_vien)
            ->where('id', $id)
            ->update(['status' => 2, 'note' => $request->note, 'updated_at' => now()]);
        $result = Bookings::where('id_user', $id_nhan_vien)->where('status', 1)->get();
        $bookings = $result->map(function ($booking) {
            return [
                'id' => $booking->id,
                'id_user' => $booking->id_user,
                'phone' => $booking->customer->phone,
                'customer_name' => $booking->customer->name,
                'customer_email' => $booking->customer->email,
                'service_id' => $booking->id_service,
                'service_name' => $booking->service->name,
                'service_slug' => $booking->service->slug,
                'service_discount' => $booking->service->price,
                'service_price' => $booking->service->compare_price,
                'time' => $booking->time,
                'end_time' => $booking->end_time,
                'status' => $booking->status,
            ];
        });
        return response()->json(['check' => true, 'bookings' => $bookings]);
    }

    // get all bookings for the current user
    public function getCustomer()
    {
        $result = Bookings::with(['customer'])
            ->where('status', 2)
            ->get();
        $customers = $result->map(function ($booking) {
            return [
                'id_booking' => $booking->id,
                'id_customer' => $booking->id_customer,
                'phone' => $booking->customer->phone,
                'name' => $booking->customer->name,
                'email' => $booking->customer->email,
                'time' => $booking->time,
            ];
        });
        return response()->json(['data' => $customers]);
    }

    public function getBillsCustomer($id)
    {
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $result = Bookings::with(['customer', 'user', 'service'])
            ->where('status', 2)
            ->where('id_customer', $id)
            // ->whereBetween('time', [$startOfDay, $endOfDay])
            ->get();
        $customers = $result->map(function ($booking) {
            return [
                'id_booking' => $booking->id,
                'id_customer' => $booking->id_customer,
                'phone' => $booking->customer->phone,
                'name' => $booking->customer->name,
                'email' => $booking->customer->email,
                'time' => $booking->time,
                'service_name' => $booking->service->name,
                'user_name' => $booking->user->name,
            ];
        });
        return response()->json(['data' => $customers]);
    }

    public function createBill($id)
    {
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();
        $result =  Bookings::with(['customer', 'user', 'service'])
            ->where('id_customer', $id)
            ->where('status', 2)
            ->whereBetween('time', [$startOfDay, $endOfDay])
            ->get();
        if (count($result) == 0) {
            return response()->json(['check' => false, 'msg' => 'Booking không phải ngày hôm nay'], 200);
        }
        $idBill = ServiceBills::insertGetId([
            'id_customer' => $result[0]->id_customer,
            'status' => 0,
        ]);
        foreach ($result as $query) {

            ServiceBillsDetails::create([
                'id_bill' => $idBill,
                'id_service' => $query->id_service,
                'id_booking' => $query->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Bookings::where('id', $query->id)->update(['status' => 4, 'updated_at' => now()]);
        }

        return response()->json(['check' => true], 200);
    }

    public function getBill()
    {
        $bills = ServiceBills::with('customer', 'serviceBillsDetails')->get();

        $data = $bills->map(function ($query) {
            return [
                'id' => $query->id,
                'id_customer' => $query->id_customer,
                'customer_name' => $query->customer->name,
                'email' => $query->customer->email,
                'phone' => $query->customer->phone,
                'address' => $query->customer->address,
                'detail' => $query->serviceBillsDetails->map(function ($detail) {
                    return [
                        'price' => $detail->service->price,
                        'discount' => $detail->service->discount,
                    ];
                }),
                'status' => $query->status,
                'created_at' => $query->created_at,
            ];
        });
        return response()->json(['check' => true, 'data' => $data]);
    }

    public function successBill($id)
    {
        $bill = ServiceBills::with('serviceBillsDetails', 'customer')
            ->where('id_customer', $id)
            ->get();
        if (empty($bill)) {
            return response()->json(['check' => false], 404);
        }

        $data = $bill->map(function ($query) {
            return [
                'id' => $query->id,
                'customer' => [
                    'id' => $query->id_customer,
                    'name' => $query->customer->name,
                    'phone' => $query->customer->phone,
                    'email' => $query->customer->email,
                    'address' => $query->customer->address,
                ],
                'status' => $query->status,
                'detail' => $query->serviceBillsDetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'id_booking' => $detail->booking->id,
                        'time' => $detail->booking->time,
                        'service' => $detail->service->name,
                        'price' => $detail->service->price,
                        'discount' => $detail->service->discount,
                        'created_at' => $detail->created_at,
                    ];
                })
            ];
        });
        return response()->json(['check' => true, 'data' => $data], 200);
    }

    public function updateStatusBill(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric|boolean|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()], 404);
        }

        $newStatus = $request->input('status');
        $bills = ServiceBills::where('id_customer', $id)->get();

        if ($bills->isEmpty()) {
            return response()->json(['check' => false, 'msg' => 'Không tìm thấy hóa đơn cho khách hàng này.'], 404);
        }

        foreach ($bills as $bill) {
            $bill->update(['status' => $newStatus]);
        }
        return response()->json(['check' => true, 'data' => $bills], 200);
    }
}
