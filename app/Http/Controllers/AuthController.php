<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function storeAuth(){
        $data = [
            'client_id' => config('salla.client_id'),
            'client_secret' => config('salla.client_secret'),
            'response_type' => 'code',
            'scope' =>'offline_access',
            'redirect_url' => config('salla.callback_url'),
            'state'=> rand(111111110,9999999999)
        ];
        $query = http_build_query($data);
        return redirect(config('salla.auth_url'). '?' . $query);
    }
    public function callback(Request $request)
    {
        $data = [
            'client_id' => config('salla.client_id'),
            'client_secret' => config('salla.client_secret'),
            'response_type' => 'code',
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'scope' => 'offline_access',
            'redirect_url' => config('salla.callback_url'),
            'state' => $request->state
        ];
        $response = Http::asForm()->post(config('salla.token_url'), $data);
        $json_response = json_decode($response->body());

        if ($response->successful()) {
            $url = config('salla.salla-api-url') . '/store/info';
            $store_info = Http::withToken($json_response->access_token)->acceptJson()->get($url);

            if ($store_info->successful()) {
                $storeData = $store_info->json()['data'];  // استرجاع بيانات المتجر

                // تخزين store_id مع بقية البيانات
                Store::query()->create([
                    'store_id' => $storeData['id'],  // تخزين store_id
                    'access_token' => $json_response->access_token,
                    'refresh_token' => $json_response->refresh_token,
                    'expire_at' => Carbon::parse(now()->addSeconds($json_response->expires_in)),
                ]);
                return $store_info;
            }
        }
    }


    public function getOrders(Request $request)
    {
        $storeId = $request->query('identifier'); // جلب store_id من الطلب (الذي هو نفسه identifier)

        // جلب المتجر بناءً على store_id
        $store = Store::where('store_id', $storeId)->first();

        if (!$store) {
            return response()->json(['error' => 'المتجر غير موجود'], 404);
        }

        $apiUrl = 'https://api.salla.dev/admin/v2/orders';

        try {
            // إرسال الطلب لجلب الطلبات باستخدام Access Token الخاص بالمتجر
            $response = Http::withToken($store->access_token)
                ->acceptJson()
                ->get($apiUrl, [
                    'per_page' => 10, // عدد الطلبات التي تريد جلبها في الصفحة
                    'page' => 1,      // الصفحة الحالية
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'store_id' => $store->store_id, // إرجاع store_id من المتجر
                    'store_name' => $store->name ?? 'متجر غير مسمى',
                    'orders' => $data['data'], // بيانات الطلبات
                    'access_token' => $store->access_token, // إرجاع access_token مع الاستجابة
                ]);
            } else {
                return response()->json(['error' => 'فشل في جلب الطلبات'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء الاتصال'], 500);
        }
    }


    public function getStoreAccessToken(Request $request)
    {
        $identifier = $request->query('identifier');
        Log::info("الـ identifier المستلم: " . $identifier); // سجل الـ identifier للتأكد من أنه صحيح

        // جلب المتجر بناءً على الـ identifier
        $store = Store::where('store_id', $identifier)->first();

        if (!$store) {
            return response()->json(['error' => 'المتجر غير موجود'], 404);
        }

        return response()->json([
            'store_id' => $store->store_id,
            'store_name' => $store->name ?? 'متجر غير مسمى',
            'access_token' => $store->access_token,
        ]);
    }



}