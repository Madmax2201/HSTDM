<?php
session_start();
require_once '../assets/config.php';

// Specializations for the filter dropdown
$specialties = [
    ['id' => 1, 'ar' => 'إعلام آلي', 'en' => 'Computer Science'],
    ['id' => 2, 'ar' => 'رياضيات', 'en' => 'Mathematics'],
    ['id' => 3, 'ar' => 'علوم طبيعية', 'en' => 'Natural Sciences'],
    ['id' => 4, 'ar' => 'علوم فيزيائية', 'en' => 'Physical Sciences'],
    ['id' => 5, 'ar' => 'علوم اقتصادية', 'en' => 'Economics'],
    ['id' => 6, 'ar' => 'أدب عربي', 'en' => 'Arabic Literature'],
    ['id' => 7, 'ar' => 'لغة فرنسية', 'en' => 'French Language'],
    ['id' => 8, 'ar' => 'لغة إنجليزية', 'en' => 'English Language'],
    ['id' => 9, 'ar' => 'فنون', 'en' => 'Arts'],
    ['id' => 10, 'ar' => 'موسيقى', 'en' => 'Music'],
    ['id' => 11, 'ar' => 'تربية بدنية', 'en' => 'Physical Education'],
    ['id' => 12, 'ar' => 'فلسفة', 'en' => 'Philosophy'],
    ['id' => 13, 'ar' => 'أمازيغية', 'en' => 'Amazigh'],
    ['id' => 14, 'ar' => 'علوم شرعية', 'en' => 'Islamic Sciences'],
    ['id' => 15, 'ar' => 'تاريخ وجغرافيا', 'en' => 'History & Geography']
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="title">لوحة المؤشرات التفاعلية | Premium Dashboard</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" id="bs-css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #1e40af;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
            --card-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        html[dir="rtl"] body { font-family: 'Tajawal', sans-serif; }
        html[dir="ltr"] body { font-family: 'Inter', sans-serif; }

        /* Premium Glassmorphism */
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
            overflow: hidden;
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12);
        }

        .navbar {
            background: rgba(15, 23, 42, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-brand {
            font-weight: 800;
            background: linear-gradient(to right, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--secondary);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .progress-circle-container {
            width: 120px;
            height: 120px;
            position: relative;
        }

        .badge-premium {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        /* Chart Styling */
        .chart-container { position: relative; height: 300px; width: 100%; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade { animation: fadeIn 0.6s ease forwards; }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-value { font-size: 1.75rem; }
        }
    </style>
</head>
<body>

    <!-- Premium Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-5">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="dashboard.php">
                <i class='bx bxs-pie-chart-alt-2 fs-3'></i>
                <span data-i18n="nav_title">ENSSM | التحليلات الذكية</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <button class="btn btn-outline-light border-0 rounded-pill px-4 fw-bold" onclick="toggleLanguage()" id="langBtn">
                    <i class='bx bx-world me-1'></i> English
                </button>
                <div class="vr bg-light opacity-25 mx-2"></div>
                <img src="../assets/images/user.png" alt="Admin" width="40" class="rounded-circle border border-2 border-primary p-1" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=3b82f6&color=fff'">
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        <!-- Header & Filters -->
        <div class="row align-items-center mb-5 animate-fade">
            <div class="col-lg-6">
                <h1 class="fw-800 text-dark mb-2" data-i18n="page_header">لوحة قياس الأداء التعليمي</h1>
                <p class="text-secondary mb-0" data-i18n="page_subheader">مراقبة حية للمؤشرات الرئيسية والإحصائيات الجامعية لعام 2024</p>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="glass-card p-3 d-flex flex-wrap gap-3 align-items-center justify-content-lg-end">
                    <select id="filter_specialty" class="form-select border-0 shadow-none bg-light rounded-pill px-4 py-2" style="width: 200px;" onchange="fetchData()">
                        <option value="all" data-i18n="all_specialties">جميع التخصصات</option>
                        <?php foreach($specialties as $spec): ?>
                            <option value="<?= $spec['id'] ?>" class="lang-text" data-ar="<?= $spec['ar'] ?>" data-en="<?= $spec['en'] ?>"><?= $spec['ar'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filter_semester" class="form-select border-0 shadow-none bg-light rounded-pill px-4 py-2" style="width: 180px;" onchange="fetchData()">
                        <option value="all" data-i18n="all_semesters">جميع السداسيات</option>
                        <option value="1" data-i18n="semester_1">السداسي الأول</option>
                        <option value="2" data-i18n="semester_2">السداسي الثاني</option>
                    </select>
                    <a href="dashboard.php" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        <i class='bx bx-grid-alt me-1'></i> <span data-i18n="back_btn">الرئيسية</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 animate-fade" style="animation-delay: 0.1s;">
                <div class="glass-card p-4 h-100 border-start border-primary border-4">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class='bx bxs-group'></i>
                    </div>
                    <div class="stat-value text-primary" id="val_students">0</div>
                    <div class="stat-label" data-i18n="total_students">إجمالي الطلبة</div>
                    <div class="mt-3 pt-3 border-top d-flex justify-content-between">
                        <small class="text-secondary">M1: <b id="val_m1">408</b></small>
                        <small class="text-secondary">M2: <b id="val_m2">257</b></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 animate-fade" style="animation-delay: 0.2s;">
                <div class="glass-card p-4 h-100 border-start border-success border-4">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class='bx bxs-graduation'></i>
                    </div>
                    <div class="stat-value text-success" id="val_teachers">0</div>
                    <div class="stat-label" data-i18n="total_teachers">هيئة التدريس</div>
                    <div class="mt-3 pt-3 border-top text-center">
                        <small class="text-secondary"><i class='bx bx-trending-up'></i> <span data-i18n="active_status">حالة نشطة</span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 animate-fade" style="animation-delay: 0.3s;">
                <div class="glass-card p-4 h-100 border-start border-warning border-4">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class='bx bxs-user-detail'></i>
                    </div>
                    <div class="stat-value text-warning" id="val_staff">0</div>
                    <div class="stat-label" data-i18n="total_staff">الطاقم الإداري</div>
                    <div class="mt-3 pt-3 border-top text-center">
                        <small class="text-secondary"><i class='bx bx-check-shield'></i> <span data-i18n="full_capacity">كفاءة تشغيلية</span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 animate-fade" style="animation-delay: 0.4s;">
                <div class="glass-card p-4 h-100 border-start border-info border-4">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class='bx bxs-building-house'></i>
                    </div>
                    <div class="stat-value text-info" id="val_infrastructure">0</div>
                    <div class="stat-label" data-i18n="infrastructure">البنية التحتية</div>
                    <div class="mt-3 pt-3 border-top d-flex justify-content-between">
                        <small class="text-secondary"><span data-i18n="classrooms">قاعة</span>: <b id="val_classrooms">20</b></small>
                        <small class="text-secondary"><span data-i18n="halls">مدرج</span>: <b id="val_halls">2</b></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visual Analytics Row -->
        <div class="row g-4 mb-5">
            <!-- Specialty Distribution -->
            <div class="col-lg-5 animate-fade" style="animation-delay: 0.5s;">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0" data-i18n="spec_chart_title">توزع التخصصات</h5>
                        <i class='bx bx-dots-vertical-rounded text-secondary'></i>
                    </div>
                    <div class="chart-container">
                        <canvas id="specialtyChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Success Rate Overview -->
            <div class="col-lg-7 animate-fade" style="animation-delay: 0.6s;">
                <div class="glass-card p-4 h-100 bg-dark text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                    <div class="row align-items-center h-100">
                        <div class="col-md-6 text-center">
                            <h2 class="display-3 fw-800 text-success mb-0" id="val_passrate">97%</h2>
                            <p class="text-light opacity-50 mb-4" data-i18n="overall_success">نسبة النجاح العامة</p>
                            <div class="d-flex justify-content-center gap-3">
                                <div class="badge-premium bg-success bg-opacity-20 text-success border border-success border-opacity-50">
                                    <i class='bx bx-up-arrow-alt'></i> +3.2%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-600" data-i18n="passed_count">الناجحين</span>
                                    <span class="text-success fw-800" id="val_general_pass">645</span>
                                </div>
                                <div class="progress bg-white bg-opacity-10" style="height: 10px; border-radius: 5px;">
                                    <div class="progress-bar bg-success rounded-pill" id="bar_general_pass" style="width: 97%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-600" data-i18n="failed_count">الراسبين</span>
                                    <span class="text-danger fw-800" id="val_general_fail">20</span>
                                </div>
                                <div class="progress bg-white bg-opacity-10" style="height: 10px; border-radius: 5px;">
                                    <div class="progress-bar bg-danger rounded-pill" id="bar_general_fail" style="width: 3%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Stats Tables -->
        <div class="row g-4">
            <div class="col-lg-8 animate-fade" style="animation-delay: 0.7s;">
                <div class="glass-card">
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0" data-i18n="subject_stats_title">تحليلات المواد الدراسية</h5>
                        <button class="btn btn-light btn-sm rounded-pill px-3"><i class='bx bx-export me-1'></i> Export</button>
                    </div>
                    <div class="p-4">
                        <canvas id="subjectChart" style="height: 350px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 animate-fade" style="animation-delay: 0.8s;">
                <div class="glass-card">
                    <div class="p-4 border-bottom">
                        <h5 class="fw-bold mb-0" data-i18n="recent_students">قائمة الطلبة المميزين</h5>
                    </div>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0 text-secondary" style="font-size: 0.75rem;" data-i18n="student_name">الطالب</th>
                                    <th class="py-3 border-0 text-secondary" style="font-size: 0.75rem;" data-i18n="specialty">التخصص</th>
                                </tr>
                            </thead>
                            <tbody id="student_list_tbody">
                                <!-- JS Populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const translations = {
            "title": { ar: "لوحة المؤشرات التفاعلية | Premium Dashboard", en: "Interactive KPI | Premium Dashboard" },
            "nav_title": { ar: "ENSSM | التحليلات الذكية", en: "ENSSM | Smart Analytics" },
            "page_header": { ar: "لوحة قياس الأداء التعليمي", en: "Educational Performance Dashboard" },
            "page_subheader": { ar: "مراقبة حية للمؤشرات الرئيسية والإحصائيات الجامعية لعام 2024", en: "Live monitoring of key indicators and university stats for 2024" },
            "all_specialties": { ar: "جميع التخصصات", en: "All Specialties" },
            "all_semesters": { ar: "جميع السداسيات", en: "All Semesters" },
            "semester_1": { ar: "السداسي الأول", en: "Semester 1" },
            "semester_2": { ar: "السداسي الثاني", en: "Semester 2" },
            "back_btn": { ar: "الرئيسية", en: "Home" },
            "total_students": { ar: "إجمالي الطلبة", en: "Total Students" },
            "total_teachers": { ar: "هيئة التدريس", en: "Academic Faculty" },
            "total_staff": { ar: "الطاقم الإداري", en: "Administrative Staff" },
            "infrastructure": { ar: "البنية التحتية", en: "Infrastructure" },
            "classrooms": { ar: "قاعة", en: "Classrooms" },
            "halls": { ar: "مدرج", en: "Halls" },
            "active_status": { ar: "حالة نشطة", en: "Active Status" },
            "full_capacity": { ar: "كفاءة تشغيلية", en: "Operational Capacity" },
            "spec_chart_title": { ar: "توزع التخصصات", en: "Specialties Distribution" },
            "overall_success": { ar: "نسبة النجاح العامة", en: "Overall Success Rate" },
            "passed_count": { ar: "الناجحين", en: "Passed" },
            "failed_count": { ar: "الراسبين", en: "Failed" },
            "subject_stats_title": { ar: "تحليلات المواد الدراسية", en: "Subject Analytics Overview" },
            "recent_students": { ar: "قائمة الطلبة", en: "Students Roster" },
            "student_name": { ar: "اسم الطالب", en: "Student Name" },
            "specialty": { ar: "التخصص", en: "Specialty" }
        };

        let currentLang = 'ar';
        let specChartInstance = null;
        let subjChartInstance = null;

        function toggleLanguage() {
            currentLang = currentLang === 'ar' ? 'en' : 'ar';
            const htmlTag = document.getElementById('html-tag');
            const bsCss = document.getElementById('bs-css');
            const langBtn = document.getElementById('langBtn');

            if (currentLang === 'en') {
                htmlTag.setAttribute('dir', 'ltr');
                htmlTag.setAttribute('lang', 'en');
                bsCss.setAttribute('href', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
                langBtn.innerHTML = "<i class='bx bx-world me-1'></i> العربية";
            } else {
                htmlTag.setAttribute('dir', 'rtl');
                htmlTag.setAttribute('lang', 'ar');
                bsCss.setAttribute('href', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css');
                langBtn.innerHTML = "<i class='bx bx-world me-1'></i> English";
            }

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if(translations[key]) el.innerText = translations[key][currentLang];
            });

            document.querySelectorAll('.lang-text').forEach(el => {
                el.innerText = el.getAttribute('data-' + currentLang);
            });
            
            fetchData();
        }

        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            if (!obj) return;
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerText = Math.floor(progress * (end - start) + start);
                if (progress < 1) window.requestAnimationFrame(step);
            };
            window.requestAnimationFrame(step);
        }

        function fetchData() {
            const specId = document.getElementById('filter_specialty').value;
            const semId = document.getElementById('filter_semester').value;
            fetch(`api_kpi.php?specialty_id=${specId}&semester_id=${semId}`)
                .then(res => res.json())
                .then(data => {
                    animateValue("val_students", 0, data.totalStudents, 1200);
                    animateValue("val_teachers", 0, data.totalTeachers, 1200);
                    animateValue("val_staff", 0, data.totalStaff, 1200);
                    animateValue("val_infrastructure", 0, data.totalHalls + data.totalClassrooms, 1200);
                    
                    document.getElementById("val_passrate").innerText = (Math.round(data.generalPass / (data.generalPass + data.generalFail) * 100)) + "%";
                    animateValue("val_general_pass", 0, data.generalPass, 1200);
                    animateValue("val_general_fail", 0, data.generalFail, 1200);
                    
                    const passPct = (data.generalPass / (data.generalPass + data.generalFail) * 100);
                    document.getElementById("bar_general_pass").style.width = passPct + "%";
                    document.getElementById("bar_general_fail").style.width = (100 - passPct) + "%";

                    updateCharts(data);

                    const studentTbody = document.getElementById("student_list_tbody");
                    studentTbody.innerHTML = "";
                    data.studentsList.forEach(st => {
                        studentTbody.innerHTML += `
                            <tr>
                                <td class="px-4 py-3"><div class="fw-bold">${currentLang === 'ar' ? st.name_ar : st.name_en}</div></td>
                                <td class="py-3"><span class="badge-premium bg-primary bg-opacity-10 text-primary">${currentLang === 'ar' ? st.spec_ar : st.spec_en}</span></td>
                            </tr>
                        `;
                    });
                });
        }

        function updateCharts(data) {
            const ctx1 = document.getElementById('specialtyChart').getContext('2d');
            if (specChartInstance) specChartInstance.destroy();
            specChartInstance = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: data.studentsPerSpec.map(s => currentLang === 'ar' ? s.name_ar : s.name_en),
                    datasets: [{
                        data: data.studentsPerSpec.map(s => s.count),
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6', '#ec4899'],
                        borderWidth: 0,
                        hoverOffset: 20
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { family: currentLang === 'ar' ? 'Tajawal' : 'Inter' } } } },
                    cutout: '70%'
                }
            });

            const ctx2 = document.getElementById('subjectChart').getContext('2d');
            if (subjChartInstance) subjChartInstance.destroy();
            subjChartInstance = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: data.subjectPassFail.map(s => s.name),
                    datasets: [
                        { label: translations['passed_count'][currentLang], data: data.subjectPassFail.map(s => s.passed), backgroundColor: '#10b981', borderRadius: 8 },
                        { label: translations['failed_count'][currentLang], data: data.subjectPassFail.map(s => s.failed), backgroundColor: '#ef4444', borderRadius: 8 }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { position: 'top', align: 'end' } }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', fetchData);
    </script>
</body>
</html>
