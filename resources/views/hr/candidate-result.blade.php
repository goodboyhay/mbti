@extends('layouts.hr')
@section('title', 'Candidate Result')
@section('content')
<div class="row">
    <div id="resultContainer" class="container content-section bg-white my-5 p-2">
        <div class="row p-4">
            <div class="col-md-10 col-sm-12 col-12 mx-auto">
                <div class="d-flex flex-row justify-content-center">
                    <div class="d-flex justify-content-center flex-column mx-4">
                        <div class="d-flex flex-row ">
                            <div class="text-head"><i class="bi bi-person-bounding-box"></i> Name:</div>
                            <div class="blue-text" id="name"></div>
                        </div>
                        <div class="d-flex flex-row font-weight-normal">
                            <div class="text-head"><i class="bi bi-mailbox"></i> Email:</div>
                            <div class="blue-text" id="email"></div>
                        </div>
                        <div class="d-flex flex-row font-weight-normal">
                            <div class="text-head"><i class="bi bi-calendar2-week"></i> Birthday:</div>
                            <div class="blue-text" id="dob"></div>
                        </div>
                        <div class="d-flex flex-row font-weight-normal">
                            <div class="text-head"><i class="bi bi-info-square"></i> Position:</div>
                            <div class="blue-text" id="position"></div>
                        </div>
                    </div>
                    <div class="d-flex flex-column ml-auto mr-1">
                        <a type="button" id="printResult" class="btn btn-primary rounded-pill font-weight-bold">Export <img src="{{asset('../resources/img/pdf.svg')}}" alt="pdf-icon"></a>
                        <a href="{{route('hr.candidate-answer', $candidate_id)}}" type="button" name="" id="viewSurvey" class="btn btn-warning my-2 rounded-pill font-weight-bold">View survey</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-12 mx-auto d-flex justify-content-center">
                <h3 class="mbti-result" id="name_mbti"></h3>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-12 mx-auto p-2">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-12 mx-auto">
                        <div class="d-flex flex-column font-weight-normal">
                            <div class="d-flex flex-row my-2">
                                <div class="px-2">
                                    <img src="{{asset('./resources/img/star.svg')}}" height="16" alt="star">
                                </div>
                                <div class="text-head">Kết quả:</div>
                                <div id="n_mbti"></div>
                            </div>
                            <div class="d-flex flex-row my-2">
                                <div class="px-2">
                                    <img src="{{asset('./resources/img/suitcase.svg')}}" height="16" alt="star">
                                </div>
                                <div class="text-head">Vị trí phù hợp:</div>
                                <div class="d-flex flex-column">
                                    <div id="position_mbti">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12 mx-auto">
                        <div class="d-flex">
                            <canvas id="myChart"></canvas>
                            <img id="chartJpg" width="300" height="300" style="display:none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-12">
                <!-- Sample Text Here, just replace it with your description-->
                <div class="row py-2">
                    <div class="col-md-12">
                        <span class="text-head">TỔNG QUAN <br></span>
                        <div class="font-weight-normal" id="overview"></div> 
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-6 col-sm-12 col-12 float-left">
                        <span class="text-head">ƯU ĐIỂM <br></span>
                        <div class="font-weight-normal" id="advantages"></div> 
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <span class="text-head">NHƯỢC ĐIỂM <br></span>
                        <div class="font-weight-normal" id="weakness"></div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-12">
                        <span class="text-head">PHÙ HỢP VỚI NGÀNH NGHỀ NÀO?<br></span>
                        <div class="font-weight-normal" id="suitable_jobs"></div>
                    </div>
                </div>
                <!-- Sample Text End -->
            </div>
        </div>
    </div>
