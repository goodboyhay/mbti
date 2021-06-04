@extends('layouts.candidate')
@section('title', 'MBTI TEST')
@section('content')
<div class="row m-0 h-100 p-1">
    <div class="container content-section bg-white my-5 p-2">
        <div class="row p-4">
            <div class="col-md-8 mx-auto d-flex justify-content-center">
        <h3>Bài trắc nghiệm MBTI</h3>
    </div>
    </div>
    <div class="row p-4">
    <div class="col-md-10 mx-auto d-flex justify-content-center flex-row">
        <div class="d-flex flex-column font-weight-normal mr-auto">
            <div>HƯỚNG DẪN</div>
            <div>Đây là bài trắc nghiệm tính cách nên sẽ không có câu trả lời đúng hay câu trả lời sai</div>
            <div>Hãy chọn câu trả lời mà bạn cho là phù hợp hơn với bản thân mình</div>
            <div>Chỉ đọc câu trả lời và chọn, bạn không nên cố gắng phân tích quá nhiều các lựa chọn này vì làm như vậy sẽ khiến kết quả của bạn kém chính xác</div>
            <div>Đừng nhìn vào bảng điểm trước khi bạn hoàn thành tất cả câu hỏi</div>
        </div>
    </div>
    </div>
    <form id="mbtiTest">
        <div id="output"></div>
        <div class="subBtn row d-flex justify-content-center my-4">
            <button type="submit" class="px-4 font-weight-bold btn btn-primary">Submit Answer</button>
        </div>
    </form>
</div>
<script>
    let subject_id = '';
    fetch('/api/get-questions')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        subject_id = data.data.subject_id;
        let output = '';
        data.data.questions.forEach((question)=>{
            output += `
            <div class="row p-2">
                <div class="col-md-10 mx-auto d-flex">
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex flex-row">
                            <div class="mx-2">Câu ${question.index_number}:</div>
                            <div>${question.title}</div>
                        </div>
                        <div class="d-flex flex-row my-2">
                            <div class="col-md-6">
                                <label class="form-check-label font-weight-normal">
                                    <input class="form-check-input radio-inline" type="radio" name="gridRadios${question.index_number}" value="A" required>
                                    a. ${question.answer_a}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check-label font-weight-normal">
                                    <input class="form-check-input radio-inline" type="radio" name="gridRadios${question.index_number}" value="B" required>
                                    b. ${question.answer_b}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        });
        document.getElementById('output').innerHTML = output;
    })
    .catch((error) => {
        console.error(error);
    });
    
    document.getElementById('mbtiTest').addEventListener('submit', submitAnswers);
    function submitAnswers(e){
        e.preventDefault();
        let ele = document.getElementsByTagName('input');
        let answer_result = {};
        for(i=0;i<ele.length;i++){
            if(ele[i].type=="radio" && ele[i].checked){
                let index = parseInt(ele[i].name.match(/\d+/));
                answer_result[index] = ele[i].value;
            }
        }
        let localData = JSON.parse(localStorage.getItem('localData'));
        let data = {
            "candidate_id": localData['candidate_id'], 
            "subject_id": subject_id, 
            "answer_result": answer_result,
            "token_key": localData['token_key']
        };
        fetch('/api/submit-answers', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then((response)=>{
            if (response.status == 200) {
                window.location.href = "/result";
                return response.json();
            } else {
                return response.json();
                throw `Error Status ${response.status}`;
            }
        })
        .then((data) => {
            console.log(data.message)
        })
        .catch((error) => console.error(error));
    }
</script>
@endsection
