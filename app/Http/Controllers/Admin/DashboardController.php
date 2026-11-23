<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Product;
use App\Models\WhatsappClick;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $since = now()->subDays(30);

        $clicksPerDay = WhatsappClick::query()
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $chart = collect(range(13, 0))->map(function (int $daysAgo) use ($clicksPerDay): array {
            $day = now()->subDays($daysAgo);

            return ['label' => $day->translatedFormat('d M'), 'total' => (int) ($clicksPerDay[$day->toDateString()] ?? 0)];
        });

        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::query()->count(),
                'availableProducts' => Product::query()->where('is_available', true)->count(),
                'articles' => Article::query()->published()->count(),
                'clicks30Days' => WhatsappClick::query()->where('created_at', '>=', $since)->count(),
                'clicksToday' => WhatsappClick::query()->where('created_at', '>=', today())->count(),
            ],
            'chart' => $chart,
            'topProducts' => Product::query()
                ->withCount(['whatsappClicks' => fn ($query) => $query->where('created_at', '>=', $since)])
                ->orderByDesc('whatsapp_clicks_count')
                ->limit(5)
                ->get(),
            'recentActivity' => $request->user()->can('administer')
                ? ActivityLog::query()->with('user')->latest('id')->limit(8)->get()
                : $request->user()->activityLogs()->latest('id')->limit(8)->get(),
        ]);
    }
}
