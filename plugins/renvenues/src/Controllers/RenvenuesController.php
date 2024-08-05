<?php

namespace App\Packages\Revenues\src\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Leo\Renvenues\Models\HoaDon;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = HoaDon::all();
        return Inertia::render("Revenues/Index", ['revenues' => $revenues]);
    }
    // product
    //http://127.0.0.1:8000/api/revenue/product
    public function getProductRevenue()
    {
        $revenues = DB::table('hoa_don_chi_tiet')
            ->join('hoa_don', 'hoa_don_chi_tiet.id_hoa_don', '=', 'hoa_don.id')
            ->join('products', 'hoa_don_chi_tiet.id_product', '=', 'products.id')
            ->select(DB::raw('products.name as product_name, products.id as id_product, SUM(hoa_don_chi_tiet.quantity * products.price) as total_revenue'))
            ->groupBy('products.name', 'products.id')
            ->get();

        return response()->json($revenues);
    }
    //http://127.0.0.1:8000/api/revenue/products/daily?date1=2024-07-01&date2=2024-08-30
    public function getProductRevenueByDate(Request $request)
    {
        $date1 = Carbon::parse($request->date1);
        $date2 = Carbon::parse($request->date2);

        $revenues = DB::table('hoa_don_chi_tiet')
            ->join('hoa_don', 'hoa_don_chi_tiet.id_hoa_don', '=', 'hoa_don.id')
            ->join('products', 'hoa_don_chi_tiet.id_product', '=', 'products.id')
            ->whereBetween('hoa_don.created_at', [$date1, $date2])
            ->select(DB::raw('products.name as product_name, products.id as id_product, SUM(hoa_don_chi_tiet.quantity * products.price) as total_revenue, MIN(hoa_don.created_at) as min_created_at'))
            ->groupBy('products.name', 'products.id')
            ->orderBy('min_created_at')
            ->get();

        return response()->json($revenues);
    }
    //http://127.0.0.1:8000/api/revenue/products/monthly?month=2024-08-01
    public function getProductRevenueByMonth(Request $request)
    {
        $month = Carbon::parse($request->month)->month;

        $revenues = DB::table('hoa_don_chi_tiet')
            ->join('hoa_don', 'hoa_don_chi_tiet.id_hoa_don', '=', 'hoa_don.id')
            ->join('products', 'hoa_don_chi_tiet.id_product', '=', 'products.id')
            ->whereMonth('hoa_don.created_at', $month)
            ->select(DB::raw('products.name as product_name, products.id as id_product, SUM(hoa_don_chi_tiet.quantity * products.price) as total_revenue, MIN(hoa_don.created_at) as min_created_at'))
            ->groupBy('products.name', 'products.id')
            ->orderBy('min_created_at')
            ->get();

        return response()->json($revenues);
    }

    //services
    //http://127.0.0.1:8000/api/revenue/services
    public function getServiceRevenue()
    {
        $revenues = DB::table('bookings')
            ->join('services', 'bookings.id_service', '=', 'services.id')
            ->select(DB::raw('services.name as service_name, services.price as service_price, COUNT(bookings.id) as total_bookings'))
            ->groupBy('services.name', 'services.price')
            ->get();

        return response()->json($revenues);
    }
    //http://127.0.0.1:8000/api/revenue/services/daily?date1=2024-06-01&date2=2024-06-30
    public function getServiceRevenueByDate(Request $request)
    {
        $date1 = Carbon::parse($request->date1);
        $date2 = Carbon::parse($request->date2);

        $revenues = DB::table('bookings')
            ->join('services', 'bookings.id_service', '=', 'services.id')
            ->whereBetween('bookings.time', [$date1, $date2])
            ->select(DB::raw('services.name as service_name, services.price as service_price, COUNT(bookings.id) as total_bookings, DATE(bookings.time) as date'))
            ->groupBy('services.name', 'services.price', 'date')
            ->orderBy('date')
            ->get();

        return response()->json($revenues);
    }
    //http://127.0.0.1:8000/api/revenue/services/monthly?month=2024-06-01
    public function getServiceRevenueByMonth(Request $request)
    {
        $month = Carbon::parse($request->month)->month;

        $revenues = DB::table('bookings')
            ->join('services', 'bookings.id_service', '=', 'services.id')
            ->whereMonth('bookings.time', $month)
            ->select(DB::raw('services.name as service_name, services.price as service_price, COUNT(bookings.id) as total_bookings, DATE_FORMAT(bookings.time, "%Y-%m") as month'))
            ->groupBy('services.name', 'services.price', 'month')
            ->get();

        return response()->json($revenues);
    }
    //http://127.0.0.1:8000/api/revenue/services/weekly-monthly?id_customer=2&date1=2024-07-01&date2=2024-08-31
    public function getServiceRevenueByWeekMonth(Request $request)
    {
        $customerId = $request->id_customer;
        $date1 = Carbon::parse($request->date1);
        $date2 = Carbon::parse($request->date2);

        $revenues = DB::table('bookings')
            ->join('services', 'bookings.id_service', '=', 'services.id')
            ->where('bookings.id_customer', $customerId)
            ->whereBetween('bookings.time', [$date1, $date2])
            ->select(DB::raw('WEEK(bookings.time) as week, MONTH(bookings.time) as month, services.name as service_name, services.price as service_price, COUNT(bookings.id) as total_bookings'))
            ->groupBy('week', 'month', 'services.name', 'services.price')
            ->get();

        return response()->json($revenues);
    }
    //staff
    public function getRevenueByStaffAndDate(Request $request)
    {
        $idUser = $request->input('id_user');
        $date = $request->input('date');

        if ($date) {
            if (!Carbon::hasFormat($date, 'Y-m-d')) {
                return response()->json(['error' => 'Invalid date format. Use YYYY-MM-DD.'], 400);
            }
            // Nếu có id_user, lấy doanh thu cho nhân viên cụ thể trong ngày
            //http://127.0.0.1:8000/api/revenue/staff-date?id_user=12&date=2024-07-15
            if ($idUser) {
                $revenues = DB::table('bookings')
                    ->join('services', 'bookings.id_service', '=', 'services.id')
                    ->join('users', 'bookings.id_user', '=', 'users.id')
                    ->where('bookings.id_user', $idUser)
                    ->whereDate('bookings.time', $date)
                    ->select(DB::raw('users.name as user_name, SUM(services.price) as total_revenue'))
                    ->groupBy('users.name')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
            } else {
                // Nếu không có id_user, lấy doanh thu cho tất cả nhân viên trong ngày
                //http://127.0.0.1:8000/api/revenue/staff-date?date=2024-07-15
                $revenues = DB::table('bookings')
                    ->join('services', 'bookings.id_service', '=', 'services.id')
                    ->join('users', 'bookings.id_user', '=', 'users.id')
                    ->whereDate('bookings.time', $date)
                    ->select(DB::raw('users.name as user_name, SUM(services.price) as total_revenue'))
                    ->groupBy('users.name')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
            }
        } else {
            // Nếu không có ngày, lấy doanh thu cho tất cả thời gian
            //http://127.0.0.1:8000/api/revenue/staff-date?id_user=12
            if ($idUser) {
                $revenues = DB::table('bookings')
                    ->join('services', 'bookings.id_service', '=', 'services.id')
                    ->join('users', 'bookings.id_user', '=', 'users.id')
                    ->where('bookings.id_user', $idUser)
                    ->select(DB::raw('users.name as user_name, SUM(services.price) as total_revenue'))
                    ->groupBy('users.name')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
            } else {
                // Nếu không có id_user, lấy doanh thu cho tất cả nhân viên và tất cả thời gian
                //http://127.0.0.1:8000/api/revenue/staff-date
                $revenues = DB::table('bookings')
                    ->join('services', 'bookings.id_service', '=', 'services.id')
                    ->join('users', 'bookings.id_user', '=', 'users.id')
                    ->select(DB::raw('users.name as user_name, SUM(services.price) as total_revenue'))
                    ->groupBy('users.name')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
            }
        }
        return response()->json($revenues);
    }
}
