<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @page { margin: 25px 20px; }
    body { font-family: DejaVu Sans, sans-serif; font-size:12px; margin:20px; }
    h1 { text-align:center; font-size:16px; margin-bottom:15px; }
    h2 { background:#f2f2f2; padding:8px; font-size:14px; margin:20px 0 10px; border:1px solid #ccc; }
    table { width:100%; border-collapse:collapse; margin-bottom:20px; }
    th, td { border:1px solid #444; padding:6px; vertical-align:top; }
    th { background:#f2f2f2; text-align:left; width:30%; }
    .section { margin-bottom:20px; }
    .page-break { page-break-after:always; }
    .footer-logo {
      margin-top: 30px;
      text-align: center;
      font-size: 10px;
      color: #555;
    }
    .footer-logo img {
      max-width: 120px;
      margin-top: 10px;
    }
    .section { margin-bottom: 25px; }
    .watermark {
      position: fixed;
      bottom: 40px;
      right: 30px;
      width: 80px;
      opacity: 0.08;
    }
  </style>
</head>

<body>
<htmlpageheader name="page-header">
    <div style="text-align:right; font-size:10px;">Generated: {{ now()->format('M d, Y H:i') }}</div>
    <hr>
</htmlpageheader>
<sethtmlpageheader name="page-header" value="on" show-this-page="1" />
<sethtmlpagefooter name="page-footer" value="on" />

@yield('content')


<div class="footer-logo">
    <p><em>&copy;{{  date('Y') }} MY Virtual PI, All rights reserved.</em></p>
    <img src="{{ public_path('images/logo-virtual-pi.png') }}" alt="Virtual PI Logo">
  </div>
  
</body>
</html>