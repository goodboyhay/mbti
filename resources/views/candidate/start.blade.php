@extends('layouts.candidate')
@section('title', 'Candidate Roll-in')
@section('content')
<div class="d-flex flex-column float-right card"><h1>MBTI PERSONALITY TEST</h1>
	<div class="d-flex p-2">
		Welcome to the MBTI survey of Hybrid Technologies!</p>
	</div>
	<div class="d-flex p-2">
		<p>The MBTI (Myers-Briggs Type Indicator) personality test is a method that uses a series of multiple-choice questions to analyze a person's personality. MBTI test results show how people perceive the world around them and make decisions for all issues in life.</p>
	</div>
	<div class="d-flex p-2">
		<p>Currently, MBTI is widely used as a fairly accurate method of personality classification. At work, MBTI helps us to have more information to choose a more accurate career, with employers also able to use MBTI to assess the candidate's personality fit for the job as well as the environment. corporate workplace.</p>
	</div>
	<a href="{{route('candidate.info')}}" type="button" class="btn btn-primary d-flex justify-content-center d-md-table mx-auto font-weight-bold">Start Survey</a>
</div>  
@endsection
