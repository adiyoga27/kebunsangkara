<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityAction;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\WhatsappClick;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'user_id' => ['nullable', 'integer'],
            'action' => ['nullable', Rule::enum(ActivityAction::class)],
            'from' => ['nullable', 'date'],
            'until' => ['nullable', 'date'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        return view('admin.activity-logs.index', [
            'logs' => ActivityLog::query()
                ->with('user')
                ->when($filters['user_id'] ?? null, fn ($query, int $userId) => $query->where('user_id', $userId))
                ->when($filters['action'] ?? null, fn ($query, string $action) => $query->where('action', $action))
                ->when($filters['from'] ?? null, fn ($query, string $from) => $query->where('created_at', '>=', $from))
                ->when($filters['until'] ?? null, fn ($query, string $until) => $query->where('created_at', '<', now()->parse($until)->addDay()->startOfDay()))
                ->when($filters['q'] ?? null, fn ($query, string $search) => $query->where(
                    fn ($query) => $query->where('description', 'like', "%{$search}%")->orWhere('ip_address', 'like', "%{$search}%")
                ))
                ->latest('id')
                ->paginate(30)
                ->withQueryString(),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'actions' => ActivityAction::cases(),
            'filters' => $filters,
        ]);
    }

    public function whatsappClicks(): View
    {
        return view('admin.activity-logs.whatsapp-clicks', [
            'clicks' => WhatsappClick::query()->with('product')->latest('id')->paginate(30),
        ]);
    }
}
