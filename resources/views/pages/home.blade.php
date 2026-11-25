@extends('layouts.app')

@section('title', 'Crestpure Water Systems')
@section('meta_description', 'Premium whole-home water filtration, reverse osmosis, softeners, and UV systems installed across Denver and the Colorado Front Range by Crestpure Water Systems.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Crestpure<br>Water Systems</h1>
        <p>Crystal-clear drinking water. Softer showers. Point-of-entry protection sized for Front Range hardness, sediment, and municipal chlorine — installed by certified Denver crews.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a free water test</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">Explore filtration systems</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Filtration systems</h2>
        <p class="lede">Whole-home, under-sink RO, softeners, and UV — engineered for Colorado water chemistry, not generic big-box kits.</p>
        <div class="grid-3">
            @foreach([
                ['whole-home-filtration','Whole-Home Filtration','Sediment + carbon POE systems that protect every faucet.','https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1200&q=80'],
                ['reverse-osmosis','Reverse Osmosis','Under-sink RO for tasting, cooking, and ice makers.','https://images.unsplash.com/photo-1523362628745-0c100150b504?auto=format&fit=crop&w=1200&q=80'],
                ['water-softeners','Water Softeners','Ion-exchange softeners sized for Front Range hardness.','https://images.unsplash.com/photo-1564419320461-6870880221ad?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Crestpure</h2>
        <p class="lede">Denver installers who treat water chemistry and plumbing access as non-negotiable — because filters fail when sizing is guessed.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Test-first design</h3>
                <p>Every quote starts with hardness, TDS, chlorine, and sediment notes — so media and flow rates match your actual water.</p>
            </div>
            <div class="panel">
                <h3>Right-sized media</h3>
                <p>POE carbon, softeners, RO membranes, and UV lamps selected for Front Range wells and municipal supplies.</p>
            </div>
            <div class="panel">
                <h3>ERP-run jobs</h3>
                <p>Quotes, installs, filter change reminders, and invoices live in one operations stack — fewer missed callbacks.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Crestpure ERP used to schedule installs, track cartridges, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>28</strong> Active jobs</div>
                    <div class="metric"><strong>$186k</strong> Pipeline</div>
                    <div class="metric"><strong>19</strong> Tests booked</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:85%"></span><span style="height:60%"></span><span style="height:95%"></span>
                    <span style="height:75%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & scheduling</li>
                    <li>Filters / media inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the lab bench</h2>
        <p class="lede">Guides and notes for homeowners dealing with hard water, taste, and scale.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
