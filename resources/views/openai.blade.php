@extends('layouts.app')
@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/highlight/highlight.dark.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
	
<iframe class="open_ai_wr"  height="650" width="100%" src="/openai" title="OPEN AI"></iframe>
	

@endsection

@section('js')
@endsection