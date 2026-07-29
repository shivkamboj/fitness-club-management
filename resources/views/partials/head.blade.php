@php
    $type = $type ?? 'gsm'; // default to prevent "Undefined variable $type"

    $headCommon = [
        'favicons' => [
            ['rel' => 'icon', 'href' => asset('images/favicon.ico'), 'sizes' => 'any'],
            ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32', 'href' => asset('images/favicon-32x32.png')],
            ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16', 'href' => asset('images/favicon-16x16.png')],
            ['rel' => 'apple-touch-icon', 'sizes' => '180x180', 'href' => asset('images/favicon-180x180.png')],
            ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '192x192', 'href' => asset('images/favicon-192x192.png')],
        ],
    ];

    $headData = [
        'gsm' => [
            'title'       => 'Gym Manager | Cloud Gym Management System',
            'description' => 'Purchase your gym management server — members, trainers, classes, payments, diet & workout plans on one secure cloud dashboard. Start hosting today.',
            'keywords'    => 'gym management system, gym software, membership management, gym CRM, gym billing software, cloud gym server',
            'author'      => 'Gym Manager',
            'ogTitle'      => 'Gym Manager | Cloud Gym Management System',
            'ogDescription'=> 'Run your gym on our servers. Manage members, trainers, payments, and classes from one dashboard.',
        ],

        'gwb' => [
            'title'       => 'Gym Website Builder | Professional Gym & Fitness Websites',
            'description' => 'Get a professional gym website in days — online membership, SEO, and digital marketing for gym owners, trainers, and yoga studios. One-time or subscription plans.',
            'keywords'    => 'gym website, fitness website builder, gym website design, yoga studio website, fitness center website, gym web development',
            'author'      => 'Gym Website Builder',
            'ogTitle'      => 'Gym Website Builder | Professional Gym & Fitness Websites',
            'ogDescription'=> 'Build your professional gym website in just a few days. Get more members with a beautiful website, online membership, SEO, and digital marketing.',
        ],
    ];

    $data = array_merge($headCommon, $headData[$type] ?? $headData['gsm']);
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] }}</title>
    <meta name="description" content="{{ $data['description'] }}">
    <meta name="keywords" content="{{ $data['keywords'] }}">
    <meta name="author" content="{{ $data['author'] }}">

    @foreach ($data['favicons'] as $icon)
        <link rel="{{ $icon['rel'] }}"
              @if(isset($icon['type'])) type="{{ $icon['type'] }}" @endif
              @if(isset($icon['sizes'])) sizes="{{ $icon['sizes'] }}" @endif
              href="{{ $icon['href'] }}">
    @endforeach

    <meta property="og:title" content="{{ $data['ogTitle'] }}">
    <meta property="og:description" content="{{ $data['ogDescription'] }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url('/') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('gwb_theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
