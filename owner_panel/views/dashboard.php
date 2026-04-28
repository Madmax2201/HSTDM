<!-- DASHBOARD VIEW -->
<!-- Welcome Banner -->
<div class="welcome-wrap">
    <div class="welcome-text">
        <h1>Bonjour, Directeur 👋</h1>
        <p>Voici un aperçu de l'activité de la plateforme aujourd'hui.</p>
    </div>
    <div class="welcome-btns">
        <button class="wbtn wbtn-primary" onclick="showView('view-appointments', document.querySelector('[data-target=\'view-appointments\']'))"><i class="fas fa-calendar-check"></i>Voir les rendez-vous</button>
        <button class="wbtn wbtn-ghost" onclick="showView('view-notices', document.querySelector('[data-target=\'view-notices\']'))"><i class="fas fa-paper-plane"></i>Envoyer notice</button>
    </div>
</div>

<!-- Stats -->
<div class="stats-row">
    <div class="kpi-card" onclick="showView('view-teachers', document.querySelector('[data-target=\'view-teachers\']'))">
        <div class="kpi-icon blue"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="kpi-body">
            <span class="kpi-val"><?php echo $total_teachers; ?></span>
            <span class="kpi-label">Enseignants</span>
            <span class="kpi-link">Voir liste<i class="fas fa-arrow-right"></i></span>
        </div>
        <i class="fas fa-chalkboard-teacher kpi-bg"></i>
    </div>
    <div class="kpi-card" onclick="showView('view-students', document.querySelector('[data-target=\'view-students\']'))">
        <div class="kpi-icon violet"><i class="fas fa-user-graduate"></i></div>
        <div class="kpi-body">
            <span class="kpi-val"><?php echo $total_students; ?></span>
            <span class="kpi-label">Étudiants</span>
            <span class="kpi-link">Voir liste<i class="fas fa-arrow-right"></i></span>
        </div>
        <i class="fas fa-user-graduate kpi-bg"></i>
    </div>
    <div class="kpi-card" onclick="showView('view-notices', document.querySelector('[data-target=\'view-notices\']'))">
        <div class="kpi-icon green"><i class="fas fa-bell"></i></div>
        <div class="kpi-body">
            <span class="kpi-val"><?php echo $total_notices; ?></span>
            <span class="kpi-label">Notices envoyées</span>
            <span class="kpi-link">Gérer<i class="fas fa-arrow-right"></i></span>
        </div>
        <i class="fas fa-bell kpi-bg"></i>
    </div>
    <div class="kpi-card alert" onclick="showView('view-appointments', document.querySelector('[data-target=\'view-appointments\']'))">
        <?php if ($appt_pending > 0): ?> <div class="alert-dot"></div> <?php endif; ?>
        <div class="kpi-icon amber"><i class="fas fa-clock"></i></div>
        <div class="kpi-body">
            <span class="kpi-val"><?php echo $appt_pending; ?></span>
            <span class="kpi-label">Rendez-vous en attente</span>
            <span class="kpi-link">Traiter<i class="fas fa-arrow-right"></i></span>
        </div>
        <i class="fas fa-clock kpi-bg"></i>
    </div>
</div>

