@extends('layouts.app')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Latest Adverts</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Example advert card -->
    <div class="border p-4 rounded">
      <h2 class="font-semibold">Sample Car For Sale</h2>
      <p class="text-sm text-gray-600">Lagos · ₦1,500,000</p>
      <a href="#" class="text-indigo-600">View</a>
    </div>
  </div>
@endsection
