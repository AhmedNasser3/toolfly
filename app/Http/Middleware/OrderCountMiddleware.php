<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderCountMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // الحصول على الـ access_token من قاعدة البيانات بناءً على الجلسة أو الـ user
        $store = Store::where('access_token', session('access_token'))->first();

        if ($store) {
            // استخدام الـ access_token لاسترجاع بيانات الأوردرات من سلة
            $url = config('salla.salla-api-url') . '/orders'; // API الأوردرات
            $response = Http::withToken($store->access_token)->get($url);

            if ($response->successful()) {
                // الحصول على عدد الأوردرات
                $orders = $response->json();
                $orderCount = count($orders);

                // إرسال عدد الأوردرات إلى جميع الصفحات عبر الجلسة أو البيانات المشتركة
                view()->share('orderCount', $orderCount);
            }
        }

        return $next($request);
    }
}