<!-- Grid -->
<div class="content-grid">
    <!-- Appointments Card -->
    <div class="dash-card">
        <div class="card-head">
            <div class="card-head-left">
                <i class="fas fa-calendar-day" style="color:#3b82f6;"></i>
                <h2>Derniers Rendez-vous</h2>
            </div>
            <a href="#appointments" class="card-head-right" onclick="showView('view-appointments', document.querySelector('[data-target=\'view-appointments\']'))">Voir tous <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card-body-scroll">
            <?php if (empty($recent_appts)): ?>
                <div class="empty-block">
                    <i class="fas fa-calendar-times"></i>
                    <p>Aucun rendez-vous récent</p>
                </div>
            <?php else: foreach ($recent_appts as $appt): ?>
                <?php
                    $bclass = 'b-pending'; $icon = 'fa-clock'; $lbl = 'En attente';
                    if ($appt['status'] === 'approved') { $bclass = 'b-approved'; $icon = 'fa-check'; $lbl = 'Approuvé'; }
                    elseif ($appt['status'] === 'rejected') { $bclass = 'b-rejected'; $icon = 'fa-times'; $lbl = 'Rejeté'; }
                ?>
                <div class="appt-item">
                    <div class="appt-avatar"><?php echo strtoupper(substr($appt['full_name'], 0, 1)); ?></div>
                    <div class="appt-info">
                        <span class="appt-name"><?php echo htmlspecialchars($appt['full_name']); ?></span>
                        <span class="appt-sub"><?php echo htmlspecialchars($appt['reason']); ?> — <?php echo date('d/m/Y', strtotime($appt['preferred_date'])); ?></span>
                    </div>
                    <div class="appt-badge <?php echo $bclass; ?>">
                        <i class="fas <?php echo $icon; ?>"></i> <?php echo $lbl; ?>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Right Column -->
    <div class="right-col">
        <!-- Overview Chart Placeholder -->
        <div class="dash-card">
            <div class="card-head">
                <div class="card-head-left">
                    <i class="fas fa-chart-donut" style="color:#3b82f6;"></i>
                    <h2>Aperçu Rendez-vous</h2>
                </div>
            </div>
            <div class="donut-area">
                <div class="donut-svg-wrap">
                    <svg viewBox="0 0 36 36">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f3f4f6" stroke-width="3.5" />
                        <?php if($appt_total > 0): 
                            $pct_app = ($appt_approved / $appt_total) * 100;
                            $pct_pen = ($appt_pending / $appt_total) * 100;
                            $pct_rej = ($appt_rejected / $appt_total) * 100;
                            $off_app = 100 - $pct_app + 25;
                        ?>
                        <path class="circle" stroke-dasharray="<?php echo $pct_app; ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#16a34a" stroke-width="3.5" stroke-linecap="round" />
                        <?php endif; ?>
                        <text x="18" y="17" text-anchor="middle" class="donut-center-total"><?php echo $appt_total; ?></text>
                        <text x="18" y="23" text-anchor="middle" class="donut-center-sub">Total</text>
                    </svg>
                </div>
                <div class="legend">
                    <div class="legend-row">
                        <div class="legend-dot ld-green"></div>
                        <span class="legend-lbl">Approuvés</span>
                        <span class="legend-num"><?php echo $appt_approved; ?></span>
                    </div>
                    <div class="legend-row">
                        <div class="legend-dot ld-amber"></div>
                        <span class="legend-lbl">Attentifs</span>
                        <span class="legend-num"><?php echo $appt_pending; ?></span>
                    </div>
                    <div class="legend-row">
                        <div class="legend-dot ld-red"></div>
                        <span class="legend-lbl">Rejetés</span>
                        <span class="legend-num"><?php echo $appt_rejected; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="dash-card">
            <div class="card-head">
                <div class="card-head-left">
                    <i class="fas fa-bolt" style="color:#3b82f6;"></i>
                    <h2>Actions Rapides</h2>
                </div>
            </div>
            <div class="qa-grid">
                <div class="qa-btn" onclick="showView('view-teachers', document.querySelector('[data-target=\'view-teachers\']'))"><i class="fas fa-chalkboard-teacher"></i>Enseignants</div>
                <div class="qa-btn" onclick="showView('view-students', document.querySelector('[data-target=\'view-students\']'))"><i class="fas fa-user-graduate"></i>Étudiants</div>
                <div class="qa-btn" onclick="showView('view-appointments', document.querySelector('[data-target=\'view-appointments\']'))"><i class="fas fa-calendar-check"></i>Rendez-vous</div>
                <div class="qa-btn" onclick="showView('view-notices', document.querySelector('[data-target=\'view-notices\']'))"><i class="fas fa-bell"></i>Notices</div>
                <div class="qa-btn" onclick="showView('view-password', document.querySelector('[data-target=\'view-password\']'))"><i class="fas fa-key"></i>Mot de passe</div>
                <div class="qa-btn" onclick="window.open('../index.php', '_blank')"><i class="fas fa-globe"></i>Site web</div>
            </div>
        </div>
    </div>
</div>

<!-- Notices Table -->
<div class="dash-card notices-card">
    <div class="card-head">
        <div class="card-head-left">
            <i class="fas fa-bullhorn" style="color:#3b82f6;"></i>
            <h2>Notices Récentes</h2>
        </div>
        <a href="#notices" class="card-head-right" onclick="showView('view-notices', document.querySelector('[data-target=\'view-notices\']'))">Gérer les notices <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="table-responsive">
        <table class="n-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Destinataires</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($recent_notices)): ?>
                <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:20px;">Aucune notice</td></tr>
                <?php else: foreach($recent_notices as $n): ?>
                <tr>
                    <td style="font-weight:600;color:#111827;"><?php echo htmlspecialchars($n['title']); ?></td>
                    <td>
                        <?php 
                        $rc = 'rt-all'; $rl = 'Tous';
                        if($n['role']=='student'){ $rc='rt-student'; $rl='Étudiants'; }
                        elseif($n['role']=='teacher'){ $rc='rt-teacher'; $rl='Enseignants'; }
                        elseif($n['role']=='admin'){ $rc='rt-admin'; $rl='Admin'; }
                        ?>
                        <span class="role-tag <?php echo $rc; ?>"><?php echo $rl; ?></span>
                    </td>
                    <td class="n-body-cell" title="<?php echo htmlspecialchars($n['body']); ?>">
                        <div style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($n['body']); ?></div>
                    </td>
                    <td class="n-date-cell"><i class="fas fa-clock" style="margin-right:4px;"></i><?php echo date('d/m/Y', strtotime($n['timestamp'])); ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
