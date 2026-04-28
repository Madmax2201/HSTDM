<?php
include("../assets/noSessionRedirect.php");
include('./fetch-data/verfyRoleRedirect.php');
error_reporting(0);
session_start();
$uid = $_SESSION['id'] ?? 'owner';

include('./config.php');

// ----- DASHBOARD STATS -----
$total_teachers = $total_students = $total_notices = 0;
$appt_pending = $appt_approved = $appt_rejected = $appt_total = 0;

$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='teacher'");
if ($r) $total_teachers = mysqli_fetch_assoc($r)['c'];

$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='student'");
if ($r) $total_students = mysqli_fetch_assoc($r)['c'];

$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM notice");
if ($r) $total_notices = mysqli_fetch_assoc($r)['c'];

// ----- APPOINTMENTS DATA -----
$sql = "SELECT * FROM `appointments` ORDER BY `submitted_at` DESC";
$result = mysqli_query($conn, $sql);
$all_appointments = [];
$recent_appts = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $all_appointments[] = $row;
        $appt_total++;
        if ($row['status'] === 'pending')  $appt_pending++;
        if ($row['status'] === 'approved') $appt_approved++;
        if ($row['status'] === 'rejected') $appt_rejected++;
        if (count($recent_appts) < 5) $recent_appts[] = $row;
    }
}

