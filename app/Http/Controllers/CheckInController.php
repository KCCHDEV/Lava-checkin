<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckIn;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckInController extends Controller
{
    /**
     * Display a listing of check-ins.
     */
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $status = $request->get('status');
        
        $query = CheckIn::with(['student', 'recorder'])
            ->whereDate('check_in_date', $date);
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $checkIns = $query->orderBy('check_in_time', 'desc')->paginate(20);
        
        $stats = [
            'total' => CheckIn::whereDate('check_in_date', $date)->count(),
            'present' => CheckIn::whereDate('check_in_date', $date)->where('status', 'present')->count(),
            'late' => CheckIn::whereDate('check_in_date', $date)->where('status', 'late')->count(),
            'students_total' => Student::count()
        ];
        
        return view('check-ins.index', compact('checkIns', 'date', 'status', 'stats'));
    }

    /**
     * Show the QR code generation form.
     */
    public function create()
    {
        return view('check-ins.create');
    }

    /**
     * Generate QR code for check-in.
     */
    public function generateQR(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string|max:255',
            'time_limit' => 'nullable|integer|min:1|max:3600', // Max 1 hour
            'note' => 'nullable|string|max:500'
        ]);

        $checkInCode = CheckIn::generateCheckInCode();
        $timeLimit = $request->get('time_limit', 600); // 10 minutes default
        
        // Store the QR code session data
        session([
            'qr_check_in_code' => $checkInCode,
            'qr_location' => $request->get('location'),
            'qr_time_limit' => $timeLimit,
            'qr_note' => $request->get('note'),
            'qr_created_at' => Carbon::now(),
            'qr_created_by' => auth()->id()
        ]);
        
        // Generate QR code URL
        $qrUrl = route('check-ins.scan', ['code' => $checkInCode]);
        
        return view('check-ins.qr-display', compact('checkInCode', 'qrUrl', 'timeLimit'));
    }

    /**
     * Show scan interface or process scan.
     */
    public function scan(Request $request, $code = null)
    {
        if (!$code) {
            // Show manual scan interface
            return view('check-ins.scan');
        }
        
        // Validate QR code
        $qrData = session('qr_check_in_code');
        if (!$qrData || $qrData !== $code) {
            return view('check-ins.scan-result', [
                'success' => false,
                'message' => 'รหัส QR ไม่ถูกต้องหรือหมดอายุแล้ว'
            ]);
        }
        
        // Check time limit
        $createdAt = session('qr_created_at');
        $timeLimit = session('qr_time_limit', 600);
        
        if (Carbon::now()->diffInSeconds($createdAt) > $timeLimit) {
            return view('check-ins.scan-result', [
                'success' => false,
                'message' => 'รหัส QR หมดอายุแล้ว กรุณาสร้างรหัสใหม่'
            ]);
        }
        
        // Show student selection form
        $students = Student::where('status', 'active')->orderBy('name')->get();
        return view('check-ins.student-select', compact('code', 'students'));
    }

    /**
     * Process check-in submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'check_in_code' => 'required|string',
            'location' => 'nullable|string|max:255'
        ]);

        // Validate QR code session
        $qrCode = session('qr_check_in_code');
        if (!$qrCode || $qrCode !== $request->check_in_code) {
            return response()->json([
                'success' => false,
                'message' => 'รหัส QR ไม่ถูกต้องหรือหมดอายุแล้ว'
            ], 400);
        }

        // Check if student already checked in today
        $existingCheckIn = CheckIn::where('student_id', $request->student_id)
            ->whereDate('check_in_date', Carbon::today())
            ->first();

        if ($existingCheckIn) {
            return response()->json([
                'success' => false,
                'message' => 'นักเรียนคนนี้เช็คชื่อวันนี้แล้ว'
            ], 400);
        }

        // Determine status (present or late)
        $currentTime = Carbon::now();
        $cutoffTime = Carbon::today()->setTime(8, 30); // 8:30 AM cutoff
        $status = $currentTime->gt($cutoffTime) ? 'late' : 'present';

        // Create check-in record
        $checkIn = CheckIn::create([
            'student_id' => $request->student_id,
            'recorded_by' => session('qr_created_by'),
            'check_in_code' => $request->check_in_code,
            'check_in_time' => $currentTime,
            'check_in_date' => Carbon::today(),
            'status' => $status,
            'location' => $request->location ?: session('qr_location'),
            'device_info' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'note' => session('qr_note'),
            'is_valid' => true
        ]);

        // Clear cache
        cache()->forget('today_checkins_stats');
        cache()->forget('recent_checkins');

        $student = Student::find($request->student_id);
        
        return response()->json([
            'success' => true,
            'message' => "เช็คชื่อสำเร็จ: {$student->name} ({$checkIn->status_text})",
            'data' => [
                'student_name' => $student->name,
                'student_code' => $student->student_code,
                'check_in_time' => $checkIn->check_in_time->format('H:i:s'),
                'status' => $checkIn->status_text,
                'status_color' => $checkIn->status_color
            ]
        ]);
    }

    /**
     * Manual check-in interface for teachers/admins.
     */
    public function manual()
    {
        $students = Student::where('status', 'active')->orderBy('name')->get();
        $date = Carbon::today()->format('Y-m-d');
        
        return view('check-ins.manual', compact('students', 'date'));
    }

    /**
     * Store manual check-in.
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'check_in_date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i',
            'status' => 'required|in:present,late',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500'
        ]);

        // Check if student already checked in on this date
        $existingCheckIn = CheckIn::where('student_id', $request->student_id)
            ->whereDate('check_in_date', $request->check_in_date)
            ->first();

        if ($existingCheckIn) {
            return redirect()->back()->with('error', 'นักเรียนคนนี้เช็คชื่อในวันนี้แล้ว');
        }

        $checkInDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->check_in_date . ' ' . $request->check_in_time);

        CheckIn::create([
            'student_id' => $request->student_id,
            'recorded_by' => auth()->id(),
            'check_in_code' => CheckIn::generateCheckInCode(),
            'check_in_time' => $checkInDateTime,
            'check_in_date' => $request->check_in_date,
            'status' => $request->status,
            'location' => $request->location,
            'device_info' => 'Manual Entry',
            'ip_address' => $request->ip(),
            'note' => $request->note,
            'is_valid' => true
        ]);

        return redirect()->route('check-ins.index')
            ->with('success', 'เพิ่มข้อมูลการเช็คชื่อเรียบร้อยแล้ว');
    }

    /**
     * Show check-in details.
     */
    public function show(CheckIn $checkIn)
    {
        $checkIn->load(['student', 'recorder']);
        return view('check-ins.show', compact('checkIn'));
    }

    /**
     * Delete check-in record.
     */
    public function destroy(CheckIn $checkIn)
    {
        $checkIn->delete();
        
        cache()->forget('today_checkins_stats');
        cache()->forget('recent_checkins');
        
        return redirect()->route('check-ins.index')
            ->with('success', 'ลบข้อมูลการเช็คชื่อเรียบร้อยแล้ว');
    }

    /**
     * Export check-ins data.
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        
        $checkIns = CheckIn::with(['student', 'recorder'])
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->orderBy('check_in_time', 'desc')
            ->get();

        $filename = "check_ins_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($checkIns) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'รหัสนักเรียน',
                'ชื่อนักเรียน',
                'วันที่เช็คชื่อ',
                'เวลาเช็คชื่อ',
                'สถานะ',
                'สถานที่',
                'ผู้บันทึก',
                'หมายเหตุ'
            ]);

            foreach ($checkIns as $checkIn) {
                fputcsv($file, [
                    $checkIn->id,
                    $checkIn->student->student_code ?? '',
                    $checkIn->student->name ?? '',
                    $checkIn->check_in_date->format('Y-m-d'),
                    $checkIn->check_in_time->format('H:i:s'),
                    $checkIn->status_text,
                    $checkIn->location ?? '',
                    $checkIn->recorder->name ?? '',
                    $checkIn->note ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
