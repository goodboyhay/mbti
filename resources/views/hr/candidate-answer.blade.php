@extends('layouts.hr')
@section('title', 'Candidate Answer')
@section('content')
<div class="row">
    <div class="container content-section bg-white my-5 p-2">
        <div class="row p-4">
            <div class="col-md-10 col-sm-12 col-12 mx-auto">
                <div class="d-flex flex-row justify-content-center">
                    <div id="candidateInfo"></div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-8 mx-auto d-flex justify-content-center">
                <h3 class="mbti-result">Bài trắc nghiệm MBTI</h3>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-10 mx-auto d-flex justify-content-center flex-row">
                <div class="d-flex flex-column font-weight-normal mr-auto">
                    <div class="blue-text">HƯỚNG DẪN</div>
                    <div>Đây là bài trắc nghiệm tính cách nên sẽ không có câu trả lời đúng hay câu trả lời sai</div>
                    <div>Hãy chọn câu trả lời mà bạn cho là phù hợp hơn với bản thân mình</div>
                    <div>Chỉ đọc câu trả lời và chọn, bạn không nên cố gắng phân tích quá nhiều các lựa chọn này vì làm như vậy sẽ khiến kết quả của bạn kém chính xác</div>
                    <div>Đừng nhìn vào bảng điểm trước khi bạn hoàn thành tất cả câu hỏi</div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-8 mx-auto d-flex justify-content-center">
                <h3 class="blue-text" id='subjectHeading'></h3>
            </div>
        </div>
        <div id="questions"></div>
        <div class="row p-4 d-flex justify-content-center">
            <a href="{{route('hr.candidates-list')}}" type="button" class="btn btn-info my-2 rounded-pill font-weight-bold">Back to list</a>
        </div>
    </div>
</div>
<script>
    let candidate_id = location.pathname.split('/')[2];
    hrToken = JSON.parse(localStorage.getItem('hrToken'));
    fetch(`/api/candidates/survey/${candidate_id}`,{
        method: 'GET',
        headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + hrToken['token'],
        },
    })
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        subject_id = `ĐỀ ${data.data.subject_id}`;
        document.getElementById('subjectHeading').innerHTML = subject_id;
        let questions = '';
        let info = '';
        let candidate_info = data.data.candidate_info;
        let candidate_answer = data.data.candidate_answer;
        info += `
            <div class="d-flex justify-content-center flex-column mx-4">
                <div class="d-flex flex-row">
                    <div class="text-head"><i class="bi bi-person-bounding-box"></i> Name:</div>
                    <div class="blue-text">${candidate_info.name}</div>
                </div>
                <div class="d-flex flex-row font-weight-normal">
                    <div class="text-head"><i class="bi bi-mailbox"></i> Email:</div>
                    <div class="blue-text">${candidate_info.email}</div>
                </div>
                <div class="d-flex flex-row font-weight-normal">
                    <div class="text-head"><i class="bi bi-calendar2-week"></i> Birthday:</div>
                    <div class="blue-text">${candidate_info.dob}</div>
                </div>
                <div class="d-flex flex-row font-weight-normal">
                    <div class="text-head"><i class="bi bi-info-square"></i>  Position:</div>
                    <div class="blue-text">${candidate_info.position}</div>
                </div>
                <div class="d-flex flex-row font-weight-normal">
                    <div class="text-head"><i class="bi bi-person-lines-fill"></i> ID:</div>
                    <div class="blue-text">${candidate_info.candidate_id}</div>
                </div>
            </div>
        `;
        data.data.questions.forEach((question)=>{
            questions += `
            <div class="row p-2">
                <div class="col-md-10 col-sm-12 col-12 mx-auto d-flex">
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex flex-row">
                            <div class="mx-2">Câu ${question.index_number}:</div>
                            <div>${question.title}</div>
                        </div>
                        <div class="d-flex flex-row my-2">
                            <div class="col-md-6">
                                <label class="form-check-label font-weight-normal">
                                    <input class="form-check-input radio-inline" type="radio" name="gridRadios${question.index_number}" ${candidate_answer[question.index_number]=='A'?'checked':''} value="A" disabled>
                                    a. ${question.answer_a}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check-label font-weight-normal">
                                    <input class="form-check-input radio-inline" type="radio" name="gridRadios${question.index_number}" ${candidate_answer[question.index_number]=='B'?'checked':''} value="B" disabled>
                                    b. ${question.answer_b}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        });
        document.getElementById('questions').innerHTML = questions;
        document.getElementById('candidateInfo').innerHTML = info;
    })
    .catch((error) => {
        console.error(error);
    });
</script>
@endsection
