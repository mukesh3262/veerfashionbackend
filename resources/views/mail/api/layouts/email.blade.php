<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} Email</title>
</head>
<body> 
    <div style="width: 700px; margin: 0 auto; font-family: 'system-ui', 'Segoe UI', 'Arial', 'Roboto', 'Helvetica Neue', 'sans-serif' !important; color:#2D2D2D; font-size: 16px; line-height: normal;">
        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-radius:4px;border:1px #f7f8f8 solid" width="100%">
            <tbody>
                <tr>
                    <td align="center" style="padding:0 25px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                            <tbody>
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="center" style="padding:20px 0 10px">
                                                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding:0" align="left">
                                                                        <a href="{{ url('/') }}" target="_blank">
                                                                            <img alt="{{ config('app.name') }} logo" style="width:150px" width="150" border="0" src="{{ asset('images/logo.svg') }}">
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                            <tbody>
                                <tr>
                                    <td align="center" style="padding:0 0 30px;background-color:#ffffff" bgcolor="#ffffff">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;padding:0px 40px">
                                            <tbody>
                                                @yield('content')
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table align="center" cellspacing="0" cellpadding="0" border="0" width="100%" style="width:100%;background-color:#50AF43" bgcolor="#50AF43">
            <tbody>
                <tr>
                    <td align="center" style="padding:24px 30px">
                        <table align="center" cellspacing="0" cellpadding="0" border="0" width="92%" style="width:100%;background-color:#50AF43" bgcolor="#50AF43">
                            <tbody>
                                <tr>
                                    <td align="center">
                                        <ul class="footer-nav" style="display: block; gap: 20px; list-style-type: none; flex-wrap: wrap; margin: 0; padding: 0; text-align: left;">
                                            <li class="footer-item" style="line-height:1.2em; color: #ffffff; display: inline-block; margin: 5px 10px 5px 0;">
                                                &copy; {{ date('Y') }} 
                                                <a href="{{ url('/') }}" style="color: #ffffff;">{{ config('app.name') }}</a>
                                            </li>
                                            <li class="footer-item" style="display: inline-block; margin: 5px 10px 5px 0;">
                                                <a class="footer-link" href="{{ url('/terms-of-service') }}" style="text-decoration:none; line-height:1.2em; color: #ffffff;">Terms of Service</a>
                                            </li>
                                            <li class="footer-item" style="display: inline-block; margin: 5px 10px 5px 0;">
                                                <a class="footer-link" href="{{ url('/privacy-policy') }}" style="text-decoration:none; line-height:1.2em; color: #ffffff;">Privacy Policy</a>
                                            </li>
                                            <li class="footer-item" style="display: inline-block; margin: 5px 10px 5px 0;">
                                                <a class="footer-link" href="{{ url('/contact-us') }}" style="text-decoration:none; line-height:1.2em; color: #ffffff;">Contact Us</a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>