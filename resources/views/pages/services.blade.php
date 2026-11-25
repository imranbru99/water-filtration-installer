@extends('layouts.app')
@section('title', 'Water Filtration Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Crestpure designs and installs whole-home filtration, reverse osmosis, softeners, UV, and commercial point-of-use systems across the Denver metro.</p>
        <div class="grid-3">
            @foreach([
                ['whole-home-filtration','Whole-Home Filtration','https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1200&q=80'],
                ['reverse-osmosis','Reverse Osmosis','https://images.unsplash.com/photo-1523362628745-0c100150b504?auto=format&fit=crop&w=1200&q=80'],
                ['water-softeners','Water Softeners','https://images.unsplash.com/photo-1564419320461-6870880221ad?auto=format&fit=crop&w=1200&q=80'],
                ['uv-sterilization','UV Sterilization','https://images.unsplash.com/photo-1611270629569-8b357cb88da9?auto=format&fit=crop&w=1200&q=80'],
                ['under-sink-filters','Under-Sink Filters','https://images.unsplash.com/photo-1556912173-46c336c7fd55?auto=format&fit=crop&w=1200&q=80'],
                ['commercial-filtration','Commercial Filtration','https://images.unsplash.com/photo-1495774539583-88580d595554?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
