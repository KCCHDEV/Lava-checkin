<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $blogs = Blog::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|in:news,academic,student-life,events,technology',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);

        // Handle tags
        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blogs', 'public');
            $data['featured_image'] = $imagePath;
        }

        // Set published_at if status is published
        if ($request->status === 'published' && !$request->published_at) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()->route('blogs.index')
            ->with('success', 'เพิ่มบทความเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $blog->load('author');
        return view('blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|in:news,academic,student-life,events,technology',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle tags
        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            
            $imagePath = $request->file('featured_image')->store('blogs', 'public');
            $data['featured_image'] = $imagePath;
        }

        // Set published_at if status is published and not already set
        if ($request->status === 'published' && !$request->published_at && !$blog->published_at) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return redirect()->route('blogs.index')
            ->with('success', 'อัปเดตบทความเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Delete featured image if exists
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', 'ลบบทความเรียบร้อยแล้ว');
    }

    /**
     * Public blog listing
     */
    public function publicIndex(Request $request)
    {
        $query = Blog::published()->with('author');

        // Filter by category
        if ($request->category) {
            $query->byCategory($request->category);
        }

        // Filter by search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $blogs = $query->orderBy('published_at', 'desc')->paginate(12);
        $featuredBlogs = Blog::published()->featured()->with('author')->take(3)->get();

        return view('blogs.public.index', compact('blogs', 'featuredBlogs'));
    }

    /**
     * Public blog detail
     */
    public function publicShow($slug)
    {
        $blog = Blog::published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $blog->incrementViewCount();

        // Get related blogs
        $relatedBlogs = Blog::published()
            ->where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->with('author')
            ->take(3)
            ->get();

        return view('blogs.public.show', compact('blog', 'relatedBlogs'));
    }

    /**
     * Toggle blog status
     */
    public function toggleStatus(Blog $blog)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $newStatus = $blog->status === 'published' ? 'draft' : 'published';
        $blog->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);

        return redirect()->back()
            ->with('success', 'อัปเดตสถานะบทความเรียบร้อยแล้ว');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Blog $blog)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $blog->update(['is_featured' => !$blog->is_featured]);

        return redirect()->back()
            ->with('success', 'อัปเดตสถานะบทความแนะนำเรียบร้อยแล้ว');
    }
}
