<x-layouts.app>
@if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
@php
    $counter=0;
@endphp
<div class="card">
    <div class="card-header">
        <h5>IMPORT LIST OF COUNTRIES</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('importcountry') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="countrylist" class="form-control">
    <button type="submit" class="btn btn-primary my-3">Import</button>
    </form>

    </div>
    <div class="card-body responsive">
        <table class="table table-striped">
            <thead>
                <th>Country</th>
                <th>Abbreviation</th>
                <th>Region</th>
                <th>Action</th>
            </thead>
            <tbody>
                @forelse ($countries as $country )
                <tr>
                    <td>{{$country->name}}</td>
                    <td>{{$country->abbrevation}}</td>
                    <td>{{$country->region}}</td>
                    <td></td>
                </tr>
                @empty
                <tr>
                     <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-layouts.app>