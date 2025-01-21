<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أوردرات المتجر</title>
    <style>
        /* تنسيق المربع الأبيض */
        .orders-box {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 2px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .orders-box h2 {
            text-align: center;
            color: #333;
        }
        .order-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .order-item p {
            margin: 5px 0;
        }
        /* لتنسيق المربع عندما لا يوجد أوردرات */
        .no-orders {
            color: red;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="orders-box">
    <h2>جميع أوردرات المتجر</h2>

    @if(isset($orders) && count($orders) > 0)
        @foreach($orders as $order)
            <div class="order-item">
                <p><strong>رقم الأوردر:</strong> {{ $order['id'] }}</p>
                <p><strong>حالة الأوردر:</strong> {{ $order['status'] }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ \Carbon\Carbon::parse($order['created_at'])->format('Y-m-d') }}</p>
                <p><strong>المجموع:</strong> {{ $order['total'] }} جنيه</p>
            </div>
        @endforeach
    @else
        <p class="no-orders">لا توجد أوردرات حالياً.</p>
    @endif
</div>

</body>
</html>
