@extends('layouts.app')

@section('content')
  <h1 class="text-2xl font-bold">{{ \$advert->title }}</h1>
  <p class="text-gray-700">{{ \$advert->description }}</p>
  <p class="mt-4 font-semibold">Price: ₦{{ \$advert->price }}</p>
@endsection
