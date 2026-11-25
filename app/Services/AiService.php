<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'how long install timeline schedule',
            'a' => 'Most whole-home filtration or softener installs take 3–6 hours depending on plumbing access and bypass complexity. Under-sink RO is often same-day. Crestpure typically books free water tests within 2–3 business days across Denver and the Front Range.',
        ],
        [
            'q' => 'cost price estimate quote filtration softener',
            'a' => 'Whole-home filtration in the Denver metro often ranges from about $1,200–$4,500+ depending on media and flow rate. Softeners and RO add-ons vary with capacity and stages. Request a free water test for an exact written quote.',
        ],
        [
            'q' => 'hard water scale soap softener grains',
            'a' => 'Front Range hardness commonly leaves white scale on fixtures and film on glassware. We size ion-exchange softeners to your grains-per-gallon and household occupancy, then set salt efficiency so you are not over-regenerating.',
        ],
        [
            'q' => 'reverse osmosis ro drinking under sink',
            'a' => 'Crestpure installs multi-stage under-sink RO with dedicated faucets, optional remineralization, and leak detectors. Ideal for cooking, ice makers, and tasting when municipal chlorine or high TDS bothers you.',
        ],
        [
            'q' => 'uv well bacteria sterilization lamp',
            'a' => 'UV disinfection is common on private wells. We size chambers to peak flow, add pre-filters so lamps stay effective, and schedule annual lamp changes. Pair UV with sediment/carbon when lab reports show microbial risk.',
        ],
        [
            'q' => 'filter change cartridge media maintenance',
            'a' => 'Sediment and carbon cartridges typically change every 6–12 months; RO membranes last longer depending on TDS. Crestpure logs change schedules in the ERP and can text reminders so pressure and taste stay consistent.',
        ],
        [
            'q' => 'schedule appointment water test contact',
            'a' => 'Call or text (303) 555-0148, use the contact form, or WhatsApp us. Free water tests for Denver, Boulder, Aurora, Highlands Ranch, and nearby Front Range cities are usually available within 2–3 business days.',
        ],
        [
            'q' => 'chlorine taste smell municipal tap',
            'a' => 'Chlorine taste and odor are common on municipal supplies. Whole-home catalytic carbon or under-sink carbon blocks remove the smell at the tap. We test free chlorine and recommend POE vs POU based on how many fixtures you want treated.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a water filtration installer about: {$prompt}",
            'description' => "Write a compelling product/service description for water filtration systems: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a water filtration SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this water filtration operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Crestpure Water Systems. We design and install whole-home filtration, reverse osmosis, softeners, and UV systems across Denver and the Colorado Front Range. Ask about pricing, hard water, RO, UV, or scheduling—or call (303) 555-0148 for a free water test.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Crestpure Water Systems assistant for a Denver, CO water filtration installer. Be concise, practical, and focused on whole-home filters, reverse osmosis, softeners, UV, and maintenance. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are Crestpure Water Systems assistant (Denver water filtration). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
