@extends('frontend.master')
@section('content')
@include('frontend.pages.banner.banner')
{{--  hero  --}}
<div class="hero">
    <div class="hero_container">
        <div class="hero_content">
            <div class="hero_data">
                <div class="hero_title">
                    <h2>

                        من الخبرة من
                        زيادة التحويلات والنمو لمتاجر التجارة الإلكترونية
                        <span>+10 سنوات</span>
                    </h2>
                    <h3>
                        منصة رقمية متكاملة تساعد متاجر التجارة الإلكترونية على زيادة التفاعل مع العملاء والمبيعات من خلال أدوات الإشعارات والتنبيهات المتطورة.

                        من تعزيز مبيعات متجرك الإلكتروني
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
{{--  notification  --}}
@include('frontend.pages.notification.notification')
@endsection