// ----- NOTICES DATA -----
$all_notices = [];
$recent_notices = [];
$r = mysqli_query($conn, "SELECT * FROM notice ORDER BY s_no DESC");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $all_notices[] = $row;
        if (count($recent_notices) < 5) $recent_notices[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ENSSM — Portail Directeur (CPA)</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/oranbyte-google-translator.css">
    <script src="../js/oranbyte-google-translator.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
/* ============================================================
   ENSSM OWNER DASHBOARD — Embedded CSS 
   ============================================================ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: 'Inter', sans-serif; background: #f0f4f9; color: #374151; min-height: 100vh; overflow-x: hidden; }
a { text-decoration: none; color: inherit; }

/* ── Layout shell ─────────────────────────────────────────── */
.dash-shell { display: flex; min-height: 100vh; }

/* ── SIDEBAR ─────────────────────────────────────────────── */
.dash-sidebar {
  width: 250px; min-width: 250px; background: #0d1b2a; display: flex; flex-direction: column;
  position: fixed; top: 0; left: 0; height: 100vh; z-index: 300;
  transition: transform 0.3s ease, width 0.3s ease; overflow: hidden;
}
.dash-sidebar.hidden { transform: translateX(-100%); }
.sidebar-brand { display: flex; align-items: center; gap: 12px; padding: 20px 18px; border-bottom: 1px solid rgba(255,255,255,0.06); }
.brand-icon {
  width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #60a5fa);
  display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff; flex-shrink: 0;
}
.brand-name { display: block; font-size: 1.15rem; font-weight: 800; color: #fff; line-height: 1.1; }
.brand-sub  { display: block; font-size: 0.68rem; color: rgba(255,255,255,0.38); letter-spacing: 0.5px; margin-top: 2px; }

.sidebar-nav { flex: 1; padding: 10px; overflow-y: auto; overflow-x: hidden; }
.sidebar-nav::-webkit-scrollbar { width: 3px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

.nav-label { font-size: 0.64rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: rgba(255,255,255,0.28); padding: 14px 10px 5px; white-space: nowrap; }
.nav-link-item {
  display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 8px;
  color: rgba(255,255,255,0.55); font-size: 0.86rem; font-weight: 500;
  transition: all 0.2s; margin-bottom: 2px; cursor: pointer; white-space: nowrap;
}
.nav-link-item i { width: 18px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
.nav-link-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
.nav-link-item.active { background: rgba(59,130,246,0.2); color: #fff; border-left: 3px solid #3b82f6; padding-left: 7px; }
.nav-link-item.logout { color: rgba(248,113,113,0.75); }
.nav-link-item.logout:hover { background: rgba(239,68,68,0.12); color: #f87171; }

.nav-badge { margin-left: auto; background: #f59e0b; color: #fff; font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 50px; }
.sidebar-footer { padding: 14px 18px; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.78rem; color: rgba(255,255,255,0.3); display: flex; align-items: center; gap: 8px; }

/* ── MAIN AREA ───────────────────────────────────────────── */
.dash-main { margin-left: 250px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; transition: margin-left 0.3s ease; }

/* ── TOPBAR ──────────────────────────────────────────────── */
.dash-topbar {
  height: 62px; background: #fff; border-bottom: 1px solid #e5e7eb;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 24px; position: sticky; top: 0; z-index: 200; box-shadow: 0 1px 8px rgba(0,0,0,0.05);
}
.topbar-left { display: flex; align-items: center; gap: 14px; }
.toggle-btn {
  width: 36px; height: 36px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;
  display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 0.95rem; cursor: pointer; transition: all 0.2s;
}
.toggle-btn:hover { background: #eff6ff; border-color: #bfdbfe; color: #3b82f6; }
.breadcrumb-title { font-size: 0.95rem; font-weight: 700; color: #111827; }
.breadcrumb-parent { font-size: 0.82rem; color: #9ca3af; cursor:pointer; }
.topbar-right { display: flex; align-items: center; gap: 12px; }
.date-pill { font-size: 0.78rem; color: #6b7280; background: #f3f4f6; padding: 5px 12px; border-radius: 50px; border: 1px solid #e5e7eb; white-space: nowrap; }
.avatar-btn {
  width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.85rem; cursor: pointer; box-shadow: 0 2px 10px rgba(59,130,246,0.35);
}

/* ── PAGE WRAPPER ────────────────────────────────────────── */
.dash-page { flex: 1; padding: 28px 28px 20px; max-width: 1380px; width: 100%; margin: 0 auto; display: none; animation: fadeIn 0.3s ease; }
.dash-page.active { display: block; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

/* ── COMMON STYLES (Header, Cards, Table, Filters, etc.) ─── */
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.page-header h1 { font-size: 1.6rem; font-weight: 800; color: #111827; display: flex; align-items: center; gap: 0.5rem; margin: 0; }
.page-header h1 i { color: #3b82f6; }
.page-header span { font-size: 0.82rem; color: #6b7280; }

.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.kpi-card { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; padding: 20px 18px; display: flex; gap: 14px; align-items: center; box-shadow: 0 1px 4px rgba(0,0,0,0.02); transition: all 0.25s; position:relative; overflow:hidden; cursor:pointer;}
.kpi-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.09); border-color: transparent; }
.kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
.kpi-icon.blue   { background: #eff6ff; color: #3b82f6; }
.kpi-icon.yellow { background: #fffbeb; color: #d97706; }
.kpi-icon.green  { background: #f0fdf4; color: #16a34a; }
.kpi-icon.red    { background: #fef2f2; color: #dc2626; }
.kpi-icon.violet { background: #f5f3ff; color: #7c3aed; }
.kpi-icon.amber  { background: #fffbeb; color: #d97706; }
.kpi-info h3, .kpi-val { font-size: 1.6rem; font-weight: 800; line-height: 1; margin: 0; color: #111827; display:block; }
.kpi-info p, .kpi-label { font-size: 0.78rem; color: #6b7280; margin: 4px 0 0; font-weight: 500; display:block; }
.kpi-link { display: inline-flex; align-items: center; gap: 4px; font-size: 0.74rem; font-weight: 600; color: #3b82f6; margin-top: 8px; }
.kpi-bg { position: absolute; right: -8px; bottom: -12px; font-size: 5rem; opacity: 0.04; pointer-events: none; }
.alert-dot { position: absolute; top: 12px; right: 12px; width: 9px; height: 9px; background: #f59e0b; border-radius: 50%; animation: blink 1.5s infinite; }

.filters { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.filter-btn { padding: 0.45rem 1rem; border-radius: 50px; border: 1px solid #e5e7eb; background: #fff; color: #6b7280; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
.filter-btn:hover, .filter-btn.active { border-color: #3b82f6; background: #eff6ff; color: #3b82f6; }
.search-bar { flex: 1; min-width: 220px; position: relative; }
.search-bar input { width: 100%; padding: 0.55rem 1rem 0.55rem 2.4rem; border: 1px solid #e5e7eb; border-radius: 50px; font-size: 0.88rem; color: #111827; outline: none; transition: border-color 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
.search-bar input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.search-bar i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }

.dash-card { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; padding: 0; box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 20px;}
.table-responsive { overflow-x: auto; }
.n-table { width: 100%; border-collapse: collapse; }
.n-table th { padding: 12px 18px; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; background: #f9fafb; border-bottom: 1px solid #e5e7eb; white-space: nowrap; text-align: left; }
.n-table td { padding: 14px 18px; font-size: 0.86rem; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.n-table tr:hover td { background: #fafbff; }

.badge-status { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.85rem; border-radius: 50px; font-size: 0.72rem; font-weight: 600; }
.badge-pending  { background: #fffbeb; color: #d97706; }
.badge-approved { background: #f0fdf4; color: #16a34a; }
.badge-rejected { background: #fef2f2; color: #dc2626; }

.action-btn { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.35rem 0.65rem; border-radius: 8px; border: none; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.2s; margin: 2px; }
.btn-approve { background: #f0fdf4; color: #16a34a; } .btn-approve:hover { background: #16a34a; color: #fff; }
.btn-reject { background: #fef2f2; color: #dc2626; } .btn-reject:hover { background: #dc2626; color: #fff; }
.btn-view { background: #eff6ff; color: #3b82f6; } .btn-view:hover { background: #3b82f6; color: #fff; }

.empty-state { text-align: center; padding: 4rem 2rem; color: #9ca3af; }
.empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #e5e7eb; }

/* Dashboard specifics */
.welcome-wrap { display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #0d1b2a 0%, #1e3a5f 100%); border-radius: 18px; padding: 28px 32px; margin-bottom: 24px; position: relative; overflow: hidden; gap: 16px; }
.welcome-wrap::after { content: ''; position: absolute; top: -50px; right: -50px; width: 180px; height: 180px; background: rgba(96,165,250,0.07); border-radius: 50%; pointer-events: none; }
.welcome-text h1 { font-size: 1.65rem; font-weight: 800; color: #fff; margin-bottom: 6px; }
.welcome-text p { color: rgba(255,255,255,0.55); font-size: 0.9rem; margin: 0; }
.wbtn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 50px; font-size: 0.84rem; font-weight: 600; transition: all 0.2s; cursor: pointer; border: none; z-index: 10;position: relative;}
.wbtn-primary { background: #3b82f6; color: #fff; } .wbtn-primary:hover { background: #2563eb; transform: translateY(-2px); color: #fff; }
.wbtn-ghost { background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.18); } .wbtn-ghost:hover { background: rgba(255,255,255,0.18); transform: translateY(-2px); color: #fff;}
.content-grid { display: grid; grid-template-columns: 1fr 360px; gap: 20px; margin-bottom: 20px; }
.right-col { display: flex; flex-direction: column; gap: 20px; }
.card-head { display: flex; align-items: center; justify-content: space-between; padding: 20px; border-bottom: 1px solid #f3f4f6; }
.card-head-left { display: flex; align-items: center; gap: 8px; }
.card-head-left h2 { font-size: 0.95rem; font-weight: 700; color: #111827; margin: 0; }
.card-head-right { font-size: 0.76rem; font-weight: 600; color: #3b82f6; display: flex; align-items: center; gap: 4px; }
.appt-item { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 10px; margin: 8px 16px; background: #f9fafb; border: 1px solid #f3f4f6; }
.appt-avatar { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #60a5fa); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; }
.appt-info { flex: 1; overflow: hidden; }
.appt-name { font-size: 0.86rem; font-weight: 600; color: #111827; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.appt-sub { font-size: 0.75rem; color: #9ca3af; }
.appt-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 50px; font-size: 0.72rem; font-weight: 600; white-space: nowrap; }
.b-pending { background: #fffbeb; color: #d97706; } .b-approved { background: #f0fdf4; color: #16a34a; } .b-rejected { background: #fef2f2; color: #dc2626; }
.donut-area { display: flex; align-items: center; gap: 20px; padding: 20px;}
.donut-svg-wrap { width: 120px; flex-shrink: 0; } .donut-svg-wrap svg { width: 100%; height: auto; }
.donut-center-total { font-size: 13px; font-weight: 800; fill: #111827; }
.donut-center-sub { font-size: 5px; fill: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; }
.legend { display: flex; flex-direction: column; gap: 10px; flex: 1; }
.legend-row { display: flex; align-items: center; gap: 8px; }
.legend-dot { width: 9px; height: 9px; border-radius: 50%; } .ld-green {background:#16a34a;} .ld-amber {background:#d97706;} .ld-red {background:#dc2626;}
.legend-lbl { font-size: 0.8rem; color: #6b7280; flex: 1; } .legend-num { font-size: 0.86rem; font-weight: 700; color: #111827; }
.qa-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 20px;}
.qa-btn { display: flex; flex-direction: column; align-items: center; gap: 7px; padding: 14px 8px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.73rem; font-weight: 600; color: #374151; cursor: pointer; text-align: center; }
.qa-btn i { font-size: 1.2rem; color: #3b82f6; }
.qa-btn:hover { background: #3b82f6; border-color: #3b82f6; color: #fff; transform: translateY(-3px); } .qa-btn:hover i { color:#fff; }
.role-tag { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 0.72rem; font-weight: 600; }
.rt-all { background: #eff6ff; color: #3b82f6; } .rt-student { background: #f5f3ff; color: #7c3aed; } .rt-teacher { background: #f0fdf4; color: #16a34a; } .rt-admin { background: #fffbeb; color: #d97706; }

/* ── MODAL & TOASTS ── */
.toast-container { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; }
.toast-msg { display: flex; align-items: center; gap: 0.75rem; background: #fff; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); font-size: 0.88rem; animation: slideIn 0.3s ease; margin-bottom: 0.75rem; border-left: 4px solid #16a34a; }
.toast-msg.error { border-left-color: #dc2626; }
@keyframes slideIn { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

/* FOOTER */
.dash-footer { padding: 14px 28px; border-top: 1px solid #e5e7eb; background: #fff; display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #9ca3af; margin-top: auto;}

@media (max-width: 900px) { .dash-sidebar { transform: translateX(-100%); } .dash-sidebar.visible { transform: translateX(0); } .dash-main { margin-left: 0; } }
@media (max-width: 768px) { .stats-row { grid-template-columns: repeat(2, 1fr); } .content-grid { grid-template-columns: 1fr;} }
@media (max-width: 480px) { .stats-row { grid-template-columns: 1fr; } .qa-grid {grid-template-columns: repeat(2, 1fr);} }
    </style>
</head>
<body>

<div class="dash-shell">

  <!-- ==============================================
       SIDEBAR
  ============================================== -->
  <aside class="dash-sidebar" id="dashSidebar">
    <div class="sidebar-brand" style="cursor:pointer;" onclick="showView('view-dashboard', document.querySelector('[data-target=\'view-dashboard\']'))">
      <div class="brand-icon"><i class="fas fa-school"></i></div>
      <div class="brand-text">
        <span class="brand-name">ENSSM</span>
        <span class="brand-sub">Portail Directeur</span>
      </div>
    </div>
    
    <nav class="sidebar-nav">
      <div class="nav-label">Principal</div>
      <a href="#dashboard" class="nav-link-item active" data-target="view-dashboard" onclick="showView('view-dashboard', this)"><i class="fas fa-chart-pie"></i><span>Tableau de bord</span></a>
      <a href="#appointments" class="nav-link-item" data-target="view-appointments" onclick="showView('view-appointments', this)">
          <i class="fas fa-calendar-check"></i><span>Rendez-vous</span>
          <?php if($appt_pending > 0): ?><span class="nav-badge"><?php echo $appt_pending; ?></span><?php endif; ?>
      </a>
      <a href="#notices" class="nav-link-item" data-target="view-notices" onclick="showView('view-notices', this)"><i class="fas fa-bell"></i><span>Notices</span></a>
      
      <div class="nav-label">Gestion</div>
      <a href="#teachers" class="nav-link-item" data-target="view-teachers" onclick="showView('view-teachers', this)"><i class="fas fa-chalkboard-teacher"></i><span>Enseignants</span></a>
      <a href="#students" class="nav-link-item" data-target="view-students" onclick="showView('view-students', this)"><i class="fas fa-user-graduate"></i><span>Étudiants</span></a>
      
      <div class="nav-label">Compte</div>
      <a href="#password" class="nav-link-item" data-target="view-password" onclick="showView('view-password', this)"><i class="fas fa-key"></i><span>Mot de passe</span></a>
      <a href="logout.php" class="nav-link-item logout"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
    </nav>
    <div class="sidebar-footer"><i class="fas fa-clock"></i><span id="sideClock">--:--:--</span></div>
  </aside>

  <!-- ==============================================
       MAIN CONTENT
  ============================================== -->
  <div class="dash-main" id="dashMain">

    <!-- TOPBAR -->
    <header class="dash-topbar">
      <div class="topbar-left">
        <button class="toggle-btn" id="sideToggle"><i class="fas fa-bars"></i></button>
        <div class="breadcrumb-title">
            <span class="breadcrumb-parent" onclick="showView('view-dashboard', document.querySelector('[data-target=\'view-dashboard\']'))">Portail</span> / <span id="breadcrumb-current">Tableau de bord</span>
        </div>
      </div>
      <div class="topbar-right">
        <div id="oranbyte-google-translator" data-default-lang="fr" data-lang-root-style="code-flag" data-lang-list-style="code-flag"></div>
        <div class="date-pill"><i class="fas fa-calendar-alt me-1"></i><?php echo date('d M Y'); ?></div>
        <div class="avatar-btn"><i class="fas fa-user-shield"></i></div>
      </div>
    </header>

    <!-- SPA VIEWS -->
    <div id="view-dashboard" class="dash-page active">
        <?php include 'views/dashboard.php'; ?>
    </div>
    <div id="view-appointments" class="dash-page">
        <?php include 'views/appointments.php'; ?>
    </div>
    <div id="view-notices" class="dash-page">
        <?php include 'views/notices.php'; ?>
    </div>
    <div id="view-teachers" class="dash-page">
        <?php include 'views/teachers.php'; ?>
    </div>
    <div id="view-students" class="dash-page">
        <?php include 'views/students.php'; ?>
    </div>
    <div id="view-password" class="dash-page">
        <?php include 'views/password.php'; ?>
    </div>

    <!-- Footer -->
    <footer class="dash-footer">
        <span>© <?php echo date('Y'); ?> ENSSM — Beni Messous. Tous droits réservés.</span>
        <span>Tableau de Bord</span>
    </footer>

  </div><!-- /dash-main -->
</div><!-- /dash-shell -->


<!-- MODALS -->

<!-- Modal: Détails Rendez-vous -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:#f9fafb;border-bottom:1px solid #e5e7eb;border-top-left-radius:16px;border-top-right-radius:16px;padding:16px 24px;">
                <h5 class="modal-title" style="font-weight:700;font-size:1.1rem;color:#111827;">
                    <i class="fas fa-calendar-check me-2" style="color:#3b82f6;"></i>Détails du Rendez-vous
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;" id="detailModalBody"></div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>


<!-- APP LOGIC (SPA) -->
<script>
// SPA Routing & Views
function showView(viewId, navEl) {
    // hide all
    document.querySelectorAll('.dash-page').forEach(el => {
        el.classList.remove('active');
    });
    // show active
    const target = document.getElementById(viewId);
    if(target) target.classList.add('active');
    
    // update nav styling
    if(navEl) {
        document.querySelectorAll('.nav-link-item').forEach(el => el.classList.remove('active'));
        navEl.classList.add('active');
        document.getElementById('breadcrumb-current').innerText = navEl.querySelector('span').innerText;
    }
    
    // Check if we need to load dynamic ajax data smoothly
    if (viewId === 'view-teachers' && !window.teachersLoaded) loadTeachers();
    if (viewId === 'view-students' && !window.studentsLoaded) loadStudents();
}

// Sidebar toggle & mobile handling
const sidebar = document.getElementById('dashSidebar');
const main = document.getElementById('dashMain');
const toggleBtn = document.getElementById('sideToggle');
toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
    sidebar.classList.toggle('visible');
    main.style.marginLeft = window.innerWidth >= 900 ? (sidebar.classList.contains('hidden') ? '0' : '250px') : '0';
});
window.addEventListener('resize', () => {
    if (window.innerWidth < 900) { sidebar.classList.add('hidden'); main.style.marginLeft = '0'; }
    else { sidebar.classList.remove('hidden'); main.style.marginLeft = '250px'; }
});
if (window.innerWidth < 900) { sidebar.classList.add('hidden'); main.style.marginLeft = '0'; }

// Clock
setInterval(() => {
    const t = new Date(), p = v => String(v).padStart(2,'0');
    document.getElementById('sideClock').textContent = `${p(t.getHours())}:${p(t.getMinutes())}:${p(t.getSeconds())}`;
}, 1000);

// On load Hash Check
window.addEventListener('load', () => {
    const h = window.location.hash;
    if(h && h.length > 1) {
        const trg = 'view-' + h.substring(1);
        const el = document.querySelector(`[data-target="${trg}"]`);
        if(document.getElementById(trg)) showView(trg, el);
    }
});

// Toast Utility
function showToast(msg, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-msg ${type === 'error' ? 'error' : ''}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="color:${type === 'success' ? '#16a34a' : '#dc2626'}"></i> ${msg}`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// ── APPOINTMENTS LOGIC ──
function filterTable(status, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.appt-row').forEach(row => { row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none'; });
}
function searchApptsTable() {
    const q = document.getElementById('apptsSearch').value.toLowerCase();
    document.querySelectorAll('.appt-row').forEach(row => {
        row.style.display = (row.dataset.name.includes(q) || row.dataset.email.includes(q) || row.dataset.reason.includes(q)) ? '' : 'none';
    });
}
function updateStatus(id, status, btn) {
    if (!confirm(`Êtes-vous sûr de vouloir marquer ce rendez-vous comme "Approuvé/Rejeté" ?`)) return;
    const og = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    $.post('fetch-data/update-appointment-status.php', {appt_id: id, status: status})
     .done(res => {
         let r = JSON.parse(res);
         if(r.success) { showToast(r.message); setTimeout(()=>location.reload(), 800); }
         else { showToast(r.message, 'error'); btn.innerHTML=og; btn.disabled=false; }
     }).fail(() => { showToast('Erreur serveur', 'error'); btn.innerHTML=og; btn.disabled=false; });
}
function showDetails(appt) {
    const bgs = { pending:'<span class="badge-status badge-pending">En attente</span>', approved:'<span class="badge-status badge-approved">Approuvé</span>', rejected:'<span class="badge-status badge-rejected">Rejeté</span>' };
    document.getElementById('detailModalBody').innerHTML = `
        <div class="detail-row"><span class="detail-label"><i class="fas fa-user text-primary me-2"></i>Nom</span><span class="detail-value">${appt.full_name}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-envelope text-primary me-2"></i>Email</span><span class="detail-value">${appt.email}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-phone text-primary me-2"></i>Téléphone</span><span class="detail-value">${appt.phone}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-tag text-primary me-2"></i>Motif</span><span class="detail-value">${appt.reason}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-calendar text-primary me-2"></i>Date</span><span class="detail-value">${appt.preferred_date}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-info-circle text-primary me-2"></i>Statut</span><span class="detail-value">${bgs[appt.status]}</span></div>
        <hr style="margin:1rem 0;">
        <strong>Message du demandeur</strong>
        <p style="background:#f9fafb;padding:12px;border-radius:8px;font-size:0.88rem;margin-top:0.5rem;">${appt.message || 'Aucun message.'}</p>
    `;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

// ── NOTICES LOGIC ──
function checkNoticeClass() {
    document.getElementById('noticeClassWrap').style.display = document.getElementById('noticeRole').value === 'student' ? 'block' : 'none';
}
function sendNotice(btn) {
    const role = $('#noticeRole').val();
    const cla = $('#noticeClass').val();
    const title = $('#noticeTitle').val();
    const msg = $('#noticeMessage').val();
    if(!title || !msg) return showToast("Veuillez remplir le titre et le message", 'error');
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btn.disabled = true;
    $.post('fetch-data/send-notice.php', { panel: role, cla: cla, title: title, message: msg })
     .done(res => { showToast("Notice envoyée !"); setTimeout(()=>location.reload(), 1000); })
     .fail(() => { showToast('Erreur serveur', 'error'); btn.innerHTML = 'Envoyer'; btn.disabled = false;});
}
function deleteNotice(id) {
    if(confirm('Supprimer cette notice ?')) {
        $.post('fetch-data/notice-delete.php', { noticeId: id })
         .done(res => { showToast("Notice supprimée"); setTimeout(()=>location.reload(), 800); })
         .fail(() => showToast("Erreur lors de la suppression", 'error'));
    }
}

// ── TEACHERS LOGIC ──
window.teachersLoaded = false;
function loadTeachers() {
    $.post('fetch-data/fetch-teachers.php', function(data) {
        $('#tb-teachers').html(data);
        window.teachersLoaded = true;
    });
}
function searchTeachers() {
    const v = $('#searchTeacher').val();
    $.post('fetch-data/search-teacher.php', {search: v}, data => $('#tb-teachers').html(data) );
}

// ── STUDENTS LOGIC ──
window.studentsLoaded = false;
function loadStudents() {
    $.post('fetch-data/fetch-student.php', function(data) {
        $('#tb-students').html(data);
        window.studentsLoaded = true;
    });
}
function searchStudents() {
    const v = $('#searchStudent').val();
    $.post('fetch-data/search-student.php', {search: v}, data => $('#tb-students').html(data) );
}
function filterStudentsByClass() {
    const v = $('#filterStudentClass').val();
    $.post('fetch-data/select-students.php', {select: v}, data => $('#tb-students').html(data) );
}

// ── PASSWORD CHANGE LOGIC ──
function changePassword(e) {
    e.preventDefault();
    const current = $('#cp_current').val();
    const newpw = $('#cp_new').val();
    const rept = $('#cp_repeat').val();
    const btn = $('#cp_btn');
    
    if (newpw !== rept) return showToast("Les nouveaux mots de passe ne correspondent pas", "error");
    
    btn.html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...').prop('disabled', true);
    
    $.post('fetch-data/update-password.php', { current: current, new: newpw, repeat: rept })
     .done(res => {
         try {
             const r = JSON.parse(res);
             showToast(r.message, r.success ? 'success' : 'error');
             if(r.success) document.getElementById('formChangePassword').reset();
         } catch(e) { showToast('Erreur serveur inattendue', 'error'); }
     })
     .fail(() => showToast('Erreur réseau', 'error'))
     .always(() => btn.html('<i class="fas fa-save me-2"></i>Mettre à jour').prop('disabled', false));
}
</script>

</body>
</html>