</div>
<script>
let candidate_id = location.pathname.split('/')[2];
hrToken = JSON.parse(localStorage.getItem('hrToken'));
fetch(`/api/candidate-detail/${candidate_id}`,{
    method: 'GET',
    headers: {
        'Accept': 'application/json, text/plain, */*',
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + hrToken['token'],
    },
})
.then((response)=> {
    if (response.status == 200) {
        return response.json();
    } else {
        throw `Error Status ${response.status}`;
    }
})
.then(data => {
    document.getElementById('name').innerHTML=data.data.name;
    document.getElementById('email').innerHTML=data.data.email;
    document.getElementById('dob').innerHTML=data.data.dob;
    document.getElementById('position').innerHTML=data.data.position;
    document.getElementById('name_mbti').innerHTML=data.data.mbti_result + ' - '+ data.data.nickname;
    document.getElementById('n_mbti').innerHTML=data.data.mbti_result;
    let suitable_job = data.data.suitable_jobs.split(';');
    let outputJobs = '';
    for(i=1;i<suitable_job.length;i++){
        outputJobs += `${suitable_job[i]} <br>`
    }
    document.getElementById('position_mbti').innerHTML=outputJobs
    document.getElementById('overview').innerHTML=data.data.overview;
    let advantages = data.data.advantages.split('–');
    let outputAdvantages = '';
    for(i=1;i<advantages.length;i++){
        outputAdvantages += `- ${advantages[i]} <br>`
    }
    document.getElementById('advantages').innerHTML=outputAdvantages;
    let weakness = data.data.weakness.split('–');
    let outputWeakness = '';
    for(i=1;i<weakness.length;i++){
        outputWeakness += `- ${weakness[i]} <br>`
    }
    document.getElementById('weakness').innerHTML=outputWeakness;
    document.getElementById('suitable_jobs').innerHTML=suitable_job;
    let summaryData = JSON.parse(data.data.summary);
    // Radar Chart
    const chartData = {
        labels: [
            'Feeling',
            'Sensing',
            'Judging',
            'Introversion',
            'Thingking',
            'Intuition',
            'Perception',
            'Extroversion'
        ],
        datasets: [{
            label: 'MBTI Result',
            data: [
                summaryData['F']*10, 
                summaryData['S']*10, 
                summaryData['J']*10, 
                summaryData['I']*10,
                summaryData['T']*10, 
                summaryData['N']*10, 
                summaryData['P']*10,
                summaryData['E']*10
            ],
            fill: true,
            backgroundColor: 'rgba(6, 100, 168, 0.2)',
            borderColor: 'rgb(6, 100, 168)',
            pointBackgroundColor: 'rgb(6, 100, 168)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(6, 100, 168)'
        }]
    };
    let ctx = document.getElementById('myChart').getContext('2d');
    function renderImg(){
        let url= myRadarChart.toBase64Image();
        document.getElementById("chartJpg").src=url;
    }
    let myRadarChart = new Chart(ctx, {
        type: 'radar',
        data: chartData,
        options: {
            scale: {
                min: 0,
                max: 100,
            },
            animation: {
                onComplete: renderImg
            },
            elements: {
                line: {
                    borderWidth: 3
                }
            },
        },
    });
})
.catch((error) => console.error(error));
document.getElementById('printResult').addEventListener('click', printDiv);
function printDiv() {
    document.getElementById('myChart').style.display = 'none';
    document.getElementById('printResult').style.display = 'none';
    document.getElementById('viewSurvey').style.display = 'none';
    document.getElementById('chartJpg').style.display = 'block';
    let divContents = document.getElementById("resultContainer").innerHTML;
    let a = window.open('', '', 'height=500, width=500');
    a.document.write('<html><head><title>Candidate Result</title>');
    a.document.write('<link rel="stylesheet" href="../css/style.css" type="text/css"/>');
    a.document.write('<link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>');
    a.document.write('</head><body>');
    a.document.write(divContents);
    a.document.write('</body></html>');
    setTimeout(function () {
        a.print();
        a.close();
    }, 1000)
    document.getElementById('myChart').style.display = 'block';
    document.getElementById('printResult').style.display = 'block';
    document.getElementById('viewSurvey').style.display = 'block';
    document.getElementById('chartJpg').style.display = 'none';
    return true;
}
</script>
@endsection
