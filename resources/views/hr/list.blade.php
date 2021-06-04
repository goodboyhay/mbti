@extends('layouts.hr')
@section('title', 'List Candidates')
@section('content')
<table class="table table-borderless bg-white table-hover hr02-content mx-1">
    <thead>
        <tr class="tr-header">
            <td colspan="7">
                <div class="row mt-2 ml-4 ">
                    <div class="col-md-w p-2">
                        <h4>List survey</h4>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-1 bg-light shadow-sm mb-4 ">
                            <div class="input-group">
                                <button disabled type="submit" class="btn "><i class="bi bi-search"></i></button>
                                <input type="search" name="search" placeholder="Search list.." class="form-control border-0 bg-light" onkeydown="searchCandidate(this)">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- flashmessage -->
                <div id="flashMessage" style="display:none;"></div>
                <!-- flashmessage -->
            </td>
        </tr>             
        <tr>
            <th scope="col"><span class="d-flex justify-content-center">#</span></th>
            <th scope="col">
                <div class="d-flex flex-row">
                    Name
                    <button id="sortName" class="d-flex flex-row btn bg-transparent p-0 mx-2 mt-1" onclick="return handleSortBtn(this)" value='{"sort_by":"candidateName","direction":"asc"}'>
                        <img src="{{asset('../resources/img/sort.png')}}" class="sort-table m-0" >
                    </button>
                </div>
            </th>
            <th scope="col">
                <div class="d-flex flex-row">
                    Date
                    <button id="sortDate" class="d-flex flex-row btn bg-transparent p-0 mx-2 mt-1" onclick="return handleSortBtn(this)" value='{"sort_by":"updated_at","direction":"asc"}'>
                        <img src="{{asset('../resources/img/sort.png')}}" class="sort-table m-0" >
                    </button>
                </div>
            </th>
            <th scope="col">
                <span class="justify-content-center">
                    Result
                </span>
            </th>
            <th scope="col">
                <div class="d-flex flex-row">
                    Position
                    <button id="sortPosition" class="d-flex flex-row btn bg-transparent p-0 mx-2 mt-1" onclick="return handleSortBtn(this)" value='{"sort_by":"position","direction":"asc"}'>
                        <img src="{{asset('../resources/img/sort.png')}}" class="sort-table m-0" >
                    </button>
                </div>
            </th>
            <th scope="col"><span class="d-flex justify-content-center">Action</span></th>
        </tr>
    </thead>
    <tbody id="candidatetbody">
    </tbody>
</table>
<div class="row my-2">
    <div class="col-md-12 col-sm-12 col-12 d-flex justify-content-center">
        <button id="loadMore" class="btn btn-primary" style="display:block;" role="button">Load more...</button>
    </div>
