<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request): View
    {
        return view('admin.articles.index', [
            'articles' => Article::query()
                ->with('author')
                ->when($request->string('q')->trim()->value(), fn ($query, string $search) => $query->where('title', 'like', "%{$search}%"))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.form', ['article' => new Article(['is_published' => true, 'published_at' => now()])]);
    }

    public function store(SaveArticleRequest $request): RedirectResponse
    {
        Article::create([
            ...$this->attributes($request),
            'user_id' => $request->user()->getKey(),
            'image_path' => $this->resolveImagePath($request, 'articles'),
        ]);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.form', ['article' => $article]);
    }

    public function update(SaveArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update([
            ...$this->attributes($request),
            'image_path' => $this->resolveImagePath($request, 'articles', $article->image_path),
        ]);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();
        $this->deleteImage($article->image_path);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(SaveArticleRequest $request): array
    {
        $attributes = $request->safe()->except(['image', 'remove_image']);

        if ($attributes['is_published'] && empty($attributes['published_at'])) {
            $attributes['published_at'] = now();
        }

        return $attributes;
    }
}
