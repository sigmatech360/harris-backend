<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title> {{$details['title']}}</title>
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
        .Logo{
            padding:10px;
        }
    </style>
</head>


<body>
    <div class="main">

        <table>
            <tr>
                <th class="Logo" >
                    <img alt="Logo" src="https://server.testlinkwebsitespace.com/virtual-pi-backend/public/images/logo-virtual-pi.png" class="" width="200px" hieght="110px"/>
                </th>
            </tr>

            <tr>
                <td>
                    <p><strong>Name:</strong> {{ $name }}</p>
                    <p><strong>Email Address:</strong> {{ $email }}</p>
                    @if(!empty($phone))
                        <p><strong>Phone Number:</strong> {{ $phone }}</p>
                    @endif
                    @if(!empty($reason))
                        <p><strong>Reason for contact :</strong>{{ $reason }}</p>
                     @endif
                    <p><strong>Message:</strong>{{ $msg }}</p>
                  <h4 class="">{{ env('APP_NAME') }}</h4>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
