<?php
include("../assets/noSessionRedirect.php"); 
include('./fetch-data/verfyRoleRedirect.php');
error_reporting(0);
?>
<?php
   session_start();
   $uid = $_SESSION['id'] ?? 'owner';

   // Get pending count for sidebar badge
   include('./config.php');
   $pending_count = 0;
   $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM appointments WHERE status='pending'");
   if ($r) { $pending_count = mysqli_fetch_assoc($r)['c']; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ENSSM – Demandes de Rendez-vous</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/oranbyte-google-translator.css">
    <script src="../js/oranbyte-google-translator.js"></script>
    <style>
/* ============================================================
   ENSSM OWNER DASHBOARD — Embedded CSS (guaranteed to load)
   ============================================================ */

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }

body {
  font-family: 'Inter', 'Segoe UI', sans-serif;
  background: #f0f4f9;
  color: #374151;
  min-height: 100vh;
  overflow-x: hidden;
}

a { text-decoration: none; color: inherit; }

/* ── Layout shell ─────────────────────────────────────────── */
.dash-shell { display: flex; min-height: 100vh; }

/* ── SIDEBAR ─────────────────────────────────────────────── */
.dash-sidebar {
  width: 250px; min-width: 250px;
  background: #0d1b2a;
  display: flex; flex-direction: column;
  position: fixed; top: 0; left: 0;
  height: 100vh; z-index: 300;
  transition: transform 0.3s ease, width 0.3s ease;
  overflow: hidden;
}
.dash-sidebar.hidden { transform: translateX(-100%); }
.sidebar-brand {
  display: flex; align-items: center; gap: 12px;
  padding: 20px 18px; border-bottom: 1px solid rgba(255,255,255,0.06);
}
.brand-icon {
  width: 40px; height: 40px; border-radius: 10px;
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; color: #fff; flex-shrink: 0;
}
.brand-name { display: block; font-size: 1.15rem; font-weight: 800; color: #fff; line-height: 1.1; }
.brand-sub  { display: block; font-size: 0.68rem; color: rgba(255,255,255,0.38); letter-spacing: 0.5px; margin-top: 2px; }

.sidebar-nav {
  flex: 1; padding: 10px 10px; overflow-y: auto; overflow-x: hidden;
}
.sidebar-nav::-webkit-scrollbar { width: 3px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

.nav-label {
  font-size: 0.64rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;
  color: rgba(255,255,255,0.28); padding: 14px 10px 5px; white-space: nowrap;
}
.nav-link-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 10px; border-radius: 8px;
  color: rgba(255,255,255,0.55); font-size: 0.86rem; font-weight: 500;
  transition: all 0.2s; margin-bottom: 2px; cursor: pointer; white-space: nowrap;
}
.nav-link-item i { width: 18px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
.nav-link-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
.nav-link-item.active { background: rgba(59,130,246,0.2); color: #fff; border-left: 3px solid #3b82f6; padding-left: 7px; }
.nav-link-item.logout { color: rgba(248,113,113,0.75); }
.nav-link-item.logout:hover { background: rgba(239,68,68,0.12); color: #f87171; }

.nav-badge {
  margin-left: auto; background: #f59e0b; color: #fff;
  font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 50px;
  animation: blink 2s infinite;
}
@keyframes blink { 0%,100% {opacity:1;} 50% {opacity:0.6;} }

.sidebar-footer {
  padding: 14px 18px; border-top: 1px solid rgba(255,255,255,0.06);
  font-size: 0.78rem; color: rgba(255,255,255,0.3); display: flex; align-items: center; gap: 8px;
}

/* ── MAIN AREA ───────────────────────────────────────────── */
.dash-main {
  margin-left: 250px; flex: 1; display: flex; flex-direction: column;
  min-height: 100vh; transition: margin-left 0.3s ease;
}

/* ── TOPBAR ──────────────────────────────────────────────── */
.dash-topbar {
  height: 62px; background: #fff; border-bottom: 1px solid #e5e7eb;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 24px; position: sticky; top: 0; z-index: 200;
  box-shadow: 0 1px 8px rgba(0,0,0,0.05);
}
.topbar-left { display: flex; align-items: center; gap: 14px; }
.toggle-btn {
  width: 36px; height: 36px; border: 1px solid #e5e7eb; border-radius: 8px;
  background: #f9fafb; display: flex; align-items: center; justify-content: center;
  color: #6b7280; font-size: 0.95rem; cursor: pointer; transition: all 0.2s;
}
.toggle-btn:hover { background: #eff6ff; border-color: #bfdbfe; color: #3b82f6; }
.breadcrumb-title { font-size: 0.95rem; font-weight: 700; color: #111827; }
.breadcrumb-parent { font-size: 0.82rem; color: #9ca3af; }

.topbar-right { display: flex; align-items: center; gap: 12px; }
.date-pill {
  font-size: 0.78rem; color: #6b7280; background: #f3f4f6;
  padding: 5px 12px; border-radius: 50px; border: 1px solid #e5e7eb; white-space: nowrap;
}
.avatar-btn {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 0.85rem; cursor: pointer;
  box-shadow: 0 2px 10px rgba(59,130,246,0.35);
}

/* ── PAGE WRAPPER ────────────────────────────────────────── */
.dash-page {
  flex: 1; padding: 28px 28px 20px; max-width: 1380px; width: 100%; margin: 0 auto;
}

/* ── HEADER & STATS ──────────────────────────────────────── */
.page-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
}
.page-header h1 {
  font-size: 1.6rem; font-weight: 800; color: #111827; display: flex; align-items: center; gap: 0.5rem; margin: 0;
}
.page-header h1 i { color: #3b82f6; }
.page-header span { font-size: 0.82rem; color: #6b7280; }

.stats-row {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;
}
.kpi-card {
  background: #fff; border-radius: 14px; border: 1px solid #e5e7eb;
  padding: 20px 18px; display: flex; gap: 14px; align-items: center;
  box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.kpi-icon {
  width: 48px; height: 48px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; flex-shrink: 0;
}
.kpi-icon.blue   { background: #eff6ff; color: #3b82f6; }
.kpi-icon.yellow { background: #fffbeb; color: #d97706; }
.kpi-icon.green  { background: #f0fdf4; color: #16a34a; }
.kpi-icon.red    { background: #fef2f2; color: #dc2626; }
.kpi-info h3 { font-size: 1.6rem; font-weight: 800; line-height: 1; margin: 0; color: #111827; }
.kpi-info p  { font-size: 0.78rem; color: #6b7280; margin: 4px 0 0; font-weight: 500; }

/* ── FILTERS ─────────────────────────────────────────────── */
.filters {
  display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;
}
.filter-btn {
  padding: 0.45rem 1rem; border-radius: 50px; border: 1px solid #e5e7eb;
  background: #fff; color: #6b7280; font-size: 0.82rem; font-weight: 600;
  cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.filter-btn:hover, .filter-btn.active {
  border-color: #3b82f6; background: #eff6ff; color: #3b82f6;
}
.search-bar { flex: 1; min-width: 220px; position: relative; }
.search-bar input {
  width: 100%; padding: 0.55rem 1rem 0.55rem 2.4rem;
  border: 1px solid #e5e7eb; border-radius: 50px; font-size: 0.88rem; color: #111827;
  outline: none; transition: border-color 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.search-bar input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.search-bar i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }

/* ── TABLE ───────────────────────────────────────────────── */
.dash-card {
  background: #fff; border-radius: 14px; border: 1px solid #e5e7eb;
  padding: 0; box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden;
}
.table-responsive { overflow-x: auto; }
.n-table { width: 100%; border-collapse: collapse; }
.n-table th {
  padding: 12px 18px; font-size: 0.74rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280;
  background: #f9fafb; border-bottom: 1px solid #e5e7eb; white-space: nowrap; text-align: left;
}
.n-table td {
  padding: 14px 18px; font-size: 0.86rem; color: #374151;
  border-bottom: 1px solid #f3f4f6; vertical-align: middle;
}
.n-table tr:hover td { background: #fafbff; }

.badge-status {
  display: inline-flex; align-items: center; gap: 0.4rem;
  padding: 0.35rem 0.85rem; border-radius: 50px; font-size: 0.72rem; font-weight: 600;
}
.badge-pending  { background: #fffbeb; color: #d97706; }
.badge-approved { background: #f0fdf4; color: #16a34a; }
.badge-rejected { background: #fef2f2; color: #dc2626; }

.action-btn {
  display: inline-flex; align-items: center; gap: 0.3rem;
  padding: 0.35rem 0.65rem; border-radius: 8px; border: none;
  font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.2s; margin: 2px;
}
.btn-approve { background: #f0fdf4; color: #16a34a; }
.btn-approve:hover { background: #16a34a; color: #fff; }
.btn-reject { background: #fef2f2; color: #dc2626; }
.btn-reject:hover { background: #dc2626; color: #fff; }
.btn-view { background: #eff6ff; color: #3b82f6; }
.btn-view:hover { background: #3b82f6; color: #fff; }

.empty-state { text-align: center; padding: 4rem 2rem; color: #9ca3af; }
.empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #e5e7eb; }

/* ── TOAST & MODAL ───────────────────────────────────────── */
.toast-container { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; }
.toast-msg {
  display: flex; align-items: center; gap: 0.75rem; background: #fff;
  padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12);
  font-size: 0.88rem; animation: slideIn 0.3s ease; margin-bottom: 0.75rem;
  border-left: 4px solid #16a34a;
}
.toast-msg.error { border-left-color: #dc2626; }
@keyframes slideIn { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

.detail-row { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; flex-wrap: wrap; }
.detail-label { font-weight: 600; color: #6b7280; min-width: 140px; font-size: 0.85rem; }
.detail-value { color: #111827; font-size: 0.85rem; }

/* ── FOOTER ──────────────────────────────────────────────── */
.dash-footer {
  padding: 14px 28px; border-top: 1px solid #e5e7eb; background: #fff;
  display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #9ca3af;
}

@media (max-width: 900px) {
  .dash-sidebar { transform: translateX(-100%); }
  .dash-sidebar.visible { transform: translateX(0); }
  .dash-main { margin-left: 0; }
}
@media (max-width: 768px) {
  .stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
  .stats-row { grid-template-columns: 1fr; }
}
    </style>
</head>
<body>

<div class="dash-shell">

  <!-- ==============================================
       SIDEBAR
  ============================================== -->
  <aside class="dash-sidebar" id="dashSidebar">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="fas fa-school"></i></div>
      <div class="brand-text">
        <span class="brand-name">ENSSM</span>
        <span class="brand-sub">Portail Directeur</span>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-label">Principal</div>
      <a href="index.php" class="nav-link-item"><i class="fas fa-chart-pie"></i><span>Tableau de bord</span></a>
      <a href="appointments.php" class="nav-link-item active">
        <i class="fas fa-calendar-check"></i><span>Rendez-vous</span>
        <?php if ($pending_count > 0): ?>
          <span class="nav-badge"><?php echo $pending_count; ?></span>
        <?php endif; ?>
      </a>
      <a href="notices.php" class="nav-link-item"><i class="fas fa-bell"></i><span>Notices</span></a>
      <div class="nav-label">Gestion</div>
      <a href="teacher-list.php" class="nav-link-item"><i class="fas fa-chalkboard-teacher"></i><span>Enseignants</span></a>
      <a href="student-list.php" class="nav-link-item"><i class="fas fa-user-graduate"></i><span>Étudiants</span></a>
      <div class="nav-label">Compte</div>
      <a href="change-password.php" class="nav-link-item"><i class="fas fa-key"></i><span>Mot de passe</span></a>
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
            <span class="breadcrumb-parent" style="cursor:pointer;" onclick="location.href='index.php'">Tableau de bord</span> / Rendez-vous
        </div>
      </div>
      <div class="topbar-right">
        <div id="oranbyte-google-translator" data-default-lang="fr" data-lang-root-style="code-flag" data-lang-list-style="code-flag"></div>
        <div class="date-pill"><i class="fas fa-calendar-alt" style="margin-right:5px;"></i><?php echo date('d M Y'); ?></div>
        <div class="avatar-btn"><i class="fas fa-user-shield"></i></div>
      </div>
    </header>

    <?php
    // Fetch appointments data
    include('./config.php');
    $sql = "SELECT * FROM `appointments` ORDER BY `submitted_at` DESC";
    $result = mysqli_query($conn, $sql);
    $appointments = [];
    $total = $pending = $approved = $rejected = 0;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
            $total++;
            if ($row['status'] === 'pending')  $pending++;
            if ($row['status'] === 'approved') $approved++;
            if ($row['status'] === 'rejected') $rejected++;
        }
    }
    ?>

    <!-- PAGE -->
    <div class="dash-page">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-calendar-check"></i>Demandes de Rendez-vous</h1>
            <span><i class="fas fa-sync-alt me-1"></i>Mis à jour automatiquement</span>
        </div>

        <!-- Stats -->
        <div class="stats-row">
            <div class="kpi-card">
                <div class="kpi-icon blue"><i class="fas fa-calendar-alt"></i></div>
                <div class="kpi-info">
                    <h3><?php echo $total; ?></h3>
                    <p>Total</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon yellow"><i class="fas fa-clock"></i></div>
                <div class="kpi-info">
                    <h3><?php echo $pending; ?></h3>
                    <p>En attente</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="kpi-info">
                    <h3><?php echo $approved; ?></h3>
                    <p>Approuvés</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon red"><i class="fas fa-times-circle"></i></div>
                <div class="kpi-info">
                    <h3><?php echo $rejected; ?></h3>
                    <p>Rejetés</p>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="filters">
            <button class="filter-btn active" onclick="filterTable('all', this)">
                <i class="fas fa-list me-1"></i>Tous (<?php echo $total; ?>)
            </button>
            <button class="filter-btn" onclick="filterTable('pending', this)">
                <i class="fas fa-clock me-1"></i>En attente (<?php echo $pending; ?>)
            </button>
            <button class="filter-btn" onclick="filterTable('approved', this)">
                <i class="fas fa-check me-1"></i>Approuvés (<?php echo $approved; ?>)
            </button>
            <button class="filter-btn" onclick="filterTable('rejected', this)">
                <i class="fas fa-times me-1"></i>Rejetés (<?php echo $rejected; ?>)
            </button>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher par nom, e-mail, motif…" oninput="searchTable()">
            </div>
        </div>

        <!-- Table -->
        <div class="dash-card">
            <div class="table-responsive">
                <table class="n-table" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom complet</th>
                            <th>Contact</th>
                            <th>Motif</th>
                            <th>Date souhaitée</th>
                            <th>Soumis le</th>
                            <th>Statut</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times"></i>
                                    <h4>Aucune demande de rendez-vous</h4>
                                    <p>Les nouvelles demandes soumises apparaîtront ici.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($appointments as $i => $appt): ?>
                        <tr class="appt-row" data-status="<?php echo htmlspecialchars($appt['status']); ?>"
                            data-name="<?php echo htmlspecialchars(strtolower($appt['full_name'])); ?>"
                            data-email="<?php echo htmlspecialchars(strtolower($appt['email'])); ?>"
                            data-reason="<?php echo htmlspecialchars(strtolower($appt['reason'])); ?>">
                            <td><strong><?php echo $i + 1; ?></strong></td>
                            <td>
                                <div style="font-weight:600;"><?php echo htmlspecialchars($appt['full_name']); ?></div>
                                <div style="font-size:0.75rem;color:var(--text-muted);"><?php echo htmlspecialchars($appt['email']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($appt['phone']); ?></td>
                            <td>
                                <span style="display:inline-block;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?php echo htmlspecialchars($appt['reason']); ?>">
                                    <?php echo htmlspecialchars($appt['reason']); ?>
                                </span>
                            </td>
                            <td><i class="fas fa-calendar-day" style="color:#3b82f6;margin-right:4px;"></i><?php echo date('d/m/Y', strtotime($appt['preferred_date'])); ?></td>
                            <td><i class="fas fa-clock" style="color:#9ca3af;margin-right:4px;"></i><?php echo date('d/m/Y H:i', strtotime($appt['submitted_at'])); ?></td>
                            <td>
                                <?php
                                $statusClass = 'badge-pending'; $statusIcon = 'fas fa-clock'; $statusLabel = 'En attente';
                                if ($appt['status'] === 'approved') { $statusClass = 'badge-approved'; $statusIcon = 'fas fa-check-circle'; $statusLabel = 'Approuvé'; }
                                elseif ($appt['status'] === 'rejected') { $statusClass = 'badge-rejected'; $statusIcon = 'fas fa-times-circle'; $statusLabel = 'Rejeté'; }
                                ?>
                                <span class="badge-status <?php echo $statusClass; ?>" id="badge-<?php echo $appt['id']; ?>">
                                    <i class="<?php echo $statusIcon; ?>"></i><?php echo $statusLabel; ?>
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <!-- View Details -->
                                <button class="action-btn btn-view" onclick='showDetails(<?php echo json_encode($appt); ?>)'>
                                    <i class="fas fa-eye"></i> Voir
                                </button>
                                <!-- Approve -->
                                <?php if ($appt['status'] !== 'approved'): ?>
                                <button class="action-btn btn-approve" onclick="updateStatus(<?php echo $appt['id']; ?>, 'approved', this)">
                                    <i class="fas fa-check"></i>
                                </button>
                                <?php endif; ?>
                                <!-- Reject -->
                                <?php if ($appt['status'] !== 'rejected'): ?>
                                <button class="action-btn btn-reject" onclick="updateStatus(<?php echo $appt['id']; ?>, 'rejected', this)">
                                    <i class="fas fa-times"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /dash-page -->

    <!-- Footer -->
    <footer class="dash-footer">
        <span>© <?php echo date('Y'); ?> ENSSM — Beni Messous. Tous droits réservés.</span>
        <span>Gestion des Rendez-vous</span>
    </footer>

  </div><!-- /dash-main -->
</div><!-- /dash-shell -->

<!-- Detail Modal -->
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
            <div class="modal-footer" style="border-top:1px solid #e5e7eb;padding:16px 24px;background:#f9fafb;border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
                <button class="btn btn-secondary" style="border-radius:8px;font-weight:600;" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ── UI Script ──────────────────────────────
const sidebar = document.getElementById('dashSidebar');
const main = document.getElementById('dashMain');
const toggleBtn = document.getElementById('sideToggle');

function setSidebar(open) {
  if (open) { sidebar.classList.remove('hidden'); sidebar.classList.add('visible'); main.style.marginLeft = window.innerWidth >= 900 ? '250px' : '0'; }
  else { sidebar.classList.add('hidden'); sidebar.classList.remove('visible'); main.style.marginLeft = '0'; }
}
toggleBtn.addEventListener('click', () => { setSidebar(sidebar.classList.contains('hidden')); });
function checkBreakpoint() {
  if (window.innerWidth < 900) { sidebar.classList.add('hidden'); main.style.marginLeft = '0'; }
  else { sidebar.classList.remove('hidden'); main.style.marginLeft = '250px'; }
}
checkBreakpoint(); window.addEventListener('resize', checkBreakpoint);

function tick() {
  const n = new Date();
  const t = [n.getHours(), n.getMinutes(), n.getSeconds()].map(v => String(v).padStart(2,'0')).join(':');
  const el = document.getElementById('sideClock'); if (el) el.textContent = t;
}
tick(); setInterval(tick, 1000);

// ── Table Logic ──────────────────────────────
function filterTable(status, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.appt-row').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}

function searchTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.appt-row').forEach(row => {
        const match = row.dataset.name.includes(q) || row.dataset.email.includes(q) || row.dataset.reason.includes(q);
        row.style.display = match ? '' : 'none';
    });
}

function updateStatus(apptId, status, btn) {
    const label = status === 'approved' ? 'Approuvé' : 'Rejeté';
    if (!confirm(`Êtes-vous sûr de vouloir marquer ce rendez-vous comme "${label}" ?`)) return;

    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch('fetch-data/update-appointment-status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `appt_id=${apptId}&status=${status}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000); // Reload immediately to stay simple
        } else {
            showToast(data.message, 'error');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('Erreur réseau. Réessayez.', 'error');
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
}

function showDetails(appt) {
    const statusBadge = {
        pending:  '<span class="badge-status badge-pending"><i class="fas fa-clock me-1"></i>En attente</span>',
        approved: '<span class="badge-status badge-approved"><i class="fas fa-check-circle me-1"></i>Approuvé</span>',
        rejected: '<span class="badge-status badge-rejected"><i class="fas fa-times-circle me-1"></i>Rejeté</span>',
    };
    document.getElementById('detailModalBody').innerHTML = `
        <div class="detail-row"><span class="detail-label"><i class="fas fa-user text-primary me-2" style="color:#3b82f6;"></i>Nom complet</span><span class="detail-value">${appt.full_name}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-envelope text-primary me-2" style="color:#3b82f6;"></i>Email</span><span class="detail-value">${appt.email}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-phone text-primary me-2" style="color:#3b82f6;"></i>Téléphone</span><span class="detail-value">${appt.phone}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-tag text-primary me-2" style="color:#3b82f6;"></i>Motif</span><span class="detail-value">${appt.reason}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-calendar-day text-primary me-2" style="color:#3b82f6;"></i>Date souhaitée</span><span class="detail-value">${appt.preferred_date}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-clock text-primary me-2" style="color:#3b82f6;"></i>Soumis le</span><span class="detail-value">${appt.submitted_at}</span></div>
        <div class="detail-row"><span class="detail-label"><i class="fas fa-info-circle text-primary me-2" style="color:#3b82f6;"></i>Statut</span><span class="detail-value">${statusBadge[appt.status] || appt.status}</span></div>
        <hr style="border-top:1px solid #e5e7eb;margin:1rem 0;">
        <div><strong class="detail-label" style="display:block;margin-bottom:0.5rem;"><i class="fas fa-comment text-primary me-2" style="color:#3b82f6;"></i>Message du demandeur</strong>
        <p style="background:#f9fafb;padding:12px;border-radius:8px;border:1px solid #e5e7eb;color:#374151;font-size:0.88rem;line-height:1.6;margin:0;">${appt.message || '<em style="color:#9ca3af;">Aucun message fourni.</em>'}</p></div>
    `;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

function showToast(msg, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-msg ${type === 'error' ? 'error' : ''}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="color:${type === 'success' ? '#16a34a' : '#dc2626'}"></i> ${msg}`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
</body>
</html>
