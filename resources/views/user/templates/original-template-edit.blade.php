@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{ URL::asset('plugins/datatable/datatables.min.css') }}" rel="stylesheet" />
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7 justify-content-center">
    <div class="page-leftheader text-center">
        <h4 class="page-title mb-0">{{ __('Edit Custom Template') }}</h4>
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i>{{ __('User') }}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.templates') }}"> {{ __('Templates') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Edit Custom Template') }}</a></li>
        </ol>
    </div>
</div>
<!-- END PAGE HEADER -->
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12 col-sm-12">
        <div class="card border-0">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-microchip-ai mr-2 text-primary"></i>{{ __('Custom Template Editor') }}</h3>
            </div>
            <div class="card-body pt-5">
                <form class="mt-3" action="{{ route('user.original-templates.custom.update', $id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12">
                            <h6>{{ __('Additional Fields') }}</h6>
                            <div id="dynamic-fields-container">
                                @if(old('dynamic_field'))
                                    @foreach(old('dynamic_field') as $key => $value)
                                        <div class="form-group dynamic-field" data-index="{{ $key }}">
                                            <input type="text" name="dynamic_field[]" class="form-control mt-2" value="{{ $value }}" placeholder="Enter value" />
                                            <button type="button" class="btn btn-danger mt-2" onclick="removeField(this)">Remove</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-primary mt-3" onclick="addField()">Add Field</button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Other form fields remain unchanged -->
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="input-box">
                                <h6>{{ __('Template Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                <div class="form-group">
                                    <input type="text" class="form-control @error('name') is-danger @enderror" id="name" name="name" value="{{ $id->name }}">
                                    @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Other form fields remain unchanged -->
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="input-box">
                                <h6>{{ __('Template Description') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                <div class="form-group">
                                    <input type="text" class="form-control @error('description') is-danger @enderror" id="description" name="description" value="{{ $id->description }}">
                                    @error('description')
                                        <p class="text-danger">{{ $errors->first('description') }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Other form fields remain unchanged -->
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="input-box">
                                <h6>{{ __('Template Category') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                <select id="image-feature-user" name="category" class="form-select" data-placeholder="{{ __('Select template category') }}">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->code }}" @if ($id->group == $category->code) selected @endif>{{ __(ucfirst($category->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="input-box">
                                <h6>{{ __('Template Icon') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You will need to get it from fontawesome.com') }}"></i></h6>
                                <div class="form-group">
                                    <input type="text" class="form-control @error('icon') is-danger @enderror" id="icon" name="icon" value="{{ $id->icon }}">
                                    @error('icon')
                                        <p class="text-danger">{{ $errors->first('icon') }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-6 col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label class="custom-switch">
                                    <input type="checkbox" name="activate" class="custom-switch-input" @if($id->status) checked @endif>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('Activate Template') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 mt-4 mb-5">
                            <div class="form-group">
                                <h6 class="fs-11 mb-2 font-weight-semibold">{{ __('User Input Fields') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                @foreach ($id->fields as $key => $value)
                                    <div class="field input-group mb-4" id="field-input-{{ $key + 1 }}">
                                        <input type="hidden" name="code[]" value="{{ $value['code'] }}">
                                        <input type="text" class="form-control" name="names[]" placeholder="{{ __('Enter Input Field Title (Required)') }}" value="{{ $value['name'] }}">
                                        @if (in_array($value['input'], ['input', 'textarea']))
                                            <input type="text" class="form-control" name="placeholders[]" placeholder="{{ __('Enter Input Field Description') }}" value="{{ $value['placeholder'] }}">
                                        @else
                                            <input type="text" class="form-control" name="placeholders[]" placeholder="{{ __('Enter Comma-separated Values') }}" value="{{ implode(', ', $value['placeholder']) }}">
                                        @endif
                                        <input type="number" class="form-control" name="length[]" placeholder="{{ __('Max Length') }}" value="{{ $value['length'] }}">
                                        <select class="form-select mr-4" name="input_field[]" onchange="notifyUser(this)">
                                            <option value="input" @if($value['input'] == 'input') selected @endif>{{ __('Input Field') }}</option>
                                            <option value="textarea" @if($value['input'] == 'textarea') selected @endif>{{ __('Textarea Field') }}</option>
                                            <option value="select" @if($value['input'] == 'select') selected @endif>{{ __('Select List Field') }}</option>
                                            <option value="checkbox" @if($value['input'] == 'checkbox') selected @endif>{{ __('Checkbox List Field') }}</option>
                                            <option value="radio" @if($value['input'] == 'radio') selected @endif>{{ __('Radio Buttons Field') }}</option>
                                        </select>
                                        <select class="form-select" name="status_field[]">
                                            <option value="0" @if($value['required'] == 0) selected @endif>{{ __('Optional') }}</option>
                                            <option value="1" @if($value['required'] == 1) selected @endif>{{ __('Required') }}</option>
                                        </select>
                                        <button type="button" onclick="addField()" class="btn btn-primary">
                                            <i class="fa fa-btn fa-plus"></i>
                                        </button>
                                        <button type="button" onclick="removeField(this)" class="btn btn-danger">
                                            <i class="fa fa-btn fa-minus"></i>
                                        </button>
                                    </div>
                                @endforeach

                                <div id="field-container"></div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-box">
                                <h6>{{ __('Template Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                                <textarea class="form-control @error('feature') is-danger @enderror" name="feature" rows="12">{{ $id->feature }}</textarea>
                                @error('feature')
                                    <p class="text-danger">{{ $errors->first('feature') }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-0 text-right mb-2 mt-1">
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <!-- Data Table JS -->
    <script src="{{ URL::asset('plugins/datatable/datatables.min.js') }}"></script>
    <!-- Sweet Alert JS -->
    <script src="{{ URL::asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>

    <!-- Custom Script -->
    <script>
        let fieldIndex = @if(old('dynamic_field')) {{ count(old('dynamic_field')) }} @else 0 @endif;

        function addField() {
            fieldIndex++;
            const fieldHTML = `
                <div class="form-group dynamic-field" data-index="${fieldIndex}">
                    <input type="text" name="dynamic_field[]" class="form-control mt-2" placeholder="Enter value" />
                    <button type="button" class="btn btn-danger mt-2" onclick="removeField(this)">Remove</button>
                </div>`;
            document.getElementById('dynamic-fields-container').insertAdjacentHTML('beforeend', fieldHTML);
        }

        function removeField(button) {
            const fieldElement = button.closest('.dynamic-field');
            if (fieldElement) {
                fieldElement.remove();
            }
        }
    </script>
@endsection
