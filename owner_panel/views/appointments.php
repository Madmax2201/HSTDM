<!-- APPOINTMENTS VIEW -->
<div class="page-header">
    <h1><i class="fas fa-calendar-check"></i>Demandes de Rendez-vous</h1>
    <span><i class="fas fa-sync-alt me-1"></i>Mis à jour automatiquement</span>
</div>

<div class="stats-row">
    <div class="kpi-card">
        <div class="kpi-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="kpi-info">
            <h3><?php echo $appt_total; ?></h3>
            <p>Total</p>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon yellow"><i class="fas fa-clock"></i></div>
        <div class="kpi-info">
            <h3><?php echo $appt_pending; ?></h3>
            <p>En attente</p>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="kpi-info">
            <h3><?php echo $appt_approved; ?></h3>
            <p>Approuvés</p>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="kpi-info">
            <h3><?php echo $appt_rejected; ?></h3>
            <p>Rejetés</p>
        </div>
    </div>
</div>

<div class="filters">
    <button class="filter-btn active" onclick="filterTable('all', this)">
        <i class="fas fa-list me-1"></i>Tous (<?php echo $appt_total; ?>)
    </button>
    <button class="filter-btn" onclick="filterTable('pending', this)">
        <i class="fas fa-clock me-1"></i>En attente (<?php echo $appt_pending; ?>)
    </button>
    <button class="filter-btn" onclick="filterTable('approved', this)">
        <i class="fas fa-check me-1"></i>Approuvés (<?php echo $appt_approved; ?>)
    </button>
    <button class="filter-btn" onclick="filterTable('rejected', this)">
        <i class="fas fa-times me-1"></i>Rejetés (<?php echo $appt_rejected; ?>)
    </button>
    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="apptsSearch" placeholder="Rechercher par nom, e-mail, motif…" oninput="searchApptsTable()">
    </div>
</div>

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
            <tbody>
                <?php if (empty($all_appointments)): ?>
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
                <?php foreach ($all_appointments as $i => $appt): ?>
                <tr class="appt-row" data-status="<?php echo htmlspecialchars($appt['status']); ?>"
                    data-name="<?php echo htmlspecialchars(strtolower($appt['full_name'])); ?>"
                    data-email="<?php echo htmlspecialchars(strtolower($appt['email'])); ?>"
                    data-reason="<?php echo htmlspecialchars(strtolower($appt['reason'])); ?>">
                    <td><strong style="color:#6b7280;"><?php echo $i + 1; ?></strong></td>
                    <td>
                        <div style="font-weight:600;color:#111827;"><?php echo htmlspecialchars($appt['full_name']); ?></div>
                        <div style="font-size:0.75rem;color:#6b7280;"><?php echo htmlspecialchars($appt['email']); ?></div>
                    </td>
                    <td><span style="color:#4b5563;"><?php echo htmlspecialchars($appt['phone']); ?></span></td>
                    <td>
                        <span style="display:inline-block;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?php echo htmlspecialchars($appt['reason']); ?>">
                            <?php echo htmlspecialchars($appt['reason']); ?>
                        </span>
                    </td>
                    <td><span class="badge-status" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-calendar-day"></i> <?php echo date('d/m/Y', strtotime($appt['preferred_date'])); ?></span></td>
                    <td><span style="color:#6b7280;font-size:0.85rem;"><i class="fas fa-clock me-1"></i><?php echo date('d/m/Y H:i', strtotime($appt['submitted_at'])); ?></span></td>
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
                        <button class="action-btn btn-view" onclick='showDetails(<?php echo json_encode($appt); ?>)'>
                            <i class="fas fa-eye"></i> Voir
                        </button>
                        <?php if ($appt['status'] !== 'approved'): ?>
                        <button class="action-btn btn-approve" onclick="updateStatus(<?php echo $appt['id']; ?>, 'approved', this)" title="Approuver">
                            <i class="fas fa-check"></i>
                        </button>
                        <?php endif; ?>
                        <?php if ($appt['status'] !== 'rejected'): ?>
                        <button class="action-btn btn-reject" onclick="updateStatus(<?php echo $appt['id']; ?>, 'rejected', this)" title="Rejeter">
                            <i class="fas fa-times"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
