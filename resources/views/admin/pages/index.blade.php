@extends('layouts.admin')

@section('content')
  <div class="container">
      <a class="btn btn-primary" href="{{ route('pages.create') }}">New Page</a><hr>
      @if (Session::has('message'))
          <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif

      @if ( count($data) )
        <h3>List of Pages</h3>
        <table class="table">
          <thead class="thead-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Page Url</th>
              <th scope="col">Is Published?</th>
              <th scope="col">Created At</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $page)
            <tr>
              <th scope="row">{{$page->id}}</th>
              <td>{{$page->name}}</td>
              <td><a href="{{ URL::to($post->slug) }}">{{$post->slug}}</a></td>
              <th scope="row">{{$post->published ? 'Yes' : 'No'}}</th>
              <td>{{$page->created_at->toFormattedDateString()}}</td>
              <td>
                      <div class="btn-group" role="group" aria-label="Basic example">
                          <a href="{{ URL::to('pages/' . $page->id . '/edit') }}">
                          	<button type="button" class="btn btn-primary">Edit</button>
                          </a>&nbsp;
                          <form action="{{url('pages', [$page->id])}}" method="POST">
            					<input type="hidden" name="_method" value="DELETE">
           						<input type="hidden" name="_token" value="{{ csrf_token() }}">
           						<input type="submit" class="btn btn-danger" value="Delete"/>
           				  </form>
                      </div>
        			</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $data->links() }}
      @else 
        <div class="well">No records yet.</div>
      @endif
  </div>
@endsection