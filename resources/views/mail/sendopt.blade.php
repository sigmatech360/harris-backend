<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        table{
            width:50%;
            margin:auto;
            background-color:#fff;
            text-align:center;
        }
        .main{
            padding:30px 0px;
            background-color:rgb(235, 235, 235);;
        }
        
    </style>
</head>


<body>
    <div class="main">

        <table>
            <tr>
                <th>
                    <img alt="Logo" src="https://server.testlinkwebsitespace.com/virtual-pi-backend/public/images/logo-virtual-pi.png" class="" width="110px" hieght="110px"/>
                    <h2>Welcome {{ $user['email'] }}</h2>
                </th>
            </tr>

            <tr>
                <td>
                    <h4 class="">
                        {{ $details['content'] }}
                    </h4>

                  <h4 class="">{{ env('APP_NAME') }}</h4>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
