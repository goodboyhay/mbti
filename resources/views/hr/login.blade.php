<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{asset('./css/style.css')}}" rel="stylesheet" type="text/css">
    <title>
        Login
    </title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 p-0">
            <div class="login-banner m-0"></div>
            <img src="{{asset('../resources/img/logo-hybrid-technologies.svg')}}" class="login-hybrid"/>
            <h2 class="login-draw">GIÁ TRỊ CỐT LÕI</h2>
            <div class="login-draw1">
                <h4 class="font-weight-bold">Giao tiếp (Communication)</h4>
                <h5>Chủ động đối thoại không ngần ngại</h5>
            </div>
            <div class="login-draw2">
                <h4 class="font-weight-bold">Giải pháp (Solution)</h4>
                <h5> Đưa ra giải pháp khả thi nhất, phù hợp mọi nhu cầu, hoàn cảnh</h5>
            </div>
            <div class="login-draw3">
                <h4 class="font-weight-bold">Mối quan hệ (Relation)</h4>
                <h5> Xây dựng mối quan hệ đồng hành, phát triển bền vững và đáng tin cậy</h5>
            </div>
            <div class="login-draw4">
                <h5> Hybrid-Technologies</h5>
                <img src="{{asset('../resources/img/Vector.svg')}}" class="login-vector"/>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
            <div class="login-text-header">
                    <h5 class="login-text-header1">Hybrid-Technologies</h5>
                    <h5 class="login-text-header2"> HR PAGE</h5>
            </div>
            <div class="login-text-form">
                <h5 class="login-text-form1">Welcome!</h5>
                <h2 class="login-text-form2"> Login to your account</h2>
                <div class="login-wrap p-4">
                    <form id="loginForm" enctype="multipart/form-data" class="login-form">
                        <img class="login-email-icon" src="{{asset('../resources/img/email.png')}}"/>
                        <label class="login-email">Email</label>
                        <div class="form-group">
                            <input name="email" id="email" type="text" class="form-control rounded-left form-place-holder" placeholder="Enter email address" required>
                        </div>
                            <label class="login-password">Password</label>
                        <div class="form-group d-flex mb-3">
                            <img class="login-password-icon" src="{{asset('../resources/img/password.png')}}"/>
                            <input name="password" id="password" type="password" class="form-control rounded-left form-place-holder" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group d-flex mb-3">
                            <span id="errorsOutput" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            </div>
        </div>
    </div>     
</div>
</body>
<script>
const loginForm = document.getElementById('loginForm');
loginForm.addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    let data = {
		"email":formData.get('email'),
		"password":formData.get('password'),
	};
    fetch('/api/auth/login', {
		method: 'POST',
        headers: {
            'Accept': 'application/json, text/plain, */*',
			'Content-Type': 'application/json',
        },
		body: JSON.stringify(data),
    })
    .then((response)=>{
        return response.json()
    })
    .then((data)=>{
        if(data.response_code==200){
			let hrToken = {
                "user": data.data.user,
				"token": data.data.token, 
			};
			localStorage.setItem("hrToken", JSON.stringify(hrToken));
			window.location.href = "/candidates-list";
		}
        document.getElementById('errorsOutput').innerHTML=data.message;
    })
    .catch((error) => console.error(error));
});
</script>
<!-- Optional JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</html>