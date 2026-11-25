<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Crestpure will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'whole-home-filtration' => [
                'title' => 'Whole-Home Filtration',
                'summary' => 'Point-of-entry sediment and carbon systems that protect every faucet, shower, and appliance.',
                'body' => 'Crestpure designs pressure-balanced whole-home filtration with proper bypass valves, media sizing, and chlorine/sediment reduction for Front Range municipal and well supplies. We plumb cleanly, commission flow rates, and leave filter-change schedules so every tap tastes better — not just the kitchen sink.',
                'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1600&q=80',
            ],
            'reverse-osmosis' => [
                'title' => 'Under-Sink Reverse Osmosis',
                'summary' => 'Multi-stage RO for drinking, cooking, and ice makers with remineralization options.',
                'body' => 'From compact under-sink RO to permeate-pump upgrades, we install membranes matched to your TDS, add dedicated faucets, and route drain lines to code. Ideal when you want bottled-water taste without the plastic waste — Crestpure commissions storage tanks and leak detectors on every install.',
                'image' => 'https://images.unsplash.com/photo-1523362628745-0c100150b504?auto=format&fit=crop&w=1600&q=80',
            ],
            'water-softeners' => [
                'title' => 'Water Softeners',
                'summary' => 'Ion-exchange softeners sized for Colorado hardness so scale stops coating fixtures and heaters.',
                'body' => 'We meter softener capacity to household occupancy and grains-per-gallon, set salt efficiency, and plumb outdoor hose bypasses when needed. Soft water means cleaner glassware, longer-lasting water heaters, and soap that finally rinses — without guessing tank size from a flyer.',
                'image' => 'https://images.unsplash.com/photo-1564419320461-6870880221ad?auto=format&fit=crop&w=1600&q=80',
            ],
            'uv-sterilization' => [
                'title' => 'UV Sterilization',
                'summary' => 'Ultraviolet disinfection for wells and private systems that need microbial protection.',
                'body' => 'Crestpure sizes UV chambers to peak flow, installs pre-filters so lamps stay effective, and sets lamp-change reminders. Pair UV with sediment and carbon when well reports show bacteria risk — we document as-built drawings for inspectors and homeowners alike.',
                'image' => 'https://images.unsplash.com/photo-1611270629569-8b357cb88da9?auto=format&fit=crop&w=1600&q=80',
            ],
            'under-sink-filters' => [
                'title' => 'Under-Sink & Point-of-Use Filters',
                'summary' => 'Compact carbon and specialty cartridges for kitchens, bars, and offices.',
                'body' => 'Not every home needs a full softener. We install under-sink carbon blocks, specialty arsenic/fluoride cartridges, and fridge-line kits with clean shutoffs. Quick installs, clear change schedules, and no wasted whole-home capacity when you only need better drinking water.',
                'image' => 'https://images.unsplash.com/photo-1556912173-46c336c7fd55?auto=format&fit=crop&w=1600&q=80',
            ],
            'commercial-filtration' => [
                'title' => 'Commercial & Multi-Unit Filtration',
                'summary' => 'Scalable POE and POU systems for cafes, clinics, offices, and small multifamily.',
                'body' => 'We phase installs around business hours, specify commercial valves and dual-tank setups, and document service intervals for facilities teams. Property managers get clear filter maps, invoice history, and seasonal media change plans through the Crestpure ERP.',
                'image' => 'https://images.unsplash.com/photo-1495774539583-88580d595554?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
