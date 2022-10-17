<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CS442 200 Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/flowbite@1.5.1/dist/flowbite.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
</head>

<body>
  <button type="button" onclick="login()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center" >Login with Line
      <p id="userID"></p>
      <p id="displayName"></p>
      <img id="pictureUrl" width="300">
  </button>

</body>

<script type="text/javascript">

    liff.init({
        liffId: '1657565110-zQDgDAll',
    });

    function login(){
        // var userIDTxt = document.getElementById("userId");
        // var userNameTxt = document.getElementById("userName");
        // var pic = document.getElementById("pic");

        if (!liff.isLoggedIn()) {
            liff.login({ redirectUri: "https://line-bot-me-opaller9.loca.lt/lineView" });
        }

        var profile = liff.getProfile();
        profile.then(
            
        )
        console.log(profile);
        // if (liff.isLoggedIn()) {
        //     liff.logout();
        // }
    }

</script>

