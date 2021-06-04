@extends('layouts.candidate')
@section('title', 'Candidate Roll-in')
@section('content')
<div class="d-flex flex-column float-right card">
	<h1>YOUR INFORMATION</h1>
	<form role="form" id="candidateInfoForm" name="candidateForm">
		<div class="form-group">
		<label>YOUR NAME</label>
			<input id="yourname" name="name" onkeyup="formatName(this)" placeholder="Full Name" type="text" class="form-control" ><span class="Font-weight-normal text-danger font-weight-light" id="nameloc"></span>
		</div>
		<div class="form-group">
			<label>DATE OF BIRTH</label>
		<div class="input-group date"> <input name="dob" id="dob" placeholder="yyyy-mm-dd" class="form-control"  type="date" data-date-format="yyyy-mm-dd" > <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i> </div>
		</span><span class="Font-weight-normal text-danger font-weight-light" id="dobloc"></span>
		</div>
		
		<div class="form-group">
		<label>YOUR EMAIL</label>
		<input name="email" id="email" placeholder="Email" type="email" class="form-control" ><span class="Font-weight-normal text-danger font-weight-light" id="emailloc"></span>
		</div>
		<div class="form-group">
			<label>POSITION APPLY</label>
			<div class="select">
			<select name="position" class="select__field form-control" required>
				<option value="" selected>Choose option ..</option>
				<option>Back-end Developer</option>
				<option>Front-end Developer</option>
				<option>QA</option>
				<option>Designer</option>
				<option>Comtor</option>
				<option>Others</option>
			</select>
			</div>
			<span class="Font-weight-normal text-danger font-weight-light" id="positionloc"></span>
		</div>
		<button type="submit" class="btn btn-primary d-flex justify-content-center d-md-table mx-auto font-weight-bold">Submit</button>
	</form>
</div>
<script>
document.getElementById('candidateInfoForm').addEventListener('submit', submitInfo);
let specialCharacters = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~\d]/;
function formatName(name){
	let nameValue = name.value;
	if(nameValue.length <=40){
		let nameInput = nameValue.replace(/\s\s+/g, ' ');
		let formattedName = nameInput.toLowerCase().split(' ');
		for (let i = 0; i < formattedName.length; i++) {
			formattedName[i] = formattedName[i].charAt(0).toUpperCase() +
			formattedName[i].substring(1);
			if(formattedName[i].match(specialCharacters)){
				document.getElementById("nameloc").innerHTML = 
				"Name can't have special characters and numbers.";
				document.candidateForm.name.focus();
			}
		}
		document.getElementById("yourname").value = formattedName.join(' ');
	} else {
		document.getElementById("nameloc").innerHTML = 
			"Name can't have more than 40 characters.";
		document.candidateForm.name.focus();
	}
}

function validate() {
	let name = document.getElementById("yourname").value;
	let dob = document.getElementById("dob").value;
	let email = document.getElementById("email").value;
	let valid = true;
	const dateTime = new Date();
	const currentYear = dateTime.getFullYear();
	const yearOfBirth= parseInt(document.candidateForm.dob.value);
	const age = currentYear-yearOfBirth;

	if (name == null || name == "") {
		document.getElementById("nameloc").innerHTML = 
			"Name can't be blank";
		document.candidateForm.name.focus();
		valid = false;
	}
	if(name.length > 40 ) {
		document.getElementById("nameloc").innerHTML = 
			"Name can't have more than 40 characters.";
		document.candidateForm.name.focus();
		valid = false;
	}
	if(name.match(specialCharacters)){
		document.getElementById("nameloc").innerHTML = 
		"Name can't have special characters and numbers.";
		document.candidateForm.name.focus();
	}
	if (dob == null || dob == "") {
		document.getElementById("dobloc").innerHTML = 
			"Date of birth can't be blank.";
		document.candidateForm.dob.focus();
		valid = false;
	}
	if (age<=18||age>=55) {
		document.getElementById("dobloc").innerHTML = 
			"Your age is out of range.";
		valid = false;
	}
	if (email == null || email == "") {
		document.getElementById("emailloc").innerHTML = 
			"Email can't be blank.";
		document.candidateForm.email.focus();
		valid = false;
	}
	return valid;
}
function submitInfo(ele){
	ele.preventDefault()
	if(validate()){
		let formEle = document.forms.candidateInfoForm;
		let formData = new FormData(formEle);
		let data = {
			"name":formData.get('name').trim(),
			"email":formData.get('email').trim(),
			"dob":formData.get('dob').trim(),
			"position":formData.get('position').trim(),
		};
		fetch('/api/create-candidate', {
			method: 'POST',
			headers: {
				'Accept': 'application/json, text/plain, */*',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(data)
		})
		.then((response)=>{
			return response.json()
		})
		.then((data)=>{
			if(data.response_code==200){
				let localData = {
					"candidate_id": data.data.candidate_id, 
					"token_key": data.data.token_key
				};
				localStorage.setItem("localData", JSON.stringify(localData)); 
				window.location.href = "/survey";
			}
		})
		.catch((error) => console.error(error));
	} else {
		console.error('Oops! Something Went Wrong.');
	}
}
</script>
@endsection
