@extends('layouts.app')

@section('title', 'จัดการธีม - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'จัดการธีม')

@section('page-content')
<div class="row">
    <!-- Theme Selection -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-palette me-2"></i>เลือกธีม
                </h6>
                <button type="button" class="btn btn-secondary btn-sm" onclick="resetTheme()">
                    <i class="fas fa-undo me-2"></i>รีเซ็ตเป็นค่าเริ่มต้น
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($themes as $key => $theme)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="theme-card card h-100 {{ $currentTheme === $key ? 'border-primary' : '' }}" 
                             data-theme="{{ $key }}">
                            <div class="card-body p-3">
                                <div class="theme-preview mb-3" style="
                                    background: linear-gradient(135deg, {{ $theme['primary_color'] }} 0%, {{ $theme['secondary_color'] }} 100%);
                                    height: 80px;
                                    border-radius: 8px;
                                    position: relative;
                                    overflow: hidden;
                                ">
                                    <div class="theme-overlay" style="
                                        position: absolute;
                                        top: 10px;
                                        left: 10px;
                                        right: 10px;
                                        bottom: 10px;
                                        background: rgba(255,255,255,0.1);
                                        border-radius: 4px;
                                    "></div>
                                    <div class="theme-accent" style="
                                        position: absolute;
                                        top: 20px;
                                        right: 20px;
                                        width: 20px;
                                        height: 20px;
                                        background: {{ $theme['accent_color'] }};
                                        border-radius: 50%;
                                    "></div>
                                </div>
                                
                                <h6 class="card-title mb-2">{{ $theme['name'] }}</h6>
                                <p class="card-text small text-muted mb-3">{{ $theme['description'] }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="color-palette">
                                        <span class="color-dot" style="background: {{ $theme['primary_color'] }}" title="Primary"></span>
                                        <span class="color-dot" style="background: {{ $theme['secondary_color'] }}" title="Secondary"></span>
                                        <span class="color-dot" style="background: {{ $theme['accent_color'] }}" title="Accent"></span>
                                    </div>
                                    
                                    @if($currentTheme === $key)
                                        <span class="badge bg-primary">ใช้งานอยู่</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="selectTheme('{{ $key }}')">
                                            เลือก
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Theme Settings -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-sliders-h me-2"></i>ปรับแต่งธีม
                </h6>
            </div>
            <div class="card-body">
                <form id="themeForm" action="{{ route('admin.theme.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="theme" id="selectedTheme" value="{{ $currentTheme }}">
                    
                    <!-- Color Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">สีหลัก</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">สีหลัก (Primary)</label>
                            <input type="color" class="form-control form-control-color w-100" 
                                   name="primary_color" id="primaryColor" 
                                   value="{{ $themes[$currentTheme]['primary_color'] }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">สีรอง (Secondary)</label>
                            <input type="color" class="form-control form-control-color w-100" 
                                   name="secondary_color" id="secondaryColor" 
                                   value="{{ $themes[$currentTheme]['secondary_color'] }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">สีเน้น (Accent)</label>
                            <input type="color" class="form-control form-control-color w-100" 
                                   name="accent_color" id="accentColor" 
                                   value="{{ $themes[$currentTheme]['accent_color'] }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">สีข้อความ (Text)</label>
                            <input type="color" class="form-control form-control-color w-100" 
                                   name="text_color" id="textColor" 
                                   value="{{ $themes[$currentTheme]['text_color'] }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">สีพื้นหลัง (Background)</label>
                            <input type="color" class="form-control form-control-color w-100" 
                                   name="background_color" id="backgroundColor" 
                                   value="{{ $themes[$currentTheme]['background_color'] }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">สีแถบด้านข้าง (Sidebar)</label>
                            <input type="color" class="form-control form-control-color w-100" 
                                   name="sidebar_color" id="sidebarColor" 
                                   value="{{ $themes[$currentTheme]['sidebar_color'] }}">
                        </div>
                    </div>
                    
                    <!-- Typography Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">ตัวอักษร</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">ฟอนต์</label>
                            <select class="form-select" name="font_family" id="fontFamily">
                                <option value="Sarabun" {{ $themes[$currentTheme]['font_family'] === 'Sarabun' ? 'selected' : '' }}>Sarabun (ไทย)</option>
                                <option value="Inter" {{ $themes[$currentTheme]['font_family'] === 'Inter' ? 'selected' : '' }}>Inter (Modern)</option>
                                <option value="Playfair Display" {{ $themes[$currentTheme]['font_family'] === 'Playfair Display' ? 'selected' : '' }}>Playfair Display (Elegant)</option>
                                <option value="Roboto" {{ $themes[$currentTheme]['font_family'] === 'Roboto' ? 'selected' : '' }}>Roboto (Clean)</option>
                                <option value="Open Sans" {{ $themes[$currentTheme]['font_family'] === 'Open Sans' ? 'selected' : '' }}>Open Sans (Readable)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- UI Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">การออกแบบ</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">ความโค้งของมุม (Border Radius)</label>
                            <select class="form-select" name="border_radius" id="borderRadius">
                                <option value="small" {{ $themes[$currentTheme]['border_radius'] === 'small' ? 'selected' : '' }}>เล็ก (4px)</option>
                                <option value="medium" {{ $themes[$currentTheme]['border_radius'] === 'medium' ? 'selected' : '' }}>กลาง (8px)</option>
                                <option value="large" {{ $themes[$currentTheme]['border_radius'] === 'large' ? 'selected' : '' }}>ใหญ่ (12px)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">ความเข้มของเงา (Shadow)</label>
                            <select class="form-select" name="shadow_intensity" id="shadowIntensity">
                                <option value="low" {{ $themes[$currentTheme]['shadow_intensity'] === 'low' ? 'selected' : '' }}>ต่ำ</option>
                                <option value="medium" {{ $themes[$currentTheme]['shadow_intensity'] === 'medium' ? 'selected' : '' }}>กลาง</option>
                                <option value="high" {{ $themes[$currentTheme]['shadow_intensity'] === 'high' ? 'selected' : '' }}>สูง</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="mb-4">
                        <h6 class="mb-3">ตัวอย่าง</h6>
                        <div id="themePreview" class="p-3 border rounded" style="
                            background: var(--preview-bg, #f8f9fa);
                            color: var(--preview-text, #333);
                            font-family: var(--preview-font, 'Sarabun', sans-serif);
                        ">
                            <div class="d-flex align-items-center mb-3">
                                <div class="btn btn-primary me-2" style="background: var(--preview-primary, #667eea); border: none;">ปุ่มหลัก</div>
                                <div class="btn btn-secondary me-2" style="background: var(--preview-secondary, #764ba2); border: none;">ปุ่มรอง</div>
                                <div class="btn btn-success" style="background: var(--preview-accent, #28a745); border: none;">ปุ่มเน้น</div>
                            </div>
                            <div class="card" style="border-radius: var(--preview-radius, 8px); box-shadow: var(--preview-shadow, 0 2px 4px rgba(0,0,0,0.1));">
                                <div class="card-body">
                                    <h6>ตัวอย่างการ์ด</h6>
                                    <p class="mb-0">นี่คือตัวอย่างการแสดงผลของธีมที่เลือก</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>บันทึกธีม
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.theme-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.theme-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.theme-card.border-primary {
    border-color: #007bff !important;
}

.color-palette {
    display: flex;
    gap: 4px;
}

.color-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 1px solid #ddd;
}

.form-control-color {
    width: 100% !important;
    height: 40px;
    border-radius: 6px;
}

#themePreview {
    transition: all 0.3s ease;
}
</style>

<script>
function selectTheme(themeKey) {
    // Update selected theme
    document.getElementById('selectedTheme').value = themeKey;
    
    // Update form values with theme data
    const themes = @json($themes);
    const theme = themes[themeKey];
    
    document.getElementById('primaryColor').value = theme.primary_color;
    document.getElementById('secondaryColor').value = theme.secondary_color;
    document.getElementById('accentColor').value = theme.accent_color;
    document.getElementById('textColor').value = theme.text_color;
    document.getElementById('backgroundColor').value = theme.background_color;
    document.getElementById('sidebarColor').value = theme.sidebar_color;
    document.getElementById('fontFamily').value = theme.font_family;
    document.getElementById('borderRadius').value = theme.border_radius;
    document.getElementById('shadowIntensity').value = theme.shadow_intensity;
    
    // Update preview
    updatePreview();
    
    // Update active theme card
    document.querySelectorAll('.theme-card').forEach(card => {
        card.classList.remove('border-primary');
    });
    document.querySelector(`[data-theme="${themeKey}"]`).classList.add('border-primary');
}

function updatePreview() {
    const preview = document.getElementById('themePreview');
    const primaryColor = document.getElementById('primaryColor').value;
    const secondaryColor = document.getElementById('secondaryColor').value;
    const accentColor = document.getElementById('accentColor').value;
    const textColor = document.getElementById('textColor').value;
    const backgroundColor = document.getElementById('backgroundColor').value;
    const fontFamily = document.getElementById('fontFamily').value;
    const borderRadius = document.getElementById('borderRadius').value;
    const shadowIntensity = document.getElementById('shadowIntensity').value;
    
    // Set CSS variables
    preview.style.setProperty('--preview-bg', backgroundColor);
    preview.style.setProperty('--preview-text', textColor);
    preview.style.setProperty('--preview-font', fontFamily);
    preview.style.setProperty('--preview-primary', primaryColor);
    preview.style.setProperty('--preview-secondary', secondaryColor);
    preview.style.setProperty('--preview-accent', accentColor);
    
    // Set border radius
    const radiusMap = { small: '4px', medium: '8px', large: '12px' };
    preview.style.setProperty('--preview-radius', radiusMap[borderRadius]);
    
    // Set shadow
    const shadowMap = {
        low: '0 1px 2px rgba(0,0,0,0.1)',
        medium: '0 2px 4px rgba(0,0,0,0.1)',
        high: '0 4px 8px rgba(0,0,0,0.15)'
    };
    preview.style.setProperty('--preview-shadow', shadowMap[shadowIntensity]);
}

function resetTheme() {
    if (confirm('คุณแน่ใจหรือไม่ที่จะรีเซ็ตธีมเป็นค่าเริ่มต้น?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.theme.reset") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Update preview when form values change
document.addEventListener('DOMContentLoaded', function() {
    const formInputs = document.querySelectorAll('#themeForm input, #themeForm select');
    formInputs.forEach(input => {
        input.addEventListener('change', updatePreview);
    });
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection
