@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">

                <div class="card-body">
                    <h2 class="">
                        <i class="far fa-edit"></i>
                        Edit topic
                    </h2>

                    <hr>

                    <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="PUT">


                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @include('shared._error')

                        <div class="mb-3">
                            <label for="title"></label>
                            <input class="form-control" type="text" id="title" name="title"
                                   value="{{ old('title', $topic->title) }}"
                                   placeholder="Please enter a title." required/>
                        </div>

                        <div class="mb-3">
                            <label for="category_id"></label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="" hidden disabled selected>Please select a category.</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $topic->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editor"></label><textarea name="body" class="form-control" id="editor" rows="6"
                                                                  required>{{ old('body', $topic->body) }}</textarea>
                        </div>

                        <div class="well well-sm">
                            <button type="submit" class="btn btn-primary"><i class="far fa-save mr-2"
                                                                             aria-hidden="true"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>

    <script>
        $(document).ready(function () {
            const editor = new Simditor({
                textarea: $('#editor'),
                upload: {
                    url: '{{ route('topics.upload_image') }}',
                    placeholder: 'Please enter at least 3 characters.',
                    toolbar: ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', 'ol', 'ul', 'blockquote', 'code', 'table', 'link', 'image', 'hr', 'indent', 'outdent', 'alignment'],
                    codeLanguages:
                        [
                            {name: 'Bash', value: 'bash'},
                            {name: 'C++', value: 'c++'},
                            {name: 'C#', value: 'cs'},
                            {name: 'CSS', value: 'css'},
                            {name: 'Erlang', value: 'erlang'},
                            {name: 'Less', value: 'less'},
                            {name: 'Sass', value: 'sass'},
                            {name: 'Diff', value: 'diff'},
                            {name: 'CoffeeScript', value: 'coffeescript'},
                            {name: 'HTML,XML', value: 'html'},
                            {name: 'JSON', value: 'json'},
                            {name: 'Java', value: 'java'},
                            {name: 'JavaScript', value: 'js'},
                            {name: 'Markdown', value: 'markdown'},
                            {name: 'Objective C', value: 'oc'},
                            {name: 'PHP', value: 'php'},
                            {name: 'Perl', value: 'parl'},
                            {name: 'Python', value: 'python'},
                            {name: 'Ruby', value: 'ruby'},
                            {name: 'SQL', value: 'sql'},
                        ],
                    params: {
                        _token: '{{ csrf_token() }}'
                    },
                    fileKey: 'upload_file',
                    connectionCount: 3,
                    leaveConfirm: 'Uploading is in progress, are you sure to leave this page?',
                },
                pasteImage: true,
            });
        })
    </script>
@stop
