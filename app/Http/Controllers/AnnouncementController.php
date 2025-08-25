<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AnnouncementController extends Controller
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

        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
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

        return view('announcements.create');
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
            'type' => 'required|in:general,important,event,academic',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
            $data['image_path'] = $imagePath;
        }

        // Set published_at if status is published
        if ($request->status === 'published' && !$request->published_at) {
            $data['published_at'] = now();
        }

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', 'เพิ่มประกาศเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $announcement->load('creator');
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,event,academic',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            
            $imagePath = $request->file('image')->store('announcements', 'public');
            $data['image_path'] = $imagePath;
        }

        // Set published_at if status is published and not already set
        if ($request->status === 'published' && !$request->published_at && !$announcement->published_at) {
            $data['published_at'] = now();
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', 'อัปเดตประกาศเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Delete image if exists
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }

    /**
     * Public announcement listing
     */
    public function publicIndex()
    {
        $announcements = Announcement::published()
            ->with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('announcements.public.index', compact('announcements'));
    }

    /**
     * Public announcement detail
     */
    public function publicShow($id)
    {
        $announcement = Announcement::published()
            ->with('creator')
            ->findOrFail($id);

        return view('announcements.public.show', compact('announcement'));
    }

    /**
     * Toggle announcement status
     */
    public function toggleStatus(Announcement $announcement)
    {
        // Check if user is admin or teacher
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $newStatus = $announcement->status === 'published' ? 'draft' : 'published';
        $announcement->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);

        return redirect()->back()
            ->with('success', 'อัปเดตสถานะประกาศเรียบร้อยแล้ว');
    }
}