</div>
<script>
hrToken = JSON.parse(localStorage.getItem('hrToken'));
window.onload = candidatesData;
// Initial variable
const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
let candidateList = '';
let i = 1;
// Button load more handle
let totalPages, currentPage = 0;
let loadMoreBtn = document.getElementById("loadMore");
loadMoreBtn.addEventListener("click",candidatesData);
// Sort data
function handleSortBtn(btnData){
    let value = JSON.parse(btnData.value);
    let params = {
        'sort_by': value.sort_by,
        'direction': value.direction,
    };
    let query = Object.keys(params)
            .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
            .join('&');
    candidateList = '';
    i=1;
    fetch(`/api/sort?${query}`,{
        method: 'GET',
        headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + hrToken['token'],
        },
    }).then((response)=> {
        if (response.status == 200) {
            return response.json();
        } else {
            throw `Error Status ${response.status}`;
        }
    }).then((data) => {
        if(data.response_code==200){
            loadMoreBtn.style.display = "none";
            data.data.data.forEach((candidate)=>{
                const time = new Date(candidate.updated_at);
                renderCandidates(candidate, time)
            });
            document.getElementById('candidatetbody').innerHTML = candidateList;
            if(value.sort_by=="candidateName"){
                value.direction=="asc"?document.getElementById('sortName').value= '{"sort_by":"candidateName","direction":"desc"}':document.getElementById('sortName').value= '{"sort_by":"candidateName","direction":"asc"}';
            } else if(value.sort_by=="updated_at"){
                value.direction=="asc"?document.getElementById('sortDate').value= '{"sort_by":"updated_at","direction":"desc"}':document.getElementById('sortDate').value= '{"sort_by":"updated_at","direction":"asc"}';
            } else if (value.sort_by=="position"){
                value.direction=="asc"?document.getElementById('sortPosition').value= '{"sort_by":"position","direction":"desc"}':document.getElementById('sortPosition').value= '{"sort_by":"position","direction":"asc"}';
            }
        }
    }).catch((error) => console.error(error));
}
// Fetch data
function candidatesData(){
    if(currentPage >= totalPages) return 
    const nextPage = currentPage + 1;
    fetch(`/api/candidates-list?page=${nextPage}`,{
        method: 'GET',
        headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + hrToken['token'],
        },
    }).then((response)=> {
        if (response.status == 200) {
            return response.json();
        } else {
            throw `Error Status ${response.status}`;
        }
    }).then((data) => {
        if(data.response_code==200){
            totalPages = data.data.last_page;
            loadMoreBtn.innerHTML = `Load more ${data.data.to}/${data.data.total}`;
            if(totalPages == nextPage) loadMoreBtn.disabled = true;
            data.data.data.forEach((candidate)=>{
                const time = new Date(candidate.updated_at);
                renderCandidates(candidate, time)
            });
            document.getElementById('candidatetbody').innerHTML = candidateList;
            currentPage = nextPage;
        }
    }).catch((error) => console.error(error));
}
// Search candidate
function searchCandidate(search){
    if(event.key === 'Enter') {
        let data = {
            "search": search.value,
        };
        if(data.search=="")location.reload();
        candidateList = '';
        i=1;
        fetch(`/api/search`,{
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + hrToken['token'],
            },
            body: JSON.stringify(data),
        }).then((response)=>{
            if (response.status == 200) {
                return response.json();
            } else {
                return response.json();
                throw `Error Status ${response.status}`;
            }
        })
        .then((data) => {
            if (data.response_code == 200) {
                loadMoreBtn.style.display = "none";
                data.data.data.forEach((candidate)=>{
                    const time = new Date(candidate.updated_at);
                    renderCandidates(candidate, time)
                });
                document.getElementById('candidatetbody').innerHTML = candidateList;
            }else{
                let flashMessage = '';
                flashMessage += `
                    <div class="row mt-2">
                        <div class="alert alert-warning alert-block w-100">
                            <button type="button" class="close" data-dismiss="alert">
                                <i height="22"class="bi bi-x-octagon"></i>
                            </button>    
                            <strong>Thông báo</strong> - ${data.message}
                        </div>
                    </div>
                `;
                document.getElementById('flashMessage').style.display="block";
                document.getElementById('flashMessage').innerHTML=flashMessage;
            }
        })
        .catch((error) => console.error(error));
    }
}
// Delete candidate
function deleteCandidate(candidate_id){
    fetch(`/api/candidate-delete/${candidate_id}`,{
        method: 'DELETE',
        headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + hrToken['token'],
        },
    }).then((response)=> {
        if (response.status == 200) {
            return response.json();
        } else {
            throw `Error Status ${response.status}`;
        }
    }).then((data) => {
        let flashMessage = '';
        flashMessage += `
            <div class="row mt-2">
                <div class="alert alert-warning alert-block w-100">
                    <button type="button" class="close" data-dismiss="alert">
                        <i height="22"class="bi bi-x-octagon"></i>
                    </button>    
                    <strong>Thông báo</strong> - ${data.message}
                </div>
            </div>
        `;
        document.getElementById('flashMessage').style.display="block";
        document.getElementById('flashMessage').innerHTML=flashMessage;
        candidateList = '';
        i = 1;
        totalPages, currentPage = 0;
        loadMoreBtn.disabled = false;
        candidatesData()
    }).catch((error) => console.error(error));
}
// Render data from API call
function renderCandidates(candidate, time){
    candidateList+=`
        <tr>
            <th scope="row" class="d-flex justify-content-center">${i++}</th>
            <td >
                <div class="d-flex flex-row align-items-center">
                    <div class="d-flex flex-column mx-2">
                        <h6 class="font-weight-bold">${candidate.candidateName}</h6>
                        <span class="sub-text-grey">${candidate.email}</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex flex-column ">
                    <h6>${months[time.getMonth()+1]+" "+ String(time.getDate()).padStart(2, '0') +" "+ time.getFullYear()} </h6>
                    <span class="sub-text-grey">${String(time.getHours()).padStart(2, '0')+":"+String(time.getMinutes()).padStart(2, '0')}</span>
                </div>
            </td>
            <td>
                <div class="d-flex flex-column ">
                    <h6>${candidate.mbti_result}</h6>
                    <span class="sub-text-grey">${candidate.nickname}</span>
                </div>
            </td>
            <td>
                <div class="d-flex flex-column ">
                    <h6>${candidate.position}</h6>
                </div>
            </td>
            <td class="d-flex justify-content-center">
                <div class="d-flex flex-row align-items-center">
                    <a class="mx-2" href="/candidate-result/${candidate.candidate_id}"><i style="font-size: 1.5em;" class="bi bi-person-check"></i></a>
                    <button class="btn bg-transparent p-0" onclick="return ConfirmBox('Bạn có chắc xóa kết quả của ${candidate.candidateName} này !!')?deleteCandidate(${candidate.candidate_id}):''"><i class="bi bi-trash"style="font-size: 1.5em;"></i></button>
                </div>
            </td>
        </tr>
    `;
}
</script>
@endsection